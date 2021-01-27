<?php

/**
 * JWT Auth Middleware
 * JSON Web Token bearer auth middleware
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

use DEOTransCodeChallenge\Helpers\JsonWebToken;
use Exception;
use Laminas\Diactoros\Response\EmptyResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * JSON Web Token bearer auth middleware.
 * 
 * @category Middleware
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

class JwtAuthMiddleware implements MiddlewareInterface
{

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
        $authHeaders = $request->getHeader('Authorization');
        if ($authHeaders) {
            [$type, $token] = explode(' ', $authHeaders[0]);

            try {
                JsonWebToken::getInstance()->decode($token);

                return $handler->handle($request);
            } catch (Exception $exception) {
                // fputs(STDERR, $exception->getMessage());
            }
        }

        return new JsonResponse(['message' => 'Failed authentication'], 401);
    }
}
