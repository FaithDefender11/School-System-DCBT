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
 
// $status_filter = $_GET['status'] ?? NULL;
// $status_filter = trim($status_filter);
 
$columnNames = array(
    'student_id',
    'name',
    'email',
    'type',
    'section_name',
    'enrollment_approve',
);

$sortBy = $columnNames[$columnIndex] ?? 'enrollment_approve'; // Default to submission_creation column if the selected column is not found in the array
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
        
        (t2.firstname LIKE '%" . $firstName . "%' AND t2.lastname LIKE '%" . $lastName . "%') OR 

        t2.lastname LIKE '%" . $searchValue . "%' OR 
        t2.firstname LIKE '%" . $searchValue . "%' OR 
        student_unique_id LIKE '%" . $searchValue . "%' OR
        email LIKE '%" . $searchValue . "%' OR
        program_section LIKE '%" . $searchValue . "%'
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

    FROM enrollment as t1
    INNER JOIN student as t2 ON t2.student_id = t1.student_id

    WHERE school_year_id=:school_year_id
    AND enrollment_status=:enrollment_status
    AND registrar_evaluated=:registrar_evaluated
    AND cashier_evaluated=:cashier_evaluated
    ");

$stmt->bindValue(":school_year_id", $current_school_year_id);
$stmt->bindValue(":enrollment_status", $enrollment_status);
$stmt->bindValue(":registrar_evaluated", $registrar_evaluated);
$stmt->bindValue(":cashier_evaluated", $cashier_evaluated);

$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];


## Total number of records with filtering

$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM enrollment as t1
    INNER JOIN student as t2 ON t2.student_id = t1.student_id
    LEFT JOIN course as t3 ON t3.course_id = t1.course_id

    WHERE 1 " . $searchQuery . " 
    AND school_year_id=:school_year_id
    AND enrollment_status=:enrollment_status
    AND registrar_evaluated=:registrar_evaluated
    AND cashier_evaluated=:cashier_evaluated
");


$stmt->bindValue(":school_year_id", $current_school_year_id);
$stmt->bindValue(":enrollment_status", $enrollment_status);
$stmt->bindValue(":registrar_evaluated", $registrar_evaluated);
$stmt->bindValue(":cashier_evaluated", $cashier_evaluated);
$stmt->execute();


