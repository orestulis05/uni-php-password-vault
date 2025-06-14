<?php
include_once __DIR__ . "/App/Auth/authCheck.php";

session_start();

$auth_message = $_SESSION["auth_message"];
$auth_message = isset($auth_message) ? $auth_message : "";

redirect_authorized();

session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login & Register</title>
  <link rel="stylesheet" href="style.css">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="wrap">
    <h1>Password Vault</h1>
    <h2>Login</h2>
    <form action="App/Auth/login.php" method="POST">
      <label for="login-email" class="form-label">Email</label>
      <input type="email" id="login-email" name="email" class="form-control" required><br>

      <label for="login-pwd" class="form-label">Password</label>
      <input type="password" id="login-pwd" name="pwd" minlength="8" class="form-control" required><br>

      <input type="submit" value="Go" class="btn btn-primary">
    </form>
    <h2>Register</h2>
    <form action="App/Auth/register.php" method="POST">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" id="name" name="name" minlength="4" class="form-control" required><br>

      <label for="register-email" class="form-label">Email</label>
      <input type="email" id="register-email" name="email" class="form-control" required><br>

      <label for="register-pwd" class="form-label">Password</label>
      <input type="password" id="register-pwd" name="pwd" minlength="8" class="form-control" required><br>

      <input type="submit" value="Go" class="btn btn-primary">
    </form>
    <p><b><?php echo $auth_message ?></b></p>
  </div>
</body>

</html>
