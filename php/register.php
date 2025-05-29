<?php
session_start();
require '../vendor/autoload.php'; // تأكد أن PHPMailer مثبّت عبر Composer
include 'db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// استقبال بيانات النموذج
$registration_number = $_POST['registration_number'];
$email = $_POST['email'];
$birthdate = $_POST['birthdate'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$profil_img = 'user.png';
// تحقق من تطابق كلمتي المرور
if ($password !== $confirm_password) {
    die("كلمتا المرور غير متطابقتين.");
}

// تحقق من عدم تكرار المستخدم
$check = $pdo->prepare("SELECT * FROM users WHERE registration_number = :reg OR email = :email");
$check->bindParam(':reg', $registration_number);
$check->bindParam(':email', $email);
$check->execute();

if ($check->rowCount() > 0) {
    die("المستخدم مسجل مسبقًا.");
}

// حساب العمر
$birth = new DateTime($birthdate);
$today = new DateTime();
$age = $birth->diff($today)->y;

// توليد الرمز وتشفير كلمة المرور
$activation_code = rand(100000, 999999);
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// إضافة المستخدم
$insert = $pdo->prepare("INSERT INTO users (registration_number, email, age, password, is_admin, is_active, activation_code, profil_img) 
                          VALUES (:reg, :email, :age, :pass, 0, 0, :code, :profil_img)");
$insert->bindParam(':reg', $registration_number);
$insert->bindParam(':email', $email);
$insert->bindParam(':age', $age);
$insert->bindParam(':pass', $hashed_password);
$insert->bindParam(':code', $activation_code);
$insert->bindParam(':profil_img', $activation_code);
$insert->execute();

// إرسال رمز التفعيل بالبريد الإلكتروني
$mail = new PHPMailer(true);

try {
    // إعدادات SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'chat.cord.unv@gmail.com';         // بريدك
    $mail->Password   = 'cyaw qlxq jprb jclz';            // كلمة مرور التطبيق
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    // المرسل والمستقبل
    $mail->setFrom('chat.cord.unv@gmail.com', 'اسم الموقع');
    $mail->addAddress($email);

    // محتوى البريد
    $mail->isHTML(true);
    $mail->Subject = 'رمز تفعيل الحساب';
    $mail->Body    = "رمز التفعيل الخاص بك هو: <b>$activation_code</b>";

    $mail->send();
} catch (Exception $e) {
    die("فشل إرسال رمز التفعيل: {$mail->ErrorInfo}");
}

// حفظ البريد ورقم التسجيل في الجلسة
$_SESSION['email'] = $email;
$_SESSION['registration_number'] = $registration_number;

// تحويل المستخدم لصفحة إدخال رمز التفعيل
header("Location: ../html/verify_activation_code.html");
exit();
?>