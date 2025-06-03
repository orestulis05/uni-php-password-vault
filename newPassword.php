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
	<title>New Password Entry - <?php echo "$user_email" ?></title>
	<link rel="stylesheet" href="style.css">

	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
</head>

<body>
	<div class="wrap">
		<form action="newPassword.html" method="post">
			<h2>Save a New Password</h2>

			<label for="title">Name of a Website / Application:</label>
			<input id="title" type="text" class="form-control" required>

			<p>Specify how much of different symbols you want in a password:</p>
			<label for="upper">Uppercase letters: </label>
			<input class="form-range" type="range" name="upper" id="upper" min="0" max="15"><br>

			<label for="lower">Lowercase letters: </label>
			<input class="form-range" type="range" name="lower" id="lower" min="0" max="15"><br>

			<label for="numbers">Numbers: </label>
			<input class="form-range" type="range" name="numbers" id="numbers" min="0" max="15"><br>

			<label for="specials">Special characters: </label>
			<input class="form-range" type="range" name="specials" id="specials" min="0" max="15"><br>

			<input class="btn btn-primary" type="submit" value="Create a new Entry">
		</form>
	</div>
</body>

</html>
