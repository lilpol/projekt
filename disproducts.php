<?php
// Database connection
$servername = "dbs.spskladno.cz";
$username = "student2";
$password = "spsnet";
$dbname = "vyuka2";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get filter values with defaults
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== "" ? $_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== "" ? $_GET['max_price'] : 100000000;
$search = isset($_GET['search']) ? $_GET['search'] : "";

// SQL query with JOIN to get user data
$sql = "SELECT p.id, p.name, p.description, p.price, u.id AS author_id, u.username 
        FROM Products1 p 
        JOIN userdata u ON p.author_id = u.id 
        WHERE p.price BETWEEN ? AND ? AND p.name LIKE ?";

$stmt = $conn->prepare($sql);
$search_param = "%$search%";
$stmt->bind_param("dds", $min_price, $max_price, $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body {
             background: linear-gradient(to right, #6a11cb, #2575fc);
              color: white;
               text-align: center;
                padding: 20px;
             }
        .container { 
            max-width: 800px;
            margin: auto; background: white;
            padding: 20px; border-radius: 10px; 
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2); 
            color: black; }
        h2 { 
            margin-bottom: 15px;
        }
        .nav-links { 
            margin-bottom: 20px; 
        }
        .nav-links a { 
            text-decoration: none; 
            background: #6a11cb; color: white; 
            padding: 10px 15px; border-radius: 5px; 
            margin: 5px; display: inline-block; 
            transition: 0.3s; 
        }
        .nav-links a:hover { 
            background: #2575fc; 
        }
        .filter-form { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-bottom: 20px; }
        .filter-form input { padding: 8px; border: 1px solid #ddd; border-radius: 5px; }
        .filter-form button { background: #6a11cb; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; transition: 0.3s; }
        .filter-form button:hover { background: #2575fc; }
        .product-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .product { background: white; padding: 15px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s; }
        .product:hover { transform: scale(1.05); }
        .product h3 { margin-bottom: 10px; color: #333; }
        .price { color: green; font-weight: bold; }
        .seller a { color: blue; font-weight: bold; text-decoration: none; }
        .seller a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h2>Product List</h2>

    <div class="nav-links">
        <a href="index3.php">🏠 Home</a>
    </div>

    <!-- FILTER FORM -->
    <form class="filter-form" method="GET">
        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo htmlspecialchars($min_price); ?>" step="0.01">
        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo htmlspecialchars($max_price); ?>" step="0.01">
        <input type="text" name="search" placeholder="Search product" value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">🔍 Filter</button>
    </form>

    <hr>

    <div class="product-container">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = htmlspecialchars($row['name']);
            $description = htmlspecialchars($row['description']);
            $price = number_format($row['price'], 2);
            $user_id = $row['author_id'];
            $username = htmlspecialchars($row['username']);
    ?>
            <div class="product">
                <h3><?php echo $name; ?></h3>
                <p><?php echo $description; ?></p>
                <p class="price">$<?php echo $price; ?></p>
                <p class="seller">Seller: <a href="profile.php?user_id=<?php echo $user_id; ?>"><?php echo $username; ?></a></p>
            </div>
    <?php
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
