<?php
namespace App\Controllers;

use App\Models\InterestModel;
use App\Models\ProgrammeModel;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterestController
{
    public function __construct(
        private InterestModel $model,
        private ProgrammeModel $progModel,
        private PhpRenderer $renderer
    ) {}

    private function clean(mixed $v): string { return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8'); }

    public function showForm(Request $req, Response $res, array $args): Response
    {
        $prog = $this->progModel->findById((int)$args['id']);
        if (!$prog) {
            $res = $res->withStatus(404)->withHeader('Content-Type', 'text/html');
            $res->getBody()->write('Not found');
            return $res;
        }
        return $this->renderer->render($res, 'student/register-interest.php', ['prog' => $prog, 'errors' => [], 'success' => false]);
    }

    public function register(Request $req, Response $res): Response
    {
        $d = $req->getParsedBody();
        $progId = (int)($d['programme_id'] ?? 0);
        $prog   = $this->progModel->findById($progId);
        $errors = [];

        $firstName = $this->clean($d['first_name'] ?? '');
        $lastName  = $this->clean($d['last_name'] ?? '');
        $email     = filter_var(trim($d['email'] ?? ''), FILTER_VALIDATE_EMAIL);

        if (!$firstName) $errors[] = 'First name is required.';
        if (!$lastName)  $errors[] = 'Last name is required.';
        if (!$email)     $errors[] = 'A valid email address is required.';

        if ($errors) {
            return $this->renderer->render($res, 'student/register-interest.php', [
                'prog' => $prog, 'errors' => $errors, 'success' => false,
            ]);
        }

        $registered = $this->model->register([
            'first_name'   => $firstName,
            'last_name'    => $lastName,
            'email'        => $email,
            'programme_id' => $progId,
        ]);

        return $this->renderer->render($res, 'student/register-interest.php', [
            'prog'    => $prog,
            'errors'  => $registered ? [] : ['You are already registered for this programme.'],
            'success' => $registered,
        ]);
    }

    public function withdraw(Request $req, Response $res, array $args): Response
    {
        $ok = $this->model->withdraw($args['token']);
        return $this->renderer->render($res, 'student/withdraw.php', ['success' => $ok]);
    }

    public function adminList(Request $req, Response $res, array $args): Response
    {
        $prog = $this->progModel->findById((int)$args['pid']);
        return $this->renderer->render($res, 'admin/interests.php', [
            'prog'      => $prog,
            'interests' => $this->model->findByProgramme((int)$args['pid']),
        ]);
    }

    public function exportCsv(Request $req, Response $res, array $args): Response
    {
        $rows = $this->model->findByProgramme((int)$args['pid']);
        $prog = $this->progModel->findById((int)$args['pid']);
        $filename = 'interests-' . preg_replace('/[^a-z0-9]+/i', '-', $prog['title'] ?? 'export') . '.csv';

        $res = $res->withHeader('Content-Type', 'text/csv')
                   ->withHeader('Content-Disposition', "attachment; filename=\"$filename\"");
        $body = $res->getBody();
        $body->write("First Name,Last Name,Email,Registered At\r\n");
        foreach ($rows as $r) {
            $body->write(implode(',', [
                '"' . str_replace('"', '""', $r['first_name']) . '"',
                '"' . str_replace('"', '""', $r['last_name']) . '"',
                '"' . str_replace('"', '""', $r['email']) . '"',
                '"' . $r['registered_at'] . '"',
            ]) . "\r\n");
        }
        return $res;
    }

    public function adminDelete(Request $req, Response $res, array $args): Response
    {
        $this->model->delete((int)$args['id']);
        return $res->withHeader('Location', $_SERVER['HTTP_REFERER'] ?? base_url('/admin/programmes'))->withStatus(302);
    }
}
