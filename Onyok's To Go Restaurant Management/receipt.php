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

$orderId = $_GET['order_id'] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f4f4f9, #e1e8ed);
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            overflow: hidden;
        }
        .container {
            width: 100%;
            max-width: 700px;
            padding: 2rem;
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid #ddd;
            overflow: hidden;
            position: relative;
        }
        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        h1 {
            color: #8B0000; /* Red color */
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(90deg, #8B0000, #D32F2F);
            -webkit-background-clip: text;
            color: transparent;
            animation: fadeIn 1.5s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        p {
            margin: 0.5rem 0;
            font-size: 1rem;
            color: #555;
            animation: fadeIn 1s forwards;
            opacity: 0;
        }
        p:nth-child(2) { animation-delay: 0.5s; }
        p:nth-child(3) { animation-delay: 0.7s; }
        p:nth-child(4) { animation-delay: 0.9s; }
        p:nth-child(5) { animation-delay: 1.1s; }
        p:nth-child(6) { animation-delay: 1.3s; }
        p:nth-child(7) { animation-delay: 1.5s; }
        button {
            width: 100%;
            padding: 0.75rem;
            background: linear-gradient(90deg, #FFD700, #FFC300);
            color: #8B0000; /* Red color */
            border: none;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: background 0.3s, transform 0.2s, box-shadow 0.2s;
            margin-top: 1.5rem;
            opacity: 0;
            animation: fadeIn 1s forwards;
            animation-delay: 1.7s;
        }
        button:hover {
            background: linear-gradient(90deg, #e6c300, #d4b300);
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
        <?php
        if ($orderId) {
            $sql = "SELECT * FROM checkout WHERE order_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $orderId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $receipt = $result->fetch_assoc();
                echo "<h1>Receipt for Order ID: " . htmlspecialchars($receipt['order_id']) . "</h1>";
                echo "<p>First Name: " . htmlspecialchars($receipt['first_name']) . "</p>";
                echo "<p>Last Name: " . htmlspecialchars($receipt['last_name']) . "</p>";
                echo "<p>Email: " . htmlspecialchars($receipt['user_email']) . "</p>";
                echo "<p>Phone Number: " . htmlspecialchars($receipt['phone_number']) . "</p>";
                echo "<p>Payment Method: " . htmlspecialchars($receipt['payment_method']) . "</p>";
                echo "<p>Payment Status: " . htmlspecialchars($receipt['payment_status']) . "</p>";
                echo "<p>Payment Date: " . htmlspecialchars($receipt['payment_date']) . "</p>";
            } else {
                echo "<p style='color: #d9534f;'>Receipt not found for the given order ID.</p>";
            }
            $stmt->close();
        } else {
            echo "<p style='color: #d9534f;'>Order ID not provided.</p>";
        }
        ?>
        <!-- Button to go back to the homepage -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php"><button>Back to Home</button></a>
        <?php else: ?>
            <a href="index.html"><button>Back to Home</button></a>
        <?php endif; ?>
    </div>
</body>
</html>
