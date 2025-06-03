<?php
session_start();
include('../includes/db.php');

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($user_id) {
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_name = ($result && $result->num_rows > 0) ? $result->fetch_assoc()['username'] : 'Foydalanuvchi';
} else {
    $user_name = 'Foydalanuvchi';
}

$car_count = $conn->query("SELECT COUNT(*) FROM cars")->fetch_row()[0] ?? 0;
$available_car_count = $conn->query("SELECT COUNT(*) FROM cars WHERE status = 'available'")->fetch_row()[0] ?? 0;
$rented_car_count = $conn->query("SELECT COUNT(*) FROM rentals WHERE status = 'active'")->fetch_row()[0] ?? 0;
$user_count = $conn->query("SELECT COUNT(*) FROM users")->fetch_row()[0] ?? 0;
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f1f3f4;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar {
            background: #343a40;
        }

        .navbar-brand {
            font-weight: bold;
            color: #ffc107 !important;
        }

        .nav-link {
            color: #fff !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            background: linear-gradient(to bottom, #ffffff, #f8f9fa);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: #343a40;
            font-size: 1.1em;
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #e9ecef;
            border-left: 4px solid #ffc107;
            color: #000;
        }

        .main-content {
            margin-left: 260px;
            padding: 30px;
            padding-top: 90px;
        }

        .card {
            border: none;
            border-radius: 10px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .card-body {
            text-align: center;
        }

        .card-title {
            font-weight: bold;
            color: #343a40;
        }

        .card-text {
            font-size: 1.8rem;
            color: #ffc107;
        }

        .user-greeting {
            text-align: center;
            font-size: 1.6rem;
            margin-bottom: 30px;
            color: #343a40;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
            }

            .main-content {
                margin-left: 0;
                padding-top: 20px;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fas fa-car"></i> Admin Paneli</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item me-3">
                    <span class="nav-link"><i class="fas fa-user-circle"></i> <?= htmlspecialchars($user_name); ?></span>
                </li>
                <li class="nav-item">
                    <a href="../logout.php" class="btn btn-outline-warning btn-sm"><i class="fas fa-sign-out-alt"></i> Shigiw</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="text-center mb-3 fw-bold">Xosh kelibsiz, <?= htmlspecialchars($user_name); ?>!</div>
    <a href="../admin/car_management.php"><i class="fas fa-car"></i> Avtomobillerdi Basqariw</a>
    <a href="../admin/car_types_management.php"><i class="fas fa-list-alt"></i> Avtomobil Turleri</a>
    <a href="../admin/rental_management.php"><i class="fas fa-handshake"></i> Ijara Basqariwi</a>
    <a href="../admin/payment_management.php"><i class="fas fa-credit-card"></i> Tolewler Boshqariw</a>
    <a href="../admin/user_management.php"><i class="fas fa-users"></i> Paydalaniwshilardi Bosqariw</a>
    <a href="notifications.php"><i class="fas fa-bell"></i> Paydalaniwshi Xabarlari</a>
    <a href="account_settings.php"><i class="fas fa-cogs"></i> Accauntdi Basqariw</a>
    <a href="../index.php"><i class="fas fa-home"></i> Bas bet</a>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
    <div class="user-greeting">Salem, <?= htmlspecialchars($user_name); ?>!</div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Avtomobiller</h5>
                    <p class="card-text"><?= $car_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Bos Avtomobiller</h5>
                    <p class="card-text"><?= $available_car_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Ijara halatida</h5>
                    <p class="card-text"><?= $rented_car_count; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card bg-light">
                <div class="card-body">
                    <h5 class="card-title">Paydalaniwshilar</h5>
                    <p class="card-text"><?= $user_count; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
