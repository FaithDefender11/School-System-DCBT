<?php
    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];


    $student_id = $_SESSION['studentLoggedInId'];

    $back_url = "../registration/index.php";
    

    if(isset($_GET['id'])){

        $enrollment_id = $_GET['id'];

        $enrollment = new Enrollment($con, null);


        $enrollment_form_id = $enrollment->GetEnrollmentFormIdBased($enrollment_id);
?>

            <?php
                echo Helper:: enrollmentStudentHeader($con, $studentLoggedInId);
            ?>
            <nav>
                <a href="<?php echo "$back_url"; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>
            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Enrollment Form <em>#<?php echo $enrollment_form_id; ?></em> Schedule</h3>
                        </div>
                    </header>
                    <main style="overflow-x: auto">
                        <table class="a" id="department_table" style="min-width: 1000px;">
                            <thead>
                                <tr>
                                    <th>Subject</th>  
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Unit</th>
                                    <th>Section</th>  
                                    <th>Days</th>  
                                    <th>Time</th>  
                                    <th>Room</th>  
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
                                    t8.room_id,

                                    t9.firstname,
                                    t9.lastname,

                                    t10.room_number


                                    FROM student_subject AS t4 

                                    LEFT JOIN subject_program AS t5 ON t5.subject_program_id = t4.subject_program_id
                                    LEFT JOIN course AS t6 ON t6.course_id = t4.course_id
                                    LEFT JOIN student_subject_grade AS t7 ON t7.student_subject_id = t4.student_subject_id

                                    LEFT JOIN subject_schedule AS t8 ON t8.subject_code = t4.subject_code
                                    AND t8.course_id = t4.course_id

                                    LEFT JOIN teacher as t9 ON t9.teacher_id = t8.teacher_id

                                    LEFT JOIN room as t10 ON t10.room_id = t8.room_id

                                    WHERE t4.student_id=:student_id
                                    AND t4.enrollment_id=:enrollment_id

                                    ORDER BY t5.subject_title ASC,

                                    CASE t8.schedule_day
                                        WHEN 'M' THEN 1
                                        WHEN 'T' THEN 2
                                        WHEN 'W' THEN 3
                                        WHEN 'TH' THEN 4
                                        WHEN 'F' THEN 5
                                        ELSE 6  
                                    END


                                ");

                                $query->bindValue(":student_id", $student_id); 
                                $query->bindValue(":enrollment_id", $enrollment_id); 
                                $query->execute(); 

                                if($query->rowCount() > 0){

                                    $subject_titles_occurrences = [];
                                    $subject_code_occurrences = [];
                                    $subject_type_occurrences = [];
                                    $subject_unit_occurrences = [];
                                    $section_occurrences = [];
                                    $sched_arr = [];

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

                                        $room_number = $row_inner['room_number'];

                                        

                                        $schedule_time = $row_inner['schedule_time'] != "" ? $row_inner['schedule_time'] : "-";
                                        
                                        $schedule->filterSubsequentOccurrencesSa($subject_titles_occurrences,
                                            $subject_title, $subject_schedule_course_id, $subject_subject_program_id);

                                        $schedule->filterSubsequentOccurrencesSa($subject_code_occurrences,
                                            $sp_subjectCode, $subject_schedule_course_id, $subject_subject_program_id);

                                        $schedule->filterSubsequentOccurrencesSa($subject_type_occurrences,
                                            $subject_type, $subject_schedule_course_id, $subject_subject_program_id);

                                        // $schedule->filterSubsequentOccurrencesSa($section_occurrences,
                                        //     $program_section, $subject_schedule_course_id, $subject_subject_program_id);

                                        // $schedule->filterSubsequentOccurrencesSa($subject_unit_occurrences,
                                        //     $unit, $subject_schedule_course_id, $subject_subject_program_id);



                                        // $schedule->filterSubsequentOccurrences($subject_code_occurrences, $sp_subjectCode);
                                        // $schedule->filterSubsequentOccurrences($subject_type_occurrences, $subject_type);
                                        // $schedule->filterSubsequentOccurrences($section_occurrences, $schedule_time);

                                        // $ss_retake_msg = $ss_retake == 1 ? "RT" : " &nbsp&nbsp&nbsp&nbsp";
                                        // $ss_overlap_msg = $ss_overlap == 1 ? "OL" : "";

                                        $enrollment_status = "enrolled";

                                        // $icon =  $remarks == "Passed" && $enrollment_status == "enrolled" ? "
                                        //     <i style='color: green;' class='fas fa-check-circle'></i>
                                        // " : ($remarks == "Failed"
                                        //     ? "<i style='color: orange;' class='fas fa-times-circle'></i>" 
                                        //     : ( $remarks == null && $enrollment_status == "enrolled" ? "<i style='color: blue;' class='bi bi-hourglass-top'></i>" : "<i style='color: blue;' class='bi bi-airplane'></i>"));

                                        $student_subject_code = $row_inner['student_subject_code'];


                                        $student_subject_id = $row_inner['student_subject_id'];
                                        $is_final = $row_inner['is_final'];

                                        
                                        $graded_student_subject_id = $row_inner['graded_student_subject_id'];

                                        $remarks_url = "";

                                        $db_enrollment_id = $row_inner['enrollment_id'];
                                        $db_is_transferee = $row_inner['is_transferee'];

                                        $time_from = $row_inner['time_from'];

                                        $time_to = $row_inner['time_to'];

                                        // $room = $row_inner['room'] != "" ? $row_inner['room'] : "-";

                                        $schedule_day = $row_inner['schedule_day'] != "" ? $row_inner['schedule_day'] : "-";

                                        $teacher_firstname = $row_inner['firstname'];
                                        $teacher_lastname = $row_inner['lastname'];

                                        $instructor_name = "-";

                                        if($teacher_firstname != null){
                                            $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                        }

                                        $changingSectionSubjectUrl = "./change_student_subject.php?id=$student_subject_id";


                                                // <td>$icon $ss_overlap_msg $ss_retake_msg</td>

                                        echo "
                                            <tr>
                                                <td>$subject_title</td>
                                                <td>
                                                    $sp_subjectCode
                                                </td>
                                                <td>$subject_type</td>
                                                <td>$unit</td>
                                                <td>
                                                    <a style='all:unset; cursor: pointer' href='$changingSectionSubjectUrl'>
                                                        $program_section
                                                    </a>
                                                </td>
                                                <td>$schedule_day</td>
                                                <td>$schedule_time</td>
                                                <td>$room_number</td>
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
        <?php
    }
    ?>
    </body>
</html>