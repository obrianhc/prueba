<?php

namespace App\Controllers;

use PsrHttpMessageServerRequestInterface as Request;
use PsrHttpMessageResponseInterface as Response;

class AdminController{
    private $container;
    private $db;
    private $lang;

    public function __construct($container, $db)
    {
        $this->container = $container;
        $this->lang = $container->dictionary;
        $this->db = $db;
    }

    public function admin($request, $response, $args){
        return $response->withRedirect('/cms/'.$this->lang['lang'].'/dashboard');
    }

    public function index($request, $response, $args){
        $data = array(
            "username" => $_SESSION['username'],
            "email" => $_SESSION['email'],
            "id_user" => $_SESSION['id_user'],
            "diccionario" => $this->lang
        );
        $this->container->view->render($response, 'internal/dashboard.twig', $data);
    }

    public function newpost($request, $response, $args){
        $data = array(
            "username" => $_SESSION['username'],
            "email" => $_SESSION['email'],
            "id_user" => $_SESSION['id_user'],
            "diccionario" => $this->lang
        );
        $this->container->view->render($response, 'internal/editor.twig', $data);
    }

    public function posts($request, $response, $args){
        $data = array(
            "username" => $_SESSION['username'],
            "email" => $_SESSION['email'],
            "id_user" => $_SESSION['id_user'],
            "diccionario" => $this->lang
        );
        $this->container->view->render($response, 'internal/posts.twig', $data);
    }

    public function categories($request, $response, $args){
        $data = array(
            "username" => $_SESSION['username'],
            "email" => $_SESSION['email'],
            "id_user" => $_SESSION['id_user'],
            "diccionario" => $this->lang
        );
        $this->container->view->render($response, 'internal/categories.twig', $data);
    }
}