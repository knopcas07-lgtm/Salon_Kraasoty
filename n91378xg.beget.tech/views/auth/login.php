<?php
// views/auth/login.php
$pageTitle = 'Вход';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    html, body { margin: 0; padding: 0; background-color: #f8f9fa; }
    .main-container { min-height: 100vh; display: flex; justify-content: center; align-items: center; }
    .card { width: 100%; max-width: 500px; margin: 10px; border-radius: 12px; }
    @media (max-width: 768px) { .card { max-width: 100%; } }
    </style>
</head>
<body>
<div class="main-container">
    <div class="card shadow">

        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Вход в систему</h4>
        </div>

        <div class="card-body">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/login">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-success px-5">Войти</button>
                </div>
            </form>

            <div class="mt-3 text-center">
                <a href="<?= BASE_URL ?>/register">Нет аккаунта? Зарегистрироваться</a>
            </div>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
