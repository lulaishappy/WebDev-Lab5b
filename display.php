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

// Query to get all users
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8e1f4;
            margin: 0;
            padding: 0;
        }
        .container {
            margin: 50px auto;
            width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #d5006d;
        }
        th, td {
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #d5006d;
            color: white;
        }
        .action-links a {
            text-decoration: none;
            color: #e91e63;
            margin: 0 10px;
            padding: 5px 10px;
            border: 1px solid #e91e63;
            border-radius: 5px;
        }
        .action-links a:hover {
            background-color: #e91e63;
            color: white;
        }
        .message {
            text-align: center;
            color: #28a745;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .error-message {
            text-align: center;
            color: #dc3545;
            font-size: 18px;
            margin-bottom: 20px;
        }
        .logout-link {
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
        <h2>User Management</h2>

        <!-- Display success or error message -->
        <?php if (isset($_SESSION['delete_success'])): ?>
            <div class="message">
                <?php echo $_SESSION['delete_success']; ?>
                <?php unset($_SESSION['delete_success']); ?>
            </div>
        <?php elseif (isset($_SESSION['delete_error'])): ?>
            <div class="error-message">
                <?php echo $_SESSION['delete_error']; ?>
                <?php unset($_SESSION['delete_error']); ?>
            </div>
        <?php endif; ?>

        <!-- Display all users -->
        <table>
            <tr>
                <th>Matric Number</th>
                <th>Name</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>

            <?php
            // Reconnect to the database to fetch user data
            $conn = new mysqli("localhost", "root", "", "lab_5b");

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to get all users
            $sql = "SELECT * FROM users";
            $result = $conn->query($sql);

            while ($user = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($user['matric']) . "</td>";
                echo "<td>" . htmlspecialchars($user['name']) . "</td>";
                echo "<td>" . htmlspecialchars($user['role']) . "</td>";
                echo "<td class='action-links'>";
                echo "<a href='update.php?matric=" . $user['matric'] . "'>Update</a>";
                echo "<a href='delete.php?matric=" . $user['matric'] . "'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }

            $conn->close();
            ?>
        </table>

        <!-- Logout Link -->
        <div class="logout-link">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</body>
</html>
