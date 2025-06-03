<?php include 'includes/db.php'; session_start(); ?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avtomobil Ijarasi Platformasi</title>

    <!-- SEO Meta Tags -->
    <meta name="description" content="Avtomobil ijarası platforması. Eń jaqsı avtomobillerdi ijaraǵa alıw ushın biziń platformamızdan paydalanıń.">
    <meta name="keywords" content="avtomobil, ijara, avtomobil ijarası, renta, travel, avtomobil platforması">
    <meta property="og:image" content="assets/images/background.jpg">
    <meta property="og:title" content="Avtomobil Ijarasi Platformasi">
    <meta property="og:description" content="Eń jaqsı avtomobillerdi ijaraǵa alıw ushın biziń platformamızdan paydalanıń.">

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            background: url('assets/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .navbar {
            background-color: rgba(4, 120, 151, 0.95);
            padding: 12px 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            border-radius: 0 0 20px 20px;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .navbar-nav {
            gap: 20px;
        }

        .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: #003e7e !important;
            background-color: #ffffff80;
            border-radius: 20px;
            padding: 8px 16px;
        }

        .hero-section {
            padding: 100px 20px;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.5);
            margin: 30px auto;
            border-radius: 20px;
            max-width: 1000px;
        }

        .hero-section h1 {
            font-size: 3.2rem;
            font-weight: bold;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.7);
        }

        .hero-section p {
            font-size: 1.3rem;
            margin-top: 20px;
        }

        .btn-main {
            background-color: rgb(33, 150, 243);
            color: #fff;
            padding: 12px 30px;
            font-size: 1.2rem;
            border-radius: 30px;
            margin-top: 30px;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-main:hover {
            background-color: rgb(21, 101, 192);
            transform: scale(1.05);
        }

        footer {
            background-color: rgba(6, 131, 165, 0.9);
            padding: 20px 0;
            margin-top: 50px;
            text-align: center;
            color: #fff;
        }
    </style>
</head>
<body>

<!-- Navigatsiya -->
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <a class="navbar-brand text-white" href="#">
            <i class="fas fa-car-side"></i> Avtomobil Ijarasi
        </a>
        <div class="collapse navbar-collapse justify-content-end">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php"><i class="fas fa-home"></i> Bas bet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../contact.php"><i class="fas fa-envelope"></i> Baylanıs</a>
                </li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../pages/cars.php"><i class="fas fa-car"></i> Avtomobiller</a>
                    </li>
                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../admin/dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-warning btn-sm text-white" href="../logout.php">
                            <i class="fas fa-sign-out-alt"></i> Shigiw
                        </a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../login.php"><i class="fas fa-sign-in-alt"></i> Kiriw</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../register.php"><i class="fas fa-user-plus"></i> Dizimnen ótiw</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container text-center">
        <h1><i class="fas fa-car-side"></i> Avtomobil Ijarası Platformasına Xosh keldińiz!</h1>
        <p><i class="fas fa-map-marked-alt"></i> Eń jaqsı avtomobillerdi ijaraǵa alıw hám <i class="fas fa-road"></i> óz saparıńızdı baslaw ushın biziń platformamızdan paydalanıń.</p>
        <a href="../pages/cars.php" class="btn btn-main"><i class="fas fa-car"></i> Avtomobiller</a>
    </div>
</section>

<!-- Footer -->
<footer>
    <p>&copy; 2025 Avtomobil Ijarasi Platformasi. Barlıq huqıqlar qorǵalǵan.</p>
</footer>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
