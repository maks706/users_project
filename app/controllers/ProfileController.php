<?php
namespace App\controllers;
use League\Plates\Engine;
use App\QueryBuilder;
class ProfileController{
    private $db;
    private $auth;
    private $query;
    public function __construct(){
        $this->query=new QueryBuilder();
        $this->db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $this->auth = new \Delight\Auth\Auth($this->db);
    }
    public function get($id){
        if($this->auth->isLoggedIn() /*&& $this->auth->hasRole(\Delight\Auth\Role::ADMIN)*/){
            $template=new Engine("../app/views");
            $data=$this->query->getOne('users',$id['id']);
            echo $template->render('page_profile',['data'=>$data]);
        }else{
            flash()->error("You are not admin or not logged in");
            header("Location:/login");
        }
    }
}
?>