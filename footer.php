<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<footer class="footer">
    <div class="container">
        <nav class="footer-nav">
            <a href="index.php" <?= $current_page == 'index.php' ? 'class="active"' : '' ?>>Главная</a>
            <?php if ($is_auth): ?>
                <a href="dashboard.php" <?= $current_page == 'dashboard.php' ? 'class="active"' : '' ?>>Заявки</a>
                <a href="profile.php" <?= $current_page == 'profile.php' ? 'class="active"' : '' ?>>Профиль</a>
                <?php if ($is_admin): ?>
                    <a href="admin/">Админка</a>
                <?php endif; ?>
                <a href="logout.php">Выход</a>
            <?php else: ?>
                <a href="login.php" <?= $current_page == 'login.php' ? 'class="active"' : '' ?>>Вход</a>
                <a href="register.php" <?= $current_page == 'register.php' ? 'class="active"' : '' ?>>Регистрация</a>
            <?php endif; ?>
        </nav>
        <p>&copy; 2026 Максим Шилов</p>
    </div>
</footer>
