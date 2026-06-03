<?php
// Настройки базы данных
$db_host = 'localhost';
$db_name = 'transport_courses';
$db_user = 'root';
$db_pass = '';

// Подключение
try {
    $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Ошибка базы данных');
}

// Сессия
session_start();

// Проверка авторизации
$is_auth = isset($_SESSION['user_id']);
$is_admin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$user_name = $_SESSION['name'] ?? '';