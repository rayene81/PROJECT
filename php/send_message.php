<?php
session_start();
require 'db_connection.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['room_id'])) {
    die("غير مسموح بالوصول.");
}

$room_id = $_POST['room_id'];
$user_id = $_SESSION['user_id'];
$message = !empty($_POST['message']) ? trim($_POST['message']) : '';

$image_path = null;
$file_path = null;
$audio_path = null;
$sticker = null;

// وظيفة لحفظ الملفات
function saveFile($file, $targetDir, $allowedTypes) {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (in_array($extension, $allowedTypes)) {
            $fileName = uniqid() . '.' . $extension;
            $destination = $targetDir . $fileName;
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                return $destination;
            }
        }
    }
    return null;
}

// رفع صورة
if (!empty($_FILES['image']['name'])) {
    $image_path = saveFile($_FILES['image'], 'uploads/images/', ['jpg', 'jpeg', 'png', 'gif']);
}

// رفع ملف
if (!empty($_FILES['file']['name'])) {
    $file_path = saveFile($_FILES['file'], 'uploads/files/', ['pdf', 'docx', 'txt', 'zip', 'rar']);
}

$audio_path = null;

if (!empty($_POST['audio_data'])) {
    $audio_data = $_POST['audio_data'];

    if (preg_match('/^data:audio\/webm;base64,/', $audio_data)) {
        $audio_data = substr($audio_data, strpos($audio_data, ',') + 1);
        $audio_data = base64_decode($audio_data);

        $audio_filename = uniqid() . '.webm';
        $audio_path = 'uploads/audio/' . $audio_filename;
        file_put_contents($audio_path, $audio_data);
    }
}
// رفع ملصق
if (!empty($_FILES['sticker']['name'])) {
    $sticker = saveFile($_FILES['sticker'], 'uploads/stickers/', ['png', 'jpg', 'gif']);
}

// إدخال الرسالة في قاعدة البيانات
$sql = "INSERT INTO messages (room_id, sender, message, image_path, file_path, audio_path, sticker)
        VALUES (:room_id, :sender, :message, :image_path, :file_path, :audio_path, :sticker)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':room_id' => $room_id,
    ':sender' => $user_id,
    ':message' => $message,
    ':image_path' => $image_path,
    ':file_path' => $file_path,
    ':audio_path' => $audio_path,
    ':sticker' => $sticker
]);

header("Location: chat_room.php?room_id=" . $room_id);
exit;