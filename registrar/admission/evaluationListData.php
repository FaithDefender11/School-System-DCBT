<?php
include('../../includes/config.php');
include('../../includes/classes/Section.php');
include('../../includes/classes/Enrollment.php');
include('../../includes/classes/SchoolYear.php');


$school_year = new SchoolYear($con, null);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];

$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;


// $new_filter = $_GET['new'] ?? NULL;

$admission_type_filter = $_GET['admission_type_filter'] ?? NULL;

$columnNames = array(
    'student_id',
    'name',
    'email',
    'type',
    'acronym',
    'submission_creation',
);

$sortBy = $columnNames[$columnIndex] ?? 'submission_creation'; // Default to submission_creation column if the selected column is not found in the array

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC



## Search
$searchQuery = "";
if ($searchValue != '') {
    $searchValue = trim(strtolower($searchValue)); 


    // $names = explode(" ", $searchValue);
    // $firstName = $names[0];
    // $lastName = isset($names[1]) ? $names[1] : "";
    // $firstName = trim(strtolower($firstName));
    // $lastName = trim(strtolower($lastName));

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
   
    // echo $searchValue;
    $searchQuery = " AND (
        (firstname LIKE '%" . $firstName . "%' AND lastname LIKE '%" . $lastName . "%') OR 
        firstname LIKE '%" . $searchValue . "%' OR 
        lastname LIKE '%" . $searchValue . "%' OR 
        student_unique_id LIKE '%" . $searchValue . "%' OR
        t1.admission_status LIKE '%" . $searchValue . "%' OR
        t3.program_section LIKE '%" . $searchValue . "%' OR

        t5.program_section LIKE '%" . $searchValue . "%' OR

        t2.enrollment_form_id LIKE '%" . $searchValue . "%' OR
        t4.acronym LIKE '%" . $searchValue . "%' OR
        email LIKE '%" . $searchValue . "%'
    )";
}

$pendingSearchQuery = "";

if ($searchValue != '') {
    $searchValue = trim(strtolower($searchValue)); 

    $names = explode(" ", $searchValue);
    $firstName = $names[0];
    $lastName = isset($names[1]) ? $names[1] : "";

    $firstName = trim(strtolower($firstName));
    $lastName = trim(strtolower($lastName));

    $pendingSearchQuery = " AND (
        (firstname LIKE '%" . $firstName . "%' AND lastname LIKE '%" . $lastName . "%') OR 
        firstname LIKE '%" . $searchValue . "%' OR 
        lastname LIKE '%" . $searchValue . "%' OR 
        email LIKE '%" . $searchValue . "%' OR
        t2.acronym LIKE '%" . $searchValue . "%' OR
        category LIKE '%" . $searchValue . "%'
    )";
}

    // AND (t1.student_status = 'EVALUATION'
    // OR t1.student_status = 'WITHDRAW')

## Total number of records without filtering (unans)
$allCountQuery = "SELECT COUNT(*) as allcount
        FROM pending_enrollees 
        -- WHERE student_status != 'APPROVED' 
        WHERE student_status = 'EVALUATION'
        AND is_finished = 1
        AND school_year_id = :pending_school_year_id
 
        UNION ALL

        SELECT COUNT(*) as allcount FROM student AS t1

        INNER JOIN enrollment AS t2 ON t2.student_id = t1.student_id


        LEFT JOIN course AS t3 ON t3.course_id = t2.course_id
        -- LEFT JOIN course AS t5 ON t5.course_id = t1.course_id
        LEFT JOIN program AS t4 ON t4.program_id = t3.program_id

        AND t1.active = 1
        AND (t2.course_id = t1.course_id OR t2.course_id = 0 OR t2.course_id != 0)
        AND t2.registrar_evaluated = 'no'
        AND t2.cashier_evaluated = 'no'
        AND t2.enrollment_status = 'tentative'
        AND t2.school_year_id = :school_year_id


        
        ";

// Execute the combined query
$stmt = $con->prepare($allCountQuery);

$stmt->bindValue(":pending_school_year_id", $current_school_year_id);
$stmt->bindValue(":school_year_id", $current_school_year_id);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $result['allcount'];


$student_admission_status_filtering = "";
if($admission_type_filter !== ""){
    $student_admission_status_filtering = "AND t1.admission_status=:admission_status";
}

$pending_admission_status_filtering = "";
if($admission_type_filter !== ""){
    $pending_admission_status_filtering = "AND t1.category=:category";
}

