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
	$raw_password = "someRawPassword123";

	$choice = $_POST["choice"]; // generated or manual
	if ($choice === "generated") {
		$upper = $_POST["upper"];
		$lower = $_POST["lower"];
		$numbers = $_POST["numbers"];
		$specials = $_POST["specials"];

		$total = $upper + $lower + $numbers + $specials;
		if ($total <= 0) {
			header("Location: newPassword.php");
			exit;
		}

		$passgen = new PasswordGenerator($upper, $lower, $numbers, $specials);
		$raw_password = $passgen->generate();
	} else if ($choice === "manual") {
		$raw_password = $_POST["existing_password"];
		if (!isset($raw_password)) {
			header("Location: newPassword.php");
			exit;
		}
	}

	$query = "SELECT user_id, secret FROM users WHERE email = '$user_email'";
	$result = $db_conn->query($query);

	if (!$result) {
		header("Location: newPassword.php");
		exit;
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
		header("Location: newPassword.php");
		exit;
	}

	mysqli_close($db_conn);
	header("Location: index.php");
	exit;
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

			<label for="title" class="form-label">Name of a Website / Application</label>
			<input id="title" type="text" class="form-control" name="title" placeholder="My GitHub account" required minlength="1"><br>

			<label class="form-label"><b>How do you want to save the password?</b></label><br>
			<input type="radio" id="radio-generated" name="choice" value="generated" checked><label for="radio-generated">&ThickSpace;Generate a new password</label><br>
			<input type="radio" id="radio-manual" name="choice" value="manual"><label for="radio-manual">&ThickSpace;Add an existing password</label><br><br>

			<div id="generate-form">
				<label for="upper" class="form-label">Uppercase letters </label>
				<input class="form-control" type="number" name="upper" id="upper" min="0" max="15" value="5"><br>

				<label for="lower" class="form-label">Lowercase letters </label>
				<input class="form-control" type="number" name="lower" id="lower" min="0" max="15" value="5"><br>

				<label for="numbers" class="form-label">Numbers </label>
				<input class="form-control" type="number" name="numbers" id="numbers" min="0" max="15" value="5"><br>

				<label for="specials" class="form-label">Special characters </label>
				<input class="form-control" type="number" name="specials" id="specials" min="0" max="15" value="5"><br>
			</div>

			<div id="manual-form">
				<label for="existing-password" class="form-label">Password</label>
				<input type="text" class="form-control" id="existing-password" name="existing_password"><br>
			</div>

			<input class="btn btn-primary" type="submit" value="Create a new Entry">
		</form>
	</div>

	<script>
		const manualFormDiv = document.getElementById("manual-form");
		const generateFormDiv = document.getElementById("generate-form");
		manualFormDiv.style.display = "none";

		const onGenerateSelect = () => {
			manualFormDiv.style.display = "none";
			generateFormDiv.style.display = "block";

		}

		const onManualSelect = () => {
			manualFormDiv.style.display = "block";
			generateFormDiv.style.display = "none";
		}

		const generateRadioBtn = document.getElementById("radio-generated");
		const manualRadioBtn = document.getElementById("radio-manual");

		generateRadioBtn.addEventListener("change", (e) => onGenerateSelect());
		manualRadioBtn.addEventListener("change", (e) => onManualSelect());
	</script>
</body>

</html>
