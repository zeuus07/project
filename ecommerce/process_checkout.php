<?php
session_start();
// Create connection
$conn = new mysqli("localhost", "root", "", "sse");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$total_price = $_SESSION['totalPrice']; // Get only the total price from the session
$user_id = $_SESSION["id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payment</title>

<!-- Add your CSS styling -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
    }
    .container {
        width: 400px; /* Fixed width for the container */
        margin: 100px auto; /* Center the container horizontally */
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center; /* Center the content horizontally */
    }
    .razorpay-image {
        width: 150px; /* Adjust the width of the image */
        height: auto; /* Maintain aspect ratio */
        margin-bottom: 20px; /* Add some bottom margin */
    }
    #rzp-button1 {
        display: block;
        width: 100%;
        background-color: #007bff; /* Default button color (blue) */
        color: white;
        padding: 15px 0;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }
    #rzp-button1:hover {
        background-color: #28a745; /* Button color on hover (green) */
    }
</style>
</head>
<body>

<div class="container">
    <img class="razorpay-image" src="https://i.postimg.cc/J4JDSFJK/rzp.png" alt="Razorpay Logo">
    <h2>Razorpay Payment</h2>
    <button id="rzp-button1">Pay Now</button>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
var options = {
    "key": "rzp_test_5uPzGreHV3wiCs", // Enter the Key ID generated from the Dashboard
    "amount": <?php echo $total_price * 100; ?>, // Total price should be in paisa if currency is INR
    "currency": "INR",
    "name": "Shree Sai Enterprises",
    "description": "Test Transaction",
    "image": "images/sselogo.png",
    "handler": function (response){
        // Send payment details to save_payment.php
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "save_payment.php", true);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText);
                // Redirect to booked.php after successful payment
                window.location.href = 'booked.php';
            }
        };
        xhr.send(JSON.stringify(response));
    }
};
document.getElementById('rzp-button1').onclick = function(e){
    var rzp1 = new Razorpay(options);
    rzp1.open();
    e.preventDefault();
}
</script>

</body>
</html>
