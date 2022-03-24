<?php
namespace App\controllers;
use League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;
use App\QueryBuilder;
use App\Image;
class CreateUserController{
    
    private $db;
    private $auth;

    public function __construct(){
        $this->db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $this->auth = new \Delight\Auth\Auth($this->db);
    }
    
    public function get(){
        if($this->auth->isLoggedIn() /*&& $this->auth->hasRole(\Delight\Auth\Role::ADMIN)*/){
            $template=new Engine("../app/views");
            echo $template->render('create_user');
            
            
        }else{
            flash()->error("You are not admin or not logged in");
            header("Location:/login");
            
        }

    }
    public function post(){
        $query=new QueryBuilder();
        var_dump($_POST);
        $avatar='img/demo/avatars/'.Image::upload_image($_FILES['avatar']);
        try {
            $userId = $this->auth->admin()->createUser($_POST['email'], $_POST['password'], $_POST['username']);
            echo $userId;
            //$this->auth->admin()->addRoleForUserById($userId, \Delight\Auth\Role::ADMIN);
            $query->update('users',
                ['status'=>$_POST['status'],
                'telnumber'=>$_POST['telnumber'],
                'address'=>$_POST['address'],
                'vkhref'=>$_POST['vkhref'],
                'telgramhref'=>$_POST['telgramhref'],
                'instahref'=>$_POST['instahref'],
                'avatar'=>$avatar,
                'speciality'=>$_POST['speciality']],$userId);
            
            
            flash()->success("User added");
            header("Location:/users");
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            flash()->error('Invalid email address');
            header("Loctaion:/createuser");
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error('Invalid password');
            header("Location:/createuser");
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            flash()->error('User already exists');
            header("Location:/createuser");
        }

    }
}