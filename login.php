<?php
// Start session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "lab_5b");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form submission
$error_message = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Check if both fields are filled
    if (empty($matric) || empty($password)) {
        $error_message = "Both matric number and password are required!";
    } else {
        // Prepared statement to check if user exists
        $sql = "SELECT * FROM users WHERE matric = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $matric); // Bind matric number
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Successful login
                $_SESSION['matric'] = $matric;
                header("Location: welcome.php"); // Redirect to welcome page
                exit;
            } else {
                // Invalid password
                $error_message = "Invalid matric number or password. Please try again or <a href='registration.php'>register here</a> if you don't have an account.";
            }
        } else {
            // Invalid matric number
            $error_message = "Invalid matric number or password. Please try again or <a href='registration.php'>register here</a> if you don't have an account.";
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
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
        .form-row {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #d5006d;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #f0a6ca;
            border-radius: 5px;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #d5006d;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #e60073;
        }
        .error-message {
            color: #ff4081;
            text-align: center;
            margin-top: 10px;
        }
        .registration-link {
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
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="form-row">
                <label for="matric">Matric Number:</label>
                <input type="text" name="matric" id="matric" required>
            </div>
            <div class="form-row">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
            </div>
            <input type="submit" value="Login">
        </form>
        <?php
        if (!empty($error_message)) {
            echo "<div class='error-message'>$error_message</div>";
        }
        ?>
        <div class="registration-link">
            Don't have an account? <a href="registration.php">Register here</a>.
        </div>
    </div>
</body>
</html>
