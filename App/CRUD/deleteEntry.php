<?php
require_once __DIR__ . "/../Auth/authCheck.php";
require_once __DIR__ . "/../Core/database.php";

session_start();
redirect_unauthorized("../../authPage.php");

$id = -1;
$title = "";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  if (!isset($_GET["id"])) {
    header("Location: ../../index.php");
    exit;
  }

  $id = $_GET["id"];

  $query = "SELECT title FROM entries WHERE id = $id";
  $result = $db_conn->query($query);

  if (!$result) {
    header("Location: ../../index.php");
    exit;
  }

  $row = $result->fetch_assoc();

  $title = $row["title"];
} else {
  // POST 
  $id = $_POST["id"];

  $query = "DELETE FROM entries WHERE id = $id";

  if (!$db_conn->query($query)) {
    header("Location: ../../index.php");
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
    <form action="deleteEntry.php" method="post">
      <!-- Hidden ID post -->
      <input style="display: none;" id="id" type="number" name="id" value="<?php echo $id; ?>" readonly><br>

      <h1>Delete Entry</h1>
      <p>Are you sure you want to <b>DELETE</b> entry <b><?php echo $title; ?></b>?</p>

      <input type="submit" class="btn btn-danger" value="Confirm">
    </form>
  </div>
</body>

</html>
