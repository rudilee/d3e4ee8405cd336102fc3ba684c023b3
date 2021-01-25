<?php

/**
 * Auth Token Repository
 * Authentication token repository
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

use DEOTransCodeChallenge\Entities\AuthCodeEntity;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;

/**
 * Authentication token repository.
 * 
 * @category Repository
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class AuthCodeRepository implements AuthCodeRepositoryInterface
{
    /**
     * Persists a new auth code to permanent storage.
     *
     * @param AuthCodeEntityInterface $authCodeEntity 
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     * 
     * @return void
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity)
    {
    }

    /**
     * Revoke an auth code.
     *
     * @param string $codeId 
     * 
     * @return void
     */
    public function revokeAuthCode($codeId)
    {
    }

    /**
     * Check if the auth code has been revoked.
     *
     * @param string $codeId 
     *
     * @return bool Return true if this code has been revoked
     */
    public function isAuthCodeRevoked($codeId)
    {
        return false;
    }

    /**
     * Creates a new AuthCode
     *
     * @return AuthCodeEntityInterface
     */
    public function getNewAuthCode()
    {
        return new AuthCodeEntity;
    }
}