## Total number of records with filtering
$studentCountQuery = "SELECT COUNT(*) as student_count

    FROM student AS t1

    INNER JOIN enrollment AS t2 ON t2.student_id = t1.student_id
    AND t1.active = 1
    AND (t2.course_id = t1.course_id OR t2.course_id = 0 OR t2.course_id != 0)
    AND t2.registrar_evaluated = 'no'
    AND t2.cashier_evaluated = 'no'
    AND t2.enrollment_status = 'tentative'
    LEFT JOIN course AS t3 ON t3.course_id = t1.course_id

    LEFT JOIN course AS t5 ON t5.course_id = t2.course_id

    LEFT JOIN program AS t4 ON t4.program_id = t3.program_id
    WHERE 1 $searchQuery
    $student_admission_status_filtering

    AND t2.school_year_id = :school_year_id

    ";
    
    // Query for the "pending_enrollees" table
    $pendingEnrolleesCountQuery = "SELECT COUNT(*) as pending_count

    FROM pending_enrollees AS t1

    LEFT JOIN program AS t2 ON t2.program_id = t1.program_id

    WHERE 1 $pendingSearchQuery
    AND t1.student_status = 'EVALUATION'
    AND t1.is_finished = 1
    $pending_admission_status_filtering
    AND t1.school_year_id = :pending_school_year_id
    
    ";


$stmt = $con->prepare($studentCountQuery);


if($student_admission_status_filtering !== ""){
    $stmt->bindValue(":admission_status", $admission_type_filter);
}


$stmt->bindValue(":school_year_id", $current_school_year_id);

$stmt->execute();
$studentCount = $stmt->fetch(PDO::FETCH_ASSOC)['student_count'];

// Execute the query for the "pending_enrollees" table
$stmt = $con->prepare($pendingEnrolleesCountQuery);

if($pending_admission_status_filtering !== ""){
    $stmt->bindValue(":category", $admission_type_filter);
}

$stmt->bindValue(":pending_school_year_id", $current_school_year_id);
$stmt->execute();

$pendingCount = $stmt->fetch(PDO::FETCH_ASSOC)['pending_count'];

// Calculate the total count by summing the counts from both tables
$totalCount = $studentCount + $pendingCount;


$totalRecordwithFilter = $totalCount;



## Fetch records
$empQuery = "SELECT 
    t1.firstname,
    t1.lastname,
    t1.email,
    t1.student_statusv2,
    t1.admission_status,
    t2.enrollment_date AS submission_creation,

    t3.program_id,

    t3.program_id AS student_program_id,

    NULL AS new_enrollee_program_id,

    NULL AS student_status_pending,
    NULL AS enrollee_status,

    t1.is_tertiary AS student_classification,
    NULL AS pending_enrollees_id,

    t1.course_id AS student_course_id,

    t1.student_id AS student_id,
    t1.student_unique_id AS student_unique_id,
    t1.new_enrollee AS new_enrollee,
    t2.student_status AS enrollment_student_status,
    t2.is_new_enrollee AS enrollment_is_new_enrollee,
    t2.is_transferee AS enrollment_is_transferee,
    t2.enrollment_form_id AS enrollment_form_id,
    t2.enrollment_id AS enrollment_id,
    t2.course_id AS enrollment_course_id,
    t3.course_id AS section_course_id

    FROM student AS t1

    INNER JOIN enrollment AS t2 ON t2.student_id = t1.student_id
    AND t1.active = 1

    AND (t2.course_id = t1.course_id OR t2.course_id = 0 OR t2.course_id != 0)
    
    AND t2.registrar_evaluated = 'no'
    AND t2.cashier_evaluated = 'no'
    AND t2.enrollment_status = 'tentative'

    -- ORIGINAL IS t3 to t1 (Search for Student Course Id table)
    LEFT JOIN course AS t3 ON t3.course_id = t1.course_id

    -- * Search for Enrollment Course Id table
    -- For non-enrolled search for Strand
    LEFT JOIN course AS t5 ON t5.course_id = t2.course_id
    LEFT JOIN program AS t4 ON t4.program_id = t3.program_id

    WHERE 1 $searchQuery
    AND t2.school_year_id =:school_year_id
    -- AND t1.admission_status =:admission_status
    $student_admission_status_filtering

    UNION

    SELECT 

        t1.firstname,
        t1.lastname,
        t1.email,
        NULL AS student_statusv2,
        NULL AS admission_status,
        t1.date_creation AS submission_creation,
        t1.program_id,
        t2.program_id AS new_enrollee_program_id,

        NULL AS student_program_id,

        t1.admission_status AS student_status_pending,
        t1.student_status AS enrollee_status,
        NULL AS student_classification,
        t1.pending_enrollees_id,
        NULL AS student_course_id,
        NULL AS student_id,
        NULL AS student_unique_id,
        NULL AS new_enrollee,
        NULL AS enrollment_student_status,
        NULL AS enrollment_is_new_enrollee,
        NULL AS enrollment_is_transferee,
        NULL AS enrollment_form_id,
        NULL AS enrollment_id,
        NULL AS enrollment_course_id,
        NULL AS section_course_id

    FROM pending_enrollees AS t1
    LEFT JOIN program AS t2 ON t2.program_id = t1.program_id

    WHERE 1 $pendingSearchQuery 

    -- AND t1.student_status != 'APPROVED'

    AND t1.student_status = 'EVALUATION'
    AND t1.school_year_id = :pending_school_year_id
    $pending_admission_status_filtering

    AND t1.is_finished = 1

    ORDER BY $sortBy $sortOrder 
    
    LIMIT " . $row . "," . $rowperpage;

