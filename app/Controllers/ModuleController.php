<?php
namespace App\Controllers;

use App\Models\ModuleModel;
use App\Models\ProgrammeModel;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ModuleController
{
    public function __construct(
        private ModuleModel $model,
        private ProgrammeModel $progModel,
        private PhpRenderer $renderer
    ) {}

    private function flash(string $key, string $msg): void { $_SESSION['flash'][$key] = $msg; }
    private function getFlash(): array { $f = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $f; }
    private function clean(mixed $v): string { return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8'); }

    public function adminIndex(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/modules.php', [
            'modules' => $this->model->getAll(),
            'flash'   => $this->getFlash(),
        ]);
    }

    public function create(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/module-form.php', [
            'module'      => null,
            'programmes'  => $this->model->getAllProgrammes(),
        ]);
    }

    public function store(Request $req, Response $res): Response
    {
        $d = $req->getParsedBody();
        $this->model->create([
            'title'         => $this->clean($d['title'] ?? ''),
            'description'   => $this->clean($d['description'] ?? ''),
            'year_of_study' => (int)($d['year_of_study'] ?? 1),
        ]);
        $this->flash('success', 'Module created.');
        return $res->withHeader('Location', base_url('/admin/modules'))->withStatus(302);
    }

    public function edit(Request $req, Response $res, array $args): Response
    {
        return $this->renderer->render($res, 'admin/module-form.php', [
            'module'     => $this->model->findById((int)$args['id']),
            'programmes' => $this->model->getAllProgrammes(),
        ]);
    }

    public function update(Request $req, Response $res, array $args): Response
    {
        $d = $req->getParsedBody();
        $this->model->update((int)$args['id'], [
            'title'         => $this->clean($d['title'] ?? ''),
            'description'   => $this->clean($d['description'] ?? ''),
            'year_of_study' => (int)($d['year_of_study'] ?? 1),
        ]);
        $this->flash('success', 'Module updated.');
        return $res->withHeader('Location', base_url('/admin/modules'))->withStatus(302);
    }

    public function destroy(Request $req, Response $res, array $args): Response
    {
        $this->model->delete((int)$args['id']);
        return $res->withHeader('Location', base_url('/admin/modules'))->withStatus(302);
    }
}
