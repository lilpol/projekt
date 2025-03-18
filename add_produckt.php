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


$messageb = ""; // Default message bad
$messageg = ""; // Default message good
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $autor = $_SESSION['username'];



        // Insert product into database
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, autor) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $description, $price, $autor);

        if ($stmt->execute()) {
            $messageg = "produkt created successfully!";
        } else {
            $messageb = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

$conn->close();
?>
<a href="index3.php">domů</a>
<form method="POST" style="border:1px solid #ccc">
  <div class="container">
    <h1>ADD product</h1>
    <p>Please fill in this form to add a prudukt.</p>
    <hr>

    <?php if (!empty($messageb)) : ?>
        <p style="color: red;"><b><?php echo $messageb; ?></b></p>
    <?php endif; ?>
    <?php if (!empty($messageg)) : ?>
        <p style="color: green;"><b><?php echo $messageg; ?></b></p>
    <?php endif; ?>

    <label for="name"><b>name of prudukt</b></label>
    <input type="text" placeholder="Enter name of prudukt" name="name" required>

    <label for="description"><b>description</b></label>
    <input type="text" placeholder="Enter description" name="description" required>

    <label for="price"><b>enter price</b></label>
    <input type="text" placeholder="enter price" name="price" required>

    <div >
      <button type="submit" class="add_prod">add product</button>
    </div>
  </div>
</form>