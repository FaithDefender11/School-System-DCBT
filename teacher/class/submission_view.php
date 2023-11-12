<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');

    if(isset($_GET['id'])){

        $subject_assignment_submission_id = $_GET['id'];

        # Check if teacher owned the Section Subject Code.

        $school_year = new SchoolYear($con);

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $subject_assignment_submission_id);

        $subject_code_assignment_id = $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();
        $get_grade = $subjectAssignmentSubmission->GetSubjectGrade();

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

        $subject_instructions = $subjectCodeAssignment->GetDescription();
        $max_grade = $subjectCodeAssignment->GetMaxScore();

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];
?>

            <?php
                echo Helper::lmsTeacherNotificationHeader(
                    $con, $teacherLoggedInId,
                    $current_school_year_id,
                    $teachingSubjects,
                    "second",
                    "first",
                    "second"
                );
            ?>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Instructions:</h3>
                            <small><?php echo $subject_instructions;?></small>
                        </div>
                        <div class="action">
                            <?php
                                if($get_grade != NULL){

                                    ?>
                                        <small>Remark: <?php echo "$get_grade / $max_grade"?></small>
                                    <?php
                                }else{
                                        include_once('./addGradeBtnModal.php');
                                    ?>
                                        <button 
                                            data-bs-target="#addGradeBtn" 
                                            data-bs-toggle="modal"
                                            class="btn btn-success" 
                                        >
                                            + Add Grade
                                        </button>
                                    <?php
                                }
                            ?>
                        </div>
                    </header>
                    <main style="overflow-x: auto">
                        <table class="a" id="department_table">
                            <thead>
                                <tr>
                                    <th>Answer Text</th>
                                    <th>Answer Image</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = $con->prepare("SELECT  

                                    t1.*, t2.*
                                    
                                    FROM subject_assignment_submission as t1

                                    INNER JOIN subject_assignment_submission_list as t2 ON t2.subject_assignment_submission_id = t1.subject_assignment_submission_id
                                    WHERE t1.subject_assignment_submission_id =:subject_assignment_submission_id
                                    ");

                                    $query->bindParam(":subject_assignment_submission_id", $subject_assignment_submission_id);
                                    $query->execute();

                                    if($query->rowCount() > 0){

                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                            $output_text = $row['output_text'];
                                            $output_file = $row['output_file'];

                                            $subject_assignment_submission_id = $row['subject_assignment_submission_id'];


                                            $removeDepartmentBtn = "";
                                            echo "
                                            <tr>
                                                <td>$output_text</td>
                                                <td>$output_file</td>
                                                <td>
                                                    <a href='submission_view.php?id=$subject_assignment_submission_id'>
                                                        <button class='btn btn-primary'>
                                                            <i class='fas fa-eye'></i>
                                                        </button>
                                                    </a>
                                                    
                                                </td>
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