<?php
use Slim\Http\Request;
use Slim\Http\Response;
use Psr\Log\LoggerInterface ;
use Slim\Container;
use App\Models;

$app->post('/interactuar', 'PublicController:interactuar')->setName('interactuar');