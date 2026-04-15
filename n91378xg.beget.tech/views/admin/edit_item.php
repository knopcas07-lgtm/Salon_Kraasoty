<?php
// views/admin/edit_item.php
$pageTitle = 'Редактировать услугу';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="main-container">

    <div class="container mb-3">
        <a href="javascript:history.back()" class="btn btn-light mb-2 d-inline-block">← Назад</a>
        <h1 class="text-center">Редактирование услуги</h1>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= e($message['type']) ?>"><?= e($message['text']) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data"
          action="<?= BASE_URL ?>/admin/edit_item?id=<?= (int)$product['id'] ?>"
          class="card p-3 shadow-sm">
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

        <div class="mb-3">
            <label>Название</label>
            <input type="text" name="title" class="form-control"
                   value="<?= e($product['title']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Цена (₽)</label>
            <input type="number" name="price" class="form-control"
                   value="<?= (int)$product['price'] ?>" required>
        </div>
        <div class="mb-3">
            <label>Описание</label>
            <textarea name="description" class="form-control"><?= e($product['description'] ?? '') ?></textarea>
        </div>

        <?php if (!empty($product['image_url'])): ?>
            <div class="mb-3">
                <label>Текущее изображение:</label><br>
                <img src="<?= BASE_URL . '/' . e($product['image_url']) ?>"
                     style="max-width:100%;height:150px;object-fit:cover;border-radius:8px;">
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label>Новое изображение (если нужно заменить)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <button class="btn btn-success w-100">Сохранить</button>
    </form>
</div>

<style>
.main-container { max-width:1200px; margin:0 auto; padding:15px; }
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
.card { border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
@media(max-width:768px){ .btn{ width:100%; } }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
