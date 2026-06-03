<?php
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
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админка - Заявки</title>
    <link rel="stylesheet" href="../css/style.css?v=black1">
</head>
<body>
    <div class="admin-nav">
        <div class="container">
            <a href="index.php" class="active">Заявки</a>
            <a href="users.php">Пользователи</a>
            <a href="../dashboard.php">Кабинет</a>
        </div>
    </div>

    <main class="container section">
        <h1 class="section-title">Заявки</h1>

        <?php if (empty($requests)): ?>
            <div class="empty-state">
                <h3>Заявок пока нет</h3>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Клиент</th>
                            <th>Услуга</th>
                            <th>Детали</th>
                            <th>Дата</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $req):
                            $details = json_decode($req['details'], true) ?? [];
                        ?>
                            <tr>
                                <td>#<?= $req['id'] ?></td>
                                <td>
                                    <?= htmlspecialchars($req['user_name']) ?>
                                    <br><small><?= htmlspecialchars($req['user_email']) ?></small>
                                    <?php if ($req['user_phone']): ?>
                                        <br><small><?= htmlspecialchars($req['user_phone']) ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($services_list[$req['service']] ?? $req['service']) ?></td>
                                <td>
                                    <?php if (!empty($details['schedule_days'])): ?>
                                        <small>Дни: <?= htmlspecialchars($details['schedule_days']) ?></small><br>
                                    <?php endif; ?>
                                    <?php if (!empty($details['schedule_time'])): ?>
                                        <small>Время: <?= htmlspecialchars($time_labels[$details['schedule_time']] ?? $details['schedule_time']) ?></small><br>
                                    <?php endif; ?>
                                    <?php if (!empty($details['payment_method'])): ?>
                                        <small>Оплата: <?= htmlspecialchars($payment_labels[$details['payment_method']] ?? $details['payment_method']) ?></small><br>
                                    <?php endif; ?>
                                    <?php if (!empty($details['comment'])): ?>
                                        <small><em><?= htmlspecialchars($details['comment']) ?></em></small>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d.m.Y H:i', strtotime($req['created_at'])) ?></td>
                                <td>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="update_status" value="1">
                                        <input type="hidden" name="request_id" value="<?= $req['id'] ?>">
                                        <select name="status" onchange="this.form.submit()">
                                            <?php foreach ($statuses as $key => $label): ?>
                                                <option value="<?= $key ?>" <?= $req['status'] === $key ? 'selected' : '' ?>>
                                                    <?= $label ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>