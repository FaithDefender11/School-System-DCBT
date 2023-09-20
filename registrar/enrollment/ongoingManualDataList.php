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
 

$columnNames = array(
    'student_id',
    'name',
    'section_name',
    'status',
);


$sortBy = $columnNames[$columnIndex];
// $sortBy = $columnNames[$columnIndex] ?? 'section_name'; // Default to submission_creation column if the selected column is not found in the array

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

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
    
    $searchQuery = " AND (

        (t1.firstname LIKE '%" . $firstName . "%' AND t1.lastname LIKE '%" . $lastName . "%') OR 
        t1.firstname LIKE '%" . $searchValue . "%' OR 
        t1.lastname LIKE '%" . $searchValue . "%' OR 
        t1.student_unique_id LIKE '%" . $searchValue . "%' OR
        t1.admission_status LIKE '%" . $searchValue . "%' OR
        t2.program_section LIKE '%" . $searchValue . "%'
    )";
}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM student as t1
    LEFT JOIN course as t2 ON t2.course_id = t1.course_id

    WHERE new_enrollee = :new_enrollee
    AND t1.course_id != 0
    AND t1.active = 1
 
    ");

$stmt->bindValue(":new_enrollee", 0);
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM student as t1
    LEFT JOIN course as t2 ON t2.course_id = t1.course_id

    WHERE 1 $searchQuery

    AND new_enrollee = :new_enrollee
    AND t1.course_id != 0
    AND t1.active = 1
   
");

$stmt->bindValue(":new_enrollee", 0);
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {
     
    $empQuery = "SELECT 

        t1.*,
        t2.program_section
        FROM student as t1
        LEFT JOIN course as t2 ON t2.course_id = t1.course_id

        WHERE 1 $searchQuery

        AND new_enrollee = :new_enrollee
        AND t1.course_id != 0
        AND t1.active = 1

        ORDER BY $sortBy $sortOrder

        LIMIT " . $row . "," . $rowperpage;
         
    $stmt = $con->prepare($empQuery);

    $stmt->bindValue(":new_enrollee", 0);
    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $student_unique_id = $row['student_unique_id'];
       
        $student_id = $row['student_id'];
        $program_section = $row['program_section'];
        $admission_status = $row['admission_status'];
        $name = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);

        $student_unique_id = "
            <a style='color: inherit' href='../student/record_details.php?id=$student_id&details=show'>
                $student_unique_id
            </a>
        ";
                                                      
        $processForm = "processForm(\"$enrollment_form_id\", $student_id, $current_school_year_id)";
        
        $button_url = "
            <button type='button' onclick='$processForm' class='btn-sm btn btn-primary'>
                Create form
            </button>
        ";

        $data[] = array(
            "student_id" => $student_unique_id,
            "name" => $name,
            "section_name" => $program_section,
            "status" => $admission_status,
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

    echo json_encode($response);
}
?>
