<?php
namespace App;
use Aura\SqlQuery\QueryFactory;
use App\controllers\UsersControllers;
use PDO;
class QueryBuilder{
    private $pdo;
    private $queryFactory;
    public function __construct(){
        $this->pdo=new PDO('mysql:dbname=user;host=localhost;charset=utf8mb4', 'root', '');
        $this->queryFactory=new QueryFactory("mysql");
        
    }public function getAll($table){
        $select = $this->queryFactory->newSelect();
        $select->cols([
            '*'
        ]);
        $select->from($table);
        $sth=$this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }public function getOne($table,$id){
        $select = $this->queryFactory->newSelect();
        $select->from($table)
            ->cols([
            '*'])
            ->where("id=:id")
            ->bindValue('id',$id)
            ;
        var_dump($select->getBindValues());
        $sth=$this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetch(PDO::FETCH_ASSOC);
    }public function getEqualEmails($table,$email){
        $select = $this->queryFactory->newSelect();
        $select->from($table)
            ->cols([
            '*'])
            ->where("email=:email")
            ->bindValue('email',$email)
            ;
        $sth=$this->pdo->prepare($select->getStatement());
        $sth->execute($select->getBindValues());
        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }
    public function insert($table,$data){
        $insert = $this->queryFactory->newInsert();

        $insert->into($table)             // insert into this table
            ->cols($data);
        $sth = $this->pdo->prepare($insert->getStatement());

            // execute with bound values
        $sth->execute($insert->getBindValues());
            
            // get the last insert ID
         
    }public function update($table,$data,$id){
        $update = $this->queryFactory->newUpdate();

        $update->table($table)           // update this table
            ->cols($data)
            ->where('id=:id')
            ->bindValue('id',$id)
            ;
        var_dump($update->getBindValues());
        $sth = $this->pdo->prepare($update->getStatement());

        // execute with bound values
        $sth->execute($update->getBindValues());
     
    }public function delete($table,$id){
        $delete = $this->queryFactory->newDelete();
        $delete
            ->from($table)                   // FROM this table
            ->where('id = :id')
            ->bindValue('id',$id);
        $sth = $this->pdo->prepare($delete->getStatement());

            // execute with bound values
        $sth->execute($delete->getBindValues());

        var_dump($sth);
    }

}
?>