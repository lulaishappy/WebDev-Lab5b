<?php
session_start();

// Check if form is submitted
if (isset($_POST['register'])) {
    // Get data from form
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Database connection
        $conn = new mysqli("localhost", "root", "", "lab_5b");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hash password for better security (optional)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL to insert data into users table
        $stmt = $conn->prepare("INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $matric, $name, $hashed_password, $role);

        // Execute and check if the insertion was successful
        if ($stmt->execute()) {
            echo "Registration successful!";
            // Redirect to login page after successful registration
            header("Location: login.php");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
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
            margin-bottom: 15px;
        }
        label {
            font-size: 16px;
            color: #d5006d;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        button {
            background-color: #d5006d;
            color: #fff;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #c40056;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
        }
        a {
            text-decoration: none;
            color: #d5006d;
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <form method="POST" action="registration.php">
            <div class="form-group">
                <label for="matric">Matric Number:</label>
                <input type="text" name="matric" required>
            </div>
            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" required>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <?php
            if (isset($error)) {
                echo "<p class='error'>$error</p>";
            }
            ?>

            <button type="submit" name="register">Register</button>
        </form>
        <a href="login.php">Already have an account? Login here</a>
    </div>
</body>
</html>
