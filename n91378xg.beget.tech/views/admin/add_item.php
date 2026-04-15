<?php
// views/admin/add_item.php
$pageTitle = 'Добавить услугу';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="main-container">

    <div class="container mb-3">
        <a href="<?= BASE_URL ?>/admin/index" class="btn btn-light mb-2 d-inline-block">← Назад</a>
        <h1 class="text-center">Добавление услуги</h1>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= e($message['type']) ?>"><?= e($message['text']) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" action="<?= BASE_URL ?>/admin/add_item"
          class="card p-3 shadow-sm">
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

        <div class="mb-3">
            <label>Название:</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Цена (₽):</label>
            <input type="number" name="price" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Описание:</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label>Изображение:</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>
        <button class="btn btn-success">Сохранить</button>
    </form>

</div>

<style>
.main-container { max-width:1200px; margin:0 auto; padding:15px; }
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
.card { border-radius:12px; }
@media(max-width:768px){ .btn{ width:100%; } }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
