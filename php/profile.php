<?php
session_start();
require 'db_connection.php'; // الاتصال بقاعدة البيانات

// تأكد من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$msg = "";

// جلب بيانات المستخدم
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// التحديث
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // تحديث الاسم واللقب
    $update_stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE id = ?");
    $update_stmt->execute([$first_name, $last_name, $user_id]);

    // تغيير كلمة المرور
    if (!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
        if (password_verify($_POST['current_password'], $user['password'])) {
            $new_hashed = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
            $pass_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $pass_stmt->execute([$new_hashed, $user_id]);
            $msg = "تم تحديث كلمة المرور بنجاح.";
        } else {
            $msg = "كلمة المرور الحالية غير صحيحة.";
        }
    }

    // تغيير صورة الملف الشخصي
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === 0) {
        $img_name = time() . "_" . $_FILES['profile_image']['name'];
        $img_path = "uploads/" . $img_name;
        move_uploaded_file($_FILES['profile_image']['tmp_name'], $img_path);

        $img_stmt = $pdo->prepare("UPDATE users SET profil_img = ? WHERE id = ?");
        $img_stmt->execute([$img_name, $user_id]);

        $user['profil_img'] = $img_name; // تحديث الصورة الحالية في الجلسة
        $msg = "تم تحديث الصورة بنجاح.";
    }

    // تحديث الاسم واللقب في الجلسة
    $user['first_name'] = $first_name;
    $user['last_name'] = $last_name;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>تعديل الملف الشخصي</title>
  <style>
    * { box-sizing: border-box; }
    body {
      font-family: 'Cairo', sans-serif;
      background: linear-gradient(to right, #6dd5ed, #2193b0);
      margin: 0; padding: 0;
    }
    .container {
      max-width: 500px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      color: #2193b0;
    }
    label {
      display: block;
      margin-top: 15px;
      color: #333;
    }
    input[type="text"], input[type="password"], input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 8px;
    }
    .profile-img {
      text-align: center;
      margin-top: 20px;
    }
    .profile-img img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #2193b0;
    }
    button {
      width: 100%;
      padding: 12px;
      margin-top: 20px;
      background-color: #2193b0;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover {
      background-color: #186a8d;
    }
    .message {
      margin-top: 15px;
      text-align: center;
      color: green;
    }
    @media (max-width: 600px) {
      .container {
        margin: 20px;
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>تعديل الملف الشخصي</h2>
    <form method="post" enctype="multipart/form-data">
      <div class="profile-img">
        <img src="uploads/<?= htmlspecialchars($user['profil_img']) ?>" alt="الصورة الشخصية">
      </div>
      <label>الاسم:</label>
      <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name']) ?>" required>

      <label>اللقب:</label>
      <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name']) ?>" required>

      <label>كلمة المرور الحالية:</label>
      <input type="password" name="current_password" placeholder="كلمة المرور الحالية">

      <label>كلمة المرور الجديدة:</label>
      <input type="password" name="new_password" placeholder="كلمة المرور الجديدة">

      <label>تغيير الصورة:</label>
      <input type="file" name="profile_image" accept="image/*">

      <button type="submit">حفظ التعديلات</button>
      <?php if (!empty($msg)): ?>
        <div class="message"><?= $msg ?></div>
      <?php endif; ?>
    </form>
  </div>
</body>
</html>