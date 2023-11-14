<?php

include('../../includes/config.php');
include('../../includes/classes/SchoolYear.php');
include('../../includes/classes/Section.php');
include('../../includes/classes/Enrollment.php');
include('../../includes/classes/SubjectProgram.php');
include('../../includes/classes/StudentSubject.php');
include('../../includes/classes/Student.php');
include('../../includes/classes/Program.php');
include('../../includes/classes/Department.php');
include('../../includes/classes/Schedule.php');


$student_id = $_GET['st_id'] ?? NULL;
$enrollment_id = $_GET['e_id'] ?? NULL;


// echo $student_id;


$school_year = new SchoolYear($con, null);

$enrollment = new Enrollment($con, null);
$student = new Student($con, $student_id);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];
$current_school_year_id = $school_year_obj['school_year_id'];



$student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId(
    $student_id,
    $enrollment_id, $current_school_year_id);



$section = new Section($con, $student_enrollment_course_id);

$subject_program = new SubjectProgram($con);

$sec_exec = new Section($con, $student_enrollment_course_id);

// echo $student_enrollment_course_id;

$student_enrollment_program_id_course_id = $sec_exec->GetSectionProgramId($student_enrollment_course_id);

 $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_id, $current_school_year_id);

    $section = new Section($con, $student_enrollment_course_id);

    $studentSubject = new StudentSubject($con);
    $section_name = $section->GetSectionName();

    $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
    $student_course_level = $student->GetStudentLevel($student_id);

    $program = new Program($con, $student_program_id);

    $program_name = $program->GetProgramAcronym();

    $program_department_id = $program->GetProgramDepartmentId();

    $department = new Department($con, $program_department_id);

    $subjectProgram = new SubjectProgram($con, $program_department_id);

    $program_department_name =  $department->GetDepartmentName();
    $department_id =  $department->GetDepartmentIdByName($program_department_name);

    $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
        $enrollment_id, $current_school_year_id);

    $enrollment_section_level = $section->GetSectionGradeLevel($student_enrollment_course_id);
    $enrollment_section_program_id = $section->GetSectionProgramId($student_enrollment_course_id);
    
    $department_type = $program_department_name == "Senior High School" ? "SHS" : ( $program_department_name == "Tertiary" ? "Tertiary" : "");

        // echo $enrollment_section_level;

    $get = $subjectProgram->GetProgramSemesterAvailableSubjectCodes(
        $enrollment_section_program_id, $current_school_year_period, $enrollment_section_level);


$draw = $_POST['draw'] ?? null;
$row = $_POST['start'] ?? null;
$rowperpage = $_POST['length'] ?? null;
$columnIndex = $_POST['order'][0]['column'] ?? null;
$columnName = $_POST['columns'][$columnIndex]['data'] ?? null;
$columnSortOrder = $_POST['order'][0]['dir'] ?? null;
$searchValue = $_POST['search']['value'] ?? null;

                // { data: 'code', orderable: false },  
                // { data: 'description', orderable: false },  
                // { data: 'requisite', orderable: false },  
                // { data: 'unit', orderable: false },  
                // { data: 'level', orderable: false },  
                // { data: 'semester', orderable: false },
                // { data: 'section', orderable: false },
                // { data: 'time', orderable: false },
                // { data: 'button_url', orderable: false }


$columnNames = array(
    'code',
    'description',
    'section',
    'status',
    'requisite',
    'unit',
    'level',
    'semester',
    'time'
);


$sortBy = $columnNames[$columnIndex];

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
        t1.subject_code LIKE '%" . $searchValue . "%' OR
        t1.subject_title LIKE '%" . $searchValue . "%' OR
        t2.program_section LIKE '%" . $searchValue . "%'
    )";

}

## Total number of records without filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM subject_program AS t1

    INNER JOIN course as t2 ON t2.program_id = t1.program_id
    AND t2.course_level = t1.course_level
    AND (
        t2.program_id = :student_program_id
        -- If not my program_id, then it should be subject type as Core
        OR t1.program_id != :student_program_id
            AND t1.subject_type='Core'
        )

    WHERE t1.department_type = :department_type
    AND t1.semester=:semester
    AND t2.active= 'yes'
    AND t2.school_year_term=:school_year_term
    AND t2.is_full= 'no'

    -- GROUP BY t1.subject_program_id,
    --     t2.course_id
 
    ");

$stmt->bindParam(":student_program_id", $student_program_id);
$stmt->bindParam(":department_type", $department_type);
$stmt->bindParam(":semester", $current_school_year_period);
$stmt->bindParam(":school_year_term", $current_school_year_term);

$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecords = $records['allcount'];

// var_dump($totalRecords);

