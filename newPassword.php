<?php

require_once __DIR__ . "/App/Auth/authCheck.php";
require_once __DIR__ . "/App/Core/passGen.php";
require_once __DIR__ . "/App/Core/database.php";
require_once __DIR__ . "/App/Utils/aes.php";

session_start();
redirect_unauthorized();

$user_email = $_SESSION["session_user_email"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$title = $_POST["title"];
	$upper = $_POST["upper"];
	$lower = $_POST["lower"];
	$numbers = $_POST["numbers"];
	$specials = $_POST["specials"];

	// TODO: CHECK IF A PASSWORD LENGTH WILL BE > 0 

	$passgen = new PasswordGenerator($upper, $lower, $numbers, $specials);
	$raw_password = $passgen->generate();

	$query = "SELECT user_id, secret FROM users WHERE email = '$user_email'";
	$result = $db_conn->query($query);

	if (!$result) {
		die("Invalid query: " . $db_conn->error);
	}

	$data = $result->fetch_assoc();

	$encryptor = new AESCrypt($data["secret"]);
	$cipher_iv = $encryptor->encrypt($raw_password);

	$user_id = $data["user_id"];
	$cipher = $cipher_iv[0];
	$iv = base64_encode($cipher_iv[1]);

	$query = "INSERT INTO entries (user_id, title, entry_pass, iv) VALUES ($user_id, '$title', '$cipher', '$iv')";
	$result = $db_conn->query($query);

	if (!$result) {
		mysqli_close($db_conn);
		die("Query failed: " . $db_conn->error);
	}

	mysqli_close($db_conn);
	echo "Success:)";
}

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
		<form action="newPassword.php" method="post">
			<h2>Save a New Password</h2>

			<label for="title">Name of a Website / Application</label>
			<input id="title" type="text" class="form-control" name="title" placeholder="My GitHub account" required minlength="1"><br>

			<label for="upper">Uppercase letters </label>
			<input class="form-control" type="number" name="upper" id="upper" min="1" max="15" value="5" required><br>

			<label for="lower">Lowercase letters </label>
			<input class="form-control" type="number" name="lower" id="lower" min="1" max="15" value="5" required><br>

			<label for="numbers">Numbers </label>
			<input class="form-control" type="number" name="numbers" id="numbers" min="1" max="15" value="5" required><br>

			<label for="specials">Special characters </label>
			<input class="form-control" type="number" name="specials" id="specials" min="1" max="15" value="5" required><br>

			<input class="btn btn-primary" type="submit" value="Create a new Entry">
		</form>
	</div>

	<script>
		// TODO: 
		// 1. generation refresh so user could see a password he will generate and then submit
		// 2. choice if you want to generate or add an existing password
	</script>
</body>

</html>
