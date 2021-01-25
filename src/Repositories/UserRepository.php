<?php

/**
 * User Repository
 * User repository
 * php version 7.4
 * 
 * @category Repository
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

namespace DEOTransCodeChallenge\Repositories;

use DEOTransCodeChallenge\Entities\UserEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * User repository.
 * 
 * @category Repository
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get a user entity.
     *
     * @param string                $username 
     * @param string                $password 
     * @param string                $grantType    The grant type used
     * @param ClientEntityInterface $clientEntity 
     *
     * @return UserEntityInterface|null
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        // Check for dummy user
        if ($username === 'user' && $password === 'pass123') {
            return new UserEntity;
        }
    }
}
