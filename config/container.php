<?php

use Slim\Container;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Connection;
use App\Controllers;

/** @var \Slim\App $app */
$container = $app->getContainer();

// Activating routes in a subfolder
$container['environment'] = function () {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $_SERVER['SCRIPT_NAME'] = dirname(dirname($scriptName)) . '/' . basename($scriptName);
    return new Slim\Http\Environment($_SERVER);
};

$container['view'] = function (Container $container) {
    $settings = $container->get('settings');
    $viewPath = $settings['twig']['path'];

    $twig = new \Slim\Views\Twig($viewPath, [
        'cache' => $settings['twig']['cache_enabled'] ? $settings['twig']['cache_path'] : false
    ]);

    /** @var Twig_Loader_Filesystem $loader */
    $loader = $twig->getLoader();
    $loader->addPath($settings['public'], 'public');

    // Instantiate and add Slim specific extension
    $router = $container->get('router');
    $uri = \Slim\Http\Uri::createFromEnvironment($container->get('environment'));
    $twig->addExtension(new \Slim\Views\TwigExtension($router, $uri));

    return $twig;
};

$twigEnvironment = $container['view'];
$availableLang  = ['en', 'es'];
$defaultLang = 'es';

// Activando el log
$container['logger'] = function (Container $container) {
    $settings = $container->get('settings');
    $logger = new Logger($settings['logger']['name']);
    
    $level = $settings['logger']['level'];
    if (!isset($level)) {
        $level = Logger::ERROR;
    }
    
    $logFile = $settings['logger']['file'];
    $handler = new RotatingFileHandler($logFile, 0, $level, true, 0775);
    $logger->pushHandler($handler);
    
    return $logger;
};

//conexion a la base de datos
$container['pdo'] = function (Container $container) {
    $settings = $container->get('settings');
    
    $host = $settings['db']['host'];
    $dbname = $settings['db']['database'];
    $username = $settings['db']['username'];
    $password = $settings['db']['password'];
    $charset = $settings['db']['charset'];
    $collate = $settings['db']['collation'];
    
    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE $collate"
    ];

    return new PDO($dsn, $username, $password, $options);
};

// Configurando la ejecucion de querys
$container['db'] = function (Container $container) {
    $settings = $container->get('settings');
    
    $config = [
        'driver' => 'mysql',
        'host' => $settings['db']['host'],
        'database' => $settings['db']['database'],
        'username' => $settings['db']['username'],
        'password' => $settings['db']['password'],
        'charset'  => $settings['db']['charset'],
        'collation' => $settings['db']['collation'],
    ];
    
    $factory = new ConnectionFactory(new \Illuminate\Container\Container());
    
    return $factory->make($config);
};

$container['pdo'] = function (Container $container) {
    return $container->get('db')->getPdo();
};

$container['PublicController'] = function($container){
    return new Controllers\PublicController($container, $container->get('db'));
};

$container['AdminController'] = function($container){
    return new Controllers\AdminController($container, $container->get('db'));
};

$container['NewsController'] = function($container){
    return new Controllers\NewsController($container, $container->get('db'));
};