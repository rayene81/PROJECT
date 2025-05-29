<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/login.html");
    exit();
}

// التحقق من أن المستخدم مسؤول
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT is_admin, registration_number FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$reg_number = $user['registration_number'];
if (!$user || $user['is_admin'] != 1) {
    die("غير مصرح لك بالوصول إلى هذه الصفحة.");
}

// إذا تم إرسال النموذج
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_name = trim($_POST['room_name']);

    if (!empty($room_name)) {
        $stmt = $pdo->prepare("INSERT INTO rooms (room_name, created_by) VALUES (?, ?)");
        $stmt->execute([$room_name, $reg_number]);
        echo "<script>alert('تم إنشاء الغرفة بنجاح'); window.location.href='home.php';</script>";
        exit();
    } else {
        $error = "الرجاء إدخال اسم الغرفة.";
    }
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>إنشاء غرفة جديدة</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            background-color: #f0f0f0;
            padding: 50px;
            text-align: center;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            display: inline-block;
        }

        input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 250px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h2>إنشاء غرفة جديدة</h2>

<?php if (isset($error)): ?>
    <div class="error"><?php echo $error; ?></div>
<?php endif; ?>

<form method="POST">
    <input type="text" name="room_name" placeholder="اسم الغرفة" required><br>
    <input type="submit" value="إنشاء">
</form>

</body>
</html>