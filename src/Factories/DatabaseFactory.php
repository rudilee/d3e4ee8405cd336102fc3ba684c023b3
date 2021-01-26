<?php

/**
 * Database Factory
 * Database connection creator
 * php version 7.4
 * 
 * @category Factory
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

namespace DEOTransCodeChallenge\Factories;

use PDO;
use PDOException;

/**
 * Database connection creator.
 * 
 * @category Factory
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class DatabaseFactory
{
    /**
     * Create PostgreSQL PDO connection object
     * 
     * @return PDO
     */
    public static function createConnection(): PDO
    {
        try {
            $parameters = [
                'host' => $_ENV['DB_HOSTNAME'],
                'dbname' => $_ENV['DB_NAME'],
                'user' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD']
            ];

            $dsnString = 'pgsql:' . implode(
                ';',
                array_map(
                    function ($value, $key) {
                        return "$key=$value";
                    },
                    $parameters,
                    array_keys($parameters)
                )
            );

            return new PDO($dsnString);
        } catch (PDOException $exception) {
            print $exception->getMessage();
            die();
        }
    }
}
