<?php
declare(strict_types=1);

namespace App\Utils;

class Encryptor
{
    /**
     * Decrypts an encrypted string.
     *
     * @param string $string The encrypted string to be decrypted.
     * @param string $key    The encryption key. If not provided, the default key from the configuration will be used.
     *
     * @return string|false The decrypted string on success, false on failure.
     */
    public static function decrypt(string $string, string $key = ''): string|false
    {
        return self::encryptDecrypt($string, $key, 'DECODE');
    }

    /**
     * Encrypts or decrypts a string based on the specified operation.
     *
     * @param string $string       The string to be encrypted or decrypted.
     * @param string $key          The encryption key. If not provided, the default key from the configuration will be used.
     * @param string $operation    The operation to perform, either 'ENCODE' for encryption or 'DECODE' for decryption.
     * @param int    $expiry       The expiration time for the encrypted string (only applicable for encoding).
     * @param int    $c_key_length The length of the dynamic key, ensuring different ciphertext for the same plaintext.
     *
     * @return string|false The result of the encryption or decryption operation.
     */
    public static function encryptDecrypt(string $string, string $key = '', string $operation = 'ENCODE', int $expiry = 0, int $c_key_length = 4): string|false
    {
        // Key initialization
        $key = md5($key ?: config('app.jwt.jwt_salt'));
        $key_a = md5(substr($key, 0, 16));
        $key_b = md5(substr($key, 16, 16));
        $key_c = $c_key_length ? ($operation == 'DECODE' ? substr($string, 0, $c_key_length) : substr(md5(microtime()), -$c_key_length)) : '';
        // Cryptographic key generation
        $crypt_key = $key_a . md5($key_a . $key_c);
        $key_length = strlen($crypt_key);
        // String processing based on the operation
        $string = $operation == 'DECODE' ? base64_decode(substr($string, $c_key_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $key_b), 0, 16) . $string;
        $string_length = strlen($string);
        $result = '';
        $box = range(0, 255);
        $rnd_key = [];
        // Generation of cryptographic key table
        for ($i = 0; $i <= 255; $i++) {
            $rnd_key[$i] = ord($crypt_key[$i % $key_length]);
        }
        // Disruption of cryptographic key table for added randomness
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rnd_key[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        // Core encryption/decryption process
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            // Validate data integrity for decoding
            if ((substr($result, 0, 10) == 0 || (int)substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $key_b), 0, 16)) {
                return substr($result, 26);
            } else {
                return false;
            }
        } else {
            // Include dynamic key in the ciphertext, using base64 encoding for potential special characters
            return $key_c . str_replace('=', '', base64_encode($result));
        }
    }
}