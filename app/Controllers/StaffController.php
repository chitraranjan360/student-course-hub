<?php
namespace App\Controllers;

use App\Models\StaffModel;
use App\Models\ModuleModel;
use App\Models\ProgrammeModel;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class StaffController
{
    public function __construct(
        private StaffModel $staffModel,
        private ModuleModel $moduleModel,
        private ProgrammeModel $programmeModel,
        private PhpRenderer $renderer
    ) {}

    private function flash(string $key, string $msg): void { $_SESSION['flash'][$key] = $msg; }
    private function getFlash(): array { $f = $_SESSION['flash'] ?? []; unset($_SESSION['flash']); return $f; }
    private function clean(mixed $v): string { return htmlspecialchars(trim((string)$v), ENT_QUOTES, 'UTF-8'); }

    /**
     * List all staff members
     */
    public function index(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/staff/list.php', [
            'staff' => $this->staffModel->getAll(),
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Show staff creation form
     */
    public function create(Request $req, Response $res): Response
    {
        return $this->renderer->render($res, 'admin/staff/form.php', [
            'staff' => null,
        ]);
    }

    /**
     * Store new staff member
     */
    public function store(Request $req, Response $res): Response
    {
        $d = $req->getParsedBody();
        
        // Validate
        $errors = [];
        $username = $this->clean($d['username'] ?? '');
        $email = $this->clean($d['email'] ?? '');
        $fullName = $this->clean($d['full_name'] ?? '');
        $password = $d['password'] ?? '';
        $confirmPassword = $d['confirm_password'] ?? '';

        if (!$username || strlen($username) < 3) {
            $errors['username'] = 'Username must be at least 3 characters.';
        }
        if ($this->staffModel->findByUsername($username)) {
            $errors['username'] = 'Username already exists.';
        }
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }
        if (!$fullName) {
            $errors['full_name'] = 'Full name is required.';
        }
        if (!$password || strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters.';
        }
        if ($password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            return $this->renderer->render($res, 'admin/staff/form.php', [
                'staff'  => $d,
                'errors' => $errors,
            ]);
        }

        $staffId = $this->staffModel->create([
            'username'  => $username,
            'email'     => $email,
            'full_name' => $fullName,
            'password'  => $password,
            'is_active' => 1,
        ], $_SESSION['admin_id']);

        $this->flash('success', 'Staff member created successfully.');
        return $res->withHeader('Location', base_url('/admin/staff'))->withStatus(302);
    }

    /**
     * Show a selected staff member with assignments
     */
    public function show(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        $staff = $this->staffModel->findById($staffId);

        if (!$staff) {
            return $res->withStatus(404);
        }

        return $this->renderer->render($res, 'admin/staff/detail.php', [
            'staff' => $staff,
            'assignedModules' => $this->staffModel->getAssignedModules($staffId),
            'assignedProgrammes' => $this->staffModel->getAssignedProgrammes($staffId),
            'unassignedModules' => $this->moduleModel->getUnassignedForStaff($staffId),
            'unassignedProgrammes' => $this->staffModel->getUnassignedProgrammes($staffId),
            'programmesList' => $this->programmeModel->getAll(),
            'flash' => $this->getFlash(),
        ]);
    }

    /**
     * Assign a single module to a selected staff member
     */
    public function assignModule(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        $staff = $this->staffModel->findById($staffId);

        if (!$staff) {
            return $res->withStatus(404);
        }

        $d = $req->getParsedBody();

        if (!empty($d['module_id'])) {
            $this->staffModel->assignModule($staffId, (int) $d['module_id']);
        }

        $this->flash('success', 'Module assigned successfully.');
        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    /**
     * Assign a single programme to a selected staff member
     */
    public function assignProgramme(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        $staff = $this->staffModel->findById($staffId);

        if (!$staff) {
            return $res->withStatus(404);
        }

        $d = $req->getParsedBody();

        if (!empty($d['programme_id'])) {
            $this->staffModel->assignProgramme($staffId, (int) $d['programme_id']);
        }

        $this->flash('success', 'Programme assigned successfully.');
        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    /**
     * Unassign a module from a staff member
     */
    public function unassignModule(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        $staff = $this->staffModel->findById($staffId);
        if (!$staff) { return $res->withStatus(404); }

        $d = $req->getParsedBody();
        if (!empty($d['module_id'])) {
            $this->staffModel->unassignModule($staffId, (int)$d['module_id']);
            $this->flash('success', 'Module unassigned successfully.');
        }
        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    /**
     * Unassign a programme from a staff member
     */
    public function unassignProgramme(Request $req, Response $res, array $args): Response
    {
        $staffId = (int) $args['id'];
        $staff = $this->staffModel->findById($staffId);
        if (!$staff) { return $res->withStatus(404); }

        $d = $req->getParsedBody();
        if (!empty($d['programme_id'])) {
            $this->staffModel->unassignProgramme($staffId, (int)$d['programme_id']);
            $this->flash('success', 'Programme unassigned successfully.');
        }
        return $res->withHeader('Location', base_url('/admin/staff/' . $staffId))->withStatus(302);
    }

    /**
     * Show edit form
     */
    public function edit(Request $req, Response $res, array $args): Response
    {
        $staffId = (int)$args['id'];
        $staff = $this->staffModel->findById($staffId);
        
        if (!$staff) {
            return $res->withStatus(404);
        }

        return $this->renderer->render($res, 'admin/staff/form.php', [
            'staff' => $staff,
        ]);
    }

    /**
     * Update staff member
     */
    public function update(Request $req, Response $res, array $args): Response
    {
        $staffId = (int)$args['id'];
        $staff = $this->staffModel->findById($staffId);
        
        if (!$staff) {
            return $res->withStatus(404);
        }

        $d = $req->getParsedBody();
        
        // Validate
        $errors = [];
        $email = $this->clean($d['email'] ?? '');
        $fullName = $this->clean($d['full_name'] ?? '');
        $password = $d['password'] ?? '';
        $confirmPassword = $d['confirm_password'] ?? '';

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required.';
        }
        if (!$fullName) {
            $errors['full_name'] = 'Full name is required.';
        }
        if ($password && strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters.';
        }
        if ($password && $password !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }

        if (!empty($errors)) {
            return $this->renderer->render($res, 'admin/staff/form.php', [
                'staff'  => array_merge($staff, $d),
                'errors' => $errors,
            ]);
        }

        $updateData = [
            'email'     => $email,
            'full_name' => $fullName,
            'is_active' => isset($d['is_active']) ? 1 : 0,
        ];

        if ($password) {
            $updateData['password'] = $password;
        }

        $this->staffModel->update($staffId, $updateData);

        $this->flash('success', 'Staff member updated successfully.');
        return $res->withHeader('Location', base_url('/admin/staff'))->withStatus(302);
    }

    /**
     * Delete staff member
     */
    public function delete(Request $req, Response $res, array $args): Response
    {
        $this->staffModel->delete((int)$args['id']);
        $this->flash('success', 'Staff member deleted.');
        return $res->withHeader('Location', base_url('/admin/staff'))->withStatus(302);
    }

    /**
     * Staff dashboard (restricted to staff only)
     */
    public function dashboard(Request $req, Response $res): Response
    {
        $staffId = $_SESSION['staff_id'];
        $staff = $this->staffModel->findById($staffId);
        $modules = $this->staffModel->getAssignedModules($staffId);

        return $this->renderer->render($res, 'staff/dashboard.php', [
            'staff'   => $staff,
            'modules' => $modules,
        ]);
    }
}
