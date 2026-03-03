<?php
class Utilisateur{
    private $conn;
    private $table="utilisateur";
    
    public $username;
    public $password;
    public $role;
    public $id_utilisateur;

    public function __construct($db)
    {
        $this->conn =$db;
    }
    public function create(){
        $query = "INSERT INTO " .$this->table. "(username,password,role) VALUES (:username, :password, :role)";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(":username",$this->username);
        $statement->bindParam(":password",$this->password);
        $statement->bindParam(":role",$this->role);

        return $statement->execute();
    } 
    public function affichage(){
        $query = "SELECT* FROM ". $this->table;
        $statement = $this->conn->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function supprimer(){
        $query = "DELETE FROM " .$this->table. " WHERE id_utilisateur = :id_utilisateur";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(":id_utilisateur",$this->id_utilisateur);

        return $statement->execute();
    }
    public function findByUsername($username) {
    $query = "SELECT * FROM utilisateur WHERE username = :username LIMIT 1";
    $statement = $this->conn->prepare($query);
    $statement->bindParam(':username', $username);
    $statement->execute();
    return $statement->fetch(PDO::FETCH_ASSOC);
}

}



?>