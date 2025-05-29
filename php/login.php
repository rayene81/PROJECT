<?php
session_start();
require_once 'db_connection.php'; // يجب أن ينشئ $pdo وليس $conn

// استقبال البيانات من النموذج
$registration_number = $_POST['registration_number'];
$password = $_POST['password'];

try {
    // التحقق من وجود المستخدم
    $query = $pdo->prepare("SELECT * FROM users WHERE registration_number = ?");
    $query->execute([$registration_number]);
    $user = $query->fetch();

    if (!$user) {
        die("رقم التسجيل غير موجود.");
    }

    // التحقق من كلمة المرور
    if (!password_verify($password, $user['password'])) {
        die("كلمة المرور غير صحيحة.");
    }

// تسجيل الدخول ناجح
$_SESSION['user_id'] = $user['id'];
$_SESSION['registration_number'] = $user['registration_number'];

// التحقق إذا كان الاسم أو اللقب ناقصًا
if (empty($user['first_name']) || empty($user['last_name'])) {
    header("Location: ../php/complete_profile.php");
    exit();
} else {
    header("Location: ../php/home.php");
    exit();
}

} catch (PDOException $e) {
    echo "حدث خطأ في قاعدة البيانات: " . $e->getMessage();
}
?>