<?php
session_start();
$uname = $_POST['uname'];  // Get username from form
$pw = $_POST['pw'];        // Get password from form

include("../database/db.php"); // Establish connection with database

$sql = "SELECT * FROM users"; // Query all users
$result = $conn->query($sql); // Execute query

$found = false; // Flag to check if credentials match

if ($result->num_rows > 0) { // If users exist
    while ($row = $result->fetch_assoc()) { // Loop through users
        $uname_db = $row["username"];   
        $pw_db = $row["password"];

        if ($uname == $uname_db && $pw == $pw_db) { // If credentials match
            $_SESSION['user'] = $uname;  // Store username in session
            header("location: ../dashboard.php");
            exit();
        }
    }
}

header("location: ../index.php?error=1");
exit();
?>