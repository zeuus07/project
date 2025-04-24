<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "sse");

// Check if the user is logged in
session_start();
if (!isset($_SESSION["id"])) {
    header("Location: login.php");
    exit();
}

// Check if productId is provided
if(isset($_POST["productId"])) {
    $productId = $_POST["productId"];

    // Delete the item from the cart
    $user_id = $_SESSION["id"];
    $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $productId);
    if ($stmt->execute()) {
        echo "Item deleted successfully";
    } else {
        echo "Error deleting item: " . $conn->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Product ID not provided";
}
?>
