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
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Student.php');
 
    echo Helper::RemoveSidebar();
    
    ?>
        <head>
                <!--Link JavaScript-->
            <script src="../../assets/js/elms-sidebar.js" defer></script>
            <script src="../../assets/js/elms-dropdown.js" defer></script>
            <script src="../../assets/js/table-dropdown.js" defer></script>

            <link
                rel="stylesheet"
                href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
                crossorigin="anonymous"
                />
                <link
                rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
                />
                <!--Link Fonts-->
                <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css?family=Lato"
                />
                <link
                rel="stylesheet"
                href="https://fonts.googleapis.com/css?family=Arimo"
                />
        </head>
    <?php

    if(
        isset($_GET['c'])
        // && isset($_GET['c_id'])
        && isset($_GET['sy_id'])
        ){



        $subject_code = $_GET['c'];
        // $course_id = $_GET['c_id'];


        $school_year_id = $_GET['sy_id'];

        
        $announcement = new Announcement($con);

        $enrollment = new Enrollment($con);

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

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

                
        $code_topic_course_id = $subjectPeriodCodeTopic->GetCourseIdBySubjectCodeAndSchoolYear(
            $subject_code,
            $school_year_id);

        // var_dump($code_topic_course_id);

        $course_id = $code_topic_course_id;

        $program_code = $subjectProgram->GetProgramCodeBySubjectCode($subject_code,
            $course_id);

        $subject_title = $subjectProgram->GetSubjectProgramTitleByRawCode($program_code);
        
        // var_dump($subject_title);
        

        $announcementList = $announcement->GetAnnouncementsWithinSubjectCode($subject_code, $teacher_id);
            
        
        $subjectCodeAssignment = new SubjectCodeAssignment($con);
        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $back_url = "../dashboard/index.php";

        $assignmentTodoIds = [];

        $allTeachingTopicIds = $subjectPeriodCodeTopic->GetAllsubjectPeriodCodeTopics($subject_code,
            $school_year_id);





        $studentList = $subjectCodeAssignment->GetStudentGradeBookOnTeachingSubject($subject_code, $school_year_id);
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

        $teachingSubjectCode = $subjectCodeAssignment->GetTeacherTeachingSubjects(
            $teacherLoggedInId,
            $school_year_id);

        $teachingSubjects = [];


        foreach ($teachingSubjectCode as $key => $value) {

            $teachingCode = $value['subject_code'];
            array_push($teachingSubjects, $teachingCode);
        }


        $fomatTerm = $enrollment->changeYearFormat($current_school_year_term);
        $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");

        $adminAnnouncement = $announcement->CheckTeacherIdBelongsToAdminAnnouncement($current_school_year_id,
            $teacherLoggedInId);
    
        // var_dump($adminAnnouncement);
        
        $logout_url = 'http://localhost/school-system-dcbt/lms_logout.php';

        if ($_SERVER['SERVER_NAME'] === 'localhost') {

            $base_url = 'http://localhost/school-system-dcbt/teacher/';
        } else {

            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/teacher/';
        }

        if ($_SERVER['SERVER_NAME'] !== 'localhost') {

            $new_url = str_replace("/teacher/", "", $base_url);
            $logout_url = "$new_url/lms_logout.php";
        }


        $getx = $con->prepare("SELECT 

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

        $getx->bindValue(":subject_code", $subject_code);
        $getx->bindValue(":school_year_id", $school_year_id);
        $getx->bindValue(":teacher_id", $teacher_id);
        $getx->execute();

        if($getx->rowCount() > 0){

            $ads = $getx->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($ads);

        }

        ?>

            <div style="min-width: 100%;" class="content">
                
                <?php 
                    echo Helper::lmsTeacherNotificationHeader(
                        $con, $teacherLoggedInId,
                        $current_school_year_id,
                        $teachingSubjects,
                        "second",
                        "first",
                        "second",
                    $logout_url);
                    
                ?>


                <div class="content-header">
                    <header>

                        <div class="title">
                            <h1><span style="font-size: 27px;"><?= $subject_title?></span>  <em style="font-size: 27px;"><?= "SY$fomatTerm-$period_short";?></em></h1>
                        </div>

                        <div class="action">
                            <div class="dropdown">
                                <button class="icon">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">

                                    <a 
                                        href="../announcement/index.php?c_id=<?= $course_id; ?>&c=<?=$subject_code;?>"
                                        class="dropdown-item" style="color: inherit">
                                        <i class="bi bi-megaphone-fill"></i>
                                        Announcement
                                    </a>
                                    
                                </div>
                            </div>
                        </div>

                    </header>
                </div>

                <nav>
                    <a href="<?php echo $back_url; ?>">
                        <i class="bi bi-arrow-return-left"></i>Back
                    </a>
                </nav>

                <main>

                        <div class='card'>
                        <div class='card-header'>
                            <?php if(count($ungradedSubmissionArr) > 0):?>
                                
                                <?php 
                                    $topicCount = [];

                                    echo "<h5 style='margin-bottom: 7px;'>(".count($ungradedSubmissionArr).") To check</h5>";

                                    foreach ($ungradedSubmissionArr as $key => $submission_id) {

                                        # code...
                                        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con, $submission_id);
                                        
                                        $subjectCodeAssignmentId = $subjectAssignmentSubmission->GetSubjectCodeAssignmentId();

                                        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subjectCodeAssignmentId);
                                        
                                        $assignment_title = $subjectCodeAssignment->GetAssignmentName();
                                        $topicId = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();

                                        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $topicId);
                                        $topicName = $subjectPeriodCodeTopic->GetTopic();
                                        $course_id = $subjectPeriodCodeTopic->GetCourseId();
                                        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();


                                        // echo "Topic Name: $topicName";
                                        // echo "<br>";
                                        // echo "<br>";

                                        if (!isset($topicCount[$topicName])) {
                                            $topicCount[$topicName] = [
                                                'count' => 1,
                                                'subject_period_code_topic_id' => $topicId,
                                                'subjectCodeAssignmentId' => $subjectCodeAssignmentId,
                                                'course_id' => $course_id,
                                                'subject_code' => $subject_code,
                                                
                                            ];
                                        } else {
                                            $topicCount[$topicName]['count']++;
                                        }
                                    }

                                    foreach ($topicCount as $topicName => $data) {

                                        $count = $data['count'];
                                        $subjectCodeAssignmentId = $data['subjectCodeAssignmentId'];

                                        $subject_code = $data['subject_code'];
                                        $course_id = $data['course_id'];

                                        //$subject_code_assignment_id = $data['subject_code_assignment_id'];
                                        $subject_period_code_topic_id = $data['subject_period_code_topic_id'];

                                        // $subjectPeriodCodeTopic = 

                                        // $url_overview = "section_topic_grading.php?ct_id=$subject_period_code_topic_id";

                                        // $url_overview = "../dashboard/student_tasks_to_check.php?sc_id=$subjectCodeAssignmentId";

                                        $url_overview = "../dashboard/todos_tasks.php?c_id=$course_id&c=$subject_code";
                                        
                                        echo "
                                            <p style='margin:0'>○
                                                <a style='color:inherit' href='$url_overview'
                                                class='m-0 text-right'> $topicName ($count)</a>
                                            </p>
                                        ";
                                        echo "<br>";

                                    }

                                    // foreach ($assignmentCounts as $assignmentTitle => $data) {

                                    //     $count = $data['count'];
                                    //     // $subject_code_assignment_id = $data['subject_code_assignment_id'];
                                    //     $subject_code_topic_id = $data['subject_code_topic_id'];
                                    //     $topic_subject_code = $data['topic_subject_code'];

                                    //     echo "<a style='color:inherit' href='assignment_due.php?c=$topic_subject_code'
                                    //         class='m-0 text-right'>Assignment Title: $assignmentTitle ($count) Code: $topic_subject_code</a>";

                                    // }
                                
                                
                                ?>

                            <?php else:?>

                                <h5 style="margin-bottom: 7px;">No to check assignments</h5>

                            <?php endif;?>

                            
                        </div>
                    </div>

                    <!-- <div class='card'>
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
                    </div> -->

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
                            $sql->bindValue(":school_year_id", $school_year_id);
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


                                    $subjectCodeHandout = new SubjectCodeHandout($con);
                                   
                                    $subjectCodeAssignment = new SubjectCodeAssignment($con);

                                    $givenAssignmentsInsideTopicSection = $subjectCodeAssignment
                                        ->GetTotalGivenAssignmentByTopicSection($subject_period_code_topic_id);

                                    $givenHandoutInsideTopicSection = $subjectCodeHandout
                                        ->GetTotalGivenHandoutByTopicSection($subject_period_code_topic_id);

                                    $sectionModuleMerge = array_merge($givenAssignmentsInsideTopicSection,
                                        $givenHandoutInsideTopicSection);

                                    $sectionModuleItemsCount = count($sectionModuleMerge);

                                    ?>

                                        <div style="width: 98%;" class="floating">

                                            <header>

                                                <div class="title">
                                                    <h3><?= $topic; ?> <em><?= $subject_period_name;?></em></h3>
                                                    <small><?= $description; ?></small>
                                                </div>

                                                <div>

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
                                                    <button onclick="window.location.href = '../module/handout_index.php?id=<?php echo $subject_period_code_topic_template_id;?>&sct_id=<?php echo $subject_period_code_topic_id ?>' " class="ml-1 task bg-primary" data-toggle="tooltip" data-placement="bottom" title="Handout">
                                                        <?= $totalHandoutCount; ?> <i class="bi bi-file-earmark">+</i>
                                                    </button>

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
                                                                    
                                                                    title="<?= $task_name; ?>"
                                                                    >
                                                                    <button class="ml-1 task bg-dark">
                                                                        <?= $module_count; ?> <i class="bi bi-file-earmark">+</i>

                                                                    </button>
                                                                    </a>

                                                                <?php

                                                            }
                                                        }
                                                    
                                                    ?>

                                                </div>

                                                <div class="action">

                                                    <?= $sectionModuleItemsCount; ?> 
                                                    <?= $sectionModuleItemsCount > 1 ? "Sections" : ($sectionModuleItemsCount == 1 ? "Section" : ""); ?>

                                                    <div class="dropdown">
                                                        <button class="table-drop">
                                                            <i class="bi bi-chevron-up"></i>
                                                            <i class="bi bi-chevron-down"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                            </header>

                                            <main>

                                                <table class="b">

                                                    <thead>
                                                        <tr>
                                                            <th>Section</th>
                                                            <th>No. submitted / Total tudent</th>
                                                            <th>Max Score</th>
                                                            <th>Due</th>
                                                            <!-- <th>Action</th> -->
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

                                                                    $assignment_name = isset($row_ass['assignment_name']) ? $row_ass['assignment_name'] : "";
                                                                    $subject_code_assignment_id = isset($row_ass['subject_code_assignment_id']) ? $row_ass['subject_code_assignment_id'] : "";
                                                                    $due_date = isset($row_ass['due_date']) ? date("M d", strtotime($row_ass['due_date'])) : "";
                                                                    $assignment_picture = "";
                                                                    $max_score = isset($row_ass['max_score']) ? $row_ass['max_score'] : "";


                                                                    // $edit_given_assignment_url = "edit.php?id=$subject_code_assignment_id";
                                                                    $edit_given_assignment_url = "#";

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

                                                                        // <td>
                                                                        //         <button title='View students submissions' onclick='location.href=\"$view_specific_assignment_url\"' class='btn btn-sm btn-primary'>
                                                                        //             <i class='fas fa-eye'></i>
                                                                        //         </button>
                                                                        //         $remove_btn

                                                                        //     </td>

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
                                                                            
                                                                        </tr>
                                                                    ";
                                                                }

                                                            }
                                                        
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </main>
                                        </div>
                                          

                                    <?php
                                }
                            }

                        ?>

                       
                        
                </main>

            </div>
        <?php

    }
?>