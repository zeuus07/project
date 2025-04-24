<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "sse");

// Check if the user is logged in
session_start();

if (!isset($_SESSION["id"])) {
    header("Location: index.php");
    exit();
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

// Store cart items in an array
$cart_items = array();
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
}

// Close the database connection


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }
        /* Total price */
.total-price {
    font-size: 50px;
    color: #43484D;
    font-weight: bold; /* Make the total price bold */
    text-align: center; /* Center align the total price */
    margin-top: 10px; /* Add some margin at the top */
}

        /* Container for the shopping cart */
        .shopping-cart {
            max-width: 400px;
            margin: 20px auto;
            padding: 10px;
            background: #FFFFFF;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 6px;
        }

        /* Individual item in the shopping cart */
        .item {
            display: flex;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #E1E8EE;
        }

        /* Image container */
        .image {
            width: 60px;
            height: 60px;
            margin-right: 10px;
        }

        /* Image styling */
        .image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 4px;
        }

        /* Description container */
        .description {
            flex-grow: 1;
            margin-right: 10px;
        }

        /* Product name */
        .description span {
            display: block;
            font-size: 14px;
            color: #43484D;
            font-weight: 500;
            margin-bottom: 5px;
        }

        /* Quantity container */
        .quantity {
            display: flex;
            align-items: center;
        }

        /* Quantity input */
        .quantity input {
            width: 30px;
            height: 30px;
            text-align: center;
            font-size: 14px;
            color: #43484D;
            font-weight: 500;
            border: 1px solid #E1E8EE;
            border-radius: 4px;
            margin: 0 5px;
        }

        /* Plus and minus buttons */
        .quantity button {
            width: 30px;
            height: 30px;
            background-color: #E1E8EE;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        /* Total price */
        .total-price {
            font-size: 14px;
            color: #43484D;
            font-weight: 500;
        }

        /* Checkout button */
        .checkout-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #007bff; /* Default button background color (blue) */
        color: #ffffff; /* Button text color */
        text-decoration: none;
        border: 1px solid #007bff; /* Border color same as background color for initial state */
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Box shadow for button */
        cursor: pointer;
        transition: background-color 0.3s, border-color 0.3s; /* Smooth transition for hover effect */
        max-width: 200px; /* Maximum width of the button */
        margin: 0 auto; /* Center the button horizontally */
    }

    .checkout-btn:hover {
        background-color: #28a745; /* Button background color on hover (green) */
        border-color: #28a745; /* Border color on hover (green) */
    }
    </style>
</head>
<body>
    <div class="shopping-cart">
        <?php 
        $total_price = 0;
        
        foreach ($cart_items as $item): 
            $total_price += $item['price'] * $item['quantity'];
            $_SESSION['totalPrice'] = $total_price;
            ?>
            <div class="item">
                <div class="image"><img src="<?php echo $item['image']; ?>" alt="Product Image"></div>
                <div class="description">
                    <span><?php echo $item['name']; ?></span>
                    <span>Price: ₹<?php echo $item['price']; ?></span>
                </div>
                <div class="quantity">
                <button class="minus-btn" type="button" onclick="decrementQuantity(<?php echo $item['id']; ?>, this.nextElementSibling)">-</button>
                    <input type="text" name="quantity" value="<?php echo $item['quantity']; ?>">
                    <button class="plus-btn" type="button" onclick="incrementQuantity(<?php echo $item['id']; ?>, this.previousElementSibling)">+</button>
                </div>
                <div class="total-price">Total: ₹<?php echo $item['price'] * $item['quantity']; ?></div>
            </div>
        <?php endforeach; ?>
        <!-- Display total price -->
        <div class="total-price">Total Price: <span style="font-weight: bold;">₹<?php echo $total_price; ?></span></div>

        <!-- Checkout button -->
      
</div>

<div style="text-align: center;"> <!-- Center the button container -->
    <a href="checkout.php?totalPrice=<?php echo $total_price; ?>">
        <button class="checkout-btn" type="button">Checkout</button>
    </a>
</div>

</body>
</html>
<script>
    // Function to fetch the latest cart items and total price
    function loadCartItems() {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'load_cart_items.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Update the shopping cart with the latest data
                document.querySelector('.shopping-cart').innerHTML = xhr.responseText;
            }
        };
        xhr.send();
    }

  // Function to update the quantity asynchronously
function updateQuantity(productId, quantity, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'update_quantity.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Reload the cart items after updating quantity
            if (callback && typeof callback === 'function') {
                callback(); // Execute the callback function
            }
        }
    };
    xhr.send('productId=' + productId + '&quantity=' + quantity);
}
// Function to handle incrementing the quantity
function incrementQuantity(productId, input) {
    var currentValue = parseInt(input.previousElementSibling.value); // Get the current value from the input field
    var newValue = currentValue + 1; // Increment the value
    input.previousElementSibling.value = newValue; // Update the input field with the new value
    updateQuantity(productId, newValue, loadCartItems); // Call updateQuantity function with the new value
}

// Function to handle decrementing the quantity
function decrementQuantity(productId, input) {
    var currentValue = parseInt(input.nextElementSibling.value); // Get the current value from the input field
    if (currentValue > 1) {
        var newValue = currentValue - 1; // Decrement the value
        input.nextElementSibling.value = newValue; // Update the input field with the new value
        updateQuantity(productId, newValue, loadCartItems); // Call updateQuantity function with the new value
    } else {
        if (confirm('Are you sure you want to remove this item from the cart?')) {
            deleteItem(productId);
        }
    }
}

// Function to delete item from the cart
function deleteItem(productId) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete_item.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Remove the deleted item from the DOM
            var deletedItem = document.querySelector('.item[data-product-id="' + productId + '"]');
            if (deletedItem) {
                deletedItem.parentNode.removeChild(deletedItem);
                // Update the total price display
                updateTotalPriceDisplay();
            }
        }
    };
    xhr.send('productId=' + productId);
}

// Function to fetch the latest cart items and total price
function loadCartItems() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'load_cart_items.php', true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Update the shopping cart with the latest data
            var shoppingCartDiv = document.querySelector('.shopping-cart');
            shoppingCartDiv.innerHTML = xhr.responseText;

            // Add event listeners to the new plus and minus buttons
            addEventListenersToButtons();
        }
    };
    xhr.send();
}


    // Load cart items when the page loads
    window.onload = function() {
        loadCartItems();
    };

   

</script>
<script>
     function redirectToCheckout() {
    // Get the total price
    var totalPrice = document.querySelector('.total-price span').innerText.replace('₹', '');
    
    // Store the total price in the session
    sessionStorage.setItem('totalPrice', totalPrice);
    
    // Redirect to checkout.php
    window.location.href = 'checkout.php';
}
    </script>