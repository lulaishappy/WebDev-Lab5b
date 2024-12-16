<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['matric'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Database connection
$conn = new mysqli("localhost", "root", "", "lab_5b");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if 'matric' is passed in the URL
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Query to delete user
    $sql = "DELETE FROM users WHERE matric = '$matric'";

    if ($conn->query($sql) === TRUE) {
        // Set success message in session
        $_SESSION['delete_success'] = "User deleted successfully.";
    } else {
        // Set error message in session
        $_SESSION['delete_error'] = "Error deleting user: " . $conn->error;
    }
}

// Close connection
$conn->close();

// Redirect to display page after deletion
header("Location: display.php");
exit;
?>
