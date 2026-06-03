<?php
// Шапка сайта
$current_page = basename($_SERVER['PHP_SELF']);
?>
<header class="header">
    <div class="container">
        <a href="index.php" class="logo">Школа вокала Мьюзикл</a>
        <nav class="nav">
            <a href="index.php" <?= $current_page == 'index.php' ? 'class="active"' : '' ?>>Главная</a>
            <?php if ($is_auth): ?>
                <a href="dashboard.php" <?= $current_page == 'dashboard.php' ? 'class="active"' : '' ?>>Заявки</a>
                <a href="profile.php" <?= $current_page == 'profile.php' ? 'class="active"' : '' ?>>Профиль</a>
                <?php if ($is_admin): ?>
                    <a href="admin/" <?= strpos($_SERVER['REQUEST_URI'], '/admin') === 0 ? 'class="active"' : '' ?>>Админка</a>
                <?php endif; ?>
                <a href="logout.php">Выход</a>
            <?php else: ?>
                <a href="login.php" <?= $current_page == 'login.php' ? 'class="active"' : '' ?>>Вход</a>
                <a href="register.php" class="btn">Регистрация</a>
            <?php endif; ?>
        </nav>
    </div>
</header>