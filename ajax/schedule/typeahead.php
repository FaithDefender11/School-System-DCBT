<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Schedule.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    
    if (isset($_GET["query"])
        && isset($_GET['department_id'])) {

        $query = $_GET["query"];
        $department_id = $_GET["department_id"];

        // echo "department_id: $department_id";
        // return;
        $stmt = $con->prepare("SELECT teacher_id, firstname, lastname, middle_name
            FROM teacher
            WHERE (LOWER(firstname) LIKE :query OR LOWER(lastname) LIKE :query)
            AND department_id = :department_id
            AND teacher_status = 'Active'
        ");
        
        $stmt->bindValue(":query", '%' . $query . '%');
        $stmt->bindValue(":department_id", $department_id);
        $stmt->execute();

        $results = array();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // $fullName = $row['firstname'] .' ' . $row['middle_name'] . ' ' . $row['lastname'];
            $fullName = $row['firstname'] .' ' . $row['lastname'];
 
            $results[] = array(
                'label' => $fullName,  // Display name in the autocomplete dropdown
                'value' => $fullName,  // Value for input field (full name)
                'teacher_id' => $row['teacher_id']  // Teacher's ID
            );

        }

        echo json_encode($results); // Return the $results array as JSON
    }

?>