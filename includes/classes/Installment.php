<?php

    class Installment{

    private $con, $sqlData;

    public function __construct($con, $installment_id = null)
    {
        $this->con = $con;
        $this->sqlData = $installment_id;

        if(!is_array($installment_id)){
            
            $query = $this->con->prepare("SELECT * FROM installment
                WHERE installment_id=:installment_id");

            $query->bindValue(":installment_id", $installment_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetDefaultPaymentDue() {
        return isset($this->sqlData['default_payment_due']) ? $this->sqlData["default_payment_due"] : NULL; 
    }

    public function GetOption() {
        return isset($this->sqlData['option']) ? $this->sqlData["option"] : NULL; 
    }
    public function GetEnable() {
        return isset($this->sqlData['enable']) ? $this->sqlData["enable"] : NULL; 
    }
}
?>