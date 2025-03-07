
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rpassword= $_POST['rpassword'];
    $checkusername = $conn->prepare("SELECT username FROM userdata WHERE username = ?");
    $checkusername->bind_param("s", $username);
    $checkusername->execute();
    $checkusername->store_result();
    if ($rpassword!=$password) {
      $message = "password doesnt match ";
    }
      if ($checkusername->num_rows > 0) {
          $message = "username already exists";
      } else {
        $stmt = $conn->prepare("INSERT INTO userdata (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            $message = "Account created successfully";
        } else {
            $message = "Error: " . $stmt->error;
        }
      
      $stmt->close();
  }

}
?>
<form method="POST" action="index3.php" style="border:1px solid #ccc">
  <div class="container">
    <h1>Sign Up</h1>
    <p>Please fill in this form to create an account.</p>
    <hr>

    <label for="username"><b>username</b></label>
    <input type="text" placeholder="username" name="username" required>

    <label for="password"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <label for="rpassword"><b>Repeat Password</b></label>
    <input type="password" placeholder="Repeat Password" name="rpassword" required>

   



    <div class="clearfix">
      <button type="submit" class="signupbtn">Sign Up</button>
    </div>
  </div>
</form>