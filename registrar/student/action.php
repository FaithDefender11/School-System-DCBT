<?php

    include_once('../../includes/config.php');
   
    if (isset($_POST['searchQuery'])) {

        $inpText = $_POST['searchQuery'];

        $stmt = $con->prepare("SELECT firstname, student_id, student_unique_id
            FROM student 
            WHERE firstname LIKE :firstname
        ");

        $stmt->execute(['firstname' => '%' . $inpText . '%']);
        $result = $stmt->fetchAll();

        if ($result) {
            foreach ($result as $row) {
                echo '<a href="#" class="list-group-item list-group-item-action border-1">'.$row['student_unique_id'].' - ' . $row['firstname'] . '</a>';
            }
        } else {
            echo '<p class="list-group-item border-1">No Record</p>';
        }
    }

    // 
?>