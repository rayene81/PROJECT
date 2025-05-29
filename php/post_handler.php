<?php
session_start();
include 'db_connection.php';

if (isset($_SESSION['user_id'], $_POST['content'])) {
    $user_id = $_SESSION['user_id'];
    $content = trim($_POST['content']);

    if (!empty($content)) {
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, content) VALUES (:user_id, :content)");
        $stmt->execute(['user_id' => $user_id, 'content' => $content]);
    }
}
?>