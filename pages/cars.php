<?php 
session_start();
include('../includes/db.php');

// Avtomobillar ro'yxatini olish
$query = "SELECT cars.*, car_types.type_name FROM cars 
          LEFT JOIN car_types ON cars.car_type_id = car_types.id";
$cars_result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Avtomobillar</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (Iconlar uchun) -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .navbar-brand {
            font-weight: bold;
            font-size: 24px;
        }
        .car-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: scale(1.02);
        }
        .card-body h5 {
            font-weight: bold;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 20px 0;
            margin-top: 40px;
        }
        .nav-link {
            font-weight: 500;
        }
        .btn-sm {
            font-size: 0.875rem;
            padding: 5px 12px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
    <div class="container">
        <a class="navbar-brand" href="#">🚗 Avtomobil Ijarasi</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="../index.php">Bas bet</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Kontent -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Bar Avtomobiller</h3>

    <!-- Mahsulot qo'shilganda muvaffaqiyatli xabar -->
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="alert alert-success text-center">
        Ónim tabıslı qosıldı!
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if ($cars_result->num_rows > 0): ?>
            <?php while ($car = $cars_result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <!-- Rasmni ko'rsatish -->
                        <img src="<?= "../uploads/" . $car['image_url'] ?>" class="card-img-top car-image" alt="Avtomobil rasmi">

                        <div class="card-body">
                            <h5 class="card-title"><?php echo $car['brand']; ?> <?php echo $car['model']; ?></h5>
                            <p class="card-text mb-1"><strong>Jil:</strong> <?php echo $car['year']; ?></p>
                            <p class="card-text mb-1"><strong>Bahasi:</strong> <?php echo $car['price_per_day']; ?> so'm/kun</p>
                            <p class="card-text mb-2"><strong>Turi:</strong> <?php echo $car['type_name']; ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="car_details.php?id=<?php echo $car['id']; ?>" class="btn btn-outline-info btn-sm">
                                    <i class="fas fa-info-circle"></i> Detallar
                                </a>
                                <a href="rent_car.php?car_id=<?php echo $car['id']; ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-car"></i> Ijaraga aliw
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-warning text-center">Házirshe avtomobiller joq.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<footer class="text-center">
    <div class="container">
        <p class="mb-2">© 2025 Avtomobil Ijarasi</p>
        <div class="social-icons">
            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white"><i class="fab fa-instagram"></i></a>
        </div>
    </div>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
