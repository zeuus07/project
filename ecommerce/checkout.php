<?php
session_start();

// Check if total price is stored in the session
if (isset($_SESSION['totalPrice'])) {
    $totalPrice = $_SESSION['totalPrice'];
} else {
    // If total price is not found in the session, handle accordingly
    $totalPrice = 0; // Set default value or handle as needed
}

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = ""; // Assuming empty password
$database = "sse";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // If the address form is submitted, insert new address into the database
    if (isset($_POST['address']) && isset($_POST['city']) && isset($_POST['postal_code']) && isset($_POST['country'])) {
        $address = $_POST['address'];
        $city = $_POST['city'];
        $postal_code = $_POST['postal_code'];
        $country = $_POST['country'];
        
        // Insert the new address into the database
        $user_id = $_SESSION["id"];
        $sql = "INSERT INTO sse_address (user_id, address, city, postal_code, country) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $user_id, $address, $city, $postal_code, $country);
        $stmt->execute();

        // Redirect to the same page to avoid form resubmission
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch addresses for the user
$user_id = $_SESSION["id"];
$sql = "SELECT * FROM sse_address WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if addresses exist for the user
if ($result->num_rows > 0) {
    // Addresses exist, display them for selection
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-top: 0;
            color: #333;
        }

        h3 {
            margin-top: 20px;
            color: #555;
        }

        p {
            font-size: 18px;
            color: #444;
        }

        select, button {
            padding: 10px;
            width: 100%;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <p>Total Price: ₹<?php echo $totalPrice; ?></p>
        <h3>Select Address</h3>
        <form action="process_checkout.php" method="post">
            <select name="address_id">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <option value="<?php echo $row['id']; ?>"><?php echo $row['address']; ?></option>
                <?php endwhile; ?>
            </select>
            <button type="submit">Proceed to Payment</button>
        </form>
    </div>
</body>
</html>

    <?php
} else {
    // No addresses found, prompt user to add address
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            margin-top: 0;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Checkout</h2>
        <?php if ($result->num_rows > 0): ?>
            <p>Total Price: ₹<?php echo $totalPrice; ?></p>
            <h3>Select Address</h3>
            <form action="process_checkout.php" method="post">
                <select name="address_id">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['address']; ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Proceed to Payment</button>
            </form>
        <?php else: ?>
            <p>Total Price: ₹<?php echo $totalPrice; ?></p>
            <h3>Add New Address</h3>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="city" placeholder="City" required>
                <input type="text" name="postal_code" placeholder="Postal Code" required>
                <input type="text" name="country" placeholder="Country" required>
                <button type="submit">Proceed to Payment</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>


    <?php
}

$conn->close();
?>
