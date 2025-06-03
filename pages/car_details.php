<?php
session_start();
include('../includes/db.php');

// Agar id mavjud bo'lsa, avtomobilni olish
if (isset($_GET['id'])) {
    $car_id = $_GET['id'];

    // Avtomobilni va uning turini bazadan olish
    $query = "SELECT cars.*, car_types.type_name AS car_type FROM cars 
              LEFT JOIN car_types ON cars.car_type_id = car_types.id 
              WHERE cars.id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Agar avtomobil topilgan bo'lsa, tafsilotlarini olish
    if ($result->num_rows > 0) {
        $car_details = $result->fetch_assoc(); // Avtomobil tafsilotlari
    } else {
        $_SESSION['message'] = "Avtomobil topilmadi!";
        header("Location: car_management.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Avtomobil IDsi berilmagan!";
    header("Location: car_management.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avtomobil Tafsilotlari</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(90deg, #0052D4, #4364F7, #6FB1FC);
            color: white;
        }
        .navbar .navbar-brand,
        .navbar a {
            color: white !important;
            font-weight: 600;
        }
        .container {
            background-color: #ffffff;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            padding: 40px;
            border-radius: 12px;
        }
        h3 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .card {
            border: none;
            border-radius: 10px;
            background-color: #fafafa;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .card-title {
            font-size: 1.5rem;
            color: #007bff;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .card p {
            font-size: 1rem;
            margin-bottom: 8px;
        }
        .btn-custom {
            background: linear-gradient(to right, #00b09b, #96c93d);
            color: white;
            border: none;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-custom:hover {
            background: linear-gradient(to right, #96c93d, #00b09b);
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .btn-light {
            border-radius: 25px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg px-4 py-2">
        <div class="container-fluid">
            <a class="navbar-brand" href="car_management.php">Avtomobil Boshqarish</a>
        </div>
    </nav>

    <div class="container mt-5">
        <h3>Avtomobil Detalları</h3>
        <?php if (isset($car_details)): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $car_details['brand'] . ' ' . $car_details['model']; ?></h5>
                    <p><strong>Model:</strong> <?php echo $car_details['model']; ?></p>
                    <p><strong>Jili:</strong> <?php echo $car_details['year']; ?></p>
                    <p><strong>Kunlik bahasi:</strong> <?php echo number_format($car_details['price_per_day'], 2); ?> so'm</p>
                    <p><strong>Jaǵdayı:</strong> <?php echo ucfirst($car_details['status']); ?></p>
                    <p><strong>Avtomobil turi:</strong> <?php echo $car_details['car_type']; ?></p>
                    <p><strong>Jaratılǵan:</strong> <?php echo $car_details['created_at']; ?></p>
                    <p><strong>Sońǵı ózgertiw:</strong> <?php echo $car_details['updated_at']; ?></p>
                    <div class="mt-4">
                        <a href="cars.php" class="btn btn-custom">🔙 Artqa</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <p>Avtomobil tafsilotlari mavjud emas.</p>
        <?php endif; ?>
    </div>
</body>
</html>

