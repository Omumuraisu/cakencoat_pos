<?php
include("../database/db.php");

$fid = intval($_GET['food_id'] ?? 0);
if ($fid <= 0) {
    die("Invalid ID.");
}

// Delete ingredients from inv_menu
$conn->query("DELETE FROM inv_menu WHERE fid = $fid");

// Delete item from menu
if ($conn->query("DELETE FROM menu WHERE food_id = $fid")) {
    header("Location: ../menu.php?deleted=1");
} else {
    die("Error deleting item: " . $conn->error);
}
?>