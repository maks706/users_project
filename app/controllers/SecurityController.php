<?php
namespace App\controllers;
use League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;
use App\QueryBuilder;
class SecurityController{
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
                echo $template->render('security',['email'=>$data['email']]);    
            }else{
                flash()->error('You can update only your account');
                header("Location:/users");
            }
            
        }else{
            flash()->error("You are not logged in");
            header("Location:/login");
        }
    }public function post($id){
        $emails=$this->query->getEqualEmails("users",$_POST['email']);
        if( (count($emails)==1 && $emails['id']==$id['id']) || count(emails)==0 ){
            if($_POST['newPassword']==$_POST['newPassword2']){
                $this->query->update("users",['email'=>$_POST['email']],$id['id']);
                try {
                    $this->auth->admin()->changePasswordForUserById($id['id'], $_POST['newPassword']);
                }
                catch (\Delight\Auth\UnknownIdException $e) {
                    die('Unknown ID');
                }
                catch (\Delight\Auth\InvalidPasswordException $e) {
                    die('Invalid password');
                }
                flash()->success("User succesfully updated");
                header("Location:/profile/".$id['id']);
            }else{
                flash()->error("Passwords are not equal");
                header("Location:/security/".$id['id']);
            }
        }else{
            flash()->error("User with this email already exist");
            header("Location:/security/".$id['id']);
        }
    }
}