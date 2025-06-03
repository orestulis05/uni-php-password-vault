<?php
require_once __DIR__ . "/../Core/database.php";
require_once __DIR__ . "/../Utils/aes.php";

session_start();

function registration_failure(string $message, $db_conn)
{
  $_SESSION["auth_message"] = "Registration failed. " . $message;
  mysqli_close($db_conn);
  header("Location: ../../authPage.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $pwd = $_POST["pwd"];

  // Check if user already exists
  $query = "SELECT * FROM users WHERE email='$email'";
  $query_res = mysqli_query($db_conn, $query);
  if (!$query_res) {
    registration_failure("Something went wrong", $db_conn);
  }

  if (mysqli_num_rows($query_res) > 0) {
    registration_failure("Email already in use", $db_conn);
  }

  $aescrypt = new AESCrypt($pwd);
  $encryption_result = $aescrypt->encrypt("interesting key choice");
  $secret = $encryption_result[0];

  $hashed_pass = password_hash($pwd, PASSWORD_DEFAULT);

  $query = "INSERT INTO users (email,password,secret,name) VALUES ('$email','$hashed_pass','$secret','$name')";
  $query_res = mysqli_query($db_conn, $query);

  if (!$query_res) {
    registration_failure("Something went wrong", $db_conn);
  }

  $_SESSION["auth_message"] = "Registration success. Please log in.";
  header("Location: ../../authPage.php");
}

mysqli_close($db_conn);
