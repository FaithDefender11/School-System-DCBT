<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    

    if(isset($_GET['id'])
        // && isset($_GET['c'])
    ){

        $subject_code_assignment_id = $_GET['id'];

        // $subject_code = $_GET['c'];

        # Check if teacher owned the Section Subject Code.
        $school_year = new SchoolYear($con);


        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);

        $assignment_name = $subjectCodeAssignment->GetAssignmentName();
        $assignment_max_score = $subjectCodeAssignment->GetMaxScore();
        $subject_code_topic_id = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_code_topic_id);

        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

        $topic_assigned_teacher_id = $subjectPeriodCodeTopic->GetTeacherId();

        // echo $topic_assigned_teacher_id;
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        
        // $back_url = "index.php?c_id=$subject_code_assignment_id&c=$subject_code";
        // $back_url = "section_topic_grading.php?ct_id=$subject_code_topic_id";
        
        ?>
            <div  class="content">

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
                                <h4 class="text-muted">Submission List on assignment: <span class="text-primary" style="font-size: 17px"><?php echo $assignment_name; ?></span> </h4>
                            </div>
                            <span>Maximum Score: <?php echo $assignment_max_score; ?> </span>
                        </header>
                        <main>

                           

                            <table id="student_submission_list_table" class="a"
                                style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Submission status</th>
                                        <th>Grade status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                        // echo $subject_code;
                                    
                                        $query = $con->prepare("SELECT 
                                        
                                            t3.firstname
                                            ,t3.lastname
                                            ,t3.student_unique_id
                                            ,t3.student_id

                                            FROM student_subject as t1
                                            INNER JOIN enrollment as t2 ON t2.enrollment_id = t1.enrollment_id
                                            AND t2.enrollment_status ='enrolled'

                                            INNER JOIN student as t3 ON t3.student_id = t2.student_id


                                            WHERE t1.subject_code=:subject_code
                                            AND t1.school_year_id=:school_year_id
                                           
                                            GROUP BY t3.student_id
                                        ");

                                        $query->bindParam(":subject_code", $subject_code);
                                        $query->bindParam(":school_year_id", $current_school_year_id);
                                        $query->execute();

                                        if($query->rowCount() > 0){


                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $student_unique_id = $row['student_unique_id'];

                                                $firstname = $row['firstname'];
                                                $lastname = $row['lastname'];
                                                $student_id = $row['student_id'];

                                         
                                                $fullname = ucwords($firstname) . " " . ucfirst($lastname);

                                                $removeDepartmentBtn = "";

                                                $status = "<i style='color:orange;' class='fas fa-times'></i>";
                                                
                                                $view_btn = "";

                                                $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

                                                $checkSubmission = $subjectAssignmentSubmission->CheckStatusSubmission(
                                                    $subject_code_assignment_id,
                                                    $student_id, $current_school_year_id);
                                                

                                                $submission_grade = "~";

                                                if($checkSubmission !== NULL){

                                                    $subject_assignment_submission_id = $checkSubmission['subject_assignment_submission_id'];


                                                    $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con,
                                                        $subject_assignment_submission_id);

                                                    $submission_grade = $subjectAssignmentSubmission->GetSubjectGrade();

                                                    $status = "
                                                        <i style='color:green;else{' class='fas fa-check'></i>
                                                    ";
                                                    $view_btn = "
                                                        <a href='student_submission_view.php?id=$subject_assignment_submission_id'>
                                                            <button class='btn btn-primary'>
                                                                <i class='fas fa-eye'></i>
                                                            </button>
                                                        </a>
                                                    ";

                                                } 
                                                

                                                echo "
                                                    <tr>
                                                        <td>$student_unique_id</td>
                                                        <td>$fullname</td>
                                                        <td>$status</td>
                                                        <td>$submission_grade</td>
                                                        <td>$view_btn</td>
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