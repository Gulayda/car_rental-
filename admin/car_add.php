<?php
session_start();
include('../includes/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand']);
    $model = trim($_POST['model']);
    $year = (int)$_POST['year'];
    $price = (int)$_POST['price_per_day'];
    $car_type_id = (int)$_POST['car_type_id'];

    $image_url = '';
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = "../uploads/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $ext = pathinfo($_FILES['image_url']['name'], PATHINFO_EXTENSION);
        $image_name = uniqid('car_', true) . "." . $ext;
        $target_path = $upload_dir . $image_name;

        if (move_uploaded_file($_FILES['image_url']['tmp_name'], $target_path)) {
            $image_url = $image_name;
        } else {
            echo "<div class='alert alert-danger'>Rasm yuklashda xatolik yuz berdi.</div>";
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>Rasm tanlanmadi yoki yuklashda xatolik.</div>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO cars (brand, model, year, price_per_day, car_type_id, image_url) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiis", $brand, $model, $year, $price, $car_type_id, $image_url);

    if ($stmt->execute()) {
        header("Location: car_management.php?success=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Xatolik: " . $stmt->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <title>Jańa avtomobil qosıw</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Umumiy orqa fon */
        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg,rgb(44, 107, 138),rgb(45, 4, 177));
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* Orqaga tugma */
        .btn-back {
            position: fixed;
            top: 30px;
            left: 30px;
            background-color: rgba(255, 255, 255, 0.3);
            border: none;
            color: #fff;
            padding: 10px 18px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;
            cursor: pointer;
            z-index: 1000;
        }
        .btn-back:hover {
            background-color: rgba(255, 255, 255, 0.6);
            color: #333;
        }

        /* Form container */
        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 50px;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 600px;
            color: #333;
        }

        h3 {
            font-weight: 700;
            margin-bottom: 30px;
            color: #333;
            text-align: center;
            letter-spacing: 1.1px;
        }

        label.form-label {
            font-weight: 600;
            color: #555;
        }

        /* Button Qo'shish */
        button.btn-success {
            background: #4caf50;
            border: none;
            padding: 12px 28px;
            font-weight: 700;
            font-size: 16px;
            border-radius: 50px;
            transition: background 0.3s ease;
        }
        button.btn-success:hover {
            background: #45a049;
        }
    </style>
</head>
<body>

<button type="button" class="btn-back" onclick="history.back()">← Artqa</button>

<div class="form-container shadow-sm">
    <h3>Jańa avtomobil qosıw</h3>
    <form method="POST" enctype="multipart/form-data" novalidate>
        <div class="mb-3">
            <label for="brand" class="form-label">Brand</label>
            <input type="text" id="brand" name="brand" class="form-control" required placeholder="" />
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" id="model" name="model" class="form-control" required placeholder="" />
        </div>
        <div class="mb-3">
            <label for="year" class="form-label">Jil</label>
            <input type="number" id="year" name="year" class="form-control" required min="1990" max="<?php echo date('Y'); ?>" placeholder="" />
        </div>
        <div class="mb-3">
            <label for="price_per_day" class="form-label">Bahasi (so'm/kun)</label>
            <input type="number" id="price_per_day" name="price_per_day" class="form-control" required min="1000" placeholder="" />
        </div>
        <div class="mb-3">
            <label for="car_type_id" class="form-label">Avtomobil turi</label>
            <select id="car_type_id" name="car_type_id" class="form-select" required>
                <option value="" selected disabled>Túrin tanlań</option>
                <?php
                $types_result = $conn->query("SELECT * FROM car_types");
                while ($type = $types_result->fetch_assoc()) {
                    echo "<option value='{$type['id']}'>{$type['type_name']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-4">
            <label for="image_url" class="form-label">Súwret</label>
            <input type="file" id="image_url" name="image_url" class="form-control" accept="image/*" required />
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-success">Qosiw</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
