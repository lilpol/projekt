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

$sql = "SELECT id, name, description, price, autor FROM products";
$result = $conn->query($sql);

$username = htmlspecialchars($_SESSION['username']);
if ($username=="admin") {
    echo <<<admin
    <a href="delproduct.php">delete produckts</a>
admin;
}
echo <<<HTML
<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Inzerce, inzeráty, bazar</title>
</head>
<body>
    <h2>Welcome, {$username}!</h2>
    <a href="logout.php">Logout</a>
    <div>
        <a href="disproducts.php">search</a> <br>
        <a href="add_produckt.php">Add Product</a>
    </div>
    <div class="product-container">
HTML;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name = htmlspecialchars($row['name']);
        $description = htmlspecialchars($row['description']);
        $price = number_format($row['price'], 2);
        $autor = htmlspecialchars($row['autor']);
        

        
        echo <<<PRODUCT
        <div class='product'>
            <h3>{$name}</h3>
            <p>{$description}</p>
            <p class='price'>\${$price}</p>
            <p>{$autor}</p>
        </div>
PRODUCT;
    }
} else {
    echo "<p>No products available.</p>";
}

echo <<<HTML
    </div>

</body>
</html>
HTML;

$conn->close();
