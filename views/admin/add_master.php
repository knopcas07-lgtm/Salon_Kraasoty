<?php
// views/admin/add_master.php
$pageTitle = 'Добавить мастера';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="main-container">

    <div class="container mb-3">
        <a href="<?= BASE_URL ?>/admin/index" class="btn btn-light mb-2 d-inline-block">← Назад</a>
        <h2 class="text-center">Добавление мастера</h2>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/admin/add_master" method="POST" enctype="multipart/form-data"
          class="card p-4 shadow-sm">
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

        <div class="mb-3">
            <label class="form-label">Имя мастера</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Направление</label>
            <input type="text" name="specialization" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Описание</label>
            <textarea name="description" class="form-control" rows="4"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Опыт (лет)</label>
            <input type="number" name="experience_years" class="form-control" min="0" value="0">
        </div>
        <div class="mb-3">
            <label class="form-label">Фотография</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button type="submit" class="btn btn-success">Добавить мастера</button>
    </form>
</div>

<style>
.main-container { max-width:880px; margin:0 auto; padding:15px; }
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
.card { border-radius:12px; }
@media(max-width:768px){ .btn{ width:100%; } }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
