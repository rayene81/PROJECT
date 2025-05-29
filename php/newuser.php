<?php
include 'db_connection.php';

// جلب آخر مستخدم تم تسجيله
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 1");
$new_user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if ($new_user): ?>
    <div style="
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        margin: 20px auto;
        max-width: 400px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        gap: 15px;
    ">
        <img src="<?= !empty($new_user['profile_image']) ? $new_user['profile_image'] : 'php/image.png' ?>" alt="الصورة" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div>
            <h3 style="margin: 0;"><?= htmlspecialchars($new_user['first_name'] . ' ' . $new_user['last_name']) ?></h3>
            <p style="margin: 5px 0; color: #555;">📧 <?= htmlspecialchars($new_user['email']) ?></p>
            <p style="margin: 5px 0; font-size: 13px; color: #999;">⏱ تم التسجيل في: <?= $new_user['created_at'] ?></p>
        </div>
    </div>
<?php endif; ?>