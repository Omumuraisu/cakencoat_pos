<!DOCTYPE html>

<?php
    include("database/db.php");
    $fid = intval($_GET['food_id']);
    $Query = "SELECT * FROM menu WHERE food_id = $fid";
    $Result = $conn->query($Query);

    if ($Result->num_rows > 0) {
        $Row = $Result->fetch_assoc();
        $name = $Row['food_name'];
        $price = $Row['price'];
        $avail = $Row['availability'];
    }
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Item</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" />
    <script src="jquery-3.7.1.min.js"></script>
    <script src="actions/javaq.js" defer></script>
    <style>
        .red-text {
        color: red;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="menu-icon-button">
            <button id="menu-toggle">&#9776;</button> <!-- Menu icon -->
        </div>
        <img src="Images/cnclogo.png" alt="Logo" class="logo">
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
    
    <!-- Sidebar or menu -->
    <div id="sidebar" class="sidebar hidden">
        <button id="close-sidebar" class="close-sidebar-button">X</button> <!-- Close button -->
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="orderHistory.php">Orders</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>

    <!-- Menu Item Section -->
    <div class="menu-item-container">
        <h1><?php echo $name?></h1>
        <div class="menu-item-content">
            <!-- Ingredients Section -->
            <div class="ingredients-box">
            <h2>Ingredients</h2>
                <?php

                $fid = intval($fid); // sanitize fid

                $iQuery = "SELECT inv_id FROM inv_menu WHERE fid = $fid";
                $iResult = $conn->query($iQuery);

                if ($iResult->num_rows > 0) {
                    echo '<ul>';

                    // For each inv_id, get details from inventory_item
                    while ($invMenuRow = $iResult->fetch_assoc()) {
                        $inv_id = intval($invMenuRow['inv_id']);

                        // Query inventory_item for this inv_id
                        $itemQuery = "SELECT name, quantity, unit FROM inventory_item WHERE id = $inv_id";
                        $itemResult = $conn->query($itemQuery);

                        if ($itemResult && $itemResult->num_rows > 0) {
                            $itemRow = $itemResult->fetch_assoc();

                            $name = htmlspecialchars($itemRow['name']);
                            $quantity = htmlspecialchars($itemRow['quantity']);
                            $unit = htmlspecialchars($itemRow['unit']);

                            // Check for low stock (example: quantity numeric and less than 2)
                            $lowStockClass = '';
                            if (is_numeric($quantity) && $quantity < 2) {
                                $lowStockClass = 'low-stock';
                            }

                            // Check if quantity equals 1 to add a red text class
                            $redTextClass = ($quantity == 1) ? 'red-text' : '';

                            // Then output the list item with the class applied to the quantity span
                            echo "<li>
                                <span class='ingredient-name'>{$name}</span>
                                <span class='ingredient-quantity {$lowStockClass} {$redTextClass}'>{$quantity} {$unit}</span>
                            </li>";
                        }
                    }
                    echo '</ul>';
                    } else {
                    echo "<p>No ingredients found.</p>";
                }
                ?>
                <a href="editmenu.php?fid=<?php echo $fid ?>" class="edit-item-link">EDIT ITEM</a>
            </div>

            <!-- Image and Button Section -->
            <div class="image-button-container">
                <div class="image-box">
                    <img src="Images/coffee.png" alt="Coffee Image">
                    <p>Price: PHP <?php echo $price?></p>
                </div>
                
                <div class="availability-box">
                    <?php if ($avail == "unavailable"): ?>
                        <form method="POST" action="actions/itemavail.php">
                        <input type="hidden" name="fid" value="<?php echo $fid ?>">
                        <button type="submit" class="availability-button">MAKE AVAILABLE</button>
                        </form>
                    <?php else: ?>
                        <form method="POST" action="actions/itemunavail.php">
                        <input type="hidden" name="fid" value="<?php echo $fid ?>">
                        <button type="submit" class="availability-button" style="background-color: red; color: white;">MAKE UNAVAILABLE</button>
                        </form>
                    <?php endif; ?>
                </div>
    
            </div>
        </div>
    </div>


<script>
</script>
</body>
</html>