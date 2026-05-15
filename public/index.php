<?php
declare(strict_types=1);

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use App\Controllers\ProgrammeController;
use App\Controllers\InterestController;
use App\Controllers\AuthController;
use App\Controllers\ModuleController;
use App\Controllers\StaffController;
use App\Models\ProgrammeModel;
use App\Models\ModuleModel;
use App\Models\InterestModel;
use App\Models\StaffModel;

require __DIR__ . '/../vendor/autoload.php';

if (!function_exists('base_url')) {
    function base_url(string $path = ''): string
    {
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($basePath === '/' || $basePath === '.' || $basePath === '\\') {
            $basePath = '';
        }

        if ($path === '' || $path === '/') {
            return $basePath !== '' ? $basePath : '/';
        }

        return rtrim($basePath, '/') . '/' . ltrim($path, '/');
    }
}

// Session
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');
session_start();

// Database
$dbConfig = require __DIR__ . '/../config/database.php';
$dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset=utf8mb4";
$pdo = new PDO($dsn, $dbConfig['user'], $dbConfig['pass'], [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// Renderer
$renderer = new PhpRenderer(__DIR__ . '/../app/Views');

// Models
$progModel     = new ProgrammeModel($pdo);
$moduleModel   = new ModuleModel($pdo);
$interestModel = new InterestModel($pdo);
$staffModel    = new StaffModel($pdo);

// Controllers
$progCtrl     = new ProgrammeController($progModel, $renderer, $staffModel);
$interestCtrl = new InterestController($interestModel, $progModel, $renderer);
$authCtrl     = new AuthController($pdo, $renderer);
$moduleCtrl   = new ModuleController($moduleModel, $progModel, $renderer);
$staffCtrl    = new StaffController($staffModel, $moduleModel, $progModel, $renderer);

$app = AppFactory::create();
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
if ($scriptName !== '/' && $scriptName !== '.') {
    $app->setBasePath($scriptName);
}
$app->addErrorMiddleware(true, true, true);

// Admin auth middleware
$adminAuth = function ($request, $handler) {
    if (empty($_SESSION['admin_id'])) {
        return (new \Slim\Psr7\Response())
            ->withHeader('Location', base_url('/admin/login'))
            ->withStatus(302);
    }
    return $handler->handle($request);
};

// Staff auth middleware
$staffAuth = function ($request, $handler) {
    if (empty($_SESSION['staff_id'])) {
        return (new \Slim\Psr7\Response())
            ->withHeader('Location', base_url('/staff/login'))
            ->withStatus(302);
    }
    return $handler->handle($request);
};

// ── Public routes ───────────────────────────────────────────────
$app->get('/', [$progCtrl, 'home']);
$app->get('/programmes/{id:[0-9]+}', [$progCtrl, 'detail']);
$app->get('/interest/register/{id:[0-9]+}', [$interestCtrl, 'showForm']);
$app->post('/interest', [$interestCtrl, 'register']);
$app->get('/interest/withdraw/{token}', [$interestCtrl, 'withdraw']);

// ── Auth routes ─────────────────────────────────────────────────
$app->get('/admin/login',  [$authCtrl, 'loginForm']);
$app->post('/admin/login', [$authCtrl, 'login']);
$app->get('/admin/logout', [$authCtrl, 'logout']);
$app->get('/staff/login',  [$authCtrl, 'staffLoginForm']);
$app->post('/staff/login', [$authCtrl, 'staffLogin']);
$app->get('/staff/logout', [$authCtrl, 'staffLogout']);

// ── Admin routes (protected) ─────────────────────────────────────
$app->group('/admin', function ($group) use ($progCtrl, $moduleCtrl, $interestCtrl, $staffCtrl) {
    $group->get('',                                   [$progCtrl, 'adminDashboard']);
    // Programmes
    $group->get('/programmes',                        [$progCtrl, 'adminIndex']);
    $group->get('/programmes/create',                 [$progCtrl, 'create']);
    $group->post('/programmes',                       [$progCtrl, 'store']);
    $group->get('/programmes/{id:[0-9]+}/edit',       [$progCtrl, 'edit']);
    $group->post('/programmes/{id:[0-9]+}',           [$progCtrl, 'update']);
    $group->post('/programmes/{id:[0-9]+}/delete',    [$progCtrl, 'destroy']);
    $group->post('/programmes/{id:[0-9]+}/publish',   [$progCtrl, 'togglePublish']);
    // Modules
    $group->get('/modules',                           [$moduleCtrl, 'adminIndex']);
    $group->get('/modules/create',                    [$moduleCtrl, 'create']);
    $group->post('/modules',                          [$moduleCtrl, 'store']);
    $group->get('/modules/{id:[0-9]+}/edit',          [$moduleCtrl, 'edit']);
    $group->post('/modules/{id:[0-9]+}',              [$moduleCtrl, 'update']);
    $group->post('/modules/{id:[0-9]+}/delete',       [$moduleCtrl, 'destroy']);
    // Interests
    $group->get('/interests/{pid:[0-9]+}',            [$interestCtrl, 'adminList']);
    $group->get('/interests/{pid:[0-9]+}/export',     [$interestCtrl, 'exportCsv']);
    $group->post('/interests/{id:[0-9]+}/delete',     [$interestCtrl, 'adminDelete']);
    // Staff Management
    $group->get('/staff',                             [$staffCtrl, 'index']);
    $group->get('/staff/create',                      [$staffCtrl, 'create']);
    $group->post('/staff',                            [$staffCtrl, 'store']);
    $group->get('/staff/{id:[0-9]+}',                 [$staffCtrl, 'show']);
    $group->post('/staff/{id:[0-9]+}/assign-module',  [$staffCtrl, 'assignModule']);
    $group->post('/staff/{id:[0-9]+}/assign-programme',[$staffCtrl, 'assignProgramme']);
    $group->post('/staff/{id:[0-9]+}/unassign-module',  [$staffCtrl, 'unassignModule']);
    $group->post('/staff/{id:[0-9]+}/unassign-programme',[$staffCtrl, 'unassignProgramme']);
    $group->get('/staff/{id:[0-9]+}/edit',            [$staffCtrl, 'edit']);
    $group->post('/staff/{id:[0-9]+}',                [$staffCtrl, 'update']);
    $group->post('/staff/{id:[0-9]+}/delete',         [$staffCtrl, 'delete']);
})->add($adminAuth);

// ── Staff routes (protected) ────────────────────────────────────
$app->group('/staff', function ($group) use ($staffCtrl) {
    $group->get('', [$staffCtrl, 'dashboard']);
})->add($staffAuth);

$app->run();
