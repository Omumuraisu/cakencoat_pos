<?php
// update_inventory.php

// Connect to DB (adjust credentials)
include("../database/db.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantities = $_POST['quantity'] ?? [];
    $units = $_POST['unit'] ?? [];

    // Loop through submitted products
    foreach ($quantities as $product => $qty) {
        $qty = floatval($qty);
        $unit = $units[$product] ?? '';

        if ($qty < 0 || empty($unit)) {
            // skip invalid entries
            continue;
        }

        // Find category_id and item id for this product
        $stmt = $conn->prepare("SELECT id, category_id FROM inventory_item WHERE name = ?");
        $stmt->bind_param("s", $product);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $item_id = $row['id'];

            // Update quantity and unit
            $update = $conn->prepare("UPDATE inventory_item SET quantity = ?, unit = ? WHERE id = ?");
            $update->bind_param("dsi", $qty, $unit, $item_id);
            $update->execute();
            $update->close();
        }
        $stmt->close();
    }
    // Redirect back or show success message
    header("Location: ../inventory.php"); // or wherever your inventory page is
    exit;
}

$conn->close();
?>