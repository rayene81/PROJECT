<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['email'])) {
    die("<h3 style='text-align:center;margin-top:100px;'>الجلسة منتهية، يرجى إعادة التسجيل.</h3>");
}

$email = $_SESSION['email'];
$code_input = $_POST['activation_code'];

// جلب الرمز الصحيح من قاعدة البيانات
$stmt = $pdo->prepare("SELECT activation_code FROM users WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    die("<h3 style='text-align:center;margin-top:100px;'>المستخدم غير موجود.</h3>");
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);
$correct_code = $user['activation_code'];

if ($code_input == $correct_code) {
    // تفعيل الحساب
    $update = $pdo->prepare("UPDATE users SET is_active = 1 WHERE email = :email");
    $update->bindParam(':email', $email);
    $update->execute();

    // إنهاء الجلسة
    session_unset();
    session_destroy();

    // عرض صفحة نجاح HTML
    echo <<<HTML
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تم تفعيل الحساب</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            text-align: center;
            background-color: #e0f7fa;
            padding-top: 100px;
        }

        .container {
            background-color: white;
            display: inline-block;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        .success {
            color: #4CAF50;
            font-size: 20px;
            margin-bottom: 20px;
        }

        a {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
                    <button class="create-rooom" onclick="window.location.href='home.php'">رجوع
                </button>
<div class="container">
    <div class="success">تم تفعيل حسابك بنجاح!</div>
    <a href="../html/login.html">الذهاب إلى صفحة تسجيل الدخول</a>
</div>

</body>
</html>
HTML;
    exit();
} else {
    die("<h3 style='text-align:center;margin-top:100px;'>رمز التفعيل غير صحيح.</h3>");
}
?>