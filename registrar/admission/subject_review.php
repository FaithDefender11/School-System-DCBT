

<?php 

    if($student_status_st == "" && $student_new_enrollee == 0){
        // Provide a route where.
        // Select wether irregular or regular based on the grade record history.
        // Update its student_statusv2 in the student table.
        // redirect to this page.
    }


 
    // echo $registrarUserId;


    $selected_course_id = $_GET['selected_course_id'];

    $section = new Section($con, $selected_course_id);
    $studentSubject = new StudentSubject($con, $selected_course_id);

    $section_subject_review_section_name = $section->GetSectionName();

    $promptSectionId = $section->CheckIdExists($selected_course_id);

    $totalSubjectList = 0;

    //  if($student_status_st == "Irregular"
    //                         || ($student_status_st == "" 
    //                         && $student_enrollment_is_new == 1 && $student_enrollment_is_transferee == 1)){

    $hasCredited = $studentSubject->CheckHasCreditedSubjectWithinSemester($student_id, $current_school_year_id);

    // var_dump($hasCredited);

    $totalUnits = NULL;

    $enrollment_payment = NULL;

    # REGULAR SHS
    if(
        // $student_enrollment_student_status === "Regular" && 
        $student_enrollment_is_tertiary === 0){

        $SHS_VOUCHER = 17500;

        $enrollment_payment =  $SHS_VOUCHER / 2;
    }

    # REGULAR TERTIARY
    if(
        // $student_enrollment_student_status === "Regular"  &&
        
        $student_enrollment_is_tertiary === 1){

        $SHS_VOUCHER = 20000;
        $enrollment_payment =  $SHS_VOUCHER / 2;
    }

    if($student_enrollment_student_status === "Irregular" 
        && $student_enrollment_is_tertiary === 1){
         
    }

    ?>
        <!-- Student Table Subject Review-->
        <div class="content">
            
            <div class="content-header">


                <?php echo Helper::RevealStudentTypePending($type,
                    $student_enrollment_student_status); ?>
                <header>

                    <div class="title">
                        <h1>Enrollment form</h1>
                    </div>
                    
                    <div class="action">
                        <div class="dropdown">

                            <button class="icon">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>

                            <div class="dropdown-menu">
                                
                                <?php 
                                
                                    $retakeFunc = "";
                                    if($student_enrollment_retake_status == 1){
                                        $retakeFunc = "markAsEnrollmentRetake($student_enrollment_id, $student_id, $current_school_year_id, 'Unretake')";
                                    }else if($student_enrollment_retake_status == 0){
                                        $retakeFunc = "markAsEnrollmentRetake($student_enrollment_id, $student_id, $current_school_year_id, 'Retake')";
                                    }
                                ?>

                                <!-- <a  onclick="javascript: <?php echo $retakeFunc; ?>"
                                        href="#" class="dropdown-item" style="color: orange">
                                    <i class="bi bi-file-earmark-x"></i>
                                    Mark form as Retake
                                </a> -->

                                <?php if ($student_enrollment_student_status === "Irregular"): ?>
                                    <a onclick="enrollmentStudentStatusChanging(<?php echo $student_enrollment_id; ?>, <?php echo $student_id; ?>, <?php echo $current_school_year_id; ?>, 'Regular')"
                                        href="#" class="dropdown-item" style="color: green">
                                        <i class="bi bi-file-earmark-x"></i>
                                        Mark as Regular
                                    </a>
                                <?php elseif ($student_enrollment_student_status === "Regular"): ?>
                                    <a onclick="enrollmentStudentStatusChanging(<?php echo $student_enrollment_id; ?>, <?php echo $student_id; ?>, <?php echo $current_school_year_id; ?>, 'Irregular')"
                                        href="#" class="dropdown-item" style="color: blue">
                                        <i class="bi bi-file-earmark-x"></i>
                                        Mark as Irregular
                                    </a>
                                <?php endif; ?>


                                

                                

                                <!-- <a href="find_credit_subject.php?e_id=<?php echo $student_enrollment_id;?>&st_id=<?php echo $student_id?>">
                                    <button type="button" class="large default">
                                        <i style="font-size: 13px;" class='bi bi-map'></i> Credit Section</button>
                                </a> -->

                                <a href="find_credit_subject.php?e_id=<?php echo $student_enrollment_id;?>&st_id=<?php echo $student_id?>"
                                    class="dropdown-item" style="color: yellow">
                                    <i class="bi bi-file-earmark-x"></i>
                                    Credit Subject
                                </a>

                            </div>


                            <!-- <button onclick="javascript: <?php echo $retakeFunc; ?>" 
                                                        type="button" class="btn-sm <?php echo $retake;?>">Retake</button> -->

                            <!-- <button onclick="enrollmentStudentStatusChanging(<?php echo $student_enrollment_id;?>, <?php echo $student_id;?>,<?php echo $current_school_year_id;?>, '<?php echo "Irregular"?>')" 
                                type="button" class="btn-sm <?php echo $irreg;?>">Irregular</button>
                                                        
                            <button onclick="enrollmentStudentStatusChanging(<?php echo $student_enrollment_id;?>, <?php echo $student_id;?>,<?php echo $current_school_year_id;?>, '<?php echo "Regular"?>')"
                                type="button" class="btn-sm <?php echo $reg;?>">Regular</button> -->

                        </div>
                    </div>

                </header>

                <?php echo Helper::ProcessStudentCards($student_id, $student_enrollment_form_id,
                    $student_unique_id, $enrollment_creation, $student_new_enrollee,
                    $student_enrollment_is_new, $student_enrollment_is_transferee, $student_status_st); ?>

            </div>

            <main>

                <div class="progress">
                    <span class="dot active"><p>Check form details</p></span>
                    <span class="line active"></span>
                    <span class="dot active"><p>Find section</p></span>
                    <span class="line active"></span>
                    <span class="dot active"><p>Subject confirmation</p></span>
                </div>

                <?php 

                    // If Old Irregular Or New Transferee.

                    # If Irregular form, this could be seen only within semester
                    # of crediting the subject.

                    if(
                        // $student_enrollment_student_status == "Irregular" 
                        $hasCredited == true
                        ){ 

                        // echo "Hey";

                        ?>
                            <!-- CREDITED SUBJECTS -->
                            <div class="floating">
                   
                                <header>
                                    <div class="title">
                                        <h3>Credited Subjects</h3>
                                    </div>
                                    
                                    <!-- <div class="action">
                                        <a href="find_credit_subject.php?e_id=<?php echo $student_enrollment_id;?>&st_id=<?php echo $student_id?>">
                                            <button type="button" class="large default">
                                                <i style="font-size: 13px;" class='bi bi-map'></i> Credit Section</button>
                                        </a>
                                    </div> -->

                                </header>

                                <main>
                                    <table class="a">
                                        <thead>
                                            <tr class="text-center"> 
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">Code</th>
                                                <th rowspan="2">Description</th>
                                                <th rowspan="2">Unit</th>
                                                <th rowspan="2">Type</th>
                                                <th rowspan="2">Level</th>
                                                <th rowspan="2">Offered Semester</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        
                                            <?php

                                                $sql = $con->prepare("SELECT  * 
                                                
                                                    FROM student_subject as t1

                                                    INNER JOIN subject_program as t2 ON t2.subject_program_id = t1.subject_program_id
                                                    
                                                    WHERE t1.student_id=:student_id
                                                    AND t1.school_year_id=:school_year_id
                                                    AND t1.is_transferee=1
                                                    AND t1.is_final= 1

                                                    ORDER BY t2.course_level, t2.semester

                                                ");

                                                $sql->bindParam(":student_id", $student_id);
                                                $sql->bindParam(":school_year_id", $current_school_year_id);
                                                // $sql->bindParam(":course_id", $selected_course_id);

                                                $sql->execute();
                                            
                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                                        
                                                        $student_subject_id = $row['student_subject_id'];
                                                        $subject_id = $row['subject_program_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $unit = $row['unit'];
                                                        $subject_type = $row['subject_type'];
                                                        $course_level = $row['course_level'];
                                                        $semester = $row['semester'];

                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$subject_type</td>
                                                                <td>$course_level</td>
                                                                <td>$semester Semester</td>
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
                ?>

                <div class="floating">
                    
                    <header>
                        <div class="title">
                            <!-- <h3><?php echo $section_subject_review_section_name;?> <a style="all:unset; cursor: pointer" href="choosing_subject.php?e_id=<?php echo $student_enrollment_id;?>&st_id=<?php echo $student_id?>">Subjects</a></h3> -->
                            <h4><?php echo $section_subject_review_section_name;?> <a style="all:unset; cursor: pointer" href="choosing_subject.php?e_id=<?php echo $student_non_enrolled_enrollment_id;?>&st_id=<?php echo $student_id?>"></a></h4>
                        </div>

                        <?php if($student_enrollment_student_status == "Irregular"
                            || $student_status_st == "Irregular"):?>
                            <a href="choosing_subject2.php?e_id=<?php echo $student_non_enrolled_enrollment_id;?>&st_id=<?php echo $student_id?>">
                                <button type="button" class="default large">+ Add Subjects
                                </button>
                            </a>
                        <?php endif;?>
                        
                    </header>

                    <main>
                        <table class="a" id="irregular_subject_review_table">
                            <thead>
                                <tr class="text-center"> 
                                    <th style="min-width: 186px;">Course Description</th>
                                    <th >Section</th>
                                    <th >Type</th>
                                    <th style="min-width: 186px;" >Time</th>
                                    <th >Room</th>
                                    <th >Unit</th>
                                    <th >Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                    $sql = $con->prepare("SELECT 
                                    
                                        t2.*,
                                        t1.student_subject_id,
                                        t1.program_code,
                                        t1.course_id as enrolled_section_id,
                                        t1.subject_code as output_subject_code,
                                        t3.program_section
                                    
                                        FROM student_subject as t1

                                        INNER JOIN subject_program as t2 ON t2.subject_program_id=t1.subject_program_id

                                        INNER JOIN enrollment as t4 ON t4.enrollment_id = t1.enrollment_id

                                        LEFT JOIN course as t3 ON t3.course_id = t1.course_id

                                        WHERE t1.student_id=:student_id
                                        AND t1.school_year_id=:school_year_id
                                        AND t1.is_transferee = 0
                                        AND t1.is_final= 0
                                        AND t4.enrollment_id = :enrollment_id

                                    ");

                                    $sql->bindParam(":student_id", $student_id);
                                    $sql->bindParam(":school_year_id", $current_school_year_id);
                                    $sql->bindParam(":enrollment_id", $student_enrollment_id);

                                    $sql->execute();
                                
                                    if($sql->rowCount() > 0){

                                        $totalSubjectList = $sql->rowCount();


                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                            $student_subject_id = $row['student_subject_id'];
                                            $subject_id = $row['subject_program_id'];
                                            $subject_code = $row['subject_code'];
                                            $subject_title = $row['subject_title'];
                                            $program_code = $row['program_code'];
                                            $enrolled_section_id = $row['enrolled_section_id'];

                                            $unit = $row['unit'];
                                            $subject_type = $row['subject_type'];
                                            $pre_req_subject_title = $row['pre_req_subject_title'];
                                            $program_section = $row['program_section'];
                                            $output_subject_code = $row['output_subject_code'];

                                            $section_subject_code = $section->CreateSectionSubjectCode($program_section, $subject_code);

                                            $section_exec = new Section($con, $enrolled_section_id);
                                            
                                            $enrolled_section_Name = $section_exec->GetSectionName(); 
                                            $change_section_subject_url = "change_subject.php?id=$student_subject_id";
                                            
                                            $removeSubject = "removeSubject($student_subject_id)";

                                                    // <td>$pre_req_subject_title</td>

                                                
                                            $allTime  = "";
                                            $allDays  = "";

                                            $schedule = new Schedule($con);

                                            $hasSubjectCode = $schedule->GetSameSubjectCode($enrolled_section_id,
                                                $section_subject_code, $current_school_year_id);

                                            
                                            $scheduleOutput = "";
                                            $roomOutput = "";

                                            if($hasSubjectCode !== []){

                                                foreach ($hasSubjectCode as $key => $value) {

                                                    // $schedule_subject_code = $value['subject_code'];
                                                    
                                                    $schedule_day = $value['schedule_day'];
                                                    $schedule_time = $value['schedule_time'];
 
                                                    $allDays .= $schedule_day;
                                                    $allTime .= $schedule_time;

                                                    $scheduleOutput .= "○ $schedule_day - $schedule_time <br>";
                                                    // echo "<br>";

                                                    $room = $value['room_number'];

                                                    if($value['room_number'] != NULL){
                                                        $roomOutput .= "$room <br>";
                                                    }else{
                                                        $roomOutput .= "TBA <br>";
                                                    }
                                                }
                                            }else{
                                                $scheduleOutput = "TBA";
                                                $roomOutput = "TBA";
                                            }
                                         
                                            $removeSubjectLoadBtn = "";

                                            if($student_enrollment_student_status === "Irregular"){
                                                $removeSubjectLoadBtn = "
                                                    <button 
                                                        class='btn btn-sm btn-danger'
                                                        onclick='$removeSubject'
                                                        >
                                                        <i class='fas fa-trash'></i>
                                                    </button>
                                                ";
                                            }
                                            $totalUnits += $unit;

                                            echo "
                                                <tr class='text-center'>
                                                    <td>$subject_title</td>
                                                    <td>$enrolled_section_Name</td>
                                                    <td>$subject_type</td>
                                                    <td>$scheduleOutput</td>
                                                    <td>$roomOutput</td>
                                                    <td>$unit</td>
                                                    <td>
                                                        <button 
                                                            class='btn btn-sm btn-primary'
                                                            onclick=\"window.location.href = '" . $change_section_subject_url . "'\"
                                                            >
                                                            <i class='fas fa-pencil'></i>
                                                        </button>
                                                        $removeSubjectLoadBtn
                                                    </td>

                                                </tr>
                                            ";
                                        }
                                    }
                                ?>
                            </tbody>
                            <?php if($totalUnits != NULL): ?>
                                <tr class="text-right">
                                    <td colspan="6">Total Units: <?php echo $totalUnits;?></td>
                                </tr>
                            <?php endif; ?>
                            
                        </table>
                    </main>
                </div>


                <div class="action">
                    <button
                        class="default large"
                        onclick="window.location.href = 'process_enrollment.php?find_section=show&st_id=<?php echo $student_id;?>&c_id=<?php echo $student_course_id;?>'">
                        Return
                    </button>

                    <?php
                    
                        if($student_evaluated_by_registrar == "yes"){
                            ?>
                                <!-- <button onclick='EvaluateRequest("<?php echo $student_enrollment_id;?>", "<?php echo $student_enrollment_form_id;?>", <?php echo $student_enrollment_course_id;?>, <?php echo $current_school_year_id;?>, <?php echo $student_id;?>, "<?php echo $student_enrollment_student_status;?>", <?php echo $totalSubjectList;?>, "<?php echo "Evaluated"; ?>")' class="default clean large"> -->
                                <button  class="information large">
                                    Evaluated
                                </button>
                            <?php
                        }else if($student_evaluated_by_registrar == "no"){
                            ?>
                                <button onclick='EvaluateRequest("<?php echo $student_enrollment_id;?>", "<?php echo $student_enrollment_form_id;?>", <?php echo $student_enrollment_course_id;?>, <?php echo $current_school_year_id;?>, <?php echo $student_id;?>, "<?php echo $student_enrollment_student_status;?>", <?php echo $totalSubjectList;?>, "<?php echo "Non-Evaluated" ?>", <?php echo $enrollment_payment; ?>, <?= $registrarUserId; ?>)' class="default clean success large">
                                    Confirm
                                </button>
                            <?php
                        }
                    ?>

                    
                </div>
            </main>
        </div>

        
    <?php

?>

<script>
                    
    function removeSubject(student_subject_id){

        Swal.fire({
            icon: 'question',
            title: `Do you want to remove Subject Load ID: ${student_subject_id}`,
            text: 'Please note this action cannot be undone',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'

        }).then((result) => {

            if (result.isConfirmed) {
                $.ajax({
                    url: "../../ajax/admission/remove_student_subject.php",
                    type: 'POST',
                    data: {
                        student_subject_id
                    },
                    success: function(response) {
                        response = response.trim();

                        // console.log(response);

                        if(response == "success_delete"){
                            Swal.fire({
                            icon: 'success',
                            title: `Successfully Removed`,
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

                            $('#irregular_subject_review_table').load(
                                location.href + ' #irregular_subject_review_table'
                            );
                            location.reload();
                        })}

                    },
                    error: function(xhr, status, error) {
                        // handle any errors here
                    }
                });
            }  
        });
    }

    function EvaluateRequest(student_enrollment_id, student_enrollment_form_id,
        student_course_id, current_school_year_id, student_id,
        student_status, totalSubjectList, remark, enrollment_payment, registrarUserId){

        var student_id = parseInt(student_id);
        var student_course_id = parseInt(student_course_id);
        var current_school_year_id = parseInt(current_school_year_id);
        
        var hasError = false;
        // console.log(totalSubjectList)

        if(parseInt(totalSubjectList) === 0){

            hasError = true;

            var enrollmentId = `<?php
                echo $student_enrollment_id
            ?>`;
            Swal.fire({
                icon: 'warning',
                title: `Oh no! Please select appropriate subject load.`,
                text: "",
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {
                    $url = `choosing_subject.php?e_id=${enrollmentId}&st_id=${student_id}`;
                    window.location.href = $url;
                }
            });
        }

        else if(student_status === ""){

            hasError = true;

            Swal.fire({
                icon: 'warning',
                title: `Oh no! You have missed something.`,
                text: "Note: Please update student status.",
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                }
            });
        }
        else if(hasError == false){

            var title = '';
            var text = '';

            if(remark == "Evaluated"){
                title = "Confirm Re-evaluation?"
                // text = "Note: You are changing the previous evaluation."

            }else if(remark == "Non-Evaluated"){
                title = "Confirm Enrollment?";
            }
            Swal.fire({
                icon: 'question',
                title:  `${title}`,
                // text: `${text}`,
                text: `Note: Please deliberately finalized your evaluation.`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                        $.ajax({
                        url: '../../ajax/admission/student_enrollment_confirm.php',
                        type: 'POST',
                        data: {student_enrollment_form_id,
                            student_course_id, current_school_year_id,
                            student_id, enrollment_payment, registrarUserId
                        },

                        // dataType: "json",

                        success: function(response) {

                            response = response.trim();
                            console.log(response)

                            if(response == "update_success"){

                                Swal.fire({
                                        title: "Successfully Evaluated.",
                                        icon: "success",
                                        showCancelButton: false,
                                        confirmButtonText: "OK",
                                          backdrop: false,
                                        allowEscapeKey: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {

                                        // var url = `../student/record_details.php?id=${student_id}&enrolled_subject=show`;
                                        var url = `./subject_insertion_summary.php?id=${student_enrollment_id}&enrolled_subject=show`;
                                        window.location.href = url;

                                    } else {
                                        // User clicked Cancel or closed the dialog
                                    }
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                }
            });
        }

    }

    // function enrollmentStudentStatusChanging(student_enrollment_id,
    //     student_id, current_school_year_id, type){

    //     var student_enrollment_id = parseInt(student_enrollment_id);
    //     var student_id = parseInt(student_id);
    //     var current_school_year_id = parseInt(current_school_year_id);
        
    //     var title = '';
    //     var text = '';

    //     if(type == "Regular"){
    //         // text = "Note: If retake form is activated, This will de-activate the Retake form.";
    //         text = "Note: Please finalized this changes.";
    //     }
    //     else if(type == "Irregular"){
    //         text = "Note: Please finalized this changes.";
    //     }

    //     Swal.fire({
    //         icon: 'question',
    //         title: `Change status as ${type}?`,
    //         text: `${text}`,
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes',
    //         cancelButtonText: 'Cancel'

    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // console.log(student_enrollment_form_id)

    //                 $.ajax({
    //                 url: '../../ajax/admission/changingFormStatus.php',
    //                 type: 'POST',
    //                 data: {
    //                     student_enrollment_id,
    //                     student_id,
    //                     current_school_year_id,
    //                     type
    //                 },

    //                 // dataType: "json",

    //                 success: function(response) {

    //                     response = response.trim();

    //                     console.log(response);

    //                     if(response == "update_success"){

    //                         Swal.fire({
    //                             title: "Changes Successfully made",
    //                             icon: "success",
    //                             showCancelButton: false,
    //                             confirmButtonText: "OK",

    //                         }).then((result) => {
    //                             if (result.isConfirmed) {

    //                                 location.reload();

    //                             } else {
                                    
    //                             }
    //                         });
    //                     }
    //                 },
    //                 error: function(xhr, status, error) {
    //                     // handle any errors here
    //                 }
    //             });
    //         }
    //     });

    // }

    function markAsEnrollmentRetake(student_enrollment_id,
        student_id, current_school_year_id, type){

        var student_enrollment_id = parseInt(student_enrollment_id);
        var student_id = parseInt(student_id);
        var current_school_year_id = parseInt(current_school_year_id);
        
        Swal.fire({
            icon: 'question',
            title: `Mark form as retake?`,
            text: "It means student has been placed again in the same year level",
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'

        }).then((result) => {
            if (result.isConfirmed) {
                // console.log(student_enrollment_form_id)

                    $.ajax({
                    url: '../../ajax/admission/changingFormStatus.php',
                    type: 'POST',
                    data: {
                        student_enrollment_id,
                        student_id,
                        current_school_year_id,
                        type
                    },

                    // dataType: "json",

                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "update_success"){

                            Swal.fire({
                                title: "Changes Successfully made",
                                icon: "success",
                                showCancelButton: false,
                                confirmButtonText: "OK",

                            }).then((result) => {
                                if (result.isConfirmed) {

                                    location.reload();

                                } else {
                                    
                                }
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // handle any errors here
                    }
                });
            }
        });
    }

    // function studentRemoveForm(student_id, enrollment_id, school_year_id){

    //     var student_id = parseInt(student_id);
    //     var enrollment_id = parseInt(enrollment_id);
    //     var school_year_id = parseInt(school_year_id);

    //     Swal.fire({
    //         icon: 'question',
    //         title: `Are you sure to remove this enrollment form?`,
    //         text: 'Note: This action cannot be undone.',
    //         showCancelButton: true,
    //         confirmButtonText: 'Yes',
    //         cancelButtonText: 'Cancel'
    //     }).then((result) => {
    //         if (result.isConfirmed) {
    //             // REFX
    //             $.ajax({
    //                 url: '../../ajax/admission/removeEnrollmentForm.php',
    //                 type: 'POST',
    //                 data: {
    //                     student_id, enrollment_id, school_year_id
    //                 },
    //                 success: function(response) {

    //                     response = response.trim();

    //                     console.log(response);

    //                     Swal.fire({
    //                         icon: 'success',
    //                         title: `Enrollment form has been removed.`,
    //                     });

    //                     setTimeout(() => {
    //                         Swal.close();
    //                         // location.reload();
    //                         window.location.href = "evaluation.php";
    //                     }, 1000);
    //                 },

    //                 error: function(jqXHR, textStatus, errorThrown) {
    //                     console.log('AJAX Error:', textStatus, errorThrown);
    //                 }
    //             });
    //         }
    //     });
    // }
            
</script>