<?php
session_start();
include 'db_connection.php';

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (!isset($_SESSION['registration_number'])) {
    header("Location: ../html/login.html");
    exit();
}
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC LIMIT 1");
$new_user = $stmt->fetch(PDO::FETCH_ASSOC);
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT first_name, last_name, is_admin FROM users WHERE id = :id");
$stmt->bindParam(':id', $user_id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || empty($user['first_name']) || empty($user['last_name'])) {
    header("Location: ../html/complete_profile.php");
    exit();
}

$first_name = $user['first_name'];
$is_admin = $user['is_admin'];
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الرئيسية - ChatCoed</title>
    <style>
body {
    margin: 0;
    padding-top: 70px;
    font-family: 'Arial', sans-serif;
    background-color: #f9fafb;
    color: #2c3e50;
    direction: rtl;
    min-height: 100vh;
  }

  .segment {
    position: absolute;
    width: 10px;
    height: 10px;
    background-color: #33ff66;
    border-radius: 50%;
    box-shadow: 0 0 6px #33ff66;
    transition: background-color 0.2s;
    pointer-events: none;
    z-index: 9999;
  }

  .segment::before {
    content: '';
    position: absolute;
    width: 4px;
    height: 4px;
    background: #fff;
    border-radius: 50%;
    top: 3px;
    left: 3px;
  }

.navbar {
    position: fixed;
    top: 0;
    right: 0;
    left: 0;
    background-color: #34495e;
    color: #ecf0f1;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    z-index: 1000;
}

.navbar .brand {
    font-weight: 700;
    font-size: 1.4rem;
    letter-spacing: 1.2px;
    display: flex;
    align-items: center;
}

.navbar .brand img {
    height: 38px;
    margin-left: 10px;
}

.navbar .nav-links {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
}

.navbar .nav-links a,
.navbar form button {
    background-color: transparent;
    border: 2px solid #ecf0f1;
    color: #ecf0f1;
    padding: 8px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.25s ease, color 0.25s ease;
    white-space: nowrap;
}

.navbar .nav-links a:hover,
.navbar form button:hover {
    background-color: #ecf0f1;
    color:rgb(76, 142, 207);
}

.container {
    max-width: 700px;
    margin: 30px auto 50px auto;
    background-color: #fff;
    padding: 30px 25px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(52, 73, 94, 0.1);
    text-align: center;
}

h1 {
    color: #34495e;
    font-weight: 700;
    margin-bottom: 15px;
    font-size: 2.2rem;
}

p {
    color: #555;
    font-size: 1.05rem;
    line-height: 1.6;
}

.admin-buttons {
    margin-top: 25px;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 20px;
}

.admin-buttons a {
    background-color: #2980b9;
    color: #fff;
    padding: 14px 26px;
    border-radius: 10px;
    font-weight: 700;
    box-shadow: 0 5px 12px rgba(41, 128, 185, 0.4);
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.admin-buttons a:hover {
    background-color: #1c5980;
    box-shadow: 0 6px 18px rgba(28, 89, 128, 0.6);
}

#new-post-form {
    margin: 40px 0;
    text-align: right;
}

#new-post-form textarea {
    width: 90%;
    min-height: 100px;
    padding: 15px 20px;
    font-size: 16px;
    border: 2px solid #bdc3c7;
    border-radius: 14px;
    resize: vertical;
    font-family: inherit;
    transition: border-color 0.3s ease;
}

#new-post-form textarea:focus {
    border-color: #2980b9;
    outline: none;
    box-shadow: 0 0 8px rgba(41, 128, 185, 0.5);
}

#new-post-form button {
    background-color: #2980b9;
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 14px;
    cursor: pointer;
    font-size: 16px;
    font-weight: 700;
    box-shadow: 0 5px 15px rgba(41, 128, 185, 0.4);
    transition: background-color 0.3s ease;
}

#new-post-form button:hover {
    background-color: #1c5980;
    box-shadow: 0 6px 20px rgba(28, 89, 128, 0.6);
}

.post {
    background-color: #fafafa;
    border: 1px solid #dcdde1;
    padding: 20px 24px;
    margin-bottom: 30px;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    text-align: right;
    transition: box-shadow 0.3s ease;
}

.post:hover {
    box-shadow: 0 6px 20px rgba(41, 128, 185, 0.15);
}

.post .actions {
    margin-top: 15px;
    display: flex;
    gap: 16px;
    justify-content: flex-start;
    flex-wrap: wrap;
}

