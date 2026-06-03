<?php
require_once '../config/db.php';

if (!$is_admin) {
    header('Location: ../login.php');
    exit;
}

// Все пользователи
$users = $pdo->query("SELECT id, name, email, phone, role, created_at FROM users ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админка - Пользователи</title>
    <link rel="stylesheet" href="../css/style.css?v=black1">
</head>
<body>
    <div class="admin-nav">
        <div class="container">
            <a href="index.php">Заявки</a>
            <a href="users.php" class="active">Пользователи</a>
            <a href="../dashboard.php">Кабинет</a>
        </div>
    </div>

    <main class="container section">
        <h1 class="section-title">Пользователи</h1>

        <?php if (empty($users)): ?>
            <div class="empty-state">
                <h3>Пользователей пока нет</h3>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Телефон</th>
                            <th>Роль</th>
                            <th>Дата</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>#<?= $u['id'] ?></td>
                                <td><?= htmlspecialchars($u['name']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['phone']) ?: '—' ?></td>
                                <td><?= $u['role'] === 'admin' ? '<b>Админ</b>' : 'Пользователь' ?></td>
                                <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>