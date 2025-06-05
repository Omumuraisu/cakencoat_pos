<?php
include("database/db.php");

$allIngredientsQuery = "SELECT id, name FROM inventory_item ORDER BY name";
$allIngredientsResults = $conn->query($allIngredientsQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Add Menu Item</title>
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
    <h1 class="edit-menu-item-header"> ADD NEW ITEM</h1>

<form method="POST" action="actions/additem.php">

        <!-- Left Section: Form and Image Upload -->
        <div class="edit-left-section">
            <!-- Form Section -->
            <div class="edit-box">
                <div class="edit-menu-item-form">
                    <label for="name"><strong>Item Name</strong></label>
                    <input type="text" name ="name" id="edit-item-name">
                    <label for="name"><strong>Item Price</strong></label>
                    <input type="number" name ="price" id="edit-item-price">
                    
                    <select name="category">
                        <option value="hot coffee">Hot Coffee</option>
                        <option value="iced coffee">Iced Coffee</option>
                        <option value="non coffee">Non Coffee</option>
                        <option value="sandwich">Sandwiches</option>
                        <option value="pastry">Pastries</option>
                        <option value="pasta">Pasta</option>
                        <option value="specialty">Specialties</option>
                        <option value="grilled">Grilled</option>
                        <option value="stewed">Stewed</option>
                    </select>
                </div>
            </div>

            <!-- Right Section: Ingredients -->
        <div class="edit-box edit-ingredients-section">


    <ul class="ingredient-list" id="ingredient-list">

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
