<?php
include 'db_connection.php';

// البحث حسب رقم التسجيل
$search = '';
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $stmt = $pdo->prepare("SELECT * FROM users WHERE registration_number LIKE ?");
    $stmt->execute(["%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
}
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>قائمة المستخدمين</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 10px;
        background-color: #f1f1f1;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 10px;
    }

    .search-form {
        display: flex;
        justify-content: center;
        margin-bottom: 10px;
        flex-wrap: wrap;
        gap: 8px;
    }

    .search-form input {
        padding: 6px 10px;
        font-size: 13px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .search-form button {
        padding: 6px 12px;
        font-size: 13px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .container {
        overflow-x: auto;
        background: white;
        border-radius: 6px;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    table {
        width: 80%;
        border-collapse: collapse;
        font-size: 12px;
        min-width: 1000px;
    }

    th, td {
        padding: 6px 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .actions {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .actions input, .actions button {
        font-size: 11px;
        padding: 5px;
        margin: 1px 0;
        border-radius: 3px;
    }

    .actions button {
        border: none;
        cursor: pointer;
    }

    .actions button:first-of-type {
        background-color: #28a745;
        color: white;
    }

    .actions button:last-of-type {
        background-color: #dc3545;
        color: white;
    }

    @media (max-width: 600px) {
        table {
            font-size: 11px;
        }

        .search-form input,
        .search-form button {
            width: 100%;
        }

        .actions input {
            font-size: 10px;
        }
    }
</style>
    </style>
</head>
<body>

<h2 style="text-align:center;">📋 قائمة المستخدمين</h2>

<?php
if (isset($_GET['success'])) echo "<p style='color:green; text-align:center;'>✅ تم تحديث المستخدم بنجاح</p>";
if (isset($_GET['error']) && $_GET['error'] === 'user_not_found') echo "<p style='color:red; text-align:center;'>❌ المستخدم غير موجود</p>";
if (isset($_GET['error']) && $_GET['error'] === 'registration_taken') echo "<p style='color:red; text-align:center;'>⚠️ رقم التسجيل مستخدم من قبل</p>";
if (isset($_GET['deleted'])) echo "<p style='color:green; text-align:center;'>✅ تم حذف المستخدم بنجاح</p>";
?>

<div class="search-form">
    <form method="GET">
        <input type="text" name="search" placeholder="🔍 بحث برقم التسجيل" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">بحث</button>
    </form>
</div>

<div class="container">
    <table>
        <tr>
            <th>📌 ID</th>

            <th>📇 رقم التسجيل</th>
            <th>📧 البريد</th>
            <th>🔑 كلمة السر</th>
            <th>👤 الاسم</th>
            <th>👤 اللقب</th>
            <th>🎂 العمر</th>

            <th>🔐 is_admin</th>
            <th>✅ is_active</th>

            <th>🕓 created_at</th>
            <th>✏️ تعديل / ❌ حذف</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= htmlspecialchars($user['registration_number']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['password']) ?></td>
            <td><?= htmlspecialchars($user['first_name']) ?></td>
            <td><?= htmlspecialchars($user['last_name']) ?></td>
            <td><?= htmlspecialchars($user['age']) ?></td>
            <td><?= $user['is_admin'] ?></td>
            <td><?= $user['is_active'] ?></td>

            <td><?= $user['created_at'] ?></td>
            <td class="actions">
                <form method="POST" action="update_user.php">
                    <input type="hidden" value="" name="registration_number" value="<?= $user['registration_number'] ?>">
                    <input type="text" value="<?= htmlspecialchars($user['registration_number']) ?>" name="new_registration_number" placeholder="رقم تسجيل جديد">
                    <input type="text" name="first_name" placeholder="اسم جديد">
                    <input type="text" name="last_name" placeholder="لقب جديد">
                    <input type="password" name="password" placeholder="كلمة مرور جديدة">
                    <button type="submit" style="background:#007bff; color:white;">تحديث</button>
                </form>
                <form method="POST" action="delete_user.php" >
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <button type="submit" style="background:red; color:white;">حذف</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

</body>
</html>