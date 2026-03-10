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
        $stat=$this->employe->affichage();
        return $stat;
    }
    public function delete($matricule){
         
        if(!$matricule){
            return ["message"=>"pas de matricule"];
        }
        $username = $_SESSION['username'] ?? 'Utilisateur Inconnu';
        $deleted = $this->employe->supprimer($username,$matricule);

        if($deleted>0){
            echo json_encode(["success"=> true,
                   "message"=> "supprimé avec success ", 
                   "username"=> $username ]);
        }
        exit;

    }
    public function store(){
        $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (!$data) {
        http_response_code(400);
        return ["message" => "Données JSON invalides ou vides"];
    }

        $this->employe->matricule = $data['matricule'];
        $this->employe->nom = $data['nom'];
        $this->employe->salaire = $data['salaire'];

        $username = $_SESSION['username'] ?? 'Utilisateur Inconnu';

       try {
        if($this->employe->create($username)){
            http_response_code(201); // Code "Created"
            return ["message" => "Employé ajouté avec succès "  .$username];
        } else {
            return ["message" => "L'insertion a échoué (vérifiez vos contraintes BDD)"];
        }
    } catch (Exception $e) {
        http_response_code(500);
        return ["message" => "Erreur SQL : " . $e->getMessage()];
    }
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
        echo json_encode([
            "success"=>true,
            "username"=>$username
        ]);
        exit;
    }   

    return ["message" => "Erreur lors de la mise à jour"];
}
}


?>