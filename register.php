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
        $messageb = "❌ Passwords do not match!";
    } elseif ($checkusername->num_rows > 0) {
        $messageb = "❌ Username already exists!";
    } else {
        // Insert user into database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO userdata (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed_password);

        if ($stmt->execute()) {
            $messageg = "✅ Account created successfully!";
        } else {
            $messageb = "❌ Error: " . $stmt->error;
        }

        $stmt->close();
    }

    $checkusername->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }


        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            font-weight: bold;
        }

        .error {
            background: #ffdddd;
            color: #d8000c;
        }

        .success {
            background: #ddffdd;
            color: #4CAF50;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            display: inline-block;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .signupbut {
            background: #6a11cb;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            width: 100%;
            cursor: pointer;
            transition: 0.3s;
        }

        .signupbut:hover {
            background: #2575fc;
        }

        .back-btn {
            background: gray;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            margin-top: 10px;
        }

        .back-btn:hover {
            background: darkgray;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Sign Up</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <?php if (!empty($messageb)) : ?>
        <p class="alert error"><?php echo $messageb; ?></p>
    <?php endif; ?>
    <?php if (!empty($messageg)) : ?>
        <p class="alert success"><?php echo $messageg; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username"><b>Username</b></label>
        <input type="text" placeholder="Enter username" name="username" required>

        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" required>

        <label for="rpassword"><b>Repeat Password</b></label>
        <input type="password" placeholder="Repeat Password" name="rpassword" required>

        <button type="submit" class="signupbut">Sign Up</button>
    </form>

    <button onclick="window.location.href='index3.php'" class="back-btn">⬅ Back</button>
</div>

</body>
</html>
