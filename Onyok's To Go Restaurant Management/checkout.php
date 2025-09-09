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

// Check if an order ID is set in the session; if not, create one
if (!isset($_SESSION['order_id'])) {
    $result = $conn->query("SELECT MAX(order_id) AS max_order_id FROM orders");
    $row = $result->fetch_assoc();
    $_SESSION['order_id'] = ($row['max_order_id'] ?? 0) + 1;
}

// Debugging output to check if order ID is set
$orderId = $_SESSION['order_id'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phone_number'];
    $paymentMethod = $_POST['payment_method'];

    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        $paymentStatus = 'Pending'; // Initial payment status
        $paymentDate = date('Y-m-d H:i:s');

        // Insert data into the `checkout` table
        $insertSql = "INSERT INTO checkout (order_id, user_email, payment_method, payment_status, payment_date, phone_number, first_name, last_name)
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("isssssss", $orderId, $email, $paymentMethod, $paymentStatus, $paymentDate, $phoneNumber, $firstName, $lastName);

        if ($stmt->execute()) {
            // Calculate total items and total cost
            $totalItems = 0;
            $totalCost = 0; // Get the total cost from cart data
            foreach ($_SESSION['cart'] as $productId => $product) {
                $totalItems += $product['quantity'];
                $totalCost += $product['price'] * $product['quantity'];
            }

            // Insert data into the `orders` table
            $orderDate = date('Y-m-d H:i:s'); // Current date and time
            $orderStatus = 'Pending'; // Initial status of the order
            $userId = $_SESSION['user_id']; // Assuming user ID is stored in session

            $insertOrderSql = "INSERT INTO orders (order_id, total_items, total_cost, order_date, status, user_id)
                               VALUES (?, ?, ?, ?, ?, ?)";
            $stmtOrder = $conn->prepare($insertOrderSql);
            $stmtOrder->bind_param("iiisii", $orderId, $totalItems, $totalCost, $orderDate, $orderStatus, $userId);

            if ($stmtOrder->execute()) {
                echo "Order record added successfully.<br>";
            } else {
                echo "Error adding order record: " . $conn->error . "<br>";
            }
            $stmtOrder->close();

            // Update stock quantities in the `menu` table
            foreach ($_SESSION['cart'] as $productId => $product) {
                $quantity = $product['quantity'];

                $updateStockSql = "UPDATE menu SET stock_quantity = stock_quantity - ? WHERE product_id = ? AND stock_quantity >= ?";
                $updateStmt = $conn->prepare($updateStockSql);
                $updateStmt->bind_param("iii", $quantity, $productId, $quantity);

                if (!$updateStmt->execute()) {
                    echo "Error updating stock for Product ID: $productId - " . $conn->error . "<br>";
                } elseif ($updateStmt->affected_rows == 0) {
                    echo "Insufficient stock for Product ID: $productId. Please review your cart.<br>";
                }
                $updateStmt->close();
            }

            // Clear session data after successful checkout
            unset($_SESSION['cart']);
            unset($_SESSION['order_id']);

            // Redirect to receipt page
            header("Location: receipt.php?order_id=" . $orderId);
            exit();
        } else {
            echo "Error processing checkout: " . $conn->error . "<br>";
        }
        $stmt->close();
    } else {
        echo "Your cart is empty. Please add items to your cart first.<br>";
    }
}

// Close the database connection
$conn->close();
?>
<?php
// Your existing PHP code remains unchanged
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stylish Checkout</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 100%;
            max-width: 600px;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        h1 {
            color: #8B0000; /* Red color */
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 1px;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }
        input[type="text"],
        input[type="email"],
        select {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1.5rem;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            outline: none;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        select:focus {
            border-color: #8B0000; /* Red color */
        }
        button {
            width: 100%;
            padding: 0.75rem;
            background-color: #FFD700; /* Yellow color */
            color: #8B0000; /* Red color */
            border: none;
            border-radius: 8px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        button:hover {
            background-color: #e6c300; /* Slightly darker yellow */
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }
        button:active {
            transform: translateY(0);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 600px) {
            .container {
                padding: 1.5rem;
            }
            h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Checkout</h1>
        <form action="checkout.php" method="POST">
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" name="phone_number" id="phone_number" required>

            <label for="payment_method">Payment Method:</label>
            <select name="payment_method" id="payment_method" required>
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>

            <button type="submit">Proceed to Checkout</button>
        </form>
    </div>
</body>
</html>


