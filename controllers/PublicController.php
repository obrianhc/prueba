<?php

namespace App\Controllers;

use PsrHttpMessageServerRequestInterface as Request;
use PsrHttpMessageResponseInterface as Response;
use App\Models\Usuario;

class PublicController{
    private $container;
    private $db;
    private $lang;

    public function __construct($container, $db)
    {
        $this->container = $container;
        $this->lang = $container->dictionary;
        $this->db = $db;
    }

    public function login($request, $response, $args){
        $data = array(
            "diccionario"=>$this->lang
        );
        $this->container->view->render($response, 'login.twig', $data);
    }

    public function auth($request, $response, $args){
        $data = $request->getParsedBody();
        $email = $data['email'];
        $password = md5($data['password']);
        $usuario = new Usuario($this->db);
        $result = $usuario->auth($email, $password);
        if(count($result)>0){
            $_SESSION['username'] = $result[0]->name;
            $_SESSION['id_user'] = $result[0]->id_user;
            $_SESSION['email'] = $result[0]->email;
            return $response->withRedirect('/cms/'.$this->lang['lang'].'/dashboard');
        } else {
            return $response->withRedirect('/cms/'.$this->lang['lang'].'login');
        }
    }

    public function logout($request, $response, $args){
        session_destroy();
        return $response->withRedirect('/cms/'.$this->lang['lang']);
    }
}