$stmt = $con->prepare($empQuery);
$stmt->bindValue(":school_year_id", $current_school_year_id);
$stmt->bindValue(":pending_school_year_id", $current_school_year_id);

if($student_admission_status_filtering !== ""){
    $stmt->bindValue(":admission_status", $admission_type_filter);
}

if($pending_admission_status_filtering !== ""){
    $stmt->bindValue(":category", $admission_type_filter);
}

// $stmt->bindValue(":admission_status", $admission_type_filter);

$stmt->execute();

$data = array();

$section = new Section($con, null);
$enrollment = new Enrollment($con, null);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    $submission_creation = $row['submission_creation'];
    $submission_creation = date("F d, Y h:i a", strtotime($submission_creation));

    $student_status_pending = $row['student_status_pending'];
    $pending_enrollees_id = $row['pending_enrollees_id'];

    $student_course_id = $row['student_course_id'];
    
    $enrollment_course_id = $row['enrollment_course_id'];

    $section_course_id = $row['section_course_id'];

    
    $student_id = $row['student_id'];
    $enrollment_form_id = $row['enrollment_form_id'] ?? "-";
    $enrollment_id = $row['enrollment_id'] ?? NULL;
    // $course_id = $row['course_id'];
    $enrollment_course_id = $row['enrollment_course_id'] ?? NULL;

    # If has enrollment_course_id and subject -> Subject Insertion Summary
    # If hasnt subject -> Finding Section

    $enrollment_url  = "";

    if($enrollment_course_id == NULL){
        $enrollment_url  = "
            process_enrollment.php?find_section=show&st_id=$student_id&c_id=0
        ";

    }else{
        $enrollment_url  = "
            subject_insertion_summary.php?id=$enrollment_id&enrolled_subject=show
        ";
    }
    // Trying to decide
    $enrollment_url = "";
    if($enrollment_form_id != "-"){
        $enrollment_form_id = "
            <a style='all:unset' href='$enrollment_url'>
                $enrollment_form_id
            </a>
        ";
    }



    $student_unique_id = $row['student_unique_id'];
    $enrollee_status = $row['enrollee_status'];

    // $student_unique_id = $student_unique_id != NULL ? $student_unique_id : "N/A";
    $student_unique_id = $row['student_unique_id'] ?? "-";

    $student_program_id = $row['student_program_id'];
    
    $new_enrollee_program_id = $row['new_enrollee_program_id'];
    $program_id = $row['program_id'];

    
   
    

    $new_enrollee = $row['new_enrollee'];
    $enrollment_student_status = $row['enrollment_student_status'];
    $enrollment_is_new_enrollee = $row['enrollment_is_new_enrollee'];
    $enrollment_is_transferee = $row['enrollment_is_transferee'];


    $student_statusv2 = $row['student_statusv2'];
    $admission_status = $row['admission_status'];
    $student_classification = $row['student_classification'];

    $identity = "";
    $type = "";

    $button_url = "";

    # If student has manually enrolled by registrar and have an enrollment_course_id
    # and officially not enrolled
    if(($student_course_id == "" || $student_course_id == NULL)
        && $enrollment_course_id != 0 && $enrollment_is_new_enrollee == 1){
        // echo $enrollment_course_id;
        // echo "<br>";

        $program_id = $section->GetSectionProgramId($enrollment_course_id);
        $acronym = $section->GetAcronymByProgramId($program_id);
        // $program_id = "";
    }

    if($student_classification != NULL){
        # 1 -> Tertiary, 0 -> SHS
        $identity = $student_classification == 1 ? "Tertiary" 
            : ($student_classification == 0 ? "SHS" : "Pending");
    }

    $process_url = "process_enrollment.php?enrollee_details=true&id=$pending_enrollees_id";

    $student_table_enrollment_url = "process_enrollment.php?details=show&st_id=$student_id";

    $advicing_pending_enrollment_url = "advising_pending_process_enrollment.php?details=show&id=$pending_enrollees_id";
    
    $fullname = $row['firstname'] . " " . $row['lastname'];

    $acronym = $section->GetAcronymByProgramId($program_id);

    // echo $acronym;
    // echo "<br>";

    // All Ongoing Student Type should based on Previous Admission/Student Status

    // 1st sem
    // 1. New Not Transferee
    // 2. New And Transferee
    // 3. Ongoing Irregular

    // 2nd sem
    // 2. New And Transferee
    // 3. Ongoing Irregular

    # Enrolle In Evaluation

    # ~ New
    # Strand Only (ABE, STEM) 

    # ~ Old
    # Section (ABE1-A, STEM11-A).

    $updated_type = "";
    $strand_output = "";
    
    if($new_enrollee == 0
        && $enrollment_is_new_enrollee == 0 
        && $enrollment_is_transferee == 0
        && $student_statusv2 == "Irregular"
        // && ($enrollment_student_status == "" || $enrollment_student_status == "Irregular")
        ){

        $updated_type = "Old Irregular";
        if($student_course_id != 0){
            $section = new Section($con, $student_course_id);
            $acronym = $section->GetSectionName();
        }
        $button_url = "
            <button class='default'
                onclick=\"window.location.href = '" . $student_table_enrollment_url . "'\">
                Evaluate
            </button>
        ";
    }
    
    else if($new_enrollee == 0
        && $enrollment_is_new_enrollee == 0 
        && $enrollment_is_transferee == 0
        && $student_statusv2 == "Regular"
        // && $enrollment_student_status == "Regular"
        ){

            
        $updated_type = "Old Regular";
        if($student_course_id != 0){
            $section = new Section($con, $student_course_id);
            $acronym = $section->GetSectionName();
            // $acronym = $section->GetAcronymByProgramId($program_id);
        }
        // echo $program_id;
        // echo "<br>";

        // $acronym = $student_program_id;

        $button_url = "
            <button class='default'
            onclick=\"window.location.href = '" . $student_table_enrollment_url . "'\">
                Evaluate
            </button>
        ";
    }

    else if($new_enrollee == 1
        && $enrollment_is_new_enrollee == 1 
        && $enrollment_is_transferee == 0
        && $student_statusv2 == ""
        // && $enrollment_student_status == "Regular"
        ){

    $updated_type = "New Regular";

    $button_url = "
        <button class='default'
            onclick=\"window.location.href = '" . $student_table_enrollment_url . "'\">
            Evaluate
        </button>
    ";
    }
    // 
    else if($new_enrollee == 1
        && $enrollment_is_new_enrollee == 1 
        && $enrollment_is_transferee == 1
        && $student_statusv2 == ""
        // && $enrollment_student_status == ""
        ){
 
        $updated_type = "New Transferee";

        $button_url = "
            <button class='default'
                onclick=\"window.location.href = '" . $student_table_enrollment_url . "'\">
                Evaluate
            </button>
        ";
    }
    
    else if($student_status_pending === "Transferee"){

        $updated_type = "New Transferee Enrollee";

        $button_url = "
            <button class='default'
                onclick=\"window.location.href = '" . $process_url . "'\">
                Evaluate
            </button>
        ";
    }

    else if($student_status_pending === "Standard"
        ){

        $updated_type = "New Enrollee";
        // $acronym = $section->GetAcronymByProgramId($new_enrollee_program_id);
        // $strand_output = $acronym;

        $button_url = "
            <button class='default'
                onclick=\"window.location.href = '" . $process_url . "'\">
                Evaluate
            </button>
        ";
    }


    // $student_unique_id = $row['student_unique_id'] !== NULL ? $row['student_unique_id'] : "N/A";
    $email = $row['email'] !== NULL ? $row['email'] : "N/A";
 
    // if($enrollment_is_new_enrollee == 0 && $student_course_id != 0){
    //     $section = new Section($con, $student_course_id);
    //     $acronym = $section->GetSectionName();
    // }

    // if($enrollment_is_new_enrollee == 0 && $student_course_id != 0){
    //     $section = new Section($con, $student_course_id);
    //     $acronym = $section->GetSectionName();
    // }

    // # Student is new Enrollee and has enrollment course id.
    // if($acronym == "" && $new_enrollee == 1){

    //     $enrollment_course_id = $enrollment->GetEnrollmentFormCourseIdByForm(
    //         $student_id,
    //         $enrollment_id, $current_school_year_id);

    //         // echo $enrollment_course_id;
    //         // echo "<br>";

    //     $section = new Section($con, $section_course_id);
    //     $acronym = $section->GetSectionName();
    // }
 
    $data[] = array(
        "student_id" => $student_unique_id,
        "enrollment_form_id" => $enrollment_form_id,
        "name" => ucfirst($row['firstname']) . ' ' . ucfirst($row['lastname']),
        // "email" => $email,
        "type" => $updated_type,
        "acronym" => $acronym,
        "submission_creation" => $submission_creation,
        "button_url" => $button_url,
    );



}

## Response
$response = array(
    "draw" => intval($_POST['draw']),
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalRecordwithFilter,
    "data" => $data
);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo json_encode($response);
?>
