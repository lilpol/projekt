<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test";

$conn = new mysqli($servername, $username, $password, $dbname);

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
        .product-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .product {
            border: 1px solid #ddd;
            padding: 10px;
            width: 250px;
            text-align: center;
        }
        .product img {
            width: 100%;
            height: auto;
        }
        .price {
            color: green;
            font-weight: bold;
        }
        .delete-btn {
            background-color: red;
            color: white;
            padding: 5px;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <h2>Products</h2>

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
                        <button type="submit">Delete</button>
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
?>
