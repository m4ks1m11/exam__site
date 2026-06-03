<?php
require_once 'config/db.php';

if ($is_auth) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $birthdate = trim($_POST['birthdate'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Проверка
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Заполните обязательные поля';
    } elseif ($password !== $password_confirm) {
        $error = 'Пароли не совпадают';
    } elseif (strlen($password) < 6) {
        $error = 'Пароль минимум 6 символов';
    } else {
        // Проверка email
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email уже зарегистрирован';
        } else {
            // Регистрация
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, phone, birthdate, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $birthdate, $hash]);
            $success = 'Регистрация прошла успешно!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/style.css?v=black1">
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main>
        <div class="form-card">
            <h2>Регистрация</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <div class="form-footer">
                    <a href="login.php" class="btn btn-primary">Войти</a>
                </div>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label>ФИО *</label>
                        <input type="text" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Телефон</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Дата рождения</label>
                        <input type="date" name="birthdate" value="<?= htmlspecialchars($_POST['birthdate'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label>Пароль *</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label>Подтвердите пароль *</label>
                        <input type="password" id="password_confirm" name="password_confirm" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Зарегистрироваться</button>
                </form>
                <div class="form-footer">
                    Уже есть аккаунт? <a href="login.php">Войти</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>