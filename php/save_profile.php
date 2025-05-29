<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/login.html");
    exit();
}

$user_id = $_SESSION['user_id'];
$first_name = trim($_POST['first_name']);
$last_name = trim($_POST['last_name']);
$profil_img = trim($_POST['profil_img']);

// تحديث الاسم واللقب
$stmt = $pdo->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, profil_img = :profil_img WHERE id = :id");
$stmt->bindParam(':first_name', $first_name);
$stmt->bindParam(':last_name', $last_name);
$stmt->bindParam(':profil_img', $profil_img);
$stmt->bindParam(':id', $user_id);
$stmt->execute();

// التوجيه إلى الصفحة الرئيسية
header("Location: ../php/home.php");
exit();
?>