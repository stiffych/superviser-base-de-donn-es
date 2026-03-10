<?php

require_once __DIR__ . "/../connexion/connexion.php";
require_once __DIR__ . "/../models/utilisateur.php";

class UtilisateurController{
    private $utilisateur;

    public function __construct()
    {
        $connexion = new Connexion();
        $db = $connexion->getConnection();
        $this->utilisateur = new utilisateur($db);
    }

    public function index(){
        return $this->utilisateur->affichage();
    }
    public function delete($id_utilisateur){
        if(!$id_utilisateur){
            return ["message"=>"pas d'ID pour cette utisateur"];
        }
        $this->utilisateur->id_utilisateur = $id_utilisateur;
        $deleted = $this->utilisateur->supprimer();

        if($deleted>0){
            return["message"=> "user supprimé"];
        }

        return ["message"=>"erreur lors de la suprression"];

    }
    public function store(){
        $data = json_decode(file_get_contents("php://input"),true);

        $this->utilisateur->username = $data['username'];
        $this->utilisateur->password = $data['password'];
        $this->utilisateur->role = $data['role'];

        if($this->utilisateur->create()){
            return["message" =>"user ajouté avec succès"];
        }

        return ["message"=>"erreur lors de l'ajout"];
    }    

   public function login() {
    $data = json_decode(file_get_contents("php://input"), true);
    $usernameSaisi = trim($data['username'] ?? '');
    $passwordSaisi = trim($data['password'] ?? '');

    $userBDD = $this->utilisateur->findByUsername($usernameSaisi);

    if ($userBDD && $passwordSaisi === $userBDD['password']) {
        $_SESSION['username'] = $userBDD['username']; 
        $_SESSION['role'] = $userBDD['role'];
       $connexion = new Connexion();
    $db = $connexion->getConnection();
        return [
            "success" => true,
            "message" => "Connecté en tant que " .$_SESSION['role'],
            "user" => [
                        "id" => $userBDD['id_utilisateur'],  
                        "username" => $_SESSION['username'],
                        "role" => $_SESSION['role']]
        ];
    } else {
        return [
            "success" => false,
            "message" => "username ou mot de passe incorrect"
        ];
    }
    }

    public function logout() {
        session_destroy();
        return ["message" => "Déconnexion réussie"];
    }


}


?>