<?php
// controllers/AuthController.php

require_once ROOT_PATH . '/config/db.php';

class AuthController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ── GET/POST /login ───────────────────────────────────────────────────
    public function login(): void
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';

            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($pass, $user['password_hash'])) {
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['is_admin']  = ($user['role'] === ROLE_ADMIN) ? 1 : 0;

                redirect($user['role'] === ROLE_ADMIN
                    ? BASE_URL . '/admin/index'
                    : BASE_URL . '/');
            } else {
                $error = 'Неверный email или пароль';
            }
        }

        render('auth/login', ['error' => $error]);
    }

    // ── GET/POST /register ────────────────────────────────────────────────
    public function register(): void
    {
        $error   = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $pass     = $_POST['password'] ?? '';
            $confirm  = $_POST['password_confirm'] ?? '';

            if (empty($email) || empty($username) || empty($pass)) {
                $error = 'Заполните все поля!';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Некорректный формат Email!';
            } elseif ($pass !== $confirm) {
                $error = 'Пароли не совпадают!';
            } elseif (strlen($pass) < 6) {
                $error = 'Пароль должен быть минимум 6 символов!';
            } else {
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                try {
                    $stmt = $this->pdo->prepare(
                        "INSERT INTO users (email, username, password_hash, role)
                         VALUES (?, ?, ?, 'client')"
                    );
                    $stmt->execute([$email, $username, $hash]);
                    $success = true;
                } catch (PDOException $e) {
                    $error = ($e->getCode() == 23000)
                        ? 'Такой email уже зарегистрирован.'
                        : 'Ошибка БД: ' . $e->getMessage();
                }
            }
        }

        render('auth/register', ['error' => $error, 'success' => $success]);
    }

    // ── /logout ───────────────────────────────────────────────────────────
    public function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }
        session_destroy();
        redirect(BASE_URL . '/');
    }
}
