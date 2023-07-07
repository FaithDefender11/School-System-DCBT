<?php
    session_start();


    if(isset($_SESSION['studentLoggedIn'])){
        session_destroy();
        header("Location: /school-system-dcbt/student_login.php");
    }
    else if(isset($_SESSION['registrarLoggedIn'])
        || isset($_SESSION['adminLoggedIn'])){
            session_start();
            session_destroy();
            header("Location: /school-system-dcbt/enrollment_login.php");
    }else{
        echo "No Identity.";
    }


?>


