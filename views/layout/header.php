<?php
// views/layout/header.php
// Подключается в начале каждой страницы через render() или require
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle ?? 'Салон красоты') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <?php if (!empty($extraCss)): ?>
        <link rel="stylesheet" href="<?= BASE_URL . '/css/' . e($extraCss) ?>">
    <?php endif; ?>

    <style>
    html, body { margin: 0; padding: 0; overflow-x: hidden; background-color: #f8f9fa; }

    .site-header {
        background-color: #fff;
        width: 100%;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 999;
        padding: 10px 0;
    }
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
    .logo a { font-weight: 700; color: #007bff; text-decoration: none; font-size: 1.5rem; }

    .site-nav ul { display: flex; align-items: center; gap: 10px; list-style: none; margin: 0; padding: 0; }
    .site-nav ul li { display: flex; align-items: center; }
    .site-nav ul li a {
        display: flex; align-items: center; justify-content: center;
        height: 38px; padding: 0 14px;
        text-decoration: none; border-radius: 6px; color: #333; font-size: 14px;
    }
    .site-nav ul li a.btn-nav  { background-color: #007bff; color: #fff; }
    .site-nav ul li a.btn-outline-nav { border: 1px solid #007bff; color: #007bff; }
    .site-nav ul li a:hover { opacity: 0.8; }

    .burger { display: none; flex-direction: column; cursor: pointer; gap: 4px; }
    .burger span { display: block; height: 3px; width: 25px; background-color: #333; border-radius: 2px; }

    @media (max-width: 768px) {
        .site-nav {
            position: absolute; top: 60px; right: 0;
            background: #fff; width: 200px;
            transform: translateX(100%); transition: 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            border-radius: 0 0 8px 8px;
        }
        .site-nav ul { flex-direction: column; padding: 10px; }
        .site-nav.show { transform: translateX(0); }
        .burger { display: flex; }
    }
    </style>
</head>
<body>

<header class="site-header">
    <div class="header-container">

        <div class="logo">
            <a href="<?= BASE_URL ?>/">Салон красоты</a>
        </div>

        <nav class="site-nav" id="nav-menu">
            <ul>
                <li><a href="<?= BASE_URL ?>/masters" class="btn-outline-nav">Мастера</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php if (($_SESSION['user_role'] ?? '') === ROLE_ADMIN): ?>
                        <li><a href="<?= BASE_URL ?>/admin/index">Админка</a></li>
                    <?php else: ?>
                        <li><a href="<?= BASE_URL ?>/profile">Профиль</a></li>
                    <?php endif; ?>
                    <li><a href="<?= BASE_URL ?>/logout" class="btn-outline-nav">Выйти</a></li>
                <?php else: ?>
                    <li><a href="<?= BASE_URL ?>/login">Войти</a></li>
                    <li><a href="<?= BASE_URL ?>/register" class="btn-nav">Регистрация</a></li>
                <?php endif; ?>
            </ul>
        </nav>

        <div class="burger" id="burger">
            <span></span><span></span><span></span>
        </div>

    </div>
</header>
