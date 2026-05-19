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

    private function handleImageUpload(Request $req): ?string
    {
        $files = $req->getUploadedFiles();
        $photo = $files['photo'] ?? null;

        if (!$photo || $photo->getError() !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = 'module_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . pathinfo($photo->getClientFilename(), PATHINFO_EXTENSION);
        $filepath = $uploadDir . $filename;

        try {
            $photo->moveTo($filepath);
            return $filename;
        } catch (\Exception $e) {
            error_log('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }

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
        $photoFile = $this->handleImageUpload($req);
        
        $this->model->create([
            'title'       => $this->clean($d['title'] ?? ''),
            'description' => $this->clean($d['description'] ?? ''),
            'photo'       => $photoFile,
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

    public function adminShow(Request $req, Response $res, array $args): Response
    {
        $id = (int)($args['id'] ?? 0);
        $module = $this->model->findById($id);
        if (!$module) {
            return $this->renderer->render($res->withStatus(404), 'admin/module-detail.php', [
                'module' => null,
                'flash'  => $this->getFlash(),
            ]);
        }

        $assignedProgram = $this->model->getAssignedProgramme($id);
        $assignedPrograms = $this->model->getAssignedProgrammes($id);
        $assignedStaff = $this->model->getAssignedStaff($id);

        return $this->renderer->render($res, 'admin/module-detail.php', [
            'module' => $module,
            'assignedProgram' => $assignedProgram,
            'assignedPrograms' => $assignedPrograms,
            'assignedStaff' => $assignedStaff,
            'flash' => $this->getFlash(),
        ]);
    }

    public function update(Request $req, Response $res, array $args): Response
    {
        $d = $req->getParsedBody();
        $moduleId = (int)$args['id'];
        $module = $this->model->findById($moduleId);
        
        $photoFile = $this->handleImageUpload($req);
        
        $this->model->update($moduleId, [
            'title'       => $this->clean($d['title'] ?? ''),
            'description' => $this->clean($d['description'] ?? ''),
            'photo'       => $photoFile ?? ($module['photo'] ?? null),
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
