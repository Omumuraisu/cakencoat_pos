<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['orders']) && isset($_POST['oid'])) {
    include("../database/db.php");

    $oid = intval($_POST['oid']);
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

    // 1. Update totalPrice in orders table (add to existing total)
    $stmt = $conn->prepare("UPDATE orders SET totalPrice = totalPrice + ? WHERE oid = ?");
    $stmt->bind_param("di", $totalPrice, $oid);
    $stmt->execute();
    $stmt->close();

    // 2. Insert new items into orderitems table
    $stmt = $conn->prepare("INSERT INTO orderitems (oid, itemName, itemNo, price) VALUES (?, ?, ?, ?)");
    foreach ($orderItems as $item) {
        $stmt->bind_param("isid", $oid, $item['itemName'], $item['itemNo'], $item['price']);
        $stmt->execute();
    }
    $stmt->close();

    $conn->close();

    // Redirect to view the updated order
    header("Location: ../order.php?oid=" . $oid);
    exit();
} else {
    echo "Invalid order submission.";
}
?>