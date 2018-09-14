<?php
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Log\LoggerInterface ;
use Slim\Container;
use App\Models;

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

$app->post('/admin', function(Request $request, Response $response){
    
        $data = $request->getParsedBody();
        $usuario = new \App\Models\Usuario($data['email'], null, $data['password'], $this);
        $result = $usuario->auth();
        if($result != null){
            $_SESSION['username'] = $result;
            return $this->get('view')->render($response, 'system.twig', ['status'=>'ok', 'info'=>$result[0]->name]);
        } else {
            return $this->get('view')->render($response, 'login.twig', ['status', 'error']);
        }
    
});

$app->get('/admin', function(Request $request, Response $response){
    session_start();
    if(isset($_SESSION['username'])){
        return $this->get('view')->render($response, 'system.twig', ['status'=>'ok', 'info'=>$_SESSION['username']]);
    } else {
        return $this->get('view')->render($response, 'login.twig', ['status', 'error']);
    }
});

$app->get('/logout', function(Request $request, Response $response){
    session_start();
    session_destroy();
    return $this->get('view')->render($response, 'logout.twig', array());
});