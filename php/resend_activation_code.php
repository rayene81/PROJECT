<?php
session_start();
include 'db_connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['email'])) {
    echo "<script>alert('الجلسة منتهية، يرجى إعادة التسجيل.'); window.location.href = '../html/register.html';</script>";
    exit();
}

$email = $_SESSION['email'];

// جلب رمز التفعيل
$stmt = $pdo->prepare("SELECT activation_code FROM users WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() === 0) {
    echo "<script>alert('المستخدم غير موجود.'); window.location.href = '../html/register.html';</script>";
    exit();
}

$row = $stmt->fetch(PDO::FETCH_ASSOC);
$activation_code = $row['activation_code'];

// إرسال البريد
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'chat.cord.unv@gmail.com';
    $mail->Password   = 'cyaw qlxq jprb jclz';
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('chat.cord.unv@gmail.com', 'نظام التسجيل');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'رمز تفعيل الحساب';
    $mail->Body    = "رمز التفعيل الخاص بك هو: <strong>$activation_code</strong>";

    $mail->send();

    // عرض alert والعودة
    echo "<script>alert('تم إعادة إرسال رمز التفعيل إلى بريدك الإلكتروني.'); window.history.back();</script>";
} catch (Exception $e) {
    echo "<script>alert('فشل إرسال البريد. يرجى المحاولة لاحقًا.'); window.history.back();</script>";
}
?>