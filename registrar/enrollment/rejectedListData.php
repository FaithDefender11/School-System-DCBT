<?php
    
    include('../../includes/config.php');
    include('../../includes/classes/Program.php');

    $draw = $_POST['draw'] ?? null;
    $row = $_POST['start'] ?? null;
    $rowperpage = $_POST['length'] ?? null;
    $columnIndex = $_POST['order'][0]['column'] ?? null;
    $columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
    $columnSortOrder = $_POST['order'][0]['dir'] ?? null;
    $searchValue = $_POST['search']['value'] ?? null;
 

    // var_dump($columnSortOrder);
    $columnNames = array(
        'firstname',
        'term',
        'status'
    );

    $sortBy = $columnNames[$columnIndex] ?? null;

    $sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

    ## Search
    $searchQuery = "";

    if ($searchValue != '') {
        $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase

        $searchQuery = " AND (
            firstname LIKE '%" . $searchValue . "%' OR 
            student_status LIKE '%" . $searchValue . "%'
        )";

    }

    // $stmt = $con->prepare("SELECT COUNT(*) AS allcount FROM student");


    $stmt = "SELECT COUNT(*) AS allcount

        FROM pending_enrollees AS t1

        WHERE t1.student_status = 'REJECTED'

        ";

    $stmt = $con->prepare($stmt);
    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
      
    $stmt = "SELECT COUNT(*) AS allcount

        FROM pending_enrollees AS t1

        WHERE 1  " . $searchQuery . " 
        AND t1.student_status = 'REJECTED'
    ";


    $stmt = $con->prepare($stmt);
    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecordwithFilter = $records['allcount'];

    ## Fetch records
        // AND student_status='REJECTED'

    if($row != null){

        $empQuery = "SELECT 

        t1.firstname
        ,t1.lastname
        ,t1.pending_enrollees_id
        ,t1.student_status
        ,t1.email
        ,t1.program_id
        ,t2.term
        ,t2.period

        FROM pending_enrollees AS t1

        LEFT JOIN school_year as t2 ON t2.school_year_id = t1.school_year_id
        
        WHERE 1  " . $searchQuery . "

        ORDER BY $sortBy $sortOrder


        LIMIT " . $row . "," . $rowperpage;


        $stmt = $con->prepare($empQuery);
        $stmt->execute();

        $data = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);
            $student_status = $row['student_status'];
            $pending_enrollees_id = $row['pending_enrollees_id'];
            $email = $row['email'];
            $program_id = $row['program_id'];

            $program = new Program($con, $program_id);

            $applyingAcronym = $program->GetProgramAcronym();

            $term = $row['term'];
            

            $period = $row['period'];
            $period_shortcut = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");

            $ay_term = "$term $period_shortcut";


            $button = "";

            $student_status_output = "";

            if($student_status == "APPROVED"){

                $student_status_output = "
                    <span class='bg-success'>$student_status</span>
                ";

            }

            if($student_status == "EVALUATION"){

                $student_status_output = "
                    <span class='bg-primary'>$student_status</span>
                ";

            }

            if($student_status == "REJECTED"){

                $student_status_output = "
                    <span class='bg-danger'>$student_status</span>
                ";
                $button = "
                    <button onclick='processRejectedEnrollees($pending_enrollees_id)' type='button' 
                        class='default information'
                        onclick=\"window.location.href='../admission/process_enrollment.php?enrollee_details=true&id=$pending_enrollees_id'\">
                        Process
                    </button>
                ";
            }

            $data[] = array(
                "name"=> $fullname,
                "term"=> $ay_term,
                "status"=> $student_status_output,
                "applying_to"=> $applyingAcronym,
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
