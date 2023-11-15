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
    include_once('../../includes/classes/SubjectCodeHandout.php');

    if(
        isset($_GET['id'])
        && isset($_GET['sct_id'])
        ){

        $subject_period_code_topic_template_id = $_GET['id'];
        $subject_period_code_topic_id = $_GET['sct_id'];


        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $subjectCodeAssignmentTemplate = new SubjectCodeAssignmentTemplate($con);
        $subjectCodeHandout = new SubjectCodeHandout($con);

        $teacher_id = $_SESSION['teacherLoggedInId'];

        $codeAssignmentTemplateList = $subjectCodeAssignmentTemplate->GetCodeAssignmentTopicTemplateList(
            $subject_period_code_topic_template_id);

        $codeHandoutTemplateList = $subjectCodeAssignmentTemplate->GetCodeHandoutTopicTemplateList(
            $subject_period_code_topic_template_id);

        $nonTemplateHandout = $subjectCodeHandout->GetNonTemplateHandoutBasedOnSubjectTopic($subject_period_code_topic_id);

        // var_dump($codeHandoutTemplateList);

        $topicHandoutsMerge = array_merge($codeHandoutTemplateList, $nonTemplateHandout);

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
                            <h3><?= $topic; ?> Handouts</h3>
                        </div>
                        <div class="action">
                            <button 
                                class="clean"
                                onclick="window.location.href='handout_create.php?id=<?= $subject_period_code_topic_id;?>'"
                            >
                                + Handout
                            </button>
                        </div>
                    </header>
                    <main style='overflow-x: auto'>
                        <?php if(count($topicHandoutsMerge) > 0):?>
                            <table class="a" id="handouts_table">
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
                                            // $query = $con->prepare("SELECT * FROM subject_code_handout

                                            //     WHERE subject_period_code_topic_id =:subject_period_code_topic_id
                                            // ");

                                            // $query->bindParam(":subject_period_code_topic_id", $subject_period_code_topic_id);
                                            // $query->execute();


                                            foreach ($topicHandoutsMerge as $key => $row) {

                                                # Handout Template
                                                $subject_code_handout_template_id = isset($row['subject_code_handout_template_id']) ? $row['subject_code_handout_template_id'] : NULL;
                                                $subject_period_code_topic_templatee_id = isset($row['subject_period_code_topic_template_id']) ? $row['subject_period_code_topic_template_id'] : NULL;
                                                $handout_name = isset($row['handout_name']) ? $row['handout_name'] : '';

                                                # NON Template Handout.
                                                $nonTemplateHandoutName = isset($row['nonTemplateHandoutName']) ? $row['nonTemplateHandoutName'] : '';
                                                $nonTemplateFile = isset($row['nonTemplateFile']) ? $row['nonTemplateFile'] : '';
                                                $nonTemplateSubjectCodeHandoutId = isset($row['nonTemplateSubjectCodeHandoutId']) ? $row['nonTemplateSubjectCodeHandoutId'] : NULL;
                                                $nonTemplateSubjectHandoutIsGiven = isset($row['nonTemplateSubjectHandoutIsGiven']) ? $row['nonTemplateSubjectHandoutIsGiven'] : NULL;


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

                                                // $handout_name = $row['nonTemplateHandoutName'];

    
                                                // $handout_file = $nonTemplateFile;

                                                // $removeDepartmentBtn = "removeDepartmentBtn($department_id)";

                                                $output_section = "";

                                                $output_btn = "";
                                                
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

                                                    // $edit_handoutMade_url = "module_edit.php?id=$nonTemplateSubjectCodeHandoutId";
                                                    $edit_handoutMade_url = "handout_edit.php?id=$nonTemplateSubjectCodeHandoutId&h_tid=$subject_period_code_topic_template_id";

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


                                                echo "
                                                    <tr>
                                                        <td>$output_section</td>
                                                        <td>$template_status</td>
                                                        <td>$given_status</td>
                                                        <td>$output_btn</td>
                                                        
                                                    </tr>
                                                ";

                                                // <td>
                                                //     <a href='handout_edit.php?id='>
                                                //         <button class='information'>
                                                //             <i class='fas fa-pen'></i>
                                                //         </button>
                                                //     </a>
                                                //     <button onclick='' class='danger'>
                                                //             <i class='fas fa-trash'></i>
                                                //     </button>
                                                // </td>

                                            }

                                            // if($query->rowCount() > 0){

                                                // while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                //     $handout_name = $row['handout_name'];

                                                //     $subject_code_handout_id = $row['subject_code_handout_id'];

                                                //     $subject_code_handout_template_id = $row['subject_code_handout_template_id'];

                                                //     $is_given = $row['is_given'];
                                                //     $handout_file = $row['file'];

                                                //     // $removeDepartmentBtn = "removeDepartmentBtn($department_id)";

                                                //     $extension = pathinfo($handout_file, PATHINFO_EXTENSION);
                                                
                                                //     $pos = strpos($handout_file, "img_");

                                                //     $original_file_name = "";

                                                //     // Check if "img_" was found
                                                //     if ($pos !== false) {
                                                //         $original_file_name = substr($handout_file, $pos + strlen("img_"));
                                                //     }

                                                //     $fileOutput = "";

                                                //     if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                        
                                                //         $fileOutput = "
                                                //             <a title='View File' href='../../$handout_file' target='__blank' rel='noopener noreferrer'>
                                                //                 $original_file_name
                                                //             </a>

                                                //         ";
                                                //     }

                                                //     $removeMadeHandout = "removeMadeHandout($subject_code_handout_id, $subject_period_code_topic_id, $teacherLoggedInId)";
    

                                                //     if($is_given == 1){

                                                //         $unGiveMadeHandout = "unGiveMadeHandout($subject_code_handout_id, $subject_period_code_topic_id, $teacherLoggedInId)";

                                                //         $is_given_status = "
                                                //             <i onclick='$unGiveMadeHandout' style='cursor:pointer; color: orange;' style='color: green;' class='fas fa-check'></i>
                                                //         ";
                                                //     }else{

                                                //         $giveMadeHandout = "giveMadeHandout($subject_code_handout_id, $subject_period_code_topic_id, $teacherLoggedInId)";

                                                //         $is_given_status = "
                                                //             <i onclick='$giveMadeHandout' style='cursor:pointer; color: orangered;' class='fas fa-times'></i>
                                                //         ";
                                                //     }

                                                //     echo "
                                                //         <tr>
                                                //             <td>$handout_name</td>
                                                //             <td>$fileOutput</td>
                                                //             <td></td>
                                                //             <td>$is_given_status</td>
                                                //             <td>
                                                //                 <a href='handout_edit.php?id=$subject_code_handout_id'>
                                                //                     <button class='information'>
                                                //                         <i class='fas fa-pen'></i>
                                                //                     </button>
                                                //                 </a>
                                                //                 <button onclick='$removeMadeHandout' class='danger'>
                                                //                         <i class='fas fa-trash'></i>
                                                //                 </button>
                                                //             </td>
                                                //         </tr>
                                                //     ";

                                                // }
                                            // }
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

        //  HANDOUT TEMPLATE

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

                                    $('#handouts_table').load(
                                        location.href + ' #handouts_table'
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

                                    $('#handouts_table').load(
                                        location.href + ' #handouts_table'
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

        //  HOME MADE HANDOUT
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

                                    $('#handouts_table').load(
                                        location.href + ' #handouts_table'
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
                    title: `Do you want to give the selected handout module?`,
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

                                    $('#handouts_table').load(
                                        location.href + ' #handouts_table'
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

                                    $('#handouts_table').load(
                                        location.href + ' #handouts_table'
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