<?php
require '../database/db.php'; // Ensure this sets $conn

$fid = $_POST['fid'];

// Use a prepared statement properly
$stmt = $conn->prepare("UPDATE menu SET availability = 'unavailable' WHERE food_id = ?");
$stmt->bind_param("i", $fid); // "i" stands for integer
$stmt->execute();
$stmt->close();

header("Location: ../menuitem.php?food_id=" . $fid);
exit;
?>
