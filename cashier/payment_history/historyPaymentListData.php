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
 
$payment_method_filter = $_GET['payment_method_filter'] ?? NULL;
$payment_status_filter = $_GET['payment_status_filter'] ?? NULL;

$school_year_id_filter = $_GET['school_year_id_filter'] ?? NULL;
$program_id_filter = $_GET['program_id_filter'] ?? NULL;
$course_level_filter = $_GET['course_level_filter'] ?? NULL;
$course_id_filter = $_GET['course_id_filter'] ?? NULL;



$payment_method_filtering = "";
$payment_status_filtering = "";

$school_year_id_filtering = "";
$program_id_filtering = "";
$course_level_filtering = "";
$course_id_filtering = "";

 
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

        // t1.firstname LIKE '%" . $searchValue . "%' OR 
        // t1.lastname LIKE '%" . $searchValue . "%' OR

    $searchQuery = " AND (


        (t1.firstname LIKE '%" . $firstName . "%' AND t1.lastname LIKE '%" . $lastName . "%') OR 
        t1.firstname LIKE '%" . $searchValue . "%' OR 
        t1.lastname LIKE '%" . $searchValue . "%' OR 


        t2.enrollment_form_id LIKE '%" . $searchValue . "%' OR
        t3.program_section LIKE '%" . $searchValue . "%' OR

        t1.student_unique_id LIKE '%" . $searchValue . "%' OR

        t2.payment_status LIKE '%" . $searchValue . "%'


    )";
}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM student AS t1

    INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id

    AND t2.registrar_evaluated = :registrar_evaluated
    AND t2.cashier_evaluated = :cashier_evaluated
    AND t2.payment_status IS NOT NULL 
    AND t2.payment_method IS NOT NULL 

    INNER JOIN course AS t3 ON t3.course_id = t2.course_id
 
    INNER JOIN school_year AS t4 ON t4.school_year_id = t2.school_year_id

    INNER JOIN program AS t5 ON t5.program_id = t3.program_id

    ");


// $stmt->bindValue(":school_year_id", 3);
$stmt->bindValue(":registrar_evaluated", "yes");
$stmt->bindValue(":cashier_evaluated", "yes");

$stmt->execute();
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];

if($payment_method_filter !== ""){
    $payment_method_filtering = "AND t2.payment_method=:payment_method";
}

if($payment_status_filter !== ""){
    $payment_status_filtering = "AND t2.payment_status=:payment_status";
}

if($school_year_id_filter !== ""){
    $school_year_id_filtering = "AND t2.school_year_id=:school_year_id";
}

if($program_id_filter !== ""){
    $program_id_filtering = "AND t5.program_id=:program_id";
}

if($course_level_filter !== ""){
    $course_level_filtering = "AND t3.course_level=:course_level";
}

if($course_id_filter !== ""){
    $course_id_filtering = "AND t3.course_id=:course_id";
}


## Total number of records with filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
        FROM student AS t1
    
        INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id
        AND t2.registrar_evaluated = :registrar_evaluated
        AND t2.cashier_evaluated = :cashier_evaluated
        AND t2.payment_status IS NOT NULL 
        AND t2.payment_method IS NOT NULL 
        $payment_method_filtering
        $payment_status_filtering

        INNER JOIN course AS t3 ON t3.course_id = t2.course_id

        INNER JOIN school_year AS t4 ON t4.school_year_id = t2.school_year_id

        INNER JOIN program AS t5 ON t5.program_id = t3.program_id

        WHERE 1 $searchQuery
        $school_year_id_filtering
        $program_id_filtering
        $course_id_filtering
        $course_level_filtering


    ");
 
$stmt->bindValue(":registrar_evaluated", "yes");
$stmt->bindValue(":cashier_evaluated", "yes");


if($payment_method_filtering !== ""){
    $stmt->bindValue(":payment_method", $payment_method_filter);
}

if($payment_status_filtering !== ""){
    $stmt->bindValue(":payment_status", $payment_status_filter);
}

