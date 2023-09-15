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
        'enrollment_form_id',
        'name',
        'program_section',
        'enrollment_status'
    );

    $sortBy = $columnNames[$columnIndex] ?? null;

    $sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

    ## Search
    $searchQuery = "";

    if ($searchValue != '') {
        $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase

        $searchQuery = " AND (
            firstname LIKE '%" . $searchValue . "%' OR 
            enrollment_form_id LIKE '%" . $searchValue . "%' OR
            program_section LIKE '%" . $searchValue . "%' OR
            term LIKE '%" . $searchValue . "%'
            
        )";

    }

    // $stmt = $con->prepare("SELECT COUNT(*) AS allcount FROM student");

    
$school_year_condition = "";

if($school_year_id !== ""){
    $school_year_condition = "AND t1.school_year_id=:school_year_id";
}

$program_condition = "";
$program_join_condition = "";

if($program_id !== ""){
    $program_join_condition = "INNER JOIN program AS t5 ON t5.program_id = t3.program_id";
    $program_condition = "AND t5.program_id=:program_id";
}

$course_condition = "";

if($course_id !== ""){
    $course_condition = "AND t3.course_id=:course_id";
}


    $stmt = "SELECT COUNT(*) AS allcount 

        FROM enrollment AS t1

        INNER JOIN student AS t2 ON t2.student_id = t1.student_id
        INNER JOIN course AS t3 ON t3.course_id = t1.course_id
        INNER JOIN school_year AS t4 ON t4.school_year_id = t1.school_year_id
        $program_join_condition

        ";

    $stmt = $con->prepare($stmt);
    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecords = $records['allcount'];

    ## Total number of records with filtering
      
    $stmt = "SELECT COUNT(*) AS allcount 

        FROM enrollment AS t1

        INNER JOIN student AS t2 ON t2.student_id = t1.student_id
        INNER JOIN course AS t3 ON t3.course_id = t1.course_id
        INNER JOIN school_year AS t4 ON t4.school_year_id = t1.school_year_id
        $program_join_condition

        WHERE 1  $searchQuery
        $school_year_condition
        $program_condition
        $course_condition
        
        ";


    $stmt = $con->prepare($stmt);

    if($school_year_id !== ""){
        $stmt->bindValue(":school_year_id", $school_year_id);
    }

    if($program_id !== ""){
        $stmt->bindValue(":program_id", $program_id);
    }

    if($course_id !== ""){
        $stmt->bindValue(":course_id", $course_id);
    }

    $stmt->execute();
    $records = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalRecordwithFilter = $records['allcount'];

    ## Fetch records

    if($row != null){


$school_year_condition = "";

if($school_year_id !== ""){
    $school_year_condition = "AND t1.school_year_id=:school_year_id";
}

$program_condition = "";
$program_join_condition = "";

if($program_id !== ""){
    $program_join_condition = "INNER JOIN program AS t5 ON t5.program_id = t3.program_id";
    $program_condition = "AND t5.program_id=:program_id";
}

$course_condition = "";

if($course_id !== ""){
    $course_condition = "AND t3.course_id=:course_id";
}


        //  ORDER BY " . $columnName . " " . $columnSortOrder . " 
        $empQuery = "SELECT 
    
            t2.firstname
            ,t2.lastname
            ,t2.student_id
            ,t3.program_section
            ,t1.enrollment_form_id
            ,t1.enrollment_status
            ,t1.enrollment_id

            ,t4.term
            ,t4.period

            FROM enrollment AS t1

            INNER JOIN student AS t2 ON t2.student_id = t1.student_id
            INNER JOIN course AS t3 ON t3.course_id = t1.course_id
            INNER JOIN school_year AS t4 ON t4.school_year_id = t1.school_year_id
            $program_join_condition
        
            WHERE 1  " . $searchQuery . "
            $school_year_condition
            $program_condition
            $course_condition

            AND t1.enrollment_status !=:enrollment_status

            ORDER BY enrollment_id DESC, " . $sortBy . " " . $sortOrder . "

            LIMIT " . $row . "," . $rowperpage;

            // ORDER BY $sortBy $sortOrder

        $stmt = $con->prepare($empQuery);

        if($school_year_id !== ""){
            $stmt->bindValue(":school_year_id", $school_year_id);
        }

        if($program_id !== ""){
            $stmt->bindValue(":program_id", $program_id);
        }

        if($course_id !== ""){
            $stmt->bindValue(":course_id", $course_id);
        }

        $stmt->bindValue(":enrollment_status", "tentative");
        $stmt->execute();

        $data = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);
            $program_section = $row['program_section'];
            $enrollment_form_id = $row['enrollment_form_id'];
            $enrollment_id = $row['enrollment_id'];
            $term = $row['term'];
            $period = $row['period'];
            $enrollment_status = ucfirst($row['enrollment_status']);
            $student_id = $row['student_id'];

            // $program_section = "
            //     <a style='color: inherit' href='../section/show.php?id=$course_id'>
            //         $program_section
            //     </a>
            // ";

        
    
        //    $button = "
        //         <button type='button' 
        //             class='default'
        //             onclick=\"window.location.href='../admission/subject_insertion_summary.php?id={$student_id}&enrolled_subject=show'\">
        //             View
        //         </button>
        //     ";

            $button = "
                <button type='button' 
                    class='default'
                    onclick=\"window.location.href='../admission/subject_insertion_summary.php?id={$enrollment_id}&enrolled_subject=show'\">
                    View
                </button>
            ";
 
            $data[] = array(
                "enrollment_form_id"=> $enrollment_form_id,
                "name"=> $fullname,
                "program_section"=> $program_section,
                "term"=> $term,
                "period"=> $period,
                "enrollment_status"=> $enrollment_status,
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
