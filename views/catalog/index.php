<?php
// views/catalog/index.php
$pageTitle = 'Каталог услуг и мастеров';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="container mt-4">

    <!-- ПОИСК -->
    <div class="container mb-3 p-3 bg-white rounded shadow-sm">
        <div class="d-flex gap-2 mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Поиск мастера или услуги...">
            <button id="searchBtn" class="btn btn-primary">
                <i class="bi bi-search"></i>
            </button>
        </div>
    </div>

    <!-- УСЛУГИ -->
    <h2 class="mb-3 mt-4">Услуги</h2>
    <div class="row g-3">

    <?php foreach ($products as $product): ?>
        <div class="col-12 col-sm-6 col-md-4 search-item">
            <div class="card h-100">

                <?php $img = !empty($product['image_url'])
                    ? BASE_URL . '/' . e($product['image_url'])
                    : 'https://via.placeholder.com/300x200'; ?>
                <div class="card-img-wrap"><img src="<?= $img ?>" class="card-img-top" alt="Услуга"></div>

                <div class="card-body d-flex flex-column">
                    <h5><?= e($product['title']) ?></h5>
                    <p><?= e($product['description'] ?? '') ?></p>

                    <div class="mt-auto">
                        <span class="fw-bold text-primary">
                            <?= number_format($product['price'], 0, '', ' ') ?> ₽
                        </span>

                        <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                            <div class="d-flex gap-2 mt-2">
                                <a href="<?= BASE_URL ?>/admin/edit_item?id=<?= $product['id'] ?>"
                                   class="btn btn-warning w-50">Редактировать</a>

                                <form action="<?= BASE_URL ?>/admin/delete_item" method="POST" class="w-50"
                                      onsubmit="return confirm('Удалить услугу?');">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $product['id'] ?>">
                                    <button class="btn btn-danger w-100">Удалить</button>
                                </form>
                            </div>

                        <?php elseif (isset($_SESSION['user_id'])): ?>
                            <a href="<?= BASE_URL ?>/book?product_id=<?= $product['id'] ?>"
                               class="btn btn-primary w-100 mt-2">Записаться</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

    <!-- МАСТЕРА -->
    <h2 class="mb-3 mt-4">Мастера</h2>
    <div class="row g-3">

    <?php foreach ($masters as $master): ?>
        <div class="col-12 col-sm-6 col-md-4 search-item">
            <div class="card h-100">

                <?php $photo = !empty($master['image_url'])
                    ? BASE_URL . '/' . e($master['image_url'])
                    : 'https://via.placeholder.com/300x200?text=Мастер'; ?>
                <div class="card-img-wrap"><img src="<?= $photo ?>" class="card-img-top" alt="Мастер"></div>

                <div class="card-body d-flex flex-column">
                    <h5><?= e($master['name']) ?></h5>
                    <p class="text-muted mb-1"><?= e($master['specialization'] ?? 'Без специализации') ?></p>
                    <p class="small text-secondary">Стаж: <?= intval($master['experience_years'] ?? 0) ?> лет</p>

                    <div class="mt-auto">
                        <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                            <div class="d-flex gap-2 mt-2">
                                <form action="<?= BASE_URL ?>/admin/delete_master" method="POST"
                                      onsubmit="return confirm('Удалить мастера?');" class="w-50">
                                    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                                    <input type="hidden" name="id" value="<?= $master['id'] ?>">
                                    <button class="btn btn-danger w-100">Удалить</button>
                                </form>
                                <a href="<?= BASE_URL ?>/admin/master_photos?master_id=<?= $master['id'] ?>"
                                   class="btn btn-outline-primary w-50">Работы</a>
                            </div>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/portfolio?master_id=<?= $master['id'] ?>"
                               class="btn btn-outline-primary w-100 mt-2">Работы</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>

</div>

<style>
.container { max-width:1200px; padding-left:15px; padding-right:15px; margin:0 auto; }

/* Карточка */
.card {
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    transition: transform 0.3s, box-shadow 0.3s;
    overflow: hidden;        /* чтобы картинка не вылезала за скруглённые углы */
    border: none;
}
.card:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.13);
}

/* Обёртка картинки — задаём пропорцию 4:3 */
.card-img-wrap {
    width: 100%;
    aspect-ratio: 4 / 3;    /* картинка всегда занимает одинаковую площадь */
    overflow: hidden;
    background: #f0f0f0;     /* заглушка пока грузится */
}

/* Сама картинка заполняет блок целиком */
.card-img-top {
    width: 100%;
    height: 100%;
    object-fit: cover;       /* заполняет без пустых полос */
    object-position: center top; /* показываем верхнюю часть — лицо мастера видно */
    display: block;
    transition: transform 0.4s ease;
}
.card:hover .card-img-top {
    transform: scale(1.04);  /* лёгкий зум при наведении */
}

/* Адаптив */
@media (max-width: 576px) {
    .card-img-wrap { aspect-ratio: 3 / 2; }
}
</style>

<script>
document.getElementById('searchBtn').addEventListener('click', search);
document.getElementById('searchInput').addEventListener('input', search);
function search() {
    const val = document.getElementById('searchInput').value.toLowerCase().trim();
    document.querySelectorAll('.search-item').forEach(item => {
        item.style.display = item.innerText.toLowerCase().includes(val) ? '' : 'none';
    });
}
</script>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>