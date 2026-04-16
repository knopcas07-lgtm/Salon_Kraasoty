<?php
// controllers/AdminController.php

class AdminController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // ── GET /admin/index ───────────────────────────────────────────────────
    public function index(): void
    {
        requireAdmin();

        $masters = $this->pdo
            ->query("SELECT * FROM masters ORDER BY name ASC")
            ->fetchAll();

        render('admin/panel', ['masters' => $masters]);
    }

    // ── GET /admin/appointments ────────────────────────────────────────────
    public function appointments(): void
    {
        requireAdmin();

        $data = $this->pdo->query(
            "SELECT a.*,
                    u.username    AS user_name,
                    m.name        AS master_name,
                    p.title       AS product_title
             FROM appointments a
             LEFT JOIN users    u ON a.user_id    = u.id
             LEFT JOIN masters  m ON a.master_id  = m.id
             LEFT JOIN products p ON a.product_id = p.id
             ORDER BY
                CASE WHEN a.date = CURDATE() THEN 0 ELSE 1 END,
                a.date DESC, a.time DESC"
        )->fetchAll();

        render('admin/appointments', ['appointments' => $data]);
    }

    // ── GET/POST /admin/add_item ───────────────────────────────────────────
    public function addItem(): void
    {
        requireAdmin();

        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            $title    = trim($_POST['title']       ?? '');
            $price    = $_POST['price']             ?? '';
            $desc     = trim($_POST['description']  ?? '');
            $imgPath  = '';
            $imgError = '';

            if (!empty($_FILES['image']['name'])) {
                $imgPath = uploadImage($_FILES['image'], $imgError) ?? '';
            }

            if ($imgError) {
                $message = ['type' => 'danger', 'text' => $imgError];
            } elseif ($title && $price) {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO products (title, description, price, image_url, user_id)
                     VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->execute([$title, $desc, $price, $imgPath, $_SESSION['user_id']]);
                $message = ['type' => 'success', 'text' => 'Услуга добавлена!'];
            } else {
                $message = ['type' => 'danger', 'text' => 'Заполните название и цену.'];
            }
        }

        render('admin/add_item', ['message' => $message, 'csrf' => csrfToken()]);
    }

    // ── GET/POST /admin/edit_item?id=X ────────────────────────────────────
    public function editItem(): void
    {
        requireAdmin();

        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch();

        if (!$product) {
            die('Услуга не найдена.');
        }

        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            $title   = trim($_POST['title']       ?? '');
            $price   = $_POST['price']             ?? '';
            $desc    = trim($_POST['description']  ?? '');
            $imgPath = $product['image_url'];
            $imgErr  = '';

            if (!empty($_FILES['image']['name'])) {
                $new = uploadImage($_FILES['image'], $imgErr);
                if ($new) {
                    $imgPath = $new;
                }
            }

            if ($imgErr) {
                $message = ['type' => 'danger', 'text' => $imgErr];
            } elseif ($title && $price) {
                $stmt = $this->pdo->prepare(
                    "UPDATE products
                     SET title = ?, description = ?, price = ?, image_url = ?
                     WHERE id = ?"
                );
                $stmt->execute([$title, $desc, $price, $imgPath, $id]);

                $product = array_merge($product, [
                    'title'       => $title,
                    'description' => $desc,
                    'price'       => $price,
                    'image_url'   => $imgPath,
                ]);

                $message = ['type' => 'success', 'text' => 'Сохранено!'];
            }
        }

        render('admin/edit_item', [
            'product' => $product,
            'message' => $message,
            'csrf'    => csrfToken(),
        ]);
    }

    // ── POST /admin/delete_item ────────────────────────────────────────────
    public function deleteItem(): void
    {
        requireAdmin();
        csrfCheck();

        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $this->pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
        }
        redirect(BASE_URL . '/');
    }

    // ── GET/POST /admin/add_master ─────────────────────────────────────────
    public function addMaster(): void
    {
        requireAdmin();

        $error   = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            $name             = trim($_POST['name']             ?? '');
            $specialization   = trim($_POST['specialization']   ?? '');
            $description      = trim($_POST['description']      ?? '');
            $experience_years = (int)($_POST['experience_years'] ?? 0);
            $imgPath          = null;
            $imgErr           = '';

            if (!empty($_FILES['image']['name'])) {
                $imgPath = uploadImage($_FILES['image'], $imgErr);
            }

            if ($imgErr) {
                $error = $imgErr;
            } else {
                $stmt = $this->pdo->prepare(
                    "INSERT INTO masters
                        (name, specialization, description, experience_years, image_url)
                     VALUES (?, ?, ?, ?, ?)"
                );
                $stmt->execute([$name, $specialization, $description, $experience_years, $imgPath]);
                $success = 'Мастер успешно добавлен!';
            }
        }

        render('admin/add_master', [
            'error'   => $error,
            'success' => $success,
            'csrf'    => csrfToken(),
        ]);
    }


    // ── GET/POST /admin/edit_master?id=X ──────────────────────────────────
    public function editMaster(): void
    {
        requireAdmin();

        $id = (int)($_GET['id'] ?? 0);

        $stmt = $this->pdo->prepare("SELECT * FROM masters WHERE id = ?");
        $stmt->execute([$id]);
        $master = $stmt->fetch();

        if (!$master) {
            die('Мастер не найден.');
        }

        $message = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            csrfCheck();

            $name             = trim($_POST['name']              ?? '');
            $specialization   = trim($_POST['specialization']    ?? '');
            $description      = trim($_POST['description']       ?? '');
            $experience_years = (int)($_POST['experience_years'] ?? 0);
            $imgPath          = $master['image_url'];
            $imgErr           = '';

            if (!empty($_FILES['image']['name'])) {
                $new = uploadImage($_FILES['image'], $imgErr);
                if ($new) {
                    if (!empty($master['image_url'])) {
                        $old = ROOT_PATH . '/public_html/' . $master['image_url'];
                        if (file_exists($old)) unlink($old);
                    }
                    $imgPath = $new;
                }
            }

            if ($imgErr) {
                $message = ['type' => 'danger', 'text' => $imgErr];
            } else {
                $stmt = $this->pdo->prepare(
                    "UPDATE masters
                     SET name = ?, specialization = ?, description = ?,
                         experience_years = ?, image_url = ?
                     WHERE id = ?"
                );
                $stmt->execute([$name, $specialization, $description,
                                $experience_years, $imgPath, $id]);

                $master = array_merge($master, [
                    'name'             => $name,
                    'specialization'   => $specialization,
                    'description'      => $description,
                    'experience_years' => $experience_years,
                    'image_url'        => $imgPath,
                ]);

                $message = ['type' => 'success', 'text' => 'Данные мастера обновлены!'];
            }
        }

        render('admin/edit_master', [
            'master'  => $master,
            'message' => $message,
            'csrf'    => csrfToken(),
        ]);
    }

    // ── POST /admin/delete_master ──────────────────────────────────────────
    public function deleteMaster(): void
    {
        requireAdmin();
        csrfCheck();

        $id = (int)($_POST['id'] ?? 0);
        if ($id) {
            $this->pdo->prepare("DELETE FROM masters WHERE id = ?")->execute([$id]);
        }
        redirect(BASE_URL . '/');
    }

    // ── GET/POST /admin/master_photos?master_id=X ─────────────────────────
    public function masterPhotos(): void
    {
        requireAdmin();

        $master_id = (int)($_GET['master_id'] ?? 0);

        $stmt = $this->pdo->prepare("SELECT * FROM masters WHERE id = ?");
        $stmt->execute([$master_id]);
        $master = $stmt->fetch();

        if (!$master) {
            redirect(BASE_URL . '/masters');
        }

        $error   = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['images']['name'][0])) {
            csrfCheck();

            $files    = $_FILES['images'];
            $uploaded = 0;

            for ($i = 0; $i < count($files['name']); $i++) {
                $single = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                ];

                $imgErr  = '';
                $imgPath = uploadImage($single, $imgErr);

                if ($imgPath) {
                    $stmt = $this->pdo->prepare(
                        "INSERT INTO master_portfolio (master_id, image_url) VALUES (?, ?)"
                    );
                    $stmt->execute([$master_id, $imgPath]);
                    $uploaded++;
                } else {
                    $error .= $imgErr . ' ';
                }
            }

            if ($uploaded > 0) {
                $success = "Загружено фото: $uploaded";
            }
        }

        $stmt = $this->pdo->prepare(
            "SELECT * FROM master_portfolio
             WHERE master_id = ?
             ORDER BY created_at DESC"
        );
        $stmt->execute([$master_id]);
        $portfolio = $stmt->fetchAll();

        render('admin/master_photos', [
            'master'    => $master,
            'portfolio' => $portfolio,
            'error'     => $error,
            'success'   => $success,
            'csrf'      => csrfToken(),
        ]);
    }

    // ── POST /admin/delete_photo ───────────────────────────────────────────
    public function deletePhoto(): void
    {
        requireAdmin();
        csrfCheck();

        $portfolio_id = (int)($_POST['portfolio_id'] ?? 0);
        $master_id    = (int)($_POST['master_id']    ?? 0);

        if ($portfolio_id) {
            $stmt = $this->pdo->prepare(
                "SELECT image_url FROM master_portfolio WHERE id = ?"
            );
            $stmt->execute([$portfolio_id]);
            $item = $stmt->fetch();

            if ($item) {
                $file = ROOT_PATH . '/public_html/' . $item['image_url'];
                if (file_exists($file)) {
                    unlink($file);
                }
                $this->pdo->prepare(
                    "DELETE FROM master_portfolio WHERE id = ?"
                )->execute([$portfolio_id]);
            }
        }

        redirect(BASE_URL . '/admin/master_photos?master_id=' . $master_id);
    }

    // ── POST /admin/update_status ──────────────────────────────────────────
    public function updateStatus(): void
    {
        requireAdmin();
        csrfCheck();

        $id     = (int)($_POST['id']     ?? 0);
        $status = $_POST['status']       ?? '';

        $allowed = [STATUS_PENDING, STATUS_CONFIRMED, STATUS_COMPLETED, STATUS_CANCELLED];

        if ($id && in_array($status, $allowed)) {
            $stmt = $this->pdo->prepare(
                "UPDATE appointments SET status = ? WHERE id = ?"
            );
            $stmt->execute([$status, $id]);
        }

        redirect(BASE_URL . '/admin/appointments');
    }
}