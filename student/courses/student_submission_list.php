<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    

    if(isset($_GET['id'])){

        $subject_code_assignment_id = $_GET['id'];

        $student_id = $_SESSION['studentLoggedInId'];

        // echo $student_id;


        $school_year = new SchoolYear($con);

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $topic_assigned_teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

        // echo $topic_assigned_teacher_id;
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];


        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $getAnsweredAssignmentList = $subjectAssignmentSubmission->GetSubmissionList(
            $subject_code_assignment_id, $current_school_year_id, $student_id);

        // print_r($getAnsweredAssignmentList);

        $studentLatestSubjectAssignmentSubmission_id = $subjectAssignmentSubmission->CheckSubmissionIsLatest(
            $subject_code_assignment_id,
            $current_school_year_id, $student_id);

        // echo $studentLatestSubjectAssignmentSubmission_id;

        $back_url = "submission_view.php?id=$subject_code_assignment_id&s_id=$studentLatestSubjectAssignmentSubmission_id";

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
                                                foreach ($getAnsweredAssignmentList as $key => $row) {

                                                    $i++;

                                                    // $date_creation = $row['date_creation'];
                                                    $date_creation = date("M d H:i a", strtotime($row['date_creation']));

                                                    $subject_assignment_submission_id = $row['subject_assignment_submission_id'];


                                                    echo "
                                                        <tr>
                                                            <td>$i</td>
                                                            <td>$date_creation</td>
                                                            <td>
                                                                <a href='submission_view.php?id=$subject_code_assignment_id&s_id=$subject_assignment_submission_id'>
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



?>