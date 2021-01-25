<?php

/**
 * Access Token Repository
 * Access token repository
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

use DEOTransCodeChallenge\Entities\AccessTokenEntity;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;

/**
 * Access token repository.
 * 
 * @category Repository
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * Persists a new access token to permanent storage.
     *
     * @param AccessTokenEntityInterface $accessTokenEntity 
     *
     * @throws UniqueTokenIdentifierConstraintViolationException
     * 
     * @return void
     */
    public function persistNewAccessToken(
        AccessTokenEntityInterface $accessTokenEntity
    ) {
    }

    /**
     * Revoke an access token.
     *
     * @param string $tokenId 
     * 
     * @return void
     */
    public function revokeAccessToken($tokenId)
    {
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param string $tokenId 
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($tokenId)
    {
        return false;
    }

    /**
     * Create a new access token
     *
     * @param ClientEntityInterface  $clientEntity  
     * @param ScopeEntityInterface[] $scopes 
     * @param mixed                  $userIdentifier 
     *
     * @return AccessTokenEntityInterface
     */
    public function getNewToken(
        ClientEntityInterface $clientEntity,
        array $scopes,
        $userIdentifier = null
    ) {
        $accessToken = new AccessTokenEntity;
        $accessToken->setClient($clientEntity);

        foreach ($scopes as $scope) {
            $accessToken->addScope($scope);
        }

        $accessToken->setUserIdentifier($userIdentifier);

        return $accessToken;
    }
}
