<?php
require_once("App/Auth/authCheck.php");

session_start();
redirect_unauthorized();

$user_email = $_SESSION["session_user_email"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>New Password Entry</title>
</head>

<body>
	<form action="newPassword.html" method="post">
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

</body>

</html>
