<?php

/**
 * Authorization Server Middleware
 * OAuth2 authorization server middleware
 * php version 7.4
 * 
 * @category Middleware
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

namespace DEOTransCodeChallenge\Middlewares;

use DateInterval;
use DEOTransCodeChallenge\Repositories\AccessTokenRepository;
use DEOTransCodeChallenge\Repositories\AuthCodeRepository;
use DEOTransCodeChallenge\Repositories\ClientRepository;
use DEOTransCodeChallenge\Repositories\RefreshTokenRepository;
use DEOTransCodeChallenge\Repositories\ScopeRepository;
use Exception;
use Laminas\Diactoros\Response;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * OAuth2 authorization server middleware.
 * 
 * @category Middleware
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class AuthorizationMiddleware implements MiddlewareInterface
{
    /**
     * Create OAuth2 AuthorizationServer object with predefined private key location
     * and all the required repositories objects.
     * 
     * @return AuthorizationServer
     */
    public function createAuthorizationServer(): AuthorizationServer
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

        $server->enableGrantType(
            new RefreshTokenGrant($refreshTokenRepository),
            new DateInterval('P1M')
        );

        return $server;
    }

    /**
     * Process an incoming server request.
     * 
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     * 
     * @param ServerRequestInterface  $request 
     * @param RequestHandlerInterface $handler 
     * 
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {
        $server = $this->createAuthorizationServer();

        try {
            $response = $server->respondToAccessTokenRequest($request, new Response);
            $nextResponse = $handler->handle($request);

            $response->withBody($nextResponse->getBody());
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse(new Response);
        } catch (Exception $exception) {
            return (new OAuthServerException(
                $exception->getMessage(),
                0,
                'unknown_error',
                500
            ))->generateHttpResponse(new Response);
        }

        return $response;
    }
}