.actions button {
    padding: 9px 22px;
    font-size: 14px;
    border: none;
    border-radius: 24px;
    cursor: pointer;
    font-weight: 700;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    box-shadow: 0 4px 12px rgba(41, 128, 185, 0.3);
}

.actions .like-btn {
    background-color: #2980b9;
    color: white;
}

.actions .like-btn:hover {
    background-color: #1c5980;
    box-shadow: 0 6px 18px rgba(28, 89, 128, 0.5);
}

.actions .delete-btn {
    background-color: #e74c3c;
    color: white;
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

.actions .delete-btn:hover {
    background-color: #a73227;
    box-shadow: 0 6px 18px rgba(167, 50, 39, 0.5);
}

.comment-form {
    margin-top: 20px;
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    justify-content: flex-start;
}

.comment-form input[type="text"] {
    flex: 1;
    padding: 12px 18px;
    border: 1.5px solid #bdc3c7;
    border-radius: 14px;
    font-size: 15px;
}

.comment-form button {
    background-color: #27ae60;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 14px;
    cursor: pointer;
    font-weight: 700;
    transition: background-color 0.3s ease;
}

.comment-form button:hover {
    background-color: #1c7a45;
}

.comments {
    margin-top: 20px;
    padding-right: 20px;
}

.comment {
    font-size: 15px;
    background-color: #ecf0f1;
    padding: 14px 18px;
    border-radius: 14px;
    margin-bottom: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    word-wrap: break-word;
}

.reply-section {
    margin-top: 20px;
    padding-right: 20px;
    border-right: 4px solid #dcdde1;
}

.reply {
    background-color: #f0f3f5;
    padding: 12px 18px;
    margin: 10px 0;
    border-radius: 14px;
    font-size: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}

.reply strong {
    color: #2980b9;
    margin-left: 8px;
    font-weight: 700;
}

.reply-form {
    display: flex;
    gap: 14px;
    margin-top: 14px;
    flex-wrap: wrap;
}

.reply-form input[type="text"] {
    flex: 1;
    padding: 10px 16px;
    border: 1.5px solid #bdc3c7;
    border-radius: 14px;
    font-size: 14px;
}

.reply-form button {
    padding: 10px 22px;
    background-color: #2980b9;
    color: white;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    font-weight: 700;
    transition: background-color 0.3s ease;
}

.reply-form button:hover {
    background-color: #1c5980;
}

.comment-section p {
    background-color: #eaf2f8;
    border-left: 6px solid #2980b9;
    padding: 12px 18px;
    margin: 10px 0;
    border-radius: 12px;
    font-size: 15px;
    color: #34495e;
    word-wrap: break-word;
}

/* Responsive */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 12px;
        padding: 20px 25px;
    }

    .navbar .nav-links {
        flex-wrap: wrap;
        justify-content: flex-start;
        gap: 10px;
    }

    .container {
        margin: 20px 15px 50px 15px;
        padding: 25px 20px;
    }

    h1 {
        font-size: 1.7rem;
    }

    .admin-buttons {
        flex-direction: column;
        gap: 18px;
    }
}
    #lgt{
    background-color: transparent;
    border: 2px solid #ecf0f1;
    color:rgb(0, 82, 102);
    padding: 7px 16px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.25s ease, color 0.25s ease;
    white-space: nowrap;
}
  
    </style>
</head>
<body>

    <nav class="navbar">
        <div class="brand">
            <img src="ui.png" alt="Logo">
            <span>ChatCoed</span>
        </div>
        <div class="nav-links">
            <a href="news_list.php">News</a>
            <a href="room_list.php">Chat Rooms</a>
            <a href="../html/complaint.html">complaint</a>
            <a  href="profile.php">Profile</a>

            <form action="../php/logout.php" method="post" style="display:inline;">
                <button id ="lgt" type="submit">
                    <img src="logout.svg" alt="Log out">
                </button>
            </form>
        </div>


    </nav>
    <div class="container">
        
        <h1>مرحباً بك، <?php echo htmlspecialchars($first_name); ?>!</h1>
        <p>هذه الصفحة قيد التطوير، سيتم إطلاقها قريباً.</p>
        <p>يرجى التأكد من أن معلوماتك صحيحة ومحدثة.</p>
        <p>يرجى متصفح الحاسوب  لتجربة أكثر متعة وأمان.</p>

        </div>
        <?php if ($is_admin): ?>
            <div class="container" >
    <div style="
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 10px;
        padding: 15px;
        margin: 20px auto;
        max-width: 400px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
        display: flex;
        align-items: center;
        gap: 15px;
    ">
        <img src="<?= !empty($new_user['profile_image']) ? $new_user['profile_image'] : 'php/image.png' ?>" alt="الصورة" style="width: 70px; height: 70px; border-radius: 50%; object-fit: cover;">
        <div>
            <h3 style="margin: 0;"><?= htmlspecialchars($new_user['first_name'] . ' ' . $new_user['last_name']) ?></h3>
            <p style="margin: 5px 0; color: #555;">📧 <?= htmlspecialchars($new_user['email']) ?></p>
            <p style="margin: 5px 0; font-size: 13px; color: #999;">⏱ تم التسجيل في: <?= $new_user['created_at'] ?></p>
        </div>
    </div>
        </div>
