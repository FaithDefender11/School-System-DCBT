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

$admission_type_filter = $_GET['admission_type_filter'] ?? NULL;


// echo $admission_type_filter;
// return;

$columnNames = array(
    'student_id',
    'name',
    'email',
    'type',
    'acronym',
    'registrar_confirmation_date',
);

$sortBy = $columnNames[$columnIndex] ?? 'registrar_confirmation_date';  

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
   
    $searchQuery = " AND (

        (t1.firstname LIKE '%" . $firstName . "%' AND t1.lastname LIKE '%" . $lastName . "%') OR 
        t1.firstname LIKE '%" . $searchValue . "%' OR 
        t1.lastname LIKE '%" . $searchValue . "%' OR 
        t2.enrollment_form_id LIKE '%" . $searchValue . "%' OR 
        t1.student_unique_id LIKE '%" . $searchValue . "%' OR
        t3.program_section LIKE '%" . $searchValue . "%' OR
        t1.email LIKE '%" . $searchValue . "%'
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
    FROM student AS t1
    INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id
    LEFT JOIN course AS t3 ON t2.course_id = t3.course_id

    WHERE (t2.is_new_enrollee = :is_new_enrollee OR t2.is_new_enrollee = :is_new_enrollee2)
    AND t2.registrar_evaluated = :registrar_evaluated
    AND t2.cashier_evaluated = :cashier_evaluated
    AND t2.school_year_id = :school_year_id
    
    ");


$stmt->bindValue(":is_new_enrollee", $is_new_enrollee);
$stmt->bindValue(":is_new_enrollee2", 0);
$stmt->bindValue(":registrar_evaluated", $registrar_evaluated);
$stmt->bindValue(":cashier_evaluated", "no");
$stmt->bindValue(":school_year_id", $current_school_year_id);

$stmt->execute();
$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];


$student_admission_status_filtering = "";

if($admission_type_filter !== ""){
    $student_admission_status_filtering = "AND t1.admission_status=:admission_status";
}


