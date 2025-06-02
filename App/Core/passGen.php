<?php
// Parameters:
//  1. uppercase letter count
//  2. lowercase letter count
//  3. number count
//  4. special char count
class PasswordGenerator
{
  private $_c_uppercase;
  private $_c_lowercase;
  private $_c_nums;
  private $_c_special;

  function __construct($upper, $lower, $nums, $special)
  {
    $this->_c_uppercase = $upper;
    $this->_c_lowercase = $lower;
    $this->_c_nums = $nums;
    $this->_c_special = $special;
  }

  public function generate(): string
  {
    $result = "";

    $remaining_upper = $this->_c_uppercase;
    $remaining_lower = $this->_c_lowercase;
    $remaining_nums = $this->_c_nums;
    $remaining_special = $this->_c_special;

    do {
      $which = rand(1, 4);

      if ($remaining_upper > 0 && $which == 1) {
        $alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

        $len = strlen($alphabet);
        $char = $alphabet[rand(0, $len - 1)];
        $result = $result . $char;

        $remaining_upper -= 1;
      }

      if ($remaining_lower > 0 && $which == 2) {
        $alphabet = "abcdefghijklmnopqrstuvwxyz";

        $len = strlen($alphabet);
        $char = $alphabet[rand(0, $len - 1)];
        $result = $result . $char;

        $remaining_lower -= 1;
      }

      if ($remaining_nums > 0 && $which == 3) {
        $alphabet = "0123456789";

        $len = strlen($alphabet);
        $char = $alphabet[rand(0, $len - 1)];
        $result = $result . $char;

        $remaining_nums -= 1;
      }

      if ($remaining_special > 0 && $which == 4) {
        $alphabet = "!@#$%^&*()_";

        $len = strlen($alphabet);
        $char = $alphabet[rand(0, $len - 1)];
        $result = $result . $char;

        $remaining_special -= 1;
      }

      $remaining_all = $remaining_special + $remaining_lower + $remaining_upper + $remaining_nums;
    } while ($remaining_all > 0);

    return $result;
  }
}
