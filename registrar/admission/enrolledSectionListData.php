<?php

include('../../includes/config.php');
include('../../includes/classes/SchoolYear.php');
include('../../includes/classes/Section.php');
include('../../includes/classes/Enrollment.php');

$school_year = new SchoolYear($con);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];

$section = new Section($con);

$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;
 
$columnNames = array(
    'program_section',
    'student_count',
    'capacity'
);

$sortBy = $columnNames[$columnIndex] ?? 'program_section'; // Default to submission_creation column if the selected column is not found in the array

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC



## Search
$searchQuery = "";

if ($searchValue != '') {

    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    
    $searchQuery = " AND (
       
        t2.program_section LIKE '%" . $searchValue . "%'
    )";
}


## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM enrollment AS t1
    INNER JOIN course AS t2 ON t2.course_id = t1.course_id

    WHERE t1.enrollment_status = :enrollment_status
    AND t1.school_year_id = :school_year_id

    GROUP BY t1.course_id

");

$stmt->bindValue(":enrollment_status", "enrolled");
$stmt->bindParam(":school_year_id", $current_school_year_id);

$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);

// $sectionEnrolledStudentList = $section->GetCurrentSectionWithEnrolledStudent($current_school_year_id);
// $sectionEnrolledStudentListCount = count($sectionEnrolledStudentList);


$totalRecords = $records['allcount'];
 

## Total number of records with filtering


$student_admission_status_filtering = "";


$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM enrollment AS t1
    INNER JOIN course AS t2 ON t2.course_id = t1.course_id

    WHERE 1 $searchQuery

    AND t1.enrollment_status = :enrollment_status
    AND t1.school_year_id = :school_year_id

    GROUP BY t1.course_id


");

$stmt->bindValue(":enrollment_status", "enrolled");
$stmt->bindParam(":school_year_id", $current_school_year_id);
    
 
$stmt->execute();

$sectionEnrolledStudentList = $section->GetCurrentSectionWithEnrolledStudent($current_school_year_id);
$sectionEnrolledStudentListCount = count($sectionEnrolledStudentList);

$records = $stmt->fetch(PDO::FETCH_ASSOC);

// var_dump($stmt);
$totalRecordwithFilter = $records['allcount'];
// $totalRecordwithFilter = $sectionEnrolledStudentListCount;


## Fetch records
if ($row != null) {

    // FROM enrollment AS t1

    // INNER JOIN course AS t2 ON t2.course_id = t1.course_id
    
    // WHERE t1.enrollment_status=:enrollment_status
    // AND t1.school_year_id=:school_year_id

    // GROUP BY t1.course_id

    $empQuery = "SELECT 

        t1.school_year_id,
        t2.* 

        FROM enrollment AS t1
        INNER JOIN course AS t2 ON t2.course_id = t1.course_id

        WHERE 1 $searchQuery

        AND t1.enrollment_status = :enrollment_status
        AND t1.school_year_id = :school_year_id

        GROUP BY t1.course_id
        ORDER BY $sortBy $sortOrder
        LIMIT " . $row . "," . $rowperpage;


    $stmt = $con->prepare($empQuery);

    $stmt->bindValue(":enrollment_status", "enrolled");
    $stmt->bindParam(":school_year_id", $current_school_year_id);
    

    $stmt->execute();

    $data = array();

    while ($value = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $program_section = $value['program_section'];
        $course_id = $value['course_id'];
        $capacity = $value['capacity'];
        $school_year_id = $value['school_year_id'];

        $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);
                                    
        $section_url = "
            <a href='enrolled_students.php?id=$course_id&sy_id=$school_year_id' style='color: inherit'>
                $program_section
            </a>
        ";

        
        $data[] = array(
            "program_section" => $section_url,
            "student_count" => $totalStudent,
            "capacity" =>$capacity
           
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
