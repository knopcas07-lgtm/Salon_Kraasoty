<?php
// views/profile/index.php
$pageTitle = 'Мои записи';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="container mt-3 px-2">
    <a href="<?= BASE_URL ?>/" class="btn btn-light mb-2 d-inline-block">← Назад</a>
    <h1 class="mb-3">Мои записи</h1>
</div>

<div class="container">

    <?php if (count($appointments) === 0): ?>
        <div class="alert alert-info text-center">
            У вас пока нет записей.
            <a href="<?= BASE_URL ?>/">Запишитесь на услугу</a>.
        </div>
    <?php else: ?>

        <div class="row">
            <?php foreach ($appointments as $appt):
                $statusText  = statusLabel($appt['status']);
                $statusColor = statusClass($appt['status']);
                $cancellable = canCancel($appt['date'], $appt['time']);
            ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 p-3">

                        <h5><?= e($appt['product_title']) ?></h5>
                        <p><strong>Дата:</strong> <?= e($appt['date']) ?></p>
                        <p><strong>Время:</strong> <?= e($appt['time']) ?></p>
                        <p><strong>Мастер:</strong> <?= e($appt['master_name']) ?></p>

                        <span class="badge bg-<?= $statusColor ?>">
                            <?= $statusText ?>
                        </span>

                        <div class="mt-3 d-flex flex-wrap gap-2">
                            <?php if ($appt['status'] === STATUS_PENDING): ?>

                                <a href="<?= BASE_URL ?>/appointment/reschedule?id=<?= $appt['id'] ?>"
                                   class="btn btn-sm btn-outline-primary">Перенести</a>

                                <?php if ($cancellable): ?>
                                    <form action="<?= BASE_URL ?>/appointment/cancel" method="POST"
                                          onsubmit="return confirm('Вы уверены?');">
                                        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                        <input type="hidden" name="id" value="<?= $appt['id'] ?>">
                                        <button class="btn btn-sm btn-outline-danger">Отменить</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn btn-sm btn-outline-secondary" disabled>Отменить</button>
                                <?php endif; ?>

                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>

    <a href="<?= BASE_URL ?>/change_password" class="btn btn-outline-secondary mt-2 mb-4">
        Сменить пароль
    </a>

</div>

<style>
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
.card { border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.05); transition:.3s; }
.card:hover { transform:translateY(-4px); box-shadow:0 8px 18px rgba(0,0,0,.1); }
@media(max-width:768px){ .col-md-4{ flex:0 0 100%; max-width:100%; } }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
