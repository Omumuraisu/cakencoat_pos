<?php
include("../database/db.php");

// Get POST data safely
$fid = intval($_POST['fid'] ?? 0);
$name = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$ingredients = $_POST['ingredients'] ?? [];

// Basic validation
if ($fid <= 0 || $name === '' || $price <= 0) {
    die("Invalid input.");
}

// Escape strings for SQL
$name_esc = $conn->real_escape_string($name);
$category_esc = $conn->real_escape_string($category);

// Update menu table
$updateMenuSql = "UPDATE menu SET food_name = '$name_esc', price = $price, category = '$category_esc' WHERE food_id = $fid";
if (!$conn->query($updateMenuSql)) {
    die("Failed to update menu item: " . $conn->error);
}

// Clear existing ingredients for this menu item
$deleteInvMenuSql = "DELETE FROM inv_menu WHERE fid = $fid";
if (!$conn->query($deleteInvMenuSql)) {
    die("Failed to clear old ingredients: " . $conn->error);
}

// Insert new ingredients if any
if (count($ingredients) > 0) {
    $values = [];
    foreach ($ingredients as $inv_id) {
        $inv_id_int = intval($inv_id);
        // Only add if inv_id is valid
        if ($inv_id_int > 0) {
            $values[] = "($fid, $inv_id_int)";
        }
    }
    if (count($values) > 0) {
        $insertSql = "INSERT INTO inv_menu (fid, inv_id) VALUES " . implode(',', $values);
        if (!$conn->query($insertSql)) {
            die("Failed to insert new ingredients: " . $conn->error);
        }
    }
}

// Redirect back to edit page or menu list
header("Location: ../menuitem.php?food_id=$fid&saved=1");
exit();
?>
