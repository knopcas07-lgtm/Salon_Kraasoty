<?php
// controllers/ProfileController.php

class ProfileController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ── GET /profile ───────────────────────────────────────────────────────
    public function index(): void
    {
        requireAuth();

        $user_id = (int)$_SESSION['user_id'];

        $stmt = $this->pdo->prepare(
            "SELECT a.*,
                    p.title AS product_title,
                    p.price AS product_price,
                    m.name  AS master_name
             FROM appointments a
             JOIN products p ON a.product_id = p.id
             JOIN masters  m ON a.master_id  = m.id
             WHERE a.user_id = :uid
             ORDER BY a.date ASC, a.time ASC"
        );
        $stmt->execute(['uid' => $user_id]);
        $appointments = $stmt->fetchAll();

        render('profile/index', ['appointments' => $appointments]);
    }

    // ── GET/POST /change_password ──────────────────────────────────────────
    public function changePassword(): void
    {
        requireAuth();

        // POST — AJAX-запрос, возвращаем JSON
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            header('Content-Type: application/json');

            // CSRF
            $token = $_POST['csrf_token'] ?? '';
            if (!hash_equals(csrfToken(), $token)) {
                echo json_encode(['status' => 'error', 'message' => 'CSRF-ошибка']);
                exit;
            }

            $current = $_POST['current_password'] ?? '';
            $new     = $_POST['new_password']     ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            $stmt = $this->pdo->prepare(
                "SELECT password_hash FROM users WHERE id = ?"
            );
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($current, $user['password_hash'])) {
                echo json_encode(['status' => 'error', 'message' => 'Неверный текущий пароль']);
                exit;
            }

            if ($new !== $confirm) {
                echo json_encode(['status' => 'error', 'message' => 'Пароли не совпадают']);
                exit;
            }

            if (strlen($new) < 8) {
                echo json_encode(['status' => 'error', 'message' => 'Минимум 8 символов']);
                exit;
            }

            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare(
                "UPDATE users SET password_hash = ? WHERE id = ?"
            );
            $stmt->execute([$hash, $_SESSION['user_id']]);

            echo json_encode(['status' => 'success', 'message' => 'Пароль успешно изменён']);
            exit;
        }

        // GET — отдаём HTML-страницу
        render('profile/change_password', ['csrf' => csrfToken()]);
    }
}
