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

$messageb = ""; // Default message
$messageg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rpassword = $_POST['rpassword'];

    // Check if username already exists
    $checkusername = $conn->prepare("SELECT username FROM userdata WHERE username = ?");
    $checkusername->bind_param("s", $username);
    $checkusername->execute();
    $checkusername->store_result();

    if ($rpassword !== $password) {
        $messageb = "Passwords do not match!";
    } elseif ($checkusername->num_rows > 0) {
        $messageb = "Username already exists!";
    } else {
        // Insert user into database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO userdata (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $messageg = "Account created successfully!";
        } else {
            $messageb = "Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkusername->close();
}

$conn->close();
?>

<form method="POST" style="border:1px solid #ccc">
  <div class="container">
    <h1>Sign Up</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <?php if (!empty($messageb)) : ?>
        <p style="color: red;"><b><?php echo $messageb; ?></b></p>
    <?php endif; ?>
    <?php if (!empty($messageg)) : ?>
        <p style="color: green;"><b><?php echo $messageg; ?></b></p>
    <?php endif; ?>

    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter username" name="username" required>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <label for="rpassword"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="rpassword" required>

    <div >
      <button type="submit" class="signupbut">Sign Up</button>
    </div>
  </div>
</form>

<div>
  <button onclick="window.location.href='index3.php'">Back</button>
</div>
