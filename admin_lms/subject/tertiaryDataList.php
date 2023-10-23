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

$generateFormId = $enrollment->GenerateEnrollmentFormId($current_school_year_id);
$enrollment_form_id = $enrollment->CheckEnrollmentFormIdExists($generateFormId);
        

$section = new Section($con, null);

$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;
 
$columnNames = array(
    'subject_program_id',
    'subject_type',
    'subject_title',
    'number_enrolled'
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

        t1.subject_title LIKE '%" . $searchValue . "%'
   
    )";
}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM subject_program as t1

    INNER JOIN program as t2 ON t2.program_id = t1.program_id
    WHERE department_type =:department_type
 
    ");

$stmt->bindValue(":department_type", "Tertiary");
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

    INNER JOIN program as t2 ON t2.program_id = t1.program_id

    WHERE 1 $searchQuery

    AND department_type =:department_type
   
");
 
$stmt->bindValue(":department_type", "Tertiary");
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {

    $stmt = $con->prepare("SELECT 
        t1.*, t2.acronym, t2.program_name

        FROM subject_program as t1

        INNER JOIN program as t2 ON t2.program_id = t1.program_id

        WHERE 1 $searchQuery

        AND department_type =:department_type

        ORDER BY $sortBy $sortOrder

        LIMIT " .$row . "," . $rowperpage
    );

    $stmt->bindValue(":department_type", "Tertiary");
    $stmt->execute();
 
    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $subject_program_id = $row['subject_program_id'];
        $subject_code = $row['subject_code'];
        $subject_title = $row['subject_title'];
        $subject_type = $row['subject_type'];

        $acronym = $row['acronym'];
        $program_name = $row['program_name'];

        $program_code = $subject_code;

        $sectionHasSameCodeUrl = "section_code_list.php?id=$subject_program_id";
        
        $subjectProgram = new SubjectProgram($con);

        // $program_code = $subjectProgram->GetSubjectProgramRawCode();

        $sectionsHaveProgramCode = $subjectProgram->GetSectionsHaveProgramCode($program_code);

        $count = count($sectionsHaveProgramCode);

        $code_type = $subject_type === "Core" 
            ? "Universal" : ($subject_type == "Specialized" || $subject_type == "Applied" ? $acronym : "");

        $button_url = "
            <a href='code_topics.php?id=$subject_program_id'>
                <button class='btn btn-primary'>
                    <i class='fas fa-eye'></i>
                </button>
            </a>
        ";

        $number_enrolled = "
            <a style='color: inherit;' href='$sectionHasSameCodeUrl'>
                $count
            </a>
        ";
        
        $data[] = array(
            "subject_program_id" => $subject_program_id,
            "subject_type" => $code_type,
            "subject_title" => $subject_title,
            "number_enrolled" => $number_enrolled,
            "button_url" => $button_url,
        );

    }

        // { data: 'subject_id', orderable: false },  
        // { data: 'subject_type', orderable: false },  
        // { data: 'subject_title', orderable: false },  
        // { data: 'number_enrolled', orderable: false },  
        // { data: 'button_url', orderable: false }

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