## Total number of records with filtering
$stmt = $con->prepare("SELECT COUNT(*) AS allcount 

    FROM subject_program AS t1

    INNER JOIN course as t2 ON t2.program_id = t1.program_id
    AND t2.course_level = t1.course_level
    AND (
        t2.program_id = :student_program_id
        -- If not my program_id, then it should be subject type as Core
        OR t1.program_id != :student_program_id
            AND t1.subject_type='Core'
        )

    LEFT JOIN student_subject as t3 ON t1.subject_program_id = t3.subject_program_id
    AND t3.student_id=:student_id

    WHERE 1 $searchQuery

    AND t1.department_type = :department_type
    AND t1.semester=:semester
    AND t2.active= 'yes'
    AND t2.school_year_term=:school_year_term
    AND t2.is_full= 'no'

    -- GROUP BY t1.subject_program_id,
    --     t2.course_id
    
");

// $stmt->bindValue(":new_enrollee", 0);

$stmt->bindParam(":department_type", $department_type);
$stmt->bindParam(":semester", $current_school_year_period);
$stmt->bindParam(":school_year_term", $current_school_year_term);
$stmt->bindParam(":student_id", $student_id);
$stmt->bindParam(":student_program_id", $student_program_id);

$stmt->execute();

$records = $stmt->fetch(PDO::FETCH_ASSOC);
$totalRecordwithFilter = $records['allcount'];

// var_dump($totalRecordwithFilter);

$arrayOfSubjectCode = $subject_program
    ->GetProgramSemesterAvailableSubjectCodes(
        $student_program_id,
        $current_school_year_period,
        $enrollment_section_level
    );

$subjectCodePlaceholders = implode(', ', array_map(function($subjectCode) {
    return ':subject_code_' . $subjectCode;
}, $arrayOfSubjectCode));

 

## Fetch records
if ($row != null) {
     
    $empQuery = "SELECT 
                                            
        t1.*
        ,t2.program_section
        ,t2.course_id
        ,t2.capacity

        ,t3.student_subject_id,
        t3.is_final AS ss_is_final,
        t3.enrollment_id AS ss_enrollment_id,
        t3.subject_program_id AS ss_subject_program_id,

        t3.is_transferee AS ss_is_transferee,
        t3.school_year_id AS ss_school_year_id,
        t3.course_id AS ss_course_id,
        t3.student_id AS ss_student_id

        -- t4.student_subject_id AS ssg_student_subject_id
        
        FROM subject_program AS t1

        INNER JOIN course as t2 ON t2.program_id = t1.program_id
        AND t2.course_level = t1.course_level
        AND (
            t2.program_id = :student_program_id
            -- If not my program_id, then it should be subject type as Core
            OR t1.program_id != :student_program_id
                AND t1.subject_type='Core'
                -- AND t1.subject_code IN ($subjectCodePlaceholders)
            )

        LEFT JOIN student_subject as t3 ON t1.subject_program_id = t3.subject_program_id
        AND t3.student_id=:student_id
 
        WHERE 1 $searchQuery

        AND t1.department_type = :department_type
        AND t1.semester=:semester
        AND t2.active= 'yes'
        AND t2.school_year_term=:school_year_term
        AND t2.is_full= 'no'


        GROUP BY t1.subject_program_id,
            t2.course_id

        ORDER BY t1.course_level,
        t1.semester, t2.program_section DESC 
            
        -- ORDER BY $sortBy $sortOrder

        LIMIT " . $row . "," . $rowperpage;
         
    $stmt = $con->prepare($empQuery);

    $stmt->bindParam(":department_type", $department_type);
    $stmt->bindParam(":semester", $current_school_year_period);
    $stmt->bindParam(":school_year_term", $current_school_year_term);
    $stmt->bindParam(":student_id", $student_id);
    $stmt->bindParam(":student_program_id", $student_program_id);
    
    foreach ($arrayOfSubjectCode as $index => $subjectCode) {

        $extra = ":subject_code_$subjectCode";
        // var_dump($extra);
        // echo "<br>";
        // $stmt->bindParam($extra, $subjectCode);
    }

    $stmt->execute();

    $data = array();

    $i = 0;


    $inserted_semester_subjects = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $subject_code = $row['subject_code'];
            $program_section = $row['program_section'];
            $section_capacity = $row['capacity'];
            
            $i++;

            // echo "subject_code: $subject_code, program_section: $program_section";
            // echo "<br>";

            $subject_program_id = $row['subject_program_id'];
            $subject_title = $row['subject_title'];
            $pre_req_subject_title = $row['pre_req_subject_title'];
            $sp_program_id = $row['program_id'];
            $unit = $row['unit'];
            $subject_type = $row['subject_type'];
            $semester = $row['semester'];
            $course_level = $row['course_level'];
            $course_id = $row['course_id'];
            $ss_course_id = $row['ss_course_id'];
            $ss_student_id = $row['ss_student_id'];
            $student_subject_id = $row['student_subject_id'];
            $ss_enrollment_id = $row['ss_enrollment_id'];
            $ss_subject_program_id = $row['ss_subject_program_id'];
            $ss_is_transferee = $row['ss_is_transferee'];
            $ss_is_final = $row['ss_is_final'];
            $ss_school_year_id = $row['ss_school_year_id'];
            // $ssg_student_subject_id = $row['ssg_student_subject_id'];

            $section = new Section($con, $course_id);
            $sectionName = $section->GetSectionName();


            $sectionSubjectCode = $section->CreateSectionSubjectCode(
                $sectionName, $subject_code
            );

            $schedule = new Schedule($con);

            $hasSubjectCode = $schedule->GetSameSubjectCode($course_id,
                $sectionSubjectCode, $current_school_year_id);

                // var_dump($hasSubjectCode);
            $allTime  = "";
            $allDays  = "";

            $scheduleOutput = "";
            $schedule_str = "";


            $subject_schedule_arr = [];

            // if($hasSubjectCode !== NULL){
            if($hasSubjectCode !== []){

                foreach ($hasSubjectCode as $key => $value) {

                    // $schedule_subject_code = $value['subject_code'];
                    
                    $subject_schedule_id = $value['subject_schedule_id'];
                    $sched_subject_program_id = $value['subject_program_id'];
                    $sched_subject_code = $value['subject_code'];
                    $sched_course_id = $value['course_id'];

                    array_push($subject_schedule_arr, $subject_schedule_id);

                

                    $schedule_day = $value['schedule_day'];
                    $schedule_time = $value['schedule_time'];

                    
                    // array_push($schedule_array, $schedule_time);

                    // echo "schedule_day: $schedule_day subject_schedule_id: $subject_schedule_id";
                    // echo "<br>";

                    if($sched_subject_program_id == $subject_program_id){
                        $schedule_str .= "$subject_schedule_id,";
                    }

                    // echo "schedule_str: $schedule_str";
                    // echo "<br>";

                    // if($sectionSubjectCode == $sched_subject_code && $course_id == $schedcour){
                    //     $schedule_str .= "$subject_schedule_id,";

                    // }

                    $allDays .= $schedule_day;
                    $allTime .= $schedule_time;
                    // ($subject_schedule_id)
                    $scheduleOutput .= "○ $schedule_day - $schedule_time  <br>";
                    // echo "<br>";
                }
            }else{
                $scheduleOutput = "TBA";
            }

            $section_subject_code = $section->CreateSectionSubjectCode($program_section, $subject_code);

            $student_subject_enrolled = $subject_program->GetSectionSubjectEnrolledStudents(
                    $subject_program_id,
                    $course_id, $section_subject_code, $current_school_year_id);

            $student_subject_enrolled = $student_subject_enrolled == 0 ? "" : $student_subject_enrolled;

            $doesFull = $student_subject_enrolled === $section_capacity ? 1 : 0;

            $test = htmlspecialchars(json_encode($subject_schedule_arr), ENT_QUOTES, 'UTF-8');   

            $addAvailable = "addAvailable($subject_program_id, $current_school_year_id, $student_id, $student_enrollment_course_id, $enrollment_id, $course_id, \"$subject_code\", $test, \"$subject_title\", $doesFull)";

            $icon = "
                <button onclick='$addAvailable' class='btn btn-primary btn-sm'>
                    <i class='fas fa-plus-circle'></i>
                </button>
            ";

            # Check if subject_program_id is already in the student_subject cart within S.Y

            $hasSelected = $studentSubject->CheckSubjectProgramHasBeenSelectedWithinSY(
                $student_id, $subject_program_id, $current_school_year_id, $sectionSubjectCode);

            // var_dump($hasSelected);

            if($hasSelected) {
                $icon = "
                    <button class='btn btn-success btn-sm'>
                        <i class='fas fa-check'></i>
                    </button>
                ";
            }

            if ($ss_subject_program_id == $subject_program_id && $ss_is_final == 1 && $ss_is_transferee == 1) {
                $currentCredited = "currentCredited($enrollment_id, $student_id)";

                // Enable crediting
                if ($ss_school_year_id == $current_school_year_id) {
                    $icon = "
                        <button onclick='$currentCredited' class='btn btn-success btn-sm'>
                            Current Credited
                        </button>
                    ";
                    array_push($inserted_semester_subjects, $subject_program_id);
                }
                // Disable crediting
                else if ($ss_school_year_id != $current_school_year_id) {
                    $icon = "
                        <button disabled class='btn btn-success btn-sm'>
                            Credited
                        </button>
                    ";
                }
            } else if ($ss_subject_program_id == $subject_program_id 
                && $ss_enrollment_id != NULL && $ss_is_final == 0 
                && $ss_is_transferee == 0) {

                array_push($inserted_semester_subjects, $subject_program_id);

                // Comes from within Program and section enrollment course id BASED.
                // if ($ss_course_id == $course_id 
                //     && $ss_enrollment_id == $enrollment_id 
                //     && $ss_student_id == $student_id) {
                //     $icon = "
                //         <button class='btn btn-info btn-sm'>
                //             Taken
                //         </button>
                //     ";
                // }
            }



            $button_url = "";
            $data[] = array(
                "code" => "$subject_code",
                "description" => "$subject_title",
                "section" => "$sectionName",
                "status" => "$student_subject_enrolled / $section_capacity",
                "requisite" => "$pre_req_subject_title",
                "unit" => "$unit",
                "level" => "$course_level",
                "semester" => "$semester",
                "time" => "$scheduleOutput",
                "button_url" => $icon,
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
