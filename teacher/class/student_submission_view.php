<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Notification.php');

    if(isset($_GET['id'])
    // && isset($_GET['c'])
    // && isset($_GET['sca_id'])
    ){


    $subject_assignment_submission_id = $_GET['id'];
    // $subject_code = $_GET['c'];
   
    // $subject_code_assignment_id = $_GET['id'];
    // $student_id = $_GET['st_id'];
    // echo "$student_id";

    # Check if teacher owned the Section Subject Code.


    if(isset($_GET['n_id'])
        && isset($_GET['notification'])
        && $_GET['notification'] == "true"){

        $notification_id = $_GET['n_id'];
        $notification = new Notification($con);

        $markAsNotified = $notification->TeacherNotificationMarkAsViewed($notification_id, $teacherLoggedInId);
        // echo "marked teacher";

    }

    $school_year = new SchoolYear($con);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];


    $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con,
        $subject_assignment_submission_id);

    $student_id = $subjectAssignmentSubmission->GetStudentId();



    $student = new Student($con, $student_id);

    $student_name = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());

    $subject_code_assignment_id = $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();
    $upload_time = $subjectAssignmentSubmission->GetDateCreation();

    $latest_subject_assignment_submission_id = $subjectAssignmentSubmission->CheckSubmissionIsLatest(
        $subject_code_assignment_id,
        $current_school_year_id, $student_id);

    // echo $latest_subject_assignment_submission_id;

    $get_grade = $subjectAssignmentSubmission->GetSubjectGrade();

    $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

    $subject_instructions = $subjectCodeAssignment->GetDescription();
    $max_score = $subjectCodeAssignment->GetMaxScore();
    $assignment_type = $subjectCodeAssignment->GetType();
    $assignment_name = $subjectCodeAssignment->GetAssignmentName();
    $subject_code_topic_id = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_code_topic_id);

    $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
    $school_year_id = $subjectPeriodCodeTopic->GetSchoolYearId();


    // $back_url = "submission_list.php?id=$subject_code_assignment_id&c=$subject_code";
    
    // $back_url = "section_topic_grading.php?ct_id=$subject_code_topic_id";
    $back_url = "task_summary.php?ct_id=$subject_code_topic_id";

    // echo $subject_code_assignment_id;

    $submission_history_url = "student_submission_list.php?id=$subject_code_assignment_id&st_id=$student_id";


    $totalSubmissionCount = $subjectAssignmentSubmission->GetSubmissionCountOnAssignment(
        $subject_code_assignment_id, $student_id, $school_year_id);
?>

            <nav>
                <a href="<?= $back_url; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Assignment: <?php echo $assignment_name; ?></h3>
                            <small>Submitted by: <?php echo $student_name; ?></small>
                        </div>
                            <div class="action">
                                <?php if($totalSubmissionCount > 1): ?>
                                    <button 
                                        class="information"
                                        onclick="window.location.href = 'student_submission_list.php?id=<?php echo $subject_code_assignment_id; ?>&st_id=<?= $student_id;?>'"
                                    >
                                        History
                                    </button>
                                <?php endif; ?>
                                <?php if ($get_grade !== NULL): ?>
                                    <a style='color: inherit' href='edit_given_grade.php?id=<?php echo $subject_assignment_submission_id ?>&c=<?php echo $subject_code ?>'>
                                        <i style="color: blueviolet;" class="fas fa-edit"></i> <?php echo $get_grade ?>
                                    </a>
                                    <small>Remark: <?php echo "$get_grade / $max_score"?></small>
                                <?php else: ?>
                                    <?php if ($subject_assignment_submission_id != 0): ?>
                                        <?php include_once('./addGradeBtnModal.php'); ?>
                                    <?php endif; ?>
                                    <?php if ($latest_subject_assignment_submission_id == $subject_assignment_submission_id): ?>
                                        <button class="clean" data-bs-target="#addGradeBtn" data-bs-toggle="modal"> + Add Grade</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                    </header>
                    <main>
                        <table class="a" id="department_table">
                            <thead>
                                <tr>
                                    <th>Submission ID</th>
                                    <th><?php echo $assignment_type == "text" ? "Answer Text" : "Answer File"; ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = $con->prepare("SELECT 

                                    t1.*, t3.firstname,
                                    t3.lastname,t3.student_id
                                    ,t2.subject_assignment_submission_list_id
                                    ,t2.output_text
                                    ,t2.output_file
                                    
                                    FROM subject_assignment_submission as t1


                                    INNER JOIN subject_assignment_submission_list as t2 ON t2.subject_assignment_submission_id = t1.subject_assignment_submission_id
                                    
                                    AND t1.subject_assignment_submission_id=:subject_assignment_submission_id
                                    AND t1.subject_code_assignment_id=:subject_code_assignment_id
                                    
                                    LEFT JOIN student as t3 ON t3.student_id = t1.student_id

                                    WHERE t1.student_id =:student_id
                                    AND t1.school_year_id =:school_year_id

                                    -- ORDER BY t1.subject_assignment_submission_id DESC

                                    LIMIT 1 
                                    ");

                                    $query->bindParam(":subject_assignment_submission_id", $subject_assignment_submission_id);
                                    $query->bindParam(":subject_code_assignment_id", $subject_code_assignment_id);
                                    $query->bindParam(":student_id", $student_id);
                                    $query->bindParam(":school_year_id", $current_school_year_id);
                                    $query->execute();

                                    if($query->rowCount() > 0){

                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                            $output_text = $row['output_text'];
                                            $output_file = $row['output_file'];

                                            $date_creation = $row['date_creation'];
                                            $student_id = $row['student_id'];

                                            $subject_assignment_submission_id_db = $row['subject_assignment_submission_id'];

                                            $firstname = $row['firstname'];
                                            $lastname = $row['lastname'];


                                            $fullname = ucwords($firstname) . " " . ucfirst($lastname);

                                            $removeDepartmentBtn = "";
                                            $image = "";

                                            $file_path = "../../$output_file";

                                            // $parts = explode('_', $output_file);
                                            // $original_file_name = end($parts);

                                            $pos = strpos($output_file, "img_");

                                            $original_file_name = "";

                                            // Check if "img_" was found
                                            if ($pos !== false) {
                                                $original_file_name = substr($output_file, $pos + strlen("img_"));
                                            }

                                            $output_assignment = "";

                                            $extension = pathinfo($output_file, PATHINFO_EXTENSION);


                                            if($assignment_type == "text"){

                                                $output_assignment = "$output_text";
                                            }else if($assignment_type == "upload"){


                                                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {
                                                    
                                                
                                                    $output_assignment = "
                                                        <a title='View File' href='<?php echo $file_path; ?>' target='__blank' rel='noopener noreferrer'>
                                                            <img style='margin-left:8px; width: 120px;' 
                                                                src='$file_path' alt='Given Photo' class='preview-image'>
                                                        </a>
                                                    ";

                                                } 
                                                elseif (in_array(strtolower($extension), ['pdf', 'docx', 'doc', 'txt'])) {
                                                    $output_assignment = "
                                                        <a title='View File' href='$file_path' target='__blank' rel='noopener noreferrer'>
                                                            $original_file_name
                                                        </a>
                                                    ";
                                                }

                                            }
                                            
                                            echo "
                                                <tr>
                                                    <td>$subject_assignment_submission_id_db</td>
                                                    <td>
                                                        $output_assignment
                                                    </td>
                                                    <td></td>
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
</div>