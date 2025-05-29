<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = (int)$_POST['post_id'];

    // تأكد أن هذا المنشور يخص المستخدم
    $stmt = $pdo->prepare("SELECT user_id FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    if ($post && $post['user_id'] == $user_id) {
        // حذف التعليقات المرتبطة
        $pdo->prepare("DELETE FROM comments WHERE post_id = ?")->execute([$post_id]);
        // حذف الإعجابات المرتبطة
        $pdo->prepare("DELETE FROM likes WHERE post_id = ?")->execute([$post_id]);
        // حذف المنشور نفسه
        $pdo->prepare("DELETE FROM posts WHERE id = ?")->execute([$post_id]);
    }
    header("Location: home.php");
    exit();
}
?>