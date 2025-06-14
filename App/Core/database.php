<?php
require_once __DIR__ . "/../Utils/dotenv.php";

$db_host = get_dotenv_value("MYSQL_DB_HOST");
$db_user = get_dotenv_value("MYSQL_DB_USERNAME");
$db_pass = get_dotenv_value("MYSQL_DB_PASSWORD");
$db_name = get_dotenv_value("MYSQL_DB_NAME");

if (is_bool($db_host) || is_bool($db_user) || is_bool($db_name)) {
  echo "<p>db env vars are not set up correctly.</p>";
  exit;
}

// if no pass was set in .env file, default it to empty string
if (is_bool($db_pass)) {
  $db_pass = "";
}

try {
  if (!$db_conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name)) {
    die("db connection failed");
  }
} catch (mysqli_sql_exception) {
  echo "<p>db connection failed.</p>";
}
