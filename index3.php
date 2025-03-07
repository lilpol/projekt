
<!DOCTYPE html>
<html lang="cs">
<head>
<title>Inzerce, inzeráty, bazar</title>
</head>
<body>
<?php
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
?>
    
    <h2>home</h2>
    <a href="index3.php">home</a>
    <a href="login.php">login</a>
    <div>
        <a href="produkt"></a>
    </div>



</body>