<?php endif; ?>
    <div class="container">
        <?php if ($is_admin): ?>
        <div class="admin-buttons">
            <a href="create_room.php">إنشاء غرفة جديدة</a>
            <a href="news.php">اضافة اخبار</a>
           <a href="users_list.php">قائمة الأعضاء</a>
        </div>
        <?php endif; ?>
        <!-- نموذج إضافة منشور جديد -->
<div id="new-post-form">
    <form id="postForm">
        <textarea name="content" placeholder="بم تفكر؟" required></textarea>
        <button type="submit">نشر</button>
    </form>
</div>

<!-- عرض المنشورات -->
<div id="posts-list"></div>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    loadPosts();

    const postForm = document.getElementById("postForm");
    if (postForm) {
        postForm.addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch("post_handler.php", {
                method: "POST",
                body: formData
            }).then(res => res.text()).then(() => {
                this.reset();
                loadPosts();
            });
        });
    }

    function loadPosts() {
        fetch("fetch_post.php")
        .then(res => res.text())
        .then(data => {
            document.getElementById("posts-list").innerHTML = data;
            bindEvents();
        });
    }

    function bindEvents() {
        document.querySelectorAll(".like-btn").forEach(btn => {
            btn.onclick = () => {
                const postId = btn.dataset.postid;
                fetch("like_handler.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "post_id=" + postId
                }).then(() => loadPosts());
            };
        });

        document.querySelectorAll(".delete-btn").forEach(btn => {
            btn.onclick = () => {
                const postId = btn.dataset.postid;
                if (confirm("هل تريد حذف المنشور؟")) {
                    fetch("delete_post.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "post_id=" + postId
                    }).then(() => loadPosts());
                }
            };
        });

        document.querySelectorAll(".comment-form").forEach(form => {
            form.onsubmit = e => {
                e.preventDefault();
                const formData = new FormData(form);
                fetch("comment_handler.php", {
                    method: "POST",
                    body: formData
                }).then(() => loadPosts());
            };
        });
    }
});
</script>
<script>
document.addEventListener("submit", function (e) {
    if (e.target.classList.contains("reply-form")) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const commentId = formData.get("comment_id");

        fetch("reply_handler.php", {
            method: "POST",
            body: formData
        })
        .then(() => {
            form.reset(); // تفريغ النموذج
            return fetch("fetch_replies.php?comment_id=" + commentId);
        })
        .then(response => response.text())
        .then(html => {
            const repliesContainer = form.previousElementSibling; // يفترض أن replies div يأتي قبل الفورم
            if (repliesContainer) {
                repliesContainer.innerHTML = html;
            }
        });
    }
});
</script>
<script>
  const segmentCount = 40;
  const segments = [];

  for (let i = 0; i < segmentCount; i++) {
    const seg = document.createElement('div');
    seg.className = 'segment';
    document.body.appendChild(seg);
    segments.push({ el: seg, x: 0, y: 0 });
  }

  let mouseX = window.innerWidth / 2;
  let mouseY = window.innerHeight / 2;

  window.addEventListener('mousemove', e => {
    mouseX = e.clientX;
    mouseY = e.clientY;
  });

  function animate() {
    segments[0].x += (mouseX - segments[0].x) * 0.2;
    segments[0].y += (mouseY - segments[0].y) * 0.2;
    segments[0].el.style.transform = `translate(${segments[0].x}px, ${segments[0].y}px)`;

    for (let i = 1; i < segments.length; i++) {
      segments[i].x += (segments[i - 1].x - segments[i].x) * 0.3;
      segments[i].y += (segments[i - 1].y - segments[i].y) * 0.3;
      segments[i].el.style.transform = `translate(${segments[i].x}px, ${segments[i].y}px)`;
    }

    requestAnimationFrame(animate);
  }

  animate();
</script>
</body>
</html>