## Total number of records with filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 
    FROM student AS t1
    
    INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id

    LEFT JOIN course AS t3 ON t2.course_id = t3.course_id

    WHERE 1 " . $searchQuery . " 
    AND  (t2.is_new_enrollee = :is_new_enrollee 
        OR t2.is_new_enrollee = :is_new_enrollee2)

    AND (t2.is_transferee = :is_transferee OR t2.is_transferee = :is_transferee2)
    AND t2.enrollment_status = :enrollment_status
    AND t2.school_year_id = :school_year_id
    AND t2.registrar_evaluated = :registrar_evaluated
    AND t2.cashier_evaluated = :cashier_evaluated

    AND t2.school_year_id = :school_year_id
    $student_admission_status_filtering
    ");

    $stmt->bindValue(":is_new_enrollee", 1);
    $stmt->bindValue(":is_new_enrollee2", 0);
    $stmt->bindValue(":is_transferee", $is_transferee);
    $stmt->bindValue(":is_transferee2", "0");
    $stmt->bindValue(":enrollment_status", $enrollment_status);
    $stmt->bindValue(":school_year_id", $current_school_year_id);
    $stmt->bindValue(":registrar_evaluated", "ues");
    $stmt->bindValue(":cashier_evaluated", "no");

    if($student_admission_status_filtering !== ""){
        $stmt->bindValue(":admission_status", $admission_type_filter);
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
            t2.cashier_evaluated,
            t2.registrar_evaluated,
            t2.is_transferee,
            t2.enrollment_approve,
            t2.course_id AS enrollment_course_id,
            t2.enrollment_date,
            t2.registrar_confirmation_date,

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

            t2.is_new_enrollee AS enrollment_is_new_enrollee,
            t2.is_transferee AS enrollment_is_transferee,
            t2.student_status AS enrollment_student_status,

            t2.enrollment_id,
            t2.enrollment_form_id,
            
            t3.program_section

            FROM student AS t1
        
            INNER JOIN enrollment AS t2 ON t1.student_id = t2.student_id

            LEFT JOIN course AS t3 ON t2.course_id = t3.course_id

            WHERE 1 " . $searchQuery . " 
            AND  (t2.is_new_enrollee = :is_new_enrollee 
                OR t2.is_new_enrollee = :is_new_enrollee2)

            AND (t2.is_transferee = :is_transferee OR t2.is_transferee = :is_transferee2)
            AND t2.enrollment_status = :enrollment_status
            AND t2.school_year_id = :school_year_id
            AND t2.registrar_evaluated = :registrar_evaluated
            AND t2.cashier_evaluated = :cashier_evaluated

            AND t2.school_year_id = :school_year_id
            $student_admission_status_filtering

            ORDER BY $sortBy $sortOrder
            
            LIMIT " . $row . "," . $rowperpage;


        $stmt = $con->prepare($empQuery);

        $stmt->bindValue(":is_new_enrollee", $is_new_enrollee);
        $stmt->bindValue(":is_new_enrollee2", 0);
        $stmt->bindValue(":is_transferee", $is_transferee);
        $stmt->bindValue(":is_transferee2", "0");
        $stmt->bindValue(":enrollment_status", $enrollment_status);
        $stmt->bindValue(":school_year_id", $current_school_year_id);
        $stmt->bindValue(":registrar_evaluated", $registrar_evaluated);
        $stmt->bindValue(":cashier_evaluated", "no");

        if($student_admission_status_filtering !== ""){
            $stmt->bindValue(":admission_status", $admission_type_filter);
        }
        $stmt->execute();

        $data = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $enrollement_student_id = $row['student_id'];
            $enrollment_id = $row['enrollment_id'];
            $enrollment_form_id = $row['enrollment_form_id'];


        
            $enrollment_url  = "
                subject_insertion_summary.php?id=$enrollment_id&enrolled_subject=show
            ";

            $enrollment_form_id = "
                <a style='all:unset' href='$enrollment_url'>
                    $enrollment_form_id
                </a>
            ";
            
            $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);

            $enrollment_date = $row['enrollment_date'];


            $standing = $row['course_level'];
            $course_id = $row['course_id'];
            $enrollment_course_id = $row['enrollment_course_id'];

            $registrar_confirmation_date = $row['registrar_confirmation_date'];
            $registrar_confirmation_date = date("F d, Y h:i a", strtotime($registrar_confirmation_date));
        
            // $dateTime = new DateTime($registrar_confirmation_date);
            // // Format the DateTime object as desired
            // $registrar_confirmation_date = $dateTime->format('Y-m-d g:i A');

            $username = $row['username'];
            $student_unique_id = $row['student_unique_id'];
            $student_id = $row['t2_student_id'];
            $program_section = $row['program_section'];
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
            
            // $waiting_payment_url = "subject_insertion_summary.php?id=$enrollement_student_id&enrolled_subject=show";
            $waiting_payment_url = "subject_insertion_summary.php?id=$enrollment_id&enrolled_subject=show&page=waiting_payment";

            $student_status_pending = "";

            $updated_type = "";
            $button_url = "";
            $strand = "";

            $section = new Section($con, $enrollment_course_id);

            $sectionProgramId = $section->GetSectionProgramId($enrollment_course_id);
            $sectionAcronym = $section->GetAcronymByProgramId($sectionProgramId);
            $section_program_name = $section->GetSectionName();

            // echo $student_statusv2;
            // echo "<br>";

            if($new_enrollee == 0
                && $enrollment_is_new_enrollee == 0 
                && $enrollment_is_transferee == 0
                // && $student_statusv2 == "Irregular"
                && ($enrollment_student_status == "" || $enrollment_student_status == "Irregular"
                )
                ){

                $updated_type = "Old Irregular";

                $button_url = "
                    <button class='default success'
                        onclick=\"window.location.href = '" . $waiting_payment_url . "'\">
                        Evaluate
                    </button>
                ";
            }
            else if($new_enrollee == 0
                && $enrollment_is_new_enrollee == 0 
                && $enrollment_is_transferee == 0
                // && $student_statusv2 == "Regular"
                && $enrollment_student_status == "Regular"){

                $updated_type = "Old Regular";

                $button_url = "
                    <button class='default success'
                        onclick=\"window.location.href = '" . $waiting_payment_url . "'\">
                        Evaluate
                    </button>
                ";
            }
            else if($new_enrollee == 1
                && $enrollment_is_new_enrollee == 1 
                && $enrollment_is_transferee == 0
                // && $student_statusv2 == "Regular"
                // && $enrollment_student_status == "Regular"
                ){

                $updated_type = "New Regular";

                $button_url = "
                    <button class='default'
                    onclick=\"window.location.href = '" . $waiting_payment_url . "'\">
                        Evaluate
                    </button>
                ";
            }
            // 
            else if($new_enrollee == 1
                && $enrollment_is_new_enrollee == 1 
                && $enrollment_is_transferee == 1
                // && $student_statusv2 == ""
                // && ($enrollment_student_status == "Irregular" || $enrollment_student_status == "Regular")
                ){

                $updated_type = "New Transferee";

                $button_url = "
                    <button class='default'
                    onclick=\"window.location.href = '" . $waiting_payment_url . "'\">
                        Evaluate
                    </button>
                ";
            }
        
            $createUrl = "http://localhost/dcbt/admin/student/edit.php?id=$student_id";

            // $transferee_insertion_url = "http://localhost/dcbt/admin/student/transferee_insertion.php?id=$student_id";
            $transferee_insertion_url = "../student/transferee_insertion.php?enrolled_subjects=true&id=$student_id";

            $regular_insertion_url = "./subject_insertion_summary.php?id=$student_id&student_details=show";

            $confirmButton  = "
                <button onclick='confirmValidation(" . $course_id . ", " . $enrollement_student_id . ")' name='confirm_validation_btn' class='btn btn-primary btn-sm'>Confirm</button>
            ";

            $evaluateBtn = "";

            $student_type_status = "";

            $data[] = array(
                "student_id" => $row['student_unique_id'],
                
                "form_id" => $enrollment_form_id,
                "name" => $fullname,
                // "email" => $row['email'],
                "type" => $updated_type,
                "acronym" => $section_program_name,
                "registrar_confirmation_date" => $registrar_confirmation_date,
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
