<?php
session_start();
require '../vendor/autoload.php'; // تحميل مكتبة PHPMailer
include 'db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // تحقق من وجود البريد
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
        die("البريد الإلكتروني غير موجود.");
    }

    // إنشاء رمز تحقق عشوائي
    $reset_code = rand(100000, 999999);

    // تحديث الرمز في قاعدة البيانات
    $update = $pdo->prepare("UPDATE users SET reset_code = :code WHERE email = :email");
    $update->bindParam(':code', $reset_code);
    $update->bindParam(':email', $email);
    $update->execute();

    // حفظ الإيميل في الجلسة
    $_SESSION['email'] = $email;

    // إعداد البريد الإلكتروني
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'chat.cord.unv@gmail.com'; // غيّره إلى بريدك
        $mail->Password   = 'cyaw qlxq jprb jclz';    // كلمة مرور تطبيق (وليس كلمة مرور Gmail)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('chat.cord.unv@gmail.com', 'اسم التطبيق');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'رمز إعادة تعيين كلمة المرور';
        $mail->Body    = "رمز التحقق الخاص بك هو: <b>$reset_code</b>";

        $mail->send();

        header("Location: ../html/reset_code.html");
        exit();
    } catch (Exception $e) {
        die("فشل إرسال البريد الإلكتروني: {$mail->ErrorInfo}");
    }
}
?>