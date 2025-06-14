<?php
require_once __DIR__ . "/../Auth/authCheck.php";
require_once __DIR__ . "/../Core/database.php";
require_once __DIR__ . "/../Utils/aes.php";
require_once __DIR__ . "/../Models/User.php";
require_once __DIR__ . "/../Models/PasswordEntry.php";

session_start();
redirect_unauthorized("../../authPage.php");

$current_user = User::CreateObjectFromTable($db_conn, $_SESSION["session_user"]);
if (!$current_user) {
  exit;
}

$id = 0;
$user_secret = $current_user->getSecret();
$original_title = "";
$raw_original_password = "";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  if (!isset($_GET["id"])) {
    header("Location: ../../index.php");
    exit;
  }

  $id = $_GET["id"];

  $entry = PasswordEntry::CreateObjectFromTable($db_conn, $id);
  if (!$entry) {
    exit;
  }

  $original_title = $entry->getTitle();

  // Get original pass 
  $decrypter = new AESCrypt($current_user->getSecret());
  $raw_original_password = $decrypter->decrypt($entry->getEntryPass(), $entry->getIv());
} else {
  $new_title = $_POST["new_title"];
  $new_raw_password = $_POST["new_password"];
  $id = $_POST["id"];

  $entry = PasswordEntry::CreateObjectFromTable($db_conn, $id);
  if (!$entry) {
    exit;
  }

  $entry->setTitle($new_title);
  $entry->setNewPassword($current_user->getSecret(), $new_raw_password);

  $entry->UpdateInDB($db_conn);

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
