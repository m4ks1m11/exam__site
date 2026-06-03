<?php
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
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новая заявка</title>
    <link rel="stylesheet" href="css/style.css?v=black1">
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main>
        <div class="form-card">
            <h2>Новая заявка</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <div class="form-footer">
                    <a href="dashboard.php" class="btn btn-primary">К заявкам</a>
                </div>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Услуга *</label>
                        <select id="service" name="service" required>
                            <option value="">-- Выберите --</option>
                            <?php foreach ($services_list as $key => $name): ?>
                                <option value="<?= $key ?>" <?= ($selected_service ?: ($_POST['service'] ?? '')) === $key ? 'selected' : '' ?>>
                                    <?= $name ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Удобные дни занятий</label>
                        <input type="text" name="schedule_days" placeholder="Пн, Ср, Пт" value="<?= htmlspecialchars($_POST['schedule_days'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label>Удобное время</label>
                        <select name="schedule_time">
                            <option value="">-- Выберите --</option>
                            <option value="morning" <?= ($_POST['schedule_time'] ?? '') === 'morning' ? 'selected' : '' ?>>Утро (08:00 - 12:00)</option>
                            <option value="day" <?= ($_POST['schedule_time'] ?? '') === 'day' ? 'selected' : '' ?>>День (12:00 - 17:00)</option>
                            <option value="evening" <?= ($_POST['schedule_time'] ?? '') === 'evening' ? 'selected' : '' ?>>Вечер (17:00 - 21:00)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Способ оплаты</label>
                        <select name="payment_method">
                            <option value="">-- Выберите --</option>
                            <option value="cash" <?= ($_POST['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>>Наличными</option>
                            <option value="card" <?= ($_POST['payment_method'] ?? '') === 'card' ? 'selected' : '' ?>>Картой</option>
                            <option value="bank" <?= ($_POST['payment_method'] ?? '') === 'bank' ? 'selected' : '' ?>>Банковский перевод</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Комментарий</label>
                        <textarea name="details" rows="4"><?= htmlspecialchars($_POST['details'] ?? '') ?></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Отправить</button>
                </form>
                <div class="form-footer">
                    <a href="dashboard.php">Назад</a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>