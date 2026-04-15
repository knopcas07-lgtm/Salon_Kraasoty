<?php
// views/admin/edit_master.php
$pageTitle = 'Редактировать мастера';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="main-container">

    <div class="container mb-3">
        <a href="<?= BASE_URL ?>/admin/index" class="btn btn-light mb-2 d-inline-block">← Назад в админку</a>
        <h2 class="text-center">Редактирование мастера</h2>
    </div>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= e($message['type']) ?>"><?= e($message['text']) ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/admin/edit_master?id=<?= (int)$master['id'] ?>"
          method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
        <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

        <div class="mb-3">
            <label class="form-label fw-semibold">Имя мастера</label>
            <input type="text" name="name" class="form-control"
                   value="<?= e($master['name']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Направление / специализация</label>
            <input type="text" name="specialization" class="form-control"
                   value="<?= e($master['specialization'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Описание</label>
            <textarea name="description" class="form-control"
                      rows="4"><?= e($master['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Опыт (лет)</label>
            <input type="number" name="experience_years" class="form-control"
                   min="0" value="<?= (int)($master['experience_years'] ?? 0) ?>">
        </div>

        <!-- ТЕКУЩЕЕ ФОТО -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Текущее главное фото</label><br>
            <?php if (!empty($master['image_url'])): ?>
                <img src="<?= BASE_URL . '/' . e($master['image_url']) ?>"
                     style="width:150px;height:150px;object-fit:cover;border-radius:10px;
                            box-shadow:0 2px 8px rgba(0,0,0,.1);">
                <p class="text-muted small mt-1">Загрузите новое фото ниже, чтобы заменить</p>
            <?php else: ?>
                <p class="text-muted small">Фото не загружено</p>
            <?php endif; ?>
        </div>

        <!-- НОВОЕ ФОТО -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Новое главное фото</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Форматы: JPG, PNG, GIF, WEBP. Макс. 5 MB.</small>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success flex-grow-1">Сохранить изменения</button>
            <a href="<?= BASE_URL ?>/admin/master_photos?master_id=<?= (int)$master['id'] ?>"
               class="btn btn-outline-primary">
                <i class="bi bi-images me-1"></i>Фото работ
            </a>
        </div>
    </form>

</div>

<style>
.main-container { max-width: 880px; margin: 0 auto; padding: 15px; }
.container { max-width: 1200px; padding: 0 15px; margin: 0 auto; }
.card { border-radius: 12px; }
@media (max-width: 768px) { .btn { width: 100%; } .d-flex.gap-2 { flex-direction: column; } }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>