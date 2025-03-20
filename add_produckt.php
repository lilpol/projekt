<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get user ID from session
$user_query = $conn->prepare("SELECT id FROM userdata WHERE username = ?");
$user_query->bind_param("s", $_SESSION['username']);
$user_query->execute();
$user_query->bind_result($user_id);
$user_query->fetch();
$user_query->close();

$messageb = "";
$messageg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Insert product with user_id
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssdi", $name, $description, $price, $user_id);

    if ($stmt->execute()) {
        $messageg = "✅ Product added successfully!";
    } else {
        $messageb = "❌ Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }


        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #333;
            margin-bottom: 15px;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .error {
            background: #ffdddd;
            color: #d8000c;
        }

        .success {
            background: #ddffdd;
            color: #4CAF50;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .add_prod {
            background: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            transition: 0.3s;
        }

        .add_prod:hover {
            background: #218838;
        }

        .back-btn {
            background: gray;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            margin-top: 10px;
        }

        .back-btn:hover {
            background: darkgray;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Add Product</h1>
    <p>Please fill in this form to add a product.</p>
    <hr>

    <?php if (!empty($messageb)) : ?>
        <p class="alert error"><?php echo $messageb; ?></p>
    <?php endif; ?>
    <?php if (!empty($messageg)) : ?>
        <p class="alert success"><?php echo $messageg; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="name"><b>Product Name</b></label>
        <input type="text" placeholder="Enter product name" name="name" required>

        <label for="description"><b>Description</b></label>
        <input type="text" placeholder="Enter description" name="description" required>

        <label for="price"><b>Price</b></label>
        <input type="text" placeholder="Enter price" name="price" required>

        <button type="submit" class="add_prod">Add Product</button>
    </form>

    <button onclick="window.location.href='index3.php'" class="back-btn">⬅ Back</button>
</div>

</body>
</html>
