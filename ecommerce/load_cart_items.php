<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "sse");

// Check if the user is logged in
session_start();
if (!isset($_SESSION["id"])) {
    exit("User not logged in");
}

// Fetch the products in the shopping cart for the user
$user_id = $_SESSION["id"];
$sql = "SELECT p.id, p.name, p.image, c.quantity, p.price 
        FROM products p 
        JOIN cart c ON p.id = c.product_id 
        JOIN sse_users u ON u.id = c.user_id
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Start building the HTML representation of the cart items
$html = '';

// Initialize total price
$total_price = 0;

// Loop through the cart items and generate HTML for each item
while ($row = $result->fetch_assoc()) {
    $html .= '<div class="item">';
    $html .= '<div class="image"><img src="' . $row['image'] . '" alt="Product Image"></div>';
    $html .= '<div class="description">';
    $html .= '<span>' . $row['name'] . '</span>';
    $html .= '<span>Price: ₹' . $row['price'] . '</span>';
    $html .= '</div>';
    $html .= '<div class="quantity">';
    $html .= '<button class="minus-btn" type="button" onclick="decrementQuantity(' . $row['id'] . ', this.nextElementSibling)">-</button>';
    $html .= '<input type="text" name="quantity" value="' . $row['quantity'] . '">';
    $html .= '<button class="plus-btn" type="button" onclick="incrementQuantity(' . $row['id'] . ', this.previousElementSibling)">+</button>';
    $html .= '</div>';
    $html .= '<div class="total-price">Total: ₹' . ($row['price'] * $row['quantity']) . '</div>';
    $html .= '</div>';

    // Update total price
    $total_price += ($row['price'] * $row['quantity']);
}

// Add the total price to the HTML
$html .= '<div class="total-price">Total Price: ₹' . $total_price . '</div>';


// Output the HTML
echo $html;
?>
<script>
    function redirectToCheckout(totalPrice) {
        // Store the total price in the session
        sessionStorage.setItem('totalPrice', totalPrice);
        
        // Redirect to checkout.php
        window.location.href = 'checkout.php';
    }
</script>
