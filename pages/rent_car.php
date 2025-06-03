<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$car_id = $_GET['car_id'] ?? null;

if (!$car_id) {
    echo "Avtomobil ID topilmadi.";
    exit();
}

// Avtomobil ma'lumotlarini olish
$car_query = "SELECT * FROM cars WHERE id = $car_id";
$car_result = $conn->query($car_query);
$car = $car_result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['rental_start'];
    $end_date = $_POST['rental_end'];
    $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
    $total_price = $car['price_per_day'] * $days;

    $insert = "INSERT INTO rentals (user_id, car_id, rental_start, rental_end, total_price)
               VALUES ('$user_id', '$car_id', '$start_date', '$end_date', '$total_price')";
    if ($conn->query($insert)) {
        header("Location: payment.php?rental_id=" . $conn->insert_id);
        exit();
    } else {
        echo "Xatolik: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Avtomobildi ijaraga aliw</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3><?php echo $car['brand'] . ' ' . $car['model']; ?> ijarasi</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Ijaranıń baslanǵan sánesi</label>
            <input type="date" name="rental_start" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ijaranıń tamamlanǵan sánesi</label>
            <input type="date" name="rental_end" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Ijaraga aliw</button>
        <a href="cars.php" class="btn btn-secondary">Artqa</a>
    </form>
</div>
</body>
</html>
