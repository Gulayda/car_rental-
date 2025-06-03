<?php
session_start();
include('../includes/db.php');

// Admin ID
$admin_id = $_SESSION['admin_id'] ?? null;

// Avtomobillarni olish
$query = "SELECT * FROM cars ORDER BY id DESC";
$cars_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avtomobillarni Boshqarish</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to right, #d3cce3, #e9e4f0);
            font-family: 'Segoe UI', sans-serif;
        }
        .navbar {
            background-color: #2c3e50;
        }
        .navbar-brand, .navbar .nav-link {
            color: white !important;
        }
        .navbar .nav-link:hover {
            color: #f1c40f !important;
        }
        .container {
            background: #ffffff;
            padding: 30px;
            margin-top: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        h3 {
            color: #2c3e50;
            font-weight: bold;
        }
        .table thead {
            background-color: #34495e;
            color: white;
        }
        .btn {
            min-width: 90px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">🚘 Avtomobiller basqarıwı</a>
        <button class="navbar-toggler bg-warning" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon text-white"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="car_add.php">+ Jańa avtomobil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">🏠 Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">

    <h3 class="mb-4 text-center">Avtomobiller dizimi</h3>

    <!-- Xabarlar -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Yopish"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Brend</th>
                    <th>Model</th>
                    <th>Jil</th>
                    <th>Kúnlik baha</th>
                    <th>Status</th>
                    <th style="width: 170px;">Ámeller</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($cars_result->num_rows > 0): $i = 1; ?>
                    <?php while ($car = $cars_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($car['brand']) ?></td>
                            <td><?= htmlspecialchars($car['model']) ?></td>
                            <td><?= htmlspecialchars($car['year']) ?></td>
                            <td><?= number_format($car['price_per_day'], 0, '', ' ') ?> swm</td>
                            <td>
                                <span class="badge bg-<?= $car['status'] === 'available' ? 'success' : 'secondary' ?>">
                                    <?= ucfirst($car['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="car_edit.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-warning">✏️ redaktorlaw</a>
                                <a href="car_delete.php?id=<?= $car['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Avtomobildi óshiriwdi qáleysiz be?')">🗑️ óshiriw</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted">🚫 Hozircha avtomobil mavjud emas.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
