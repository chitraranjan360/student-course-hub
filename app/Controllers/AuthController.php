<?php
namespace App\Controllers;

use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthController
{
    public function __construct(private \PDO $pdo, private PhpRenderer $renderer) {}

    public function loginForm(Request $req, Response $res): Response
    {
        if (!empty($_SESSION['admin_id'])) {
            return $res->withHeader('Location', base_url('/admin'))->withStatus(302);
        }
        return $this->renderer->render($res, 'admin/login.php', ['error' => null]);
    }

    public function login(Request $req, Response $res): Response
    {
        $d    = $req->getParsedBody();
        $user = trim($d['username'] ?? '');
        $pass = $d['password'] ?? '';

        $stmt = $this->pdo->prepare('SELECT * FROM admins WHERE username = ?');
        $stmt->execute([$user]);
        $admin = $stmt->fetch();

        if ($admin && password_verify($pass, $admin['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            return $res->withHeader('Location', base_url('/admin'))->withStatus(302);
        }

        return $this->renderer->render($res, 'admin/login.php', ['error' => 'Invalid credentials.']);
    }

    public function logout(Request $req, Response $res): Response
    {
        session_destroy();
        return $res->withHeader('Location', base_url('/'))->withStatus(302);
    }
}
