<?php
// views/masters/index.php
$pageTitle = 'Мастера';
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="container mt-4">
    <h2 class="mb-4">Наши мастера</h2>

    <div class="row g-3">
    <?php foreach ($masters as $master): ?>
        <div class="col-12 col-sm-6 col-md-4">
            <div class="card h-100">
                <?php $photo = !empty($master['image_url'])
                    ? BASE_URL . '/' . e($master['image_url'])
                    : 'https://via.placeholder.com/300x200?text=Мастер'; ?>
                <div class="card-img-wrap"><img src="<?= $photo ?>" class="card-img-top" alt="<?= e($master['name']) ?>"></div>

                <div class="card-body d-flex flex-column">
                    <h5><?= e($master['name']) ?></h5>
                    <p class="text-muted mb-1"><?= e($master['specialization'] ?? '') ?></p>
                    <p class="small text-secondary">Стаж: <?= intval($master['experience_years'] ?? 0) ?> лет</p>
                    <?php if (!empty($master['description'])): ?>
                        <p class="small"><?= e($master['description']) ?></p>
                    <?php endif; ?>
                    <div class="mt-auto">
                        <a href="<?= BASE_URL ?>/portfolio?master_id=<?= $master['id'] ?>"
                           class="btn btn-outline-primary w-100 mt-2">Портфолио</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
</div>

<style>
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
.card { border-radius:14px; box-shadow:0 2px 10px rgba(0,0,0,.07); transition:.3s; overflow:hidden; border:none; }
.card:hover { transform:translateY(-6px); box-shadow:0 12px 28px rgba(0,0,0,.13); }
.card-img-wrap { width:100%; aspect-ratio:4/3; overflow:hidden; background:#f0f0f0; }
.card-img-top { width:100%; height:100%; object-fit:cover; object-position:center top; display:block; transition:transform .4s ease; }
.card:hover .card-img-top { transform:scale(1.04); }
@media(max-width:576px){ .card-img-wrap{ aspect-ratio:3/2; } }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>