<?php

namespace App\Models;

class Usuario{
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function auth($email, $password){
        $result = $this->db->table('cms_user')
                                ->select(array("id_user", "name", "email"))
                                ->where(array("email"=>$email, "password"=>$password))->get();
        return $result;
    }

    public function add($name, $email, $password){
        $result = $this->db->table('cms_user')->insert(array("name" => $name, "email" => $email, "password" => $password));
        return $result;
    }

    public function updatePassword($id, $lastPassword, $newPassword){
        $result = $this->db->table('cms_user')->select(array("password"))->where(array("id_user"=>$id))->get();
        
    }
}