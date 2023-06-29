<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con, null);

    $form = $teacher->createTeacherForm();

    echo "
        <div class='col-md-10 row offset-md-1'>
            $form
        </div>
    ";
        
?>