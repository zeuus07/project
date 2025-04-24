<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "sse";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get total price from the session
$total_price = $_SESSION['totalPrice'];
$user_id = $_SESSION['id'];

// Get payment response from Razorpay
$response = file_get_contents('php://input');
$payment_response = json_decode($response);

// Check if payment is successful
if(isset($payment_response->razorpay_payment_id) && !empty($payment_response->razorpay_payment_id)) {
    // Payment successful
    $payment_id = $payment_response->razorpay_payment_id;
    $status = 'success';

   // Insert order details into the database
$order_sql = "INSERT INTO orders (user_id, payment_id, total_price, product_details) VALUES (?, ?, ?, ?)";
$order_stmt = $conn->prepare($order_sql);

// Get product details for the user
$product_details = array();
$cart_sql = "SELECT product_id, quantity FROM cart WHERE user_id = ?";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

while ($row = $cart_result->fetch_assoc()) {
    $product_details[] = $row['product_id'] . ':' . $row['quantity']; // Concatenate product_id and quantity
}

// Convert product details array to comma-separated string
$product_details_string = implode(',', $product_details);

// Bind parameters and execute the query
$order_stmt->bind_param("isds", $user_id, $payment_id, $total_price, $product_details_string);
$order_stmt->execute();
$order_id = $order_stmt->insert_id; // Get the inserted order ID
    // Fetch cart details from the database based on user ID
    $cart_sql = "SELECT * FROM cart WHERE user_id = ?";
    $cart_stmt = $conn->prepare($cart_sql);
    $cart_stmt->bind_param("i", $user_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

   

    // Delete cart items based on user ID
    $delete_cart_sql = "DELETE FROM cart WHERE user_id = ?";
    $delete_cart_stmt = $conn->prepare($delete_cart_sql);
    $delete_cart_stmt->bind_param("i", $user_id);
    $delete_cart_stmt->execute();

    // Redirect to booked.php
    header("Location: booked.php");
    exit();
} else {
    // Payment failed
    // Handle payment failure scenario here
    header("Location: ../pages/payment_failed.php");
    exit();
}
?>
    