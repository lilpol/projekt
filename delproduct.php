<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);
$username = htmlspecialchars($_SESSION['username']);
$isAdmin = ($username === "admin");
if ($isAdmin) {
    

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products from the database
$sql = "SELECT id, name, description, price FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: white;
            text-align: center;
            padding: 20px;
        }
        h2 {
            margin-bottom: 20px;
            font-size: 2rem;
        }
        a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s ease-in-out;
        }
        a:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: auto;
        }
        .product {
            background: rgba(255, 255, 255, 0.15);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .product:hover {
            transform: scale(1.05);
        }
        h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        p {
            font-size: 1rem;
            margin-bottom: 10px;
        }
        .price {
            color: #27ae60;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition:  0.3s ease-in-out;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>

    <h2>Products</h2>
    <a href="index3.php">🏠 Home</a>

    <div class="product-container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $id = $row['id'];
                $name = htmlspecialchars($row['name']);
                $description = htmlspecialchars($row['description']);
                $price = number_format($row['price'], 2);

                echo <<<PRODUCT
                <div class='product'>
                    <h3>{$name}</h3>
                    <p>{$description}</p>
                    <p class='price'>\${$price}</p>
                    <form action="del.php" method="POST">
                        <input type="hidden" name="id" value="{$id}">
                        <button type="submit" class="delete-btn">🗑 Delete</button>
                    </form>
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
    } else {header("Location: index3.php");}
?>
