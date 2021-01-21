<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();

$routes->add('root', new Route('/', [
    '_controller' => function (Request $request) {
        return new Response('Halaman utama...');
    }
]));

$routes->add('hello', new Route('/hello/{name}', [
    '_controller' => function (Request $request) {
        return new Response(
            sprintf("Hello %s", $request->get('name'))
        );
    }
]));