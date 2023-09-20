<?php 

    include_once('../../includes/student_header.php');


    echo "Student Index Dashboard";
    echo "<br>";
    echo $_SESSION['studentLoggedIn'];
    echo "<br>";
    echo $_SESSION['status'];

?>