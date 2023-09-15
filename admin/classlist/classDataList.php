<?php
include('../../includes/config.php');
include('../../includes/classes/SchoolYear.php');
include('../../includes/classes/Schedule.php');

$school_year = new SchoolYear($con, null);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];

// if(isset($_GET['sy_id'])){


$school_year_id = $_GET['sy_id'] ?? NULL;
$program_id = $_GET['p_id'] ?? NULL;
$course_id = $_GET['c_id'] ?? NULL;

// var_dump($school_year_id);

$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;
 
$columnNames = array(
    'student_id',
    'name',
    'admission_status',
    'date_enrolled'
);

$sortBy = $columnNames[$columnIndex];

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC';  

## Search
$searchQuery = "";
if ($searchValue != '') {

    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    
    $searchQuery = " AND (
        program_section LIKE '%" . $searchValue . "%' OR 
        subject_code LIKE '%" . $searchValue . "%' OR
        schedule_time LIKE '%" . $searchValue . "%'

    )";
}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
        
    FROM enrollment as t1

    INNER JOIN student as t2 ON t2.student_id=t1.student_id
    INNER JOIN course as t3 ON t3.course_id=t1.course_id
    INNER JOIN program as t4 ON t4.program_id=t3.program_id
    INNER JOIN school_year as t5 ON t5.school_year_id=t1.school_year_id

    WHERE t1.enrollment_status = 'enrolled'
");
$stmt->execute();
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];



$school_year_condition = "";

if($school_year_id !== ""){
    $school_year_condition = "AND t1.school_year_id=:school_year_id";
}

$program_condition = "";
$program_join_condition = "";

if($program_id !== ""){
    // $program_join_condition = "INNER JOIN program AS t4 ON t4.program_id = t2.program_id";
    $program_condition = "AND t4.program_id=:program_id";
}


$course_condition = "";

if($course_id !== ""){
    $course_condition = "AND t3.course_id=:course_id";
}


## Total number of records with filtering

$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
        
    FROM enrollment as t1

    INNER JOIN student as t2 ON t2.student_id=t1.student_id
    INNER JOIN course as t3 ON t3.course_id=t1.course_id
    INNER JOIN program as t4 ON t4.program_id=t3.program_id
    INNER JOIN school_year as t5 ON t5.school_year_id=t1.school_year_id

    WHERE 1 $searchQuery 
    $course_condition
    $school_year_condition
    $program_condition

    AND t1.enrollment_status = 'enrolled'
  
    
");
 

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
if ($row != null) {

    $empQuery = "SELECT 

        t1.enrollment_approve,

        t3.program_id, t2.student_id,
        t3.program_section,

        t2.active,
        t2.firstname,
        t2.lastname,

        t2.student_unique_id,
        t2.admission_status,
        t2.student_statusv2,
        t5.term,
        t5.period
        
        FROM enrollment as t1

        INNER JOIN student as t2 ON t2.student_id=t1.student_id
        INNER JOIN course as t3 ON t3.course_id=t1.course_id
        INNER JOIN program as t4 ON t4.program_id=t3.program_id
        INNER JOIN school_year as t5 ON t5.school_year_id=t1.school_year_id
 
        WHERE 1 $searchQuery 
        $course_condition
        $school_year_condition
        $program_condition

        AND t1.enrollment_status = 'enrolled'

        ORDER BY $sortBy $sortOrder
        
        LIMIT " . $row . "," . $rowperpage;

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

 
    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $enrollment_approve = $row['enrollment_approve'];

        $student_unique_id = $row['student_unique_id'];
        $admission_status = $row['admission_status'];

        $period = $row['period'];
        $term = $row['term'];
        $program_section = $row['program_section'];

        $name = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);
 
        $data[] = array(
            "student_id" => $student_unique_id,
            "name" => $name,
            "program_section" => $program_section,
            "admission_status" => $admission_status,
            "ay" => $term,
            "period" => "$period Semester",
            "date_enrolled" => $enrollment_approve
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

// }

?>

