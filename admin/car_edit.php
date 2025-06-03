<?php
session_start();
include('../includes/db.php');

// Adminning ID sini olish
$admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : null;

// Avtomobilni tanlash
if (isset($_GET['id'])) {
    $car_id = $_GET['id'];

    // Avtomobil ma'lumotlarini olish
    $query = "SELECT * FROM cars WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $car_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $car = $result->fetch_assoc();

    // Agar avtomobil topilmasa
    if (!$car) {
        $_SESSION['message'] = "Avtomobil topilmadi!";
        header("Location: car_management.php");
        exit();
    }
} else {
    $_SESSION['message'] = "Avtomobil IDsi berilmagan!";
    header("Location: car_management.php");
    exit();
}

// Avtomobilni tahrirlash
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_car'])) {
    $brand = $_POST['brand'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $price_per_day = $_POST['price_per_day'];
    $car_type_id = $_POST['car_type_id'];

    if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $name = $_FILES['image']['name'];
        $upload_dir = "../uploads/";
        if (move_uploaded_file($tmp_name, $upload_dir . $name)) {
            echo "Rasm muvaffaqiyatli yuklandi!";
        } else {
            echo "Rasmni yuklashda xatolik yuz berdi.";
        }
    } else {
        echo "Faylni yuklashda xatolik yuz berdi.";
    }
    

    // SQL so'rovini tayyorlash
    $stmt = $conn->prepare("UPDATE cars SET brand = ?, model = ?, year = ?, price_per_day = ?, car_type_id = ?, image_url = ? WHERE id = ?");
    $stmt->bind_param("ssdisis", $brand, $model, $year, $price_per_day, $car_type_id, $image_url, $car_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "Avtomobil muvaffaqiyatli tahrirlandi!";
        header("Location: car_management.php");
        exit();
    } else {
        die('So\'rovni bajarishda xatolik yuz berdi: ' . $stmt->error);
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avtomobilni Tahrirlash</title>
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
            background-color:rgb(0, 177, 236);
        }
        .navbar a {
            color:rgb(70, 44, 44);
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
        <h3>Avtomobilni Tahrirlash</h3>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="brand" class="form-label">Brend</label>
                <input type="text" class="form-control" id="brand" name="brand" value="<?php echo $car['brand']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="model" class="form-label">Model</label>
                <input type="text" class="form-control" id="model" name="model" value="<?php echo $car['model']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Yil</label>
                <input type="number" class="form-control" id="year" name="year" value="<?php echo $car['year']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="price_per_day" class="form-label">Kunlik narx</label>
                <input type="number" class="form-control" id="price_per_day" name="price_per_day" value="<?php echo $car['price_per_day']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="car_type_id" class="form-label">Avtomobil turi</label>
                <select class="form-select" id="car_type_id" name="car_type_id" required>
                    <?php
                    $types_result = $conn->query("SELECT * FROM car_types");
                    while ($type = $types_result->fetch_assoc()) {
                        $selected = ($car['car_type_id'] == $type['id']) ? 'selected' : '';
                        echo "<option value='{$type['id']}' {$selected}>{$type['type_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Avtomobil rasmi</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <img src="<?php echo $car['image_url']; ?>" alt="Current Image" class="mt-3" style="max-width: 200px;">
            </div>
            <button type="submit" name="edit_car" class="btn btn-primary">Tahrirlash</button>
        </form>
    </div>
</body>
</html>
