<?php
require_once 'config/db.php';

if ($is_auth) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Заполните все поля';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header('Location: dashboard.php');
            exit;
        } else {
            $error = 'Неверный email или пароль';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="css/style.css?v=black1">
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main>
        <div class="form-card">
            <h2>Вход</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Войти</button>
            </form>
            <div class="form-footer">
                Нет аккаунта? <a href="register.php">Регистрация</a>
            </div>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>