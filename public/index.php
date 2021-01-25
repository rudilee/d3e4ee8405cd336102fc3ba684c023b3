<?php

/**
 * Front Controller
 * Single controller to handles all the requests
 * php version 7.4
 * 
 * @category FrontController
 * @package  DEOTransCodeChallenge
 * @author   Rudi <rudi@kanayahijab.com>
 * @license  https://opensource.org/license/MIT MIT License
 * @link     https://github.com/rudilee/deotrans-code-chalenge
 */

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use DEOTransCodeChallenge\Entities\UserEntity;
use DEOTransCodeChallenge\Factories\OAuthServerFactory;
use DEOTransCodeChallenge\Middlewares\ResourceMiddleware;
use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$router = new Router;

$router->map(
    'GET',
    '/',
    function (): ResponseInterface {
        return new RedirectResponse('/client.html');
    }
);

$router->map(
    'GET',
    '/authorize',
    function (ServerRequestInterface $request): ResponseInterface {
        $server = OAuthServerFactory::createAuthorizationServer();

        try {
            $authRequest = $server->validateAuthorizationRequest($request);
            $authRequest->setUser(new UserEntity);
            $authRequest->setAuthorizationApproved(true);

            return $server->completeAuthorizationRequest($authRequest, new Response);
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
    }
);

$router->map(
    'POST',
    '/access_token',
    function (ServerRequestInterface $request): ResponseInterface {
        $server =  OAuthServerFactory::createAuthorizationServer();

        try {
            return $server->respondToAccessTokenRequest($request, new Response);
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
    }
);

$router->group(
    '/api',
    function ($router) {
        $router->map(
            'GET',
            '/emails',
            function (): array {
                return [];
            }
        );

        $router->map(
            'POST',
            '/emails',
            function (ServerRequestInterface $request): array {
                return [];
            }
        );
    }
)
    ->middleware(new ResourceMiddleware)
    ->setStrategy(new JsonStrategy(new ResponseFactory));

$response = $router->dispatch($request);

(new SapiEmitter)->emit($response);
