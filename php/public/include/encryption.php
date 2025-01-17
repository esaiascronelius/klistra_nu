<?php
class Encryption
{
    private $cipher = "AES-256-GCM";
    private $key;
    private $iv;

    public function __construct($key)
    {
        $this->key = hash("sha256", $key, true);
    }

    public function encrypt($data)
    {
        $this->iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($this->cipher));
        $tag = "";
        $encrypted = openssl_encrypt(
            $data,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $this->iv,
            $tag
        );
        $encoded = base64_encode($encrypted . "::" . $this->iv . "::" . $tag);
        return $encoded;
    }

    public function decrypt($data)
    {
        $decoded = base64_decode($data);
        list($encrypted, $iv, $tag) = explode("::", $decoded, 3);
        $decrypted = openssl_decrypt(
            $encrypted,
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            $iv,
            $tag
        );
        return $decrypted;
    }
}
