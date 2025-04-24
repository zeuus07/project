<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "sse");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product ID and quantity from the POST data
    $productId = $_POST["productId"];
    $quantity = $_POST["quantity"];

    // Check if the quantity is zero
    if ($quantity == 0) {
        // Prepare and execute the SQL query to delete the item from the database
        $sql = "DELETE FROM cart WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
    } else {
        // Prepare and execute the SQL query to update the quantity in the database
        $sql = "UPDATE cart SET quantity = ? WHERE product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $quantity, $productId);
        $stmt->execute();
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
}
?>