$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {
     

    // $student_status_filtering = "";
    // if($status_filter !== ""){
    //     $student_status_filtering = "AND t2.active=:active";
    // }

    $empQuery = "SELECT 

        t1.student_id, 
        t1.enrollment_id, 
        t1.cashier_evaluated,
        t1.registrar_evaluated,
        t1.is_transferee,
        t1.enrollment_approve,
        t1.course_id AS enrollment_course_id,
        t1.enrollment_date,
        t1.registrar_confirmation_date,
        t1.is_new_enrollee AS enrollment_is_new_enrollee,
        t1.is_transferee AS enrollment_is_transferee,
        t1.student_status AS enrollment_student_status,
    
        t2.firstname,
        t2.username, t2.student_unique_id,
        t2.lastname
        ,t2.course_level,
        t2.email,
        t2.student_unique_id,

        t2.admission_status,t2.student_statusv2,
        t2.course_id, t2.student_id AS t2_student_id,
        t2.course_id, t2.course_level,t2.student_status,
        t2.is_tertiary, t2.new_enrollee,  
        
        
        t3.program_section,
        t3.course_id
    
        FROM enrollment as t1

        INNER JOIN student as t2 ON t2.student_id = t1.student_id

        LEFT JOIN course as t3 ON t3.course_id = t1.course_id

        WHERE 1 " . $searchQuery . " 

        AND school_year_id=:school_year_id
        AND enrollment_status=:enrollment_status
        AND registrar_evaluated=:registrar_evaluated
        AND cashier_evaluated=:cashier_evaluated

        ORDER BY $sortBy $sortOrder

        LIMIT " . $row . "," . $rowperpage;
         
    $stmt = $con->prepare($empQuery);


    $stmt->bindValue(":school_year_id", $current_school_year_id);
    $stmt->bindValue(":enrollment_status", $enrollment_status);
    $stmt->bindValue(":registrar_evaluated", $registrar_evaluated);
    $stmt->bindValue(":cashier_evaluated", $cashier_evaluated);

    // if($status_filter !== ""){
    //     $stmt->bindValue(":active", $status_filter);
    // }

    $stmt->execute();

    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $enrollement_student_id = $row['student_id'];
        $enrollment_id = $row['enrollment_id'];
        
        $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);
        $standing = $row['course_level'];
        $course_id = $row['course_id'];
        $enrollment_course_id = $row['enrollment_course_id'];

        $enrollment_approve = $row['enrollment_approve'];

        $enrollment_approvex = date("F d, Y", strtotime($enrollment_approve));

        $username = $row['username'];
        $student_unique_id = $row['student_unique_id'];
        $student_id = $row['t2_student_id'];
        $program_section = $row['program_section'];

        // echo $program_section;
        // echo "<br>";

        $cashier_evaluated = $row['cashier_evaluated'];
        $registrar_evaluated = $row['registrar_evaluated'];
        $course_level = $row['course_level'];

        $student_status = $row['student_statusv2'];
        $student_statusv2 = $row['student_statusv2'];
        // $admission_status = $row['admission_status'];

        $new_enrollee = $row['new_enrollee'];
        $enrollment_student_status = $row['enrollment_student_status'];
        $enrollment_is_new_enrollee = $row['enrollment_is_new_enrollee'];
        $enrollment_is_transferee = $row['enrollment_is_transferee'];


        $new_enrollee = $row['new_enrollee'];
        $is_tertiary = $row['is_tertiary'];
        // $is_transferee = $row['is_transferee'];

        $process_url = "";



        $enrollmentSubjectsUrl = "subject_insertion_summary.php?id=$enrollment_id&enrolled_subject=show";

        $student_status_pending = "";

        $updated_type = "";
        $button_url = "";
        $strand = "";
 
        $section = new Section($con, $enrollment_course_id);

        $sectionProgramId = $section->GetSectionProgramId($enrollment_course_id);
        $sectionAcronym = $section->GetAcronymByProgramId($sectionProgramId);
        $sectionName = $section->GetSectionName();

        $student_details_url = "../student/record_details.php?id=$student_id&details=show";

        if($new_enrollee == 0
            && $enrollment_is_new_enrollee == 0 
            && $enrollment_is_transferee == 0
            && $student_statusv2 == "Irregular"
            && ($enrollment_student_status == "" || $enrollment_student_status == "Irregular")
            ){

            $updated_type = "Old Irregular";

            $button_url = "
                <button class='default'
                    onclick=\"window.location.href = '" . $student_details_url . "'\">
                    View
                </button>
            ";
        }
        else if($new_enrollee == 0
            && $enrollment_is_new_enrollee == 0 
            && $enrollment_is_transferee == 0
            && $student_statusv2 == "Regular"
            && $enrollment_student_status == "Regular"
            ){

            $updated_type = "Old Regular";

            $button_url = "
                <button class='default'
                onclick=\"window.location.href = '" . $enrollmentSubjectsUrl . "subject_insertion_summary.php?id=560&enrolled_subject=show'\">
                    View
                </button>
            ";
        }

        else if($new_enrollee == 1
            && $enrollment_is_new_enrollee == 1 
            && $enrollment_is_transferee == 0
            // && $student_statusv2 == ""
            && $enrollment_student_status == "Regular"){

            $updated_type = "New Regular";

            $button_url = "
                <button class='default'
                    onclick=\"window.location.href = '" . $enrollmentSubjectsUrl . "subject_insertion_summary.php?id=560&enrolled_subject=show'\">
                    View
                </button>
            ";
        }
        // 
        else if($new_enrollee == 1
        && $enrollment_is_new_enrollee == 1 
        && $enrollment_is_transferee == 1
        // && $student_statusv2 == ""
        && ($enrollment_student_status == "Irregular" || $enrollment_student_status == "Regular")
        ){

        $updated_type = "New Transferee";

        $button_url = "
            <button class='default'
                    onclick=\"window.location.href = '" . $student_details_url . "'\">
                View
            </button>
        ";
        }

        $student_unique_id = $row['student_unique_id'] !== "" ? $row['student_unique_id'] : "N/A";

        $data[] = array(
            "student_id" => $student_unique_id,
            "name" => $fullname,
            "email" => $row['email'],
            "type" => $updated_type,
            "section_name" => $program_section,
            "enrollment_approve" => $enrollment_approve !== NULL ? $enrollment_approvex : "N/A",
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
