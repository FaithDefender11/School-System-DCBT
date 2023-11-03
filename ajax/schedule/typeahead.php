<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Schedule.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    
    if (isset($_GET["query"])) {

        $query = $_GET["query"];

        $stmt = $con->prepare("SELECT teacher_id, firstname, lastname, middle_name
            FROM teacher 
            WHERE LOWER(firstname) LIKE :query OR LOWER(lastname) LIKE :query
        ");
        
        $stmt->bindValue(":query", '%' . $query . '%');
        $stmt->execute();

        $results = array();
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            // $fullName = $row['firstname'] .' ' . $row['middle_name'] . ' ' . $row['lastname'];
            $fullName = $row['firstname'] .' ' . $row['lastname'];


            // Add the full name to the results array
            // $results[] = $fullName;

            $results[] = array(
                'label' => $fullName,  // Display name in the autocomplete dropdown
                'value' => $fullName,  // Value for input field (full name)
                'teacher_id' => $row['teacher_id']  // Teacher's ID
            );

        }

        echo json_encode($results); // Return the $results array as JSON
    }

?>