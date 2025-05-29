<?php
$host = 'localhost';
$db = 'user_system';
$user = 'root';
$pass = ''; // إذا كنت تستخدم XAMPP، اتركه فارغًا
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
}