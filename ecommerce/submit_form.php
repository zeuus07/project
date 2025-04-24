<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "sse";

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind parameters
    $stmt = $conn->prepare("INSERT INTO contact (name, phone_number, email, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone_number, $email, $message);

    // Set parameters and execute
    $name = $_POST["name"];
    $phone_number = $_POST["phone_number"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    $stmt->execute();

    echo "New record created successfully";

    $stmt->close();
    $conn->close();
}
?>
