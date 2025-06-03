<?php
include('../includes/db.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "To'lov ID topilmadi."; exit;
}

$query = "DELETE FROM payments WHERE id = $id";
if ($conn->query($query)) {
    header("Location: payment_management.php");
} else {
    echo "Xatolik: " . $conn->error;
}
?>
