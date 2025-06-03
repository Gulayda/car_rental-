<?php
session_start();
include('../includes/db.php');

// Adminning ID sini olish
$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

// Avtomobilni o'chirish
if (isset($_GET['id'])) {
    $car_id = $_GET['id'];

    // Avtomobilning rasm faylini olish
    $query = "SELECT image FROM cars WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $stmt->store_result();
    
    // Agar avtomobil mavjud bo'lsa, rasmni o'chirish
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($image);
        $stmt->fetch();

        // Rasm faylini serverdan o'chirish
        $image_path = '../uploads/' . $image; // Rasmni joylashgan joyini to'liq yo'l
        if (file_exists($image_path)) {
            unlink($image_path); // Rasmni o'chirish
        }

        // Avtomobilni bazadan o'chirish
        $query = "DELETE FROM cars WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $car_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Avtomobil muvaffaqiyatli o'chirildi!";
            header("Location: car_management.php");
            exit();
        } else {
            $_SESSION['message'] = "Avtomobilni o'chirishda xatolik yuz berdi!";
            header("Location: car_management.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Bu avtomobil topilmadi!";
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
    <title>Avtomobilni O'chirish</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .container {
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 8px;
        }
        h3 {
            color: #007bff;
        }
        .btn {
            margin: 5px;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar a {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="car_management.php">Avtomobil Boshqarish</a>
            <div class="d-flex">
                <a class="btn btn-light" href="car_management.php">Orqaga</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h3>Avtomobilni o'chirish</h3>
        <p>Avtomobilni o'chirishni tasdiqlaysizmi?</p>
        <form method="POST" action="car_delete.php?id=<?php echo $_GET['id']; ?>">
            <button type="submit" class="btn btn-danger">Ha, o'chirilsin</button>
            <a href="car_management.php" class="btn btn-secondary">Orqaga</a>
        </form>
    </div>
</body>
</html>
