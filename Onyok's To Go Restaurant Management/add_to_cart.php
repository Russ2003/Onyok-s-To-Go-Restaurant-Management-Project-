<?php
session_start();

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

// Handle adding an item to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $productId = intval($_POST['product_id']);

    // Query to get product details from the database
    $sql = "SELECT product_name, price FROM menu WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Check if the cart session is set, if not create it
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Add product details to the session cart
        if (isset($_SESSION['cart'][$productId])) {
            // If the item already exists, increment the quantity
            $_SESSION['cart'][$productId]['quantity'] += 1;
        } else {
            // Otherwise, add the item to the cart
            $_SESSION['cart'][$productId] = [
                'product_name' => $row['product_name'],
                'price' => $row['price'],
                'quantity' => 1 // Default quantity
            ];
        }

        // Check if order ID is already set, if not create it
        if (!isset($_SESSION['order_id'])) {
            $_SESSION['order_id'] = uniqid('order_', true); // Generate a unique order ID
        }

        echo json_encode(['success' => true, 'message' => 'Item added to cart successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found.']);
    }
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}

$conn->close();
?>
