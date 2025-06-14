<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 'On');

require_once __DIR__ . "/../Auth/authCheck.php";
require_once __DIR__ . "/../Core/database.php";
require_once __DIR__ . "/../Utils/aes.php";

session_start();
redirect_unauthorized("../../authPage.php");

$id = -1;
$user_secret = "";

$original_title = "";
$raw_original_password = "";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  if (!isset($_GET["id"])) {
    header("Location: ../../index.php");
    exit;
  }

  $id = $_GET["id"];

  $query = "SELECT * FROM entries INNER JOIN users ON entries.user_id = users.user_id WHERE entries.id = $id";
  $result = $db_conn->query($query);

  if (!$result) {
    header("Location: ../../index.php");
    exit;
  }

  $row = $result->fetch_assoc();

  $original_title = $row["title"];

  // Get original pass 
  $user_secret = $row["secret"];
  $decrypter = new AESCrypt($user_secret);
  $raw_original_password = $decrypter->decrypt($row["entry_pass"], base64_decode($row["iv"]));
} else {
  $new_title = $_POST["new_title"];
  $new_raw_password = $_POST["new_password"];
  $id = $_POST["id"];

  $query = "SELECT secret FROM entries INNER JOIN users ON entries.user_id = users.user_id WHERE entries.id = $id";
  $result = $db_conn->query($query);

  if (!$result) {
    header("Location: ../../index.php");
    exit;
  }

  $row = $result->fetch_assoc();

  // Encrypt new password
  $aes = new AESCrypt($row["secret"]);
  $cipher_iv = $aes->encrypt($new_raw_password);

  $cipher = $cipher_iv[0];
  $iv = base64_encode($cipher_iv[1]);

  $query = "UPDATE entries SET title = '$new_title', entry_pass = '$cipher', iv = '$iv' WHERE id = $id";
  if (!$db_conn->query($query)) {
    exit;
  }

  header("Location: ../../index.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Vault - Edit</title>
  <link rel="stylesheet" href="../../style.css">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="wrap">
    <form action="editEntry.php" method="post">
      <h1>Edit Entry</h1>

      <!-- Hidden ID post -->
      <input style="display: none;" id="id" type="number" name="id" value="<?php echo $id; ?>" readonly><br>

      <label for="title">Title</label>
      <input id="title" type="text" class="form-control" name="new_title" placeholder="New title" value="<?php echo $original_title; ?>" required minlength="1"><br>

      <label for="password">Password</label>
      <input id="password" type="text" class="form-control" name="new_password" placeholder="New password" value="<?php echo $raw_original_password; ?>" required minlength="1"><br>

      <input type="submit" class="btn btn-primary" value="Confirm changes">
    </form>
  </div>
</body>

</html>