if($school_year_id_filtering !== ""){
    $stmt->bindValue(":school_year_id", $school_year_id_filter);
}

if($program_id_filtering !== ""){
    $stmt->bindValue(":program_id", $program_id_filter);
}

if($course_id_filtering !== ""){
    $stmt->bindValue(":course_id", $course_id_filter);
}

if($course_level_filtering !== ""){
    $stmt->bindValue(":course_level", $course_level_filter);
}


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
        t2.payment_status,
        t2.payment_method,

        t3.program_section,

        t4.term,
        t4.period

        FROM student AS t1
    
        INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id

        AND t2.cashier_evaluated = :cashier_evaluated
        AND t2.registrar_evaluated = :registrar_evaluated
        AND t2.payment_status IS NOT NULL 
        AND t2.payment_method IS NOT NULL 
        $payment_method_filtering
        $payment_status_filtering
        

        INNER JOIN course AS t3 ON t3.course_id = t2.course_id

        INNER JOIN school_year AS t4 ON t4.school_year_id = t2.school_year_id

        INNER JOIN program AS t5 ON t5.program_id = t3.program_id


        WHERE 1 $searchQuery

        $school_year_id_filtering
        $program_id_filtering
        $course_id_filtering
        $course_level_filtering

        ORDER BY $sortBy $sortOrder
        
        LIMIT " . $row . "," . $rowperpage;


    $stmt = $con->prepare($empQuery);

    $stmt->bindValue(":cashier_evaluated", "yes");
    $stmt->bindValue(":registrar_evaluated", "yes");

    if($payment_method_filtering !== ""){
        $stmt->bindValue(":payment_method", $payment_method_filter);
    }

    if($payment_status_filtering !== ""){
        $stmt->bindValue(":payment_status", $payment_status_filter);
    }

    if($school_year_id_filtering !== ""){
        $stmt->bindValue(":school_year_id", $school_year_id_filter);
    }

    if($program_id_filtering !== ""){
        $stmt->bindValue(":program_id", $program_id_filter);
    }

    if($course_id_filtering !== ""){
        $stmt->bindValue(":course_id", $course_id_filter);
    }

    if($course_level_filtering !== ""){
        $stmt->bindValue(":course_level", $course_level_filter);
    }


    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        
        $term = $row['term'];
        $period = $row['period'];

        $enrollment_id = $row['enrollment_id'];
        
        $enrollment_form_id = $row['enrollment_form_id'];
        $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);

        $course_level = $row['course_level'];
        $student_unique_id = $row['student_unique_id'];

        $cashier_confirmation_date = $row['cashier_confirmation_date'];

        // if($cashier_confirmation_date === NULL){
        //     $cashier_confirmation_date = "N/A";
        // }else{
        //     $cashier_confirmation_date = date("M d, Y H:i a", strtotime($cashier_confirmation_date));
        // }
        $cashier_confirmation_date = ($row['cashier_confirmation_date'] === NULL) ? "N/A" : date("M d, Y H:i a", strtotime($row['cashier_confirmation_date']));


        $username = $row['username'];
        $student_id = $row['t2_student_id'];
        $program_section = $row['program_section'];
        $course_level = $row['course_level'];
        $payment_status = $row['payment_status'];
        $payment_method = $row['payment_method'];

        $url = "../payment/payment_summary.php?id=$enrollment_id&enrolled_subject=show";
         
        $button_url = "
            <button onclick=\"window.location.href='$url'\" class='default information'>View</button>
        ";

        $period_acronym = $period === "First" ? "S1" : ($period==="Second" ? "S2" : "");

        $term_semester = "$term $period_acronym";
 
        $data[] = array(
            "enrollment_form_id" => $enrollment_form_id,
            "student_no" => $student_unique_id,
            "name" => $fullname,
            "term_semester" => $term_semester,
            
            "section" => $program_section,
            "status" => $payment_status,
            "method" => $payment_method,
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
