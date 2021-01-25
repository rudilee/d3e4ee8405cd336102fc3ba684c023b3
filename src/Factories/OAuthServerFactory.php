<?php

/**
 * OAuth Server Factory
 * OAuth2 authorization & resource server creator 
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

use DateInterval;
use DEOTransCodeChallenge\Repositories\AccessTokenRepository;
use DEOTransCodeChallenge\Repositories\AuthCodeRepository;
use DEOTransCodeChallenge\Repositories\ClientRepository;
use DEOTransCodeChallenge\Repositories\RefreshTokenRepository;
use DEOTransCodeChallenge\Repositories\ScopeRepository;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\ResourceServer;

/**
 * OAuth2 authorization & resource server creator.
 * 
 * @category Factory
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class OAuthServerFactory
{
    /**
     * Create OAuth2 AuthorizationServer object with predefined private key location
     * and all the required repositories objects.
     * 
     * @return AuthorizationServer
     */
    public static function createAuthorizationServer(): AuthorizationServer
    {
        $privateKeyPath = 'file://' . __DIR__ . '/../../keys/private.key';
        $server = new AuthorizationServer(
            new ClientRepository,
            new AccessTokenRepository,
            new ScopeRepository,
            $privateKeyPath,
            $_ENV['OAUTH2_STRING_PASSWORD']
        );

        $refreshTokenRepository = new RefreshTokenRepository;
        $server->enableGrantType(
            new AuthCodeGrant(
                new AuthCodeRepository,
                $refreshTokenRepository,
                new DateInterval('PT10M')
            ),
            new DateInterval('PT1H')
        );

        return $server;
    }

    /**
     * Create OAuth2 ResourceServer object with predefined public key location.
     * 
     * @return ResourceServer
     */
    public static function createResourceServer(): ResourceServer
    {
        $publicKeyPath = 'file://' . __DIR__ . '/../../keys/public.key';
        $server = new ResourceServer(
            new AccessTokenRepository,
            $publicKeyPath
        );

        return $server;
    }
}
