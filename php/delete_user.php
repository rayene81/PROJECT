<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!isset($_POST['user_id']) || empty(trim($_POST['user_id']))) {
        showAlert("تنبيه", "الرجاء إدخال رقم التسجيل.", "info", true);
        exit;
    }

    $user_id = trim($_POST['user_id']);
    // ثم تتابع الحذف أو المعالجة هنا...

    // تحقق مما إذا كان المستخدم موجودًا
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    // حذف المستخدم

    showAlert("تم الحذف", "تم حذف المستخدم بنجاح.", "success", false, "users_list.php");

} else {
    showAlert("طلب غير صالح", "تم الوصول إلى الصفحة بطريقة غير صحيحة.", "error", true);
    exit;
}

function showAlert($title, $text, $icon, $back = false, $redirect = null) {
    echo "
    <!DOCTYPE html>
    <html lang='ar'>
    <head>
      <meta charset='UTF-8'>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
      <title>تنبيه</title>
    </head>
    <body>
    <script>
      Swal.fire({
        icon: '$icon',
        title: '$title',
        text: '$text',
        confirmButtonText: 'حسنًا',
        timer: 2500,
        timerProgressBar: true
      }).then(() => {
        " . ($redirect ? "window.location.href = '$redirect';" : ($back ? "window.history.back();" : "")) . "
      });
    </script>
    </body>
    </html>
    ";
}
?>