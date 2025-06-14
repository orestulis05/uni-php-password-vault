<?php
require_once __DIR__ . "/IDatabaseReadable.php";
require_once __DIR__ . "/IDatabaseSaveable.php";

const ENTRIES_TABLE_NAME = "entries";

class PasswordEntry implements IDatabaseReadable, IDatabaseSaveable
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

  public function setNewPassword($secret, $raw)
  {
    $aes = new AESCrypt($secret);
    $cipher_iv = $aes->encrypt($raw);

    $cipher = $cipher_iv[0];
    $iv = $cipher_iv[1];

    $this->entry_pass = $cipher;
    $this->iv = $iv;
  }

  public function setTitle($title)
  {
    $this->title = $title;
  }

  static function CreateObjectFromTable(mysqli $conn, int $id)
  {
    $query = "SELECT * FROM " . ENTRIES_TABLE_NAME . " WHERE id = $id";
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
    $query = "SELECT * FROM " . ENTRIES_TABLE_NAME . " WHERE user_id = $user_id";
    $results = $conn->query($query);
    if (!$results) {
      return false;
    }

    $passwords = [];

    while ($row = $results->fetch_assoc()) {
      $original_iv = base64_decode($row["iv"]);

      $entry = new PasswordEntry($row["id"], $row["user_id"], $row["title"], $row["entry_pass"], $original_iv, $row["created_at"]);
      array_push($passwords, $entry);
    }

    return $passwords;
  }

  public function UpdateInDB(mysqli $conn)
  {
    $id = $this->id;
    $title = $this->title;
    $entry_pass = $this->entry_pass;
    $iv = base64_encode($this->iv);

    $query = "UPDATE " . ENTRIES_TABLE_NAME . " SET title = '$title', entry_pass = '$entry_pass', iv = '$iv' WHERE id = $id";
    if (!$conn->query($query)) {
      exit;
    }
  }
}
