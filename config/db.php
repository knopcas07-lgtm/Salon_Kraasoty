<?php
// config/db.php — БЕЗОПАСНАЯ ВЕРСИЯ С .ENV

$host    = 'localhost';
$db      = 'n91378xg_vanda';
$user    = 'n91378xg_vanda';
$charset = 'utf8mb4';

// --- Загрузка пароля из .env ---
$pass = '';
$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    die('Ошибка: Файл конфигурации ' . $envFile . ' не найден. Создайте его с паролем.');
}

$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    list($key, $value) = explode('=', $line, 2);
    if (trim($key) === 'DB_PASSWORD') {
        $pass = trim($value);
        break;
    }
}

if (empty($pass)) {
    die('Ошибка: В файле ' . $envFile . ' не найдена строка DB_PASSWORD=ваш_пароль');
}
// --- Конец загрузки ---

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('Ошибка подключения к БД: ' . $e->getMessage());
}
