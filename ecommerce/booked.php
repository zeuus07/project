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

// Fetch order details from the database
$user_id = $_SESSION['id'];
$order_sql = "SELECT * FROM orders WHERE user_id = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $user_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();

if ($order_result->num_rows > 0) {
    $order_data = $order_result->fetch_assoc();
    $order_id = $order_data['id'];
    $total_price = $order_data['total_price'];
    $product_details = $order_data['product_details'];
    // Split product details into individual product IDs and quantities
    $products = explode(',', $product_details);

    // Fetch product names
    $product_names = [];
    foreach ($products as $product) {
        list($product_id, $quantity) = explode(':', $product);
        $product_sql = "SELECT name FROM products WHERE id = ?";
        $product_stmt = $conn->prepare($product_sql);
        $product_stmt->bind_param("i", $product_id);
        $product_stmt->execute();
        $product_result = $product_stmt->get_result();
        if ($product_result->num_rows > 0) {
            $product_data = $product_result->fetch_assoc();
            $product_name = $product_data['name'];
            $product_names[$product_id] = $product_name;
        }
    }
} else {
    echo "No orders found.";
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <!-- Add your CSS styles here -->
    <style>
        /* Example CSS styles for receipt */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center; /* Center align the content */
        }
        .receipt h2 {
            margin-top: 0;
        }
        .receipt-details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .back-btn-container {
            text-align: center;
        }
        .back-btn {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 8px;
            transition: background-color 0.3s;
        }
        .back-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h2>Payment Receipt</h2>
        <div class="receipt-details">
            <p>Order ID: <?php echo $order_id; ?></p>
            <p>Total Price: ₹<?php echo $total_price; ?></p>
        </div>
        <h3>Products Purchased:</h3>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Product ID</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <?php list($product_id, $quantity) = explode(':', $product); ?>
                    <tr>
                        <td><?php echo isset($product_names[$product_id]) ? $product_names[$product_id] : 'Unknown'; ?></td>
                        <td><?php echo $product_id; ?></td>
                        <td><?php echo $quantity; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="back-btn-container">
            <button class="back-btn" onclick="redirectToIndex()">Back to Index</button>
        </div>
    </div>

    <script>
        function redirectToIndex() {
            window.location.href = "index.php";
        }
    </script>
</body>
</html>
