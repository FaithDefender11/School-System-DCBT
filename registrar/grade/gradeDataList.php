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
$student_subject_id = $_GET['ss_id'] ?? NULL;

// var_dump($school_year_id);

$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;
 
$columnNames = array(
    'student_unique_id',
    'name',
    'subject_code',
    'units',
    'first',
    'second',
    'third',
    'fourth',
    'remarks'
);


$sortBy = $columnNames[$columnIndex];

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC';  


## Search
$searchQuery = "";
if ($searchValue != '') {

    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    
        // schedule_time LIKE '%" . $searchValue . "%'
        // program_section LIKE '%" . $searchValue . "%' OR 
        // subject_code LIKE '%" . $searchValue . "%'
    $searchQuery = " AND (
        
        t1.subject_code LIKE '%" . $searchValue . "%'

    )";
}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
        
    FROM student_subject as t1

    INNER JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id
    INNER JOIN course as t3 ON t3.course_id = t1.course_id
    INNER JOIN program AS t4 ON t4.program_id = t3.program_id
    INNER JOIN school_year AS t5 ON t5.school_year_id = t1.school_year_id
    INNER JOIN student AS t6 ON t6.student_id = t1.student_id

");
$stmt->execute();
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];



$school_year_condition = "";

if($school_year_id !== ""){
    $school_year_condition = "AND t5.school_year_id=:school_year_id";
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

$student_subject_condition = "";

if($student_subject_id !== ""){
    $student_subject_condition = "AND t2.student_subject_id=:student_subject_id";
}

## Total number of records with filtering

$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
        
        FROM student_subject as t1

        INNER JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id
        INNER JOIN course as t3 ON t3.course_id = t1.course_id
        INNER JOIN program AS t4 ON t4.program_id = t3.program_id
        INNER JOIN school_year AS t5 ON t5.school_year_id = t1.school_year_id
        INNER JOIN student AS t6 ON t6.student_id = t1.student_id
 

        WHERE 1 $searchQuery 
        $school_year_condition
        $program_condition
        $course_condition
        $student_subject_condition
    
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

    if($student_subject_id !== ""){
        $stmt->bindValue(":student_subject_id", $student_subject_id);
    }

$stmt->execute();


$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {

     
    $empQuery = "SELECT 

        t1.student_subject_id,
        t1.subject_code,
        t1.course_id,
        t2.first,
        t2.second,
        t2.third,
        t2.fourth,
        t2.remarks,
        -- t2.unit,

        t6.firstname,
        t6.lastname,
        t6.student_unique_id,

        t7.unit

        
        FROM student_subject as t1

        INNER JOIN student_subject_grade as t2 ON t2.student_subject_id = t1.student_subject_id
        INNER JOIN course as t3 ON t3.course_id = t1.course_id
        INNER JOIN program AS t4 ON t4.program_id = t3.program_id
        INNER JOIN school_year AS t5 ON t5.school_year_id = t1.school_year_id

        INNER JOIN student AS t6 ON t6.student_id = t1.student_id

        -- INNER JOIN student AS t6 ON t6.student_id = t1.student_id

        LEFT JOIN subject_program AS t7 ON t7.subject_program_id = t1.subject_program_id

        -- AND t3.program_id=:program_id
        -- AND t1.school_year_id=:school_year_id
        -- AND t3.course_id=:course_id

        WHERE 1 $searchQuery 
        $school_year_condition
        $program_condition
        $course_condition
        $student_subject_condition

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

    if($student_subject_id !== ""){
        $stmt->bindValue(":student_subject_id", $student_subject_id);
    }
    
    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $subject_code = $row['subject_code'];

        $first = $row['first'] === 0 ? "~" : $row['first'];
        $second = $row['second'] === 0 ? "~" : $row['second'];
        $third = $row['third'] === 0 ? "~" : $row['third'];
        $fourth = $row['fourth'] === 0 ? "~" : $row['fourth'];

        $remarks = $row['remarks'];
        $unit = $row['unit'];

        $name = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);
        $student_unique_id = $row['student_unique_id'];

        $remarks = $remarks !== NULL && $remarks === "Passed" ? "Passed" 
            : ($remarks === "Failed" ? "Failed" : "");
 
        $data[] = array(

            "student_unique_id" => $student_unique_id,
            "name" => $name,
            "subject_code" => $subject_code,
            "units" => $unit,
            "first" => $first,
            "second" => $second,
            "third" => $third,
            "fourth" => $fourth,
            "remarks" => $remarks
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

