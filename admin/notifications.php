<?php
// Bazaga ulanish
include '../includes/db.php';
session_start();

// Faqat admin kirishi mumkin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

// Xabarlarni olish
$query = "SELECT * FROM notifications ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

// Xato tekshiruvi
if (!$result) {
    die("Xatolik yuz berdi: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foydalanuvchi Xabarlari - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />


    <style>
        body {
            background-color: #f0f2f5;
        }
        .container {
            margin-top: 50px;
        }
        .badge {
            font-size: 0.9em;
        }
        .back-button {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1050;
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            background-color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-button:hover {
            background-color: #0d6efd;
            color: white;
            transform: translateY(-2px);
        }

    </style>
</head>
<body>

<a href="dashboard.php" 
   class="btn btn-outline-primary back-button">
    <i class="fas fa-arrow-left"></i> Artqa
</a>

<div class="container">
    <h2 class="mb-4">Paydalanıwshılardan kelgen xabarlar</h2>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Xabar jaǵdayı tabıslı jańalandı.</div>
    <?php endif; ?>

    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Ati</th>
                <th>Email</th>
                <th>Xabar</th>
                <th>Sane</th>
                <th>Jaǵday</th>
                <th>Ámel</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <?php if ($row['status'] == 0): ?>
                            <span class="badge bg-warning text-dark">Jańa</span>
                        <?php else: ?>
                            <span class="badge bg-success">Kórip shıǵılǵan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['status'] == 0): ?>
                            <a href="mark_as_read.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Kórip shıǵıldı dep belgilew</a>
                        <?php else: ?>
                            <button class="btn btn-sm btn-secondary" disabled>✔️</button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
