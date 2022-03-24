<?php
namespace App\controllers;
use \Tamtamchik\SimpleFlash\Flash;
class DeleteController{
    private $db;
    private $auth;
    public function __construct(){
        $this->db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $this->auth = new \Delight\Auth\Auth($this->db);
    }public function get($id){
        if($this->auth->isLoggedIn()){
            if($this->auth->hasRole(\Delight\Auth\Role::ADMIN)){
                
                try {
                    $this->auth->admin()->deleteUserById($id['id']);
                    
                    
                    if($this->auth->getUserId()==$id['id']){
                        flash()->success("You deleted");
                        $this->auth->logOut();
                        header("Location:/");
                    }else{
                        flash()->success("User is deleted");
                        header("Location:/users");
                    }
                } 
                catch (\Delight\Auth\UnknownIdException $e) {
                    flash()->error('Unknown ID');
                    header("Location:/users");
                }
            }
            
            elseif($this->auth->getUserId()==$id['id']){
                $this->auth->admin()->deleteUserById($id['id']);
                flash()->success("You deleted");
                $this->auth->logOut();
                header("Location:/");
            }
            
            else{
                flash()->error("You can update only your account");
                header("Location:/users");
            }
            
        }else{
            flash()->error("You are not logged in");
            header("Location:/login");
        }
       
    }
}

?>