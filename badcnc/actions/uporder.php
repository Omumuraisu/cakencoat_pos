<?php
require '../database/db.php'; // Update path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = $_POST['order_id'] ?? null;
    $newStatus = $_POST['new_status'] ?? null;

    if ($orderId && $newStatus) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE oid = ?");
        $stmt->bind_param("si", $newStatus, $orderId);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ../order.php?oid=" . $orderId);
    exit;
}