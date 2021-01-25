<?php

/**
 * Refresh Token Repository
 * Refresh token repository
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

use DEOTransCodeChallenge\Entities\RefreshTokenEntity;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;

/**
 * Refresh token repository.
 * 
 * @category Repository
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class RefreshTokenRepository implements RefreshTokenRepositoryInterface
{
    /**
     * Create a new refresh token_name.
     *
     * @param RefreshTokenEntityInterface $refreshTokenEntity 
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     * 
     * @return void
     */
    public function persistNewRefreshToken(
        RefreshTokenEntityInterface $refreshTokenEntity
    ) {
    }

    /**
     * Revoke the refresh token.
     *
     * @param string $tokenId 
     * 
     * @return void
     */
    public function revokeRefreshToken($tokenId)
    {
    }

    /**
     * Check if the refresh token has been revoked.
     *
     * @param string $tokenId 
     *
     * @return bool Return true if this token has been revoked
     */
    public function isRefreshTokenRevoked($tokenId)
    {
        return false;
    }

    /**
     * Creates a new refresh token
     *
     * @return RefreshTokenEntityInterface|null
     */
    public function getNewRefreshToken()
    {
        return new RefreshTokenEntity;
    }
}
