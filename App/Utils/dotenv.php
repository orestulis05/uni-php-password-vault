<?php

define("DOTENV_FILEPATH", __DIR__ . "/../../.env");

function get_dotenv_value(string $key): string | false
{
  $env = parse_ini_file(DOTENV_FILEPATH);
  if (!is_array($env)) {
    echo "<p>.env file not found.</p>";
    return false;
  }

  $val = $env[$key];
  if (!is_string($val)) {
    echo "<p>.env key <b>{$key}</b> does not exist.</p>";
    return false;
  }

  if (!isset($val)) {
    echo "<p>.env key <b>{$key}</b> does not exist.</p>";
    return false;
  }

  return $val;
}
