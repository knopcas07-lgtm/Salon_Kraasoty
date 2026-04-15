<?php
// views/appointment/reschedule.php
$pageTitle = 'Перенос записи';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="container mt-3">
    <a href="javascript:history.back()" class="btn btn-light mb-3">← Назад</a>
    <h1 class="text-center mb-3">Перенос записи</h1>

    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="card p-4 shadow-sm" style="border-radius:12px;">

                <form action="<?= BASE_URL ?>/appointment/update" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                    <input type="hidden" name="id" value="<?= (int)$appt['id'] ?>">

                    <!-- ДАТА -->
                    <div class="mb-3">
                        <label class="form-label">Дата</label>
                        <input type="date" name="date" class="form-control"
                               value="<?= e($appt['date']) ?>" required>
                    </div>

                    <!-- ВРЕМЯ -->
                    <div class="mb-3">
                        <label class="form-label">Время</label>
                        <div class="d-flex flex-wrap gap-2">
                            <?php foreach ($timeSlots as $t): ?>
                                <button type="button"
                                        class="time-btn <?= $appt['time'] === $t ? 'active' : '' ?>"
                                        data-time="<?= e($t) ?>">
                                    <?= e($t) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="time" id="timeInput" value="<?= e($appt['time']) ?>">
                    </div>

                    <button class="btn btn-success w-100">Сохранить изменения</button>
                </form>

            </div>
        </div>
    </div>
</div>

<style>
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
.time-btn {
    padding:10px 16px; border-radius:10px; border:1px solid #007bff;
    background:#fff; cursor:pointer; transition:.2s;
}
.time-btn:hover, .time-btn.active { background:#007bff; color:#fff; }
@media(max-width:768px){ .time-btn{ width:100%; } }
</style>

<script>
const buttons   = document.querySelectorAll('.time-btn');
const timeInput = document.getElementById('timeInput');
buttons.forEach(btn => {
    btn.addEventListener('click', () => {
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        timeInput.value = btn.dataset.time;
    });
});
</script>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
