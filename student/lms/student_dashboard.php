<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectProgram.php');

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


    // $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCode($student_id,
    //     $school_year_id, $enrollment_id);
    // print_r($allEnrolledSubjectCode);

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
    // $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

    $subjectTopicAssignmentsArray = [];
    $subjectCodeAssignmentsArray = [];

    # List of all Enrolled Subject subject_period_code_topic_id(s)
    $getEnrolledSubjects = $subjectPeriodCodeTopic->GetAllSubjectTopicEnrolledBased(
        $school_year_id, $student_id, $enrollment_id
    );

    // print_r($getEnrolledSubjects);
    // echo "<br>";

    $subjectCodeAssignment = new SubjectCodeAssignment($con);


    $submissionCodeAssignmentArr = [];

    foreach ($getEnrolledSubjects as $key => $subject_period_code_topic_id) {
        
        # All assignments based on enrolled subjects (Not Due assignment)
        $assignmentList =  $subjectCodeAssignment->
            GetAllAssignmentOnTopicBased($subject_period_code_topic_id);
        
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $topicSubjectCode =  $subjectPeriodCodeTopic->GetSubjectCode();

        # All submitted assignment
        $mySubmissionWithinSemester = $subjectCodeAssignment->GetAllStudentAssignmentsSubmission(
            $studentLoggedInId, $school_year_id, $topicSubjectCode);

        foreach ($mySubmissionWithinSemester as $key => $submission) {
            # code...
            $submission_subject_code_assignment_id = $submission['subject_code_assignment_id'];

            if (!in_array($submission_subject_code_assignment_id,
                    $submissionCodeAssignmentArr)) {
                        
                array_push($submissionCodeAssignmentArr,
                    $submission_subject_code_assignment_id);
            }
        }
        

        // echo "<br>";
        // var_dump($mySubmissionWithinSemester);
        // echo "<br>";

        // echo $topicSubjectCode;
        // echo "<br>";
        
        // $subject_period_codeTopic_id =  $subjectCodeAssignment->
        //     GetSubjectPeriodCodeTopicId($subject_period_code_topic_id);
        

        // $assignmentList = 
        if (!empty($assignmentList)) {

            foreach ($assignmentList as $key => $value) {

                // if($value['subject_code_assignment_id'] !==)
                $subjectCodeAssignment_id = $value['subject_code_assignment_id'];

                if (!in_array($subjectCodeAssignment_id, $submissionCodeAssignmentArr)) {
                    
                    // # Get all NOT DUE Subject Code Assignment Given by your Teacher
                    //  # Based on your Enrolled Subject Code

                    // Only push if it's not in $submissionCodeAssignmentArr
                    array_push($subjectCodeAssignmentsArray, 
                        $subjectCodeAssignment_id);

                }
                // if (!empty($value)) {

                //     array_push($subjectCodeAssignmentsArray, $subjectCodeAssignment_id);
                //     // array_push($subjectCodeAssignmentsArray, $value['assignment_name']);
                // }
            }
        }
        
    }
    // print_r($submissionCodeAssignmentArr);
    // print_r($subjectCodeAssignmentsArray);

?>
<div class="content">

    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h4 style="font-weight: bold;" class="text-primary">My Enrolled Subject(s)</h4>
                </div>
            </header>

            <?php if(count($subjectCodeAssignmentsArray) > 0):?>

                <h3 class="text-right">Assignments Due <?php echo count($subjectCodeAssignmentsArray) ?></h3>

                <?php 

                    $arrRawCode = [];
                    $arrSubjectTitle = [];
                    $subjectProgramArr = [];
                    $subjectTitleCounts = [];
                    $count = 0;
                    $assignmentCounts = [];

                    foreach ($subjectCodeAssignmentsArray as $key => $subjectCodeAssignmentIds) {
                        # code...

                        // echo "SubjectCodeAssignmentIds: $subjectCodeAssignmentIds";
                        // echo "<br>";

                        $subjectCodeAssignmentExec = new SubjectCodeAssignment($con, $subjectCodeAssignmentIds);
                        $assignment_name = $subjectCodeAssignmentExec->GetAssignmentName();
                        $assignment_topic_id = $subjectCodeAssignmentExec->GetSubjectPeriodCodeTopicId();

                        $subjectPeriodCodeTopicId = $subjectCodeAssignmentExec->GetSubjectPeriodCodeTopicId();
                        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subjectPeriodCodeTopicId);
                        
                        $rawCode = $subjectPeriodCodeTopic->GetProgramCode();
                        $subject_program_id = $subjectPeriodCodeTopic->GetSubjectProgramId();
                        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

                        $subject_program = new SubjectProgram($con, $subject_program_id);
                        $subjectTitle = $subject_program->GetTitle();

                        // echo "SubjectPeriodCodeTopicId: $subjectPeriodCodeTopicId";
                        // echo "<br>";

                        // echo "Assignment Title: $subjectTitle";
                        // echo "<br>";

                        // if (!isset($assignmentCounts[$subjectTitle])) {
                        //     $assignmentCounts[$subjectTitle] = 1;
                        // } else {
                        //     $assignmentCounts[$subjectTitle]++;
                        // }

                        if (!isset($assignmentCounts[$subjectTitle])) {
                            $assignmentCounts[$subjectTitle] = [
                                'count' => 1,

                                // 'subject_code_assignment_id' => $subjectCodeAssignmentIds
                                
                                'subject_code_topic_id' => $subjectPeriodCodeTopicId,
                                'topic_subject_code' => $topic_subject_code,
                                
                            ];
                        } else {
                            $assignmentCounts[$subjectTitle]['count']++;
                        }
                    }

                    foreach ($assignmentCounts as $assignmentTitle => $data) {

                        $count = $data['count'];
                        // $subject_code_assignment_id = $data['subject_code_assignment_id'];
                        $subject_code_topic_id = $data['subject_code_topic_id'];
                        $topic_subject_code = $data['topic_subject_code'];

                        echo "<a style='color:inherit' href='assignment_due.php?c=$topic_subject_code'
                            class='m-0 text-right'>Assignment Title: $assignmentTitle ($count) Code: $topic_subject_code</a>";

                    }






                    

                ?>
                <!-- <?php foreach ($subjectCodeAssignmentsArray as $row):?>
                     
                <?php endforeach;?> -->


            <?php endif;?>


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
