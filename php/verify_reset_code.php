<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['email'])) {
    die("الجلسة منتهية، يرجى إعادة المحاولة.");
}

$email = $_SESSION['email'];
$code_input = $_POST['reset_code'];

$stmt = $pdo->prepare("SELECT reset_code FROM users WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();
$row = $stmt->fetch();

if (!$row) {
    die("البريد الإلكتروني غير صحيح.");
}

if ($code_input == $row['reset_code']) {
    // نجاح، التوجيه إلى صفحة كلمة المرور الجديدة
    header("Location: ../html/new_password.html");
    exit();
} else {
    die("رمز غير صحيح.");
}
?>