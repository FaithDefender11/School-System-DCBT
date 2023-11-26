<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/EnrollmentPayment.php");
    
    if (isset($_POST['enrollment_id']) 
        && isset($_POST['cashier_id'])
        && isset($_POST['to_pay_amount'])
        && isset($_POST['enrollment_payment_id'])
   ) {

        $enrollment_id = $_POST['enrollment_id'];
        $cashier_id = $_POST['cashier_id'];
        $amount_paid = $_POST['to_pay_amount'];
        $enrollment_payment_id = $_POST['enrollment_payment_id'];

        $amount_paid = floatval(str_replace(',', '', $amount_paid));
        // echo "amount_paid: $amount_paid";
        // return;

        $enrollmentPayment = new EnrollmentPayment($con);
        $enrollment = new Enrollment($con);

        # Check if all enrollment_payment WHERE enrollment form id doesnt have any process date.
        $hasPreviousPayment = $enrollmentPayment->CheckIfEnrollmentFormIdIsFresh($enrollment_id);        

        // if($hasPreviousPayment == false){
        //     # If so, Update the enrollment form  into Partial, Incomplete.
        //     $enrollmentIntoPartialIncomplete = $enrollment->EnrollmentSetAsPartialAndIncomplete($enrollment_id);

        // }
        // var_dump($hasPreviousPayment);
        // return;

        $doesOneStepFurther = $enrollmentPayment->CheckIfEnrollmentFormIdIsOneStepToComplete($enrollment_id);
        
        if($doesOneStepFurther == 1){
            $wasSuccessCompletedPartial = $enrollment->EnrollmentPaymentCompleted($enrollment_id);
        }

        // var_dump($doesOneStepFurther);

        // return;

        $enrollmentPayment->MarkAsProcessedSelectedFormInstallment(
            $enrollment_id, $amount_paid, $cashier_id, $enrollment_payment_id);
        
        if($enrollmentPayment){
            echo "success";
            return;
        }

    }
?>