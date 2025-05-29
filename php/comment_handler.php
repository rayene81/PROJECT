<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $post_id = $_POST['post_id'];
    $comment = trim($_POST['comment']);

    if (!empty($comment)) {
        $stmt = $pdo->prepare("INSERT INTO comments (user_id, post_id, comment, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$user_id, $post_id, $comment]);
    }
}
?>