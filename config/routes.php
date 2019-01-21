<?php
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Log\LoggerInterface ;
use Slim\Container;
use App\Models;

// Inicio de sesion
session_start();

// Agregando el middelware a la aplicacion

$app->add(new slim3_multilanguage\MultilanguageMiddleware([
    'availableLang' => $availableLang,
    'defaultLang' => $defaultLang,
    'twig' => $twigEnvironment->getEnvironment(),
    'container' => $container,
    'langFolder' => '../lang/'
]));

$app->add($loggedInMiddleware);

$app->get('/no-page-multilanguage-support', 'CALLED FONCTION');

$app->get('/', 'NewsController:index')->setName('index');

$app->group('/{lang:[a-z]{2}}', function () use ($container){
    // Sitio de noticias
    $this->get('', 'NewsController:index')->setName('index');

    // Autenticacion
    $this->get('/login', 'PublicController:login')->setName('login');
    $this->post('/post-login', 'PublicController:auth')->setName('post-login');
    $this->get('/logout', 'PublicController:logout')->setName('logout');

    // Sistema interno
    $this->get('/admin', 'AdminController:admin')->setName('admin');
    $this->get('/newpost', 'AdminController:newpost')->setName('newpost');
    $this->get('/dashboard', 'AdminController:index')->setName('dashboard');
    $this->get('/posts', 'AdminController:posts')->setName('posts');
    $this->get('/categories', 'AdminController:categories')->setName('categories');
});