<?php
    
    include('../../includes/config.php');

    $school_year_id = $_GET['sy_id'] ?? NULL;
    $program_id = $_GET['p_id'] ?? NULL;
    $course_id = $_GET['c_id'] ?? NULL;


    $draw = $_POST['draw'] ?? null;
    $row = $_POST['start'] ?? null;
    $rowperpage = $_POST['length'] ?? null;
    $columnIndex = $_POST['order'][0]['column'] ?? null;
    $columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
    $columnSortOrder = $_POST['order'][0]['dir'] ?? null;
    $searchValue = $_POST['search']['value'] ?? null;
 

    // var_dump($columnSortOrder);

    $columnNames = array(
        'student_id',
        'name',
        'program_section',
        'status',
        'form_137',
        'good_moral',
        'psa',
        'status'
    );

    $sortBy = $columnNames[$columnIndex] ?? null;

    $sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

    ## Search
    $searchQuery = "";

    if ($searchValue != '') {
        $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase

        $names = explode(" ", $searchValue);
        // $firstName = $names[0];
        // $lastName = isset($names[1]) ? $names[1] : "";


        if (count($names) > 1) {
            $lastName = array_pop($names); // Remove the last element and assign it to the last name
            $firstName = implode(" ", $names); // The remaining parts are considered the first name
        } else {
            $firstName = $names[0]; // Only one part, so it's the first name
            $lastName = ""; // No last name provided
        }

        $firstName = trim(strtolower($firstName));
        $lastName = trim(strtolower($lastName));

        $searchQuery = " AND (
            (t2.firstname LIKE '%" . $firstName . "%' AND t2.lastname LIKE '%" . $lastName . "%') OR 
            t2.firstname LIKE '%" . $searchValue . "%' OR 
            t2.lastname LIKE '%" . $searchValue . "%' OR 
            t3.program_section LIKE '%" . $searchValue . "%'
            
        )";

    }
 

    $stmt = "SELECT COUNT(*) AS allcount 

        FROM student_requirement AS t1

        INNER JOIN student AS t2 ON t2.student_id = t1.student_id
        LEFT JOIN course AS t3 ON t3.course_id = t2.course_id

        WHERE t1.student_type IS NOT NULL
        AND t1.student_id IS NOT NULL
        AND t1.student_id != 0
        ";

    $stmt = $con->prepare($stmt);
    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
      
    $stmt = "SELECT COUNT(*) AS allcount 

        FROM student_requirement AS t1

        INNER JOIN student AS t2 ON t2.student_id = t1.student_id
        LEFT JOIN course AS t3 ON t3.course_id = t2.course_id


        WHERE 1 $searchQuery
        AND t1.student_type IS NOT NULL
        AND t1.student_id IS NOT NULL
        AND t1.student_id != 0
        
        ";

    $stmt = $con->prepare($stmt);
    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecordwithFilter = $records['allcount'];

    ## Fetch records

