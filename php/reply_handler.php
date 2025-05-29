<?php
session_start();
include 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'], $_POST['comment_id'])) {
    $reply = trim($_POST['reply']);
    $comment_id = (int)$_POST['comment_id'];
    $user_id = $_SESSION['user_id'];

    if ($reply !== "") {
        $stmt = $pdo->prepare("INSERT INTO comment_replies (comment_id, user_id, reply, created_at)
                               VALUES (:comment_id, :user_id, :reply, NOW())");
        $stmt->execute([
            'comment_id' => $comment_id,
            'user_id' => $user_id,
            'reply' => $reply
        ]);
    }
}
?>