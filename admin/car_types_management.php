<?php
session_start();
include('../includes/db.php');

// Qo‘shish
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_type'])) {
    $type_name = trim($_POST['type_name']);
    $stmt = $conn->prepare("INSERT INTO car_types (type_name) VALUES (?)");
    $stmt->bind_param("s", $type_name);
    $stmt->execute();
    $_SESSION['message'] = "✅ Yangi avtomobil turi qo‘shildi!";
    header("Location: car_types_management.php");
    exit();
}

// Tahrirlash uchun ma'lumot olish
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM car_types WHERE id = $edit_id");
    $edit_data = $edit_result->fetch_assoc();
}

// Tahrirlashni saqlash
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_type'])) {
    $id = $_POST['id'];
    $type_name = trim($_POST['type_name']);
    $stmt = $conn->prepare("UPDATE car_types SET type_name = ? WHERE id = ?");
    $stmt->bind_param("si", $type_name, $id);
    $stmt->execute();
    $_SESSION['message'] = "✏️ Avtomobil turi yangilandi!";
    header("Location: car_types_management.php");
    exit();
}

// O‘chirish
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM car_types WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $_SESSION['message'] = "🗑️ Avtomobil turi o‘chirildi!";
    header("Location: car_types_management.php");
    exit();
}

// Ro‘yxat
$result = $conn->query("SELECT * FROM car_types ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Avtomobil túrlerin basqarıw</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #c2e9fb 0%, #a1c4fd 100%);
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            margin-top: 40px;
        }
        .form-section {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
        .table th {
            background-color:rgb(41, 48, 58);
            color: white;
        }
        .navbar {
            background-color:rgb(9, 138, 145);
        }
        .navbar .navbar-brand, .navbar a {
            color: black;
            font-weight: bold;
        }
        .btn-custom {
            min-width: 100px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid px-4">
        <a class="navbar-brand" href="#">🚗Avtomobil túrleri</a>
        <div class="d-flex">
            <a href="dashboard.php" class="btn btn-light btn-sm">🏠 Artqa</a>
        </div>
    </div>
</nav>

<div class="container">

    <h2 class="mb-4 text-center text-primary">Avtomobil túrlerin basqarıw</h2>

    <!-- Xabar -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?= $_SESSION['message']; unset($_SESSION['message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Yopish"></button>
        </div>
    <?php endif; ?>

    <!-- Form: Qo‘shish / Tahrirlash -->
    <div class="form-section mb-4">
        <form method="POST">
            <div class="row align-items-end">
                <div class="col-md-6">
                    <label class="form-label">Avtomobil turi nomi:</label>
                    <input type="text" name="type_name" class="form-control" required value="<?= isset($edit_data) ? htmlspecialchars($edit_data['type_name']) : '' ?>">
                </div>
                <div class="col-md-6">
                    <?php if (isset($edit_data)): ?>
                        <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                        <button type="submit" name="edit_type" class="btn btn-warning btn-custom mt-3 mt-md-0">✏️ Jańalaw</button>
                        <a href="car_types_management.php" class="btn btn-secondary btn-custom mt-3 mt-md-0">❌ Biykarlaw</a>
                    <?php else: ?>
                        <button type="submit" name="add_type" class="btn btn-primary btn-custom mt-3 mt-md-0">+ Qosıw</button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Jadval -->
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead>
                <tr>
                    <th style="width: 60px;">#</th>
                    <th>Avtomobil turi</th>
                    <th style="width: 200px;">Ameller</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): $i = 1; ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['type_name']) ?></td>
                            <td>
                                <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-outline-primary">✏️ Redaktorlaw</a>
                                <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Shınnan da óshirmekshimisiz??')">🗑️ Óshiriw</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">🚫 Házirshe avtomobil túri joq.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
