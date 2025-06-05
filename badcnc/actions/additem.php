<?php
include("../database/db.php");

// Get POST data safely
$name = trim($_POST['name'] ?? '');
$category = trim($_POST['category'] ?? '');
$price = floatval($_POST['price'] ?? 0);
$ingredients = $_POST['ingredients'] ?? [];

// Basic validation
if ($name === '' || $category === '' || $price <= 0) {
    die("Invalid input.");
}

// Escape strings for SQL
$name_esc = $conn->real_escape_string($name);
$category_esc = $conn->real_escape_string($category);

// Insert into menu table
$insertMenuSql = "INSERT INTO menu (food_name, price, availability, category)
                  VALUES ('$name_esc', $price, 'available', '$category_esc')";

if (!$conn->query($insertMenuSql)) {
    die("Failed to create new menu item: " . $conn->error);
}

// Get the new menu item's ID
$fid = $conn->insert_id;

// Insert new ingredients if any
if (count($ingredients) > 0) {
    $values = [];
    foreach ($ingredients as $inv_id) {
        $inv_id_int = intval($inv_id);
        if ($inv_id_int > 0) {
            $values[] = "($fid, $inv_id_int)";
        }
    }
    if (count($values) > 0) {
        $insertSql = "INSERT INTO inv_menu (fid, inv_id) VALUES " . implode(',', $values);
        if (!$conn->query($insertSql)) {
            die("Failed to insert ingredients: " . $conn->error);
        }
    }
}

// Redirect to item page
header("Location: ../menu.php");
exit();
?>
