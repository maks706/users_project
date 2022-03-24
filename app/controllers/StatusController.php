<?php
namespace App\controllers;
use League\Plates\Engine;
use App\QueryBuilder;
class StatusController{
    private $db;
    private $auth;
    private $query;
    public function __construct(){
        $this->query=new QueryBuilder();
        $this->db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $this->auth = new \Delight\Auth\Auth($this->db);
    }
    public function get($id){
        if($this->auth->isLoggedIn()){
            if($this->auth->hasRole(\Delight\Auth\Role::ADMIN) || $this->auth->getUserId()==$id['id']){
                $template=new Engine("../app/views");
                $data=$this->query->getOne('users',$id['id']);
                echo $template->render('status',['status'=>$data['status1']]);    
            }else{
                flash()->error('You can update only your account');
                header("Location:/users");
            }
            
        }else{
            flash()->error("You are not logged in");
            header("Location:/login");
        }
    }
    public function post($id){

        $this->query->update("users",['status1'=>$_POST['status']],$id['id']);
        flash()->success("User succesfully updated");
        header("Location:/users");
    }
}