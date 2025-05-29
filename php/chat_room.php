<?php
session_start();
require 'db_connection.php';

if (!isset($_GET['room_id'])) {
    die("Room not found.");
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

$stmt2 = $pdo->prepare("SELECT room_name FROM rooms WHERE room_id = ?");
$stmt2->execute([$room_id]);
$room = $stmt2->fetch(PDO::FETCH_ASSOC);
$room_name = $room ? $room['room_name'] : "غرفة غير معروفة";


?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <script>
    if (/Mobi|Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        window.location.href = "../html/do.html";
    }
</script>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>غرفة: <?= htmlspecialchars($room_name) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Tajawal&display=swap" rel="stylesheet">    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
 body {
    font-family: 'Tajawal', 'Segoe UI', sans-serif;
    margin: 0;
    direction: rtl;
    background: linear-gradient(135deg, #f7f9fc 0%, #e6ecf1 100%);
    color: #222;
    height: 100vh;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-container {
    display: flex;
    flex-direction: row;
    height: calc(100vh - 80px);
    margin: 60px 20px 20px 20px;
    border-radius: 16px;
    box-shadow: 0 8px 24px rgb(0 0 0 / 0.12);
    background: #fff;
    overflow: hidden;
    transition: all 0.3s ease;
}

.users-panel {
    width: 280px;
    background: linear-gradient(180deg, #4f46e5 0%, #4338ca 100%);
    color: #e0e7ff;
    padding: 20px 20px;
    overflow-y: auto;
    box-shadow: inset -4px 0 12px rgb(0 0 0 / 0.15);
    display: flex;
    flex-direction: column;
    border-top-left-radius: 16px;
    border-bottom-left-radius: 16px;
    scrollbar-width: thin;
    scrollbar-color: #a5b4fc transparent;
}

.users-panel::-webkit-scrollbar {
    width: 8px;
}

.users-panel::-webkit-scrollbar-thumb {
    background-color: #a5b4fc;
    border-radius: 12px;
}

.users-panel h3 {
    margin-bottom: 32px;
    font-size: 24px;
    font-weight: 700;
    border-bottom: 2px solid rgba(255, 255, 255, 0.4);
    padding-bottom: 12px;
}

.users-panel p {
    margin: 10px 0;
    padding: 12px 16px;
    background: rgba(255 255 255 / 0.12);
    border-radius: 14px;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
    font-weight: 600;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
    white-space: nowrap;
}

.users-panel p:hover,
.users-panel p.active {
    background-color: rgba(255 255 255 / 0.35);
    color: #1e40af;
    font-weight: 700;
}

.chat-panel {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    padding: 25px 30px;
    background: #fafbff;
    border-top-right-radius: 16px;
    border-bottom-right-radius: 16px;
    overflow: hidden;
    box-shadow: inset 0 0 10px #e0e7ff;
}

.messages {
    flex-grow: 1;
    overflow-y: auto;
    padding-right: 16px;
    display: flex;
    flex-direction: column;
    gap: 18px;
    scroll-behavior: smooth;
}

.message {
    max-width: 65%;
    padding: 18px 26px;
    border-radius: 28px;
    word-wrap: break-word;
    box-shadow: 0 4px 12px rgb(0 0 0 / 0.10);
    font-size: 17px;
    line-height: 1.6;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.message.outgoing {
    background-color: #2563eb;
    color: #f9fafb;
    align-self: flex-end;
    border-radius: 28px 28px 0 28px;
    box-shadow: 0 6px 18px rgb(37 99 235 / 0.6);
}

.message.incoming {
    background-color: #e5e7eb;
    color: #1f2937;
    align-self: flex-start;
    border-radius: 28px 28px 28px 0;
    box-shadow: 0 4px 12px rgb(0 0 0 / 0.06);
}

.message img {
    margin-top: 14px;
    max-width: 240px;
    max-height: 240px;
    border-radius: 16px;
    object-fit: cover;
    box-shadow: 0 5px 15px rgb(0 0 0 / 0.12);
    transition: transform 0.2s ease;
}

.message img:hover {
    transform: scale(1.05);
}

.send-box {
    display: flex;
    gap: 16px;
    padding: 18px 28px;
    background-color: #ffffff;
    border-top: 1px solid #dde3f0;
    border-radius: 0 0 16px 16px;
    box-shadow: 0 -3px 12px rgb(0 0 0 / 0.05);
    align-items: center;
}

.send-box input[type="text"] {
    flex: 1;
    padding: 18px 24px;
    border-radius: 36px;
    border: 2px solid #cbd5e1;
    font-size: 18px;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
    outline-offset: 0;
}

.send-box input[type="text"]:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 14px #2563ebcc;
}

.send-box button,
.send-box label {
    font-size: 26px;
    background: none;
    border: none;
    cursor: pointer;
    color: #475569;
    transition: color 0.3s ease;
    padding: 8px 6px;
}

.send-box button:hover,
.send-box label:hover {
    color: #2563eb;
}

.send-box input[type="file"] {
    display: none;
}

#recordButton {
    font-size: 28px;
    color: #475569;
    background: none;
    border: none;
    cursor: pointer;
    transition: color 0.3s ease;
}

#recordButton:hover {
    color: #ef4444;
}

#create-rooom {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #2563eb;
    color: white;
    padding: 16px 32px;
    border-radius: 36px;
    text-decoration: none;
    font-weight: 700;
    box-shadow: 0 6px 22px rgb(37 99 235 / 0.8);
    z-index: 1100;
    transition: background-color 0.3s ease;
}
#create-rooom:hover {
    background-color: #1d4ed8;
}

