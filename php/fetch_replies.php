<?php
session_start();
include 'db_connection.php';

if (isset($_GET['comment_id'])) {
    $comment_id = (int)$_GET['comment_id'];

    $stmt = $pdo->prepare("SELECT comment_replies.reply, users.first_name 
                           FROM comment_replies 
                           JOIN users ON comment_replies.user_id = users.id 
                           WHERE comment_replies.comment_id = :comment_id 
                           ORDER BY comment_replies.created_at ASC");
    $stmt->execute(['comment_id' => $comment_id]);
    $replies = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($replies as $reply) {
        echo "<div style='
            background: #eef2ff;
            padding: 10px 12px;
            margin-bottom: 6px;
            border-left: 4px solid #4a3aff;
            border-radius: 6px;
            font-size: 14px;
            color: #333;
        '>
            <strong style='color: #4a3aff;'>" . htmlspecialchars($reply['first_name']) . ":</strong>
            <span>" . nl2br(htmlspecialchars($reply['reply'])) . "</span>
        </div>";
    }
}
?>