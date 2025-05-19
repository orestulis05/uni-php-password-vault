<?php
require_once("dotenv.php");

const AES_ALGO = "aes-256-cbc";

class AESCrypt
{
  private $_key;
  private $_iv_length;
  private $_options;

  function __construct(string $key)
  {
    $this->_key = $key;
    $this->_iv_length = openssl_cipher_iv_length(AES_ALGO);
    $this->_options = 0;
  }

  // returns array
  // array[0] is cipher
  // array[1] is iv
  public function encrypt(string $data): array|false
  {
    $iv = openssl_random_pseudo_bytes($this->_iv_length);
    if (!$iv) return false;

    $ciphertext = openssl_encrypt($data, AES_ALGO, $this->_key, $this->_options, $iv);
    if (!$ciphertext) return false;

    $results = array();
    $results[0] = $ciphertext;
    $results[1] = $iv;

    return $results;
  }

  // returns decrypted string from cipher and iv
  public function decrypt(string $cipher, string $iv): string|false
  {
    $decrypted = openssl_decrypt($cipher, AES_ALGO, $this->_key, $this->_options, $iv);
    if (!$decrypted) return false;

    return $decrypted;
  }
}
