<?php
include 'db_connection.php';

$news = $pdo->query("SELECT * FROM news ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>الأخبار</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo&display=swap');
        body {
            font-family: 'Cairo', sans-serif;
            background: #f4f4f8;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
        }
        h1 {
            text-align: center;
            color: #4a3aff;
            margin-bottom: 30px;
        }
        .news-card {
            background: #fff;
            padding: 20px;
            margin-bottom: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            transition: transform 0.2s ease;
        }
        .news-card:hover {
            transform: translateY(-5px);
        }
        .news-card img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .news-title {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .news-content {
            font-size: 15px;
            color: #444;
            margin-bottom: 10px;
            white-space: pre-wrap;
        }
        .news-date {
            font-size: 13px;
            color: #999;
            text-align: left;
        }

        @media (max-width: 600px) {
            .news-title {
                font-size: 18px;
            }
            .news-content {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>آخر الأخبار</h1>

    <?php if (count($news) === 0): ?>
        <p style="text-align:center; color: #888;">لا توجد أخبار حالياً.</p>
    <?php else: ?>
        <?php foreach ($news as $item): ?>
            <div class="news-card">
                <?php if (!empty($item['image_path'])): ?>
                    <img src="uploads/news_images/<?= htmlspecialchars($item['image_path']) ?>" alt="صورة الخبر" />
                <?php endif; ?>
                <div class="news-title"><?= htmlspecialchars($item['title']) ?></div>
                <div class="news-content"><?= nl2br(htmlspecialchars($item['content'])) ?></div>
                <div class="news-date"><?= htmlspecialchars($item['created_at']) ?></div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>