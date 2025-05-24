<?php
session_start();

$auth_message = $_SESSION["auth_message"];
$auth_message = isset($auth_message) ? $auth_message : "";

// TODO: Check if session already exists -> redirect to main page.

session_unset();
session_destroy();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Register</title>
</head>

<body>
  <h1>Password Vault</h1>
  <h2>Login</h2>
  <form action="App/Auth/login.php" method="POST">
    <label for="login-email">Email</label>
    <input type="email" id="login-email" name="email" required><br>

    <label for="login-pwd">Password</label>
    <input type="password" id="login-pwd" name="pwd" minlength="8" required><br>

    <input type="submit" value="Go">
  </form>
  <h2>Register</h2>
  <form action="App/Auth/register.php" method="POST">
    <label for="name">Full Name</label>
    <input type="text" id="name" name="name" minlength="4" required><br>

    <label for="register-email">Email</label>
    <input type="email" id="register-email" name="email" required><br>

    <label for="register-pwd">Password</label>
    <input type="password" id="register-pwd" name="pwd" minlength="8" required><br>

    <input type="submit" value="Go">
  </form>
  <p><b><?php echo $auth_message ?></b></p>
</body>

</html>
