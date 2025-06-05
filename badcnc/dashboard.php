<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" />
    <script src="jquery-3.7.1.min.js"></script>
    <script src="actions/javaq.js"></script>
</head>
    
<body>
    <div class="header">
        <div class="menu-icon-button">
            <button id="menu-toggle">&#9776;</button> <!-- Menu icon -->
        </div>
        <img src="assets/images/cnclogo.png" alt="Logo" class="logo">
        <div class="brand-name">Cake n' Coat</div>

        <?php
             session_start();
             if (!isset($_SESSION['user'])) {
                 header("location: login.php"); // Redirect to login if not logged in
                 exit();
             }

        ?>

        <div class="username">
            <?php echo htmlspecialchars($_SESSION['user']); ?>
            <div><button id="signOut">Sign out</button></div>
        </div>
    </div>
    
    <!-- Sidebar or menu -->
    <div id="sidebar" class="sidebar hidden">
        <button id="close-sidebar" class="close-sidebar-button">X</button> <!-- Close button -->
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="orderHistory.php">Orders</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>


    <div class="main">
        <div class="button-container">
            <button class="button pos-button">POS</button>
            <button class="button inventory-button">Inventory</button>
            <button class="button menu-button">Menu</button>
        </div>
        <div class="divider"></div>
        <div class="latest-order">
            <h1>Orders</h1>
            <div class="status-icons">
                    <span class="status-indicator pending"></span> Waiting
                    <span class="status-indicator paid"></span> Served
                </div>
            <div class="order-status">
                <button class="order-history">
                    Order History <span class="history-icon">⏱</span>
                </button>
            </div>
            <div class="orders-grid">

            <?php
                include("database/db.php");
                $orderQuery = "SELECT * FROM orders WHERE status != 'COMPLETE'";
                $orderResult = $conn->query($orderQuery);

                    if ($orderResult->num_rows > 0) {
                        while ($orderRow = $orderResult->fetch_assoc()) {
                            $oid = $orderRow['oid'];
                                $status = strtolower($orderRow['status']); // For optional class styling
                                $customer = htmlspecialchars($orderRow['customerName']);
                                $total = number_format($orderRow['totalPrice'], 2);
            ?>
                                    <div class="order-card <?php echo $status === 'tbp' ? 'paid' : ''; ?>" data-oid="<?php echo $oid; ?>">
                                        <p>#<?php echo str_pad($oid, 4, "0", STR_PAD_LEFT); ?></p>
                                        <p><?php echo $customer; ?></p>
                                        <p>Total: <?php echo $total; ?> Php</p>
                                    </div>
            <?php
                        }
                    } else {
                        echo "<p>No pending orders found.</p>";
                    }
            ?>
        </div>
    </div>
</body>
</html>
