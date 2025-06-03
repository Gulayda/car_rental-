<?php
include 'includes/db.php';  // Baza ulanniwi

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Foydalanuvchi ma'lumotlarini sanitatsiya qilish
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);  // Parolni shifrlash

    // Email va username uchun validatsiya
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Yaroqsiz email manzili!";
        exit;
    }

    // Ma'lumotlar bazasiga qo'shish
    $query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$password', 'user')";

    if (mysqli_query($conn, $query)) {
        header('Location: login.php');
        exit;
    } else {
        echo "Xatolik: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dizimnen ótiw - Avtomobil Ijarası Platforması</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('assets/paulo-carrolo-xrcHixz9kXU-unsplas.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #fff;
            margin: 0;
            height: 100vh;
        }
        .register-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 30px;
            background-color:  rgba(4, 120, 151, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.4);
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
        .form-control {
            margin-bottom: 15px;
        }
        .btn-register {
            width: 100%;
            background-color: rgb(33, 8, 156);
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 30px;
            transition: background-color 0.3s ease;
        }
        .btn-register:hover {
            background-color:rgb(21, 101, 192);
        }
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 38px;
            cursor: pointer;
            color: #ccc;
        }
        .navbar {
        background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
        }
        .navbar-brand {
            font-size: 18px;
            color: #ffffff !important;
            transition: color 0.3s ease;
        }
        .navbar-brand:hover {
            color:rgb(0, 13, 255) !important;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fas fa-arrow-left"></i> Artqa
        </a>
    </div>
</nav>


<div class="register-form">
    <h2 class="text-center">Dizimnen ótiw</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label for="username">Paydalaniwshi ati:</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email manzil:</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group position-relative">
            <label for="password">Parol:</label>
            <input type="password" name="password" id="password" class="form-control" required>
            <i class="fa-solid fa-eye password-toggle" id="togglePassword" onclick="togglePassword()"></i>
        </div>
        <button type="submit" class="btn btn-register">Dizimnen ótiw</button>
    </form>
    <p class="text-center mt-3">
    Dizimnen otkensiz be? <a href="login.php" class="text-white">Kiriw</a>
    </p>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById("password");
    const toggleIcon = document.getElementById("togglePassword");

    const isPasswordVisible = passwordInput.type === "text";
    passwordInput.type = isPasswordVisible ? "password" : "text";
    toggleIcon.classList.toggle("fa-eye");
    toggleIcon.classList.toggle("fa-eye-slash");
}
</script>

</body>
</html>
