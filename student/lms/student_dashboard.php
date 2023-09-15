<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/StudentSubject.php');

    $section = new Section($con, null);
    $enrollment = new Enrollment($con);

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

    $student_id = $_SESSION['studentLoggedInId'];

    $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
        $school_year_id);

    $studentSubject = new StudentSubject($con);


    $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCode($student_id,
        $school_year_id, $enrollment_id);

    // print_r($allEnrolledSubjectCode);
    

?>
<div class="content">

    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h4 style="font-weight: bold;" class="text-primary">My Enrolled Subject(s)</h4>
                </div>
            </header>
            <h3 class="text-right">Assignments Due</h3>

            <main>

                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th>Unit</th>
                            <th>Section</th>  
                            <th>Instructor</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $query = $con->prepare("SELECT 

                                t4.subject_code AS student_subject_code,
                                t4.is_final,
                                t4.enrollment_id,
                                t4.is_transferee,
                                t4.student_subject_id,
                                t4.retake AS ss_retake,
                                t4.overlap AS ss_overlap,
                                

                                t5.subject_code AS sp_subjectCode,
                                t5.subject_type,
                                t5.subject_title,
                                t5.unit,

                                t6.program_section,

                                t7.student_subject_id as graded_student_subject_id,
                                t7.remarks,

                                t8.subject_schedule_id,
                                t8.course_id AS subject_schedule_course_id,
                                t8.subject_program_id AS subject_subject_program_id,
                                t8.time_from,
                                t8.time_to,
                                t8.schedule_day,
                                t8.schedule_time,
                                t8.room,

                                t9.firstname,
                                t9.lastname

                                FROM student_subject AS t4 

                                LEFT JOIN subject_program AS t5 ON t5.subject_program_id = t4.subject_program_id
                                LEFT JOIN course AS t6 ON t6.course_id = t4.course_id
                                LEFT JOIN student_subject_grade AS t7 ON t7.student_subject_id = t4.student_subject_id

                                LEFT JOIN subject_schedule AS t8 ON t8.subject_code = t4.subject_code
                                AND t8.course_id = t4.course_id

                                LEFT JOIN teacher as t9 ON t9.teacher_id = t8.teacher_id

                                WHERE t4.student_id=:student_id
                                AND t4.enrollment_id=:enrollment_id

                                ORDER BY t5.subject_title DESC
                            ");

                            $query->bindValue(":student_id", $student_id); 
                            $query->bindValue(":enrollment_id", $enrollment_id); 
                            $query->execute(); 

                            if($query->rowCount() > 0){

                                while($row_inner = $query->fetch(PDO::FETCH_ASSOC)){
                                    $subject_title = $row_inner['subject_title'];

                                    $schedule = new Schedule($con);

                                    $student_subject_code = $row_inner['student_subject_code'];
                                    $sp_subjectCode = $row_inner['sp_subjectCode'];
                                    $subject_schedule_id = $row_inner['subject_schedule_id'];

                                    $subject_schedule_course_id = $row_inner['subject_schedule_course_id'];
                                    $subject_subject_program_id = $row_inner['subject_subject_program_id'];

                                    $subject_type = $row_inner['subject_type'];
                                    $unit = $row_inner['unit'];
                                    $program_section = $row_inner['program_section'];
                                    $remarks = $row_inner['remarks'];
                                    $ss_retake = $row_inner['ss_retake'];
                                    $ss_overlap = $row_inner['ss_overlap'];

                                    $schedule_time = $row_inner['schedule_time'] != "" ? $row_inner['schedule_time'] : "-";
                                    
                                    $student_subject_code = $row_inner['student_subject_code'];

                                    $teacher_firstname = $row_inner['firstname'];
                                    $teacher_lastname = $row_inner['lastname'];

                                    $instructor_name = "-";

                                    if($teacher_firstname != null){
                                        $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                    }

                                    $section_code = $section->CreateSectionSubjectCode($program_section, $sp_subjectCode);

                                    // $section_code = trim(strtolower($section_code));

                                    $courses_url = "../courses/index.php?c=$section_code";
                                    
                                    echo "
                                        <tr class='text-center'>
                                            <td>
                                                <a style='color: inherit' href='$courses_url'>
                                                    $subject_title
                                                </a>
                                            </td>
                                            <td>
                                                $sp_subjectCode
                                            </td>
                                            <td>$subject_type</td>
                                            <td>$unit</td>
                                            <td>
                                                <a style='all:unset; cursor: pointer' href=''>
                                                    $program_section
                                                </a>
                                            </td>
                                            <td>$instructor_name</td>
                                        </tr>
                                    ";
                                }
                            }

                        ?>
                    </tbody>
                </table>

            </main>
        </div>
    </main>
</div>
