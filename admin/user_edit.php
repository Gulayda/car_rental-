<?php
session_start();
include('../includes/db.php');  // Baza ulanish fayli

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "Foydalanuvchi ID berilmagan!";
    header("Location: users_list.php"); // Foydalanuvchilar ro'yxati sahifasiga qaytish
    exit();
}

$user_id = intval($_GET['id']);

// Foydalanuvchi ma'lumotini olish
$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    $_SESSION['error'] = "Foydalanuvchi topilmadi!";
    header("Location: users_list.php");
    exit();
}

// Forma yuborilgan bo'lsa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    // Oddiy validatsiya
    $errors = [];
    if (empty($username)) {
        $errors[] = "Username bo'sh bo'lishi mumkin emas.";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "To'g'ri email kiriting.";
    }
    if ($role !== 'admin' && $role !== 'user') {
        $errors[] = "Noto'g'ri role tanlandi.";
    }

    // Email unikal bo'lishi kerak (o'zgartirilganda)
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt_check->bind_param("si", $email, $user_id);
    $stmt_check->execute();
    $res_check = $stmt_check->get_result();
    if ($res_check->num_rows > 0) {
        $errors[] = "Bu email allaqachon ro'yxatdan o'tgan.";
    }

    if (empty($errors)) {
        $stmt_update = $conn->prepare("UPDATE users SET username = ?, email = ?, role = ?, updated_at = NOW() WHERE id = ?");
        $stmt_update->bind_param("sssi", $username, $email, $role, $user_id);

        if ($stmt_update->execute()) {
            $_SESSION['success'] = "Foydalanuvchi ma'lumotlari muvaffaqiyatli yangilandi.";
            header("Location: user_edit.php?id=" . $user_id);
            exit();
        } else {
            $errors[] = "Yangilashda xatolik yuz berdi.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <title>Foydalanuvchini tahrirlash</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body style="background: linear-gradient(to right, #1cb5e0, #000851); color: white; min-height: 100vh;">

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <a href="user_management.php" class="btn btn-outline-light"><i class="bi bi-arrow-left"></i> Orqaga</a>
        <span class="navbar-brand mx-auto">Foydalanuvchini tahrirlash</span>
        <div></div>
    </div>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                </div>
            <?php elseif (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="card p-4 text-dark shadow rounded">
                <form method="POST" novalidate>
                    <div class="mb-3">
                        <label for="username" class="form-label">Foydalanuvchi nomi</label>
                        <input type="text" class="form-control" id="username" name="username" 
                            value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email manzil</label>
                        <input type="email" class="form-control" id="email" name="email" 
                            value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Rolni tanlang</label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Foydalanuvchi</option>
                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Saqlash</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
