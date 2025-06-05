<?php
include("database/db.php");

$fid = intval($_GET['fid'] ?? 0);
$name = "";
$price = 0;

// Fetch menu item data
$Query = "SELECT * FROM menu WHERE food_id = $fid";
$Result = $conn->query($Query);

if ($Result->num_rows > 0) {
    $Row = $Result->fetch_assoc();
    $name = $Row['food_name'];
    $price = $Row['price'];
    $category = $Row['category'];
} else {
    die("Menu item not found.");
}

// Handle form submission (save changes)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $conn->real_escape_string($_POST['item_name']);
    $newPrice = floatval($_POST['item_price']);
    $ingredients = $_POST['ingredients'] ?? []; // Array of ingredient IDs

    // Update menu name and price
    $updateMenu = "UPDATE menu SET food_name='$newName', price=$newPrice WHERE food_id=$fid";
    $conn->query($updateMenu);

    // Update ingredients in inv_menu:
    // Delete existing relations
    $conn->query("DELETE FROM inv_menu WHERE fid=$fid");

    // Insert new ingredient links
    if (count($ingredients) > 0) {
        $values = [];
        foreach ($ingredients as $inv_id) {
            $inv_id_int = intval($inv_id);
            $values[] = "($fid, $inv_id_int)";
        }
        $values_str = implode(',', $values);
        $conn->query("INSERT INTO inv_menu (fid, inv_id) VALUES $values_str");
    }

    // Redirect to avoid resubmission
    header("Location: menuitem.php?fid=$fid&saved=1");
    exit();
}

// Fetch current ingredients linked to this menu item
$ingredientsQuery = "SELECT ii.id, ii.name 
                     FROM inv_menu im 
                     JOIN inventory_item ii ON im.inv_id = ii.id
                     WHERE im.fid = $fid";
$ingredientResults = $conn->query($ingredientsQuery);

// Fetch all possible ingredients for dropdown
$allIngredientsQuery = "SELECT id, name FROM inventory_item ORDER BY name";
$allIngredientsResults = $conn->query($allIngredientsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edit Menu Item</title>
<style>
    .ingredient-list li { margin-bottom: 5px; }
    .ingredient-remove { cursor: pointer; color: red; margin-left: 10px; }
</style>
    <link rel="stylesheet" href="assets/css/dashboard.css" type="text/css" />
    <script src="jquery-3.7.1.min.js"></script>
    <script src="actions/javaq.js" defer></script>
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

    <div class="edit-menu-item-container">
    <h1 class="edit-menu-item-header">EDIT <?php echo $name?></h1>

<form method="POST" action="actions/upitem.php">

        <!-- Left Section: Form and Image Upload -->
        <div class="edit-left-section">
            <!-- Form Section -->
            <div class="edit-box">
                <div class="edit-menu-item-form">
                    <label for="name"><strong>Item Name</strong></label>
                    <input type="text" name ="name" value="<?php echo $name?>" id="edit-item-name">
                    <input type="hidden" name="fid" value="<?php echo intval($fid); ?>" />
                    <label for="name"><strong>Item Price</strong></label>
                    <input type="number" name ="price" value="<?php echo $price?>" id="edit-item-price">
                    
                    <select name="category">
                        <option value="hot coffee" <?= ($category == 'hot coffee') ? 'selected' : '' ?>>Hot Coffee</option>
                        <option value="iced coffee" <?= ($category == 'iced coffee') ? 'selected' : '' ?>>Iced Coffee</option>
                        <option value="non coffee" <?= ($category == 'non coffee') ? 'selected' : '' ?>>Non Coffee</option>
                        <option value="sandwich" <?= ($category == 'sandwich') ? 'selected' : '' ?>>Sandwiches</option>
                        <option value="pastry" <?= ($category == 'pastry') ? 'selected' : '' ?>>Pastries</option>
                        <option value="pasta" <?= ($category == 'pasta') ? 'selected' : '' ?>>Pasta</option>
                        <option value="specialty" <?= ($category == 'specialty') ? 'selected' : '' ?>>Specialties</option>
                        <option value="grilled" <?= ($category == 'grilled') ? 'selected' : '' ?>>Grilled</option>
                        <option value="stewed" <?= ($category == 'stewed') ? 'selected' : '' ?>>Stewed</option>
                    </select>


                    <a href="actions/deleteitem.php?food_id=<?= $fid ?>" 
                        onclick="return confirm('Are you sure you want to delete this item?');"
                        class="edit-remove-link" 
                        style="color: black; text-decoration: underline;">
                        Delete Item
                    </a>
                </div>
            </div>

            <!-- Right Section: Ingredients -->
        <div class="edit-box edit-ingredients-section">


    <ul class="ingredient-list" id="ingredient-list">
        <?php
        if ($ingredientResults->num_rows > 0) {
            while ($ing = $ingredientResults->fetch_assoc()) {
                $id = intval($ing['id']);
                $iname = htmlspecialchars($ing['name']);
                echo "<li data-id='$id'>
                        <span class='ingredient-name'>$iname</span>
                        <span class='ingredient-remove' title='Remove'>x</span>
                        <input type='hidden' name='ingredients[]' value='$id' />
                      </li>";
            }
        } else {
            echo "<li>No ingredients added yet.</li>";
        }
        ?>
    </ul>

</div>
            <div class="edit-ingredient-actions">

    <select id="ingredient-select">
        <option value="">-- Select ingredient --</option>
        <?php
        if ($allIngredientsResults->num_rows > 0) {
            while ($row = $allIngredientsResults->fetch_assoc()) {
                $id = intval($row['id']);
                $iname = htmlspecialchars($row['name']);
                echo "<option value='$id'>$iname</option>";
            }
        }
        ?>
    </select>
    <button type="button" id="add-ingredient-btn">Add Ingredient</button>

    
        <div class="edit-menu-item-buttons">
            <button type="button" class="edit-cancel-btn">CANCEL</button>
            <button class="edit-save-btn">SAVE</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {

    // Remove ingredient on clicking 'x'
    $('#ingredient-list').on('click', '.ingredient-remove', function() {
        $(this).closest('li').remove();

        // If no items left, show "No ingredients added yet."
        if ($('#ingredient-list li').length === 0) {
            $('#ingredient-list').append('<li>No ingredients added yet.</li>');
        }
    });

    // Add ingredient button
    $('#add-ingredient-btn').click(function() {
        var selectedVal = $('#ingredient-select').val();
        var selectedText = $('#ingredient-select option:selected').text();

        if (selectedVal === "") {
            alert("Please select an ingredient.");
            return;
        }

        // Prevent duplicates
        if ($('#ingredient-list li[data-id="' + selectedVal + '"]').length > 0) {
            alert("Ingredient already added.");
            return;
        }

        // Remove "No ingredients added yet." if present
        $('#ingredient-list li').each(function() {
            if ($(this).text() === "No ingredients added yet.") {
                $(this).remove();
            }
        });

        // Add new ingredient to list
        var newLi = $("<li data-id='" + selectedVal + "'>" +
            "<span class='ingredient-name'>" + selectedText + "</span>" +
            "<span class='ingredient-remove' title='Remove'>x</span>" +
            "<input type='hidden' name='ingredients[]' value='" + selectedVal + "' />" +
            "</li>");
        $('#ingredient-list').append(newLi);
    });


    $(".edit-cancel-btn").click(function() {
        window.location.href = "menu.php";
    });

});

</script>

</body>
</html>
