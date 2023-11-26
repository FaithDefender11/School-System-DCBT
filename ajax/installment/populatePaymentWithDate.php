<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Enrollment.php");
    
    if(isset($_POST['option']) &&
        isset($_POST['amountToPay'])) {

        // echo "im not";
        $option = $_POST['option'];
        $amountToPay = $_POST['amountToPay'];

        // echo "option: $option";

        $get = $con->prepare("SELECT * 
            
            FROM installment as t1

            WHERE t1.option=:option

        ");

        $get->bindValue(":option", $option);
        $get->execute();

        if($get->rowCount() > 0){

            $amount_to_pay = ($amountToPay / $get->rowCount());
            $amount_to_pay = number_format($amount_to_pay, 2);

            while($row = $get->fetch(PDO::FETCH_ASSOC)){
            
                $installment_id = $row['installment_id'];
                $default_payment_due = $row['default_payment_due'];
                
                $data[] = array(
                    'installment_id' => $installment_id,
                    'amount_to_pay' => $amount_to_pay,
                    'default_payment_due' => $default_payment_due,
                );
            }
        }

        if(empty($data)){
            echo json_encode([]);
        }else{
            echo json_encode($data);
        }
           
    }

?>