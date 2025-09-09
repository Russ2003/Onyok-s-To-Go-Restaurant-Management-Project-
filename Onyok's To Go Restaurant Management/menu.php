<?php
session_start(); // Start the session

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "onyoks_to_go";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in and is an admin
$isAdmin = isset($_SESSION['user_id']) && $_SESSION['username'] === 'admin';

// Handle form submission for updating stock quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $isAdmin) {
    $productId = $_POST['product_id'] ?? null;
    $newQuantity = $_POST['stock_quantity'] ?? null;

    if ($productId && $newQuantity !== null) {
        // Update query
        $updateSql = "UPDATE menu SET stock_quantity = ? WHERE product_id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $newQuantity, $productId);

        if ($stmt->execute()) {
            echo "<p class='success-message'>Stock quantity updated successfully for Product ID: $productId.</p>";
        } else {
            echo "<p class='error-message'>Error updating stock quantity: " . $conn->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p class='error-message'>Invalid product ID or quantity.</p>";
    }
}

// Query to fetch the menu items
$sql = "SELECT product_id, product_name, stock_quantity, price FROM menu";
$result = $conn->query($sql);

// Image URLs mapped to product names
$imageUrls = [
    "Chicken Liempo" => "https://i.ibb.co/pzsG2n5/liempo.png",
    "Chicken Barbeque" => "https://i.ibb.co/fGqfVzS/bbq.png",
    "Whole Chicken" => "https://i.ibb.co/7QfkZkf/whole-chicken.png",
    "Chicken Sisig" => "https://i.ibb.co/yR8PvVQ/sisig.png",
    "Chicken Isaw" => "https://i.ibb.co/WsR42CN/isaw.png"
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Onyoks To Go</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        h1 {
            text-align: center;
            margin: 20px 0;
            color: #333;
        }

        .header {
            background-image: url('https://i.ibb.co/JjBMDy2/Client-Info-Group13-1.jpg');
            display: flex;
            justify-content: flex-start;
            align-items: center;
            background-color: #800000;
            padding: 10px 20px;
            color: #333;
        }

        .header a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .header a:hover {
            background-color: #e0c000; /* Slightly lighter shade */
        }

        .header-title {
            font-family: 'Roboto', sans-serif;
            font-size: 1.8em;
            font-weight: bold;
            color: white; /* Yellow color to make it stand out */
            text-align: left;
            margin: 10px 20px; /* Add some space around the title */
        }

        .back-btn {
            padding: 10px 15px;
            background-color: #141414;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .back-btn:hover {
            background-color: #e0c000;
        }

        .menu-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .menu-card {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 250px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px;
        }

        .menu-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .menu-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
        }

        .menu-card h2 {
            margin: 15px 0;
            font-size: 1.2em;
            color: black;
        }

        .menu-card p {
            margin: 5px 0;
        }

        .menu-card .price {
            font-size: 1 em;
            font-weight: bold;
            color: #8B0000; /* Updated to red */
        }

        .menu-card .available {
            font-size: 0.9em;
            color: #dc3545;
        }

        .admin-form {
            margin-top: 10px;
        }

        .admin-form input[type="number"] {
            padding: 5px;
            width: 100px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .admin-form input[type="submit"] {
            padding: 5px 10px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .admin-form input[type="submit"]:hover {
            background-color: #218838;
        }

        .success-message {
            color: #28a745;
            text-align: center;
            font-weight: bold;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            font-weight: bold;
        }

        .add-to-cart-btn {
            background-color: #FFD700; /* Updated to yellow */
            color: #333;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        .add-to-cart-btn:hover {
            background-color: #e0c000; /* Slightly darker yellow */
        }

        .cart-btn {
            padding: 10px 50px;
            background-color: #141414;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
            margin-left: 10px;
            transition: background-color 0.3s ease;
        }

        .cart-btn:hover {
            background-color: #e0c000;
        }

    </style>
</head>
<body>
    <div class="header">
    <h2 class="header-title">Onyok's to Go!!</h2>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a class="back-btn" href="index.php">Back to Index</a>
        <?php else: ?>
            <a class="back-btn" href="index.html">Back to Index</a>
        <?php endif; ?>
            <a class="cart-btn" href="cart.php">View your Cart</a>
    </div>
    
    <h1>Products</h1>
    <?php
    if (isset($_SESSION['user_id'])) {
        echo "<p class='success-message'>Hello, <strong>" . htmlspecialchars($_SESSION['username']) . "</strong>!</p>";
    }

    if ($isAdmin) {
        echo "<p class='success-message'>You are logged in as admin. You can update the available items.</p>";
    }
    ?>
    <div class="menu-container">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $productName = htmlspecialchars($row['product_name']);
                $imageUrl = $imageUrls[$productName] ?? 'default_image_url.png'; // Fallback image

                echo "<div class='menu-card'>
                        <img src='$imageUrl' alt='$productName'>
                        <h2>$productName</h2>
                        <p class='available'>Available Items: " . htmlspecialchars($row['stock_quantity']) . "</p>
                        <p class='price'>₱" . number_format($row['price'], 2) . "</p>";

                if ($isAdmin) {
                    echo "<form class='admin-form' method='POST' action='menu.php'>
                            <input type='hidden' name='product_id' value='" . htmlspecialchars($row['product_id']) . "'>
                            <input type='number' name='stock_quantity' placeholder='New Quantity' required>
                            <input type='submit' value='Update'>
                          </form>";
                }

                echo "<button class='add-to-cart-btn' onclick='addToCart(" . htmlspecialchars($row['product_id']) . ")'>Add to Cart</button>
                    </div>";
            }
        } else {
            echo "<p class='error-message'>No menu items available.</p>";
        }
        $conn->close();
        ?>
    </div>

    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'product_id=' + productId
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>
</body>
</html>
