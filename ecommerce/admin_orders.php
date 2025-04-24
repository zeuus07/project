<?php
session_start();

// If admin is not logged in, redirect to login page
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: admin_login.php");
    exit;
}

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $delivery_status = $_POST['delivery_status'];

    // Update delivery status in the database
    $sql = "UPDATE orders SET delivery_status = '$delivery_status' WHERE id = $order_id";
    if ($conn->query($sql) === TRUE) {
        echo "Delivery status updated successfully.";
    } else {
        echo "Error updating delivery status: " . $conn->error;
    }
}

// Fetch all orders with address information from the database
$order_sql = "SELECT orders.id, orders.user_id, orders.total_price, orders.product_details, orders.delivery_status,
sse_address.address, sse_address.city, sse_address.postal_code, sse_address.country
FROM orders
LEFT JOIN sse_address ON orders.user_id = sse_address.user_id;";
$order_result = $conn->query($order_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - View Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
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
        .status-select {
            width: 100%;
        }
    </style>
</head>
<body>
    <h2>Admin Panel - View Orders</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Price</th>
                <th>Product Details</th>
                <th>Address</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Country</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($order_result->num_rows > 0): ?>
                <?php while ($order_data = $order_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $order_data['id']; ?></td>
                        <td><?php echo $order_data['user_id']; ?></td>
                        <td><?php echo $order_data['total_price']; ?></td>
                        <td>
                            <?php
                            // Split product details into individual product IDs and quantities
                            $product_details = $order_data['product_details'];
                            $products = explode(',', $product_details);
                            foreach ($products as $product) {
                                list($product_id, $quantity) = explode(':', $product);
                                // Fetch product name from products table
                                $product_sql = "SELECT name FROM products WHERE id = $product_id";
                                $product_result = $conn->query($product_sql);
                                if ($product_result->num_rows > 0) {
                                    $product_data = $product_result->fetch_assoc();
                                    echo $product_data['name'] . " (Qty: $quantity)<br>";
                                }
                            }
                            ?>
                        </td>
                        <td><?php echo $order_data['address']; ?></td>
                        <td><?php echo $order_data['city']; ?></td>
                        <td><?php echo $order_data['postal_code']; ?></td>
                        <td><?php echo $order_data['country']; ?></td>
                        <td>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="order_id" value="<?php echo $order_data['id']; ?>">
                                <select class="status-select" name="delivery_status">
                                    <option value="pending" <?php if ($order_data['delivery_status'] == 'pending') echo 'selected'; ?>>Pending</option>
                                    <option value="shipped" <?php if ($order_data['delivery_status'] == 'shipped') echo 'selected'; ?>>Shipped</option>
                                    <option value="delivered" <?php if ($order_data['delivery_status'] == 'delivered') echo 'selected'; ?>>Delivered</option>
                                </select>
                                <input type="submit" name="update_status" value="Update">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">No orders found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>

