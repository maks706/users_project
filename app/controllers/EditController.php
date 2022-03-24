<?php
namespace App\controllers;
use League\Plates\Engine;
use \Tamtamchik\SimpleFlash\Flash;
use App\QueryBuilder;
class EditController{
    private $db;
    private $auth;
    public function __construct(){
        $this->db = new \PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $this->auth = new \Delight\Auth\Auth($this->db);
    }
    public function get($id){
        if($this->auth->isLoggedIn()){
            //if(/**&& $this->auth->hasRole(\Delight\Auth\Role::ADMIN) ||**/ $this->auth->getUserId()==$id){
                $template=new Engine("../app/views");
                $query=new QueryBuilder();
                $data=$query->getOne('users',$id['id']);
                echo $template->render('edit',['username'=>$data['username'],
                                            'speciality'=>$data['speciality'],
                                            'telnumber'=>$data['address'],
                                            'address'=>$data['address']
                                        ]);
                    
            
            /*}else{
                flash()->error("You can edit only your account");
                header("Location:/users");
            }*/
            
        }else{
            flash()->error("You not logged in");
            header("Location:/login");
            
        }
    }
    public function post($id){
        $query=new QueryBuilder();
        $query->update(
            'users',
            [
                'username'=>$_POST['username'],
                'speciality'=>$_POST['speciality'],
                'telnumber'=>$_POST['telnumber'],
                'address'=>$_POST['address']
            ],
            $id['id']
        );
        flash()->success("This profile success updated");
        header("Location:/profile/".$id['id']);
    }

}

?>