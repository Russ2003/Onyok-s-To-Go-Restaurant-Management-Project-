<?php
session_start();

// Handle clearing the cart when the button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
    $_SESSION['cart_message'] = "Your cart has been cleared.";
    header("Location: cart.php");
    exit();
}

// Image URLs for products
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
    <title>Cart - Onyoks To Go</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* Styles remain unchanged */
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
            color: white;
        }

        .header {
            background-image: url('https://i.ibb.co/JjBMDy2/Client-Info-Group13-1.jpg');
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            color: white;
        }

        .header a {
            text-decoration: none;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            background: #141414;
        }

        .header a:hover {
            background-color: #FFD700;
        }

        .cart-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .cart-item {
            display: flex;
            gap: 20px;
            border-bottom: 1px solid #ddd;
            padding: 15px 0;
        }

        .cart-item img {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            object-fit: cover;
        }

        .cart-item-details {
            flex: 1;
        }

        .cart-item h2 {
            font-size: 1.2em;
            color: black;
            margin: 0;
        }

        .cart-item p {
            margin: 5px 0;
        }

        .cart-item .price {
            font-size: 1em;
            font-weight: bold;
            color: #8B0000;
        }

        .buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .buttons a,
        .buttons button {
            padding: 10px 20px;
            background-color: #FFD700;
            color: #333;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .buttons a:hover,
        .buttons button:hover {
            background-color: #e0c000;
        }

        .empty-message {
            text-align: center;
            color: #dc3545;
            font-weight: bold;
            margin-top: 20px;
        }

        .total-price {
            text-align: right;
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Onyok's To Go - Cart</h1>
        <a href="index.php">Return to Home</a>
    </div>

    <div class="cart-container">
        <?php
        if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
            $totalPrice = 0; // Initialize total price
            foreach ($_SESSION['cart'] as $productId => $product) {
                $imageUrl = $imageUrls[$product['product_name']] ?? 'default_image.png';
                $subtotal = $product['price'] * $product['quantity'];
                $totalPrice += $subtotal; // Accumulate total price
                echo "<div class='cart-item'>
                        <img src='$imageUrl' alt='" . htmlspecialchars($product['product_name']) . "'>
                        <div class='cart-item-details'>
                            <h2>" . htmlspecialchars($product['product_name']) . "</h2>
                            <p class='price'>₱" . number_format($product['price'], 2) . " x " . htmlspecialchars($product['quantity']) . "</p>
                            <p>Subtotal: ₱" . number_format($subtotal, 2) . "</p>
                        </div>
                      </div>";
            }

            // Display total price
            echo "<div class='total-price'>Total: ₱" . number_format($totalPrice, 2) . "</div>";

            echo "<div class='buttons'>
                    <form method='POST' action=''>
                        <button type='submit' name='clear_cart'>Clear Cart</button>
                    </form>
                    <a href='checkout.php'>Proceed to Checkout</a>
                  </div>";
        } else {
            if (isset($_SESSION['cart_message'])) {
                echo "<p class='empty-message'>" . $_SESSION['cart_message'] . "</p>";
                unset($_SESSION['cart_message']);
            } else {
                echo "<p class='empty-message'>Your cart is empty.</p>";
            }
        }
        ?>
    </div>
</body>
</html>
