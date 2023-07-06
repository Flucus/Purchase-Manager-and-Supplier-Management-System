<!DOCTYPE html>
<html>

<head>
  <title>Yummy Restaurant Login</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <div class="login-box">
    <h1>Yummy Restaurant Login</h1>

    <?php
    if (isset($_GET["error"])) {
    $error_message = $_GET["error"];
    echo "<p class='error'>$error_message</p>";
    }
    ?>

    <form action="login.php" method="POST">
      <label for="username">Username:</label>
      <input type="text" id="username" name="username" placeholder="Enter your username">
      <label for="password">Password:</label>
      <input type="password" id="password" name="password" placeholder="Enter your password">
      <input type="submit" value="Login">
    </form>
  </div>
</body>

</html>