<?php
// views/masters/portfolio.php
$pageTitle = 'Портфолио — ' . e($master['name']);
require ROOT_PATH . '/views/layout/header.php';
?>

<div class="container mt-4">
    <a href="<?= BASE_URL ?>/masters" class="btn btn-light mb-3">← Назад к мастерам</a>
    <h2><?= e($master['name']) ?></h2>
    <p class="text-muted"><?= e($master['specialization'] ?? '') ?></p>

    <div class="row g-3 mt-2">
        <?php if (count($portfolio) > 0): ?>
            <?php foreach ($portfolio as $item): ?>
                <div class="col-6 col-md-3">
                    <img src="<?= BASE_URL . '/' . e($item['image_url']) ?>"
                         class="img-fluid rounded"
                         style="height:200px;width:100%;object-fit:cover;transition:.3s;cursor:pointer;"
                         onmouseover="this.style.transform='scale(1.05)'"
                         onmouseout="this.style.transform='scale(1)'"
                         alt="Работа мастера">
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Портфолио пока пусто.</p>
        <?php endif; ?>
    </div>
</div>

<style>
.container { max-width:1200px; padding:0 15px; margin:0 auto; }
</style>

<?php require ROOT_PATH . '/views/layout/footer.php'; ?>
