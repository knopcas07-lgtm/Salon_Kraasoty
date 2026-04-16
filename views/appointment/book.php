<?php
// views/appointment/book.php
$pageTitle = 'Запись на услугу';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="main-container">

    <a href="<?= BASE_URL ?>/profile" class="btn btn-light mb-2 d-inline-block">← Назад</a>
    <h1 class="text-center mb-3">Запись</h1>

    <form action="<?= BASE_URL ?>/appointment/save" method="POST" class="card p-3 shadow-sm">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="product_id" value="<?= (int)$product_id ?>">

        <!-- ДАТА -->
        <div class="mb-3">
            <label>Дата</label>
            <input type="text" name="date" id="dateInput" class="form-control"
                   placeholder="Выберите дату" required>
        </div>

        <!-- ВРЕМЯ -->
        <div id="timeWrapper" class="mb-3" style="opacity:0;transform:translateY(15px);transition:.3s;">
            <label>Время</label>
            <div id="timeButtons" class="d-flex flex-wrap gap-2"></div>
            <input type="hidden" name="time" id="timeInput" required>
        </div>

        <!-- МАСТЕР -->
        <div class="mb-3">
            <label>Мастер</label>
            <select name="master_id" id="masterSelect" class="form-select" required>
                <option value="">Сначала выберите время</option>
            </select>
        </div>

        <button class="btn btn-primary">Записаться</button>
    </form>

</div>

<!-- CSS с SRI -->
<link rel="stylesheet" 
      href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"
      integrity="sha384-EZs1k2mHkY8rJWK9FhG5ZO6UJyX5UY5jEJT3oV9kE6k1nxjC8I6jSqfCkU8yR5b"
      crossorigin="anonymous">

<!-- JavaScript с SRI -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"
        integrity="sha384-qF7mJh8dF4D/VsHlE9GxT+qn8pFtkWz1zXjJj8qSj8l3E6nLpD6otQjfYjC5lMf"
        crossorigin="anonymous"></script>

<style>
.main-container { max-width:600px; margin:0 auto; padding:15px; }
.card { border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.05); }
.time-btn {
    padding:10px 16px; border:1px solid #007bff; border-radius:10px;
    background:#fff; cursor:pointer; transition:.25s; font-weight:500;
}
.time-btn:hover, .time-btn.active { background:#007bff; color:#fff; }
.time-btn.disabled { background:#eee; border-color:#ccc; color:#aaa; cursor:not-allowed; }
@media(max-width:768px){ .btn{ width:100%; } }
</style>

<script>
const timeSlots = <?= json_encode(TIME_SLOTS) ?>;
const timeContainer = document.getElementById('timeButtons');
const timeInput     = document.getElementById('timeInput');
const timeWrapper   = document.getElementById('timeWrapper');
const masterSelect  = document.getElementById('masterSelect');

flatpickr('#dateInput', {
    minDate: 'today',
    dateFormat: 'Y-m-d',
    onChange(selectedDates, dateStr) {
        fetch('<?= BASE_URL ?>/get_busy?date=' + dateStr)
        .then(r => r.json())
        .then(data => {
            timeContainer.innerHTML = '';
            timeInput.value = '';
            masterSelect.innerHTML = '<option value="">Сначала выберите время</option>';

            timeSlots.forEach((time, i) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.textContent = time;
                btn.className = 'time-btn';

                if (data.busyTimes.includes(time)) {
                    btn.classList.add('disabled');
                    btn.disabled = true;
                }

                btn.addEventListener('click', () => {
                    document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    timeInput.value = time;

                    fetch(`<?= BASE_URL ?>/get_masters?date=${dateStr}&time=${time}`)
                    .then(r => r.json())
                    .then(masters => {
                        masterSelect.innerHTML = '<option value="">Выберите мастера</option>';
                        if (masters.length === 0) {
                            masterSelect.innerHTML = '<option>Нет свободных мастеров</option>';
                        } else {
                            masters.forEach(m => {
                                const opt = document.createElement('option');
                                opt.value = m.id;
                                opt.textContent = m.name;
                                masterSelect.appendChild(opt);
                            });
                        }
                    });
                });

                btn.style.cssText = 'opacity:0;transform:translateY(10px);transition:.3s;';
                setTimeout(() => { btn.style.opacity = 1; btn.style.transform = 'translateY(0)'; }, i * 80);
                timeContainer.appendChild(btn);
            });

            timeWrapper.style.opacity = 1;
            timeWrapper.style.transform = 'translateY(0)';
        });
    }
});
</script>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
