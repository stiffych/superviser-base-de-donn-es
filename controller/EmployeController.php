<?php

require_once __DIR__ . "/../connexion/connexion.php";
require_once __DIR__ . "/../models/employe.php";

class EmployeController{
    private $employe;

    public function __construct()
    {
        $connexion = new Connexion();
        $db = $connexion->getConnection();
        $this->employe = new Employe($db);
    }

    public function index(){
        return $this->employe->affichage();
    }
    public function delete($matricule){
        if(!$matricule){
            return ["message"=>"pas de matricule"];
        }
        $username = $_SESSION['username'] ?? 'Utilisateur Inconnu';
        $deleted = $this->employe->supprimer($username,$matricule);

        if($deleted>0){
            return["message"=> "employé supprimé"];
        }

        return ["message"=>"erreur lors de la suprression"];

    }
    public function store(){
        $data = json_decode(file_get_contents("php://input"),true);

        $this->employe->matricule = $data['matricule'];
        $this->employe->nom = $data['nom'];
        $this->employe->salaire = $data['salaire'];

        $username = $_SESSION['username'] ?? 'Utilisateur Inconnu';

        if($this->employe->create($username)){
            return["message" =>"Employé ajouté avec succès"];
        }

        return ["message"=>"erreur lors de l'ajout"];
    }    
    public function update() {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['matricule'])) {
        http_response_code(400);
        return ["message" => "Matricule manquant pour la modification"];
    }
    $this->employe->matricule = $data['matricule'];
    $this->employe->nom = $data['nom'];
    $this->employe->salaire = $data['salaire'];

    $username = $_SESSION['username'] ?? 'Inconnu';

    if ($this->employe->update($username)) {
        return ["message" => "Employé mis à jour avec succès"];
    }

    return ["message" => "Erreur lors de la mise à jour"];
}
}


?>