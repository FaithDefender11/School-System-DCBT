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
    'role',
    'description'
);


$sortBy = $columnNames[$columnIndex];
// $sortBy = $columnNames[$columnIndex] ?? 'section_name'; // Default to submission_creation column if the selected column is not found in the array

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

## Search
$searchQuery = "";

if ($searchValue != '') {

    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    
    
    // $names = explode(" ", $searchValue);
    // // $firstName = $names[0];
    // // $lastName = isset($names[1]) ? $names[1] : "";


    // if (count($names) > 1) {
    //     $lastName = array_pop($names); // Remove the last element and assign it to the last name
    //     $firstName = implode(" ", $names); // The remaining parts are considered the first name
    // } else {
    //     $firstName = $names[0]; // Only one part, so it's the first name
    //     $lastName = ""; // No last name provided
    // }

    // $firstName = trim(strtolower($firstName));
    // $lastName = trim(strtolower($lastName));
    
    $searchQuery = " AND (
        t1.description LIKE '%" . $searchValue . "%' OR
        t1.role LIKE '%" . $searchValue . "%'
      
    )";
}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM users_log AS t1
 
    ");

$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

                            
    FROM users_log AS t1

    WHERE 1 $searchQuery

    ORDER BY t1.users_log_id DESC
    
");

$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {
     
    # Filter out all old student that has enrollment ID
    $empQuery = "SELECT t1.* 
                            
        FROM users_log AS t1

        WHERE 1 $searchQuery

        ORDER BY t1.users_log_id DESC
        -- ORDER BY $sortBy $sortOrder

        LIMIT " . $row . "," . $rowperpage;
         
    $stmt = $con->prepare($empQuery);

    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $role = $row['role'];
        $users_log_id = $row['users_log_id'];
        $description = $row['description'];


        $data[] = array(
            "role" => $role,
            "description" => $description
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
