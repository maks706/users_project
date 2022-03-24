<?php
namespace App\controllers;
if( !session_id() ) @session_start();

use League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;
class RegisterController{
    public function get(){
        $template=new Engine("../app/views");
        echo $template->render('page_register');
    }public function post(){
        
        $db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $auth = new \Delight\Auth\Auth($db);
        try {
            $userId = $auth->register($_POST['email'], $_POST['password'], function ($selector, $token) {
            });
            flash()->success('Success message!');
            header("Location:/login");
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            
            flash()->error('Invalid email address');
            header("Location:/");
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            
            flash()->error('Invalid password');
            header("Location:/");
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            
            flash()->error('UserAlredyExist');
            header("Location:/");
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            
            
            flash()->error('Too many requests');
            header("Location:/");
        }
    }
}
?>