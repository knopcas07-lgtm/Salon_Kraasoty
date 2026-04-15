<?php
// views/layout/footer.php
// Подключается в конце каждой страницы
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if (!empty($extraJs)): ?>
    <script src="<?= BASE_URL . '/js/' . e($extraJs) ?>"></script>
<?php endif; ?>

<script>
// Бургер-меню
const burger  = document.getElementById('burger');
const navMenu = document.getElementById('nav-menu');
if (burger && navMenu) {
    burger.addEventListener('click', () => navMenu.classList.toggle('show'));
}
</script>

</body>
</html>
