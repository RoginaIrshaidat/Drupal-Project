<?php

namespace Drupal\ddos_security\Services;

/**
 * Defines the Openssl encrypting/decryption service.
 */
class DdosCrypt {

  const CRYPT_CIPHER = 'AES-256-CBC';
  const CRYPT_IV = 'ALTR-JKSA89DJKDS8-SD8DSKJSD89SD--DSKLSDJKSD8-DIDOEKS739139EE4G3';
  const CRYPT_KEY = 'ALTR19CD23-2837LJK-IERCXM-SDKDS55-4FJKLDSFAS-DFASF23-REAF34SDF';

  /**
   * Function to encrypt a string using openssl.
   *
   * @param string $string
   *   String to encrypt.
   *
   * @return false|string
   *   Encrypted string.
   */
  public function encryptString(string $string) {
    $key = $this->getKey();
    $iv = $this->getIv();
    return base64_encode(openssl_encrypt($string, self::CRYPT_CIPHER, $key, 0, $iv));
  }

  /**
   * Function to decrypt a string using openssl.
   *
   * @param string $encryptedString
   *   String to decrypt.
   *
   * @return false|string
   *   Decrypted string.
   */
  public function decryptString(string $encryptedString) {
    $key = $this->getKey();
    $iv = $this->getIv();
    return openssl_decrypt(base64_decode($encryptedString), self::CRYPT_CIPHER, $key, 0, $iv);
  }

  /**
   * Function to get crypt key.
   *
   * @return string
   *   Return hashed string.
   */
  private function getKey() {
    return hash('sha256', self::CRYPT_KEY);
  }

  /**
   * Function to get IV key.
   *
   * @return string
   *   Return hashed string.
   */
  private function getIv() {
    return substr(hash('sha256', self::CRYPT_IV), 0, 16);
  }

}
