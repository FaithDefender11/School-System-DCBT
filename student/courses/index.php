<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');

    if(isset($_GET['c'])){

        $subject_code = $_GET['c'];

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $subject_code_assignment = new SubjectCodeAssignment($con);


        $assignmentTodoIds = $subject_code_assignment->GetAllTodosWithinSubjectCode(
            $studentLoggedInId,
            $current_school_year_id,
            $subject_code);
        

        $get_only_one_assignmentTodoId = NULL;

        if(count($assignmentTodoIds) > 0){
            $get_only_one_assignmentTodoId = $assignmentTodoIds[0];
        }


        // var_dump($assignmentTodoIds);
        // echo $get_only_one_assignmentTodoId;

        $back_url = "../lms/student_dashboard.php";

        ?>

            <div class="row content col-md-12">

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <div class="col-md-9">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center text-muted"><?php echo $subject_code;?></h3>
                        </div>

                        <?php 
                        
                            $sql = $con->prepare("SELECT 

                                t1.*
                            
                                FROM subject_period_code_topic as t1 

                                WHERE t1.subject_code=:subject_code
                                AND t1.school_year_id=:school_year_id

                                ORDER BY
                                CASE subject_period_name
                                    WHEN 'Prelim' THEN 1
                                    WHEN 'Midterm' THEN 2
                                    WHEN 'Pre-final' THEN 3
                                    WHEN 'Final' THEN 4
                                    ELSE 5  
                                END

                                -- ORDER BY t1.period_order ASC
                            ");

                            $sql->bindValue(":subject_code", $subject_code);
                            $sql->bindValue(":school_year_id", $current_school_year_id);
                            $sql->execute();

                            if($sql->rowCount() > 0){
                                $i = 0;

                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                    $subject_period_code_topic_id = $row['subject_period_code_topic_id'];
                                    $subject_period_name = $row['subject_period_name'];

                                    $period_order = $row['period_order'];
                                    $topic = $row['topic'];
                                    $description = $row['description'];

                                    $i++;
                                    ?>

                                        <div class='col-md-12 mb-3'>
                                            
                                            <div style='border: 2px solid green;' class='card'>
                                                <div class='card-body'>
                                                    <div class='card-block'>
                                                        <h4 class='card-title'><?php echo "$i. $topic"?> <span>(<?php echo $subject_period_name?>)</span> </h4>
                                                        <h6 class='card-subtitle text-muted'><?php echo $description?></h6>
                                                        <p class='card-text p-y-1'>Some quick example text to build on the card title.</p>
                                                        
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">

                                                    <table class='a'>
                                                        <thead>
                                                            <tr class='bg-success text-center'>
                                                                <th>Section</th>
                                                                <th>Submitted</th>
                                                                <th>Score</th>
                                                                <th>Due</th>
                                                                <th>Status</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php

                                                                $subjectCodeAssignment = new SubjectCodeAssignment($con);

                                                                $subjectTopicAssignmentList = $subjectCodeAssignment->
                                                                    GetSubjectTopicAssignmentList($subject_period_code_topic_id);
                                                                    

                                                                $handoutList = $subjectCodeAssignment->
                                                                        GetSubjectTopicHandoutList($subject_period_code_topic_id);

                                                                    // print_r($handoutList);

                                                                $mergedList = array_merge($handoutList, $subjectTopicAssignmentList);
                                                                

                                                                if(count($mergedList) > 0){

                                                                    foreach ($mergedList as $key => $row_ass) {
                                                                        
                                                                        $assignment_name = isset($row_ass['assignment_name']) ? $row_ass['assignment_name'] : "";
                                                                        $subject_code_assignment_id = isset($row_ass['subject_code_assignment_id']) ? $row_ass['subject_code_assignment_id'] : "";
                                                                        $due_date = isset($row_ass['due_date']) ? $row_ass['due_date'] : "";
                                                                        $assignment_picture = "";
                                                                        $max_score = isset($row_ass['max_score']) ? $row_ass['max_score'] : "";

                                                                        $section_output = "";

                                                                        $task_view_url = "task_submission.php?sc_id=$subject_code_assignment_id";
                                                                        $handout_name = isset($row_ass['handout_name']) ? $row_ass['handout_name'] : "";
                                                                        $subject_code_handout_id = isset($row_ass['subject_code_handout_id']) ? $row_ass['subject_code_handout_id'] : NULL;

                                                                        if($assignment_name !== ""){
                                                                            $section_output = "
                                                                                <a style='color: blue;' href='$task_view_url'>
                                                                                    $assignment_name
                                                                                </a>
                                                                            ";
                                                                        }else{
                                                                            $section_output = "
                                                                                <a style='color: inherit;' href='topic_module_view.php?id=$subject_code_handout_id'>
                                                                                    <i class='fas fa-file'></i>&nbsp $handout_name
                                                                                </a>
                                                                            ";

                                                                        }
                                                                        echo "
                                                                            <tr class='text-center'>
                                                                                <td>
                                                                                    $section_output
                                                                                </td>
                                                                                <td>
                                                                                    
                                                                                </td>

                                                                                <td>$max_score</td>
                                                                                <td>$due_date</td>
                                                                                <td>

                                                                                </td>
                                                                            </tr>
                                                                        ";
                                                                    }
                                                                }

                                                                $assignment = $con->prepare("SELECT *
                                                
                                                                    FROM subject_code_assignment
                                                                    WHERE subject_period_code_topic_id=:subject_period_code_topic_id
                                                                ");

                                                                $assignment->bindValue(":subject_period_code_topic_id", $subject_period_code_topic_id);
                                                                $assignment->execute();

                                                                if($sql->rowCount() == 0){

                                                                    while($row_ass = $assignment->fetch(PDO::FETCH_ASSOC)){

                                                                        $assignment_name = $row_ass['assignment_name'];
                                                                        $subject_code_assignment_id = $row_ass['subject_code_assignment_id'];
                                                                        $due_date = $row_ass['due_date'];
                                                                        $assignment_picture = "";
                                                                        $max_score = $row_ass['max_score'];

                                                                        $task_view_url = "task_submission.php?sc_id=$subject_code_assignment_id";

                                                                        echo "
                                                                            <tr class='text-center'>
                                                                                <td>
                                                                                    <a style='color: blue;' href='$task_view_url'>
                                                                                        $assignment_name
                                                                                    </a>
                                                                                </td>
                                                                                <td>
                                                                                    
                                                                                </td>

                                                                                <td>$max_score</td>
                                                                                <td>$due_date</td>
                                                                                <td>

                                                                                </td>
                                                                            </tr>
                                                                        ";
                                                                    }
                                                                }


                                                                                
                                                            ?>
                                                        </tbody>
                                                    </table>

                                                    
                                                </div>

                                            </div>


                                        </div>

                                    <?php
                                }
                            }
                        
                        ?>
                    </div>
                </div>
                <br>
                <hr>

                <div class="col-md-3">
                    <div class='card'>
                        <div class='card-header'>
                            <?php if(count($assignmentTodoIds) > 0 && $get_only_one_assignmentTodoId !== NULL):?>
                                <h5 style="margin-bottom: 7px;">Assignments</h5>
                                <p>
                                    <?php
                                        echo "
                                            <a style='color: blue' href='task_submission.php?sc_id=$get_only_one_assignmentTodoId'>".count($assignmentTodoIds)."  assignments due</a>
                                        ";
                                    ?>
                                </p>

                            <?php else:?>

                                <h5 style="margin-bottom: 7px;">No assignments</h5>

                            <?php endif;?>

                            
                        </div>
                    </div>
                </div>
            </div>

        <?php
    }
?>




