<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" />
    <script src="jquery-3.7.1.min.js"></script>
    <script src="actions/javaq.js" defer></script>
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

    <!-- Sidebar -->
    <div id="sidebar" class="sidebar hidden">
        <button id="close-sidebar" class="close-sidebar-button">X</button>
        <ul>
            <li><a href="dashboard.php">Dashboard</a></li>
            <li><a href="orderHistory.php">Orders</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
    </div>

    <div class="menu-wrapper">
    <!-- Category Section -->
    <div class="category-list">
        <h2>Category</h2>
        <ul>
            <li><a class="selected" data-category="hot coffee">Hot Coffee</a></li>
            <li><a data-category="iced coffee">Iced Coffee</a></li>
            <li><a data-category="non coffee">Non Coffee</a></li>
            <li><a data-category="sandwich">Sandwiches</a></li>
            <li><a data-category="pastry">Pastries</a></li>
            <li><a data-category="pasta">Pasta</a></li>
            <li><a data-category="specialty">Specialties</a></li>
            <li><a data-category="grilled">Grilled</a></li>
            <li><a data-category="stewed">Stewed</a></li>
        </ul>
    </div>

    <!-- Menu Items Section -->
    <div class="item-section">
        <?php
            include("database/db.php");

            // Fetch all menu items from database
            $query = "SELECT food_id, food_name, price, availability, category FROM menu ORDER BY category, food_name";
            $result = $conn->query($query);

            $menuByCategory = [];

            while ($row = $result->fetch_assoc()) {
                $category = $row['category'];
                $menuByCategory[$category][] = $row;
            }

            // Render each category with its items
            foreach ($menuByCategory as $category => $items) {
                echo "<div class='category-box' data-category='" . htmlspecialchars($category) . "'>"; // <- this line changed
                echo "<h2 class='category-title'>" . htmlspecialchars($category) . "</h2>";
                echo "<div class='item-grid'>";

                foreach ($items as $item) {
                    $isAvailable = $item['availability'] === 'available'; // assuming values like 'available' or 'unavailable'
                    $statusIcon = $isAvailable ? "✔" : "✖";
                    $statusClass = $isAvailable ? "available" : "unavailable";
                    $cardClass = $isAvailable ? "item-card" : "item-card disabled";

                    echo "<div class='{$cardClass}' data-food-id='{$item['food_id']}'>";
                    echo "<img src='Images/coffee.png' alt='" . htmlspecialchars($item['food_name']) . "'>";
                    echo "<p>" . htmlspecialchars($item['food_name']) . "</p>";
                    echo "<div class='status-icons'><span class='status-icon {$statusClass}'>{$statusIcon}</span></div>";
                    echo "</div>";
                }

                echo "</div></div>";
            }
        ?>
        <div class="add-item-container">
            <a href="addmenu.php" class="add-item-button">Add Item</a>
        </div>

    </div>
<script>
  // Use class selector and event delegation for dynamic content
  $(document).on('click', '.item-card', function () {
    const foodId = $(this).data('food-id'); // gets the food_id from data attribute
    window.location.href = `menuitem.php?food_id=${foodId}`;
  });

  
</script>

</body>
</html>