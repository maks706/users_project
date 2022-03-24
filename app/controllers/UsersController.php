<?php
namespace App\controllers;
use League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;
use App\QueryBuilder;
class UsersController{
    public function get(){
        $db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $auth = new \Delight\Auth\Auth($db);
        $query=new QueryBuilder();
        $users=$query->getAll("users");
        //var_dump($users);
        if($auth->isLoggedIn()){
            
            $is_admin=false;
            if($auth->hasRole(\Delight\Auth\Role::ADMIN)){
                $is_admin=true;
            }
            $template=new Engine("../app/views");
            //$template->addData(['users'=>$users]);
            echo $template->render('users',['is_admin'=>$is_admin,'users'=>$users,'my_id'=>$auth->getUserId()]);
        }
        
        else{
            flash()->error("Log in");
            header("Location:/login");
        }
    }
}