<?php
// controllers/AppointmentController.php

class AppointmentController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ── GET /book?product_id=X ─────────────────────────────────────────────
    public function book(): void
    {
        requireAuth();

        $product_id = (int)($_GET['product_id'] ?? 0);
        $masters    = $this->pdo->query("SELECT * FROM masters")->fetchAll();

        render('appointment/book', [
            'product_id' => $product_id,
            'masters'    => $masters,
            'timeSlots'  => TIME_SLOTS,
        ]);
    }

    // ── POST /appointment/save ────────────────────────────────────────────
    public function save(): void
    {
        requireAuth();
        csrfCheck();

        $user_id    = (int)$_SESSION['user_id'];
        $product_id = (int)($_POST['product_id'] ?? 0);
        $master_id  = (int)($_POST['master_id']  ?? 0);
        $date       = $_POST['date'] ?? '';
        $time       = $_POST['time'] ?? '';

        if (!$product_id || !$master_id || !$date || !$time) {
            die('Ошибка: не все поля заполнены.');
        }

        // нормализация времени "HH:MM" → "HH:MM:SS"
        if (strlen($time) === 5) {
            $time .= ':00';
        }

        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO appointments
                    (user_id, product_id, master_id, date, time, status)
                 VALUES (?, ?, ?, ?, ?, 'pending')"
            );
            $stmt->execute([$user_id, $product_id, $master_id, $date, $time]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                die('Этот мастер уже занят в выбранное время.');
            }
            die('Ошибка БД: ' . $e->getMessage());
        }

        redirect(BASE_URL . '/profile');
    }

    // ── POST /appointment/cancel ──────────────────────────────────────────
    public function cancel(): void
    {
        requireAuth();
        csrfCheck();

        $id      = (int)($_POST['id'] ?? 0);
        $user_id = (int)$_SESSION['user_id'];

        $stmt = $this->pdo->prepare(
            "SELECT * FROM appointments WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$id, $user_id]);
        $appt = $stmt->fetch();

        if (!$appt || $appt['status'] === STATUS_CANCELLED) {
            redirect(BASE_URL . '/profile');
        }

        $stmt = $this->pdo->prepare(
            "UPDATE appointments
             SET status = 'cancelled', cancel_reason = 'Отменено пользователем'
             WHERE id = ?"
        );
        $stmt->execute([$id]);

        redirect(BASE_URL . '/profile');
    }

    // ── GET /appointment/reschedule?id=X ──────────────────────────────────
    public function reschedule(): void
    {
        requireAuth();

        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->pdo->prepare(
            "SELECT * FROM appointments WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$id, $_SESSION['user_id']]);
        $appt = $stmt->fetch();

        if (!$appt) {
            die('Запись не найдена.');
        }

        render('appointment/reschedule', [
            'appt'      => $appt,
            'timeSlots' => TIME_SLOTS,
        ]);
    }

    // ── POST /appointment/update ──────────────────────────────────────────
    public function update(): void
    {
        requireAuth();
        csrfCheck();

        $id      = (int)($_POST['id'] ?? 0);
        $date    = $_POST['date'] ?? '';
        $time    = $_POST['time'] ?? '';

        $stmt = $this->pdo->prepare(
            "UPDATE appointments
             SET date = ?, time = ?
             WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$date, $time, $id, $_SESSION['user_id']]);

        redirect(BASE_URL . '/profile');
    }

    // ── AJAX GET /get_busy?date=YYYY-MM-DD ────────────────────────────────
    public function getBusy(): void
    {
        header('Content-Type: application/json');

        $date = $_GET['date'] ?? '';
        if (!$date) {
            echo json_encode(['busyTimes' => []]);
            exit;
        }

        $stmt = $this->pdo->prepare(
            "SELECT time FROM appointments
             WHERE date = ? AND status != 'cancelled'"
        );
        $stmt->execute([$date]);
        $busy = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode(['busyTimes' => $busy]);
        exit;
    }

    // ── AJAX GET /get_masters?date=...&time=... ───────────────────────────
    public function getAvailableMasters(): void
    {
        header('Content-Type: application/json');

        $date = $_GET['date'] ?? '';
        $time = $_GET['time'] ?? '';

        $stmt = $this->pdo->prepare(
            "SELECT * FROM masters
             WHERE id NOT IN (
                 SELECT master_id FROM appointments
                 WHERE date = ? AND time = ? AND status != 'cancelled'
             )"
        );
        $stmt->execute([$date, $time]);

        echo json_encode($stmt->fetchAll());
        exit;
    }
}
