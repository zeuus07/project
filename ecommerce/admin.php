<?php
// Start the session
session_start();

// Check if the "admin_id" session variable is not set or empty
if (!isset($_SESSION["admin"]) || empty($_SESSION["admin"])) {
    // Redirect the user to the login page
    header("Location: admin_login.php");
    exit(); // Terminate script execution after redirection
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .dashboard {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 600px;
            width: 80%;
        }

        .dashboard h1 {
            margin-bottom: 40px;
            font-size: 36px;
        }

        .dashboard a {
            display: block;
            margin-bottom: 20px;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s;
            font-size: 24px;
        }

        .dashboard a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard">
            <h1>Admin Dashboard</h1>
            <a href="admin_prod.php">Manage Products</a>
            <a href="admin_orders.php">View Orders</a>
            <a href="admin_contact.php">Contact Us Details</a>
        </div>
    </div>
</body>
</html>
