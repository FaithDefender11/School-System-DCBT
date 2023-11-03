<?php

include('../../includes/config.php');
include('../../includes/classes/SchoolYear.php');
include('../../includes/classes/Section.php');
include('../../includes/classes/Enrollment.php');
include('../../includes/classes/SubjectProgram.php');

$school_year = new SchoolYear($con, null);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];


$enrollment = new Enrollment($con);

$generateFormId = $enrollment->GenerateEnrollmentFormId();
$enrollment_form_id = $enrollment->CheckEnrollmentFormIdExists($generateFormId);
        

$section = new Section($con, null);

$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;


$program_id = $_GET['id'] ?? NULL;

// var_dump($program_id);

$columnNames = array(
    'description',
    'code',
    'unit',
    'requisite',
    'grade_level',
    'semester'
);


$sortBy = $columnNames[$columnIndex];
// $sortBy = $columnNames[$columnIndex] ?? 'section_name'; // Default to submission_creation column if the selected column is not found in the array

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

## Search
$searchQuery = "";
if ($searchValue != '') {

    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    
    
    // $names = explode(" ", $searchValue);
    // $firstName = $names[0];
    // $lastName = isset($names[1]) ? $names[1] : "";


    // if (count($names) > 1) {
    //     $lastName = array_pop($names); // Remove the last element and assign it to the last name
    //     $firstName = implode(" ", $names); // The remaining parts are considered the first name
    // } else {
    //     $firstName = $names[0]; // Only one part, so it's the first name
    //     $lastName = ""; // No last name provided
    // }

    $searchQuery = " AND (

        t1.subject_title LIKE '%" . $searchValue . "%' OR
        t1.subject_code LIKE '%" . $searchValue . "%'
   
    )";
}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM subject_program as t1

    WHERE program_id=:program_id
    ORDER BY course_level,
    semester
 
    ");

$stmt->bindParam("program_id", $program_id);
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];


    // $stmt = $con->prepare("SELECT 
    //     t1.*, t2.acronym, t2.program_name

    //     FROM subject_program as t1

    //     INNER JOIN program as t2 ON t2.program_id = t1.program_id
    //     WHERE department_type =:department_type

    // ");

    // $stmt->bindValue(":department_type", "SHS");
    // $stmt->execute();


    ## Total number of records with filtering
    $stmt = $con->prepare("SELECT COUNT(*) AS allcount 

        FROM subject_program as t1

        WHERE 1 $searchQuery
        AND program_id=:program_id
        ORDER BY course_level,
        semester
    
    ");

$stmt->bindParam("program_id", $program_id);
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {

//    ORDER BY $sortBy $sortOrder

    $stmt = $con->prepare("SELECT 
    
        t1.* 
        
        FROM subject_program as t1

        WHERE 1 $searchQuery
        AND t1.program_id=:program_id
        ORDER BY t1.course_level,
        t1.semester
        LIMIT " .$row . "," . $rowperpage

    );

    $stmt->bindParam("program_id", $program_id);
    $stmt->execute();
 
    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
 
        $subject_program_id = $row['subject_program_id'];
        $subject_title = $row['subject_title'];
        $course_level = $row['course_level'];
        $semester = $row['semester'];
        $subject_code = $row['subject_code'];
        $pre_req_subject_title = $row['pre_req_subject_title'];
        $subject_template_id = $row['subject_template_id'];
        $unit = $row['unit'];


        $removeSubjectProgramBtn = "removeSubjectProgramBtn($subject_program_id)";

        $button  = "
            <button type='button' value='$subject_program_id'
                class='editSubjectStrandBtn btn btn-primary btn-sm'>

                <i class='fas fa-edit'></i>
            </button>
            <button onclick='$removeSubjectProgramBtn'
                type='button' value='$subject_program_id'
                class='btn btn-danger btn-sm'>
                <i class='fas fa-trash'></i>
            </button>
        ";
        
 
        
        $data[] = array(
            "description" => "($subject_program_id) $subject_title",
            "code" => $subject_code,
            "unit" => $unit,
            "requisite" => $pre_req_subject_title,
            "grade_level" => $course_level,
            "semester" => $semester,
            "button_url" => $button,
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
