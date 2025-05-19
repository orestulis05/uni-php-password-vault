<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

require_once("../Core/database.php");
require_once("../Utils/aes.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $pwd = $_POST["pwd"];

  $aescrypt = new AESCrypt($pwd);
  $encryption_result = $aescrypt->encrypt("interesting key choice");
  $secret = $encryption_result[0];

  $hashed_pass = password_hash($pwd, PASSWORD_DEFAULT);

  $query = "INSERT INTO users (email,password,secret,name) VALUES ('$email','$hashed_pass','$secret','$name')";
  $query_res = mysqli_query($db_conn, $query);

  if (!$query_res) {
    // echo "db query failed.";
    die();
  }

  // echo "query success.";
}

mysqli_close($db_conn);
