<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'db_connection.php';

if (!isset($_GET['room_id'])) {
    http_response_code(400);
    exit('Invalid request');
}

$room_id = $_GET['room_id'];

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
    $isOutgoing = ($msg['sender'] == $_SESSION['user_id']);
    $messageClass = $isOutgoing ? 'outgoing' : 'incoming';
?>
    <div class="message <?= $messageClass ?>" data-id="<?= $msg['id'] ?>">
        <div class="sender">
            <strong><?= htmlspecialchars($msg['first_name']) ?>:</strong><br>
            <?= nl2br(htmlspecialchars($msg['message'])) ?>
        </div>

        <?php if (!empty($msg['image_path'])): ?>
            <img src="<?= htmlspecialchars($msg['image_path']) ?>" alt="صورة">
        <?php endif; ?>

        <?php if (!empty($msg['file_path'])): ?>
            <a href="<?= htmlspecialchars($msg['file_path']) ?>" download></a>
        <?php endif; ?>

  <?php if ($msg['audio_path']): ?>
    <div class="audio-message">
        <audio controls>
            <source src="<?= htmlspecialchars($msg['audio_path']) ?>" type="audio/webm">
            متصفحك لا يدعم تشغيل الصوت.
        </audio>
    </div>
<?php endif; ?>

        <?php if (!empty($msg['sticker'])): ?>
            <img src="<?= htmlspecialchars($msg['sticker']) ?>" alt="ملصق" style="width: 100px;">
        <?php endif; ?>

        <?php if ($isOutgoing): ?>
            <button class="delete-button" data-id="<?= $msg['id'] ?>" style="margin-top: 5px; background: transparent; border: none; color: white; cursor: pointer;">
                <i class="fa fa-trash"></i> 
            </button>
        <?php endif; ?>
    </div>
<?php endforeach; ?>