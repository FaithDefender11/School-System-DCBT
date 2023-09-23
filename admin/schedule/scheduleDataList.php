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
 
$schedule = new Schedule($con);
 
$columnNames = array(
    'program_section',
    'subject_code',
    'term',
    'period',
    'day',
    'time',
    'room',
    'type',
    'instructor'
);

$sortBy = $columnNames[$columnIndex];

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC';  


## Search
$searchQuery = "";
if ($searchValue != '') {

    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    
    $searchQuery = " AND (
        t2.program_section LIKE '%" . $searchValue . "%' OR 
        t1.subject_code LIKE '%" . $searchValue . "%' OR
        t1.schedule_time LIKE '%" . $searchValue . "%'

    )";
}
 

// $stmt = $con->prepare("SELECT COUNT(*) AS allcount FROM student");

$default_shs_course_level = 11;
$is_new_enrollee = 1;
$is_transferee = 1;
$regular_Status = "Regular";
$enrollment_status = "tentative";
$registrar_evaluated = "yes";



## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount

    FROM subject_schedule AS t1

    INNER JOIN course AS t2 ON t2.course_id = t1.course_id
    INNER JOIN school_year AS t3 ON t3.school_year_id = t1.school_year_id
    INNER JOIN program AS t4 ON t4.program_id = t2.program_id
");
$stmt->execute();
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];



## Total number of records with filtering

// $stmt = $con->prepare("SELECT COUNT(*) AS allcount

//     FROM subject_schedule AS t1

//     INNER JOIN course AS t2 ON t2.course_id = t1.course_id
//     INNER JOIN school_year AS t3 ON t3.school_year_id = t1.school_year_id

//     WHERE 1 " . $searchQuery . "
// ");

$school_year_condition = "";

if($school_year_id !== ""){
    $school_year_condition = "AND t1.school_year_id=:school_year_id";
}

$course_condition = "";

if($course_id !== ""){
    $course_condition = "AND t2.course_id=:course_id";
}

$program_condition = "";
$program_join_condition = "";

if($program_id !== ""){
    $program_join_condition = "INNER JOIN program AS t4 ON t4.program_id = t2.program_id";
    $program_condition = "AND t4.program_id=:program_id";
}



$stmt = $con->prepare("SELECT COUNT(*) AS allcount

    FROM subject_schedule AS t1

    INNER JOIN course AS t2 ON t2.course_id = t1.course_id
    INNER JOIN school_year AS t3 ON t3.school_year_id = t1.school_year_id
    $program_join_condition

    WHERE 1 " . $searchQuery . "
    $school_year_condition
    $program_condition
    $course_condition
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

    $school_year_condition = "";
    if($school_year_id !== ""){
        $school_year_condition = "AND t1.school_year_id=:school_year_id";
    }

    $program_condition = "";
    $program_join_condition = "";

    if($program_id !== ""){
        $program_join_condition = "INNER JOIN program AS t7 ON t7.program_id = t2.program_id";
        $program_condition = "AND t7.program_id=:program_id";
    }
    

    $course_condition = "";

    if($course_id !== ""){
        $course_condition = "AND t2.course_id=:course_id";
    }

    $empQuery = "SELECT 
        t1.*,
        t2.program_section, t2.school_year_term,
        t3.term, t3.period,

        t4.firstname,
        t4.lastname,
        t4.teacher_id,

        t5.department_type,
        t6.room_number,
        t6.room_name

        FROM subject_schedule AS t1

        INNER JOIN course AS t2 ON t2.course_id = t1.course_id
        INNER JOIN school_year AS t3 ON t3.school_year_id = t1.school_year_id

        LEFT JOIN teacher AS t4 ON t4.teacher_id = t1.teacher_id
        LEFT JOIN subject_program AS t5 ON t5.subject_program_id = t1.subject_program_id
        LEFT JOIN room AS t6 ON t6.room_id = t1.room_id

        $program_join_condition

        WHERE 1 $searchQuery 
        $school_year_condition
        $program_condition
        $course_condition

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

        // $room = $row['room'];

        $subject_code = $row['subject_code'];
        $schedule_day = $row['schedule_day'];
        $schedule_time = $row['schedule_time'];
        $program_section = $row['program_section'];
        $school_year_term = $row['school_year_term'];
        $subject_schedule_id = $row['subject_schedule_id'];

        $room_number = $row['room_number'] === NULL ? "TBA" : $row['room_number'];
        $room_name = $row['room_name'];

        
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];

        
        $teacher_id = $row['teacher_id'];

        // var_dump($teacher_id);
        $teacherName = "";
        if($teacher_id !== NULL){
            $teacherName = ucwords($firstname) . " " . ucwords($lastname);
        }else if($teacher_id === NULL){
            $teacherName = "TBA";
        }


         
        $department_type = $row['department_type'];


        $term = $row['term'];
        $period = $row['period'];

        $removeDepartmentBtn= "";

        $days = $schedule->convertToDays($schedule_day);

        $url = "../section/show.php?id=1213&term=$term&per_semester=$period";
        // $url = "#";
        
        $program_section = "
           <a id='clickSectionRedirect' style='color: inherit' href='$url'>$program_section</a>
        ";

        $button_url = "
            <button onclick='window.location.href=\"edit.php?id=$subject_schedule_id\"'
                class='btn btn-primary btn-sm'>
                    <i class='fas fa-pen'></i>
                </button>
        ";

        $data[] = array(

            "program_section" => $program_section,
            "subject_code" => $subject_code,
            "term" => $term,
            "period" => $period,
            "day" => $schedule_day,
            "time" => $schedule_time,
            "room" => $room_number,
            "type" => $department_type,
            "instructor" => "$teacherName",
            "button_url" => "$button_url",
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

