<?php
// views/profile/change_password.php
$pageTitle = 'Смена пароля';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="container mt-3">
    <a href="<?= BASE_URL ?>/profile" class="btn btn-light mb-2 d-inline-block">← Назад</a>
    <h1 class="mb-3 text-center">Смена пароля</h1>
</div>

<div class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="card p-3" style="border-radius:12px;">

                <div id="alertBox" class="alert" style="display:none;"></div>

                <form id="passwordForm">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf) ?>">

                    <div class="mb-2">
                        <input type="password" name="current_password" id="current_password"
                               class="form-control" placeholder="Текущий пароль" required>
                    </div>
                    <div class="mb-2">
                        <input type="password" name="new_password" id="new_password"
                               class="form-control" placeholder="Новый пароль" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="confirm_password" id="confirm_password"
                               class="form-control" placeholder="Повторите пароль" required>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="togglePassword">
                        <label class="form-check-label" for="togglePassword">Показать пароли</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Сохранить</button>
                </form>

            </div>
        </div>
    </div>
</div>

<style>
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
@media(max-width:768px){ .btn{ width:100%; } }
</style>

<script>
document.getElementById('togglePassword').addEventListener('change', function () {
    const type = this.checked ? 'text' : 'password';
    ['current_password','new_password','confirm_password'].forEach(id => {
        document.getElementById(id).type = type;
    });
});

document.getElementById('passwordForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('<?= BASE_URL ?>/change_password', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        const box = document.getElementById('alertBox');
        box.style.display = 'block';
        box.className = 'alert alert-' + (data.status === 'success' ? 'success' : 'danger');
        box.textContent = data.message;
    });
});
</script>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
