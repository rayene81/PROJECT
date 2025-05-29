<?php
session_start();
include 'db_connection.php';

$user_id = $_SESSION['user_id'];

$posts = $pdo->query("SELECT posts.id, posts.content, posts.user_id, posts.created_at, users.first_name 
                      FROM posts JOIN users ON posts.user_id = users.id 
                      ORDER BY posts.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

foreach ($posts as $post) {
    $post_id = $post['id'];

    // عدد الإعجابات
    $likes = $pdo->prepare("SELECT COUNT(*) FROM likes WHERE post_id = :post_id");
    $likes->execute(['post_id' => $post_id]);
    $likes_count = $likes->fetchColumn();

    // هل المستخدم قام بالإعجاب؟
    $liked = $pdo->prepare("SELECT * FROM likes WHERE post_id = :post_id AND user_id = :user_id");
    $liked->execute(['post_id' => $post_id, 'user_id' => $user_id]);
    $is_liked = $liked->rowCount() > 0;

    echo "<div class='post' style='
        background:rgb(179, 177, 206); 
        border: 1px solid #ddd; 
        padding: 15px; 
        border-radius: 8px; 
        margin-bottom: 20px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    '>
    <p style='
        font-weight: bold; 
        color: #4a3aff; 
        margin-bottom: 6px; 
        font-size: 16px;
    '>".htmlspecialchars($post['first_name'])."</p>
    <p style='font-size: 15px; color: #222; margin-top: 0; margin-bottom: 8px;'>
        ".nl2br(htmlspecialchars($post['content']))."
    </p>
    <small style='color: #888; font-size: 12px;'>".$post['created_at']."</small>
    <div style='margin-top: 12px;'>
        <button class='like-btn' data-postid='{$post_id}' style='
            background-color: ".($is_liked ? "#e74c3c" : "#3498db").";
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        '>"
        .($is_liked ? "إلغاء إعجاب 👎" : "أعجبني 👍")." ({$likes_count})</button>";

    if ($post['user_id'] == $user_id) {
        echo "<button class='delete-btn' data-postid='{$post_id}' style='
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        '>حذف 🗑️</button>";
    }

    echo "</div></div>";

    // نموذج التعليق
    echo "<div class='comment-section'>
        <form class='comment-form' data-postid='{$post_id}'>
            <input type='text' name='comment' placeholder='اكتب تعليقاً...' required />
            <input type='hidden' name='post_id' value='{$post_id}' />
            <button type='submit'>تعليق</button>
        </form>";

    // عرض التعليقات
    $comments = $pdo->prepare("SELECT comments.id, comments.comment, users.first_name 
                               FROM comments JOIN users ON comments.user_id = users.id 
                               WHERE comments.post_id = :post_id ORDER BY comments.created_at ASC");
    $comments->execute(['post_id' => $post_id]);

    foreach ($comments as $c) {
        $comment_id = $c['id'];
        echo "<div style='margin: 10px 0; padding: 8px; background: #f9f9f9; border-left: 3px solid #4a3aff;'>
            <p style='margin: 0;'><strong>" . htmlspecialchars($c['first_name']) . ":</strong> " . nl2br(htmlspecialchars($c['comment'])) . "</p>";

        // عرض الردود على كل تعليق
 $replies = $pdo->prepare("SELECT comment_replies.reply, users.first_name 
    FROM comment_replies 
    JOIN users ON comment_replies.user_id = users.id 
    WHERE comment_replies.comment_id = :comment_id 
    ORDER BY comment_replies.created_at ASC");
$replies->execute(['comment_id' => $comment_id]);
        $replies->execute(['comment_id' => $comment_id]);

        echo "<div style='margin-top: 5px; padding-left: 15px;'>";
        foreach ($replies as $r) {
            echo "<div style='margin-bottom: 4px;'>
                <small><strong>" . htmlspecialchars($r['first_name']) . ":</strong> " . nl2br(htmlspecialchars($r['reply'])) . "</small>
            </div>";
        }
        echo "</div>";

        // نموذج الرد على التعليق
        echo "<form class='reply-form' data-comment_id='{$comment_id}' style='margin-top: 8px;'>
            <input type='text' name='reply' placeholder='اكتب ردًا...' required />
            <input type='hidden' name='comment_id' value='{$comment_id}' />
            <button type='submit'>رد</button>
        </form>";

        echo "</div>";
    }

    echo "</div><hr>";
}
?>