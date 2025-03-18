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

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists
    $stmt = $conn->prepare("SELECT password FROM userdata WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    //colects password from db
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        // Verify the password
        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username; // Start session
            header("Location: index3.php"); // Redirect to welcome page
            exit();
        } else {
            $message = "Incorrect password!";
        }
    } else {
        $message = "Username not found!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <a href="index3.php">home</a>

    <form method="POST">
        <?php if (!empty($message)) : ?>
            <p style="color: red;"><b><?php echo $message; ?></b></p>
        <?php endif; ?>

        <label for="username">Username:</label>
        <input type="text" id="username" name="username" placeholder="Enter your Username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your Password" required>

        <div class="wrap">
            <button type="submit">Submit</button>
        </div>
    </form>

    <p>Not registered? <a href="register.php">Create an account</a></p>
</body>
</html>
