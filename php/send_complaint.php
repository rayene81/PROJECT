<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // تأكد من أن المسار صحيح حسب هيكل ملفاتك

$name = $_POST['name'];
$email = $_POST['email'];
$message = $_POST['message'];

$mail = new PHPMailer(true);

try {
    // إعدادات SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'chat.cord.unv@gmail.com'; // بريدك
    $mail->Password = 'cyaw qlxq jprb jclz';  // كلمة مرور التطبيق
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // معلومات الرسالة
    $mail->setFrom('chat.cord.unv@gmail.com', 'نموذج الشكاوى');
    $mail->addAddress('chat.cord.unv@gmail.com'); // تصل الشكوى إلى نفس البريد
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = 'شكوى جديدة من ' . $name;
    $mail->Body    = "
        <strong>الاسم:</strong> $name<br>
        <strong>البريد:</strong> $email<br>
        <strong>الرسالة:</strong><br>$message
    ";

    $mail->send();
    echo "<script>alert('تم إرسال الشكوى بنجاح.'); window.location.href='../html/complaint.html';</script>";
} catch (Exception $e) {
    echo "<script>alert('فشل في إرسال الشكوى: {$mail->ErrorInfo}'); history.back();</script>";
}
?>