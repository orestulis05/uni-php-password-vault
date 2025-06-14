<?php
require_once __DIR__ . "/IDatabaseReadable.php";

const USER_TABLE_NAME = "users";

class User implements IDatabaseReadable
{
  private $user_id = -1;
  private $email = "";
  private $password = "";
  private $secret = "";
  private $name = "";

  function __construct($user_id, $email, $password, $secret, $name)
  {
    $this->user_id = $user_id;
    $this->email = $email;
    $this->password = $password;
    $this->secret = $secret;
    $this->name = $name;
  }

  public function getId()
  {
    return $this->user_id;
  }

  public function getEmail()
  {
    return $this->email;
  }

  public function getPassword()
  {
    return $this->password;
  }

  public function getSecret()
  {
    return $this->secret;
  }

  public function getName()
  {
    return $this->name;
  }

  static function CreateObjectFromTable(mysqli $conn, $id)
  {
    $query = "SELECT * FROM " . USER_TABLE_NAME . " WHERE user_id = $id";
    $results = $conn->query($query);
    if (!$results) {
      return false;
    }

    $row = $results->fetch_assoc();
    $user = new User($row["user_id"], $row["email"], $row["password"], $row["secret"], $row["name"]);

    return $user;
  }
}
