<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/TaskType.php');
 

    if(
        isset($_GET['c'])
        && isset($_GET['c_id'])){

        $subject_code = $_GET['c'];
        $course_id = $_GET['c_id'];

        
        $announcement = new Announcement($con);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];

        $subjectProgram = new SubjectProgram($con);


        // $subject_code = $studentSubject->GetStudentSubjectCode();
        // $subjectProgramId = $studentSubject->GetStudentSubjectProgramId();

        $subjectProgram = new SubjectProgram($con);


        $program_code = $subjectProgram->GetProgramCodeBySubjectCode($subject_code,
            $course_id);

        $subject_title = $subjectProgram->GetSubjectProgramTitleByRawCode($program_code);
        

        $announcementList = $announcement->GetAnnouncementsWithinSubjectCode($subject_code, $teacher_id);
            
        
        $subjectCodeAssignment = new SubjectCodeAssignment($con);
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $back_url = "../dashboard/index.php";

        $assignmentTodoIds = [];

        $allTeachingTopicIds = $subjectPeriodCodeTopic->GetAllsubjectPeriodCodeTopics($subject_code,
            $current_school_year_id);

        $studentList = $subjectCodeAssignment->GetStudentGradeBookOnTeachingSubject($subject_code, $current_school_year_id);
        $totalStudents = count($studentList);
        // print_r($allTeachingTopicIds);

        $subjectCodeAssignmentIdsArr = [];

        foreach ($allTeachingTopicIds as $key => $topicIds) {
            # code...
            // $assignmentsBasedFromSubjectTopic = $subjectCodeAssignment->GetAllAssignmentsBasedFromSubjectTopic($topicIds);
            $assignmentsBasedFromSubjectTopicList = $subjectCodeAssignment->GetAllAssignmentsBasedFromSubjectTopic($topicIds);

            if(count($assignmentsBasedFromSubjectTopicList) > 0){

                foreach ($assignmentsBasedFromSubjectTopicList as $key => $assignmentList) {
                    # code...
                    $subject_code_assignment_ids = $assignmentList['subject_code_assignment_id'];
                    // echo $topicIds;
                    // echo "<br>";
                    array_push($subjectCodeAssignmentIdsArr,
                        $subject_code_assignment_ids);

                    // echo "hey";
                    // echo "<br>";
                }
            }

            // $subject_code_assignment_ids = $assignmentsBasedFromSubjectTopicList['subject_code_assignment_id'];
            // var_dump($subject_code_assignment_ids);
        }


        // print_r($subjectCodeAssignmentIdsArr);

        $ungradedSubmissionArr = [];

        if(count($subjectCodeAssignmentIdsArr) > 0){


            foreach ($subjectCodeAssignmentIdsArr as $key => $codeAssignmentId) {
                
                // echo $codeAssignmentId;
                // echo "<br>";
                $submissionList = $subjectAssignmentSubmission->GetSubmittedUngradedSubmission($codeAssignmentId);
                
                
                // var_dump($submissionList);

                foreach ($submissionList as $key => $submissions) {

                    // $subject_assignment_submission_id = $submissions['subject_assignment_submission_id'];
                    
                    $subject_assignment_submission_id = $submissions;
                   
                    array_push($ungradedSubmissionArr,
                        $subject_assignment_submission_id);
                }
                
            }
            
            // print_r($ungradedSubmissionArr);

        }





        ?>
        <div class="content">

            <nav style="min-width: 100%; margin-bottom: 7px;
                display: flex;flex-direction: row;">
                <a href="<?php echo $back_url;?>">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>

                <div style="flex: 1;"  class="text-right">

                    <span>
                        <h3 style="font-weight: bold;"><?= $subject_title?></h3>
                    </span>
                </div>

            </nav>

            <div class="row col-md-12">



                <div class="col-md-9">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center text-muted"><?php echo "$subject_code $current_school_year_term $current_school_year_period Semester";?></h3>
                            <!-- <button onclick="PopulateDefaultTopics('<?php echo $program_code; ?>', <?php echo $course_id?>, <?php echo $teacher_id?>, <?php echo $current_school_year_id; ?>)"
                                class="btn btn-success"
                                type="submit">
                                Populate
                            </button> -->
                        
                            <button onclick="window.location.href='add_announcement.php?c_id=<?php echo $course_id;?>&c=<?php echo $subject_code; ?>'" class="btn btn-sm btn-success">+ Announcement</button>
                        </div>

                        <?php 
                        
                            $sql = $con->prepare("SELECT 

                                t1.*
                            
                                FROM subject_period_code_topic as t1 

                                WHERE t1.subject_code=:subject_code
                                AND t1.school_year_id=:school_year_id
                                AND t1.teacher_id=:teacher_id


                                ORDER BY
                                CASE subject_period_name
                                    WHEN 'Prelim' THEN 1
                                    WHEN 'Midterm' THEN 2
                                    WHEN 'Pre-final' THEN 3
                                    WHEN 'Final' THEN 4
                                    ELSE 5  
                                END

                            ");

                            $sql->bindValue(":subject_code", $subject_code);
                            $sql->bindValue(":school_year_id", $current_school_year_id);
                            $sql->bindValue(":teacher_id", $teacher_id);
                            $sql->execute();

                            if($sql->rowCount() > 0){

                                $i = 0;
                                
                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                    $subject_period_code_topic_id = $row['subject_period_code_topic_id'];
                                    $subject_period_name = $row['subject_period_name'];

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
                                                        
                                                        <div class='row'>

                                                            <!-- <div class='col-md-2'>
                                                                <a href="create_assignment.php?id=<?php echo $subject_period_code_topic_id;?>">
                                                                    <button class='btn btn-info btn-sm'><i class="fas fa-plus"></i> Assignment</button>
                                                                </a>
                                                            </div>
                                                            <div class='col-md-2'>
                                                                <a href="module_create.php?id=<?php echo $subject_period_code_topic_id;?>">
                                                                    <button class='btn btn-sm'><i class="fas fa-plus"></i> Handout</button>
                                                                </a>
                                                            </div> -->

                                                            <div class='row col-md-9'>
                                                                <?php

                                                                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

                                                                    $topic_name = $subjectPeriodCodeTopic->GetTopic();
 
                                                                    $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);
                                                                    $subject_period_code_topic_template_id = $subjectPeriodCodeTopicTemplate->GetTopicTemplateIdByTopicName($topic_name);

                                                                    

                                                                    $subjectCodeHandout = new SubjectCodeHandout($con);
                                                                    
                                                                    $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);

                                                                    # HANDOUTS (TEMPLATE AND NON TEMPLATE).

                                                                    $codeHandoutTemplateList = $subjectCodeAssignmentTemplate->GetCodeHandoutTopicTemplateList(
                                                                        $subject_period_code_topic_template_id);

                                                                    $nonTemplateHandout = $subjectCodeHandout->GetNonTemplateHandoutBasedOnSubjectTopic(
                                                                        $subject_period_code_topic_id);
                                                                    
                                                                    $topicHandoutsMerge = array_merge($codeHandoutTemplateList, $nonTemplateHandout);

                                                                    $totalHandoutCount = count($topicHandoutsMerge);

                                                                    # ASSIGNMENTS (TEMPLATE AND NON TEMPLATE).

                                                                    $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);
                                                                    $subjectCodeAssignment = new SubjectCodeAssignment($con);


                                                                    $codeAssignmentTemplateList = $subjectCodeAssignmentTemplate->GetCodeAssignmentTopicTemplateList(
                                                                        $subject_period_code_topic_template_id);

                                                                    $nonTemplateAssignment = $subjectCodeAssignment->GetNonTemplateAssignmentBasedOnSubjectTopic(
                                                                        $subject_period_code_topic_id);


                                                                    $assignmentHandoutsMerge = array_merge($codeAssignmentTemplateList,
                                                                        $nonTemplateAssignment);

                                                                    $totalAssignmentCount = count($assignmentHandoutsMerge);

                                                                ?>

                                                                <a href="section_topic.php?id=<?php echo $subject_period_code_topic_template_id;?>&ct_id=<?php echo $subject_period_code_topic_id; ?>">
                                                                    <button class='btn btn-sm btn-success'><i class="fas fa-plus"></i> View</button>
                                                                </a>

                                                                <?php
                                                                
                                                                    // echo $subject_period_code_topic_id;
                                                                        
                                                                    $task_query = $con->prepare("SELECT * FROM task_type
                                                                        WHERE enabled = 1");
                                                                    
                                                                    $task_query->execute();

                                                                    if($task_query->rowCount() > 0){

                                                                        while($row = $task_query->fetch(PDO::FETCH_ASSOC)){

                                                                            $task_type_id =  $row['task_type_id'];
                                                                            $task_name =  $row['task_name'];

                                                                            

                                                                            $taskType = new TaskType($con);

                                                                            $module_count = $taskType->GetTaskTypeModuleCount($task_type_id,
                                                                                $subject_period_code_topic_id);
                                                                            
                                                                            ?>

                                                                            <a style="color: inherit;" href = '../module/task.php?id=<?= $task_type_id; ?>&sctt_id=<?php echo $subject_period_code_topic_template_id;?>&sct_id=<?php echo $subject_period_code_topic_id ?>'
                                                                                class="ml-1 task bg-warning"
                                                                                title="<?= $task_name; ?>"
                                                                                >

                                                                                <?= $module_count; ?> <i class="bi bi-file-earmark">+</i>
                                                                             </a>

                                                                            <?php

                                                                        }
                                                                    }
                                                                
                                                                ?>

                                                                <button onclick="window.location.href = '../module/handout_index.php?id=<?php echo $subject_period_code_topic_template_id;?>&sct_id=<?php echo $subject_period_code_topic_id ?>' " class="ml-1 task bg-dark" data-toggle="tooltip" data-placement="bottom" title="Handout">
                                                                   <?= $totalHandoutCount; ?> <i class="bi bi-file-earmark">+</i>
                                                                </button>

                                                                <button class="ml-1 task bg-primary" data-toggle="tooltip" data-placement="bottom" title="Activity">
                                                                    <i class="bi bi-file-earmark">+</i>
                                                                </button>

                                                                <button onclick="window.location.href = '../module/assignment_index.php?id=<?php echo $subject_period_code_topic_template_id;?>&sct_id=<?php echo $subject_period_code_topic_id ?>' " class="ml-1 task bg-info" data-toggle="tooltip" data-placement="bottom" title="Assignment">
                                                                   <?= $totalAssignmentCount; ?> <i class="bi bi-file-earmark">+</i>
                                                                </button>



                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <table id="my_class_table" class='a'>
                                                        <thead>
                                                            <tr class='bg-dark text-center'>
                                                                <th>Assignment</th>
                                                                <th>Submitted / Total</th>
                                                                <th>Max Score</th>
                                                                <th>Due</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php

                                                                $subjectTopicAssignmentList = $subjectCodeAssignment->
                                                                    GetSubjectTopicAssignmentList($subject_period_code_topic_id);
                                                                
                                                                $handoutList = $subjectCodeAssignment->
                                                                    GetSubjectTopicHandoutList($subject_period_code_topic_id);
                                                                
                                                                $mergedList = array_merge($handoutList, $subjectTopicAssignmentList);

                                                                $submission = new SubjectAssignmentSubmission($con);

                                                                // print_r($mergedList);

                                                                if(count($mergedList) > 0){

                                                                    foreach ($mergedList as $key => $row_ass) {
                                                                        # code...
                                                                        $assignment_name = isset($row_ass['assignment_name']) ? $row_ass['assignment_name'] : "";
                                                                        $subject_code_assignment_id = isset($row_ass['subject_code_assignment_id']) ? $row_ass['subject_code_assignment_id'] : "";
                                                                        $due_date = isset($row_ass['due_date']) ? date("M d", strtotime($row_ass['due_date'])) : "";
                                                                        $assignment_picture = "";
                                                                        $max_score = isset($row_ass['max_score']) ? $row_ass['max_score'] : "";


                                                                        $edit_given_assignment_url = "edit.php?id=$subject_code_assignment_id";

                                                                        $view_specific_assignment_url = "submission_list.php?id=$subject_code_assignment_id";

                                                                        $section_output = "";

                                                                        $handout_name = isset($row_ass['handout_name']) ? $row_ass['handout_name'] : "";
                                                                        $subject_code_handout_id = isset($row_ass['subject_code_handout_id']) ? $row_ass['subject_code_handout_id'] : NULL;
                                                                        
                                                                        $student_submitted_total = "";

                                                                        $total = $submission->GetTotalSubmittedOnAssignment($subject_code_assignment_id);

                                                                        $totalCount = count($total);

                                                                        // echo count($total);

                                                                        $student_submitted_total = $subject_code_assignment_id !== "" ? $totalCount . " / $totalStudents" : "~";

                                                                        // echo var_dump($subject_code_assignment_id);
                                                                        // echo "<br>";

                                                                        $remove_btn = "";

                                                                        if($assignment_name === "" && $subject_code_handout_id !== NULL){
                                                                            
                                                                            $removeHandout = "removeHandout($subject_code_handout_id, $current_school_year_id, $teacher_id)";

                                                                            $section_output = "
                                                                                <a style='color: inherit;' href='module_edit.php?id=$subject_code_handout_id'>
                                                                                    <i class='fas fa-file'></i>&nbsp $handout_name
                                                                                </a>
                                                                            ";
                                                                            $remove_btn = "
                                                                                <button onclick='$removeHandout' class='btn btn-sm btn-danger'>
                                                                                    <i class='fas fa-times'></i>
                                                                                </button>
                                                                            ";
                                                                        }

                                                                        else if($assignment_name != "" && $subject_code_assignment_id != 0){

                                                                            $removeAssignment = "removeAssignment($subject_code_assignment_id, $current_school_year_id, $teacher_id)";

                                                                            $section_output = "
                                                                                <a style='color: inherit;' href='$edit_given_assignment_url'>
                                                                                    $assignment_name
                                                                                </a>
                                                                            ";
                                                                            
                                                                            $remove_btn = "
                                                                                <button onclick='$removeAssignment' class='btn btn-sm btn-danger'>
                                                                                    <i class='fas fa-trash'></i>
                                                                                </button>
                                                                            ";
                                                                        }

                                                                            // $student_submitted_total = "? / ?";


                                                                        echo "
                                                                            <tr class='text-center'>
                                                                                <td>
                                                                                    <a style='color: inherit;' href='$edit_given_assignment_url'>
                                                                                        $section_output
                                                                                    </a>
                                                                                </td>
                                                                                <td>
                                                                                    $student_submitted_total
                                                                                </td>
                                                                                <td>$max_score</td>
                                                                                <td>$due_date</td>
                                                                                <td>
                                                                                    <button title='View students submissions' onclick='location.href=\"$view_specific_assignment_url\"' class='btn btn-sm btn-primary'>
                                                                                        <i class='fas fa-eye'></i>
                                                                                    </button>
                                                                                    $remove_btn

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

                <div class="col-md-3">
                    <div class='card'>
                        <div class='card-header'>
                            <?php if(count($ungradedSubmissionArr) > 0):?>
                                
                                <?php 
                                    $topicCount = [];

                                    echo "<h5 style='margin-bottom: 7px;'>To check ".count($ungradedSubmissionArr)."</h5>";

                                    foreach ($ungradedSubmissionArr as $key => $submission_id) {

                                        # code...
                                        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $submission_id);
                                        
                                        $subjectCodeAssignmentId = $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();

                                        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subjectCodeAssignmentId);
                                        
                                        $assignment_title = $subjectCodeAssignment->GetAssignmentName();
                                        $topicId = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();

                                        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $topicId);
                                        $topicName = $subjectPeriodCodeTopic->GetTopic();
                                        $topicName = $subjectPeriodCodeTopic->GetTopic();


                                        // echo "Topic Name: $topicName";
                                        // echo "<br>";
                                        // echo "<br>";

                                        if (!isset($topicCount[$topicName])) {
                                            $topicCount[$topicName] = [
                                                'count' => 1,
                                                'subject_period_code_topic_id' => $topicId,
                                                'subjectCodeAssignmentId' => $subjectCodeAssignmentId,
                                                
                                            ];
                                        } else {
                                            $topicCount[$topicName]['count']++;
                                        }
                                    }

                                    foreach ($topicCount as $topicName => $data) {

                                        $count = $data['count'];
                                        $subjectCodeAssignmentId = $data['subjectCodeAssignmentId'];

                                        //$subject_code_assignment_id = $data['subject_code_assignment_id'];
                                        $subject_period_code_topic_id = $data['subject_period_code_topic_id'];

                                        // $subjectPeriodCodeTopic = 

                                        // $url_overview = "section_topic_grading.php?ct_id=$subject_period_code_topic_id";

                                        $url_overview = "../dashboard/student_tasks_to_check.php?sc_id=$subjectCodeAssignmentId";
                                        
                                        echo "
                                            <p style='margin:0'>○
                                                <a style='color:inherit' href='$url_overview'
                                                class='m-0 text-right'> $topicName ($count)</a>
                                            </p>
                                        ";
                                        echo "<br>";

                                    }
                                
                                ?>

                            <?php else:?>

                                <h5 style="margin-bottom: 7px;">No to check assignments</h5>

                            <?php endif;?>

                            
                        </div>
                    </div>

                    <hr>
                    <div class='card'>
                        <div id="notification_div" class='card-header'>
                            <?php if(count($announcementList) > 0):?>

                                <p style="margin-bottom: 7px;"><?php echo count($announcementList); ?> Announcement(s)</p>
                                <?php 

                                    $i= 0;
                                    foreach ($announcementList as $key => $value) {

                                        $title = $value['title'];
                                        $announcement_id = $value['announcement_id'];
                                        $i++;

                                        $removeAnnouncement = "removeAnnouncement($announcement_id, $teacher_id)";

                                        # code...
                                        echo "
                                            <a href='subject_announcement.php?id=$announcement_id'>
                                                <span>$i. $title </span>
                                            </a>
                                            <span><i onclick='$removeAnnouncement' style='color: orange' class='fas fa-times'></i></span>
                                            <br>
                                        ";
                                    }
                                ?>
                            <?php else:?>
                                <h5 style="margin-bottom: 7px;">No to check assignments</h5>
                            <?php endif;?>
                        </div>
                    </div>

                   
                </div>

            </div>

        </div>
        <?php
    }