/* استجابة الهواتف */
@media (max-width: 768px) {
    .chat-container {
        flex-direction: column;
        height: 100vh;
        margin: 70px 10px 10px 10px;
        border-radius: 0;
        box-shadow: none;
    }

    .users-panel {
        width: 100%;
        height: 80px;
        padding: 12px 15px;
        flex-direction: row;
        overflow-x: auto;
        box-shadow: none;
        border-radius: 12px 12px 0 0;
        border-bottom: 1px solid #dde3f0;
        scrollbar-width: thin;
        scrollbar-color: #94a3b8 transparent;
    }

    .users-panel::-webkit-scrollbar {
        height: 8px;
    }

    .users-panel::-webkit-scrollbar-thumb {
        background-color: #94a3b8;
        border-radius: 12px;
    }

    .users-panel h3 {
        display: none;
    }

    .users-panel p {
        margin: 0 12px 0 0;
        padding: 10px 20px;
        white-space: nowrap;
        font-size: 16px;
        box-shadow: none;
        background:#3b82f6;
        color: white;
        border-radius: 30px;
        flex-shrink: 0;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .users-panel p:hover,
    .users-panel p.active {
        background:#2563eb;
        color: white;
    }

    .chat-panel {
        width: 100%;
        flex-grow: 1;
        border-radius: 0 0 12px 12px;
        padding: 15px 15px 10px 15px;
        box-shadow: none;
    }

    .messages {
        padding-right: 0;
        gap: 12px;
    }

    .message {
        max-width: 85%;
        font-size: 16px;
    }

    .send-box {
        padding: 12px 20px;
        gap: 12px;
        border-radius: 0 0 12px 12px;
    }

    .send-box input[type="text"] {
        font-size: 16px;
        padding: 14px 18px;
    }

    #create-rooom {
        padding: 12px 24px;
        font-size: 16px;
    }
}
    </style>
</head>
<body>
<a href="home.php" id ="create-rooom" style="position: fixed; top: 600px; right: 18px; background-color: #007bff; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none; z-index: 999;">← الرجوع</a>
<div class="chat-container">
    <div class="users-panel">
        <h3>أعضاء الغرفة</h3>
        <?php
        $stmt = $pdo->prepare("
            SELECT DISTINCT users.first_name
            FROM messages 
            JOIN users ON messages.sender = users.id 
            WHERE messages.room_id = ?
        ");
     
$stmt->execute([$room_id]);
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $user) {
    echo "<p style='margin: 8px 0; padding: 6px 10px; background:rgb(0, 0, 0); border-radius: 6px;'>" . htmlspecialchars($user['first_name']) . "</p>";


}
?>
    </div>

    <div class="chat-panel">
        
        <div class="messages" id="messages-container">
<?php include 'fetch_messages.php'; ?>


        </div>
