<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Order History</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" />
    <script src="jquery-3.7.1.min.js"></script>
    <script src="actions/javaq.js"></script>
</head>
<body>
    <div class="header">
        <div class="menu-icon-button">
            <button id="menu-toggle">&#9776;</button> <!-- Menu icon -->
        </div>
        <img src="assets/images/cnclogo.png" alt="Logo" class="logo" />
        <div class="brand-name">Cake n' Coat</div>

        <?php
            session_start();
            if (!isset($_SESSION['user'])) {
                header("location: login.php");
                exit();
            }
        ?>

        <div class="username">
            <?php echo htmlspecialchars($_SESSION['user']); ?>
            <div><button id="signOut">Sign out</button></div>
        </div>
    </div>

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar hidden">
        <button id="close-sidebar" class="close-sidebar-button">X</button>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="orders.php">Orders</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main">
        <h1>Order History</h1>
        <div class="status-icons">
                <span class="status-indicator pending"></span> Waiting
                <span class="status-indicator paid"></span> Served
                <span class="status-indicator completed"></span> Payed
            </div>
        <div class="order-status">
            <form method="GET" id="filter-form">
                <select name="range" id="filter-range" onchange="document.getElementById('filter-form').submit()">
                    <option value="day" <?= ($_GET['range'] ?? 'day') === 'day' ? 'selected' : '' ?>>Day</option>
                    <option value="week" <?= ($_GET['range'] ?? 'day') === 'week' ? 'selected' : '' ?>>Week</option>
                    <option value="month" <?= ($_GET['range'] ?? 'day') === 'month' ? 'selected' : '' ?>>Month</option>
                </select>
            </form>
        </div>

        <?php
            include("database/db.php");

            $range = $_GET['range'] ?? 'day';

            $query = "SELECT * FROM orders ORDER BY date DESC";
            $result = $conn->query($query);

            $ordersGrouped = [];

            while ($row = $result->fetch_assoc()) {
                $date = strtotime($row['date']);

                if ($range === 'month') {
                    $groupKey = date("F Y", $date); // e.g., "June 2025"
                } elseif ($range === 'week') {
                    $monday = strtotime("last Monday", $date);
                    $sunday = strtotime("next Sunday", $monday);
                    $groupKey = "Week of " . date("M j", $monday) . " – " . date("M j", $sunday);
                } else {
                    $groupKey = date("m/d/Y", $date); // Default: day
                }

                $ordersGrouped[$groupKey][] = $row;
            }

            foreach ($ordersGrouped as $label => $orders) {
                echo "<div class='order-history-section'>";
                echo "<h2>" . htmlspecialchars($label) . "</h2>";
                echo "<div class='orders-grid'>";

                if (empty($orders)) {
                    echo "<p>No orders found.</p>";
                } else {
                    foreach ($orders as $order) {
                        $rawOid = $order['oid'];
                        $oid = str_pad($rawOid, 4, "0", STR_PAD_LEFT);
                        $customer = htmlspecialchars($order['customerName']);
                        $total = number_format($order['totalPrice'], 2);
                        $status = strtolower($order['status']);

                        $statusClass = '';
                        if ($status === 'paid') $statusClass = '';
                        elseif ($status === 'complete') $statusClass = 'completed';
                        elseif ($status === 'tbp') $statusClass = 'paid';

                        echo "<div class='order-card {$statusClass}' data-oid='{$rawOid}'>";
                        echo "<p>#$oid</p>";
                        echo "<p>{$customer}</p>";
                        echo "<p>Total: {$total} Php</p>";
                        echo "</div>";
                    }
                }

                echo "</div></div>";
            }
        ?>
    </div>
</body>
</html>
