<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_number = trim($_POST['registration_number']);
    $new_registration_number = trim($_POST['new_registration_number']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE registration_number = ?");
    $stmt->execute([$registration_number]);
    $user = $stmt->fetch();

    if (!$user) {
        showAlert("خطأ!", "المستخدم غير موجود.", "error", true);
        exit;
    }

    $fields = [];
    $params = [];

    if (!empty($new_registration_number)) {
        $fields[] = "registration_number = ?";
        $params[] = $new_registration_number;
    }
    if (!empty($first_name)) {
        $fields[] = "first_name = ?";
        $params[] = $first_name;
    }
    if (!empty($last_name)) {
        $fields[] = "last_name = ?";
        $params[] = $last_name;
    }
    if (!empty($email)) {
        $fields[] = "email = ?";
        $params[] = $email;
    }
    if (!empty($password)) {
        $fields[] = "password = ?";
        $params[] = password_hash($password, PASSWORD_DEFAULT);
    }

    if (!empty($fields)) {
        $params[] = $registration_number;
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE registration_number = ?";
        $updateStmt = $pdo->prepare($sql);
        $updateStmt->execute($params);
        showAlert("تم التحديث", "تم تحديث معلومات المستخدم بنجاح.", "success", false, "users_list.php");
    } else {
        showAlert("تنبيه", "لم يتم إدخال أي بيانات للتحديث.", "info", true);
    }
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