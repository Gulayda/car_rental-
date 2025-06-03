<?php
session_start();
include('../includes/db.php');

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rental_id = $_POST['rental_id'];
    $payment_method = $_POST['payment_method'];

    $stmt = $conn->prepare("SELECT total_price FROM rentals WHERE id = ?");
    $stmt->bind_param("i", $rental_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $rental = $result->fetch_assoc();

    if ($rental) {
        $payment_amount = $rental['total_price'];
        $status = ($payment_method === 'cash' || $payment_method === 'credit_card') ? 'completed' : 'pending';

        $stmt = $conn->prepare("INSERT INTO payments (rental_id, payment_amount, payment_method, payment_status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $rental_id, $payment_amount, $payment_method, $status);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success text-center mb-4'>✅ Tólem tabıslı ámelge asırıldı!</div>";

            if ($status === 'completed') {
                $update = $conn->prepare("UPDATE rentals SET status = 'completed' WHERE id = ?");
                $update->bind_param("i", $rental_id);
                $update->execute();
            }
        } else {
            $message = "<div class='alert alert-danger text-center mb-4'>❌ Xatolik: " . $stmt->error . "</div>";
        }
    } else {
        $message = "<div class='alert alert-warning text-center mb-4'>⚠️ Ijara topilmadi.</div>";
    }
}

$rentals_result = $conn->query("
    SELECT rentals.id AS rental_id, cars.id AS car_id, cars.brand, cars.model, cars.image, rentals.total_price
    FROM rentals 
    JOIN cars ON rentals.car_id = cars.id
    WHERE rentals.status = 'active'
");
$rentals = [];
while ($row = $rentals_result->fetch_assoc()) {
    $rentals[] = $row;
}
?>

<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>To'lov sahifasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            min-height: 100vh;
            padding: 30px;
            font-family: 'Segoe UI', sans-serif;
        }
        .payment-box {
            background: #fff;
            max-width: 600px;
            margin: auto;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
        }
        .car-preview img {
            width: 100%;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        .info-box {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 12px;
            font-size: 15px;
        }
        .btn-primary {
            background-color: #0093E9;
            border: none;
        }
        .btn-secondary {
            background-color: #6c757d;
        }
    </style>
    <script>
        const rentalData = <?= json_encode($rentals) ?>;
        function updateInfo() {
            const rentalId = document.getElementById('rental_id').value;
            const infoBox = document.getElementById('rental_info');
            const selected = rentalData.find(r => r.rental_id === rentalId);

            if (selected) {
                infoBox.innerHTML = `
                    <div class="car-preview">
                        <img src="../uploads/${selected.image}" alt="Mashina rasmi">
                    </div>
                    <div class="info-box">
                        <strong>Mashina:</strong> ${selected.brand} ${selected.model}<br>
                        <strong>To'lov miqdori:</strong> <span class="text-success fw-bold">${selected.total_price} UZS</span>
                    </div>
                `;
            } else {
                infoBox.innerHTML = '<div class="text-danger">Ijara topilmadi.</div>';
            }
        }
    </script>
</head>
<body>

<div class="payment-box">
    <?= $message ?>
    <h4 class="text-center mb-4 text-primary"><i class="fas fa-credit-card"></i> Tólem beti</h4>

    <form method="POST" action="payment.php">
        <div class="mb-3">
            <label for="rental_id" class="form-label">Ijara tańlań</label>
            <select class="form-select" id="rental_id" name="rental_id" onchange="updateInfo()" required>
                <option value="">-- Ijara tanlang --</option>
                <?php foreach ($rentals as $rental): ?>
                    <option value="<?= $rental['rental_id'] ?>">
                        <?= $rental['brand'] . ' ' . $rental['model'] ?> (ID: <?= $rental['rental_id'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="rental_info" class="mb-3"></div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Tólem usılı</label>
            <select class="form-select" id="payment_method" name="payment_method" required>
                <option value="credit_card">💳 Kredit karta</option>
                <option value="cash">💵 Naq pul</option>
                <option value="paypal">💲 PayPal</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            <i class="fas fa-check-circle me-1"></i>Tólemdi ámelge asırıw
        </button>
    </form>

    <a href="cars.php" class="btn btn-secondary mt-3 w-100">
        <i class="fas fa-arrow-left me-1"></i> Artqa
    </a>
</div>

</body>
</html>
