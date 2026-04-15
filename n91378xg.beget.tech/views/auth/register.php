<?php
// views/auth/register.php
$pageTitle = 'Регистрация';
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

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Регистрация</h4>
        </div>

        <div class="card-body">

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= e($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success">
                    Регистрация успешна! <a href="<?= BASE_URL ?>/login">Войти</a>
                </div>
            <?php else: ?>

                <form method="POST" action="<?= BASE_URL ?>/register">
                    <div class="mb-3">
                        <label class="form-label">Имя пользователя</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Пароль</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Подтверждение пароля</label>
                        <input type="password" name="password_confirm" class="form-control" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-5">Зарегистрироваться</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="<?= BASE_URL ?>/login">Уже есть аккаунт? Войти</a>
                </div>

            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
