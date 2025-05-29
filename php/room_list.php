<?php
session_start();
include '../php/db_connection.php';

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

try {
    $stmt = $pdo->query("SELECT room_id, room_name FROM rooms");
    $rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("خطأ في جلب الغرف: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قائمة الغرف</title>
    <style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    direction: rtl;
    background: linear-gradient(to bottom right, #4a3aff, #6e7bfb);
    padding: 30px 15px;
    margin: 0;
    text-align: center;
    min-height: 100vh;
    box-sizing: border-box;
}

h2 {
    color: #fff;
    margin-bottom: 25px;
    font-size: 24px;
    letter-spacing: 1px;
}

ul {
    list-style: none;
    padding: 0;
    max-width: 500px;
    margin: 0 auto;
}

li {
    background-color: #ffffff;
    margin: 12px 0;
    padding: 18px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
}

li:hover {
    transform: translateY(-3px);
    background-color: #f0f8ff;
}

a {
    text-decoration: none;
    color: #333;
    font-size: 18px;
    font-weight: bold;
    display: block;
    transition: color 0.3s ease;
}

a:hover {
    color: #4a3aff;
}

.logo {
    max-width: 160px;
    height: auto;
    margin: 25px auto 10px;
    display: block;
    filter: drop-shadow(0 2px 5px rgba(0, 0, 0, 0.2));
}

.create-rooom {
    padding: 10px 24px;
    background-color: #28a745;
    border-radius: 25px;
    cursor: pointer;
    color: white;
    border: none;
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 10;
    font-size: 14px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    transition: background-color 0.3s ease;
}

.create-rooom:hover {
    background-color: #218838;
}

@media (max-width: 768px) {
    body {
        padding: 20px 10px;
    }

    h2 {
        font-size: 20px;
    }

    ul {
        max-width: 100%;
    }

    .logo {
        max-width: 120px;
        margin-bottom: 15px;
    }

    .create-rooom {
        top: 15px;
        right: 15px;
        padding: 8px 16px;
        font-size: 12px;
    }

    li {
        padding: 14px;
        font-size: 16px;
    }

    a {
        font-size: 16px;
    }
}
    </style>

</head>
<body>
                        <button class="create-rooom" onclick="window.location.href='home.php'">رجوع
                </button>
    <img src="../php/ui.png" alt="your image" class="logo">
    <h2>قائمة غرف الدردشة</h2>
    <ul>
        <?php if (count($rooms) > 0): ?>
            <?php foreach ($rooms as $room): ?>
                <li>
                    <a href="chat_room.php?room_id=<?= htmlspecialchars($room['room_id']) ?>">
                        <?= htmlspecialchars($room['room_name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>لا توجد غرف متاحة حالياً.</li>
        <?php endif; ?>
    </ul>
</body>
</html>