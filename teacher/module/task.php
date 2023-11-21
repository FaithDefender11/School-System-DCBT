<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/TaskType.php');

    if(
        isset($_GET['sctt_id'])
        && isset($_GET['sct_id'])
        && isset($_GET['id'])
        ){

        $subject_period_code_topic_template_id = $_GET['sctt_id'];
        $subject_period_code_topic_id = $_GET['sct_id'];
        $task_type_id = $_GET['id'];



        // echo "task_type_id: $task_type_id";
        // echo "<br>";

        $taskType = new TaskType($con, $task_type_id);

        $taskName = $taskType->GetTaskName();

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);


        $teacher_id = $_SESSION['teacherLoggedInId'];


        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);
        $subjectCodeAssignment = new SubjectCodeAssignment($con);


        $codeAssignmentTemplateList = $subjectCodeAssignmentTemplate->GetCodeAssignmentTopicTemplateList(
            $subject_period_code_topic_template_id, $task_type_id);

        // var_dump($codeAssignmentTemplateList);

 
        $nonTemplateAssignment = $subjectCodeAssignment->GetNonTemplateAssignmentBasedOnSubjectTopic(
            $subject_period_code_topic_id, $task_type_id);



        $assignmentsMerge = array_merge($codeAssignmentTemplateList,
            $nonTemplateAssignment);

        // var_dump($assignmentsMerge);
        

        $topic = $subjectPeriodCodeTopic->GetTopic();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $course_id = $subjectPeriodCodeTopic->GetCourseId();
        $school_year_id = $subjectPeriodCodeTopic->GetSchoolYearId();


        $back_url = "../class/index.php?c=$subject_code&sy_id=$school_year_id";
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
                            <h3><?= $topic; ?>&nbsp; <?= $taskName?></h3>
                        </div>
                        <div class="action">
                            <button class="clean large" onclick="window.location.href='task_create.php?id=<?= $subject_period_code_topic_id;?>&t_id=<?=$subject_period_code_topic_template_id;?>&task_id=<?= $task_type_id;?>'">
                                + <?= $taskName;?>
                            </button>
                        </div>
                    </header>
                    <main style='overflow-x: auto'>
                        <?php if(count($assignmentsMerge) > 0):?>
                            <table class="a" id="assignments_table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <!-- <th>File</th> -->
                                        <th>From Template</th>
                                        <th>Given</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 0;

                                        foreach ($assignmentsMerge as $key => $row) {


                                            $template_status = "";
                                            $output_section = "";
                                            $given_status = "";
                                            $output_btn = "";

                                            $i++;
 
                                            # Assignment Template
                                            $subject_code_assignment_template_id = isset($row['subject_code_assignment_template_id']) ? $row['subject_code_assignment_template_id'] : NULL;
                                            
                                            $template_subject_code_assignment_id = isset($row['template_subject_code_assignment_id']) ? $row['template_subject_code_assignment_id'] : NULL;
                                            
                                            $assignment_name = isset($row['assignment_name']) ? $row['assignment_name'] : '';
                                            $description = isset($row['description']) ? $row['description'] : '';
                                            $max_score = isset($row['max_score']) ? $row['max_score'] : '';
                                            $type = isset($row['type']) ? ucwords($row['type']) : '';


                                            # Non Template Assignment
                                            $nonTemplateSubjectCodeAssignmentId = isset($row['nonTemplateSubjectCodeAssignmentId']) ? $row['nonTemplateSubjectCodeAssignmentId'] : NULL;
                                            $nonTemplateSubjectAssignmentName = isset($row['nonTemplateSubjectAssignmentName']) ? $row['nonTemplateSubjectAssignmentName'] : '';
                                            $nonTemplateSubjectAssignmentIsGiven = isset($row['nonTemplateSubjectAssignmentIsGiven']) ? $row['nonTemplateSubjectAssignmentIsGiven'] : NULL;


                                            $sc_subject_period_code_topic_template_id = NULL;
                                            $sc_subject_code_assignment_template_id = NULL;
                                            $sc_assignment_template_is_given = NULL;
                                            $sc_assignment_id = NULL;

                                            $queryAssignment = $con->prepare("SELECT 
                                                t1.subject_code_assignment_template_id AS sc_subject_code_assignment_template_id,
                                                t1.is_given AS sc_assignment_template_is_given,
                                                t1.subject_code_assignment_id AS sc_assignment_id
                                                
                                                FROM subject_code_assignment as t1

                                                INNER JOIN subject_period_code_topic AS t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                AND t2.school_year_id=:school_year_id
                                                
                                                WHERE t1.subject_code_assignment_template_id=:subject_code_assignment_template_id
                                                LIMIT 1
                                                ");

                                            $queryAssignment->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
                                            $queryAssignment->bindValue(":school_year_id", $current_school_year_id);
                                            $queryAssignment->execute();

                                            if($queryAssignment->rowCount() > 0){

                                                # subject_code_assignment subject_code_assignment_template id
                                                $row_assignment = $queryAssignment->fetch(PDO::FETCH_ASSOC);

                                                $sc_subject_code_assignment_template_id = $row_assignment['sc_subject_code_assignment_template_id'];
                                                $sc_assignment_template_is_given = $row_assignment['sc_assignment_template_is_given'];
                                                $sc_assignment_id = $row_assignment['sc_assignment_id'];
                                            }

                                            $template_status = "";

                                            $assignment_status = "";
                                            $assignment_btn = "";

                                            if($sc_subject_code_assignment_template_id != $subject_code_assignment_template_id){
                                                $assignment_btn = "
                                                    <button onclick='window.location.href = \"assignment_template_create.php?id=$subject_code_assignment_template_id&ct_id=$subject_period_code_topic_id&t_id=$subject_period_code_topic_template_id&task_id=$task_type_id\"' class='btn btn-sm btn-primary'>
                                                        <i class='fas fa-plus'></i>
                                                    </button>
                                                ";
                                            }

                                            $output_section = "";
                                            $output_btn = "";
                                            $given_status = "";

                                            $desiredMadeAction = "";

                                            # Template Assignment LOGIC HERE
                                            if($subject_code_assignment_template_id !== NULL){
                                                    
                                                // $given_status = $assignment_status;

                                                if($sc_assignment_template_is_given == 1 && $sc_assignment_id !== NULL){

                                                    $ungiveAssignmentTemplate = "ungiveAssignmentTemplate($sc_assignment_id, $subject_period_code_topic_id, $teacher_id)";
                                                    $given_status = "
                                                        <i onclick='$ungiveAssignmentTemplate' style='cursor:pointer; color: green;' class='fas fa-check'></i>
                                                    ";
                                                    
                                                }
                                                if($sc_assignment_template_is_given == 0){
                                                    
                                                    $given_status = "
                                                        <i style='color: orange;' class='fas fa-times'></i>
                                                    ";
                                                }
                                                
                                                $output_btn = $assignment_btn;

                                                $template_status = "
                                                    <i style='color: green;' class='fas fa-check'></i>
                                                ";


                                                $edit_given_template_assignment_url = "task_edit.php?id=$template_subject_code_assignment_id&task_id=$task_type_id&view_only=true";
                                                
                                                $output_section = "
                                                    <a style='color: inherit;' href='$edit_given_template_assignment_url'>
                                                        $assignment_name
                                                    </a>
                                                ";
                                            }

                                            $assignment_edit_url = "task_edit.php?id=$nonTemplateSubjectCodeAssignmentId&task_id=$task_type_id";


                                            # NON TEMPLATE Assignment
                                            if($nonTemplateSubjectCodeAssignmentId !== NULL){
                                                    
                                                // $filename = basename($nonTemplateFile);
                                                // $extension = pathinfo($nonTemplateFile, PATHINFO_EXTENSION);
                                                // $parts = explode('_', $nonTemplateFile);
                                                // $original_file_name = end($parts);

                                                // $nonTemplateFilePath = "../../$nonTemplateFile";

                                                $given_status = $nonTemplateSubjectAssignmentIsGiven;

                                                # GIVEN STATUS CHECK
                                                if($nonTemplateSubjectAssignmentIsGiven == 1){

                                                    $unGiveMadeAssignment = "unGiveMadeAssignment($nonTemplateSubjectCodeAssignmentId,
                                                        $subject_period_code_topic_id, $teacher_id)";

                                                    $given_status = "
                                                        <i onclick='$unGiveMadeAssignment' style='cursor: pointer;color: yellow;' class='fas fa-check'></i>
                                                    ";

                                                    $assignment_view_only_url = "task_edit.php?id=$nonTemplateSubjectCodeAssignmentId&task_id=$task_type_id&view_only=true";

                                                    $output_section = "
                                                        <a style='color: inherit' href='$assignment_view_only_url'>
                                                            $nonTemplateSubjectAssignmentName
                                                        </a>
                                                    ";
                                                }else{
                                                    $output_section = "
                                                    <a style='color: inherit' href='$assignment_edit_url'>
                                                        $nonTemplateSubjectAssignmentName
                                                    </a>
                                                ";
                                                }
                                                if($nonTemplateSubjectAssignmentIsGiven == 0){

                                                    # Only in a state of ungiven can removed the made assignment.
                                                    $removeMadeAssignment = "removeMadeAssignment($nonTemplateSubjectCodeAssignmentId,
                                                            $subject_period_code_topic_id, $teacher_id)";

                                                    $output_btn = "
                                                        <button onclick='$removeMadeAssignment' class='btn btn-danger btn-sm'>
                                                            <i class='bi bi-trash'></i>
                                                        </button>
                                                    ";

                                                    $giveMadeAssignment = "giveMadeAssignment($nonTemplateSubjectCodeAssignmentId,
                                                        $subject_period_code_topic_id, $teacher_id)";

                                                    $given_status = "
                                                        <i onclick='$giveMadeAssignment' style='cursor:pointer; color: yellow;' class='fas fa-times'></i>
                                                    ";
                                                    
                                                    // $given_status = "
                                                    //     <i onclick=\"window.location.href = 'task_edit.php?id=104&task_id=$task_type_id&given=true'\" style='cursor:pointer; color: yellow;' class='fas fa-times'></i>
                                                    // ";
                                                    
                                                }

                                                // $output_btn = $handout_btn;
                                                $template_status = "
                                                    <i style='color: yellow;' class='fas fa-times'></i>
                                                ";

                                                // $assignment_edit_url = "assignment_edit.php?id=$nonTemplateSubjectCodeAssignmentId";
                                                
                                                // $assignment_edit_url = "task_edit.php?id=$nonTemplateSubjectCodeAssignmentId&task_id=$task_type_id";
                                                
                                                // $output_section = "
                                                //     <a style='color: inherit' href='$assignment_edit_url'>
                                                //         $nonTemplateSubjectAssignmentName
                                                //     </a>
                                                // ";

                                            }



                                            echo "
                                                <tr>
                                                    <td>$i. $output_section</td>
                                                    <td>$template_status</td>
                                                    <td>$given_status</td>
                                                    <td>$output_btn</td>
                                                </tr>
                                            ";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </main>
                </div>
            </main>
        </div>
    <?php
        }
    ?>
    <script>

        // Template Assignment

        function ungiveAssignmentTemplate(subject_code_assignment_id,
        subject_period_code_topic_id, teacher_id){

        var subject_code_assignment_id = parseInt(subject_code_assignment_id);
        var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
        var teacher_id = parseInt(teacher_id);

        Swal.fire({
                icon: 'question',
                title: `Do you want to un-give the selected assignment?`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                    url: "../../ajax/class/ungiveAssignmentTemplate.php",
                        type: 'POST',
                        data: {
                            subject_code_assignment_id,
                            subject_period_code_topic_id,
                            teacher_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Un-gived Assignment`,
                                showConfirmButton: false,
                                timer: 1200, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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

                                $('#assignments_table').load(
                                    location.href + ' #assignments_table'
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

        // MADE Assignment
    function giveMadeAssignment(subject_code_assignment_id,
        subject_period_code_topic_id, teacher_id){

            
        var subject_code_assignment_id = parseInt(subject_code_assignment_id);
        var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
        var teacher_id = parseInt(teacher_id);

        Swal.fire({
    icon: 'question',
    title: `This action will give the selected file. Please make sure you already check the file.`,
    showCancelButton: true,
    confirmButtonText: 'Checked & give now',
    cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                    url: "../../ajax/class/giveAssignmentMade.php",
                        type: 'POST',
                        data: {
                            subject_code_assignment_id,
                            subject_period_code_topic_id,
                            teacher_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Assignment has been successfully given`,
                                showConfirmButton: false,
                                timer: 1200, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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

                                $('#assignments_table').load(
                                    location.href + ' #assignments_table'
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

        function unGiveMadeAssignment(subject_code_assignment_id,
            subject_period_code_topic_id, teacher_id){

                
            var subject_code_assignment_id = parseInt(subject_code_assignment_id);
            var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
            var teacher_id = parseInt(teacher_id);

            Swal.fire({
                    icon: 'question',
                    title: `Do you want to un-give the selected assignment?`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                        url: "../../ajax/class/unGiveMadeAssignment.php",
                            type: 'POST',
                            data: {
                                subject_code_assignment_id,
                                subject_period_code_topic_id,
                                teacher_id
                            },
                            success: function(response) {

                                response = response.trim();

                                console.log(response);

                                if(response == "success"){
                                    Swal.fire({
                                    icon: 'success',
                                    title: `Assignment has been successfully reverted`,
                                    showConfirmButton: false,
                                    timer: 1200, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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

                                    $('#assignments_table').load(
                                        location.href + ' #assignments_table'
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

        function removeMadeAssignment(subject_code_assignment_id,
            subject_period_code_topic_id, teacher_id){

            var subject_code_assignment_id = parseInt(subject_code_assignment_id);
            var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
            var teacher_id = parseInt(teacher_id);

            Swal.fire({
                    icon: 'question',
                    title: `Do you want to remove the selected assignment?`,
                    text: 'Important! This action cannot be undone',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                        url: "../../ajax/class/removeMadeAssignment.php",
                            type: 'POST',
                            data: {
                                subject_code_assignment_id,
                                subject_period_code_topic_id,
                                teacher_id
                            },
                            success: function(response) {

                                response = response.trim();

                                console.log(response);

                                if(response == "success"){
                                    Swal.fire({
                                    icon: 'success',
                                    title: `Assignment has been removed`,
                                    showConfirmButton: false,
                                    timer: 1200, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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

                                    $('#assignments_table').load(
                                        location.href + ' #assignments_table'
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
    </script>
    </body>
</html>