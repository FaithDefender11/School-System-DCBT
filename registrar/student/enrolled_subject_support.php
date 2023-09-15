<?php

    $enrollmentEnrolledSubDetails1 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_ELEVEN, $FIRST_SEMESTER);

        // EE
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
                <a href="SHS-find-form-evaluation.html"
                ><i class="bi bi-arrow-return-left fa-1x"></i>
                <h3>Back</h3>
                </a>
            </nav>
            <div class="content-header">
                <?php echo Helper::RevealStudentTypePending($type); ?>

                <header>
                    <div class="title">
                        <h2><?php echo $student->GetLastName();?>, <?php echo $student->GetFirstName();?>, <?php echo $student->GetMiddleName();?>, <?php echo $student->GetSuffix();?></h2>
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
                
                <?php echo Helper::CreateStudentTabs($student_unique_id, $student_level,
                    $type, $section_acronym, $payment_status,
                    $enrollment_date);?>
            </div>

            <div class="tabs">

                <?php
                    echo "
                        <button class='tab' 
                            style='background-color: var(--them)'
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
                            style='background-color: var(--mainContentBG); color: white'
                            onclick=\"window.location.href = 'record_details.php?id=$student_id&enrolled_subject=show';\">
                            <i class='bi bi-collection icon'></i>
                            Enrolled Subjects
                        </button>
                    ";
                ?>
            </div>
                    
            <main>

                <!-- ES GRADE 11 1st Semester -->
                <div class="floating">

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

                <!-- ES GRADE 11 2nd Semester -->
                <div class="floating">

                    <?php 

                        $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                            $student_id, $GRADE_ELEVEN, $SECOND_SEMESTER);

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
                                            Grade 11 Second Semester
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
                                <p><?php echo $enrollment_es_period11_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Grade level</p>
                                <p><?php echo $enrollment_es_section_level11_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Strand</p>
                                <p><?php echo $enrollment_es_section_acronym11_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Scholastic Status</p>
                                <p><?php echo $enrollment_es_student_status11_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Added</p>
                                <p><?php echo $enrollment_es_date_approved11_2nd ?? "-";?></p>
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
                                        $GRADE_ELEVEN, $SECOND_SEMESTER);

                                    foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                        $course_id = $value['course_id'];
                                        $course_level = $value['course_level'];
                                        $subject_code = $value['subject_code'];
                                        $subject_title = $value['subject_title'];
                                        $unit = $value['unit'];
                                        $program_section = $value['program_section'];
                                        $subject_type = $value['subject_type'];

                                        $db_enrollment_id = $value['enrollment_id'];
                                        $db_is_transferee = $value['is_transferee'];

                                        $student_subject_code = $value['student_subject_code'];

                                        $student_subject_id = $value['student_subject_id'];

                                        $graded_student_subject_id = $value['graded_student_subject_id'];


                                        $time_from = $value['time_from'];
                                        $time_to = $value['time_to'];
                                        $schedule_day = $value['schedule_day'];
                                        $schedule_time = $value['schedule_time'];
                                        $room = $value['room'];

                                        $room = $value['room'];

                                        $teacher_firstname = $value['firstname'];
                                        $teacher_lastname = $value['lastname'];

                                        $instructor_name = "N/A";

                                        if($teacher_firstname != null){
                                            $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                        }

                                        if($db_enrollment_id == NULL && $db_is_transferee == 1){
                                            $program_section = "-";
                                            $schedule_day = "-";
                                            $schedule_time = "-";
                                            $room = "-";
                                            $instructor_name = "-";
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

                <!-- ES GRADE 12 1st Semester -->
                <div class="floating">

                    <?php 

                        $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                            $student_id, $GRADE_TWELVE, $FIRST_SEMESTER);

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
                                            Grade 12 First Semester
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
                                <p><?php echo $enrollment_es_period12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Grade level</p>
                                <p><?php echo $enrollment_es_section_level12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Strand</p>
                                <p><?php echo $enrollment_es_section_acronym12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Scholastic Status</p>
                                <p><?php echo $enrollment_es_student_status12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Added</p>
                                <p><?php echo $enrollment_es_date_approved12_1st ?? "-";?></p>
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
                                        $GRADE_TWELVE, $FIRST_SEMESTER);

                                    foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                        $course_id = $value['course_id'];
                                        $course_level = $value['course_level'];
                                        $subject_code = $value['subject_code'];
                                        $subject_title = $value['subject_title'];
                                        $unit = $value['unit'];
                                        $program_section = $value['program_section'];
                                        $subject_type = $value['subject_type'];

                                        $db_enrollment_id = $value['enrollment_id'];
                                        $db_is_transferee = $value['is_transferee'];

                                        $student_subject_code = $value['student_subject_code'];

                                        $student_subject_id = $value['student_subject_id'];

                                        $graded_student_subject_id = $value['graded_student_subject_id'];


                                        $time_from = $value['time_from'];
                                        $time_to = $value['time_to'];
                                        $schedule_day = $value['schedule_day'];
                                        $schedule_time = $value['schedule_time'];
                                        $room = $value['room'];

                                        $room = $value['room'];

                                        $teacher_firstname = $value['firstname'];
                                        $teacher_lastname = $value['lastname'];

                                        $instructor_name = "N/A";

                                        if($teacher_firstname != null){
                                            $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                        }

                                        if($db_enrollment_id == NULL && $db_is_transferee == 1){
                                            $program_section = "-";
                                            $schedule_day = "-";
                                            $schedule_time = "-";
                                            $room = "-";
                                            $instructor_name = "-";
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

                <!-- ES GRADE 12 2nd Semester -->
                <div class="floating">

                    <?php 

                        $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                            $student_id, $GRADE_TWELVE, $SECOND_SEMESTER);

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
                                            Grade 12 Second Semester
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
                                <p><?php echo $enrollment_es_period12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Grade level</p>
                                <p><?php echo $enrollment_es_section_level12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Strand</p>
                                <p><?php echo $enrollment_es_section_acronym12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Scholastic Status</p>
                                <p><?php echo $enrollment_es_student_status12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Added</p>
                                <p><?php echo $enrollment_es_date_approved12_1st ?? "-";?></p>
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
                                        $GRADE_TWELVE, $SECOND_SEMESTER);

                                    foreach ($enrolledSubjectsGradeLevelSemesterBased as $key => $value) {
                                        $course_id = $value['course_id'];
                                        $course_level = $value['course_level'];
                                        $subject_code = $value['subject_code'];
                                        $subject_title = $value['subject_title'];
                                        $unit = $value['unit'];
                                        $program_section = $value['program_section'];
                                        $subject_type = $value['subject_type'];

                                        $db_enrollment_id = $value['enrollment_id'];
                                        $db_is_transferee = $value['is_transferee'];

                                        $student_subject_code = $value['student_subject_code'];

                                        $student_subject_id = $value['student_subject_id'];

                                        $graded_student_subject_id = $value['graded_student_subject_id'];


                                        $time_from = $value['time_from'];
                                        $time_to = $value['time_to'];
                                        $schedule_day = $value['schedule_day'];
                                        $schedule_time = $value['schedule_time'];
                                        $room = $value['room'];

                                        $room = $value['room'];

                                        $teacher_firstname = $value['firstname'];
                                        $teacher_lastname = $value['lastname'];

                                        $instructor_name = "N/A";

                                        if($teacher_firstname != null){
                                            $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                                        }

                                        if($db_enrollment_id == NULL && $db_is_transferee == 1){
                                            $program_section = "-";
                                            $schedule_day = "-";
                                            $schedule_time = "-";
                                            $room = "-";
                                            $instructor_name = "-";
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

            </main>
        </div>
    <?php

?>