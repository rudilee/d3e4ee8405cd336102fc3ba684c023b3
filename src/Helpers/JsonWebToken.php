<?php

/**
 * Json Web Token
 * JSON Web Token helper singleton
 * php version 7.4
 * 
 * @category Helpers
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

namespace DEOTransCodeChallenge\Helpers;

use Exception;
use Firebase\JWT\JWT;

/**
 * JSON Web Token helper singleton.
 * 
 * @category Helpers
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class JsonWebToken
{
    /**
     * Singleton instance
     */
    private static $_instance = [];

    /**
     * Private key text
     */
    private $_privateKey;

    /**
     * Public key text
     */
    private $_publicKey;

    /**
     * Prevent direct construction call
     */
    protected function __construct()
    {
        $this->_privateKey = file_get_contents(__DIR__ . '/../../keys/private.key');
        $this->_publicKey = file_get_contents(__DIR__ . '/../../keys/public.key');
    }

    /**
     * Make singleton not cloneable
     * 
     * @return void
     */
    protected function __clone()
    {
    }

    /**
     * Singleton should not be restorable from strings.
     * 
     * @return void
     */
    public function __wakeup()
    {
        throw new Exception('Can not unserialize this singleton.');
    }

    /**
     * Initiate singleton instance if not initiated yet
     * 
     * @return JsonWebToken
     */
    public static function getInstance(): JsonWebToken
    {
        $cls = static::class;
        if (!isset(self::$_instance[$cls])) {
            self::$_instance[$cls] = new static;
        }

        return self::$_instance[$cls];
    }

    /**
     * Encode JWT payload
     * 
     * @param $payload array
     * 
     * @return string
     */
    public function encode(array $payload): string
    {
        return JWT::encode($payload, $this->_privateKey, 'RS256');
    }

    /**
     * Decode JWT token
     * 
     * @param $token string
     * 
     * @return array
     */
    public function decode(string $token): array
    {
        return (array) JWT::decode($token, $this->_publicKey, ['RS256']);
    }
}
