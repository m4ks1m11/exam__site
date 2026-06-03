<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Инструкция</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',sans-serif;background:#fff;color:#333;line-height:1.7;padding:40px 20px}
.c{max-width:1000px;margin:0 auto}
h1{font-size:1.4rem;font-weight:600;margin-bottom:30px}
h2{font-size:1rem;font-weight:600;margin:35px 0 12px}
p{margin-bottom:12px;color:#555;font-size:0.95rem}
.b{background:#f8f9fa;border-radius:8px;padding:20px;margin-bottom:15px}
.bt{font-weight:600;margin-bottom:12px;font-size:0.95rem}
.bn{color:#999;font-size:0.8rem;margin-bottom:3px}
textarea{width:100%;border:1px solid #e8e8e8;background:#fff;font-family:Consolas,monospace;font-size:0.8rem;line-height:1.5;padding:12px;border-radius:0 0 6px 6px}
.btn{display:inline-block;background:#e8e8e8;color:#333;padding:6px 14px;border-radius:5px;font-size:0.85rem;border:none;cursor:pointer}
.btn:hover{background:#ddd}
.fh{background:#f5f5f5;padding:8px 12px;border-radius:6px 6px 0 0;border:1px solid #e8e8e8;border-bottom:none;font-family:Consolas,monospace;font-size:0.85rem;color:#666;display:flex;justify-content:space-between}
.fc{border:1px solid #e8e8e8;border-radius:0 0 6px 6px;margin-bottom:20px}
hr{border:none;border-top:1px solid #eee;margin:35px 0}
footer{border-top:1px solid #eee;padding:25px 0;text-align:center;margin-top:40px;color:#999;font-size:0.85rem}
.fl{background:#f8f9fa;padding:15px 20px;border-radius:8px;font-family:Consolas,monospace;font-size:0.85rem;color:#666;white-space:pre}
</style>
</head>
<body>
<div class="c">

<h1>Инструкция по установке</h1>

<div class="b">
<div class="bn">Шаг 1</div>
<div class="bt">Создайте базу данных</div>
<p>Откройте phpMyAdmin:</p>
<div class="fc">
<div class="fh"><span>SQL</span><button class="btn" onclick="copy('s1')">Копировать</button></div>
<textarea id="s1">CREATE DATABASE IF NOT EXISTS transport_courses CHARACTER SET utf8 COLLATE utf8_general_ci;</textarea>
</div>
</div>

<div class="b">
<div class="bn">Шаг 2</div>
<div class="bt">Создайте таблицы</div>
<div class="fc">
<div class="fh"><span>SQL</span><button class="btn" onclick="copy('s2')">Копировать</button></div>
<textarea id="s2">CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT '',
    birthdate DATE DEFAULT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    service VARCHAR(100) NOT NULL,
    status ENUM('new', 'processing', 'completed', 'cancelled') DEFAULT 'new',
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role) VALUES
('Администратор', 'admin@mail.ru', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');</textarea>
</div>
</div>

<div class="b">
<div class="bn">Шаг 3</div>
<div class="bt">Структура файлов</div>
<div class="fl">project/
├── config/db.php
├── templates/header.php, footer.php
├── admin/index.php, users.php
├── css/style.css
├── js/main.js
├── index.php, login.php, register.php
├── logout.php, dashboard.php, new-request.php
├── profile.php, docs.php</div>
</div>

<hr>

<h2>Коды файлов</h2>

<div class="fc">
<div class="fh"><span>config/db.php</span><button class="btn" onclick="copy('f1')">Копировать</button></div>
<textarea id="f1">&lt;?php
// Настройки базы данных
$db_host = 'localhost';
$db_name = 'transport_courses';
$db_user = 'root';
$db_pass = '';

// Подключение
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo-&gt;setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo-&gt;setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Ошибка базы данных');
}

// Сессия
session_start();

// Проверка авторизации
$is_auth = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) &amp;&amp; $_SESSION['role'] === 'admin';
$user_name = $_SESSION['name'] ?? '';</textarea>
</div>

<div class="fc">
<div class="fh"><span>templates/header.php</span><button class="btn" onclick="copy('f2')">Копировать</button></div>
<textarea id="f2">&lt;?php
// Шапка сайта
$current_page = basename($_SERVER['PHP_SELF']);
?&gt;
&lt;header class="header"&gt;
    &lt;div class="container"&gt;
        &lt;a href="index.php" class="logo"&gt;КурсыВождения&lt;/a&gt;
        &lt;nav class="nav"&gt;
            &lt;a href="index.php" &lt;?= $current_page == 'index.php' ? 'class="active"' : '' ?&gt;&gt;Главная&lt;/a&gt;
            &lt;?php if ($is_auth): ?&gt;
                &lt;a href="dashboard.php" &lt;?= $current_page == 'dashboard.php' ? 'class="active"' : '' ?&gt;&gt;Заявки&lt;/a&gt;
                &lt;a href="profile.php" &lt;?= $current_page == 'profile.php' ? 'class="active"' : '' ?&gt;&gt;Профиль&lt;/a&gt;
                &lt;?php if ($is_admin): ?&gt;
                    &lt;a href="admin/" &lt;?= strpos($_SERVER['REQUEST_URI'], '/admin') === 0 ? 'class="active"' : '' ?&gt;&gt;Админка&lt;/a&gt;
                &lt;?php endif; ?&gt;
                &lt;a href="logout.php"&gt;Выход&lt;/a&gt;
            &lt;?php else: ?&gt;
                &lt;a href="login.php" &lt;?= $current_page == 'login.php' ? 'class="active"' : '' ?&gt;&gt;Вход&lt;/a&gt;
                &lt;a href="register.php" class="btn"&gt;Регистрация&lt;/a&gt;
            &lt;?php endif; ?&gt;
        &lt;/nav&gt;
    &lt;/div&gt;
&lt;/header&gt;</textarea>
</div>

<div class="fc">
<div class="fh"><span>templates/footer.php</span><button class="btn" onclick="copy('f3')">Копировать</button></div>
<textarea id="f3">&lt;footer class="footer"&gt;
    &lt;div class="container"&gt;
        &lt;p&gt;&copy; 2026 КурсыВождения&lt;/p&gt;
    &lt;/div&gt;
&lt;/footer&gt;</textarea>
</div>

<div class="fc">
<div class="fh"><span>index.php</span><button class="btn" onclick="copy('f4')">Копировать</button></div>
<textarea id="f4">&lt;?php
require_once 'config/db.php';
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="ru"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Курсы вождения - Главная&lt;/title&gt;
    &lt;link rel="stylesheet" href="css/style.css?v=black1"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;?php include 'templates/header.php'; ?&gt;

    &lt;section class="hero"&gt;
        &lt;h1&gt;Курсы вождения общественного транспорта&lt;/h1&gt;
        &lt;p&gt;Трамваи, автобусы, электробусы — стань профессиональным водителем&lt;/p&gt;
        &lt;?php if ($is_auth): ?&gt;
            &lt;a href="new-request.php" class="btn btn-primary"&gt;Подать заявку&lt;/a&gt;
        &lt;?php else: ?&gt;
            &lt;a href="register.php" class="btn btn-primary"&gt;Записаться на курсы&lt;/a&gt;
        &lt;?php endif; ?&gt;
    &lt;/section&gt;

    &lt;section class="section"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="section-title"&gt;Наши курсы&lt;/h2&gt;
            &lt;div class="services-grid"&gt;
                &lt;div class="service-card"&gt;
                    &lt;h3&gt;Курс трамвая&lt;/h3&gt;
                    &lt;p&gt;Обучение вождению трамвая. Теория, практика на тренажёре и реальном трамвае.&lt;/p&gt;
                    &lt;div class="price"&gt;от 45 000 ₽&lt;/div&gt;
                    &lt;a href="&lt;?= $is_auth ? 'new-request.php?service=tram' : 'register.php' ?&gt;" class="btn btn-primary"&gt;Выбрать&lt;/a&gt;
                &lt;/div&gt;
                &lt;div class="service-card"&gt;
                    &lt;h3&gt;Курс автобуса&lt;/h3&gt;
                    &lt;p&gt;Профессиональные курсы для водителей автобусов. Категория D.&lt;/p&gt;
                    &lt;div class="price"&gt;от 50 000 ₽&lt;/div&gt;
                    &lt;a href="&lt;?= $is_auth ? 'new-request.php?service=bus' : 'register.php' ?&gt;" class="btn btn-primary"&gt;Выбрать&lt;/a&gt;
                &lt;/div&gt;
                &lt;div class="service-card"&gt;
                    &lt;h3&gt;Курс электробуса&lt;/h3&gt;
                    &lt;p&gt;Современные электробусы — будущее городского транспорта. Обучение от экспертов.&lt;/p&gt;
                    &lt;div class="price"&gt;от 55 000 ₽&lt;/div&gt;
                    &lt;a href="&lt;?= $is_auth ? 'new-request.php?service=trolleybus' : 'register.php' ?&gt;" class="btn btn-primary"&gt;Выбрать&lt;/a&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;section class="section" style="background: #fff;"&gt;
        &lt;div class="container"&gt;
            &lt;h2 class="section-title"&gt;Почему мы&lt;/h2&gt;
            &lt;div class="benefits-grid"&gt;
                &lt;div class="benefit-item"&gt;
                    &lt;div class="icon"&gt;🎓&lt;/div&gt;
                    &lt;h3&gt;Опытные инструкторы&lt;/h3&gt;
                    &lt;p&gt;Преподаватели с многолетним стажем&lt;/p&gt;
                &lt;/div&gt;
                &lt;div class="benefit-item"&gt;
                    &lt;div class="icon"&gt;🏆&lt;/div&gt;
                    &lt;h3&gt;Лицензия&lt;/h3&gt;
                    &lt;p&gt;Полный пакет документов&lt;/p&gt;
                &lt;/div&gt;
                &lt;div class="benefit-item"&gt;
                    &lt;div class="icon"&gt;🚍&lt;/div&gt;
                    &lt;h3&gt;Современные автопарк&lt;/h3&gt;
                    &lt;p&gt;Тренажёры и реальная техника&lt;/p&gt;
                &lt;/div&gt;
                &lt;div class="benefit-item"&gt;
                    &lt;div class="icon"&gt;💼&lt;/div&gt;
                    &lt;h3&gt;Трудоустройство&lt;/h3&gt;
                    &lt;p&gt;Помогаем с поиском работы&lt;/p&gt;
                &lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/section&gt;

    &lt;?php include 'templates/footer.php'; ?&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
</div>

<div class="fc">
<div class="fh"><span>login.php</span><button class="btn" onclick="copy('f5')">Копировать</button></div>
<textarea id="f5">&lt;?php
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
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="ru"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Вход&lt;/title&gt;
    &lt;link rel="stylesheet" href="css/style.css?v=black1"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;?php include 'templates/header.php'; ?&gt;

    &lt;main&gt;
        &lt;div class="form-card"&gt;
            &lt;h2&gt;Вход&lt;/h2&gt;

            &lt;?php if ($error): ?&gt;
                &lt;div class="alert alert-error"&gt;&lt;?= htmlspecialchars($error) ?&gt;&lt;/div&gt;
            &lt;?php endif; ?&gt;

            &lt;form method="POST"&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;Email&lt;/label&gt;
                    &lt;input type="email" id="email" name="email" required value="&lt;?= htmlspecialchars($_POST['email'] ?? '') ?&gt;"&gt;
                &lt;/div&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;Пароль&lt;/label&gt;
                    &lt;input type="password" id="password" name="password" required&gt;
                &lt;/div&gt;
                &lt;button type="submit" class="btn btn-primary btn-block"&gt;Войти&lt;/button&gt;
            &lt;/form&gt;
            &lt;div class="form-footer"&gt;
                Нет аккаунта? &lt;a href="register.php"&gt;Регистрация&lt;/a&gt;
            &lt;/div&gt;
        &lt;/div&gt;
    &lt;/main&gt;

    &lt;?php include 'templates/footer.php'; ?&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
</div>

<div class="fc">
<div class="fh"><span>register.php</span><button class="btn" onclick="copy('f6')">Копировать</button></div>
<textarea id="f6">&lt;?php
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
    } elseif (strlen($password) &lt; 6) {
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
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="ru"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Регистрация&lt;/title&gt;
    &lt;link rel="stylesheet" href="css/style.css?v=black1"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;?php include 'templates/header.php'; ?&gt;

    &lt;main&gt;
        &lt;div class="form-card"&gt;
            &lt;h2&gt;Регистрация&lt;/h2&gt;

            &lt;?php if ($error): ?&gt;
                &lt;div class="alert alert-error"&gt;&lt;?= htmlspecialchars($error) ?&gt;&lt;/div&gt;
            &lt;?php endif; ?&gt;

            &lt;?php if ($success): ?&gt;
                &lt;div class="alert alert-success"&gt;&lt;?= htmlspecialchars($success) ?&gt;&lt;/div&gt;
                &lt;div class="form-footer"&gt;
                    &lt;a href="login.php" class="btn btn-primary"&gt;Войти&lt;/a&gt;
                &lt;/div&gt;
            &lt;?php else: ?&gt;
                &lt;form method="POST"&gt;
                    &lt;div class="form-group"&gt;
                        &lt;label&gt;ФИО *&lt;/label&gt;
                        &lt;input type="text" id="name" name="name" required value="&lt;?= htmlspecialchars($_POST['name'] ?? '') ?&gt;"&gt;
                    &lt;/div&gt;
                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Email *&lt;/label&gt;
                        &lt;input type="email" id="email" name="email" required value="&lt;?= htmlspecialchars($_POST['email'] ?? '') ?&gt;"&gt;
                    &lt;/div&gt;
                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Телефон&lt;/label&gt;
                        &lt;input type="tel" name="phone" value="&lt;?= htmlspecialchars($_POST['phone'] ?? '') ?&gt;"&gt;
                    &lt;/div&gt;
                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Дата рождения&lt;/label&gt;
                        &lt;input type="date" name="birthdate" value="&lt;?= htmlspecialchars($_POST['birthdate'] ?? '') ?&gt;"&gt;
                    &lt;/div&gt;
                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Пароль *&lt;/label&gt;
                        &lt;input type="password" id="password" name="password" required&gt;
                    &lt;/div&gt;
                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Подтвердите пароль *&lt;/label&gt;
                        &lt;input type="password" id="password_confirm" name="password_confirm" required&gt;
                    &lt;/div&gt;
                    &lt;button type="submit" class="btn btn-primary btn-block"&gt;Зарегистрироваться&lt;/button&gt;
                &lt;/form&gt;
                &lt;div class="form-footer"&gt;
                    Уже есть аккаунт? &lt;a href="login.php"&gt;Войти&lt;/a&gt;
                &lt;/div&gt;
            &lt;?php endif; ?&gt;
        &lt;/div&gt;
    &lt;/main&gt;

    &lt;?php include 'templates/footer.php'; ?&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
</div>

<div class="fc">
<div class="fh"><span>logout.php</span><button class="btn" onclick="copy('f7')">Копировать</button></div>
<textarea id="f7">&lt;?php session_start();session_destroy();header('Location:index.php');exit;</textarea>
</div>

<hr>

<div class="fc">
<div class="fh"><span>dashboard.php</span><button class="btn" onclick="copy('f8')">Копировать</button></div>
<textarea id="f8">&lt;?php
require_once 'config/db.php';

if (!$is_auth) {
    header('Location: login.php');
    exit;
}

// Заявки пользователя
$stmt = $pdo->prepare("SELECT * FROM requests WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$requests = $stmt->fetchAll();

// Услуги
$services_list = [
    'tram' => 'Курс трамвая',
    'bus' => 'Курс автобуса',
    'trolleybus' => 'Курс электробуса',
];

// Статусы
$statuses = [
    'new' => ['label' => 'Новая', 'class' => 'status-new'],
    'processing' => ['label' => 'В обработке', 'class' => 'status-processing'],
    'completed' => ['label' => 'Завершена', 'class' => 'status-completed'],
    'cancelled' => ['label' => 'Отменена', 'class' => 'status-cancelled'],
];

// Время занятий
$time_labels = [
    'morning' => 'Утро (08:00 - 12:00)',
    'day' => 'День (12:00 - 17:00)',
    'evening' => 'Вечер (17:00 - 21:00)',
];

// Способы оплаты
$payment_labels = [
    'cash' => 'Наличными',
    'card' => 'Картой',
    'bank' => 'Банковский перевод',
];
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="ru"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Мои заявки&lt;/title&gt;
    &lt;link rel="stylesheet" href="css/style.css?v=black1"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;?php include 'templates/header.php'; ?&gt;

    &lt;main class="container section"&gt;
        &lt;h1 class="section-title"&gt;Мои заявки&lt;/h1&gt;

        &lt;div style="text-align: center; margin-bottom: 30px;"&gt;
            &lt;a href="new-request.php" class="btn btn-primary"&gt;Новая заявка&lt;/a&gt;
        &lt;/div&gt;

        &lt;?php if (empty($requests)): ?&gt;
            &lt;div class="empty-state"&gt;
                &lt;h3&gt;У вас пока нет заявок&lt;/h3&gt;
                &lt;p&gt;Подайте заявку на интересующий курс&lt;/p&gt;
            &lt;/div&gt;
        &lt;?php else: ?&gt;
            &lt;div class="requests-list"&gt;
                &lt;?php foreach ($requests as $req):
                    $details = json_decode($req['details'], true) ?? [];
                ?&gt;
                    &lt;div class="request-item"&gt;
                        &lt;div class="request-info"&gt;
                            &lt;h4&gt;&lt;?= htmlspecialchars($services_list[$req['service']] ?? $req['service']) ?&gt;&lt;/h4&gt;
                            &lt;p&gt;&lt;?= date('d.m.Y H:i', strtotime($req['created_at'])) ?&gt;&lt;/p&gt;

                            &lt;?php if (!empty($details['schedule_days'])): ?&gt;
                                &lt;p&gt;&lt;strong&gt;Дни:&lt;/strong&gt; &lt;?= htmlspecialchars($details['schedule_days']) ?&gt;&lt;/p&gt;
                            &lt;?php endif; ?&gt;

                            &lt;?php if (!empty($details['schedule_time'])): ?&gt;
                                &lt;p&gt;&lt;strong&gt;Время:&lt;/strong&gt; &lt;?= htmlspecialchars($time_labels[$details['schedule_time']] ?? $details['schedule_time']) ?&gt;&lt;/p&gt;
                            &lt;?php endif; ?&gt;

                            &lt;?php if (!empty($details['payment_method'])): ?&gt;
                                &lt;p&gt;&lt;strong&gt;Оплата:&lt;/strong&gt; &lt;?= htmlspecialchars($payment_labels[$details['payment_method']] ?? $details['payment_method']) ?&gt;&lt;/p&gt;
                            &lt;?php endif; ?&gt;

                            &lt;?php if (!empty($details['comment'])): ?&gt;
                                &lt;p&gt;&lt;strong&gt;Комментарий:&lt;/strong&gt; &lt;?= htmlspecialchars($details['comment']) ?&gt;&lt;/p&gt;
                            &lt;?php endif; ?&gt;
                        &lt;/div&gt;
                        &lt;span class="request-status &lt;?= $statuses[$req['status']]['class'] ?&gt;"&gt;
                            &lt;?= $statuses[$req['status']]['label'] ?&gt;
                        &lt;/span&gt;
                    &lt;/div&gt;
                &lt;?php endforeach; ?&gt;
            &lt;/div&gt;
        &lt;?php endif; ?&gt;
    &lt;/main&gt;

    &lt;?php include 'templates/footer.php'; ?&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
</div>

<div class="fc">
<div class="fh"><span>new-request.php</span><button class="btn" onclick="copy('f9')">Копировать</button></div>
<textarea id="f9">&lt;?php
require_once 'config/db.php';

if (!$is_auth) {
    header('Location: login.php');
    exit;
}

$error = '';
$success = '';
$selected_service = $_GET['service'] ?? '';

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = trim($_POST['service'] ?? '');
    $details = trim($_POST['details'] ?? '');

    // Дополнительные данные заявки
    $schedule_days = trim($_POST['schedule_days'] ?? '');
    $schedule_time = trim($_POST['schedule_time'] ?? '');
    $payment_method = trim($_POST['payment_method'] ?? '');

    if (empty($service)) {
        $error = 'Выберите услугу';
    } else {
        // Собираем все данные в JSON
        $request_data = json_encode([
            'schedule_days' => $schedule_days,
            'schedule_time' => $schedule_time,
            'payment_method' => $payment_method,
            'comment' => $details
        ], JSON_UNESCAPED_UNICODE);

        $stmt = $pdo->prepare("INSERT INTO requests (user_id, service, details) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $service, $request_data]);
        $success = 'Заявка отправлена!';
    }
}

// Услуги
$services_list = [
    'tram' => 'Курс трамвая',
    'bus' => 'Курс автобуса',
    'trolleybus' => 'Курс электробуса',
];
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="ru"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Новая заявка&lt;/title&gt;
    &lt;link rel="stylesheet" href="css/style.css?v=black1"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;?php include 'templates/header.php'; ?&gt;

    &lt;main&gt;
        &lt;div class="form-card"&gt;
            &lt;h2&gt;Новая заявка&lt;/h2&gt;

            &lt;?php if ($error): ?&gt;
                &lt;div class="alert alert-error"&gt;&lt;?= htmlspecialchars($error) ?&gt;&lt;/div&gt;
            &lt;?php endif; ?&gt;

            &lt;?php if ($success): ?&gt;
                &lt;div class="alert alert-success"&gt;&lt;?= htmlspecialchars($success) ?&gt;&lt;/div&gt;
                &lt;div class="form-footer"&gt;
                    &lt;a href="dashboard.php" class="btn btn-primary"&gt;К заявкам&lt;/a&gt;
                &lt;/div&gt;
            &lt;?php else: ?&gt;
                &lt;form method="POST"&gt;
                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Услуга *&lt;/label&gt;
                        &lt;select id="service" name="service" required&gt;
                            &lt;option value=""&gt;-- Выберите --&lt;/option&gt;
                            &lt;?php foreach ($services_list as $key => $name): ?&gt;
                                &lt;option value="&lt;?= $key ?&gt;" &lt;?= ($selected_service ?: ($_POST['service'] ?? '')) === $key ? 'selected' : '' ?&gt;&gt;
                                    &lt;?= $name ?&gt;
                                &lt;/option&gt;
                            &lt;?php endforeach; ?&gt;
                        &lt;/select&gt;
                    &lt;/div&gt;

                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Удобные дни занятий&lt;/label&gt;
                        &lt;input type="text" name="schedule_days" placeholder="Пн, Ср, Пт" value="&lt;?= htmlspecialchars($_POST['schedule_days'] ?? '') ?&gt;"&gt;
                    &lt;/div&gt;

                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Удобное время&lt;/label&gt;
                        &lt;select name="schedule_time"&gt;
                            &lt;option value=""&gt;-- Выберите --&lt;/option&gt;
                            &lt;option value="morning" &lt;?= ($_POST['schedule_time'] ?? '') === 'morning' ? 'selected' : '' ?&gt;&gt;Утро (08:00 - 12:00)&lt;/option&gt;
                            &lt;option value="day" &lt;?= ($_POST['schedule_time'] ?? '') === 'day' ? 'selected' : '' ?&gt;&gt;День (12:00 - 17:00)&lt;/option&gt;
                            &lt;option value="evening" &lt;?= ($_POST['schedule_time'] ?? '') === 'evening' ? 'selected' : '' ?&gt;&gt;Вечер (17:00 - 21:00)&lt;/option&gt;
                        &lt;/select&gt;
                    &lt;/div&gt;

                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Способ оплаты&lt;/label&gt;
                        &lt;select name="payment_method"&gt;
                            &lt;option value=""&gt;-- Выберите --&lt;/option&gt;
                            &lt;option value="cash" &lt;?= ($_POST['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?&gt;&gt;Наличными&lt;/option&gt;
                            &lt;option value="card" &lt;?= ($_POST['payment_method'] ?? '') === 'card' ? 'selected' : '' ?&gt;&gt;Картой&lt;/option&gt;
                            &lt;option value="bank" &lt;?= ($_POST['payment_method'] ?? '') === 'bank' ? 'selected' : '' ?&gt;&gt;Банковский перевод&lt;/option&gt;
                        &lt;/select&gt;
                    &lt;/div&gt;

                    &lt;div class="form-group"&gt;
                        &lt;label&gt;Комментарий&lt;/label&gt;
                        &lt;textarea name="details" rows="4"&gt;&lt;?= htmlspecialchars($_POST['details'] ?? '') ?&gt;&lt;/textarea&gt;
                    &lt;/div&gt;

                    &lt;button type="submit" class="btn btn-primary btn-block"&gt;Отправить&lt;/button&gt;
                &lt;/form&gt;
                &lt;div class="form-footer"&gt;
                    &lt;a href="dashboard.php"&gt;Назад&lt;/a&gt;
                &lt;/div&gt;
            &lt;?php endif; ?&gt;
        &lt;/div&gt;
    &lt;/main&gt;

    &lt;?php include 'templates/footer.php'; ?&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
</div>

<div class="fc">
<div class="fh"><span>profile.php</span><button class="btn" onclick="copy('f10')">Копировать</button></div>
<textarea id="f10">&lt;?php
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
    } elseif (strlen($new_pass) &lt; 6) {
        $error = 'Пароль минимум 6 символов';
    } else {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hash, $_SESSION['user_id']]);
        $success = 'Пароль изменён';
    }
}
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="ru"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Профиль&lt;/title&gt;
    &lt;link rel="stylesheet" href="css/style.css?v=black1"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;?php include 'templates/header.php'; ?&gt;

    &lt;main class="container section"&gt;
        &lt;h1 class="section-title"&gt;Мой профиль&lt;/h1&gt;

        &lt;?php if ($error): ?&gt;
            &lt;div class="alert alert-error"&gt;&lt;?= htmlspecialchars($error) ?&gt;&lt;/div&gt;
        &lt;?php endif; ?&gt;

        &lt;?php if ($success): ?&gt;
            &lt;div class="alert alert-success"&gt;&lt;?= htmlspecialchars($success) ?&gt;&lt;/div&gt;
        &lt;?php endif; ?&gt;

        &lt;div class="profile-card"&gt;
            &lt;h2&gt;Личные данные&lt;/h2&gt;
            &lt;form method="POST"&gt;
                &lt;input type="hidden" name="update_profile" value="1"&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;ФИО *&lt;/label&gt;
                    &lt;input type="text" name="name" required value="&lt;?= htmlspecialchars($user['name']) ?&gt;"&gt;
                &lt;/div&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;Email *&lt;/label&gt;
                    &lt;input type="email" name="email" required value="&lt;?= htmlspecialchars($user['email']) ?&gt;"&gt;
                &lt;/div&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;Телефон&lt;/label&gt;
                    &lt;input type="tel" name="phone" value="&lt;?= htmlspecialchars($user['phone']) ?&gt;"&gt;
                &lt;/div&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;Дата рождения&lt;/label&gt;
                    &lt;input type="date" name="birthdate" value="&lt;?= htmlspecialchars($user['birthdate'] ?? '') ?&gt;"&gt;
                &lt;/div&gt;
                &lt;button type="submit" class="btn btn-primary btn-block"&gt;Сохранить&lt;/button&gt;
            &lt;/form&gt;
        &lt;/div&gt;

        &lt;div class="profile-card" style="margin-top: 30px;"&gt;
            &lt;h2&gt;Смена пароля&lt;/h2&gt;
            &lt;form method="POST"&gt;
                &lt;input type="hidden" name="change_password" value="1"&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;Текущий пароль&lt;/label&gt;
                    &lt;input type="password" name="current_password"&gt;
                &lt;/div&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;Новый пароль&lt;/label&gt;
                    &lt;input type="password" name="new_password"&gt;
                &lt;/div&gt;
                &lt;div class="form-group"&gt;
                    &lt;label&gt;Подтвердите пароль&lt;/label&gt;
                    &lt;input type="password" name="new_password_confirm"&gt;
                &lt;/div&gt;
                &lt;button type="submit" class="btn btn-outline btn-block"&gt;Изменить пароль&lt;/button&gt;
            &lt;/form&gt;
        &lt;/div&gt;
    &lt;/main&gt;

    &lt;?php include 'templates/footer.php'; ?&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
</div>

<hr>

<div class="fc">
<div class="fh"><span>admin/index.php</span><button class="btn" onclick="copy('f11')">Копировать</button></div>
<textarea id="f11">&lt;?php
require_once '../config/db.php';

if (!$is_admin) {
    header('Location: ../login.php');
    exit;
}

// Изменение статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = (int)$_POST['request_id'];
    $status = trim($_POST['status']);
    if (in_array($status, ['new', 'processing', 'completed', 'cancelled'])) {
        $pdo->prepare("UPDATE requests SET status = ? WHERE id = ?")->execute([$status, $id]);
    }
}

// Все заявки
$requests = $pdo->query("SELECT r.*, u.name as user_name, u.email as user_email, u.phone as user_phone
    FROM requests r
    JOIN users u ON r.user_id = u.id
    ORDER BY r.created_at DESC")->fetchAll();

// Статусы
$statuses = [
    'new' => 'Новая',
    'processing' => 'В обработке',
    'completed' => 'Завершена',
    'cancelled' => 'Отменена',
];

$services_list = [
    'tram' => 'Курс трамвая',
    'bus' => 'Курс автобуса',
    'trolleybus' => 'Курс электробуса',
];

$time_labels = [
    'morning' => 'Утро (08:00 - 12:00)',
    'day' => 'День (12:00 - 17:00)',
    'evening' => 'Вечер (17:00 - 21:00)',
];

$payment_labels = [
    'cash' => 'Наличными',
    'card' => 'Картой',
    'bank' => 'Банковский перевод',
];
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="ru"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Админка - Заявки&lt;/title&gt;
    &lt;link rel="stylesheet" href="../css/style.css?v=black1"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="admin-nav"&gt;
        &lt;div class="container"&gt;
            &lt;a href="index.php" class="active"&gt;Заявки&lt;/a&gt;
            &lt;a href="users.php"&gt;Пользователи&lt;/a&gt;
            &lt;a href="../dashboard.php"&gt;Кабинет&lt;/a&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;main class="container section"&gt;
        &lt;h1 class="section-title"&gt;Заявки&lt;/h1&gt;

        &lt;?php if (empty($requests)): ?&gt;
            &lt;div class="empty-state"&gt;
                &lt;h3&gt;Заявок пока нет&lt;/h3&gt;
            &lt;/div&gt;
        &lt;?php else: ?&gt;
            &lt;div class="table-wrapper"&gt;
                &lt;table&gt;
                    &lt;thead&gt;
                        &lt;tr&gt;
                            &lt;th&gt;ID&lt;/th&gt;
                            &lt;th&gt;Клиент&lt;/th&gt;
                            &lt;th&gt;Услуга&lt;/th&gt;
                            &lt;th&gt;Детали&lt;/th&gt;
                            &lt;th&gt;Дата&lt;/th&gt;
                            &lt;th&gt;Статус&lt;/th&gt;
                        &lt;/tr&gt;
                    &lt;/thead&gt;
                    &lt;tbody&gt;
                        &lt;?php foreach ($requests as $req):
                            $details = json_decode($req['details'], true) ?? [];
                        ?&gt;
                            &lt;tr&gt;
                                &lt;td&gt;#&lt;?= $req['id'] ?&gt;&lt;/td&gt;
                                &lt;td&gt;
                                    &lt;?= htmlspecialchars($req['user_name']) ?&gt;
                                    &lt;br&gt;&lt;small&gt;&lt;?= htmlspecialchars($req['user_email']) ?&gt;&lt;/small&gt;
                                    &lt;?php if ($req['user_phone']): ?&gt;
                                        &lt;br&gt;&lt;small&gt;&lt;?= htmlspecialchars($req['user_phone']) ?&gt;&lt;/small&gt;
                                    &lt;?php endif; ?&gt;
                                &lt;/td&gt;
                                &lt;td&gt;&lt;?= htmlspecialchars($services_list[$req['service']] ?? $req['service']) ?&gt;&lt;/td&gt;
                                &lt;td&gt;
                                    &lt;?php if (!empty($details['schedule_days'])): ?&gt;
                                        &lt;small&gt;Дни: &lt;?= htmlspecialchars($details['schedule_days']) ?&gt;&lt;/small&gt;&lt;br&gt;
                                    &lt;?php endif; ?&gt;
                                    &lt;?php if (!empty($details['schedule_time'])): ?&gt;
                                        &lt;small&gt;Время: &lt;?= htmlspecialchars($time_labels[$details['schedule_time']] ?? $details['schedule_time']) ?&gt;&lt;/small&gt;&lt;br&gt;
                                    &lt;?php endif; ?&gt;
                                    &lt;?php if (!empty($details['payment_method'])): ?&gt;
                                        &lt;small&gt;Оплата: &lt;?= htmlspecialchars($payment_labels[$details['payment_method']] ?? $details['payment_method']) ?&gt;&lt;/small&gt;&lt;br&gt;
                                    &lt;?php endif; ?&gt;
                                    &lt;?php if (!empty($details['comment'])): ?&gt;
                                        &lt;small&gt;&lt;em&gt;&lt;?= htmlspecialchars($details['comment']) ?&gt;&lt;/em&gt;&lt;/small&gt;
                                    &lt;?php endif; ?&gt;
                                &lt;/td&gt;
                                &lt;td&gt;&lt;?= date('d.m.Y H:i', strtotime($req['created_at'])) ?&gt;&lt;/td&gt;
                                &lt;td&gt;
                                    &lt;form method="POST" style="display: inline;"&gt;
                                        &lt;input type="hidden" name="update_status" value="1"&gt;
                                        &lt;input type="hidden" name="request_id" value="&lt;?= $req['id'] ?&gt;"&gt;
                                        &lt;select name="status" onchange="this.form.submit()"&gt;
                                            &lt;?php foreach ($statuses as $key => $label): ?&gt;
                                                &lt;option value="&lt;?= $key ?&gt;" &lt;?= $req['status'] === $key ? 'selected' : '' ?&gt;&gt;
                                                    &lt;?= $label ?&gt;
                                                &lt;/option&gt;
                                            &lt;?php endforeach; ?&gt;
                                        &lt;/select&gt;
                                    &lt;/form&gt;
                                &lt;/td&gt;
                            &lt;/tr&gt;
                        &lt;?php endforeach; ?&gt;
                    &lt;/tbody&gt;
                &lt;/table&gt;
            &lt;/div&gt;
        &lt;?php endif; ?&gt;
    &lt;/main&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
</div>

<div class="fc">
<div class="fh"><span>admin/users.php</span><button class="btn" onclick="copy('f12')">Копировать</button></div>
<textarea id="f12">&lt;?php
require_once '../config/db.php';

if (!$is_admin) {
    header('Location: ../login.php');
    exit;
}

// Все пользователи
$users = $pdo->query("SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html lang="ru"&gt;
&lt;head&gt;
    &lt;meta charset="UTF-8"&gt;
    &lt;meta name="viewport" content="width=device-width, initial-scale=1.0"&gt;
    &lt;title&gt;Админка - Пользователи&lt;/title&gt;
    &lt;link rel="stylesheet" href="../css/style.css?v=black1"&gt;
&lt;/head&gt;
&lt;body&gt;
    &lt;div class="admin-nav"&gt;
        &lt;div class="container"&gt;
            &lt;a href="index.php"&gt;Заявки&lt;/a&gt;
            &lt;a href="users.php" class="active"&gt;Пользователи&lt;/a&gt;
            &lt;a href="../dashboard.php"&gt;Кабинет&lt;/a&gt;
        &lt;/div&gt;
    &lt;/div&gt;

    &lt;main class="container section"&gt;
        &lt;h1 class="section-title"&gt;Пользователи&lt;/h1&gt;

        &lt;?php if (empty($users)): ?&gt;
            &lt;div class="empty-state"&gt;
                &lt;h3&gt;Пользователей пока нет&lt;/h3&gt;
            &lt;/div&gt;
        &lt;?php else: ?&gt;
            &lt;div class="table-wrapper"&gt;
                &lt;table&gt;
                    &lt;thead&gt;
                        &lt;tr&gt;
                            &lt;th&gt;ID&lt;/th&gt;
                            &lt;th&gt;Имя&lt;/th&gt;
                            &lt;th&gt;Email&lt;/th&gt;
                            &lt;th&gt;Телефон&lt;/th&gt;
                            &lt;th&gt;Роль&lt;/th&gt;
                            &lt;th&gt;Дата&lt;/th&gt;
                        &lt;/tr&gt;
                    &lt;/thead&gt;
                    &lt;tbody&gt;
                        &lt;?php foreach ($users as $u): ?&gt;
                            &lt;tr&gt;
                                &lt;td&gt;#&lt;?= $u['id'] ?&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;?= htmlspecialchars($u['name']) ?&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;?= htmlspecialchars($u['email']) ?&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;?= htmlspecialchars($u['phone']) ?: '—' ?&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;?= $u['role'] === 'admin' ? '&lt;b&gt;Админ&lt;/b&gt;' : 'Пользователь' ?&gt;&lt;/td&gt;
                                &lt;td&gt;&lt;?= date('d.m.Y', strtotime($u['created_at'])) ?&gt;&lt;/td&gt;
                            &lt;/tr&gt;
                        &lt;?php endforeach; ?&gt;
                    &lt;/tbody&gt;
                &lt;/table&gt;
            &lt;/div&gt;
        &lt;?php endif; ?&gt;
    &lt;/main&gt;
&lt;/body&gt;
&lt;/html&gt;</textarea>
</div>

<hr>

<div class="fc">
<div class="fh"><span>css/style.css</span><button class="btn" onclick="copy('f13')">Копировать</button></div>
<textarea id="f13" style="font-size:0.7rem">/* === Базовые стили === */
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;background:#f5f7fa;color:#2c3e50;line-height:1.6}

/* === Контейнер === */
.container{max-width:1200px;margin:0 auto;padding:0 20px}

/* === Шапка === */
.header{background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.08);padding:15px 0;position:sticky;top:0;z-index:100}
.header .container{display:flex;justify-content:space-between;align-items:center}
.logo{font-size:1.6rem;font-weight:700;color:#4a90d9;text-decoration:none}
.nav{display:flex;gap:15px}
.nav a{color:#5a6c7d;text-decoration:none;padding:10px 18px;border-radius:6px;transition:all .3s;font-weight:500}
.nav a:hover{background:#4a90d9;color:#fff}
.nav a.active{background:#4a90d9;color:#fff}
.nav a.btn{background:#4a90d9;color:#fff}
.nav a.btn:hover{background:#3a7fc4}

/* === Hero секция === */
.hero{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;padding:100px 0;text-align:center}
.hero h1{font-size:2.8rem;margin-bottom:20px;font-weight:700}
.hero p{font-size:1.25rem;opacity:.95;max-width:650px;margin:0 auto 35px;font-weight:300}

/* === Кнопки === */
.btn{display:inline-block;padding:14px 35px;border-radius:8px;text-decoration:none;font-weight:600;transition:all .3s;border:none;cursor:pointer;font-size:1rem}
.btn-primary{background:#27ae60;color:#fff}.btn-primary:hover{background:#219a52;transform:translateY(-3px);box-shadow:0 5px 20px rgba(39,174,96,.4)}
.btn-secondary{background:#fff;color:#667eea}.btn-secondary:hover{background:#f0f0f0}
.btn-outline{background:transparent;border:2px solid #667eea;color:#667eea}.btn-outline:hover{background:#667eea;color:#fff}

/* === Секции === */
.section{padding:70px 0}.section-title{text-align:center;font-size:2.2rem;color:#2c3e50;margin-bottom:50px;font-weight:700}

/* === Карточки услуг === */
.services-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:35px}
.service-card{background:#fff;border-radius:16px;box-shadow:0 5px 30px rgba(0,0,0,.08);padding:35px;transition:all .4s;border:1px solid #eee}
.service-card:hover{transform:translateY(-8px);box-shadow:0 15px 40px rgba(0,0,0,.12);border-color:#667eea}
.service-card h3{color:#4a90d9;margin-bottom:15px;font-size:1.5rem}
.service-card p{color:#7a8a9a;margin-bottom:25px;line-height:1.7}
.service-card .price{font-size:1.8rem;font-weight:700;color:#27ae60;margin-bottom:25px}
.service-card .btn{width:100%;text-align:center}

/* === Преимущества === */
.benefits-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:40px}
.benefit-item{text-align:center;padding:30px}
.benefit-item .icon{font-size:3.5rem;margin-bottom:20px;display:block}
.benefit-item h3{margin-bottom:12px;color:#2c3e50;font-size:1.3rem}
.benefit-item p{color:#7a8a9a}

/* === Формы === */
.form-card{background:#fff;border-radius:20px;box-shadow:0 10px 50px rgba(0,0,0,.1);padding:45px;max-width:480px;margin:50px auto}
.form-card h2{text-align:center;margin-bottom:35px;color:#2c3e50;font-size:1.8rem}
.form-group{margin-bottom:25px}.form-group label{display:block;margin-bottom:10px;font-weight:600;color:#3d4f5f}
.form-group input,.form-group select,.form-group textarea{width:100%;padding:14px 18px;border:2px solid #e4e8ec;border-radius:10px;font-size:1rem;font-family:inherit;transition:all .3s;background:#fafbfc}
.form-group input:focus,.form-group select:focus,.form-group textarea:focus{outline:none;border-color:#667eea;background:#fff;box-shadow:0 0 0 4px rgba(102,126,234,.15)}
.form-group textarea{resize:vertical;min-height:110px}
.form-footer{text-align:center;margin-top:25px}.form-footer a{color:#667eea;font-weight:600}.form-footer a:hover{text-decoration:underline}
.btn-block{width:100%;display:block;text-align:center}

/* === Заявки (личный кабинет) === */
.requests-list{display:flex;flex-direction:column;gap:20px}
.request-item{background:#fff;border-radius:14px;box-shadow:0 3px 15px rgba(0,0,0,.06);padding:25px;display:flex;justify-content:space-between;align-items:center;border-left:4px solid #667eea}
.request-info h4{color:#2c3e50;margin-bottom:8px;font-size:1.2rem}
.request-info p{color:#8a9aaa;font-size:.95rem}
.request-status{padding:8px 18px;border-radius:25px;font-size:.85rem;font-weight:600}
.status-new{background:#e3f2fd;color:#1565c0}.status-processing{background:#fff3e0;color:#e65100}.status-completed{background:#e8f5e9;color:#2e7d32}.status-cancelled{background:#ffebee;color:#c62828}

/* === Таблица (админка) === */
.table-wrapper{background:#fff;border-radius:16px;box-shadow:0 5px 30px rgba(0,0,0,.08);overflow:hidden}
table{width:100%;border-collapse:collapse}th,td{padding:18px 22px;text-align:left;border-bottom:1px solid #f0f2f5}
th{background:#fafbfc;font-weight:700;color:#3d4f5f;font-size:.9rem;text-transform:uppercase;letter-spacing:.5px}
tr:hover{background:#f8f9fc}td{color:#5a6c7d}

/* === Профиль === */
.profile-card{background:#fff;border-radius:16px;box-shadow:0 5px 30px rgba(0,0,0,.08);padding:40px;max-width:550px;margin:0 auto}
.profile-card h2{margin-bottom:30px;text-align:center;color:#2c3e50}

/* === Футер === */
.footer{background:#2c3e50;color:#a8b5c5;padding:40px 0;text-align:center}.footer p{font-size:.95rem}

/* === Алерты === */
.alert{padding:18px 22px;border-radius:12px;margin-bottom:25px;font-weight:500}
.alert-success{background:#e8f5e9;color:#2e7d32;border:1px solid #a5d6a7}.alert-error{background:#ffebee;color:#c62828;border:1px solid #ef9a9a}.alert-info{background:#e3f2fd;color:#1565c0;border:1px solid #90caf9}

/* === Пустое состояние === */
.empty-state{text-align:center;padding:70px 30px;color:#8a9aaa}.empty-state h3{margin-bottom:12px;color:#5a6c7d;font-size:1.4rem}

/* === Навигация админки === */
.admin-nav{background:#fff;box-shadow:0 2px 10px rgba(0,0,0,.08);padding:15px 0;margin-bottom:35px}
.admin-nav .container{display:flex;gap:10px;flex-wrap:wrap}
.admin-nav a{color:#5a6c7d;text-decoration:none;padding:10px 20px;border-radius:8px;font-weight:500;transition:all .3s}
.admin-nav a:hover,.admin-nav a.active{background:#667eea;color:#fff}
select[name="status"]{padding:8px 12px;border-radius:6px;border:1px solid #ddd;background:#fafbfc;cursor:pointer}

/* === Адаптивность === */
@media(max-width:768px){.header .container{flex-direction:column;gap:15px}.nav{flex-wrap:wrap;justify-content:center}.hero h1{font-size:2rem}.hero p{font-size:1rem}.form-card{margin:25px 15px;padding:30px 25px}.request-item{flex-direction:column;gap:18px;text-align:center}.section-title{font-size:1.8rem}.service-card{padding:25px}}
@media(max-width:480px){.hero{padding:60px 0}.hero h1{font-size:1.6rem}.btn{padding:12px 25px;font-size:.95rem}}</textarea>
</div>

<div class="fc">
<div class="fh"><span>js/main.js</span><button class="btn" onclick="copy('f14')">Копировать</button></div>
<textarea id="f14">// Простая валидация форм
document.addEventListener('DOMContentLoaded', function() {
    // Валидация формы регистрации
    const registerForm = document.querySelector('form[action="register.php"]');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');
            const email = document.getElementById('email');

            // Проверка паролей
            if (password && passwordConfirm && password.value !== passwordConfirm.value) {
                e.preventDefault();
                alert('Пароли не совпадают!');
                return false;
            }

            // Проверка email
            if (email && !email.value.includes('@')) {
                e.preventDefault();
                alert('Введите корректный email!');
                return false;
            }
        });
    }

    // Валидация формы входа
    const loginForm = document.querySelector('form[action="login.php"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email');
            const password = document.getElementById('password');

            if (!email.value || !password.value) {
                e.preventDefault();
                alert('Заполните все поля!');
                return false;
            }
        });
    }

    // Валидация новой заявки
    const requestForm = document.querySelector('form[action="new-request.php"]');
    if (requestForm) {
        requestForm.addEventListener('submit', function(e) {
            const service = document.getElementById('service');
            if (service && !service.value) {
                e.preventDefault();
                alert('Выберите услугу!');
                return false;
            }
        });
    }
});</textarea>
</div>

<hr>

<div class="b">
<div class="bt">Готово!</div>
<p>Создайте структуру файлов и вставьте коды. Сайт готов!</p>
<p><strong>Админ:</strong> admin@mail.ru / password</p>
</div>

</div>

<footer><span>Админ: admin@mail.ru / password</span></footer>

<script>
function copy(id) {
    var text = document.getElementById(id).value;
    navigator.clipboard.writeText(text).then(function() {
        var btn = event.target;
        btn.textContent = 'Скопировано!';
        setTimeout(function() { btn.textContent = 'Копировать'; }, 2000);
    });
}
</script>

</body>
</html>