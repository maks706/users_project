<?php
namespace App\controllers;
class LogOutController{
    public function get(){
        $db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $auth = new \Delight\Auth\Auth($db);
        $auth->logOut();
        header("Location:/login");
    }
}