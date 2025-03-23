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

// Get user ID from URL
if (!isset($_GET['user_id']) || empty($_GET['user_id'])) {
    die("Invalid user.");
}
$user_id = intval($_GET['user_id']);

// Fetch user details
$sql = "SELECT username, email FROM userdata WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Query failed: " . $conn->error);
}

// Check if user exists
if ($result->num_rows == 0) {
    die("User not found.");
}
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($user['username']); ?>'s Profile</title>
    <style>
        body {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            text-align: center;
            padding: 20px;
        }
        .profile-container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            color: black;
        }
        h2 {
            margin-bottom: 10px;
        }
        .back-link {
            margin-top: 15px;
            display: inline-block;
            padding: 10px;
            background: #6a11cb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .back-link:hover {
            background: #2575fc;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2><?php echo htmlspecialchars($user['username']); ?>'s Profile</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <a href="index3.php" class="back-link">🏠 Back to Home</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
