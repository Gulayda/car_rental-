<?php
// Baza ulanishi uchun parametrlar
$host = 'localhost';     // Baza serveri
$username = 'root';      // Baza foydalanuvchisi
$password = 'root';          // Baza paroli (agar mavjud bo'lsa, kiriting)
$dbname = 'Avtomobil_ijarasi'; // Baza nomi

$conn = new mysqli($servername, $username, $password, $dbname);

// Ulashish xatoliklarini tekshirish
if ($conn->connect_error) {
    die("Ulanishda xatolik yuz berdi: " . $conn->connect_error);
}
?>