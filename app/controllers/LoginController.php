<?php
namespace App\controllers;
if( !session_id() ) @session_start();

use League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;
class LoginController{
    private $db;
    private $auth;
    public function  __construct(){
        $this->db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $this->auth = new \Delight\Auth\Auth($this->db);
    }
    public function get(){
        $this->auth->logOut();
        echo $this->auth->getUserId();
        $template=new Engine("../app/views");
        echo $template->render('page_login');
    }public function post(){
        
        try {
            $this->auth->login($_POST['email'], $_POST['password']);
            header("Location:/users");
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            
            flash()->error("Wrong email address");
            header("Location:/login");
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            flash()->error("Wrong password");
            header("Location:/login");
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            
            flash()->error("emailnot verified");
            header("Location:/login");
            
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            flash()->error("Too many requests");
            header("Location:/login");
        }
    
    }
}