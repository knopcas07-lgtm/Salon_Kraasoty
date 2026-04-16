<?php
// config/db.php — PDO подключение к базе данных

$host    = 'localhost';
$db      = 'n91378xg_vanda'; // ← замените на своё имя БД из Beget
$user    = 'n91378xg_vanda'; // ← замените на своего пользователя
$pass    = '5HL32mC!RRgf';  // ← замените на свой пароль
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
