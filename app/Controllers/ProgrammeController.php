<?php
namespace App\Controllers;

use App\Models\ProgrammeModel;
use App\Models\StaffModel;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\UploadedFileInterface;

class ProgrammeController
{
    public function __construct(private ProgrammeModel $model, private PhpRenderer $renderer, private StaffModel $staffModel) {}

    private function flash(string $key, string $msg): void { $_SESSION['flash'][$key] = $msg; }
    private function getFlash(): array { $f = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $f; }
    private function clean(mixed $v): string { return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8'); }

    public function home(Request $req, Response $res): Response
    {
        $q = $req->getQueryParams();
        $level  = $q['level']  ?? '';
        $search = $q['search'] ?? '';
        $programmes = $this->model->getAllPublished($level ?: null, $search ?: null);
        return $this->renderer->render($res, 'student/home.php', [
            'programmes' => $programmes,
            'level'      => $level,
            'search'     => $search,
        ]);
    }

    public function detail(Request $req, Response $res, array $args): Response
    {
        $prog = $this->model->findById((int)$args['id']);
        if (!$prog || !$prog['is_published']) {
            $res = $res->withStatus(404)->withHeader('Content-Type', 'text/html');
            $res->getBody()->write('<h1>404 – Programme not found</h1>');
            return $res;
        }
        $modulesByYear = $this->model->getModules((int)$args['id']);
        return $this->renderer->render($res, 'student/programme-detail.php', [
            'prog'          => $prog,
            'modulesByYear' => $modulesByYear,
        ]);
    }

    public function adminDashboard(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/dashboard.php', [
            'totalProgrammes' => $this->model->countAll(),
            'totalStaff'      => $this->staffModel->countAll(),
        ]);
    }

    public function adminIndex(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/programmes.php', [
            'programmes' => $this->model->getAll(),
            'flash'      => $this->getFlash(),
        ]);
    }

    public function adminShow(Request $req, Response $res, array $args): Response
    {
        $programmeId = (int) $args['id'];
        $programme = $this->model->findById($programmeId);

        if (!$programme) {
            return $res->withStatus(404);
        }

        $modulesByYear = $this->model->getModules($programmeId);
        $assignedStaff = $this->model->getAssignedStaff($programmeId);
        $allModules = $this->staffModel->getAllModules();
        $assignedModuleIds = [];

        foreach ($modulesByYear as $yearModules) {
            foreach ($yearModules as $module) {
                $assignedModuleIds[] = (int) $module['id'];
            }
        }

        $availableModules = array_values(array_filter(
            $allModules,
            fn (array $module): bool => !in_array((int) $module['id'], $assignedModuleIds, true)
        ));

        return $this->renderer->render($res, 'admin/programme-detail.php', [
            'programme'        => $programme,
            'modulesByYear'    => $modulesByYear,
            'assignedStaff'    => $assignedStaff,
            'availableModules' => $availableModules,
            'flash'            => $this->getFlash(),
        ]);
    }

    public function assignModule(Request $req, Response $res, array $args): Response
    {
        $programmeId = (int) $args['id'];
        $programme = $this->model->findById($programmeId);

        if (!$programme) {
            return $res->withStatus(404);
        }

        $d = $req->getParsedBody();
        if (!empty($d['module_id'])) {
            $this->model->assignModule($programmeId, (int) $d['module_id']);
            $this->flash('success', 'Module assigned to programme successfully.');
        }

        return $res->withHeader('Location', base_url('/admin/programmes/' . $programmeId))->withStatus(302);
    }

    public function unassignModule(Request $req, Response $res, array $args): Response
    {
        $programmeId = (int) $args['id'];
        $programme = $this->model->findById($programmeId);

        if (!$programme) {
            return $res->withStatus(404);
        }

        $d = $req->getParsedBody();
        if (!empty($d['module_id'])) {
            $this->model->unassignModule($programmeId, (int) $d['module_id']);
            $this->flash('success', 'Module removed from programme successfully.');
        }

        return $res->withHeader('Location', base_url('/admin/programmes/' . $programmeId))->withStatus(302);
    }

    public function create(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/programme-form.php', ['prog' => null, 'flash' => []]);
    }

    public function store(Request $req, Response $res): Response
    {
        $d = $req->getParsedBody();
        // prefer uploaded file over manual URL
        $uploadedUrl = $this->handleImageUpload($req);
        $imageUrl = $uploadedUrl ?? $this->clean($d['image_url'] ?? '');

        $this->model->create([
            'title'        => $this->clean($d['title'] ?? ''),
            'level'        => $this->clean($d['level'] ?? ''),
            'description'  => $this->clean($d['description'] ?? ''),
            'image_url'    => $imageUrl,
            'is_published' => isset($d['is_published']) ? 1 : 0,
        ]);
        $this->flash('success', 'Programme created.');
        return $res->withHeader('Location', base_url('/admin/programmes'))->withStatus(302);
    }

    public function edit(Request $req, Response $res, array $args): Response
    {
        $prog = $this->model->findById((int)$args['id']);
        return $this->renderer->render($res, 'admin/programme-form.php', ['prog' => $prog, 'flash' => []]);
    }

    public function update(Request $req, Response $res, array $args): Response
    {
        $d = $req->getParsedBody();
        $existing = $this->model->findById((int)$args['id']);
        $uploadedUrl = $this->handleImageUpload($req);
        $imageUrl = $uploadedUrl ?? $this->clean($d['image_url'] ?? ($existing['image_url'] ?? ''));

        $this->model->update((int)$args['id'], [
            'title'        => $this->clean($d['title'] ?? ''),
            'level'        => $this->clean($d['level'] ?? ''),
            'description'  => $this->clean($d['description'] ?? ''),
            'image_url'    => $imageUrl,
            'is_published' => isset($d['is_published']) ? 1 : 0,
        ]);
        $this->flash('success', 'Programme updated.');
        return $res->withHeader('Location', base_url('/admin/programmes'))->withStatus(302);
    }

    private function handleImageUpload(Request $req): ?string
    {
        $files = $req->getUploadedFiles();
        $file = $files['image'] ?? null;
        if (!($file instanceof UploadedFileInterface)) {
            return null;
        }
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $mime = $file->getClientMediaType();
        if (!isset($allowed[$mime])) {
            return null;
        }

        $ext = $allowed[$mime];
        try {
            $name = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        } catch (\Exception $e) {
            $name = time() . '_' . bin2hex(openssl_random_pseudo_bytes(6)) . '.' . $ext;
        }

        $targetDir = __DIR__ . '/../../public/uploads/programmes/';
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $target = $targetDir . $name;
        $file->moveTo($target);

        return 'uploads/programmes/' . $name;
    }

    public function destroy(Request $req, Response $res, array $args): Response
    {
        $this->model->delete((int)$args['id']);
        return $res->withHeader('Location', base_url('/admin/programmes'))->withStatus(302);
    }

    public function togglePublish(Request $req, Response $res, array $args): Response
    {
        $this->model->togglePublish((int)$args['id']);
        $prog = $this->model->findById((int)$args['id']);
        $res->getBody()->write(json_encode(['is_published' => $prog['is_published']]));
        return $res->withHeader('Content-Type', 'application/json');
    }
}
