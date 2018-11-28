<?php

namespace App\Controllers;

use PsrHttpMessageServerRequestInterface as Request;
use PsrHttpMessageResponseInterface as Response;

class NewsController{
    private $container;
    private $db;

    public function __construct($container, $db)
    {
        $this->container = $container;
        $this->db = $db;
    }

    public function index($request, $response, $args){
        return $response->write('Hola Mundo!');
    }
}