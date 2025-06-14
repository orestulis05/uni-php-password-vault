<?php
require_once __DIR__ . "/IDatabaseReadable.php";

const TABLE_NAME = "entries";

class PasswordEntry implements IDatabaseReadable
{
  private $id = -1;
  private $user_id = -1;
  private $title = "";
  private $entry_pass = "";
  private $iv = "";
  private $created_at = "";

  function __construct($id, $user_id, $title, $entry_pass, $iv, $created_at)
  {
    $this->id = $id;
    $this->user_id = $user_id;
    $this->title = $title;
    $this->entry_pass = $entry_pass;
    $this->iv = $iv;
    $this->created_at = $created_at;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getUserId()
  {
    return $this->user_id;
  }

  public function getTitle()
  {
    return $this->title;
  }

  public function getEntryPass()
  {
    return $this->entry_pass;
  }

  public function getIv()
  {
    return $this->iv;
  }

  public function getCreatedAt()
  {
    return $this->created_at;
  }

  static function CreateObjectFromTable(mysqli $conn, $id)
  {
    $query = "SELECT * FROM " . TABLE_NAME . " WHERE id = $id";
    $results = $conn->query($query);
    if (!$results) {
      return false;
    }

    $row = $results->fetch_assoc();

    $original_iv = base64_decode($row["iv"]);

    $entry = new PasswordEntry($row["id"], $row["user_id"], $row["title"], $row["entry_pass"], $original_iv, $row["created_at"]);
    return $entry;
  }

  static function getAllUserPasswords(mysqli $conn, $user_id)
  {
    $query = "SELECT * FROM " . TABLE_NAME . " WHERE user_id = $user_id";
    $results = $conn->query($query);
    if (!$results) {
      return false;
    }

    $passwords = [];

    while ($row = $results->fetch_assoc()) {
      $entry = new PasswordEntry($row["id"], $row["user_id"], $row["title"], $row["entry_pass"], $row["iv"], $row["created_at"]);
      array_push($passwords, $entry);
    }

    return $passwords;
  }
}
