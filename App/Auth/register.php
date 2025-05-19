<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once("../Core/database.php");
require_once("../Utils/aes.php");

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $pwd = $_POST["pwd"];

  // Check if user already exists
  $query = "SELECT * FROM users WHERE email='$email'";
  $query_res = mysqli_query($db_conn, $query);
  if (!$query_res) {
    $_SESSION["registration_message"] = "Registration failed. Something went wrong.";
    header("Location: ../../authPage.php");
    exit();
  }

  if (mysqli_num_rows($query_res) > 0) {
    $_SESSION["registration_message"] = "Registration failed. User with this email already exists.";
    header("Location: ../../authPage.php");
    exit();
  }

  $aescrypt = new AESCrypt($pwd);
  $encryption_result = $aescrypt->encrypt("interesting key choice");
  $secret = $encryption_result[0];

  $hashed_pass = password_hash($pwd, PASSWORD_DEFAULT);

  $query = "INSERT INTO users (email,password,secret,name) VALUES ('$email','$hashed_pass','$secret','$name')";
  $query_res = mysqli_query($db_conn, $query);

  if (!$query_res) {
    $_SESSION["registration_message"] = "Registration failed. Something went wrong.";
    header("Location: ../../authPage.php");
    exit();
  }

  $_SESSION["registration_message"] = "Registration success. Please log in.";
  header("Location: ../../authPage.php");
}

mysqli_close($db_conn);
