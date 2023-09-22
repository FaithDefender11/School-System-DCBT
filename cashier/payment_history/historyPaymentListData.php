<?php
include('../../includes/config.php');
include('../../includes/classes/SchoolYear.php');
include('../../includes/classes/Section.php');
include('../../includes/classes/Enrollment.php');

$school_year = new SchoolYear($con, null);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];


$section = new Section($con, null);

$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;
 

 
$columnNames = array(
    'enrollment_form_id',
    'student_no',
    'name',
    'section',
    'cashier_confirmation_date'
);

$sortBy = $columnNames[$columnIndex] ?? 'cashier_confirmation_date';  

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC';  


## Search
$searchQuery = "";
if ($searchValue != '') {
    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    $searchQuery = " AND (t1.firstname LIKE '%" . $searchValue . "%' OR 
        t1.lastname LIKE '%" . $searchValue . "%' OR
        t2.enrollment_form_id LIKE '%" . $searchValue . "%' OR
        t3.program_section LIKE '%" . $searchValue . "%' OR
        t1.student_unique_id LIKE '%" . $searchValue . "%'
    )";
}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM student AS t1

    INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id
    INNER JOIN course AS t3 ON t3.course_id = t2.course_id

    AND t2.enrollment_status='enrolled'
    AND t2.cashier_evaluated = 'yes'
    -- AND t2.school_year_id=:school_year_id
    
    ");


// $stmt->bindValue(":school_year_id", 3);
$stmt->execute();
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];


## Total number of records with filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
        FROM student AS t1
    
        INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id
        INNER JOIN course AS t3 ON t3.course_id = t2.course_id

        -- AND t2.enrollment_status = 'enrolled'
        AND t2.cashier_evaluated = 'yes'

        WHERE 1 $searchQuery
    
    ");
 
// $stmt->bindValue(":school_year_id", 3);
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {

    // $default_shs_course_level = 11;
    // $is_new_enrollee = 1;
    // $is_transferee = 1;
    // $regular_Status = "Regular";
    // $enrollment_status = "tentative";
    // $registrar_evaluated = "yes";



    $empQuery = "SELECT 
        t2.student_id,
        t2.cashier_confirmation_date,

        t1.firstname,
        t1.username,
        t1.student_unique_id,
        t1.lastname,
        t1.email,
        t1.course_level,
        t1.admission_status,
        t1.student_statusv2,
        t1.course_id,
        t1.student_id AS t2_student_id,
        t1.course_id,
        t1.course_level,
        t1.student_status,
        t1.is_tertiary,
        t1.new_enrollee,
        t1.student_unique_id,

        t2.is_new_enrollee AS enrollment_is_new_enrollee,
        t2.is_transferee AS enrollment_is_transferee,
        t2.student_status AS enrollment_student_status,
        t2.waiting_list,
        t2.enrollment_id,
        t2.enrollment_form_id,

        t3.program_section

        FROM student AS t1
    
        INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id
        INNER JOIN course AS t3 ON t3.course_id = t2.course_id

        -- AND t2.enrollment_status = 'enrolled'
        AND t2.cashier_evaluated = 'yes'

        WHERE 1 $searchQuery

        ORDER BY $sortBy $sortOrder
        
        LIMIT " . $row . "," . $rowperpage;


    $stmt = $con->prepare($empQuery);

    // $stmt->bindValue(":school_year_id", 3);
    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        
        $enrollment_id = $row['enrollment_id'];
        $enrollment_form_id = $row['enrollment_form_id'];
        $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);

        $course_level = $row['course_level'];
        $student_unique_id = $row['student_unique_id'];

        $cashier_confirmation_date = $row['cashier_confirmation_date'];
        $cashier_confirmation_date = date("F d, Y H:i a", strtotime($cashier_confirmation_date));

        $username = $row['username'];
        $student_id = $row['t2_student_id'];
        $program_section = $row['program_section'];
        $course_level = $row['course_level'];

        $button_url = "
            <button class='default information'>View</button>
        ";
 
        $data[] = array(
            "enrollment_form_id" => $enrollment_form_id,
            "student_no" => $student_unique_id,
            "name" => $fullname,
            "section" => $program_section,
            "cashier_confirmation_date" => $cashier_confirmation_date,
            "button_url" => $button_url,
        );
    }

    ## Response
    $response = array(
        "draw" => intval($draw),
        "recordsTotal" => $totalRecords, // Use "recordsTotal" instead of "iTotalRecords"
        "recordsFiltered" => $totalRecordwithFilter, // Use "recordsFiltered" instead of "iTotalDisplayRecords"
        "data" => $data // The records (rows) should be under the "data" key
    );

    echo json_encode($response, JSON_PRETTY_PRINT);

    // echo json_encode($response);
}
?>
