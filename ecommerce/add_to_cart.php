<?php
// Start session to access user ID
session_start();

// Database connection details

// Create connection
$connection = mysqli_connect("localhost", "root", "", "sse");

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the user is logged in (user ID is set in session)
if (isset($_SESSION['id'])) {
    // Check if the product ID is provided in the request
    if (isset($_POST['product_id'])) {
        // Sanitize the product ID
        $product_id = mysqli_real_escape_string($connection, $_POST['product_id']);
        
        // Get the user ID from the session
        $user_id = $_SESSION['id'];
        
        // Insert the product into the cart table
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES ('$user_id', '$product_id', 1)";
        
        if (mysqli_query($connection, $query)) {
            echo "Product added to cart successfully.";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($connection);
        }
    } else {
        echo "Product ID is missing in the request.";
    }
} else {
    echo "User is not logged in.";
}

// Close connection
mysqli_close($connection);
?>
