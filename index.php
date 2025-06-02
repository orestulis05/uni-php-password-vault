<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 'On');

include_once("App/Core/passGen.php");
$gen = new PasswordGenerator(4, 4, 2, 5);
$res = $gen->generate();

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
  <form action="App/Auth/logout.php">
    <input type="submit" value="Log out">
  </form>

  <form action="index.php" method="post">
    <h2>Save a New Password</h2>

    <p>Name of a Website / Application:</p>
    <input type="text" required>

    <p>Specify how much of different symbols you want in a password:</p>
    <label for="upper">Uppercase letters: </label>
    <input type="number" name="upper" id="upper" min="0" max="15"><br>

    <label for="lower">Lowercase letters: </label>
    <input type="number" name="lower" id="lower" min="0" max="15"><br>

    <label for="numbers">Numbers: </label>
    <input type="number" name="numbers" id="numbers" min="0" max="15"><br>

    <label for="specials">Special characters: </label>
    <input type="number" name="specials" id="specials" min="0" max="15"><br>

    <input type="submit" value="Create a new Entry">
  </form>

  <h1><?php echo "$res" ?></h1>
</body>

</html>
