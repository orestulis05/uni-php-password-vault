<?php

function redirect_unauthorized(string $authPagePath = "authPage.php")
{
  $logged_in = isset($_SESSION["session_user"]);
  if (!$logged_in) {
    $_SESSION["auth_message"] = "Please log in.";
    header("Location: $authPagePath");
    exit();
  }
}

function redirect_authorized()
{
  // Session already exists -> redirect to main page.
  $logged_in = isset($_SESSION["session_user"]);
  if ($logged_in) {
    header("Location: index.php");
    exit();
  }
}
