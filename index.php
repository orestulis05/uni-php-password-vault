<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once __DIR__ . "/App/Core/database.php";
require_once __DIR__ . "/App/Auth/authCheck.php";
require_once __DIR__ . "/App/Core/passGen.php";
require_once __DIR__ . "/App/Utils/aes.php";
require_once __DIR__ . "/App/Models/PasswordEntry.php";
require_once __DIR__ . "/App/Models/User.php";

$DIR = __DIR__;

session_start();
redirect_unauthorized();

$current_user = User::CreateObjectFromTable($db_conn, $_SESSION["session_user"]);
if (!$current_user) {
  exit;
}

$password_entries = PasswordEntry::getAllUserPasswords($db_conn, $_SESSION["session_user"]);
if (!$password_entries) {
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Vault - <?php echo $current_user->getEmail(); ?></title>
  <link rel="stylesheet" href="style.css">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="wrap">
    <h1><b>Password Vault</b></h1>

    <form action="App/Auth/logout.php" class="user-info-form">
      <h2>Welcome <?php echo $current_user->getEmail(); ?></h2>
      <input type="submit" value="Log out" class="btn btn-danger">
    </form>

    <div class="table-container">
      <h2><b>List of Passwords</b></h2>
      <a href="newPassword.php" class="btn btn-primary" role="button">New Password</a>
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Password</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $decrypter = new AESCrypt($current_user->getSecret());

          foreach ($password_entries as $entry) {
            $id = $entry->getId();
            $title = $entry->getTitle();
            $created = $entry->getCreatedAt();

            $cipher = $entry->getEntryPass();
            $iv = $entry->getIv();
            $raw_password = $decrypter->decrypt($cipher, $iv);

            echo ("
              <tr>
                <td>" . $title . "</td>
                <td>" . $raw_password . "</td>
                <td>" . $created . "</td>
                <td>
                  <a href='/password-vault/App/CRUD/editEntry.php?id=$id' class='btn btn-primary btn-sm'>Edit</a>
                  <a href='/password-vault/App/CRUD/deleteEntry.php?id=$id' class='btn btn-danger btn-sm'>Delete</a>
                </td>
              </tr>
            ");
          }
          ?>
        </tbody>
      </table>
    </div>
  </div>
</body>

</html>
