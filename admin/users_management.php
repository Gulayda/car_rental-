<?php
session_start();
include('../includes/db.php');

// Foydalanuvchini o'chirish
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM users WHERE id = $delete_id");
    header("Location: users_management.php");
    exit();
}

// Foydalanuvchilar ro'yxati
$result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>Paydalanıwshılardı basqarıw</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .table {
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
        }
        .btn-danger {
            padding: 4px 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h3 class="text-center mb-4">👥 Paydalanıwshılardı basqarıw</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Paydalanıwshı atı</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Jaratılǵan</th>
                <th>Ámeller</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= ucfirst($user['role']) ?></td>
                    <td><?= $user['created_at'] ?></td>
                    <td>
                        <a href="users_management.php?delete_id=<?= $user['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bul paydalanıwshını óshiriwge iseniminiz kámil me??')">🗑️ Óshiriw</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
