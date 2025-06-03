<?php
session_start();
include('../includes/db.php');

// To'lovlar ro'yxatini olish
$query = "SELECT * FROM payments ORDER BY payment_date DESC";
$result = $conn->query($query);

// To'lovni o'chirish
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $payment_id = $_GET['id'];
    
    // To'lovni o'chirish
    $delete_query = "DELETE FROM payments WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $payment_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "To'lov muvaffaqiyatli o'chirildi!";
    } else {
        $_SESSION['message'] = "To'lovni o'chirishda xatolik yuz berdi!";
    }
    header("Location: payment_management.php"); // O'chirishdan keyin sahifaga qaytadi
    exit();
}

?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To'lovlar Boshqaruvi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f5f8;
            font-family: 'Arial', sans-serif;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
        }
        h3 {
            color: #4e73df;
            font-size: 2rem;
        }
        .navbar {
            background-color: #4e73df;
        }
        .navbar a {
            color:rgb(10, 58, 91);
        }
        .btn {
            margin: 5px;
        }
        .alert {
            margin-top: 20px;
        }
        .table th, .table td {
            text-align: center;
        }
        .badge {
            font-size: 14px;
        }
        .badge-danger {
            background-color: #e74a3b;
        }
        .badge-success {
            background-color: #1cc88a;
        }
        .badge-warning {
            background-color: #f6c23e;
        }
        .btn-primary, .btn-danger {
            width: 100%;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Admin Paneli</a>
            <div class="d-flex">
                <a class="btn btn-light" href="dashboard.php">Artqa</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h3>Tólemlerdi basqarıw</h3>

        <!-- Xabarlar -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Paydalanıwshı ID</th>
                    <th>Tólem muǵdarı</th>
                    <th>Tólem usılı</th>
                    <th>Tólem jaǵdayı</th>
                    <th>Tólem waqtı</th>
                    <th>Ámeller</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['rental_id']; ?></td>
                            <td><?php echo number_format($row['payment_amount'], 2); ?> swm</td>
                            <td><?php echo ucfirst($row['payment_method']); ?></td>
                            <td>
                                <?php
                                    // To'lov holatini tekshirish
                                    if ($row['payment_status'] == 'pending'): ?>
                                        <span class="badge badge-danger">Tólenbegen</span>
                                    <?php elseif ($row['payment_status'] == 'completed'): ?>
                                        <span class="badge badge-success">Tólendi</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Bilinbedi</span>
                                    <?php endif; ?>
                            </td>
                            <td><?php echo date('d-m-Y H:i:s', strtotime($row['payment_date'])); ?></td>
                            <td>
                                <a href="payment_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Redaktorlaw</a>
                                <a href="payment_management.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tólemdi óshiriwdi qáleysiz be??')">Óshiriw</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tólemler joq!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
