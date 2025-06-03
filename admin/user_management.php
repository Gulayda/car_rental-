<?php
session_start();
include('../includes/db.php');

$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($query);

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $user_id);
    if ($stmt->execute()) {
        $_SESSION['message'] = "✅ Paydalanıwshı tabıslı óshirildi!";
    } else {
        $_SESSION['message'] = "❌Paydalanıwshını óshiriwde qáte júz berdi!";
    }
    header("Location: users_management.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paydalanıwshılardı basqarıw</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            background-color: #f1f4f8;
        }
        .container {
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            padding: 40px 30px;
        }
        h3 {
            color: #333;
            margin-bottom: 30px;
        }
        .btn {
            margin: 2px;
        }
        .navbar {
            background-color: #0d6efd;
        }
        .navbar .navbar-brand,
        .navbar a {
            color: #fff !important;
        }
        .table thead {
            background-color: #0d6efd;
            color: white;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg mb-4">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="dashboard.php">Admin Paneli</a>
            <div class="ms-auto">
                <a href="dashboard.php" class="btn btn btn-outline-light ">⬅️ Artqa</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h3><i class="bi bi-people-fill"></i> Paydalanıwshılardı basqarıw</h3>

        <!-- Xabarlar -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="jabıw"></button>
            </div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-striped table-bordered align-middle">
                <thead class="text-center">
                    <tr>
                        <th>#</th>
                        <th>Ati</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Dizimge alınǵan waqti</th>
                        <th>Ámeller</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="text-center"><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo ucfirst($row['role']); ?></td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td class="text-center">
                                    <a href="user_edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-primary">✏️ redaktorlaw</a>
                                    <a href="users_management.php?action=delete&id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Foydalanuvchini o‘chirishni istaysizmi?')">🗑️ óshiriw</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Hesh qanday paydalanıwshı tabılmadı!</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
