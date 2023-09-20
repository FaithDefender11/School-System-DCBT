<?php

include('../../includes/config.php');
include('../../includes/classes/SchoolYear.php');
include('../../includes/classes/Section.php');
include('../../includes/classes/Enrollment.php');
include('../../includes/classes/Schedule.php');

$school_year = new SchoolYear($con, null);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
    $schedule = new Schedule($con);

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];

$subject_titles_occurrences = [];
$subject_code_occurrences = [];
$section_occurrences = [];

$section = new Section($con, null);

$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;

// $teacher_id = 1;

$school_year_id = $_GET['sy_id'] ?? NULL;
$teacher_id = $_GET['t_id'] ?? NULL;
$course_id = $_GET['c_id'] ?? NULL;

$columnNames = array(
    'subject_title',
    'subject_code',
    'program_section',
    'schedule_day',
    'schedule_time',
    'hrs_week'
);

$sortBy = $columnNames[$columnIndex]; // Default to submission_creation column if the selected column is not found in the array

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

## Search
$searchQuery = "";
if ($searchValue != '') {
    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    
    $searchQuery = " AND (
        program_section LIKE '%" . $searchValue . "%' OR 
        t1.subject_code LIKE '%" . $searchValue . "%' OR

        schedule_time LIKE '%" . $searchValue . "%'
    )";
}

$default_shs_course_level = 11;
$is_new_enrollee = 1;
$is_transferee = 1;
$regular_Status = "Regular";
$enrollment_status = "tentative";
$registrar_evaluated = "yes";


$enrollment_status = "enrolled";
$registrar_evaluated = "yes";
$cashier_evaluated = "yes";



## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
        

        FROM subject_schedule as t1

        INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id
        
        INNER JOIN course AS t4 ON t4.course_id = t1.course_id

        WHERE t1.teacher_id = :teacher_id
    ");

$stmt->bindValue(":teacher_id", $teacher_id);
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];


$school_year_condition = "";

if($school_year_id !== ""){
    $school_year_condition = "AND t1.school_year_id=:school_year_id";
}

$course_condition = "";

if($course_id !== ""){
    $course_condition = "AND t4.course_id=:course_id";
}

## Total number of records with filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
        
        FROM subject_schedule as t1
        INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id
        INNER JOIN course AS t4 ON t4.course_id = t1.course_id

        WHERE 1 $searchQuery
        $school_year_condition
        $course_condition

        AND t1.teacher_id = :teacher_id
    ");

if($school_year_id !== ""){
    $stmt->bindParam(":school_year_id", $school_year_id);
}

if($course_id !== ""){
    $stmt->bindValue(":course_id", $course_id);
}


$stmt->bindValue(":teacher_id", $teacher_id);
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {



 
    $stmt = $con->prepare("SELECT 
        t1.subject_schedule_id,
        t1.course_id AS subject_schedule_course_id,
        t1.subject_program_id AS subject_subject_program_id,
        t1.time_from,
        t1.time_to,
        t1.schedule_day,
        t1.schedule_time,
        -- t1.room,
        t1.course_id, t1.subject_code,

        t4.program_section,
        t4.course_id as courseCourseId,

        t3.subject_title,
        t3.subject_program_id,
        t3.subject_code AS sp_subject_code,

        t5.term,
        t5.period
        
        FROM subject_schedule as t1
        INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id
        INNER JOIN school_year AS t5 ON t5.school_year_id = t1.school_year_id
        INNER JOIN course AS t4 ON t4.course_id = t1.course_id
        
        LEFT JOIN subject_program as t3 ON t3.subject_program_id = t1.subject_program_id

        WHERE 1 $searchQuery

        $school_year_condition

        $course_condition

        AND t1.teacher_id = :teacher_id

        -- ORDER BY $sortBy $sortOrder
        ORDER BY t1.subject_code

        LIMIT " . $row . "," . $rowperpage
    );

    // var_dump($school_year_id);
    if($school_year_id !== ""){
        $stmt->bindParam(":school_year_id", $school_year_id);
    }

    if($course_id !== ""){
        $stmt->bindValue(":course_id", $course_id);
    }

    $stmt->bindParam(":teacher_id", $teacher_id);
    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $subject_title = $row['subject_title'];
        $course_id = $row['course_id'];
        $subject_code = $row['subject_code'];
        $program_section = $row['program_section'];
        $subject_program_id = $row['subject_program_id'];
        $courseCourseId = $row['courseCourseId'];
        $schedule_day = $row['schedule_day'];
        $time = $row['schedule_time'];

        $term = $row['term'];
        $period = $row['period'];

        $subject_subject_program_id = $row['subject_subject_program_id'];
        
        $subject_schedule_course_id = $row['subject_schedule_course_id'];

        $sp_subject_code = $row['sp_subject_code'];

        $status = "";
        $hrs_per_week = "";


        $schedule->filterSubsequentOccurrencesSa($subject_titles_occurrences,
            $subject_title, $subject_schedule_course_id, $subject_program_id);

        $schedule->filterSubsequentOccurrencesSa($subject_code_occurrences,
            $subject_code, $subject_schedule_course_id, $subject_subject_program_id);
 
        $data[] = array(
            "subject_title" => $subject_title,
            "subject_code" => $subject_code,
            "program_section" => $program_section,
            "term_period" => $term . " - " . $period,
            "schedule_day" => $schedule_day,
            "schedule_time" => $time,
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

