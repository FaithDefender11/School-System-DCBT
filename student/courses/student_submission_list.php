<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/StudentSubject.php');
    
    $test = false;

    if($test == false){
        echo Helper::RemoveSidebar();
        $test = true;

    }

    if($test == true){

        if(isset($_GET['id'])){

            $subject_code_assignment_id = $_GET['id'];

            $student_id = $_SESSION['studentLoggedInId'];

            // echo $student_id;


            $school_year = new SchoolYear($con);


            $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

            $subjectPeriodCodeTopicId = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();

            $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subjectPeriodCodeTopicId);

            $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
            $topic_assigned_teacher_id = $subjectPeriodCodeTopic->GetTeacherId();
            $school_year_id = $subjectPeriodCodeTopic->GetSchoolYearId();

            // echo $topic_assigned_teacher_id;
            $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

            $current_school_year_id = $school_year_obj['school_year_id'];
            $current_school_year_period = $school_year_obj['period'];
            $current_school_year_term = $school_year_obj['term'];


            $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

            $studentSubject = new StudentSubject($con);

            $studentSubjectId = $studentSubject->GetStudentSubjectIdBySubjectCode(
                $studentLoggedInId, $school_year_id, $topic_subject_code);

            $getAnsweredAssignmentList = $subjectAssignmentSubmission->GetSubmissionList(
                $subject_code_assignment_id, $current_school_year_id, $student_id);

            // var_dump($studentSubjectId);

            $studentLatestSubjectAssignmentSubmission_id = $subjectAssignmentSubmission->CheckSubmissionIsLatest(
                $subject_code_assignment_id,
                $current_school_year_id, $student_id);

            // echo $studentLatestSubjectAssignmentSubmission_id;

            // $back_url = "submission_view.php?id=$subject_code_assignment_id&s_id=$studentLatestSubjectAssignmentSubmission_id";

            $back_url = "submission_view.php?sc_id=$subject_code_assignment_id&s_id=$studentLatestSubjectAssignmentSubmission_id&ss_id=$studentSubjectId";


            ?>
                <div class="content">

                    <nav>
                        <a href="<?php echo $back_url;?>">
                            <i class="bi bi-arrow-return-left fa-1x"></i>
                            <h3>Back</h3>
                        </a>
                    </nav>

                    <main>
                        <div class="floating" id="shs-sy">
                            <header>
                                <div class="title">
                                    <h3>Submission History</h3>
                                </div>
                            </header>

                            <main>

                                <?php 
                                    if(count($getAnsweredAssignmentList) > 0){

                                        ?>
                                        <table id="department_table" class="a" style="margin: 0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>When</th>
                                                    <th>Show</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                <?php

                                                    $i = 0;
                                                    $highestNumber = null; // Initialize a variable to store the highest number

                                                    foreach ($getAnsweredAssignmentList as $key => $row) {

                                                        $i++;
                                                        $latest = "";

                                                        // $date_creation = $row['date_creation'];
                                                        $date_creation = date("M d H:i a", strtotime($row['date_creation']));

                                                        $subject_assignment_submission_id = $row['subject_assignment_submission_id'];

                                                        if ($highestNumber === null || $subject_assignment_submission_id > $highestNumber) {

                                                            $highestNumber = $subject_assignment_submission_id; // Update the highest number
                                                        }

                                                        // $url = "submission_view.php?id=$subject_code_assignment_id&s_id=$subject_assignment_submission_id&ss_id=$studentSubjectId";
                                                        $url = "submission_view.php?sc_id=$subject_code_assignment_id&s_id=$subject_assignment_submission_id&ss_id=$studentSubjectId";


                                                        if($subject_assignment_submission_id == $highestNumber){
                                                            $latest = "Latest";
                                                        }
                                                        echo "
                                                            <tr>
                                                                <td>$i</td>
                                                                <td>$date_creation <span style='font-size: 12px; color:green;'>$latest</span></td>
                                                                <td>
                                                                    <a href='$url'>
                                                                        <button class='btn btn-dark'>
                                                                            <i class='fas fa-file'></i>
                                                                        </button>
                                                                    </a>
                                                                    
                                                                </td>
                                                            </tr>
                                                        ";
                                                    }

                                                ?>
                                            </tbody>
                                        </table>
                                        <?php
                                    }
                                ?>
                                

                            </main>
                        </div>
                    </main>
                </div>

            <?php
        }

    }


?>