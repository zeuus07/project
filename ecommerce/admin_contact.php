<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sse";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all contact records from the database
$contact_sql = "SELECT * FROM contact";
$contact_result = $conn->query($contact_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - View Contacts</title>
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
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Admin Panel - View Contacts</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Email</th>
                <th>Message</th>
                <th>Submission Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($contact_result->num_rows > 0): ?>
                <?php while ($contact_data = $contact_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $contact_data['id']; ?></td>
                        <td><?php echo $contact_data['name']; ?></td>
                        <td><?php echo $contact_data['phone_number']; ?></td>
                        <td><?php echo $contact_data['email']; ?></td>
                        <td><?php echo $contact_data['message']; ?></td>
                        <td><?php echo $contact_data['submission_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No contact records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
