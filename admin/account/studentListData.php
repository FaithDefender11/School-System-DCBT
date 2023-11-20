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
        'username',
        'email',
        'name',
        'status'
    );
 

    $sortBy = $columnNames[$columnIndex] ?? null;

    $sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

    ## Search
    $searchQuery = "";

    if ($searchValue != '') {

        $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase

        $names = explode(" ", $searchValue);

        // if (count($names) > 1) {
        //     $lastName = array_pop($names); // Remove the last element and assign it to the last name
        //     $firstName = implode(" ", $names); // The remaining parts are considered the first name
        // } else {
        //     $firstName = $names[0]; // Only one part, so it's the first name
        //     $lastName = ""; // No last name provided
        // }
        if (count($names) > 2) {
            $lastName = array_pop($names); // Remove the last element and assign it to the last name
            $middleName = array_pop($names); // Remove the new last element and assign it to the middle name
            $firstName = implode(" ", $names); // The remaining parts are considered the first name
        } elseif (count($names) > 1) {
            $lastName = array_pop($names); // Remove the last element and assign it to the last name
            $middleName = ""; // No middle name provided
            $firstName = implode(" ", $names); // The remaining parts are considered the first name
        } else {
            $firstName = $names[0]; // Only one part, so it's the first name
            $middleName = ""; // No middle name provided
            $lastName = ""; // No last name provided
        }

        $firstName = trim(strtolower($firstName));
        $lastName = trim(strtolower($lastName));
        $middleName = trim(strtolower($middleName));
        
        $searchQuery = " AND (
            (t1.firstname LIKE '%" . $firstName . "%' AND t1.lastname LIKE '%" . $lastName . "%' AND t1.middle_name LIKE '%" . $middleName . "%') OR 
    (t1.firstname LIKE '%" . $searchValue . "%' OR t1.lastname LIKE '%" . $searchValue . "%' OR t1.middle_name LIKE '%" . $searchValue . "%') OR 
            t1.lastname LIKE '%" . $searchValue . "%' OR 
            t1.username LIKE '%" . $searchValue . "%' OR 
            t1.email LIKE '%" . $searchValue . "%' OR 
            t1.middle_name LIKE '%" . $searchValue . "%' OR 
            t1.student_unique_id LIKE '%" . $searchValue . "%'
            
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
            t1.email,
            t1.username,
            t1.middle_name,
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

            $name = $row['firstname'] . " " .  $row['middle_name']. " " . $row['lastname'];
            $program_section = $row['program_section'] == 0 ? "NE" : $row['program_section'];
            $student_id = $row['student_id'];
            $student_unique_id = $row['student_unique_id'];
            $email = $row['email'];

            $active = $row['active'];

            $student_username = $row['username'];
            $email = $row['email'];

            $statusStud = $active == 1 ? "Active" : ($active == 0 ? "Inactive" : "");


            $course_level = $row['course_level'] == 0 ? "NE" : $row['course_level'];

            $status = "";

            $username_output = "";

            if($student_username == ""){
                $status = "Not enrolled";
                $username_output = $email;
            }else{
                $status = "Enrolled";
                $username_output = $student_username;
            }
            

            // <td>
            //             <form method='POST'>
            //                 <input name='student_username' type='hidden' value='$username_output'>
            //                 <button type='submit' name='reset_student_password' class='danger'>Reset Password</button>
            //             </form>
            //         </td>

            $statusOutput = $active == 0 ? "Active" : "Inactive";
            $text = $active == 0 ? "Activate" : "Deactivate";
            $btnColor = $active == 0 ? "btn-outline-primary" : "btn-outline-warning";

            $resetPasswordBtn = "
                <button type='button' onclick='resetEnrolledStudent($student_id)'
                    class='btn btn-sm danger'>Reset password
                </button>
            ";

            $deactivateAccount = "
                <button type='button' onclick='deactivateStudent($student_id, \"$statusOutput\")'
                    class='btn btn-sm $btnColor'>$text
                </button>
            ";

              

                $button = "
                    $resetPasswordBtn
                    $deactivateAccount
                ";
            $data[] = array(
                "student_id"=> $student_unique_id,
                "username"=> $username_output,
                "email"=> $email,
                "name"=> $name,
                "status"=> $statusStud,
                "button"=> $button
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
