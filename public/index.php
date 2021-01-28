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

use Bernard\Message\DefaultMessage;
// use DEOTransCodeChallenge\Entities\UserEntity;
use DEOTransCodeChallenge\Factories\DatabaseFactory;
use DEOTransCodeChallenge\Factories\JobQueueFactory;
use DEOTransCodeChallenge\Helpers\JsonWebToken;
use DEOTransCodeChallenge\Middlewares\JwtAuthMiddleware;
use Laminas\Diactoros\Response\JsonResponse;
// use DEOTransCodeChallenge\Factories\OAuthServerFactory;
// use DEOTransCodeChallenge\Middlewares\ResourceMiddleware;
// use Laminas\Diactoros\Response;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
// use League\OAuth2\Server\Exception\OAuthServerException;
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

// Gak jadi pakai OAuth2
// ================
// $router->map(
//     'GET',
//     '/authorize',
//     function (ServerRequestInterface $request): ResponseInterface {
//         $server = OAuthServerFactory::createAuthorizationServer();

//         try {
//             $authRequest = $server->validateAuthorizationRequest($request);
//             $authRequest->setUser(new UserEntity);
//             $authRequest->setAuthorizationApproved(true);

//             return $server->completeAuthorizationRequest($authRequest, new Response);
//         } catch (OAuthServerException $exception) {
//             return $exception->generateHttpResponse(new Response);
//         } catch (Exception $exception) {
//             return (new OAuthServerException(
//                 $exception->getMessage(),
//                 0,
//                 'unknown_error',
//                 500
//             ))->generateHttpResponse(new Response);
//         }
//     }
// );

// $router->map(
//     'POST',
//     '/access_token',
//     function (ServerRequestInterface $request): ResponseInterface {
//         $server =  OAuthServerFactory::createAuthorizationServer();

//         try {
//             return $server->respondToAccessTokenRequest($request, new Response);
//         } catch (OAuthServerException $exception) {
//             return $exception->generateHttpResponse(new Response);
//         } catch (Exception $exception) {
//             return (new OAuthServerException(
//                 $exception->getMessage(),
//                 0,
//                 'unknown_error',
//                 500
//             ))->generateHttpResponse(new Response);
//         }
//     }
// );
// ================

$jsonStrategy = new JsonStrategy(new ResponseFactory);

$router->map(
    'POST',
    '/login',
    function (ServerRequestInterface $request): ResponseInterface {
        $params = $request->getParsedBody();
        if (empty($params)) {
            return new JsonResponse(['error' => 'Empty parameters'], 400);
        }

        if (!key_exists('username', $params) || !key_exists('password', $params)) {
            return new JsonResponse(['error' => 'Mandatory parameters invalid'], 400);
        }

        $selectPassword = DatabaseFactory::createConnection()->prepare(
            'SELECT password FROM users WHERE username = :username'
        );

        $selectPassword->execute([':username' => $params['username']]);

        $result = $selectPassword->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            if (password_verify($params['password'], $result['password'])) {
                $token = JsonWebToken::getInstance()->encode(
                    ['username' => $params['username']]
                );

                return new JsonResponse(
                    ['message' => 'Login success'],
                    200,
                    ['X-API-KEY' => $token]
                );
            }
        }

        return new JsonResponse(['message' => 'Login failed'], 401);
    }
);

$router->group(
    '/api',
    function ($router) {
        $router->map(
            'GET',
            '/emails',
            function (): ResponseInterface {
                return new JsonResponse(
                    DatabaseFactory::createConnection()
                        ->query('SELECT * FROM emails')
                        ->fetchAll(PDO::FETCH_ASSOC)
                );
            }
        );

        $router->map(
            'POST',
            '/emails',
            function (ServerRequestInterface $request): ResponseInterface {
                $params = $request->getParsedBody();
                if (empty($params)) {
                    return new JsonResponse(['error' => 'Empty parameters'], 400);
                }

                if (!key_exists('sender', $params) || !key_exists('receiver', $params) || !key_exists('subject', $params) || !key_exists('message', $params)) {
                    return new JsonResponse(['error' => 'Mandatory parameters invalid'], 400);
                }

                $conn = DatabaseFactory::createConnection();
                $insertEmails = $conn->prepare(
                    'INSERT INTO emails (' .
                        'sender, receiver, subject, message' .
                        ') VALUES (' .
                        ':sender, :receiver, :subject, :message' .
                        ')'
                );

                $insertEmails->execute(
                    [
                        ':sender' => $params['sender'],
                        ':receiver' => $params['receiver'],
                        ':subject' => $params['subject'],
                        ':message' => $params['message']
                    ]
                );

                $params['id'] = $conn->lastInsertId('emails_id_seq');
                JobQueueFactory::createProducer()->produce(
                    new DefaultMessage('SendEmail', $params)
                );

                return new JsonResponse(['message' => 'Email inserted']);
            }
        );
    }
)
    // ->middleware(new ResourceMiddleware)
    ->middleware(new JwtAuthMiddleware);

$response = $router->dispatch($request);

(new SapiEmitter)->emit($response);
