<?php

namespace App\Models;

class Usuario{
    private $email;
    private $name;
    private $pass;
    private $app;

    public function __construct($email, $name, $pass, $enviroment){
        $this->email = $email;
        $this->pass = md5($pass);
        $this->app = $enviroment;
    }

    public function auth(){
        $result = $this->app->db->table('base_user')
                                ->select("name")
                                ->where(["email"=>$this->email], 
                                        ["password"=>$this->pass])->get();
        return $result;
    }

    public function add(){
        $result = $this->app->db->table('base_user')
                                ->insert(["name" => $this-name, 
                                        "email" => $this->email, 
                                        "password" => $this->password]);
        return $result;
    }
}