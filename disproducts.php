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

// Get filter values (set defaults if empty)
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== "" ? $_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== "" ? $_GET['max_price'] : 100000000;
$search = isset($_GET['search']) ? $_GET['search'] : "";

// Build SQL query with filters
$sql = "SELECT id, name, description, price FROM products WHERE 1";

// Apply filters if selected
if (!empty($min_price)) {
    $sql .= " AND price >= " . $conn->real_escape_string($min_price);
}
if (!empty($max_price)) {
    $sql .= " AND price <= " . $conn->real_escape_string($max_price);
}
if (!empty($search)) {
    $sql .= " AND name LIKE '%" . $conn->real_escape_string($search) . "%'";
}

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
    </style>
</head>
<body>

    <h2>Products</h2>
<a href="index3.php">home</a>
    <!-- FILTER FORM -->
    <form method="GET">
        <label>Min Price:</label>
        <input type="number" name="min_price" value="<?php echo htmlspecialchars($min_price); ?>" step="0.01">

        <label>Max Price:</label>
        <input type="number" name="max_price" value="<?php echo htmlspecialchars($max_price); ?>" step="0.01">

        <label>Search:</label>
        <input type="text" name="search" placeholder="Search product" value="<?php echo htmlspecialchars($search); ?>">

        <button type="submit">Filter</button>
    </form>

    <hr>

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
?>
