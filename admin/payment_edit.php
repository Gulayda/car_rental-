<?php
session_start();
include('../includes/db.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "To'lov ID topilmadi."; exit;
}

$query = "SELECT * FROM payments WHERE id = $id";
$result = $conn->query($query);
$payment = $result->fetch_assoc();

// To'lov holati (statusi) faqat 'pending' yoki 'completed' bo'lishi kerak
$valid_statuses = ['pending', 'completed'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment_amount = $_POST['payment_amount'];
    $payment_method = $_POST['payment_method'];
    $payment_status = $_POST['payment_status'];

    // Agar kiritilgan holat noto'g'ri bo'lsa, xatolikni ko'rsating
    if (!in_array($payment_status, $valid_statuses)) {
        echo "Xato: Noto'g'ri to'lov holati.";
        exit;
    }

    // To'lovni yangilash
    $update = "UPDATE payments SET 
        payment_amount = '$payment_amount',
        payment_method = '$payment_method',
        payment_status = '$payment_status'
        WHERE id = $id";

    if ($conn->query($update)) {
        $_SESSION['message'] = "To'lov yangilandi.";
        header("Location: payment_management.php");
        exit;
    } else {
        echo "Xatolik: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>To'lovni tahrirlash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3>Tólemdi redaktorlaw</h3>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Tólem muǵdarı (sum)</label>
            <input type="number" step="0.01" name="payment_amount" class="form-control" value="<?= $payment['payment_amount'] ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">To'lov usuli</label>
            <select name="payment_method" class="form-select" required>
                <option value="cash" <?= $payment['payment_method'] == 'cash' ? 'selected' : '' ?>>Naq</option>
                <option value="credit_card" <?= $payment['payment_method'] == 'credit_card' ? 'selected' : '' ?>>Karta</option>
                <option value="paypal" <?= $payment['payment_method'] == 'paypal' ? 'selected' : '' ?>>PayPal</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">To'lov holati</label>
            <select name="payment_status" class="form-select" required>
                <option value="pending" <?= $payment['payment_status'] == 'pending' ? 'selected' : '' ?>>Kútilmekte</option>
                <option value="completed" <?= $payment['payment_status'] == 'completed' ? 'selected' : '' ?>>Tólendi</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Saqlaw</button>
        <a href="payment_management.php" class="btn btn-secondary">Artqa</a>
    </form>
</div>
</body>
</html>
