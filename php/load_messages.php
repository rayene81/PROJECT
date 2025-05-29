<?php
session_start();
require 'db_connection.php';

$room_id = $_GET['room_id'] ?? 0;

$stmt = $pdo->prepare("
    SELECT messages.*, users.first_name
    FROM messages 
    JOIN users ON messages.sender = users.id 
    WHERE messages.room_id = ? 
    ORDER BY messages.id ASC
");
$stmt->execute([$room_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($messages as $msg):
    $isMine = $msg['sender'] == $_SESSION['user_id'];
?>
<div class="message <?= $isMine ? 'mine' : 'other' ?>">
    <div class="sender"><strong><?= htmlspecialchars($msg['first_name']) ?>:</strong></div>
    <div class="text"><?= nl2br(htmlspecialchars($msg['message'])) ?></div>
</div>
<?php endforeach; ?>