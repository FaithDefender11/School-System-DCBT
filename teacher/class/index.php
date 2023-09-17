<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
 

    if(
        isset($_GET['c'])
        && isset($_GET['c_id'])
        
        ){

        $subject_code = $_GET['c'];
        $course_id = $_GET['c_id'];

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];

        $subjectProgram = new SubjectProgram($con);

        $program_code = $subjectProgram->GetProgramCodeBySubjectCode($subject_code,
            $course_id);
            
        
        $subjectCodeAssignment = new SubjectCodeAssignment($con);


        ?>

            <div class="row">

                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center text-muted"><?php echo "$subject_code $current_school_year_term $current_school_year_period Semester";?></h3>
                        
                            <!-- <button onclick="PopulateDefaultTopics('<?php echo $program_code; ?>', <?php echo $course_id?>, <?php echo $teacher_id?>, <?php echo $current_school_year_id; ?>)"
                                class="btn btn-success"
                                type="submit">
                                Populate
                            </button> -->
                        
                        </div>

                        <?php 
                        
                            $sql = $con->prepare("SELECT 

                                t1.*
                            
                                FROM subject_period_code_topic as t1 

                                WHERE t1.subject_code=:subject_code
                                AND t1.school_year_id=:school_year_id
                                AND t1.teacher_id=:teacher_id

                                ORDER BY t1.period_order ASC
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
                                                        <div class='row'>
                                                            <div class='col-md-2'>
                                                                <a href="create_assignment.php?id=<?php echo $subject_period_code_topic_id;?>">
                                                                    <button class='btn btn-info btn-sm'><i class="fas fa-plus"></i> Assignment</button>
                                                                </a>
                                                            </div>
                                                            <div class='col-md-2'>
                                                                <a href="module_create.php?id=<?php echo $subject_period_code_topic_id;?>">
                                                                    <button class='btn btn-sm'><i class="fas fa-plus"></i> Handout</button>
                                                                </a>
                                                            </div>
                                                            <div class='col-md-3'>
                                                                <?php

                                                                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

                                                                    $topic_name = $subjectPeriodCodeTopic->GetTopic();
 
                                                                    $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);
                                                                    $subject_period_code_topic_template_id = $subjectPeriodCodeTopicTemplate->GetTopicTemplateIdByTopicName($topic_name);

                                                                ?>
                                                                <a href="section_topic.php?id=<?php echo $subject_period_code_topic_template_id;?>&ct_id=<?php echo $subject_period_code_topic_id; ?>">
                                                                    <button class='btn btn-sm btn-success'><i class="fas fa-plus"></i> Template</button>
                                                                </a>
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
                                                                            $student_submitted_total = "";
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
                                                                            $student_submitted_total = "? / ?";
                                                                        }

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


</script>



