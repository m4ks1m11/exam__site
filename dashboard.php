<?php
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
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мои заявки</title>
    <link rel="stylesheet" href="css/style.css?v=black1">
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <main class="container section">
        <h1 class="section-title">Мои заявки</h1>

        <div style="text-align: center; margin-bottom: 30px;">
            <a href="new-request.php" class="btn btn-primary">Новая заявка</a>
        </div>

        <?php if (empty($requests)): ?>
            <div class="empty-state">
                <h3>У вас пока нет заявок</h3>
                <p>Подайте заявку на интересующий курс</p>
            </div>
        <?php else: ?>
            <div class="requests-list">
                <?php foreach ($requests as $req):
                    $details = json_decode($req['details'], true) ?? [];
                ?>
                    <div class="request-item">
                        <div class="request-info">
                            <h4><?= htmlspecialchars($services_list[$req['service']] ?? $req['service']) ?></h4>
                            <p><?= date('d.m.Y H:i', strtotime($req['created_at'])) ?></p>

                            <?php if (!empty($details['schedule_days'])): ?>
                                <p><strong>Дни:</strong> <?= htmlspecialchars($details['schedule_days']) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($details['schedule_time'])): ?>
                                <p><strong>Время:</strong> <?= htmlspecialchars($time_labels[$details['schedule_time']] ?? $details['schedule_time']) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($details['payment_method'])): ?>
                                <p><strong>Оплата:</strong> <?= htmlspecialchars($payment_labels[$details['payment_method']] ?? $details['payment_method']) ?></p>
                            <?php endif; ?>

                            <?php if (!empty($details['comment'])): ?>
                                <p><strong>Комментарий:</strong> <?= htmlspecialchars($details['comment']) ?></p>
                            <?php endif; ?>
                        </div>
                        <span class="request-status <?= $statuses[$req['status']]['class'] ?>">
                            <?= $statuses[$req['status']]['label'] ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'templates/footer.php'; ?>
</body>
</html>