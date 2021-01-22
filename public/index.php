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

use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Router;
use League\Route\Strategy\JsonStrategy;
use Psr\Http\Message\ServerRequestInterface;

$request = ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

$responseFactory = new ResponseFactory();
$strategy = new JsonStrategy($responseFactory);

$router = new Router();
$router->setStrategy($strategy);

$router->map(
    'GET',
    '/',
    function (ServerRequestInterface $request): array {
        return ['title' => 'Simple test API', 'version' => 1];
    }
);

$router->map(
    'GET',
    '/hello/world',
    function (ServerRequestInterface $request): array {
        return ['hello' => 'world'];
    }
);

$response = $router->dispatch($request);

(new SapiEmitter)->emit($response);
