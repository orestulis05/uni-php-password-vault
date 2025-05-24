<?php
session_start();

$logged_in = isset($_SESSION["session_user_email"]);
if (!$logged_in) {
  $_SESSION["auth_message"] = "Please log in.";
  header("Location: authPage.php");
  exit();
}

$user_email = $_SESSION["session_user_email"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hello World!</title>
</head>

<body>
  <h1>Password Vault</h1>
  <h2>Welcome <?php echo $user_email ?></h2>

</body>

</html>
