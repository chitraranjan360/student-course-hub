<?php
namespace App\Controllers;

use App\Models\InterestModel;
use App\Models\ProgrammeModel;
use PHPMailer\PHPMailer\Exception as MailException;
use PHPMailer\PHPMailer\PHPMailer;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class InterestController
{
    public function __construct(
        private InterestModel $model,
        private ProgrammeModel $progModel,
        private PhpRenderer $renderer,
        private array $mailConfig
    ) {}

    private function clean(mixed $v): string { return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8'); }
    private function flash(string $key, string $message): void { $_SESSION['flash'][$key] = $message; }
    private function getFlash(): array { $flash = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $flash; }

    private function redirectToInterests(Response $res): Response
    {
        return $res->withHeader('Location', base_url('/admin/interests'))->withStatus(302);
    }

    private function cleanText(mixed $value): string
    {
        return trim((string) $value);
    }

    private function sendEmail(array $to, string $subject, string $body, bool $useBcc = false): void
    {
        $cfg = $this->mailConfig;

        $mailer = new PHPMailer(true);
        $mailer->isSMTP();
        $mailer->Host = (string) ($cfg['host'] ?? '');
        $mailer->Port = (int) ($cfg['port'] ?? 587);
        $mailer->SMTPAuth = true;
        $mailer->Username = (string) ($cfg['username'] ?? '');
        $mailer->Password = (string) ($cfg['password'] ?? '');

        $encryption = strtolower((string) ($cfg['encryption'] ?? 'tls'));
        if ($encryption === 'ssl') {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($encryption === 'tls') {
            $mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        }

        $fromEmail = (string) ($cfg['from_email'] ?? $cfg['username'] ?? '');
        $fromName = (string) ($cfg['from_name'] ?? 'Student Course Hub');
        $mailer->setFrom($fromEmail, $fromName);

        foreach ($to as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if ($useBcc) {
                    $mailer->addBCC($email);
                } else {
                    $mailer->addAddress($email);
                }
            }
        }

        if (!$useBcc && count($mailer->getToAddresses()) === 0) {
            throw new MailException('No valid recipient email addresses found.');
        }
        if ($useBcc && count($mailer->getBccAddresses()) === 0) {
            throw new MailException('No valid recipient email addresses found.');
        }

        $mailer->isHTML(false);
        $mailer->Subject = $subject;
        $mailer->Body = $body;
        $mailer->send();
    }

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

    public function adminAll(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/interests-all.php', [
            'interests' => $this->model->findAllWithProgramme(),
            'flash'     => $this->getFlash(),
        ]);
    }

    public function sendProgrammeMail(Request $req, Response $res): Response
    {
        $data = $req->getParsedBody() ?? [];
        $programmeId = (int) ($data['programme_id'] ?? 0);
        $programme = $this->progModel->findById($programmeId);

        if (!$programme) {
            $this->flash('error', 'Invalid programme selected.');
            return $this->redirectToInterests($res);
        }

        $recipients = $this->model->listEmailsByProgramme($programmeId);
        if (empty($recipients)) {
            $this->flash('error', 'No registered students found for the selected programme.');
            return $this->redirectToInterests($res);
        }

        $subject = $this->cleanText($data['subject'] ?? ('Update for ' . $programme['title'] . ' Applicants'));
        $body = $this->cleanText($data['body'] ?? ('Dear student,' . PHP_EOL . PHP_EOL . 'This is an update regarding ' . $programme['title'] . '.' . PHP_EOL . PHP_EOL . 'Regards,' . PHP_EOL . 'Admin Team'));

        try {
            $this->sendEmail($recipients, $subject, $body, true);
            $this->flash('success', 'Email sent to ' . count($recipients) . ' students for ' . $programme['title'] . '.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Email send failed: ' . $e->getMessage());
        }

        return $this->redirectToInterests($res);
    }

    public function sendSingleMail(Request $req, Response $res, array $args): Response
    {
        $interest = $this->model->findOneWithProgramme((int) $args['id']);
        if (!$interest) {
            $this->flash('error', 'Registration record not found.');
            return $this->redirectToInterests($res);
        }

        $data = $req->getParsedBody() ?? [];
        $subject = $this->cleanText($data['subject'] ?? ('Update for ' . ($interest['programme_title'] ?? 'Programme')));
        $body = $this->cleanText($data['body'] ?? ('Dear ' . ($interest['first_name'] ?? 'student') . ',' . PHP_EOL . PHP_EOL . 'This is an update regarding your interest registration.' . PHP_EOL . PHP_EOL . 'Regards,' . PHP_EOL . 'Admin Team'));

        try {
            $this->sendEmail([(string) $interest['email']], $subject, $body);
            $this->flash('success', 'Email sent to ' . $interest['email'] . '.');
        } catch (\Throwable $e) {
            $this->flash('error', 'Email send failed: ' . $e->getMessage());
        }

        return $this->redirectToInterests($res);
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
