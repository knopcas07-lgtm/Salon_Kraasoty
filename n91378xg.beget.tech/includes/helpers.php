<?php
// includes/helpers.php — глобальные вспомогательные функции

/**
 * Безопасный вывод строки (XSS-защита)
 */
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * Редирект и немедленный выход
 */
function redirect(string $url): void {
    header("Location: $url");
    exit;
}

/**
 * Подключить view-шаблон с переданными переменными.
 * Пример: render('auth/login', ['error' => 'Неверный пароль'])
 */
function render(string $view, array $data = []): void {
    extract($data);
    $path = ROOT_PATH . "/views/{$view}.php";
    if (!file_exists($path)) {
        die("View не найден: $path");
    }
    require $path;
}

/**
 * Генерация / получение CSRF-токена для текущей сессии
 */
function csrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Проверка CSRF-токена из POST.
 * Завершает скрипт при несовпадении.
 */
function csrfCheck(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals(csrfToken(), $token)) {
        http_response_code(403);
        die('CSRF-ошибка. Обновите страницу и попробуйте снова.');
    }
}

/**
 * Проверка: пользователь авторизован?
 * При необходимости — редирект на логин.
 */
function requireAuth(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['user_id'])) {
        redirect(BASE_URL . '/login');
    }
}

/**
 * Проверка: пользователь — администратор?
 * При несоответствии — 403.
 */
function requireAdmin(): void {
    requireAuth();
    if (($_SESSION['user_role'] ?? '') !== ROLE_ADMIN) {
        http_response_code(403);
        die('ДОСТУП ЗАПРЕЩЁН. <a href="' . BASE_URL . '/login">Войти</a>');
    }
}

/**
 * Статус записи → читаемый русский текст
 */
function statusLabel(string $status): string {
    return match($status) {
        STATUS_PENDING   => 'Ожидает',
        STATUS_CONFIRMED => 'Подтверждено',
        STATUS_COMPLETED => 'Завершено',
        STATUS_CANCELLED => 'Отменено',
        default          => 'Неизвестно',
    };
}

/**
 * Bootstrap-класс бейджа для статуса
 */
function statusClass(string $status): string {
    return match($status) {
        STATUS_PENDING   => 'warning',
        STATUS_CONFIRMED => 'primary',
        STATUS_COMPLETED => 'success',
        STATUS_CANCELLED => 'danger',
        default          => 'secondary',
    };
}

/**
 * Можно ли отменить запись (осталось > 24 часов)?
 */
function canCancel(string $date, string $time): bool {
    return (strtotime("$date $time") - time()) > 86400;
}

/**
 * Загрузка изображения в UPLOAD_DIR.
 * Возвращает относительный путь (uploads/имя.jpg) или null при ошибке.
 * Ошибку записывает в переданную переменную $error.
 */
function uploadImage(array $file, string &$error): ?string {

    // 1. Проверка ошибки загрузки
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Ошибка загрузки файла (код ' . $file['error'] . ').';
        return null;
    }

    // 2. Проверка размера (макс. 5 MB)
    $maxSize = 5 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        $error = 'Файл слишком большой. Максимум 5 MB.';
        return null;
    }

    // 3. Проверка MIME через finfo (надёжнее, чем $_FILES['type'])
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($mimeType, $allowedMime)) {
        $error = 'Допустимые форматы: JPEG, PNG, GIF, WEBP.';
        return null;
    }

    // 4. Дополнительная проверка через getimagesize
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        $error = 'Файл не является изображением.';
        return null;
    }

    // 5. Создаём папку uploads/ если не существует
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    // 6. Безопасное имя файла через image_type_to_extension
    $ext     = image_type_to_extension($imageInfo[2]);
    $newName = uniqid('img_', true) . $ext;
    $dest    = UPLOAD_DIR . $newName;

    // 7. Перемещение файла
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        $error = 'Не удалось сохранить файл. Проверьте права папки uploads/.';
        return null;
    }

    // Возвращаем относительный путь для сохранения в БД и вывода на сайте
    return 'uploads/' . $newName;
}