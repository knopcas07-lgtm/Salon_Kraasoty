<?php
// views/admin/master_photos.php
$pageTitle = 'Фото мастера — ' . e($master['name']);
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="container mt-4">

    <a href="<?= BASE_URL ?>/masters" class="btn btn-secondary mb-3">← Назад к мастерам</a>
    <h3>Мастер: <?= e($master['name']) ?></h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= e($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= e($success) ?></div>
    <?php endif; ?>

    <!-- Загрузка фото -->
    <form action="<?= BASE_URL ?>/admin/master_photos?master_id=<?= (int)$master['id'] ?>"
          method="POST" enctype="multipart/form-data" class="mb-4">
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">
        <div class="mb-3">
            <label class="form-label">Фотографии работ</label>
            <input type="file" name="images[]" class="form-control"
                   accept=".jpg,.jpeg,.png,.gif" multiple required>
            <small class="text-muted">Можно выбрать несколько файлов. Форматы: JPG, PNG, GIF</small>
        </div>
        <button type="submit" class="btn btn-primary">Загрузить фото</button>
    </form>

    <!-- Галерея -->
    <div class="row">
        <?php if (count($portfolio) > 0): ?>
            <?php foreach ($portfolio as $item): ?>
                <div class="col-md-3 col-6 mb-3">
                    <img src="<?= BASE_URL . '/' . e($item['image_url']) ?>"
                         class="img-fluid rounded"
                         style="height:150px;width:100%;object-fit:cover;">

                    <form action="<?= BASE_URL ?>/admin/delete_photo" method="POST" class="mt-1"
                          onsubmit="return confirm('Удалить фото?');">
                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                        <input type="hidden" name="portfolio_id" value="<?= $item['id'] ?>">
                        <input type="hidden" name="master_id" value="<?= (int)$master['id'] ?>">
                        <button class="btn btn-danger btn-sm w-100">Удалить</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Портфолио пусто.</p>
        <?php endif; ?>
    </div>

</div>

<style>
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
