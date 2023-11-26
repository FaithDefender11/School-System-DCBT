<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/EnrollmentPayment.php");
    
    if (isset($_POST['enrollment_id']) 
        && isset($_POST['cashier_id'])
   ) {

        $enrollment_id = $_POST['enrollment_id'];
        $cashier_id = $_POST['cashier_id'];

        $enrollment = new Enrollment($con);

        $wasSuccess = $enrollment->SetEnrollmentPaymentMethodIntoCash($enrollment_id);

        if($wasSuccess){
            echo "success";
            return;
        }

    }
?>