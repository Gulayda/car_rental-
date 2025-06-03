<?php
session_start();
include('../includes/db.php');

// Ijaralarni olish
$query = "SELECT rentals.id, rentals.rental_start, rentals.rental_end, rentals.total_price, rentals.status, 
                 cars.brand, cars.model, users.username AS user_name
          FROM rentals
          INNER JOIN cars ON rentals.car_id = cars.id
          INNER JOIN users ON rentals.user_id = users.id
          ORDER BY rentals.created_at DESC";

$result = $conn->query($query);

// Ijara holatini yangilash
if (isset($_GET['action'], $_GET['id'], $_GET['status']) && $_GET['action'] === 'update_status') {
    $rental_id = (int)$_GET['id'];
    $new_status = $_GET['status'];

    // Status uchun faqat ruxsat etilgan qiymatlar
    $allowed_statuses = ['active', 'completed', 'cancelled'];
    if (in_array($new_status, $allowed_statuses)) {
        $stmt = $conn->prepare("UPDATE rentals SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $new_status, $rental_id);
        if ($stmt->execute()) {
            $_SESSION['message'] = "Ijara holati muvaffaqiyatli yangilandi!";
        } else {
            $_SESSION['message'] = "Ijara holatini yangilashda xatolik yuz berdi!";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Noto‘g‘ri holat qiymati!";
    }

    header("Location: rental_management.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ijara basqarıwı</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f9f9f9;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #fff;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            padding: 30px;
            border-radius: 12px;
            margin-top: 50px;
        }
        h3 {
            color: #007bff;
            font-weight: 700;
            margin-bottom: 30px;
            font-size: 2rem;
        }
        .table thead {
            background-color: #007bff;
            color: #fff;
        }
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: #f2f2f2;
        }
        .alert-info {
            background-color: #e7f7ff;
            border-color: #b8e0ff;
            color: #31708f;
        }
        .navbar {
            background-color: #2c3e50;
        }
        .navbar-brand {
            color: #f39c12 !important;
            font-weight: 700;
        }
        .navbar a {
            color: #ecf0f1 !important;
            transition: color 0.3s ease;
        }
        .navbar a:hover {
            color: #f39c12 !important;
        }
        .badge {
            font-size: 0.875rem;
            padding: 0.4em 0.75em;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Admin Paneli</a>
            <div class="d-flex">
                <a class="btn btn-outline-primary" href="dashboard.php">Artqa</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3>Ijara basqarıwı</h3>

        <?php if (!empty($_SESSION['message'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Paydalanıwshı</th>
                        <th>Avtomobil</th>
                        <th>Ijara Baslaniw</th>
                        <th>Ijara Tamamlanıw</th>
                        <th>Jámi tólem</th>
                        <th>Jaǵday</th>
                        <th>Ameller</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo (int)$row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['brand'] . ' ' . $row['model']); ?></td>
                                <td><?php echo htmlspecialchars($row['rental_start']); ?></td>
                                <td><?php echo htmlspecialchars($row['rental_end']); ?></td>
                                <td><?php echo number_format($row['total_price'], 2, ',', ' '); ?> so'm</td>
                                <td>
                                    <?php
                                        switch ($row['status']) {
                                            case 'active':
                                                echo '<span class="badge badge-warning">aktiv</span>';
                                                break;
                                            case 'completed':
                                                echo '<span class="badge badge-success">Tamamlanǵan</span>';
                                                break;
                                            case 'cancelled':
                                                echo '<span class="badge badge-danger">biykar etilgen</span>';
                                                break;
                                            default:
                                                echo '<span class="badge bg-secondary">belgisiz</span>';
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php if ($row['status'] === 'active'): ?>
                                        <a href="rental_management.php?action=update_status&id=<?php echo $row['id']; ?>&status=completed" class="btn btn-success btn-sm me-1" onclick="return confirm('Ijara jaǵdayın Tamamlangan dep ózgertpekshimisiz?')">Tamamlandı</a>
                                        <a href="rental_management.php?action=update_status&id=<?php echo $row['id']; ?>&status=cancelled" class="btn btn-danger btn-sm" onclick="return confirm('Ijara jaǵdayın biykar etilgen dep ózgertpekshimisiz?')">Biykarlaw</a>
                                    <?php else: ?>
                                        <span class="text-muted">Ámel joq</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Ijara bar emes!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
