<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/Template.php');

    $subject = new Subject($con, null);

    if(isset($_GET['type']) && $_GET['type'] == "shs"){
        $type = $_GET['type'];

        $form = $subject->createFormModified($type);

        echo "
            <div class='col-md-10 row offset-md-1'>
                $form
            </div>
        ";
        
    }

    if(isset($_GET['type']) 
        && $_GET['type'] == "tertiary"){
        $type = $_GET['type'];

        // echo $type;

        $form = $subject->createFormModified($type);

        echo "
            <div class='col-md-10 row offset-md-1'>
                $form
            </div>
        ";
    }
    
    if(isset($_GET['type']) && $_GET['type'] != "tertiary" && $_GET['type'] != "shs"){
        
        // System should provide selection of subject template between SHS and Tertiary.
        echo "choose between";
    }



?>