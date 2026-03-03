<?php 
require_once __DIR__ . "/../models/audit.php";
class AuditController{
    private $audit;

    public function __construct()
    {
        $connexion = new Connexion();
        $db = $connexion->getConnection();
        $this->audit = new Audit($db);
    }
    public function index() {
        // On récupère tout d'un coup
        return [
            'logs' => $this->audit->getAllAudits(),
            'stats' => $this->audit->getCounts()
        ];
    }
}

?>