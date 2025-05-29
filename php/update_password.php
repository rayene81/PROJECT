<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['email'])) {
    die("الجلسة منتهية، يرجى إعادة المحاولة.");
}

$email = $_SESSION['email'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// التحقق من تطابق كلمتي المرور
if ($new_password !== $confirm_password) {
    die("كلمتا المرور غير متطابقتين.");
}

// تشفير كلمة المرور الجديدة
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// تحديث كلمة المرور وإزالة رمز الاستعادة
$update = $pdo->prepare("UPDATE users SET password = ?, reset_code = NULL WHERE email = ?");
$update->execute([ $hashed_password,$email] );

if ($update->execute()) {
    // حذف الجلسة وإعادة التوجيه لصفحة الدخول
    session_destroy();
    header("Location: ../html/login.html");
    exit();
} else {
    die("حدث خطأ أثناء تحديث كلمة المرور.");
}