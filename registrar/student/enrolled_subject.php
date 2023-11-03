<?php

    $enrollmentEnrolledSubDetails1 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_ELEVEN, $FIRST_SEMESTER);

    if($enrollmentEnrolledSubDetails1 != null){

        $enrollment_es_date_approved11_1st = $enrollmentEnrolledSubDetails1['enrollment_date_approved'];
        $enrollment_es_section_acronym11_1st = $enrollmentEnrolledSubDetails1['enrollment_section_acronym'];
        $enrollment_es_section_level11_1st = $enrollmentEnrolledSubDetails1['enrollment_section_level'];
        $enrollment_es_period11_1st = $enrollmentEnrolledSubDetails1['enrollment_period'];
        $enrollment_es_student_status11_1st = $enrollmentEnrolledSubDetails1['enrollment_student_status'];
    }

    $enrollmentEnrolledSubDetails2 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_ELEVEN, $SECOND_SEMESTER);
    
    
    if($enrollmentEnrolledSubDetails2 != null){
        $enrollment_es_date_approved11_2nd = $enrollmentEnrolledSubDetails2['enrollment_date_approved'];
        $enrollment_es_section_acronym11_2nd = $enrollmentEnrolledSubDetails2['enrollment_section_acronym'];
        $enrollment_es_section_level11_2nd = $enrollmentEnrolledSubDetails2['enrollment_section_level'];
        $enrollment_es_period11_2nd = $enrollmentEnrolledSubDetails2['enrollment_period'];
        $enrollment_es_student_status11_2nd = $enrollmentEnrolledSubDetails2['enrollment_student_status'];
    }


    $enrollmentEnrolledSubDetails3 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_TWELVE, $FIRST_SEMESTER);
    
    
    if($enrollmentEnrolledSubDetails3 != null){
        $enrollment_es_date_approved12_1st = $enrollmentEnrolledSubDetails3['enrollment_date_approved'];
        $enrollment_es_section_acronym12_1st = $enrollmentEnrolledSubDetails3['enrollment_section_acronym'];
        $enrollment_es_section_level12_1st = $enrollmentEnrolledSubDetails3['enrollment_section_level'];
        $enrollment_es_period12_1st = $enrollmentEnrolledSubDetails3['enrollment_period'];
        $enrollment_es_student_status12_1st = $enrollmentEnrolledSubDetails3['enrollment_student_status'];
    }


    $enrollmentEnrolledSubDetails4 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_TWELVE, $SECOND_SEMESTER);
    
    
    if($enrollmentEnrolledSubDetails4 != null){
        $enrollment_es_date_approved12_2nd = $enrollmentEnrolledSubDetails4['enrollment_date_approved'];
        $enrollment_es_section_acronym12_2nd = $enrollmentEnrolledSubDetails4['enrollment_section_acronym'];
        $enrollment_es_section_level12_2nd = $enrollmentEnrolledSubDetails4['enrollment_section_level'];
        $enrollment_es_period12_2nd = $enrollmentEnrolledSubDetails4['enrollment_period'];
        $enrollment_es_student_status12_2nd = $enrollmentEnrolledSubDetails4['enrollment_student_status'];
    }

    ?>

        <div class="content">
            <nav>
                <a href="index.php"><i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>
            <div class="content-header">
                <?php echo Helper::RevealStudentTypePending($type); ?>

                <header>
                    <div class="title">
                        <h2><?php echo $student->GetLastName();?>, <?php echo $student->GetFirstName();?> <?php echo $student->GetMiddleName();?> <?php echo $student->GetSuffix();?></h2>
                    </div>
                    <div class="action">
                        <div class="dropdown">
                        <button class="icon">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item" style="color: red"
                                ><i class="bi bi-file-earmark-x"></i>Delete form</a
                                >
                            </div>
                        </div>
                    </div>
                </header>
                
                <?php 
                    echo Helper::CreateStudentTabs($student_unique_id, $student_level,
                        $type, $section_acronym, $student_active_status,
                        $enrollment_date);
                ?>
            </div>

            <div class="tabs">

                <?php
                    echo "
                        <button class='tab' 
                            style='background-color: var(--them); color: white'
                            onclick=\"window.location.href = 'record_details.php?id=$student_id&details=show';\">
                            <i class='bi bi-clipboard-check'></i>
                            Student Details
                        </button>
                    ";

                    echo "
                        <button class='tab' 
                            id='shsPayment'
                            style='background-color: var(--them); color: white'
                            onclick=\"window.location.href = 'record_details.php?id=$student_id&grade_records=show';\">
                            <i class='bi bi-book'></i>
                            Grade Records
                        </button>
                    ";

                    echo "
                        <button class='tab' 
                            id='shsPayment'
                            style='background-color: var(--mainContentBG); color: black'
                            onclick=\"window.location.href = 'record_details.php?id=$student_id&enrolled_subject=show';\">
                            <i class='bi bi-collection icon'></i>
                            Enrolled Subjects
                        </button>
                    ";
                ?>
            </div>
                    
            <main>

                <!-- ES GRADE 11 1st Semester -->
                <div style="display: none;" class="floating">

                    <?php 

                        $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                            $student_id, $GRADE_ELEVEN, $FIRST_SEMESTER);

                        if($enrollment_school_year !== null){

                            $term = $enrollment_school_year['term'];
                            $period = $enrollment_school_year['period'];
                            $school_year_id = $enrollment_school_year['school_year_id'];
                            $enrollment_course_id = $enrollment_school_year['course_id'];

                            $section = new Section($con, $enrollment_course_id);
                            $enrollment_course_level = $section->GetSectionGradeLevel();

                            $enrollment_section_name = $section->GetSectionName();
                            
                            // Grade 11 $enrollment_section_name $period Semester (SY $term)
                            
                            echo "
                                <header>
                                    <div class='title'>
                                        <h4 class='text-info'>
                                            SY $term
                                        </h4>
                                    </div>
                                </header>
                            
                            ";
                        }else{
                            echo "
                                <header>
                                    <div class='col-md-12' class='title'>
                                        <p class='text-right text-warning mb-0' style='font-weight:bold;font-size:14px;'></p>
                                        <h4 class='text-muted'>
                                            Grade 11 First Semester
                                        </h4>
                                    </div>
                                </header>
                            
                            ";
                        }
                    ?>
                    
                    <main>
                    
                        <div style="
                            display: flex;
                            justify-content: space-around;
                            gap: 10px;
                            flex-wrap: wrap;
                            text-align: center" class="cards">
                            <div class="card">
                                <p>Semester</p>
                                <p><?php echo $enrollment_es_period11_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Grade level</p>
                                <p><?php echo $enrollment_es_section_level11_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Strand</p>
                                <p><?php echo $enrollment_es_section_acronym11_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Scholastic Status</p>
                                <p><?php echo $enrollment_es_student_status11_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Added</p>
                                <p><?php echo $enrollment_es_date_approved11_1st ?? "-";?></p>
                            </div>

                        </div>
                        
                        <table class="a">
                            <thead>
                                <tr> 
                                    <th>Subject</th>  
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Unit</th>
                                    <th>Section</th>  
                                    <th>Days</th>  
                                    <th>Time</th>  
                                    <th>Room</th>  
                                    <th>Instructor</th>  
                                </tr>
                            </thead> 	
                            <tbody>
                                <?php 

                                    $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                        $GRADE_ELEVEN, $FIRST_SEMESTER);

                                    foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {

                                        $course_id = $value['course_id'];
                                        $course_level = $value['course_level'];
                                        $subject_code = $value['subject_code'];
                                        $subject_title = $value['subject_title'];
                                        $unit = $value['unit'];
                                        $program_section = $value['program_section'];
                                        $subject_type = $value['subject_type'];

                                        $student_subject_code = $value['student_subject_code'];

                                        $student_subject_id = $value['student_subject_id'];

                                        $graded_student_subject_id = $value['graded_student_subject_id'];


                                        $time_from = $value['time_from'];
                                        $time_to = $value['time_to'];
                                        $schedule_day = $value['schedule_day'];
                                        $schedule_time = $value['schedule_time'];

                                        $room = $value['room'];

                                        $teacher_firstname = $value['firstname'];
                                        $teacher_lastname = $value['lastname'];

                                        $instructor_name = "N/A";

                                        if($teacher_firstname != null){
                                            $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                        }

                                        echo '<tr class="text-center">';
                                        echo '<td>'.$subject_title.'</td>';
                                        echo '<td>'.$subject_code.'</td>';
                                        echo '<td>'.$subject_type.'</td>';
                                        echo '<td>'.$unit.'</td>';
                                        echo '<td>'.$program_section.'</td>';
                                        echo '<td>'.$schedule_day.'</td>';
                                        echo '<td>'.$schedule_time.'</td>';
                                        echo '<td>'.$room.'</td>';
                                        echo '<td>'.$instructor_name.'</td>';
                                        echo '</tr>';
                                        
                                    }

                                ?>
                            </tbody>
                        </table>

                    </main>
                </div>

                <?php 

                    // $retakeDate = $enrollment->GetRetake($student_id);
                    $retakeEnrollment = $enrollment->GetEnrolledSubjectForm($student_id);

                    if(count($retakeEnrollment) > 0){
                        foreach ($retakeEnrollment as $key => $value) {

                            $enrollment_id = $value['enrollment_id'];
                            $term = $value['term'];
                            $period = $value['period'];
                            $school_year_id = $value['school_year_id'];
                            $retake = $value['retake'];
                            $enrollment_status = $value['enrollment_status'];

                            // echo $school_year_id;
                            
                            $course_level = $value['course_level'];
                            $course_id = $value['course_id'];
                            $student_status = $value['student_status'];

                            $enrollment_approve = $value['enrollment_approve'];

                            $dateTime = new DateTime($enrollment_approve);

                            // Format the DateTime object as desired
                            $enrollment_approve = $dateTime->format('Y-m-d g:i A');


                            $enrollment_form_id = $value['enrollment_form_id'];
                            $enrollment_id = $value['enrollment_id'];

                            $section = new Section($con, $course_id);

                            $sectionProgramId = $section->GetSectionProgramId($course_id);
                            $sectionAcronym = $section->GetAcronymByProgramId($sectionProgramId);
                            $sectionName = $section->GetSectionName($sectionProgramId);
                            // $sp_subjectCode = $value['sp_subjectCode'];
                            // $subject_code = $value['subject_code'];

                            $enrollment_course_level
                            ?>
                                <div class="floating">
                                    <?php 
                                        include_once('./changeStudentSectionModal.php');
                                    ?>
                                    <header>
                                        
                                        <div class='title'>
                                            
                                            <?php
                                                if($school_year_id == $current_school_year_id){

                                                    ?>
                                                        <div class='action'>
                                                            <div class='dropdown'>

                                                                <button class='icon'>
                                                                    <i class='bi bi-three-dots-vertical'></i>
                                                                </button>

                                                                <div class='dropdown-menu' >

                                                                    <button style="cursor: pointer;"
                                                                        type='button' 
                                                                        data-bs-target='#changeStudentSectionModalBtn' 
                                                                        data-bs-toggle='modal'
                                                                        href='#' class='dropdown-item text-warning'>
                                                                        <i class='bi bi-file-earmark-x'></i>&nbsp Change Section
                                                                    </button>

                                                                    <!-- <a 
                                                                        onclick="<?php echo "studentWithdrawForm($student_id, $student_enrollment_id, $current_school_year_id)"; ?>"
                                                                        class="dropdown-item" style="cursor:pointer;color: yellow">
                                                                        <i class="bi bi-file-earmark-x"></i>
                                                                        Un-enroll Form
                                                                    </a> -->

                                                                    <form  action='enrolled_subject_print.php' method='POST'>

                                                                        <input type="hidden" name="enrollment_id" id="enrollment_id" value="<?php echo $enrollment_id;?>">
                                                                        <input type="hidden" name="student_id" id="student_id" value="<?php echo $student_id;?>">

                                                                        <button style="cursor: pointer;"
                                                                            type='submit' 
                                                                            
                                                                            href='#' name="enrolled_subject_print_<?php echo $enrollment_form_id;?>"
                                                                            class='dropdown-item text-primary'>
                                                                            <i class='bi bi-file-earmark-x'></i>&nbsp Print
                                                                        </button>
                                                                    </form>

                                                                    <form style="text-align: right;display: block;"
                                                                        action="../admission/print_enrolled_subject.php" method="POST" id="printForm">

                                                                        <input type="hidden" name="enrollment_id" id="enrollment_id" value="<?php echo $enrollment_id;?>">
                                                                        <input type="hidden" name="student_id" id="student_id" value="<?php echo $student_id;?>">
                                                                        <input type="hidden" name="school_year_id" id="school_year_id" value="<?php echo $current_school_year_id;?>">
                                                                        
                                                                        <button name="print_enrolled_subject" id="toClickButton"
                                                                            class="dropdown-item text-primary">
                                                                            <i class='bi bi-file-earmark-x'></i>&nbsp Send email
                                                                        </button>

                                                                    </form>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php
                                                        
                                                }
                                            ?>
                                            <p class="text-right text-primary"><?php echo $retake == 1 ? "Retake Level" : "" ?></p>

                                            <a style="all: unset;" href="../admission/subject_insertion_summary.php?id=<?php echo $enrollment_id; ?>&enrolled_subject=show">
                                                <p class='mb-0 text-right text-muted'>Form ID: #<?php echo $enrollment_form_id;?></p>
                                            </a>

                                            <span class="text-warning" style="font-size: 12px; font-weight: bold"><?php echo $enrollment_status == "enrolled" ? "Enrolled" : 
                                                ($enrollment_status == "withdraw" ? "Withdraw" : ($enrollment_status == "tentative" ? "Tentative" : "")) ?></span>
                                            <h4 style="font-weight: bold;" class='text-info'>
                                                S.Y <?php echo $term;?>
                                            </h4>
                                        </div>
                                    </header>

                                    <main>

                                        <div style="display: flex; justify-content: space-around; gap: 10px; flex-wrap: wrap; text-align: center"  class="cards">
                                            
                                            <div class="card">
                                                <p>Semester</p>
                                                <p><?php echo $period;?></p>
                                            </div>
                                            <div class="card">
                                                <p>Grade level</p>
                                                <p><?php echo $course_level;?></p>
                                                
                                            </div>
                                            <div class="card">
                                                <p>Section</p>
                                                <p><?php echo $sectionName;?></p>
                                                
                                            </div>
                                            <div class="card">
                                                <p>Scholastic Status</p>
                                                <p><?php echo $student_status;?></p>
                                                
                                            </div>
                                            <div class="card">
                                                <p>Added</p>
                                                <p><?php echo $enrollment_approve;?></p>
                                            
                                            </div>
                                        </div>

                                        <table class="a">
                                            <thead>
                                                <tr> 
                                                    <th>Subject</th>  
                                                    <th>Code</th>
                                                    <th>Unit</th>
                                                    <th>Section</th>  
                                                    <th>Days</th>  
                                                    <th>Time</th>  
                                                    <th>Room</th>  
                                                    <th>Instructor</th>  
                                                    <th></th>  
                                                </tr>
                                            </thead> 	
                                            <tbody>
                                                <?php
                                                
                                                    $query = $con->prepare("SELECT 

                                                        t4.subject_code AS student_subject_code,
                                                        t4.is_final,
                                                        t4.enrollment_id,
                                                        t4.is_transferee,
                                                        t4.student_subject_id,
                                                        t4.retake AS ss_retake,
                                                        t4.overlap AS ss_overlap,
                                                        

                                                        t5.subject_code AS sp_subjectCode,
                                                        t5.subject_type,
                                                        t5.subject_title,
                                                        t5.unit,

                                                        t6.program_section,

                                                        t7.student_subject_id as graded_student_subject_id,
                                                        t7.remarks,

                                                        t8.subject_schedule_id,
                                                        t8.course_id AS subject_schedule_course_id,
                                                        t8.subject_program_id AS subject_subject_program_id,
                                                        t8.time_from,
                                                        t8.time_to,
                                                        t8.schedule_day,
                                                        t8.schedule_time,
                                                        t8.room_id,

                                                        t9.firstname,
                                                        t9.lastname,

                                                        t10.room_number

                                                        FROM student_subject AS t4 

                                                        LEFT JOIN subject_program AS t5 ON t5.subject_program_id = t4.subject_program_id
                                                        LEFT JOIN course AS t6 ON t6.course_id = t4.course_id
                                                        LEFT JOIN student_subject_grade AS t7 ON t7.student_subject_id = t4.student_subject_id

                                                        LEFT JOIN subject_schedule AS t8 ON t8.subject_code = t4.subject_code
                                                        AND t8.course_id = t4.course_id

                                                        LEFT JOIN teacher as t9 ON t9.teacher_id = t8.teacher_id

                                                        LEFT JOIN room as t10 ON t10.room_id = t8.room_id

                                                        WHERE t4.student_id=:student_id
                                                        AND t4.enrollment_id=:enrollment_id

                                                        ORDER BY t5.subject_title DESC

                                                        -- GROUP BY t8.schedule_time -- Distinct on t1.subject_title

                                                    ");

                                                    $query->bindValue(":student_id", $student_id); 
                                                    $query->bindValue(":enrollment_id", $enrollment_id); 
                                                    $query->execute(); 

                                                    if($query->rowCount() > 0){

                                                        $subject_titles_occurrences = [];
                                                        $subject_code_occurrences = [];
                                                        $subject_type_occurrences = [];
                                                        $subject_unit_occurrences = [];
                                                        $section_occurrences = [];
                                                        $sched_arr = [];

                                                        while($row_inner = $query->fetch(PDO::FETCH_ASSOC)){
                                                            $subject_title = $row_inner['subject_title'];

                                                            $schedule = new Schedule($con);

                                                            $student_subject_code = $row_inner['student_subject_code'];
                                                            $sp_subjectCode = $row_inner['sp_subjectCode'];
                                                            $subject_schedule_id = $row_inner['subject_schedule_id'];

                                                            $subject_schedule_course_id = $row_inner['subject_schedule_course_id'];
                                                            $subject_subject_program_id = $row_inner['subject_subject_program_id'];

                                                            $subject_type = $row_inner['subject_type'];
                                                            $unit = $row_inner['unit'];
                                                            $program_section = $row_inner['program_section'];
                                                            $remarks = $row_inner['remarks'];
                                                            $ss_retake = $row_inner['ss_retake'];
                                                            $ss_overlap = $row_inner['ss_overlap'];

                                                            $schedule_time = $row_inner['schedule_time'] != "" ? $row_inner['schedule_time'] : "-";
                                                            
                                                            $schedule->filterSubsequentOccurrencesSa($subject_titles_occurrences,
                                                                $subject_title, $subject_schedule_course_id, $subject_subject_program_id);

                                                            $schedule->filterSubsequentOccurrencesSa($subject_code_occurrences,
                                                                $sp_subjectCode, $subject_schedule_course_id, $subject_subject_program_id);

                                                            $schedule->filterSubsequentOccurrencesSa($subject_type_occurrences,
                                                                $subject_type, $subject_schedule_course_id, $subject_subject_program_id);

                                                            // $schedule->filterSubsequentOccurrencesSa($section_occurrences,
                                                            //     $program_section, $subject_schedule_course_id, $subject_subject_program_id);

                                                            // $schedule->filterSubsequentOccurrencesSa($subject_unit_occurrences,
                                                            //     $unit, $subject_schedule_course_id, $subject_subject_program_id);



                                                            // $schedule->filterSubsequentOccurrences($subject_code_occurrences, $sp_subjectCode);
                                                            // $schedule->filterSubsequentOccurrences($subject_type_occurrences, $subject_type);
                                                            // $schedule->filterSubsequentOccurrences($section_occurrences, $schedule_time);

                                                            $ss_retake_msg = $ss_retake == 1 ? "RT" : " &nbsp&nbsp&nbsp&nbsp";
                                                            $ss_overlap_msg = $ss_overlap == 1 ? "OL" : "";

                                                            $icon =  $remarks == "Passed" && $enrollment_status == "enrolled" ? "
                                                                <i style='color: green;' class='fas fa-check-circle'></i>
                                                            " : ($remarks == "Failed"
                                                                ? "<i style='color: orange;' class='fas fa-times-circle'></i>" 
                                                                : ( $remarks == null && $enrollment_status == "enrolled" ? "<i style='color: blue;' class='bi bi-hourglass-top'></i>" : "<i style='color: blue;' class='bi bi-airplane'></i>"));

                                                            $student_subject_code = $row_inner['student_subject_code'];


                                                            $student_subject_id = $row_inner['student_subject_id'];
                                                            $is_final = $row_inner['is_final'];

                                                            
                                                            $graded_student_subject_id = $row_inner['graded_student_subject_id'];

                                                            $remarks_url = "";
  
                                                            $db_enrollment_id = $row_inner['enrollment_id'];
                                                            $db_is_transferee = $row_inner['is_transferee'];

                                                            $time_from = $row_inner['time_from'];

                                                            $time_to = $row_inner['time_to'];

                                                            // $room = $row_inner['room'] != "" ? $row_inner['room'] : "-";

                                                            $room_number = $row_inner['room_number'] != "" ? $row_inner['room_number'] : "-";

                                                            
                                                            $schedule_day = $row_inner['schedule_day'] != "" ? $row_inner['schedule_day'] : "-";

                                                            $teacher_firstname = $row_inner['firstname'];
                                                            $teacher_lastname = $row_inner['lastname'];

                                                            $instructor_name = "-";

                                                            if($teacher_firstname != null){
                                                                $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                                            }

                                                            $changingSectionSubjectUrl = "./change_student_subject.php?id=$student_subject_id";

                                                            echo "
                                                                <tr class='text-center'>
                                                                    <td>$subject_title</td>
                                                                    <td>
                                                                        $sp_subjectCode
                                                                    </td>
                                                                    <td>$unit</td>
                                                                    <td>
                                                                        <a style='all:unset; cursor: pointer' href='$changingSectionSubjectUrl'>
                                                                            $program_section
                                                                        </a>
                                                                    </td>
                                                                    <td>$schedule_day</td>
                                                                    <td>$schedule_time</td>
                                                                    <td>$room_number</td>
                                                                    <td>$instructor_name</td>
                                                                    <td>$icon $ss_overlap_msg $ss_retake_msg</td>
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

    function studentWithdrawForm(student_id, enrollment_id, school_year_id){

        var student_id = parseInt(student_id);
        var enrollment_id = parseInt(enrollment_id);
        var school_year_id = parseInt(school_year_id);

        Swal.fire({
            icon: 'question',
            title: `Are you sure to un-enroll this enrollment form?`,
            text: 'Note: This action will cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/admission/unEnrollEnrolledForm.php',
                    type: 'POST',
                    data: {
                        student_id, enrollment_id, school_year_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "success_update"){

                            Swal.fire({
                                icon: 'success',
                                title: `Enrollment Form has been removed..`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                // location.reload();
                                window.location.href = "../enrollment/index.php";
                            }, 1000);

                        }

                    },

                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }

</script>