?>

<script>

    function PopulateDefaultTopics(program_code, course_id, teacher_id, school_year_id){
        Swal.fire({
                icon: 'question',
                title: `Do you want to populate here the ${program_code} topics?`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/class/populateTopic.php",
                        type: 'POST',
                        data: {
                            program_code,
                            course_id,
                            teacher_id,
                            school_year_id
                        },
                        success: function(response) {
                            response = response.trim();

                            console.log(response);

                            if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Populated`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                location.reload();
                            });
                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }

    function removeAssignment(subject_code_assignment_id,
        current_school_year_id, teacher_id){

        var subject_code_assignment_id = parseInt(subject_code_assignment_id);
        var current_school_year_id = parseInt(current_school_year_id);
        var teacher_id = parseInt(teacher_id);

        Swal.fire({
                icon: 'question',
                title: `Are you sure you want remove the selected assignment?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                    url: "../../ajax/class/removeAssignment.php",
                        type: 'POST',
                        data: {
                            subject_code_assignment_id,
                            current_school_year_id,
                            teacher_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Deleted`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                // $('#my_class_table').load(
                                //     location.href + ' #my_class_table'
                                // );
                                location.reload();
                            });}

                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                            console.error('Error:', error);
                            console.log('Status:', status);
                            console.log('Response Text:', xhr.responseText);
                            console.log('Response Code:', xhr.status);
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }

    function removeHandout(subject_code_handout_id,
        current_school_year_id, teacher_id){

        var subject_code_handout_id = parseInt(subject_code_handout_id);
        var current_school_year_id = parseInt(current_school_year_id);
        var teacher_id = parseInt(teacher_id);

        Swal.fire({
                icon: 'question',
                title: `Are you sure you want remove the selected handout?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                    url: "../../ajax/class/removeHandout.php",
                        type: 'POST',
                        data: {
                            subject_code_handout_id,
                            current_school_year_id,
                            teacher_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Deleted`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                $('#my_class_table').load(
                                    location.href + ' #my_class_table'
                                );

                                // location.reload();
                            });}

                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                            console.error('Error:', error);
                            console.log('Status:', status);
                            console.log('Response Text:', xhr.responseText);
                            console.log('Response Code:', xhr.status);
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }

    function removeAnnouncement(announcement_id, teacher_id){

        var announcement_id = parseInt(announcement_id);
        // var current_school_year_id = parseInt(current_school_year_id);
        var teacher_id = parseInt(teacher_id);

        Swal.fire({
                icon: 'question',
                title: `Are you sure you want remove the selected handout?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                    url: "../../ajax/class/removeAnnouncement.php",
                        type: 'POST',
                        data: {
                            announcement_id,
                            // current_school_year_id,
                            teacher_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Deleted`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                // $('#notification_div').load(
                                //     location.href + ' #notification_div'
                                // );

                                location.reload();
                                
                            });}

                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                            console.error('Error:', error);
                            console.log('Status:', status);
                            console.log('Response Text:', xhr.responseText);
                            console.log('Response Code:', xhr.status);
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }


</script>



