<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../html/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>أكمل معلوماتك</title>
    <style>
        body {
            font-family: Arial;
            direction: rtl;
            background-color: #f2f2f2;
            text-align: center;
            padding-top: 100px;
        }

        .container {
            background-color: white;
            display: inline-block;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        input[type="text"], input[type="submit"] {
            padding: 10px;
            margin: 10px 0;
            width: 250px;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        h2 {
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
                    <button class="create-rooom" onclick="window.location.href='home.php'">رجوع
                </button>
<div class="container">
    <h2>أكمل معلوماتك الشخصية</h2>
    <form action="../php/save_profile.php" method="POST">
        <input type="text" name="first_name" placeholder="الاسم" required><br>
        <input type="text" name="last_name" placeholder="اللقب" required><br>
        <input type="file" name="profil_img" accept="image/*" required>
        <input type="submit" value="حفظ والمتابعة">
    </form>
</div>

</body>
</html>