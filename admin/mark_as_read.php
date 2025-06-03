<?php
// Ma'lumotlar bazasiga ulanish
include '../includes/db.php';
session_start();

// Faqat admin foydalanuvchi kira oladi
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Xabar ID si yuborilganini tekshirish
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Xavfsizlik uchun intga aylantirish

    // Avval xabar mavjudligini tekshirish
    $checkQuery = "SELECT * FROM notifications WHERE id = $id";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Statusni yangilash
        $updateQuery = "UPDATE notifications SET status = 1 WHERE id = $id";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: notifications.php?success=1");
            exit();
        } else {
            die("Xatolik yuz berdi: " . mysqli_error($conn));
        }
    } else {
        // Noto‘g‘ri ID yuborilgan
        header("Location: notifications.php?error=notfound");
        exit();
    }
} else {
    // ID umuman yuborilmagan bo‘lsa
    header("Location: notifications.php?error=invalid");
    exit();
}
