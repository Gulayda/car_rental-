<?php
session_start();
include('../includes/db.php');

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $messages = [];

    // Emailni yangilash
    if ($new_email !== $user['email']) {
        $update_email = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $update_email->bind_param("si", $new_email, $user_id);
        if ($update_email->execute()) {
            $messages[] = "📧 Email yangilandi.";
        } else {
            $messages[] = "❌ Emailni yangilashda xatolik.";
        }
    }

    // Parolni yangilash
    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        if (password_verify($current_password, $user['password'])) {
            if ($new_password === $confirm_password) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_password = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_password->bind_param("si", $hashed_password, $user_id);
                if ($update_password->execute()) {
                    $messages[] = "🔒 Parol yangilandi.";
                } else {
                    $messages[] = "❌ Parolni yangilashda xatolik.";
                }
            } else {
                $messages[] = "❌ Yangi parol va tasdiqlash paroli mos emas.";
            }
        } else {
            $messages[] = "❌ Joriy parol noto‘g‘ri.";
        }
    }

    $_SESSION['message'] = implode("<br>", $messages);
    header("Location: account_settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Esap parametrleri</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #1cb5e0, #000851);
            color: #fff;
            min-height: 100vh;
        }
        .card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            color: #000;
        }
        .btn-custom {
            background-color: #1cb5e0;
            border: none;
            border-radius: 10px;
            padding: 10px;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #159ecb;
        }
        .form-control {
            border-radius: 10px;
        }
        .alert {
            border-radius: 10px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="bi bi-person-circle"></i> Parametrler</a>
        <a class="btn btn-outline-light ms-auto" href="javascript:history.back()"><i class="bi bi-arrow-left"></i> Artqa</a>
    </div>
</nav>

<!-- Kontent -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-info"><?php echo $_SESSION['message']; unset($_SESSION['message']); ?></div>
            <?php endif; ?>

            <div class="card p-4">
                <h4 class="text-center mb-4">🔧Esaptı jańalaw</h4>
                <form method="POST">
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Elektron pochta mánzilińiz</label>
                        <input type="email" name="email" id="email" class="form-control" value="<?= $user['email'] ?>" required>
                    </div>

                    <!-- Current password -->
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Ámeldegi parol</label>
                        <input type="password" name="current_password" id="current_password" class="form-control">
                    </div>

                    <!-- New password -->
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Jańa parol</label>
                        <input type="password" name="new_password" id="new_password" class="form-control">
                    </div>

                    <!-- Confirm password -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Paroldı tastıyıqlaw</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-custom w-100">Saqlaw</button>
                </form>

            </div>

        </div>
    </div>
</div>

<!-- Footer -->
<footer class="text-center text-white py-3 bg-dark mt-5">
    <small>&copy; 2025 Avtomobil Ijarasi Platformasi. Barlıq huqıqlar qorǵalǵan.</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
