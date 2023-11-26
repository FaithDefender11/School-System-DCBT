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

    public function GetInstallmentOption($enrollment_id){

        $query = $this->con->prepare("SELECT installment_option FROM enrollment_payment
            WHERE enrollment_id=:enrollment_id
            ORDER BY enrollment_payment_id DESC
            LIMIT 1
        ");

        $query->bindValue(":enrollment_id", $enrollment_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }
        return NULL;
    }


    public function GetPaymentHistoryExceptDownPayment($enrollment_id){

        $query = $this->con->prepare("SELECT * FROM enrollment_payment
            WHERE enrollment_id=:enrollment_id
            AND is_downpayment IS NULL
            AND date_to_pay IS NOT NULL
        ");

        $query->bindValue(":enrollment_id", $enrollment_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function GetSelectedPaymentOptionsCount($option){


        $query = $this->con->prepare("SELECT * FROM installment
            
            WHERE option=:option
            AND enable = 1

        ");

        $query->bindValue(":option", $option);
        $query->execute();

        return $query->rowCount();

    }

    public function GetDownPayment($enrollment_id){


        $query = $this->con->prepare("SELECT amount_paid FROM enrollment_payment
            WHERE enrollment_id=:enrollment_id
            AND is_downpayment=:is_downpayment
            LIMIT 1
        ");

        $query->bindValue(":enrollment_id", $enrollment_id);
        $query->bindValue(":is_downpayment", 1);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }

        return NULL;

    }

    public function HasPaidThePaymentSchedule($enrollment_id){


        $query = $this->con->prepare("SELECT * FROM enrollment_payment
            WHERE enrollment_id=:enrollment_id
            AND process_date IS NOT NULL
            AND is_downpayment IS NULL
            AND cashier_id IS NOT NULL
            LIMIT 1
        ");

        $query->bindValue(":enrollment_id", $enrollment_id);
        $query->execute();

        return $query->rowCount() > 0;

    }

    public function GetOptionsAndSinkToTheEnrollmentForm(
        $option, $enrollment_id, $downPayment, $cashier_id){

        $isSuccess = false;

        $query = $this->con->prepare("SELECT * FROM installment
            WHERE option=:option
            AND enable = 1

        ");

        $query->bindValue(":option", $option);
        $query->execute();

        if($query->rowCount() > 0){
            
            $allOptions = $query->fetchAll(PDO::FETCH_ASSOC);

            $insertDownPaymentSuccess = $this->InsertDownPayment(
                $downPayment, $enrollment_id, $cashier_id, $option);

            $sql = $this->con->prepare("INSERT INTO enrollment_payment
                (enrollment_id, date_to_pay, installment_option)
                VALUES(:enrollment_id, :date_to_pay, :installment_option)
            ");

            // $sql->bindParam(":room", $room);
            
            foreach ($allOptions as $key => $value) {

                # 

                $default_payment_due = $value['default_payment_due'];

                $sql->bindValue(":enrollment_id", $enrollment_id);
                $sql->bindValue(":date_to_pay", $default_payment_due);
                $sql->bindValue(":installment_option", $option);
                $sql->execute();

                if($sql->rowCount() > 0){
                   $isSuccess = true;
                }
            }

        }
        return $isSuccess;
    }

    public function EditEnrollmentPaymentInstallmentOptions(
        $option, $enrollment_id, $downPayment, $cashier_id){

        $isSuccess = false;

        $query = $this->con->prepare("SELECT * FROM installment
            
            WHERE option=:option
            AND enable = 1

        ");

        $query->bindValue(":option", $option);
        $query->execute();

        if($query->rowCount() > 0){
            
            $allOptions = $query->fetchAll(PDO::FETCH_ASSOC);

            // var_dump($allOptions);
            // return;

            if(count($allOptions) > 0){
                
                # Remove ALL
                $removeAllInstallment = $this->RemoveAllEnrollmentPayment($enrollment_id);

                if($removeAllInstallment){

                    $insertDownPaymentSuccess = $this->InsertDownPayment(
                        $downPayment, $enrollment_id, $cashier_id, $option);

                    $sql = $this->con->prepare("INSERT INTO enrollment_payment
                        (enrollment_id, date_to_pay, installment_option)
                        VALUES(:enrollment_id, :date_to_pay, :installment_option)
                    ");
                    
                    foreach ($allOptions as $key => $value) {
                        # 

                        $default_payment_due = $value['default_payment_due'];
                        $option_db = $value['option'];

                        $sql->bindValue(":enrollment_id", $enrollment_id);
                        $sql->bindValue(":date_to_pay", $default_payment_due);
                        $sql->bindValue(":installment_option", $option_db);
                        $sql->execute();

                        if($sql->rowCount() > 0){
                            $isSuccess = true;
                        }
                    }
                }

            }

        }
        return $isSuccess;
    }
    public function RemoveAllEnrollmentPayment($enrollment_id){

        $query = $this->con->prepare("DELETE FROM enrollment_payment

            WHERE enrollment_id=:enrollment_id
        ");

        $query->bindValue(":enrollment_id", $enrollment_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }
    public function InsertDownPayment($downPayment, $enrollment_id, $cashier_id, $option){

        $process_date = date("Y-m-d H:i:s");

        $add = $this->con->prepare("INSERT INTO enrollment_payment
            (enrollment_id, date_to_pay, amount_paid, process_date,
            cashier_id, is_downpayment, installment_option)
            VALUES(:enrollment_id, :date_to_pay, :amount_paid, :process_date,
            :cashier_id, :is_downpayment, :installment_option)
        ");

        $add->bindValue(":enrollment_id", $enrollment_id);
        $add->bindValue(":date_to_pay", NULL);
        $add->bindValue(":amount_paid", $downPayment);
        $add->bindValue(":process_date", $process_date);
        $add->bindValue(":cashier_id", $cashier_id);
        $add->bindValue(":is_downpayment", 1);
        $add->bindValue(":installment_option", $option);
        $add->execute();

        if($add->rowCount() > 0){
           return true;
        }
        return false;
    }

    public function InsertPaymentFullCash(
        $enrollment_id, $cashier_id, $amount_paid){

        $process_date = date("Y-m-d H:i:s");

        $sql = $this->con->prepare("INSERT INTO enrollment_payment
            (enrollment_id, cashier_id, amount_paid, process_date)
            VALUES(:enrollment_id, :cashier_id, :amount_paid, :process_date)
        ");

        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":cashier_id", $cashier_id);
        $sql->bindValue(":amount_paid", $amount_paid);
        $sql->bindValue(":process_date", $process_date);

        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function CheckIfEnrollmentFormIdIsFresh($enrollment_id){

        $query = $this->con->prepare("SELECT * FROM enrollment_payment
            WHERE enrollment_id=:enrollment_id
            AND process_date IS NOT NULL
            AND cashier_id IS NOT NULL
        ");

        $query->bindValue(":enrollment_id", $enrollment_id);
        $query->execute();

        if($query->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function CheckIfEnrollmentFormIdIsOneStepToComplete($enrollment_id){

        $allProcessedPayment = 0;
        $allUnProcessedPayment = 0;

        $query1 = $this->con->prepare("SELECT * FROM enrollment_payment
            WHERE enrollment_id=:enrollment_id
            AND process_date IS NULL
            AND cashier_id IS NULL
        ");

        $query1->bindValue(":enrollment_id", $enrollment_id);
        $query1->execute();

        if($query1->rowCount() > 0){
            
            $allUnProcessedPayment =  $query1->rowCount();
        }

        $query2 = $this->con->prepare("SELECT * FROM enrollment_payment
            WHERE enrollment_id=:enrollment_id
            AND process_date IS NOT NULL
            AND cashier_id IS NOT NULL
        ");

        $query2->bindValue(":enrollment_id", $enrollment_id);
        $query2->execute();

        if($query2->rowCount() > 0){
            
            $allProcessedPayment =  $query2->rowCount();

        }

        $result = NULL;
        // $oneStepFurther = $allProcessedPayment - $allUnProcessedPayment;

        // if($oneStepFurther == 1){

        //     $result = 1;

        //     $enrollment = new Enrollment($this->con);
        //     // $wasSuccessCompletedPartial = $enrollment->EnrollmentPaymentCompleted($enrollment_id);
            
        // }else{
        //     $result = NULL;
        // }

        // echo "allUnProcessedPayment: $allUnProcessedPayment";
        // echo "<br>";
        // echo "allProcessedPayment: $allProcessedPayment";
        // echo "<br>";
        // echo "<br>";

        // echo "oneStepFurther: $oneStepFurther";
        // echo "<br>";

        return $allUnProcessedPayment;

    }
    
    public function AddEnrollmentPayment(
        $enrollment_id, $amount_paid,
        $enrollment_form_student_id, $payment_method,
        $cashier_id){

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
            (enrollment_id, amount_paid, date_creation, cashier_id)
            VALUES(:enrollment_id, :amount_paid, :date_creation, :cashier_id)
        ");

        // $sql->bindParam(":room", $room);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":amount_paid", $amount_paid);
        $sql->bindValue(":date_creation", $now);
        $sql->bindValue(":cashier_id", $cashier_id);
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

     public function MarkAsProcessedSelectedFormInstallment(
        $enrollment_id, $amount_paid, $cashier_id, $enrollment_payment_id){


        $now = date("Y-m-d H:i:s");

        $sql = $this->con->prepare("UPDATE enrollment_payment
            
            SET amount_paid=:amount_paid,
                process_date=:date_now,
                cashier_id=:cashier_id
            WHERE enrollment_id=:enrollment_id
            AND enrollment_payment_id=:enrollment_payment_id
        ");

        // $sql->bindParam(":room", $room);
        $sql->bindValue(":amount_paid", $amount_paid);
        $sql->bindValue(":date_now", $now);
        $sql->bindValue(":cashier_id", $cashier_id);
        $sql->bindValue(":enrollment_id", $enrollment_id);
        $sql->bindValue(":enrollment_payment_id", $enrollment_payment_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }
    
}

?>