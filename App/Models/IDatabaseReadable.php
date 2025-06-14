<?php

interface IDatabaseReadable
{
  static function CreateObjectFromTable(mysqli $conn, int $id);
}
