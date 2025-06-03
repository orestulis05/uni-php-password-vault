<?php
require_once __DIR__ . "/../Core/database.php";
require_once __DIR__ . "/../Utils/aes.php";

session_start();

function auth_failure(string $message, $db_conn)
{
  $_SESSION["auth_message"] = "Login failed. " . $message;
  mysqli_close($db_conn);
  header("Location: ../../authPage.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = $_POST["email"];
  $pwd = $_POST["pwd"];

  $query = "SELECT * FROM users WHERE email='$email'";
  $query_res = mysqli_query($db_conn, $query);
  if (!$query_res) {
    auth_failure("Something went wrong.", $db_conn);
  }

  // Check if user exists
  if (mysqli_num_rows($query_res) == 0) {
    auth_failure("User does not exist.", $db_conn);
  }

  $user = $query_res->fetch_assoc();

  // pass guard
  if (!password_verify($pwd, $user["password"])) {
    auth_failure("Password was incorrect.", $db_conn);
  }

  // TODO: redirect to main page after logging in


  $_SESSION["session_user_email"] = "$email";
  header("Location: ../../index.php");
}

mysqli_close($db_conn);
