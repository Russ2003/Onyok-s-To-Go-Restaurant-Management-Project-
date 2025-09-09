<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Onyoks To Go</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cabin:wght@400;700&family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cabin', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
        }

        /* Header styling */
        header {
            background-image: url('https://i.ibb.co/JjBMDy2/Client-Info-Group13-1.jpg');
            background-size: cover;
            background-position: center;
            padding: 40px 0;
            text-align: center;
            color: white;
            width: 100%;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
            padding-bottom: 15px;
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
        }

        .button-container button {
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #FFD700;
            color: #333;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
            font-family: 'Cabin', sans-serif;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .button-container button:hover {
            background-color: #FF4500;
            color: white;
        }

        .logout-form {
            display: inline;
        }

        .logout-form input {
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #FFD700;
            color: #333;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s ease;
            font-family: 'Cabin', sans-serif;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logout-form input:hover {
            background-color: #FF4500;
            color: white;
        }

        /* Welcome section styling */
        .welcome-section {
            background-color: #f7f7f7;
            padding: 50px 0;
            text-align: center;
        }

        .welcome-section h2 {
            font-family: 'Cabin', sans-serif;
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .line {
            width: 100px;
            height: 3px;
            background-color: #8B0000;
            margin: 0 auto 20px;
        }

        .welcome-text {
            font-family: 'Cabin', sans-serif;
            font-size: 16px;
            line-height: 1.6;
            text-align: justify;
            max-width: 800px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .bestsellers-section {
            background-image: url('https://i.ibb.co/6n6ShB0/Client-Info-Group13.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            padding: 50px;
            text-align: center;
            color: white;
            width: 100%;
            box-sizing: border-box;
            margin-bottom: 0;
        }

        .bestsellers-section h2 {
            font-family: 'Cabin', sans-serif;
            font-weight: bold;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .line {
            width: 100px;
            height: 3px;
            background-color: #8B0000;
            margin: 0 auto 20px;
        }

        .slideshow-container {
            position: relative;
            max-width: 800px;
            margin: 0 auto;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .bestseller img {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .prev, .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 50px;
            height: 50px;
            font-size: 24px;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            text-align: center;
            line-height: 50px;
            border-radius: 50%;
            z-index: 1;
        }

        .prev {
            left: 10px;
        }

        .next {
            right: 10px;
        }

        .prev:hover, .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        .fade {
            display: none;
        }

        .fade img {
            animation: fadeEffect 1s;
        }

        @keyframes fadeEffect {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        footer {
            background-image: url('https://i.ibb.co/JjBMDy2/Client-Info-Group13-1.jpg');
            background-size: cover;
            background-position: center;
            padding: 20px;
            color: white;
            font-family: 'Cabin', sans-serif;
            width: 100%;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
            margin: 0;
        }

        footer img {
            max-width: 100px;
            height: auto;
        }

        footer p {
            margin: 0;
            font-size: 14px;
            color: #f1f1f1;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Welcome to Onyok's To Go</h1>
        <div class="button-container">
            <!-- "Home" button -->
            <button onclick="location.href='index.php'">Home</button>

            <!-- "Menu" button -->
            <button onclick="location.href='menu.php'">Menu</button>

            <!-- "Cart" button -->
            <button onclick="location.href='cart.php'">Cart</button>

            <!-- Logout button -->
            <form action="logout.php" method="POST" class="logout-form">
                <input type="submit" value="Logout">
            </form>
        </div>
        <p>Hello, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>! You are logged in.</p>
    </header>

    <!-- Main content -->
    <div class="welcome-section">
        <h2>Winner Winner Chicken Dinner!</h2>
        <div class="line"></div>
        <div class="welcome-text">
            <p>Onyok’s To Go is all about tasty roasted chicken. They've got classics like Whole Chicken, Chicken Barbecue, and Chicken Isaw, all marinated overnight giving that flavorful bite. Recognized as Pulillan's favorite, Onyok’s To Go stands out as the go-to establishment for convenient door-to-door delivery of quality roasted chicken or a delightful street food experience.</p>
        </div>
    </div>

    <!-- Bestsellers section -->
    <div class="bestsellers-section">
        <h2>Bestsellers</h2>
        <div class="line"></div>
        <div class="slideshow-container">
            <!-- Slide 1 -->
            <div class="bestseller fade">
                <h3>Chicken Liempo</h3>
                <img src="https://i.ibb.co/pzsG2n5/liempo.png" alt="liempo">
            </div>
            <!-- Slide 2 -->
            <div class="bestseller fade">
                <h3>Chicken Barbecue</h3>
                <img src="https://i.ibb.co/fGqfVzS/bbq.png" alt="bbq">
            </div>
            <!-- Slide 3 -->
            <div class="bestseller fade">
                <h3>Pork Sisig</h3>
                <img src="https://i.ibb.co/yR8PvVQ/sisig.png" alt="sisig">
            </div>

            <!-- Navigation buttons -->
            <a class="prev" onclick="plusSlides(-1)">❮</a>
            <a class="next" onclick="plusSlides(1)">❯</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <img src="https://i.ibb.co/ygYNfTB/logo.png" alt="logo">
        <p>&copy; 2024 Onyok's To Go. All rights reserved.</p>
    </footer>

    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function showSlides(n) {
            let slides = document.getElementsByClassName("bestseller");
            if (n > slides.length) { slideIndex = 1 }
            if (n < 1) { slideIndex = slides.length }
            for (let i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slides[slideIndex - 1].style.display = "block";
        }
    </script>
</body>
</html>
