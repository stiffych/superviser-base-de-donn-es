<?php
class Employe{
    private $conn;
    private $table="employe";
    
    public $matricule;
    public $nom;
    public $salaire;

    public function __construct($db)
    {
        $this->conn =$db;
    }
    public function create($username){

        $this->conn->query("SET @current_user = '$username'");

        $query = "INSERT INTO " .$this->table. "(matricule,nom,salaire) VALUES (:matricule, :nom, :salaire)";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(":matricule",$this->matricule);
        $statement->bindParam(":nom",$this->nom);
        $statement->bindParam(":salaire",$this->salaire);

        return $statement->execute();
    } 
    public function affichage(){
        $query = "SELECT* FROM ". $this->table;
        $statement = $this->conn->prepare($query);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    public function supprimer($username){
        $stmtUser = $this->conn->prepare("SET @current_user = :user");
        $stmtUser->execute([':user' => $username]);

        $query = "DELETE FROM " .$this->table. " WHERE matricule = :matricule";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(":matricule",$this->matricule);

        return $statement->execute();
    }
    public function showByMatricule($id) {
        $stmt = $this->conn->prepare("SELECT * FROM employe WHERE matricule = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function update($username) {
        $stmtUser = $this->conn->prepare("SET @current_user = :user");
        $stmtUser->execute([':user' => $username]);
        $sql = "UPDATE employe SET nom=:n, salaire=:s WHERE matricule=:m";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':n'=>$this->nom, ':s'=>$this->salaire, ':m'=>$this->matricule]);
    }

}



?>