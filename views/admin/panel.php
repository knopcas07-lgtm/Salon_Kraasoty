<?php
// views/admin/panel.php
$pageTitle = 'Панель администратора';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="container mt-4">

    <div class="alert alert-success">
        <h1>Панель Администратора</h1>
        <p>Управление салоном красоты</p>
    </div>

    <!-- КНОПКИ УПРАВЛЕНИЯ -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        <a href="<?= BASE_URL ?>/" class="btn btn-primary admin-btn">
            <i class="bi bi-house-door me-1"></i> На главную
        </a>
        <a href="<?= BASE_URL ?>/admin/appointments" class="btn btn-primary admin-btn">
            <i class="bi bi-calendar-check me-1"></i> Управление записями
        </a>
        <a href="<?= BASE_URL ?>/admin/add_item" class="btn btn-success admin-btn">
            <i class="bi bi-plus-lg me-1"></i> Добавить услугу
        </a>
        <a href="<?= BASE_URL ?>/admin/add_master" class="btn btn-success admin-btn">
            <i class="bi bi-person-plus me-1"></i> Добавить мастера
        </a>
    </div>

    <!-- ФОТО МАСТЕРОВ -->
    <div class="card p-3 shadow-sm" style="border-radius:12px;">
        <h5 class="mb-3"><i class="bi bi-images me-2"></i>Фото и портфолио мастеров</h5>

        <?php if (empty($masters)): ?>
            <p class="text-muted">Мастера ещё не добавлены.</p>
        <?php else: ?>
            <div class="row g-2">
                <?php foreach ($masters as $master): ?>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="d-flex align-items-center justify-content-between
                                    border rounded p-2 bg-light">

                            <div class="d-flex align-items-center gap-2">
                                <?php if (!empty($master['image_url'])): ?>
                                    <img src="<?= BASE_URL . '/' . e($master['image_url']) ?>"
                                         style="width:40px;height:40px;object-fit:cover;border-radius:50%;">
                                <?php else: ?>
                                    <div style="width:40px;height:40px;border-radius:50%;
                                                background:#dee2e6;display:flex;align-items:center;
                                                justify-content:center;">
                                        <i class="bi bi-person text-muted"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="fw-semibold"><?= e($master['name']) ?></span>
                            </div>

                            <div class="d-flex gap-1">
                                <a href="<?= BASE_URL ?>/admin/edit_master?id=<?= $master['id'] ?>"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil me-1"></i>Изменить
                                </a>
                                <a href="<?= BASE_URL ?>/admin/master_photos?master_id=<?= $master['id'] ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-camera me-1"></i>Фото работ
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="mt-3">
            <small class="text-muted">
                <i class="bi bi-info-circle me-1"></i>
                Главное фото мастера загружается при добавлении мастера.
                «Фото работ» — это портфолио (несколько фото).
            </small>
        </div>
    </div>

</div>

<style>
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
.admin-btn { min-width:200px; }
@media(max-width:768px){ .admin-btn{ width:100%; } }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>