if($row != null){



    //  ORDER BY " . $columnName . " " . $columnSortOrder . " 
    $empQuery = "SELECT  
        t1.*, 
        t2.firstname, t2.lastname, t2.middle_name,
        t2.student_unique_id, t2.active_search,t3.program_section

        FROM student_requirement AS t1

        INNER JOIN student AS t2 ON t2.student_id = t1.student_id
        LEFT JOIN course AS t3 ON t3.course_id = t2.course_id


        WHERE 1 $searchQuery
        AND t1.student_type IS NOT NULL
        AND t1.student_id IS NOT NULL
        AND t1.student_id != 0
  
        ORDER BY $sortBy $sortOrder 

        LIMIT " . $row . "," . $rowperpage;


    $stmt = $con->prepare($empQuery);


    // $stmt->bindValue(":enrollment_status", "tentative");
    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $name = $row['firstname'] . " " . $row['lastname'];
        $student_id = $row['student_id'];
        $student_unique_id = $row['student_unique_id'];
        $student_requirement_id = $row['student_requirement_id'];
        $form_137 = $row['form_137'];
        $good_moral = $row['good_moral'];
        $psa = $row['psa'];

        $program_section = $row['program_section'];

        // $activeStatus = $row['active'] == 1 ? "Active" : ($row['active'] == 2 ? "Inactive" : "");
        $activeSearch = $row['active_search'];

        $hasGoodMoral = "<i onclick='ChangeGoodMoral($student_requirement_id, $student_id, \"checked\", \"Good moral\")' style='cursor: pointer; color: orange;' class='fas fa-times'></i>";
        $hasPSA = "<i  onclick='ChangePSA($student_requirement_id, $student_id, \"checked\", \"PSA\")' style='cursor: pointer; color: orange;' class='fas fa-times'></i>";
        $hasForm137 = "<i onclick='ChangeForm137($student_requirement_id, $student_id, \"checked\", \"Form 137\")' style='cursor: pointer; color: orange;' class='fas fa-times'></i>";

        $good_moral_valid = $row['good_moral_valid'];
        $psa_valid = $row['psa_valid'];
        $form_137_valid = $row['form_137_valid'];

        if($good_moral_valid === 1){
            $hasGoodMoral = "
                <i onclick='ChangeGoodMoral($student_requirement_id, $student_id, \"UnChecked\", \"Good moral\")' style='cursor: pointer; color: green;' class='fas fa-check'></i>
            ";
        }

        if($psa_valid === 1){
            $hasPSA = "
                <i onclick='ChangePSA($student_requirement_id, $student_id, \"UnChecked\", \"PSA\")'
                    style='cursor: pointer; color: green;'
                    class='fas fa-check'></i>
            ";
        }

        if($form_137_valid === 1){
            $hasForm137 = "
                <i onclick='ChangeForm137($student_requirement_id, $student_id, \"UnChecked\", \"Form 137\")'
                    style='cursor: pointer; color: green;'
                    class='fas fa-check'></i>
            ";
        }

        $psaOutput = "<i 
            style='cursor: pointer; color: orange;'
            class='fas fa-times'></i>";

        if($psa !== NULL){
            $psaOutput = "
                <i 
                    style='cursor: pointer; color: green;'
                    class='fas fa-check'>
                </i>
            ";
        }

        $form_137Output = "<i 
            style='cursor: pointer; color: orange;'
            class='fas fa-times'></i>";

        if($form_137 !== NULL){
            $form_137Output = "
                <i 
                    style='cursor: pointer; color: green;'
                    class='fas fa-check'>
                </i>
            ";
        }

        $good_moralOutput = "<i 
            style='cursor: pointer; color: orange;'
            class='fas fa-times'></i>";

        if($good_moral !== NULL){
            $good_moralOutput = "
                <i 
                    style='cursor: pointer; color: green;'
                    class='fas fa-check'>
                </i>
            ";
        }

        $button = "
            <button type='submit' 
                name='reset_student_password' 
                class='default '
                onclick=\"window.location.href = 'edit.php?id=$student_requirement_id';\">
                    View
            </button>  
        ";
 
        // $data[] = array(
        //     "id"=> 555,
        //     "name"=> "qweqwe",
        //     "section"=> "",
        //     "status"=> "",
        //     "form_137"=> "",
        //     "good_moral"=> "",
        //     "psa"=> "",
        //     "view_button"=> ""
        // );

         $data[] = array(
            "student_id"=> $student_unique_id,
            "name"=> $name,
            "program_section"=> $program_section,
            "status"=> $activeSearch,

            "form_137"=> $form_137Output,
            "good_moral"=> $good_moralOutput,
            "psa"=> $psaOutput,

            // "form_137"=> $hasForm137,
            // "good_moral"=> $good_moralOutput,
            // "psa"=> $psaOutput,

            "view_button"=> $button
        );
    }

    ## Response
    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => $totalRecords, // Use "recordsTotal" instead of "iTotalRecords"
        "recordsFiltered" => $totalRecordwithFilter, // Use "recordsFiltered" instead of "iTotalDisplayRecords"
        "data" => $data // The records (rows) should be under the "data" key
    );

    echo json_encode($response);
}


?>
