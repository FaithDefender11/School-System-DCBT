<?php

    class EnrollmentPayment{

    private $con, $sqlData;

    public function __construct($con, $enrollment_payment_id = null)
    {
        $this->con = $con;
        $this->sqlData = $enrollment_payment_id;

        if(!is_array($enrollment_payment_id)){
            
            $query = $this->con->prepare("SELECT * FROM enrollment_payment
                WHERE enrollment_payment_id=:enrollment_payment_id");

            $query->bindValue(":enrollment_payment_id", $enrollment_payment_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetPaymentHistory($enrollment_id){

        $query = $this->con->prepare("SELECT * FROM enrollment_payment
                WHERE enrollment_id=:enrollment_id");

        $query->bindValue(":enrollment_id", $enrollment_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
    
    public function AddEnrollmentPayment(
        $enrollment_id, $amount_paid,
        $enrollment_form_student_id, $payment_method){

        $now = date("Y-m-d H:i:s");


        $enrollment = new Enrollment($this->con);

        $paymentEnrollmentList = $this->GetPaymentHistory(
            $enrollment_id);

        $enrollmentTotalPayment = $enrollment->GetEnrollmentTotalPayment(
            $enrollment_form_student_id, $enrollment_id);

        
        $total_amount = NULL;

        foreach ($paymentEnrollmentList as $key => $value) {
            $total_amount += $value['amount_paid'];

        }

        $amount_paid = intval($amount_paid);
        $totalBalance = intval($enrollmentTotalPayment - $total_amount);

        // echo "totalBalance: $totalBalance";
        // echo "<br>";

        // echo "amount_paid: $amount_paid";
        // echo "<br>";

        // return;

        # Fill up all necessary to pay amount.
        
        $payment_completed = false;
        
        if($amount_paid === $totalBalance){

            // echo "fillup all";
            // return;

            $wasCompletedPayment = $enrollment->EnrollmentPaymentCompleted($enrollment_id);
            if($wasCompletedPayment){
                $payment_completed = true;
            }

            // var_dump($wasCompletedPayment);
            // return;
        }
        //   echo "fillup a x ll";
        //     return;

        // return;

        $enroll_payment_inserted = false;

        $sql = $this->con->prepare("INSERT INTO enrollment_payment
            (enrollment_id, amount_paid, date_creation)
            VALUES(:enrollment_id, :amount_paid, :date_creation)
        ");

        // $sql->bindParam(":room", $room);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":amount_paid", $amount_paid);
        $sql->bindValue(":date_creation", $now);
        $sql->execute();

        if($sql->rowCount() > 0){
            $enroll_payment_inserted = true;
            // return true;
        }

        // return false;
        $output = "";

        if($payment_completed === true 
            && $enroll_payment_inserted === true
            && $payment_method === "Partial"){
            $output = "payment_completed_enrollment_payment_success";
        }

        if($payment_completed === false 
            && $enroll_payment_inserted === true
            && $payment_method === "Partial"){
            $output = "payment_incomplete_enrollment_payment_success";
        }
        if($payment_completed === false 
            && $enroll_payment_inserted === true
            && $payment_method === "Cash"){
            $output = "cash_complete_enrollment_payment_success";
        }

        return $output;
    }
    
}

?>