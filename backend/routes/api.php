<?php
header("Content-type: application/json");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . "/../controller/EmployeController.php";
require_once __DIR__ . "/../controller/UtilisateurController.php";
require_once __DIR__ . "/../controller/AuditController.php";

$method = $_SERVER['REQUEST_METHOD'];
$uri = explode("/",trim($_SERVER['REQUEST_URI'],"/"));
$route = $uri[1] ?? null ;

if($route=== "employe"){
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
}elseif($route=== "user"){
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
}elseif ($route === "audit") {
    error_log("Tentative audit - Role en session : " . ($_SESSION['role'] ?? 'RIEN DU TOUT'));
    if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
        $controller = new AuditController();
        if ($method === "GET") {
            echo json_encode($controller->index()); 
        }
    } else {
        http_response_code(403);
        echo json_encode([
            "message" => "Accès refusé",
            "debug_role_detecte" => $_SESSION['role'] ?? 'aucune session trouvée'
        ]);
    }
}


?>