<?php
session_start();
include 'db_connection.php';


$message = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $image_path = null;

    if ($title === "" || $content === "") {
        $error = "يرجى تعبئة العنوان والمحتوى";
    } else {
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($_FILES['image']['type'], $allowed_types)) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid() . "." . $ext;
                $upload_dir = "uploads/news_images/";
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
                $upload_path = $upload_dir . $new_filename;

                if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                    $error = "فشل في رفع الصورة";
                } else {
                    $image_path = $new_filename;
                }
            } else {
                $error = "نوع الصورة غير مدعوم، استخدم JPEG أو PNG أو GIF";
            }
        }

        if ($error === "") {
            $stmt = $pdo->prepare("INSERT INTO news (title, content, image_path, created_at) VALUES (:title, :content, :image_path, NOW())");
            $stmt->execute([
                'title' => $title,
                'content' => $content,
                'image_path' => $image_path
            ]);
            $message = "تم إضافة الخبر بنجاح";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>إضافة خبر جديد - لوحة الإدارة</title>
<style>
    /* خطوط وألوان */
    @import url('https://fonts.googleapis.com/css2?family=Cairo&display=swap');
    body {
        font-family: 'Cairo', sans-serif;
        background: #f0f3f7;
        margin: 0; padding: 0;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        padding: 40px 15px;
    }
    .container {
        background: #fff;
        max-width: 600px;
        width: 100%;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        padding: 30px 40px;
        box-sizing: border-box;
    }
    h1 {
        color: #4a3aff;
        margin-bottom: 30px;
        font-weight: 700;
        text-align: center;
    }
    label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
        margin-top: 20px;
        font-size: 16px;
    }
    input[type="text"],
    textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1.8px solid #ddd;
        border-radius: 8px;
        font-size: 15px;
        resize: vertical;
        transition: border-color 0.3s ease;
        box-sizing: border-box;
    }
    input[type="text"]:focus,
    textarea:focus {
        border-color: #4a3aff;
        outline: none;
    }
    input[type="file"] {
        margin-top: 10px;
        font-size: 15px;
    }
    button {
        margin-top: 30px;
        background-color: #4a3aff;
        border: none;
        color: white;
        padding: 15px;
        font-size: 16px;
        border-radius: 10px;
        cursor: pointer;
        width: 100%;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(74,58,255,0.4);
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #372ecf;
    }
    .message {
        background-color: #d4edda;
        color: #155724;
        border: 1.5px solid #c3e6cb;
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1.5px solid #f5c6cb;
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    @media (max-width: 480px) {
        .container {
            padding: 20px 15px;
            border-radius: 10px;
        }
        button {
            padding: 12px;
            font-size: 14px;
        }
    }
</style>
</head>
<body>

<div class="container">
    <h1>إضافة خبر جديد</h1>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" novalidate>
        <label for="title">عنوان الخبر:</label>
        <input type="text" id="title" name="title" placeholder="أدخل عنوان الخبر" required />

        <label for="content">محتوى الخبر:</label>
        <textarea id="content" name="content" rows="6" placeholder="اكتب محتوى الخبر هنا..." required></textarea>

        <label for="image">صورة الخبر (اختياري):</label>
        <input type="file" id="image" name="image" accept="image/*" />

        <button type="submit">إضافة الخبر</button>
    </form>
</div>

</body>
</html>