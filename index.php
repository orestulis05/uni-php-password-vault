<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once __DIR__ . "/App/Core/database.php";
require_once __DIR__ . "/App/Auth/authCheck.php";
require_once __DIR__ . "/App/Core/passGen.php";
require_once __DIR__ . "/App/Utils/aes.php";

session_start();
redirect_unauthorized();

$user_email = $_SESSION["session_user_email"];

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Vault - <?php echo "$user_email" ?></title>
  <link rel="stylesheet" href="style.css">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">
</head>

<body>
  <div class="wrap">
    <h1>Password Vault</h1>
    <h2>Welcome <?php echo $user_email ?></h2>
    <form action="App/Auth/logout.php">
      <input type="submit" value="Log out" class="btn btn-danger">
    </form>

    <div class="table-container">
      <a href="newPassword.php" class="btn btn-primary" role="button">New Password Entry</a>
      <h2>List of Passwords</h2>
      <table class="table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Password</th>
            <th>Created At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $query = "SELECT email, secret, id, entry_pass, iv, title, created_at FROM entries INNER JOIN users ON entries.user_id = users.user_id WHERE email = '$user_email'";
          $result = $db_conn->query($query);

          if (!$result) {
            die("Invalid query: " . $db_conn->error);
          }

          while ($row = $result->fetch_assoc()) {
            $decrypter = new AESCrypt($row["secret"]);
            $raw_password = $decrypter->decrypt($row["entry_pass"], base64_decode($row["iv"]));

            echo ("
              <tr>
                <td>" . $row["title"] . "</td>
                <td>$raw_password</td>
                <td>" . $row["created_at"] . "</td>
                <td>
                  <a href='#' class='btn btn-primary btn-sm'>Edit</a>
                  <a href='#' class='btn btn-primary btn-sm'>Delete</a>
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
