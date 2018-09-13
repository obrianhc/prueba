<?php

use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Log\LoggerInterface;
use Slim\Container;

$app->get('/', function (Request $request, Response $response) {
    return $this->get('view')->render($response, 'login.twig', []);
})->setName('root');

$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});

$app->get('/time', function (Request $request, Response $response) {
    $viewData = [
        'now' => date('Y-m-d H:i:s')
    ];

    return $this->get('view')->render($response, 'time.twig', $viewData);
});

$app->get('/logger-test', function (Request $request, Response $response) {
    /** @var Container $this */
    /** @var LoggerInterface $logger */

    $logger = $this->get('logger');
    $logger->error('My error message!');

    $response->getBody()->write("Success");

    return $response;
});

// Generando una consulta
use Illuminate\Database\Connection;

$app->get('/databases', function (Request $request, Response $response) {
    /** @var Container $this */
    /** @var Connection $db */
    
    $db = $this->get('db');

    // fetch all rows as collection
    $rows = $db->table('information_schema.schemata')->get();

    // return a json response
    return $response->withJson($rows);
});