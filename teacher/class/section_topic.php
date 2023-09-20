<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
 
    ?>
        <style>
        .dropdown-menu.show{
            margin-left: -157px;
        }
        </style>
    <?php

    if(isset($_GET['id'])
        && isset($_GET['ct_id'])
    ){
 
        $subject_period_code_topic_template_id = $_GET['id'];
        $subject_period_code_topic_id = $_GET['ct_id'];

        // $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate(
        //     $con, $subject_period_code_topic_template_id);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $teacher_id = $_SESSION['teacherLoggedInId'];

        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate(
            $con);

        $codeAssignmentTemplateList = $subjectCodeAssignmentTemplate->GetCodeAssignmentTopicTemplateList(
            $subject_period_code_topic_template_id);

        $codeHandoutTemplateList = $subjectCodeAssignmentTemplate->GetCodeHandoutTopicTemplateList(
            $subject_period_code_topic_template_id);

        
        // var_dump($codeAssignmentTemplateList);
            
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);



        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $topic_course_id = $subjectPeriodCodeTopic->GetCourseId();
        $topic_name = $subjectPeriodCodeTopic->GetTopic();
        $subject_period_name = $subjectPeriodCodeTopic->GetSubjectPeriodName();

        $back_url = "index.php?c_id=$topic_course_id&c=$topic_subject_code";

        ?>

            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
            
                <main>
                    <h4 style="font-weight: bold;" class="text-muted text-start"><?php echo $topic_name; ?> (<?php echo $subject_period_name?>)</h4>
                    
                    <span>
                        <button onclick="window.location.href = 'section_topic_grading.php?ct_id=<?php echo $subject_period_code_topic_id; ?>' " class="btn btn-sm">To Grade: </button> 
                    </span>

                    <div class="floating" id="shs-sy">

                        <header>
                            <div class="title">
                                <!-- <h5>Default Handout for Topic: <span><?php echo $topic_name; ?></span></h5> -->
                                <h3 class="text-muted text-start">Module Overview</h3>
                            </div>

                           <div class="action">

                                <div class="dropdown">
                                    <button class="icon">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">

                                       <a 
                                        href="module_create.php?id=<?php echo $subject_period_code_topic_id;?>"
                                        class="dropdown-item" style="color: green">
                                            <i class="bi bi-file-earmark-x"></i>
                                            Add Handout
                                        </a>
                                        <a href="create_assignment.php?id=<?php echo $subject_period_code_topic_id;?>" class="dropdown-item" style="color: blue">
                                            <i class="bi bi-file-earmark-x"></i>
                                            Add Assignment
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </header>
                        <main>

                        <?php

                            $mergeHandoutWithAssignmentList = array_merge($codeHandoutTemplateList, $codeAssignmentTemplateList);

                            // print_r($mergeHandoutWithAssignmentList);

                            if(count($mergeHandoutWithAssignmentList) > 0){

                                ?>
                                    <table id="handoutt_template_table" class="a" style="margin: 0">
                                        <thead>
                                            <tr>
                                                <th>Section</th>
                                                <th>Given</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                             
                                                $i = 0;

                                                foreach ($mergeHandoutWithAssignmentList as $key => $row) {

                                                    # code...

                                                    $i++;

                                                    $subject_code_handout_template_id = isset($row['subject_code_handout_template_id']) ? $row['subject_code_handout_template_id'] : NULL;
                                                    $subject_period_code_topic_templatee_id = isset($row['subject_period_code_topic_template_id']) ? $row['subject_period_code_topic_template_id'] : NULL;
                                                    $handout_name = isset($row['handout_name']) ? $row['handout_name'] : '';
                                                    $file = isset($row['file']) ? $row['file'] : '';

                                                    $handout_subject_code_handout_template_id = NULL;

                                                    $query = $con->prepare("SELECT 
                                                        t1.subject_code_handout_template_id AS handout_subject_code_handout_template_id
                                                        
                                                        FROM subject_code_handout as t1

                                                        INNER JOIN subject_period_code_topic AS t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                        AND t2.school_year_id=:school_year_id
                                                        
                                                        WHERE t1.subject_code_handout_template_id=:subject_code_handout_template_id
                                                        LIMIT 1
                                                        ");

                                                    $query->bindValue(":subject_code_handout_template_id", $subject_code_handout_template_id);
                                                    $query->bindValue(":school_year_id", $current_school_year_id);
                                                    $query->execute();

                                                    if($query->rowCount() > 0){
                                                        $handout_subject_code_handout_template_id = $query->fetchColumn();
                                                    }

                                                    $handout_status = "";
                                                    $handout_btn = "";


                                                    // if(false){
                                                    if($subject_code_handout_template_id == $handout_subject_code_handout_template_id){
                                                        
                                                        $handout_status = "
                                                            <i style='color: green;' class='fas fa-check'></i>
                                                        ";
                                                    }
                                                    else if($subject_code_handout_template_id != $handout_subject_code_handout_template_id
                                                        && $subject_code_handout_template_id != NULL){

                                                        $attachModule = "attachModule($subject_code_handout_template_id, \"$handout_name\", $subject_period_code_topic_id)";

                                                        $handout_status = "
                                                            <i style='color: orange;' class='fas fa-times'></i>
                                                        ";
                                                        $handout_btn = "
                                                            <button onclick='$attachModule' class='btn btn-info'>
                                                                <i class='fas fa-plus'></i>
                                                            </button>
                                                        ";
                                                    }


                                                    $filename = basename($file);

                                                    $extension = pathinfo($file, PATHINFO_EXTENSION);

                                                    $parts = explode('_', $file);

                                                    $original_file_name = end($parts);

                                                    $file_output = "";

                                                    $filePath = "../../$file";

                                                    if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                        $file_output = "
                                                            <a title='View File' href='$filePath' target='__blank' rel='noopener noreferrer'>
                                                                $original_file_name
                                                            </a>
                                                            <br>
                                                        ";
                                                    }

                                                    # Assignment Template

                                                    $subject_code_assignment_template_id = isset($row['subject_code_assignment_template_id']) ? $row['subject_code_assignment_template_id'] : NULL;
                                                    $assignment_name = isset($row['assignment_name']) ? $row['assignment_name'] : '';
                                                    $description = isset($row['description']) ? $row['description'] : '';
                                                    $max_score = isset($row['max_score']) ? $row['max_score'] : '';
                                                    $type = isset($row['type']) ? ucwords($row['type']) : '';


                                                    $sc_subject_period_code_topic_template_id = NULL;
                                                    $sc_subject_code_assignment_template_id = NULL;

                                                    $queryCodeAssignment = $con->prepare("SELECT 
                                                        t1.subject_code_assignment_template_id AS sc_subject_code_assignment_template_id
                                                        
                                                        FROM subject_code_assignment as t1

                                                        INNER JOIN subject_period_code_topic AS t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                        AND t2.school_year_id=:school_year_id
                                                        
                                                        WHERE t1.subject_code_assignment_template_id=:subject_code_assignment_template_id
                                                        LIMIT 1
                                                        ");

                                                    $queryCodeAssignment->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
                                                    $queryCodeAssignment->bindValue(":school_year_id", $current_school_year_id);
                                                    $queryCodeAssignment->execute();

                                                    if($queryCodeAssignment->rowCount() > 0){

                                                        $sc_subject_code_assignment_template_id = $queryCodeAssignment->fetchColumn();
                                                    }

                                                    $assignment_status = "";
                                                    $assignment_btn = "";


                                                    if($sc_subject_code_assignment_template_id == $subject_code_assignment_template_id){
                                                        
                                                        $assignment_status = "
                                                            <i style='color: green;' class='fas fa-check'></i>
                                                        ";
                                                        

                                                    }else{

                                                        $assignment_status = "
                                                            <i style='color: orange;' class='fas fa-times'></i>
                                                        ";
                                                        $assignment_btn = "
                                                            <button onclick='window.location.href = \"create_assignment_template.php?id=$subject_code_assignment_template_id&ct_id=$subject_period_code_topic_id&t_id=$subject_period_code_topic_template_id\"' class='btn btn-primary'>
                                                                <i class='fas fa-plus'></i>
                                                            </button>
                                                        ";
                                                    }

                                                    $output_section = "";
                                                    $output_btn = "";
                                                    $given_status = "";

                                                    if($subject_code_handout_template_id == NULL 
                                                        && $subject_code_assignment_template_id !== NULL){
                                                            
                                                        $given_status = $assignment_status;
                                                        $output_btn = $assignment_btn;

                                                        $output_section = "
                                                            $assignment_name
                                                        ";
                                                    }
                                                    if($subject_code_handout_template_id !== NULL 
                                                        && $subject_code_assignment_template_id === NULL){
                                                        
                                                        $given_status = $handout_status;
                                                        $output_btn = $handout_btn;
                                                        
                                                        
                                                        if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                            $output_section = "
                                                                <a style='color: inherit;' title='View File' href='$filePath' target='__blank' rel='noopener noreferrer'>
                                                                    $handout_name
                                                                </a>
                                                                <br>
                                                            ";
                                                        }
                                                        
                                                    }

                                                    echo "
                                                        <tr>
                                                            <td>$output_section</td>
                                                            <td>$given_status</td>
                                                            <td>$output_btn</td>
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
                <br>
                <br>
                <main>
                    <div class="floating" id="shs-sy">
                        <header>
                            <div class="title">
                                <h5>Default Assignment for Topics: <span><?php echo $topic_name; ?></span></h5>
                            </div>
                        </header>
                        <main>

                        <?php
                            if(count($codeAssignmentTemplateList) > 0){

                                ?>
                                    <table id="assignment_template_table" class="a" style="margin: 0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Assignment Name</th>
                                                <th>Description</th>
                                                <th>Max Score</th>
                                                <th>Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                             
                                                $i = 0;

                                                foreach ($codeAssignmentTemplateList as $key => $row) {
                                                    # code...
                                                    $i++;

                                                    $subject_code_assignment_template_id = isset($row['subject_code_assignment_template_id']) ? $row['subject_code_assignment_template_id'] : NULL;
                                                    $assignment_name = isset($row['assignment_name']) ? $row['assignment_name'] : '';
                                                    $description = isset($row['description']) ? $row['description'] : '';
                                                    $max_score = isset($row['max_score']) ? $row['max_score'] : '';
                                                    $type = isset($row['type']) ? ucwords($row['type']) : '';


                                                    $sc_subject_period_code_topic_template_id = NULL;
                                                    $sc_subject_code_assignment_template_id = NULL;

                                                    $query = $con->prepare("SELECT 
                                                        t1.subject_code_assignment_template_id AS sc_subject_code_assignment_template_id
                                                        
                                                        FROM subject_code_assignment as t1

                                                        INNER JOIN subject_period_code_topic AS t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                        AND t2.school_year_id=:school_year_id
                                                        
                                                        WHERE t1.subject_code_assignment_template_id=:subject_code_assignment_template_id
                                                        LIMIT 1
                                                        ");

                                                    $query->bindValue(":subject_code_assignment_template_id", $subject_code_assignment_template_id);
                                                    $query->bindValue(":school_year_id", $current_school_year_id);
                                                    $query->execute();

                                                    if($query->rowCount() > 0){

                                                        $sc_subject_code_assignment_template_id = $query->fetchColumn();
                                                    }

                                                    $status = "";
                                                    $btn = "";


                                                    if($sc_subject_code_assignment_template_id == $subject_code_assignment_template_id){
                                                        
                                                        $status = "
                                                            <i style='color: green;' class='fas fa-check'></i>
                                                        ";
                                                        

                                                    }else{

                                                        $status = "
                                                            <i style='color: orange;' class='fas fa-times'></i>
                                                        ";
                                                        $btn = "
                                                            <button onclick='window.location.href = \"create_assignment_template.php?id=$subject_code_assignment_template_id&ct_id=$subject_period_code_topic_id&t_id=$subject_period_code_topic_template_id\"' class='btn btn-primary'>
                                                                <i class='fas fa-plus'></i>
                                                            </button>
                                                        ";
                                                    }


                                                    echo "
                                                        <tr>
                                                            <td>$i</td>
                                                            <td>$assignment_name</td>
                                                            <td>$description</td>
                                                            <td>$max_score</td>
                                                            <td>$type</td>
                                                            <td>$status</td>
                                                            <td>
                                                                $btn
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

                <br>
                <main>
                    <div class="floating" id="shs-sy">

                        <header>
                            <div class="title">
                                <h5>Default Handout for Topic: <span><?php echo $topic_name; ?></span></h5>
                            </div>

                            <div class="action">
                                <button class="default clean">Add Handout</button>
                            </div>
                        </header>
                        <main>

                        <?php
                            if(count($codeHandoutTemplateList) > 0){

                                ?>
                                    <table id="handoutt_template_table" class="a" style="margin: 0">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Handout Name</th>
                                                <th >File</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                             
                                                $i = 0;

                                                foreach ($codeHandoutTemplateList as $key => $row) {

                                                    # code...

                                                    $i++;

                                                    $subject_code_handout_template_id  = $row['subject_code_handout_template_id'];
                                                    // $subject_code_handout_template_id  = $row['subject_code_handout_template_id'];
                                                    $subject_period_code_topic_templatee_id  = $row['subject_period_code_topic_template_id'];

                                                    
                                                    // echo $subject_code_handout_template_id;
                                                    // echo "<br>";
                                                    

                                                    $handout_name = $row['handout_name'];
                                                    $file = $row['file'];

                                                    $handout_subject_code_handout_template_id = NULL;

                                                    $query = $con->prepare("SELECT 
                                                        t1.subject_code_handout_template_id AS handout_subject_code_handout_template_id

                                                        
                                                        FROM subject_code_handout as t1

                                                        INNER JOIN subject_period_code_topic AS t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                        AND t2.school_year_id=:school_year_id
                                                        
                                                        WHERE t1.subject_code_handout_template_id=:subject_code_handout_template_id
                                                        LIMIT 1
                                                        ");

                                                    $query->bindValue(":subject_code_handout_template_id", $subject_code_handout_template_id);
                                                    $query->bindValue(":school_year_id", $current_school_year_id);
                                                    $query->execute();

                                                    if($query->rowCount() > 0){

                                                        $handout_subject_code_handout_template_id = $query->fetchColumn();

                                                        // echo $handout_subject_code_handout_template_id;
                                                        // echo "<br>";
                                                    }

                                                    $status = "";
                                                    $btn = "";


                                                    // if(false){
                                                    if($subject_code_handout_template_id == $handout_subject_code_handout_template_id){
                                                        
                                                        $status = "
                                                            <i style='color: green;' class='fas fa-check'></i>
                                                        ";
                                                        
                                                    }
                                                    else if($subject_code_handout_template_id != $handout_subject_code_handout_template_id
                                                        && $subject_code_handout_template_id != NULL){

                                                        $attachModule = "attachModule($subject_code_handout_template_id, \"$handout_name\", $subject_period_code_topic_id)";

                                                        $status = "
                                                            <i style='color: orange;' class='fas fa-times'></i>
                                                        ";
                                                        $btn = "
                                                            <button onclick='$attachModule' class='btn btn-info'>
                                                                <i class='fas fa-plus'></i>
                                                            </button>
                                                        ";
                                                    }


                                                    $filename = basename($file);

                                                    $extension = pathinfo($file, PATHINFO_EXTENSION);

                                                    $parts = explode('_', $file);

                                                    $original_file_name = end($parts);

                                                    $file_output = "";

                                                    $filePath = "../../$file";

                                                    if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                        $file_output = "
                                                            <a title='View File' href='$filePath' target='__blank' rel='noopener noreferrer'>
                                                                $original_file_name
                                                            </a>
                                                            <br>
                                                        ";
                                                    }

                                                    echo "
                                                        <tr>
                                                            <td>$i</td>
                                                            <td>$handout_name</td>
                                                            <td style='max-width: 100px; 
                                                                overflow: hidden; text-overflow: ellipsis;'>
                                                                $file_output</td>
                                                            <td>$status</td>
                                                            <td>
                                                                $btn
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



<script>

    var dropBtns = document.querySelectorAll(".icon");

    dropBtns.forEach(btn => {
        btn.addEventListener("click", (e) => {
            const dropMenu = e.currentTarget.nextElementSibling;
            if (dropMenu.classList.contains("show")) {
                dropMenu.classList.toggle("show");
            } else {
                document.querySelectorAll(".dropdown-menu").forEach(item => item.classList.remove("show"));
                dropMenu.classList.add("show");
            }
        });
    });

    function attachModule(subject_code_handout_template_id,
        handout_name, subject_period_code_topic_id){

        var subject_code_handout_template_id = parseInt(subject_code_handout_template_id);

        Swal.fire({
                icon: 'question',
                title: `Do you want to give Module ${handout_name}?`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                    url: "../../ajax/class/attachModule.php",
                        type: 'POST',
                        data: {
                            subject_code_handout_template_id,subject_period_code_topic_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Attached`,
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

                                $('#handoutt_template_table').load(
                                    location.href + ' #handoutt_template_table'
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