<form id="send-form" class="send-box" enctype="multipart/form-data">
                        <button type="button" id="recordButton">🎙️</button>
            <audio id="audioPlayback" controls style="display: none;"></audio>
            <input type="hidden" name="audio_data" id="audio_hidden">

            <input type="hidden" name="room_id" value="<?= $room_id ?>">
            <input type="text" id="message" name="message" placeholder="اكتب رسالتك...">

            <label for="image">📷</label>
            <input type="file" name="image" id="image" accept="image/*">

            <label for="file">📎</label>
            <input type="file" name="file" id="file">



            <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
        </form>
    </div>
</div>
<!-- Modal تأكيد الحذف -->
<div id="deleteModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
  <div style="background: white; padding: 20px; border-radius: 8px; text-align: center; width: 300px;">
    <p style="margin-bottom: 20px;">هل تريد حذف هذه الرسالة؟</p>
    <button id="confirmDelete" style="padding: 8px 16px; background: red; color: white; border: none; border-radius: 5px; margin: 0 10px;">نعم</button>
    <button id="cancelDelete" style="padding: 8px 16px; background: #ccc; border: none; border-radius: 5px;">إلغاء</button>
  </div>
</div>

<script>
const recordButton = document.getElementById("recordButton");
const audioPlayback = document.getElementById("audioPlayback");
const audioHiddenInput = document.getElementById("audio_hidden");
let mediaRecorder, isRecording = false, audioChunks = [];

recordButton.addEventListener("click", async () => {
    if (!isRecording) {
        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];

        mediaRecorder.ondataavailable = e => audioChunks.push(e.data);

        mediaRecorder.onstop = () => {
            const blob = new Blob(audioChunks, { type: 'audio/webm' });
            const audioUrl = URL.createObjectURL(blob);
            audioPlayback.src = audioUrl;
            audioPlayback.style.display = "block";

            const reader = new FileReader();
            reader.readAsDataURL(blob);
            reader.onloadend = () => {
                audioHiddenInput.value = reader.result;
            };
        };

        mediaRecorder.start();
        isRecording = true;
        recordButton.textContent = "إيقاف";
    } else {
        mediaRecorder.stop();
        isRecording = false;
        recordButton.textContent = "🎙️";
    }
});
</script>

<script>
function fetchMessages() {
    const roomId = <?= json_encode($room_id) ?>;
    fetch("fetch_messages.php?room_id=" + roomId)
        .then(response => response.text())
        .then(data => {
            const container = document.getElementById('messages-container');
            const isAtBottom = container.scrollTop + container.clientHeight >= container.scrollHeight - 20;
            container.innerHTML = data;
            if (isAtBottom) container.scrollTop = container.scrollHeight;
        })
        .catch(error => console.error('Error fetching messages:', error));
}
setInterval(fetchMessages, 3000);
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
});

</script>
<script>
document.getElementById('send-form').addEventListener('submit', async function(e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const response = await fetch('send_message.php', {
        method: 'POST',
        body: formData
    });

    if (response.ok) {
        form.reset();  // تنظيف الفورم
        document.getElementById('audioPlayback').style.display = "none";
        document.getElementById('audio_hidden').value = '';
        fetchMessages(); // تحديث الرسائل بعد الإرسال
    } else {
        alert("فشل إرسال الرسالة");
    }
});
</script>


<script>
let messageToDelete = null;

document.addEventListener('click', function(e) {
    // زر الحذف
    if (e.target.classList.contains('delete-button') || e.target.closest('.delete-button')) {
        const btn = e.target.closest('.delete-button');
        messageToDelete = btn.closest('.message');
        document.getElementById('deleteModal').style.display = 'flex';
    }

    // إلغاء الحذف
    if (e.target.id === 'cancelDelete') {
        document.getElementById('deleteModal').style.display = 'none';
        messageToDelete = null;
    }

    // تأكيد الحذف
    if (e.target.id === 'confirmDelete' && messageToDelete) {
        const messageId = messageToDelete.dataset.id;
        fetch('delete_message.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'message_id=' + encodeURIComponent(messageId)
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                messageToDelete.remove();
            } else {
                alert('فشل في حذف الرسالة');
            }
        })
        .catch(err => console.error('Error:', err))
        .finally(() => {
            document.getElementById('deleteModal').style.display = 'none';
            messageToDelete = null;
        });
    }
});
</script>

</body>
</html>