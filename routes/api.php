<?php
session_start();
require_once __DIR__ . "/../controller/EmployeController.php";
require_once __DIR__ . "/../controller/UtilisateurController.php";
require_once __DIR__ . "/../controller/AuditController.php";

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode("/",trim($_SERVER['REQUEST_URI'],"/"));

if($uri[1] === "employe"){
    $controller = new EmployeController();
    switch ($method){
        case "GET":
            echo json_encode($controller->index());
            break;
        case "POST":
            echo json_encode($controller->store());
            break;
        case "PUT":
            echo json_encode($controller->update());
            break;
        case "DELETE":
            echo json_encode($controller->delete($uri[2] ?? null));
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Méthode non autorisée"]);
    }
}elseif($uri[1] === "user"){
    $controller = new UtilisateurController();
    switch ($method){
        case "GET":
            echo json_encode($controller->index());
            break;
        case "POST":
            echo json_encode($controller->login());
            break;
        case "DELETE":
            echo json_encode($controller->delete($uri[2] ?? null));
            break;
        default:
            http_response_code(405);
            echo json_encode(["message" => "Méthode non autorisée"]);
    }
}elseif ($uri[1] === "audit") {
    if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
        $controller = new AuditController();
        if ($method === "GET") {
            echo json_encode($controller->index()); 
        }
    } else {
        http_response_code(403);
        echo json_encode(["message" => "Accès refusé : Seul l'administrateur peut voir l'audit"]);
    }
}


?>