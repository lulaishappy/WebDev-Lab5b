<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['matric'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

// Get matric from URL
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];
    
    // Database connection
    $conn = new mysqli("localhost", "root", "", "lab_5b");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query to get user details based on matric
    $sql = "SELECT * FROM users WHERE matric = '$matric'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "User not found!";
        exit;
    }

    // Update logic (if form is submitted)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_matric = $_POST['matric'];
        $name = $_POST['name'];
        $role = $_POST['role'];

        // Update user details (without updating the password)
        $update_sql = "UPDATE users SET matric = '$new_matric', name = '$name', role = '$role' WHERE matric = '$matric'";
        
        if ($conn->query($update_sql) === TRUE) {
            echo "User updated successfully.";
            $_SESSION['matric'] = $new_matric; // Update session matric number if changed
            header("Location: display.php"); // Redirect to display page after update
        } else {
            echo "Error updating user: " . $conn->error;
        }
    }

    // Close connection
    $conn->close();
} else {
    echo "Invalid request.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update User</title>
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
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: #d5006d;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .submit-btn {
            background-color: #d5006d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background-color: #e91e63;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update User</h2>

        <form method="POST" action="update.php?matric=<?php echo $matric; ?>">
            <div class="form-group">
                <label for="matric">Matric Number:</label>
                <input type="text" name="matric" id="matric" value="<?php echo htmlspecialchars($user['matric']); ?>" required>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <input type="text" name="role" id="role" value="<?php echo htmlspecialchars($user['role']); ?>" required>
            </div>
            <button type="submit" class="submit-btn">Update</button>
        </form>
    </div>
</body>
</html>
