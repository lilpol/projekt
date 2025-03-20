<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

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

// Fetch products and their associated user
$sql = "SELECT products.id, products.name, products.description, products.price, userdata.username 
        FROM products 
        JOIN userdata ON products.user_id = userdata.id";
$result = $conn->query($sql);


$username = htmlspecialchars($_SESSION['username']);
$isAdmin = ($username === "admin");

?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Listings</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            text-align: center;
            padding: 20px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        .nav-links {
            margin-bottom: 20px;
        }

        .nav-links a {
            text-decoration: none;
            background: #6a11cb;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 5px;
            display: inline-block;
            transition: 0.3s;
        }

        .nav-links a:hover {
            background: #2575fc;
        }

        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .product:hover {
            transform: scale(1.05);
        }

        .product h3 {
            margin-bottom: 10px;
            color: #333;
        }

        .price {
            color: green;
            font-weight: bold;
        }

        .logout-btn {
            background: red;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background: darkred;
        }

        .admin-btn {
            background: #ff9800;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }

        .admin-btn:hover {
            background: #e68900;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo $username; ?>!</h2>

    <div class="nav-links">
        <a href="disproducts.php">🔍 Search</a>
        <a href="add_produckt.php">➕ Add Product</a>
        <a href="logout.php" class="logout-btn">🚪 Logout</a>
    </div>

    <?php if ($isAdmin): ?>
        <a href="delproduct.php" class="admin-btn">🗑 Delete Products</a>
    <?php endif; ?>

    <div class="product-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name = htmlspecialchars($row['name']);
                $description = htmlspecialchars($row['description']);
                $price = number_format($row['price'], 2);
                $username = htmlspecialchars($row['username']);
        ?>
    <div class="product-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $name = htmlspecialchars($row['name']);
                $description = htmlspecialchars($row['description']);
                $price = number_format($row['price'], 2);

                echo <<<PRODUCT
                <div class='product'>
                    <h3>{$name}</h3>
                    <p>{$description}</p>
                    <p class='price'>\${$price}</p>
                </div>
PRODUCT;
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>

</body>
</html>

<?php
$conn->close();
    }
}
?>