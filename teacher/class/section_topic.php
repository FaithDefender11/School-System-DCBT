<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Announcement.php');

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

    // var_dump($teacher_id);

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

    $subjectCodeHandout = new SubjectCodeHandout($con);
    $subjectCodeAssignment = new SubjectCodeAssignment($con);

    $nonTemplateHandout = $subjectCodeHandout->GetNonTemplateHandoutBasedOnSubjectTopic($subject_period_code_topic_id);
    
    $nonTemplateAssignment = $subjectCodeAssignment->GetNonTemplateAssignmentBasedOnSubjectTopic(
        $subject_period_code_topic_id);


    $school_year_id = $subjectPeriodCodeTopic->GetSchoolYearId();

    // print_r($nonTemplateHandout);

    $back_url = "index.php?c=$topic_subject_code&sy_id=$school_year_id";
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
                            <h3><?php echo $topic_name; ?> (<?php echo $subject_period_name?>)</h3>
                        </div>
                        <div class="action">
                            <a href="task_summary.php?ct_id=<?php echo $subject_period_code_topic_id; ?>">
                                <button 
                                class="information"
                                >
                                Task Summary
                                </button>
                            </a>
                            
                            <button 
                                class="information"
                                onclick="window.location.href = 'handout_summary.php?ct_id=<?php echo $subject_period_code_topic_id; ?>' "
                            >
                                Handout Summary
                            </button>
                        </div>
                    </header>
                </div>

                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Module Overview</h3>
                        </div>
                        <div class="action">
                            <div class="dropdown">
                                <button class="icon">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a 
                                        href="module_create.php?id=<?php echo $subject_period_code_topic_id;?>"
                                        class="dropdown-item" style="color: green"
                                    >
                                        <i class="bi bi-file-earmark-x"></i>
                                        Add Handout
                                    </a>
                                    <a 
                                        href="create_assignment.php?id=<?php echo $subject_period_code_topic_id;?>" 
                                        class="dropdown-item" 
                                        style="color: blue"
                                    >
                                        <i class="bi bi-file-earmark-x"></i>
                                        Add Assignment
                                    </a>
                                </div>
                            </div>
                        </div>
                    </header>
                    <main style="overflow-x: auto">
                        <?php
                            // $mergeHandoutWithAssignmentList = array_merge($codeHandoutTemplateList,
                            //     $codeAssignmentTemplateList);

                            $mergeHandoutWithAssignmentList = array_merge(
                                $codeHandoutTemplateList,
                                $nonTemplateHandout,
                                $codeAssignmentTemplateList,
                                $nonTemplateAssignment);


                            // print_r($mergeHandoutWithAssignmentList);

                            if(count($mergeHandoutWithAssignmentList) > 0){
                                ?>
                                <table class="a" id="handoutt_template_table" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>Section</th>
                                            <th>Template</th>
                                            <th>Given</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i = 0;

                                            foreach ($mergeHandoutWithAssignmentList as $key => $row) {

                                                $i++;

                                                # Handout Template
                                                $subject_code_handout_template_id = isset($row['subject_code_handout_template_id']) ? $row['subject_code_handout_template_id'] : NULL;
                                                $subject_period_code_topic_templatee_id = isset($row['subject_period_code_topic_template_id']) ? $row['subject_period_code_topic_template_id'] : NULL;
                                                $handout_name = isset($row['handout_name']) ? $row['handout_name'] : '';
                                                
                                                # Assignment Template
                                                $subject_code_assignment_template_id = isset($row['subject_code_assignment_template_id']) ? $row['subject_code_assignment_template_id'] : NULL;
                                                
                                                $template_subject_code_assignment_id = isset($row['template_subject_code_assignment_id']) ? $row['template_subject_code_assignment_id'] : NULL;
                                                
                                                $assignment_name = isset($row['assignment_name']) ? $row['assignment_name'] : '';
                                                $description = isset($row['description']) ? $row['description'] : '';
                                                $max_score = isset($row['max_score']) ? $row['max_score'] : '';
                                                $type = isset($row['type']) ? ucwords($row['type']) : '';

                                                // var_dump($template_subject_code_assignment_id);
                                                // 

                                                # Non Template Handout
                                                $nonTemplateHandoutName = isset($row['nonTemplateHandoutName']) ? $row['nonTemplateHandoutName'] : '';
                                                $nonTemplateFile = isset($row['nonTemplateFile']) ? $row['nonTemplateFile'] : '';
                                                $nonTemplateSubjectCodeHandoutId = isset($row['nonTemplateSubjectCodeHandoutId']) ? $row['nonTemplateSubjectCodeHandoutId'] : NULL;
                                                $nonTemplateSubjectHandoutIsGiven = isset($row['nonTemplateSubjectHandoutIsGiven']) ? $row['nonTemplateSubjectHandoutIsGiven'] : NULL;

                                                # Non Template Assignment
                                                $nonTemplateSubjectCodeAssignmentId = isset($row['nonTemplateSubjectCodeAssignmentId']) ? $row['nonTemplateSubjectCodeAssignmentId'] : NULL;
                                                $nonTemplateSubjectAssignmentName = isset($row['nonTemplateSubjectAssignmentName']) ? $row['nonTemplateSubjectAssignmentName'] : '';
                                                $nonTemplateSubjectAssignmentIsGiven = isset($row['nonTemplateSubjectAssignmentIsGiven']) ? $row['nonTemplateSubjectAssignmentIsGiven'] : NULL;

                                                
                                                // var_dump($nonTemplateSubjectCodeHandoutId);
                                                // echo "<br>";

                                                $file = isset($row['file']) ? $row['file'] : '';

                                                $handout_subject_code_handout_template_id = NULL;
                                                $handout_template_is_given = NULL;
                                                $handout_template_subject_code_handout_id = NULL;

                                                $queryHandout = $con->prepare("SELECT 
                                                    t1.subject_code_handout_template_id AS handout_subject_code_handout_template_id,
                                                    t1.is_given AS handout_template_is_given,
                                                    t1.subject_code_handout_id AS handout_template_subject_code_handout_id
                                                    
                                                    FROM subject_code_handout as t1

                                                    INNER JOIN subject_period_code_topic AS t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
                                                    AND t2.school_year_id=:school_year_id
                                                    
                                                    WHERE t1.subject_code_handout_template_id=:subject_code_handout_template_id
                                                    LIMIT 1
                                                    ");

                                                $queryHandout->bindValue(":subject_code_handout_template_id", $subject_code_handout_template_id);
                                                $queryHandout->bindValue(":school_year_id", $current_school_year_id);
                                                $queryHandout->execute();

                                                if($queryHandout->rowCount() > 0){

                                                    $row_handout = $queryHandout->fetch(PDO::FETCH_ASSOC);

                                                    $handout_subject_code_handout_template_id = $row_handout['handout_subject_code_handout_template_id'];
                                                    $handout_template_is_given = $row_handout['handout_template_is_given'];
                                                    $handout_template_subject_code_handout_id = $row_handout['handout_template_subject_code_handout_id'];
                                                }

                                                $handout_status = "";
                                                $handout_btn = "";

                                                // if(false){
                                                if($subject_code_handout_template_id != $handout_subject_code_handout_template_id){
                                                    
                                                    // $giveHandoutTemplate = "giveHandoutTemplate($subject_code_handout_template_id, \"$handout_name\", $subject_period_code_topic_id)";
                                                    // $handout_btn = "
                                                    //     <button onclick='$giveHandoutTemplate' class='btn btn-sm btn-info'>
                                                    //         <i class='fas fa-plus'></i>
                                                    //     </button>
                                                    // ";
                                                }


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
                                                        <button onclick='window.location.href = \"create_assignment_template.php?id=$subject_code_assignment_template_id&ct_id=$subject_period_code_topic_id&t_id=$subject_period_code_topic_template_id\"' class='btn btn-sm btn-primary'>
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


                                                    $edit_given_template_assignment_url = "edit.php?id=$template_subject_code_assignment_id";
                                                    
                                                    $output_section = "
                                                        <a style='color: inherit;' href='$edit_given_template_assignment_url'>
                                                            $assignment_name
                                                        </a>
                                                    ";
                                                }

                                                # HANDOUT TEMPLATE LOGIC HERE
                                                if($subject_code_handout_template_id !== NULL ){

                                                    $filename = basename($file);
                                                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                                                    $parts = explode('_', $file);
                                                    $original_file_name = end($parts);

                                                    $filePath = "../../$file";
                                                    
                                                    // $given_status = $handout_status;

                                                    // var_dump($handout_template_is_given);

                                                    if($handout_template_is_given == 1 && $handout_template_subject_code_handout_id !== NULL){

                                                        $ungiveHandoutTemplate = "ungiveHandoutTemplate($handout_template_subject_code_handout_id, $subject_period_code_topic_id, $teacher_id)";
                                                        $given_status = "
                                                            <i onclick='$ungiveHandoutTemplate' style='cursor:pointer; color: green;' class='fas fa-check'></i>
                                                        ";
                                                    }

                                                    if($handout_template_is_given == 0){
                                                        $giveHandoutTemplate = "giveHandoutTemplate($subject_code_handout_template_id, \"$handout_name\", $subject_period_code_topic_id)";

                                                        $given_status = "
                                                            <i onclick='$giveHandoutTemplate' style='cursor:pointer; color: orange;' class='fas fa-times'></i>
                                                        ";
                                                    }

                                                    $output_btn = $handout_btn;
                                                    
                                                    $template_status = "
                                                        <i style='color: green;' class='fas fa-check'></i>
                                                    ";
                                                    
                                                    if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                        $output_section = "
                                                            <a style='color: inherit;' title='View File' href='$filePath' target='__blank' rel='noopener noreferrer'>
                                                                $handout_name
                                                            </a>
                                                            <br>
                                                        ";
                                                    }
                                                    
                                                }

                                                # NON TEMPLATE HANDOUT
                                                if($nonTemplateSubjectCodeHandoutId !== NULL
                                                    ){
                                                        
                                                    $filename = basename($nonTemplateFile);
                                                    $extension = pathinfo($nonTemplateFile, PATHINFO_EXTENSION);
                                                    $parts = explode('_', $nonTemplateFile);
                                                    $original_file_name = end($parts);

                                                    $nonTemplateFilePath = "../../$nonTemplateFile";

                                                    $unGiveMadeHandout = "unGiveMadeHandout($nonTemplateSubjectCodeHandoutId, $subject_period_code_topic_id, $teacher_id)";

                                                    // if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                    //     $output_section = "
                                                    //         <a style='color: inherit;' title='View File' href='$nonTemplateFilePath' target='__blank' rel='noopener noreferrer'>
                                                    //             $nonTemplateHandoutName
                                                    //         </a>
                                                    //         <br>
                                                    //     ";
                                                    // }

                                                    $edit_handoutMade_url = "module_edit.php?id=$nonTemplateSubjectCodeHandoutId";

                                                    $output_section = "
                                                            <a style='color: inherit;' title='Edit File' href='$edit_handoutMade_url'>
                                                                $nonTemplateHandoutName
                                                            </a>
                                                        <br>
                                                    ";

                                                    if($nonTemplateSubjectHandoutIsGiven == 1){
                                                        // $given_status = "
                                                        //     <i style='color: green;' class='fas fa-check'></i>
                                                        // ";
                                                        $given_status = "
                                                            <i onclick='$unGiveMadeHandout' style='cursor:pointer; color: green;' class='fas fa-check'></i>
                                                        ";
                                                    }

                                                    if($nonTemplateSubjectHandoutIsGiven == 0){

                                                        $removeMadeHandout = "removeMadeHandout($nonTemplateSubjectCodeHandoutId, $subject_period_code_topic_id, $teacher_id)";

                                                        $output_btn = "
                                                            <button onclick='$removeMadeHandout' class='btn btn-danger btn-sm'>
                                                                <i class='fas fa-trash'></i>
                                                            </button>
                                                        ";

                                                        $giveMadeHandout = "giveMadeHandout($nonTemplateSubjectCodeHandoutId, $subject_period_code_topic_id, $teacher_id)";

                                                        $given_status = "
                                                            <i  onclick='$giveMadeHandout' style='cursor:pointer; color: orange;' class='fas fa-times'></i>
                                                        ";
                                                    }
                                                    $template_status = "
                                                        <i style='color: orange;' class='fas fa-times'></i>
                                                    ";
                                                }


                                                # NON TEMPLATE Assignment
                                                if($nonTemplateSubjectCodeAssignmentId !== NULL){
                                                        
                                                    $filename = basename($nonTemplateFile);
                                                    $extension = pathinfo($nonTemplateFile, PATHINFO_EXTENSION);
                                                    $parts = explode('_', $nonTemplateFile);
                                                    $original_file_name = end($parts);

                                                    $nonTemplateFilePath = "../../$nonTemplateFile";

                                                    $given_status = $nonTemplateSubjectAssignmentIsGiven;

                                                    # GIVEN STATUS CHECK
                                                    if($nonTemplateSubjectAssignmentIsGiven == 1){

                                                        $unGiveMadeAssignment = "unGiveMadeAssignment($nonTemplateSubjectCodeAssignmentId,
                                                            $subject_period_code_topic_id, $teacher_id)";

                                                        $given_status = "
                                                            <i onclick='$unGiveMadeAssignment' style='cursor: pointer;color: yellow;' class='fas fa-check'></i>
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
                                                        
                                                    }

                                                    // $output_btn = $handout_btn;
                                                    $template_status = "
                                                        <i style='color: yellow;' class='fas fa-times'></i>
                                                    ";

                                                    $assignment_edit_url = "edit.php?id=$nonTemplateSubjectCodeAssignmentId";
                                                    
                                                    $output_section = "
                                                        <a style='color: inherit' href='$assignment_edit_url'>
                                                            $nonTemplateSubjectAssignmentName
                                                        </a>
                                                    ";
                                                    
                                                }

                                                // <td>$output_btn</td>

                                                echo "
                                                    <tr>
                                                        <td>$output_section</td>
                                                        <td>$template_status</td>
                                                        <td>$given_status</td>
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


        // Template Handout
        function giveHandoutTemplate(subject_code_handout_template_id,
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
                        url: "../../ajax/class/giveHandoutTemplate.php",
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

        function ungiveHandoutTemplate(subject_code_handout_id,
            subject_period_code_topic_id, teacher_id){

            var subject_code_handout_id = parseInt(subject_code_handout_id);
            var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
            var teacher_id = parseInt(teacher_id);

            Swal.fire({
                    icon: 'question',
                    title: `Do you want to un-give the selected module?`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                        url: "../../ajax/class/ungiveHandoutTemplate.php",
                            type: 'POST',
                            data: {
                                subject_code_handout_id,
                                subject_period_code_topic_id,
                                teacher_id
                            },
                            success: function(response) {

                                response = response.trim();

                                console.log(response);

                                if(response == "success"){
                                    Swal.fire({
                                    icon: 'success',
                                    title: `Successfully Un-gived Handout`,
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

        // MADE HANDOUT
        function unGiveMadeHandout(subject_code_handout_id,
            subject_period_code_topic_id, teacher_id){

            var subject_code_handout_id = parseInt(subject_code_handout_id);
            var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
            var teacher_id = parseInt(teacher_id);

            Swal.fire({
                    icon: 'question',
                    title: `Do you want to un-give the selected module?`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                        url: "../../ajax/class/unGiveMadeHandout.php",
                            type: 'POST',
                            data: {
                                subject_code_handout_id,
                                subject_period_code_topic_id,
                                teacher_id
                            },
                            success: function(response) {

                                response = response.trim();

                                console.log(response);

                                if(response == "success"){
                                    Swal.fire({
                                    icon: 'success',
                                    title: `Successfully Un-give`,
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

        function giveMadeHandout(subject_code_handout_id,
            subject_period_code_topic_id, teacher_id){

                
            var subject_code_handout_id = parseInt(subject_code_handout_id);
            var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
            var teacher_id = parseInt(teacher_id);

            Swal.fire({
                    icon: 'question',
                    title: `Do you want to give the selected module?`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                        url: "../../ajax/class/giveHandoutMade.php",
                            type: 'POST',
                            data: {
                                subject_code_handout_id,
                                subject_period_code_topic_id,
                                teacher_id
                            },
                            success: function(response) {

                                response = response.trim();

                                console.log(response);

                                if(response == "success"){
                                    Swal.fire({
                                    icon: 'success',
                                    title: `Handout has been successfully given`,
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

        function removeMadeHandout(subject_code_handout_id,
            subject_period_code_topic_id, teacher_id){

            var subject_code_handout_id = parseInt(subject_code_handout_id);
            var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
            var teacher_id = parseInt(teacher_id);

            Swal.fire({
                    icon: 'question',
                    title: `Do you want to remove the selected module?`,
                    text: `Important! This action cannot be undone`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {

                        $.ajax({
                        url: "../../ajax/class/removeMadeHandout.php",
                            type: 'POST',
                            data: {
                                subject_code_handout_id,
                                subject_period_code_topic_id,
                                teacher_id
                            },
                            success: function(response) {

                                response = response.trim();

                                console.log(response);

                                if(response == "success"){
                                    Swal.fire({
                                    icon: 'success',
                                    title: `Handout has been removed`,
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

        // MADE Assignment
        function giveMadeAssignment(subject_code_assignment_id,
            subject_period_code_topic_id, teacher_id){

                
            var subject_code_assignment_id = parseInt(subject_code_assignment_id);
            var subject_period_code_topic_id = parseInt(subject_period_code_topic_id);
            var teacher_id = parseInt(teacher_id);

            Swal.fire({
                    icon: 'question',
                    title: `Do you want to give the selected assignment?`,
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
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
    </body>
</html>