<?php
class Audit{
    private $conn;
     public function __construct($db)
    {
        $this->conn =$db;
    }

    public function getAllAudits(){
        return $this->conn->query("SELECT * FROM audit ORDER BY date_action DESC")->fetchAll();
    }
    public function getCounts(){
        $sql = "SELECT operation_type, COUNT(*) as total FROM audit GROUP BY operation_type";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>