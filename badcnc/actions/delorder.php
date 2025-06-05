<?php
session_start();
include("../database/db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["order_id"])) {
        $orderId = intval($_POST["order_id"]);

        // First, delete items associated with the order
        $deleteItemsQuery = "DELETE FROM orderitems WHERE oid = ?";
        $stmtItems = $conn->prepare($deleteItemsQuery);
        $stmtItems->bind_param("i", $orderId);
        $stmtItems->execute();
        $stmtItems->close();

        // Then, delete the order itself
        $deleteOrderQuery = "DELETE FROM orders WHERE oid = ?";
        $stmtOrder = $conn->prepare($deleteOrderQuery);
        $stmtOrder->bind_param("i", $orderId);
        $stmtOrder->execute();
        $stmtOrder->close();

        // Redirect back to order list or dashboard
        header("Location: ../orderHistory.php?deleted=success");
        exit();
    } else {
        // Missing order ID
        header("Location: ../orderHistory.php?error=missing_id");
        exit();
    }
} else {
    // Not a POST request
    header("Location: ../orderHistory.php?error=invalid_method");
    exit();
}
?>