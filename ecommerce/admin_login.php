<?php
session_start();

// If admin is already logged in, redirect to admin panel
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: admin.php");
    exit;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if username and password are correct (This is a basic example, use secure authentication methods in production)
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Dummy admin credentials (Replace with your actual admin credentials)
    $admin_username = "sse";
    $admin_password = "sse123";

    if ($username === $admin_username && $password === $admin_password) {
        // Set admin session variable and redirect to admin panel
        $_SESSION['admin'] = true;
        header("Location: admin.php");
        exit;
    } else {
        // Display error message if credentials are incorrect
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Add your CSS styles here -->
    <style>
        /* Example CSS styles for admin login page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 8px;
        }
        input[type="text"], input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 4px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Admin Login</h2>
    <div class="login-container">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>
