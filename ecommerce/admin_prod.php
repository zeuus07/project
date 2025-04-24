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

// Add Product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Check if file was uploaded without errors
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // Get file extension
        $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        // Generate unique filename to avoid overwriting existing files
        $image = 'images/' . uniqid('product_img_') . '.' . $file_extension;

        // Move uploaded file to desired location
        if (move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            // Insert product into the database
            $sql = "INSERT INTO products (name, category, description, price, image) VALUES ('$name', '$category', '$description', '$price', '$image')";
            if ($conn->query($sql) === TRUE) {
                echo "Product added successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error uploading image.";
        }
    } else {
        echo "No image uploaded.";
    }
}

// Delete Product
if (isset($_POST['delete_product'])) {
    $product_id = $_POST['product_id'];

    // Delete product from the database
    $sql = "DELETE FROM products WHERE id = $product_id";
    if ($conn->query($sql) === TRUE) {
        echo "Product deleted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all products from the database
$product_sql = "SELECT * FROM products";
$product_result = $conn->query($product_sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        form label {
            display: block;
            margin-bottom: 8px;
        }

        form input[type="text"],
        form input[type="number"],
        form textarea,
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            padding: 10px 20px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .image {
            max-width: 100px;
            max-height: 100px;
            border-radius: 4px;
        }

        .delete-form {
            display: inline-block;
        }

        .delete-form input[type="submit"] {
            background-color: #f44336;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .delete-form input[type="submit"]:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <h2>Admin Panel - Manage Products</h2>

    <!-- Add Product Form -->
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="name">Product Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="category">Category:</label>
        <input type="text" id="category" name="category" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>
        <label for="image">Image:</label>
        <input type="file" id="image" name="image" accept="image/*" required>
        <input type="submit" name="add_product" value="Add Product">
    </form>
    <!-- Product Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($product_result->num_rows > 0): ?>
                <?php while ($product_data = $product_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $product_data['id']; ?></td>
                        <td><?php echo $product_data['name']; ?></td>
                        <td><?php echo $product_data['category']; ?></td>
                        <td><?php echo $product_data['description']; ?></td>
                        <td><?php echo $product_data['price']; ?></td>
                        <td><img src="<?php echo $product_data['image']; ?>" alt="Product Image" class="image"></td>
                        <td class="delete-form">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="product_id" value="<?php echo $product_data['id']; ?>">
                                <input type="submit" name="delete_product" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No products found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
