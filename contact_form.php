<?php
include 'includes/db.php'; // Bazaga ulanish
session_start();

// Foydalanuvchi forma yuborganini tekshiramiz
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formadan kelgan ma'lumotlarni xavfsiz olish
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Maydonlar bo‘sh emasligini tekshirish
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Ma'lumotni "notifications" jadvaliga qo'shish
        $query = "INSERT INTO notifications (name, email, message, status) 
                  VALUES ('$name', '$email', '$message', 0)";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Muvaffaqiyatli yozildi – foydalanuvchini qayta yo‘naltirish
            header("Location: contact.php?success=1");
            exit();
        } else {
            // Xatolik yuz berdi
            echo "Xatolik: " . mysqli_error($conn);
        }
    } else {
        echo "Barcha maydonlarni to‘ldiring!";
    }
}
?>
