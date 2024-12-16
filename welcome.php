<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['matric'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get the logged-in user's matric number
$matric = $_SESSION['matric'];

// Database connection
$conn = new mysqli("localhost", "root", "", "lab_5b");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get user details
$sql = "SELECT * FROM users WHERE matric = '$matric'";
$result = $conn->query($sql);

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found!";
    exit;
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8e1f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 50px auto;
            width: 400px;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px #aaa;
            border: 2px solid #f0a6ca;
        }
        h2 {
            text-align: center;
            color: #d5006d;
        }
        .user-info {
            text-align: center;
            font-size: 18px;
            color: #d5006d;
        }
        .logout-link, .display-link {
            text-align: center;
            margin-top: 15px;
        }
        a {
            text-decoration: none;
            color: #e91e63;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h2>
        <div class="user-info">
            <p><strong>Matric Number:</strong> <?php echo htmlspecialchars($user['matric']); ?></p>
            <p><strong>Role:</strong> <?php echo htmlspecialchars($user['role']); ?></p>
        </div>

        <!-- Display link/button for admin -->
        <?php if ($user['role'] == 'admin'): ?>
            <div class="display-link">
                <a href="display.php"><button>Display Users</button></a> <!-- Link to display.php for admin -->
            </div>
        <?php endif; ?>

        <!-- Logout Link -->
        <div class="logout-link">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
