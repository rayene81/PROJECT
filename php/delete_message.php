<?php
session_start();
require 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message_id'])) {
    $messageId = $_POST['message_id'];
    $userId = $_SESSION['user_id'];

    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = ? AND sender = ?");
    $stmt->execute([$messageId, $userId]);

    echo json_encode(['success' => true]);
    exit;
}
echo json_encode(['success' => false]);