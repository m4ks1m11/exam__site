<?php
require_once 'config/db.php';

if (!$is_auth) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';

// Данные пользователя
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Обновление профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $birthdate = trim($_POST['birthdate'] ?? '');

    if (empty($name) || empty($email)) {
        $error = 'Заполните обязательные поля';
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, birthdate = ? WHERE id = ?");
        $stmt->execute([$name, $email, $phone, $birthdate ?: null, $_SESSION['user_id']]);
        $_SESSION['name'] = $name;
        $success = 'Профиль обновлён';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
}

// Смена пароля
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $new_pass_confirm = $_POST['new_password_confirm'] ?? '';

    if (empty($current_pass) || empty($new_pass) || empty($new_pass_confirm)) {
        $error = 'Заполните все поля';
    } elseif (!password_verify($current_pass, $user['password'])) {
        $error = 'Неверный текущий пароль';
    } elseif ($new_pass !== $new_pass_confirm) {
        $error = 'Новые пароли не совпадают';
    } elseif (strlen($new_pass) < 6) {
        $error = 'Пароль минимум 6 символов';
    } else {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $_SESSION['user_id']]);
        $success = 'Пароль изменён';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" href="css/style.css?v=black1">
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main class="container section">
        <h1 class="section-title">Мой профиль</h1>

        <?php if ($error): ?>
            <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <div class="profile-card">
            <h2>Личные данные</h2>
            <form method="POST">
                <input type="hidden" name="update_profile" value="1">
                <div class="form-group">
                    <label>ФИО *</label>
                    <input type="text" name="name" required value="<?= htmlspecialchars($user['name']) ?>">
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
                </div>
                <div class="form-group">
                    <label>Телефон</label>
                    <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone']) ?>">
                </div>
                <div class="form-group">
                    <label>Дата рождения</label>
                    <input type="date" name="birthdate" value="<?= htmlspecialchars($user['birthdate'] ?? '') ?>">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Сохранить</button>
            </form>
        </div>

        <div class="profile-card" style="margin-top: 30px;">
            <h2>Смена пароля</h2>
            <form method="POST">
                <input type="hidden" name="change_password" value="1">
                <div class="form-group">
                    <label>Текущий пароль</label>
                    <input type="password" name="current_password">
                </div>
                <div class="form-group">
                    <label>Новый пароль</label>
                    <input type="password" name="new_password">
                </div>
                <div class="form-group">
                    <label>Подтвердите пароль</label>
                    <input type="password" name="new_password_confirm">
                </div>
                <button type="submit" class="btn btn-primary btn-block">Изменить пароль</button>
            </form>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>