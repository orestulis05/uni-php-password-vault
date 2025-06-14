<?php

interface IDatabaseSaveable
{
  public function UpdateInDB(mysqli $conn);
}
