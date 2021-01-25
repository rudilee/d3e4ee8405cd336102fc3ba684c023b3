<?php

/**
 * Resource Server Middleware
 * OAuth2 resource server middleware
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

use DEOTransCodeChallenge\Repositories\AccessTokenRepository;
use Exception;
use Laminas\Diactoros\Response;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * OAuth2 resource server middleware.
 * 
 * @category Middleware
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class ResourceMiddleware implements MiddlewareInterface
{
    /**
     * Create OAuth2 ResourceServer object with predefined public key location.
     * 
     * @return ResourceServer
     */
    public function createResourceServer(): ResourceServer
    {
        $publicKeyPath = 'file://' . __DIR__ . '/../../keys/public.key';
        $server = new ResourceServer(
            new AccessTokenRepository,
            $publicKeyPath
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
        $server = $this->createResourceServer();

        try {
            $request = $server->validateAuthenticatedRequest($request);
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

        return $handler->handle($request);
    }
}
