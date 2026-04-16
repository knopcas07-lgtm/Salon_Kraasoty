<?php
// views/admin/appointments.php
$pageTitle = 'Записи — Админ';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="page-wrapper">

    <a href="<?= BASE_URL ?>/" class="btn btn-light mb-3">← Назад</a>
    <h3 class="mb-4">Все записи</h3>

    <div class="row">
        <?php foreach ($appointments as $a): ?>
            <div class="col-md-4 mb-3">
                <div class="card p-3" style="border-radius:12px;font-size:14px;">

                    <div><b>Пользователь:</b> <?= e($a['user_name'] ?? '-') ?></div>
                    <div><b>Мастер:</b> <?= e($a['master_name'] ?? '-') ?></div>
                    <div><b>Услуга:</b> <?= e($a['product_title'] ?? '-') ?></div>
                    <hr>
                    <div><b>Дата:</b> <?= e($a['date']) ?></div>
                    <div><b>Время:</b> <?= e($a['time']) ?></div>

                    <div class="mt-2 fw-bold">
                        Статус: <?= statusLabel($a['status']) ?>
                    </div>

                    <?php if ($a['status'] !== STATUS_CANCELLED): ?>
                        <form method="POST" action="<?= BASE_URL ?>/admin/update_status" class="mt-2">
                            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                            <input type="hidden" name="id" value="<?= $a['id'] ?>">
                            <select name="status" class="form-select form-select-sm">
                                <?php foreach ([STATUS_PENDING, STATUS_CONFIRMED, STATUS_COMPLETED, STATUS_CANCELLED] as $s): ?>
                                    <option value="<?= $s ?>" <?= $a['status'] === $s ? 'selected' : '' ?>>
                                        <?= statusLabel($s) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button class="btn btn-primary btn-sm mt-2 w-100">Сохранить</button>
                        </form>
                    <?php else: ?>
                        <div class="text-danger mt-2">Отменено</div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.page-wrapper { max-width:1200px; margin:0 auto; padding:30px 15px 20px; }
@media(max-width:768px){ .col-md-4{ flex:0 0 100%; max-width:100%; } }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
