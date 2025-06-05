<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" />
    <script src="jquery-3.7.1.min.js"></script>
    <script src="actions/javaq.js"></script>
</head>

<body>
    <div class="header">
        <div class="menu-icon-button">
            <button id="menu-toggle">&#9776;</button>
        </div>
        <img src="assets/images/cnclogo.png" alt="Logo" class="logo">
        <div class="brand-name">Cake n' Coat</div>
        <?php
            session_start();
            if (!isset($_SESSION['user'])) {
                header("location: actions/login.php");
                exit();
            }
        ?>
        <div class="username">
            <?php echo htmlspecialchars($_SESSION['user']); ?>
            <div><button id="signOut">Sign out</button></div>
        </div>
    </div>
    
    <div id="sidebar" class="sidebar hidden">
        <button id="close-sidebar" class="close-sidebar-button">X</button>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="orderHistory.php">Orders</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>

    <div class="order-details-container">
        <div class="order-details">
            <?php
                include("database/db.php");
                if (isset($_GET['oid'])) {
                    $oid = intval($_GET['oid']);
                    $orderQuery = "SELECT * FROM orders WHERE oid = $oid";
                    $orderResult = $conn->query($orderQuery);

                    if ($orderResult->num_rows > 0) {
                        while ($orderRow = $orderResult->fetch_assoc()) {
                            $status = strtoupper($orderRow['status']);
                            $customer = htmlspecialchars($orderRow['customerName']);
                            $total = number_format($orderRow['totalPrice'], 2);
            ?>
                <h2 class="order-id">#<?php echo str_pad($oid, 4, "0", STR_PAD_LEFT); ?></h2>
                <h3 class="customer-name"><?php echo $customer; ?></h3>
                <span class="order-status <?php echo strtolower($status); ?>"><?php echo $status; ?></span>
                <hr>
                <div class="order-items">
                    <?php
                        $itemQuery = "SELECT * FROM orderitems WHERE oid = $oid";
                        $itemResult = $conn->query($itemQuery);
                        if ($itemResult->num_rows > 0) {
                            while ($itemRow = $itemResult->fetch_assoc()) {
                                $itemName = htmlspecialchars($itemRow['itemName']);
                                $itemNo = intval($itemRow['itemNo']);
                                $price = number_format($itemRow['price'], 2) * $itemNo;
                                echo "<p>$itemName" . ($itemNo > 1 ? " x$itemNo" : "") . " <span class='item-price'>PHP $price</span></p>";
                            }
                        } else {
                            echo "<p>No items found for this order.</p>";
                        }
                    ?>
                </div>
                <hr>
                <div class="order-total">
                    <strong>Total:</strong> <span class="total-price">PHP <?php echo $total; ?></span>
                </div>
                </div>

                        <div class="order-actions-container">
                        <form method="POST" action="actions/delorder.php">
                            <input type="hidden" name="order_id" value="<?php echo $orderRow['oid']; ?>">
                            <input type="hidden" name="action" value="remove">
                            <button type="submit" class="remove-order-btn">REMOVE</button>
                        </form>

                        <?php if ($orderRow['status'] === 'pending'): ?>
                            <form method="POST" action="editorder.php">
                                <input type="hidden" name="order_id" value="<?php echo $orderRow['oid']; ?>">
                                <input type="hidden" name="action" value="edit">
                                <button type="submit" class="edit-order-btn">EDIT</button>
                            </form>
                            <form method="POST" action="actions/uporder.php">
                                <input type="hidden" name="order_id" value="<?php echo $orderRow['oid']; ?>">
                                <input type="hidden" name="new_status" value="tbp">
                                <button type="submit" class="served-order-btn">SERVE</button>
                            </form>
                        <?php elseif ($orderRow['status'] === 'tbp'): ?>
                            <form method="POST" action="editorder.php">
                                <input type="hidden" name="order_id" value="<?php echo $orderRow['oid']; ?>">
                                <input type="hidden" name="action" value="edit">
                                <button type="submit" class="edit-order-btn">EDIT</button>
                            </form>
                            <form method="POST" action="actions/uporder.php">
                                <input type="hidden" name="order_id" value="<?php echo $orderRow['oid']; ?>">
                                <input type="hidden" name="new_status" value="complete">
                                <button type="submit" class="served-order-btn">COMPLETE</button>
                            </form>
                        <?php endif; ?>
                    </div>
            <?php
                        }
                    } else {
                        echo "<h2>No orders found with that ID.</h2>";
                    }
                } else {
                    echo "<h2>No order ID specified.</h2>";
                }
            ?>
        


    </div>
</body>
</html>
