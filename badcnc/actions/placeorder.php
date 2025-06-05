<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orders']) && isset($_POST['customer_name'])) {
    include("../database/db.php");

    $customerName = trim($_POST['customer_name']);
    $orders = $_POST['orders'];
    $totalPrice = 0;
    $orderItems = [];

    foreach ($orders as $itemName => $details) {
        $itemNameClean = str_replace('_', ' ', $itemName);
        $quantity = intval($details['quantity']);
        $price = floatval($details['price']);
        $itemTotal = $quantity * $price;

        if ($quantity > 0) {
            $totalPrice += $itemTotal;

            $orderItems[] = [
                'itemName' => $itemNameClean,
                'itemNo' => $quantity,
                'price' => $price
            ];
        }
    }

    if (count($orderItems) === 0) {
        echo "No valid order items.";
        exit();
    }

    $status = 'pending';
    $date = date('Y-m-d H:i:s');

    // 1. Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (customerName, totalPrice, status, date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sdss", $customerName, $totalPrice, $status, $date);
    $stmt->execute();
    $oid = $stmt->insert_id;
    $stmt->close();

    // 2. Insert into orderitems
    $stmt = $conn->prepare("INSERT INTO orderitems (oid, itemName, itemNo, price) VALUES (?, ?, ?, ?)");

    foreach ($orderItems as $item) {
        $stmt->bind_param("isid", $oid, $item['itemName'], $item['itemNo'], $item['price']);
        $stmt->execute();
    }

    $stmt->close();
    $conn->close();

    header("Location: ../order.php?oid=" . $oid);
    exit();
} else {
    echo "Invalid order submission.";
}
?>