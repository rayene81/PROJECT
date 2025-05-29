<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['user_id'], $_POST['post_id'])) {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];

    // التحقق من وجود الإعجاب مسبقاً
    $check = $pdo->prepare("SELECT * FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    $check->execute(['user_id' => $user_id, 'post_id' => $post_id]);

    if ($check->rowCount() > 0) {
        // حذف الإعجاب
        $stmt = $pdo->prepare("DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id");
    } else {
        // إضافة إعجاب
        $stmt = $pdo->prepare("INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)");
    }

    $stmt->execute(['user_id' => $user_id, 'post_id' => $post_id]);
}
?>