<?php
    
    include('../../includes/config.php');

    $draw = $_POST['draw'] ?? null;
    $row = $_POST['start'] ?? null;
    $rowperpage = $_POST['length'] ?? null;
    $columnIndex = $_POST['order'][0]['column'] ?? null;
    $columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
    $columnSortOrder = $_POST['order'][0]['dir'] ?? null;
    $searchValue = $_POST['search']['value'] ?? null;
 

    $status_filter = $_GET['status'] ?? NULL;

    $status_filter = trim($status_filter);

    // var_dump($columnSortOrder);
    $columnNames = array(
        'student_id',
        'name',
        'course_levelx',
        'program_section',
        'status',
        'type',
    );

    $sortBy = $columnNames[$columnIndex] ?? null;

    $sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

    ## Search
    $searchQuery = "";

    if ($searchValue != '') {

        $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase

        $names = explode(" ", $searchValue);

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
            (t1.firstname LIKE '%" . $firstName . "%' AND t1.lastname LIKE '%" . $lastName . "%') OR 
            t1.firstname LIKE '%" . $searchValue . "%' OR 
            t1.lastname LIKE '%" . $searchValue . "%' OR 
            t1.admission_status LIKE '%" . $searchValue . "%' OR
            t1.student_unique_id LIKE '%" . $searchValue . "%' OR
            t2.program_section LIKE '%" . $searchValue . "%'
            
        )";

    }

    // $stmt = $con->prepare("SELECT COUNT(*) AS allcount FROM student");

    ## Total number of records without filtering

    $stmt = $con->prepare("SELECT COUNT(DISTINCT t1.student_id) AS allcount 
        FROM student AS t1

        LEFT JOIN course AS t2 ON t1.course_id = t2.course_id
        LEFT JOIN student_requirement AS t3 ON t3.student_id = t1.student_id

        INNER JOIN enrollment as t4 ON t4.student_id = t1.student_id
        AND t4.enrollment_status = 'enrolled'
        
    ");

    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $records['allcount'];


    $student_status_filtering = "";

    if($status_filter !== ""){
        $student_status_filtering = "AND t1.active=:active";
    }


    ## Total number of records with filtering
      
   $stmt = $con->prepare("SELECT COUNT(DISTINCT t1.student_id) AS allcount

        FROM student AS t1
        LEFT JOIN course AS t2 ON t1.course_id = t2.course_id
        LEFT JOIN student_requirement AS t3 ON t3.student_id = t1.student_id
        INNER JOIN enrollment AS t4 ON t4.student_id = t1.student_id AND t4.enrollment_status = 'enrolled'

        WHERE 1 $searchQuery

        $student_status_filtering
        -- GROUP BY t1.student_id
    ");

    if($status_filter !== ""){

        $stmt->bindValue(":active", $status_filter);

    }
    
    $stmt->execute();

    $records = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecordwithFilter = $records['allcount'];

    ## Fetch records

    if($row != null){



        //  ORDER BY " . $columnName . " " . $columnSortOrder . " 
        $empQuery = "SELECT 
            t1.student_unique_id as ASD,
            t1.student_unique_id,
            t1.student_id,
            t1.firstname,
            t1.lastname,
            t1.course_level,
            t1.active,
            t1.new_enrollee,
            t1.admission_status,
            t1.course_id,
            t2.program_section,
            t3.student_requirement_id,
            t3.good_moral_valid,
            t3.good_moral,
            t3.psa_valid,
            t3.psa,
            t3.form_137_valid,
            t3.form_137

        FROM student AS t1
        INNER JOIN enrollment AS t4 ON t4.student_id = t1.student_id AND t4.enrollment_status = 'enrolled'
        LEFT JOIN course AS t2 ON t1.course_id = t2.course_id
        LEFT JOIN student_requirement AS t3 ON t3.student_id = t1.student_id
        WHERE 1 $searchQuery

        $student_status_filtering

        GROUP BY t1.student_id
        ORDER BY $sortBy $sortOrder
        LIMIT " . $row . "," . $rowperpage;


        $stmt = $con->prepare($empQuery);

        if($status_filter !== ""){

            $stmt->bindValue(":active", $status_filter);

        }

        $stmt->execute();

        $data = array();

        // var_dump($empQuery);

        // error_log($empQuery);


        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $student_id = $row['student_id'];
            $student_unique_id = $row['student_unique_id'];
            $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);
            $active = $row['active'];
            $program_section = $row['program_section'];
            $course_id = $row['course_id'];

            $program_section = "
                <a style='color: inherit' href='../section/show.php?id=$course_id'>
                    $program_section
                </a>
            ";
            $admission_status = $row['admission_status'];
            $course_level = $row['course_level'];
            $course_levelx = $course_level != 0 ? $course_level : "N/A";
            $program_section = $program_section != 0 ? $program_section : "N/A";
            $doesActive = $active == 1 ? "Active" : "Inactive";

            $status = $active == 1 ? "Active" : ($active == 0 ? "Inactive" : "");

            $new_enrollee = $row['new_enrollee'];
            $type = $new_enrollee == 1 ? "New" : ($new_enrollee == 0 ? "Old" : "");
            
            
            $student_requirement_id = $row['student_requirement_id'];

            $good_moral_valid = $row['good_moral_valid'];
            $good_moral = $row['good_moral'];

            $psa_valid = $row['psa_valid'];
            $psa = $row['psa'];


            $form_137_valid = $row['form_137_valid'];
            $form_137 = $row['form_137'];


            // echo $good_moral_valid;
            // $good_moral_valid = 0;
            // $psa_valid = 0;
            // $form_137_valid = 0;

            $completed = NULL;

            if($good_moral !== NULL){
                $completed += 1;
            }
            if($psa !== NULL){
                $completed += 1;
            }
            if($form_137 !== NULL){
                $completed += 1;
            }

            $requirement_url = "../requirements/edit.php?id=$student_requirement_id";
            
            $button  = "
                <button type='submit' 
                    class='default'
                    onclick=\"window.location.href = 'record_details.php?id=$student_id&details=show';\">
                    View
                </button>
            ";

            # Requirement for SHS
            # PSA, FORM137, GOODMORAL

            $requirement = "";
            if($completed === NULL){
                $requirement = "0 out of 3";
            }

            if($completed !== NULL){
                $requirement = "
                    <a style='color: inherit' href='$requirement_url'>$completed out of 3</a> 
                ";
            }

            $data[] = array(
                "student_id"=> $student_unique_id,
                "name"=> $fullname,
                "course_levelx"=> $course_levelx,
                "program_section"=> $program_section,
                "status"=> $status,
                "type"=> $admission_status,
                "requirement"=> $requirement,
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
