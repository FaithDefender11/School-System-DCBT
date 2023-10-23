<?php

include('../../includes/config.php');
include('../../includes/classes/SchoolYear.php');
include('../../includes/classes/Section.php');
include('../../includes/classes/Enrollment.php');
include('../../includes/classes/SubjectProgram.php');
include('../../includes/classes/Program.php');

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
    'subject_template_id',
    'strand',
    'code',
    'description',
    'pre_requisite',
    'type',
    'unit'
);

$department_type = $_GET['type'] ?? NULL;


$sortBy = $columnNames[$columnIndex];
// $sortBy = $columnNames[$columnIndex] ?? 'section_name'; // Default to submission_creation column if the selected column is not found in the array

$sortOrder = strtoupper($columnSortOrder) === 'DESC' ? 'DESC' : 'ASC'; // Ensure the sort order is either ASC or DESC

## Search
$searchQuery = "";
if ($searchValue != '') {

    $searchValue = trim(strtolower($searchValue)); // Convert search value to lowercase
    
    $searchQuery = " AND (

        subject_title LIKE '%" . $searchValue . "%' OR 
        subject_code LIKE '%" . $searchValue . "%'
   
    )";
}

$stmt = NULL;

## Total number of records without filtering
 
if($department_type == "Senior High School"){

    $stmt = $con->prepare("SELECT COUNT(*) AS allcount  
    
        FROM subject_template

        WHERE program_type = 0

    ");
}
else if($department_type == "Tertiary"){

    $stmt = $con->prepare("SELECT COUNT(*) AS allcount  
    
        FROM subject_template

        WHERE program_type = 1
    ");
}

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


    if($department_type == "Senior High School"){

        $stmt = $con->prepare("SELECT COUNT(*) AS allcount  
        
            FROM subject_template

            WHERE 1 $searchQuery
            AND program_type = 0

        ");

    }else if($department_type == "Tertiary"){

        $stmt = $con->prepare("SELECT COUNT(*) AS allcount  
        
            FROM subject_template

            WHERE 1 $searchQuery
            AND program_type = 1

        ");
    }

// $stmt = $con->prepare("SELECT COUNT(*) AS allcount 

//     FROM subject_program as t1

//     INNER JOIN program as t2 ON t2.program_id = t1.program_id

//     WHERE 1 $searchQuery

//     AND department_type =:department_type
   
// ");
 
$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
if ($row != null) {


    if($department_type == "Senior High School"){

        $stmt = $con->prepare("SELECT * FROM subject_template

            WHERE 1 $searchQuery
            AND program_type = 0
            
            ORDER BY $sortBy $sortOrder

            LIMIT " .$row . "," . $rowperpage
        );

    }else if($department_type == "Tertiary"){

        $stmt = $con->prepare("SELECT * FROM subject_template

            WHERE 1 $searchQuery

            AND program_type = 1
            
            ORDER BY $sortBy $sortOrder

            LIMIT " .$row . "," . $rowperpage
        );
    }

    $stmt->execute();
 
    $data = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $subject_template_id = $row['subject_template_id'];

        $program_id = $row['program_id'];
        $subject_type = $row['subject_type'];
        $description = $row['description'];
        $subject_code = $row['subject_code'];

        $subject_title = $row['subject_title'];
        $pre_requisite_title = $row['pre_requisite_title'];
        $unit = $row['unit'];

        $program = new Program($con, $program_id);

        $program_name = $program->GetProgramSectionName();

        $url = "template_edit.php?id=$subject_template_id";

        if($subject_type == "Core"){
            // Applicable to all strands. not specfically distributed.
            $program_name = "Universal";
        }

        $removeTemplateBtn = "removeTemplateBtn($subject_template_id)";

        $button_url = "

            <a href='$url'>
                <button class='btn btn-sm btn-primary'>
                    <i class='fas fa-edit'></i>
                </button> 
            </a>

            <button type='button' onclick='$removeTemplateBtn' 
                class='btn btn-sm btn-danger'>
                <i class='fas fa-trash'></i>
            </button> 

        ";
        
        $data[] = array(
            "subject_template_id" => $subject_template_id,
            "strand" => $program_name,
            "code" => $subject_code,
            "description" => $subject_title,
            "pre_requisite" => $pre_requisite_title,
            "type" => $subject_type,
            "unit" => $unit,
            "button_url" => $button_url,
        );

    }

    // { data: 'subject_template_id', orderable: false },  
    // { data: 'strand', orderable: false },  
    // { data: 'code', orderable: false },  
    // { data: 'description', orderable: false },  
    // { data: 'pre_requisite', orderable: false },  
    // { data: 'type', orderable: false },  
    // { data: 'unit', orderable: false },  
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
