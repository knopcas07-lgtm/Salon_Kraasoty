<?php
// public_html/index.php — Front Controller (единственная точка входа)

// ── 1. Базовый путь до корня проекта ──────────────────────────────────────
define('ROOT_PATH', dirname(__DIR__));

// ── 2. Загрузка конфигурации ──────────────────────────────────────────────
require ROOT_PATH . '/config/config.php';
require ROOT_PATH . '/config/db.php';
require ROOT_PATH . '/includes/helpers.php';

// ── 3. Запуск сессии ──────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── 4. Роутинг ────────────────────────────────────────────────────────────
// Получаем «виртуальный» путь из mod_rewrite (?route=...)
$route = trim($_GET['route'] ?? '', '/');

// Разбиваем маршрут: "admin/add_item" → controller=admin, action=add_item
$parts      = explode('/', $route);
$controller = $parts[0] ?? '';
$action     = $parts[1] ?? 'index';

// ── 5. Диспетчер контроллеров ─────────────────────────────────────────────
switch ($controller) {

    // ── Аутентификация ────────────────────────────────────────────────────
    case 'login':
        require ROOT_PATH . '/controllers/AuthController.php';
        $c = new AuthController($pdo);
        $c->login();
        break;

    case 'register':
        require ROOT_PATH . '/controllers/AuthController.php';
        $c = new AuthController($pdo);
        $c->register();
        break;

    case 'logout':
        require ROOT_PATH . '/controllers/AuthController.php';
        $c = new AuthController($pdo);
        $c->logout();
        break;

    // ── Каталог (главная) ─────────────────────────────────────────────────
    case '':
    case 'catalog':
        require ROOT_PATH . '/controllers/CatalogController.php';
        $c = new CatalogController($pdo);
        $c->index();
        break;

    // ── Мастера ───────────────────────────────────────────────────────────
    case 'masters':
        require ROOT_PATH . '/controllers/MastersController.php';
        $c = new MastersController($pdo);
        $c->index();
        break;

    case 'portfolio':
        require ROOT_PATH . '/controllers/MastersController.php';
        $c = new MastersController($pdo);
        $c->portfolio();
        break;

    // ── Запись на приём ───────────────────────────────────────────────────
    case 'book':
        require ROOT_PATH . '/controllers/AppointmentController.php';
        $c = new AppointmentController($pdo);
        $c->book();
        break;

    case 'appointment':
        require ROOT_PATH . '/controllers/AppointmentController.php';
        $c = new AppointmentController($pdo);
        switch ($action) {
            case 'save':     $c->save();     break;
            case 'cancel':   $c->cancel();   break;
            case 'reschedule': $c->reschedule(); break;
            case 'update':   $c->update();   break;
            default: redirect(BASE_URL . '/');
        }
        break;

    // ── AJAX-эндпоинты записи ─────────────────────────────────────────────
    case 'get_busy':
        require ROOT_PATH . '/controllers/AppointmentController.php';
        $c = new AppointmentController($pdo);
        $c->getBusy();
        break;

    case 'get_masters':
        require ROOT_PATH . '/controllers/AppointmentController.php';
        $c = new AppointmentController($pdo);
        $c->getAvailableMasters();
        break;

    // ── Профиль пользователя ──────────────────────────────────────────────
    case 'profile':
        require ROOT_PATH . '/controllers/ProfileController.php';
        $c = new ProfileController($pdo);
        $c->index();
        break;

    case 'change_password':
        require ROOT_PATH . '/controllers/ProfileController.php';
        $c = new ProfileController($pdo);
        $c->changePassword();
        break;

    // ── Админ-панель ──────────────────────────────────────────────────────
    case 'admin':
        require ROOT_PATH . '/controllers/AdminController.php';
        $c = new AdminController($pdo);
        switch ($action) {
            case 'index':          $c->index();         break;
            case 'appointments':   $c->appointments();  break;
            case 'add_item':       $c->addItem();        break;
            case 'edit_item':      $c->editItem();       break;
            case 'delete_item':    $c->deleteItem();     break;
            case 'add_master':     $c->addMaster();      break;
            case 'edit_master':    $c->editMaster();     break;
            case 'delete_master':  $c->deleteMaster();   break;
            case 'master_photos':  $c->masterPhotos();   break;
            case 'delete_photo':   $c->deletePhoto();    break;
            case 'update_status':  $c->updateStatus();   break;
            default: redirect(BASE_URL . '/admin/index');
        }
        break;

    // ── 404 ───────────────────────────────────────────────────────────────
    default:
        http_response_code(404);
        echo '<h1>404 — Страница не найдена</h1>';
        break;
}