

<?php 

    $init = false;

    if($init == false){
        echo Helper::RemoveSidebar();
        $init = true;
    }


    
    $enrollmentRecordDetails1 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_ELEVEN, $FIRST_SEMESTER);

    if($enrollmentRecordDetails1 != null){

        $enrollment_date_approved11_1st = $enrollmentRecordDetails1['enrollment_date_approved'];
        $enrollment_section_acronym11_1st = $enrollmentRecordDetails1['enrollment_section_acronym'];
        $enrollment_section_level11_1st = $enrollmentRecordDetails1['enrollment_section_level'];
        $enrollment_period11_1st = $enrollmentRecordDetails1['enrollment_period'];
        $enrollment_student_status11_1st = $enrollmentRecordDetails1['enrollment_student_status'];
        $enrollment_course_idx = $enrollmentRecordDetails1['enrollment_course_id'];

        // echo $enrollment_course_idx;
        
    }

    $enrollmentRecordDetails2 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_ELEVEN, $SECOND_SEMESTER);
    
    if($enrollmentRecordDetails2 != null){

        $enrollment_date_approved11_2nd = $enrollmentRecordDetails2['enrollment_date_approved'];
        $enrollment_section_acronym11_2nd = $enrollmentRecordDetails2['enrollment_section_acronym'];
        $enrollment_section_level11_2nd = $enrollmentRecordDetails2['enrollment_section_level'];
        $enrollment_period11_2nd = $enrollmentRecordDetails2['enrollment_period'];
        $enrollment_student_status11_2nd = $enrollmentRecordDetails2['enrollment_student_status'];
    }


    $enrollmentRecordDetails3 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_TWELVE, $FIRST_SEMESTER);
    
    
    if($enrollmentRecordDetails3 != null){

        $enrollment_date_approved12_1st = $enrollmentRecordDetails3['enrollment_date_approved'];
        $enrollment_section_acronym12_1st = $enrollmentRecordDetails3['enrollment_section_acronym'];
        $enrollment_section_level12_1st = $enrollmentRecordDetails3['enrollment_section_level'];
        $enrollment_period12_1st = $enrollmentRecordDetails3['enrollment_period'];
        $enrollment_student_status12_1st = $enrollmentRecordDetails3['enrollment_student_status'];
    }


    $enrollmentRecordDetails4 = $enrollment->getEnrollmentSectionDetails($student_id,
        $GRADE_TWELVE, $SECOND_SEMESTER);
    
    
    if($enrollmentRecordDetails4 != null){

        $enrollment_date_approved12_2nd = $enrollmentRecordDetails4['enrollment_date_approved'];
        $enrollment_section_acronym12_2nd = $enrollmentRecordDetails4['enrollment_section_acronym'];
        $enrollment_section_level12_2nd = $enrollmentRecordDetails4['enrollment_section_level'];
        $enrollment_period12_2nd = $enrollmentRecordDetails4['enrollment_period'];
        $enrollment_student_status12_2nd = $enrollmentRecordDetails4['enrollment_student_status'];
    }

    if($init == true){

        ?>
            <main>
                <!-- 1. Passed -->
                <!-- 2. Failed -->
                <!-- Some of subjects were passed, but some were fao;  -->

                <!-- GR GRADE 11 1st Semester -->
                <!-- <button style='display: block;' id='savePDF' class='btn btn-sm'>PDF</button> -->

                <!-- <form  action='pdf_sample.php' method='POST'>

                    <button  name='pdf_sample' type='submit'
                        class='btn btn-outline-primary btn-sm'>
                        Generate PDF
                    </button>
                </form> -->

                <div class="floating" id="firstSemCapture">

                    <?php 

                        $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                            $student_id, $GRADE_ELEVEN, $FIRST_SEMESTER);

                        if($enrollment_school_year !== null){

                            $term = $enrollment_school_year['term'];
                            $period = $enrollment_school_year['period'];
                            $school_year_id = $enrollment_school_year['school_year_id'];
                            $enrollment_course_id = $enrollment_school_year['course_id'];
                            $enrollment_form_id = $enrollment_school_year['enrollment_form_id'];

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
                                            Grade 11 1st Semester
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
                                <p><?php echo $enrollment_period11_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Grade level</p>
                                <p><?php echo $enrollment_section_level11_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Strand</p>
                                <p><?php echo $enrollment_section_acronym11_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Scholastic Status</p>
                                <p><?php echo $enrollment_student_status11_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Added</p>
                                <p><?php echo $enrollment_date_approved11_1st ?? "-";?></p>
                            </div>
                        </div>
                        
                        <table  class="a">
                            <thead>
                                <tr style="font-size: 14.5px;"> 
                                    <th>Subject</th>  
                                    <th>Code</th>
                                    <!-- <th>Type</th> -->
                                    <th rowspan="2">Unit</th>
                                    <th>Section</th>  
                                    <th>Prelim</th>  
                                    <th>Midterm</th>  
                                    <th>Pre-Final</th>  
                                    <th>Final</th>  
                                    <!-- <th>Average</th>   -->
                                    <th >Remarks</th>  
                                </tr>
                            </thead> 	
                            <tbody style="font-size: 13.8px;">
                                <?php 
                                    $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                        $GRADE_ELEVEN, $FIRST_SEMESTER);

                                    $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                        $checkEnrollmentEnrolled, $student_id);
                                ?>
                            </tbody>
                        </table>
                    </main>
                    
                </div>

                <!-- GR GRADE 11 2nd Semester -->
                <div class="floating">
                    <?php 

                        $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                            $student_id, $GRADE_ELEVEN, $SECOND_SEMESTER);

                        if($enrollment_school_year !== null){
                            $term = $enrollment_school_year['term'];
                            $period = $enrollment_school_year['period'];
                            $school_year_id = $enrollment_school_year['school_year_id'];
                            $enrollment_course_id = $enrollment_school_year['course_id'];
                            $enrollment_form_id = $enrollment_school_year['enrollment_form_id'];

                            $section = new Section($con, $enrollment_course_id);
                            $enrollment_course_level = $section->GetSectionGradeLevel();

                            $enrollment_section_name = $section->GetSectionName();

                            // include_once('./changeStudentSectionModal.php');

                            
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
                                            Grade 11 2nd Semester
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
                                text-align: center"
                            class="cards">
                            <div class="card">
                                <p>Semester</p>
                                <p><?php echo $enrollment_period11_2nd ?? "-"?></p>
                            </div>
                            <div class="card">
                                <p>Grade level</p>
                                <p><?php echo $enrollment_section_level11_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Strand</p>
                                <p><?php echo $enrollment_section_acronym11_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Scholastic Status</p>
                                <p><?php echo $enrollment_student_status11_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Added</p>
                                <p><?php echo $enrollment_date_approved11_2nd ?? "-";?></p>
                            </div>

                        </div>
                        <table class="a">
                            <thead>
                                <tr> 
                                    <th>Subject</th>  
                                    <th>Code</th>
                                    <!-- <th>Type</th> -->
                                    <th>Unit</th>
                                    <th>Section</th>  
                                    <th>Prelim</th>  
                                    <th>Midterm</th>  
                                    <th>Pre-Final</th>  
                                    <th>Final</th>  
                                    <!-- <th>Average</th>   -->
                                    <th >Remarks</th>  
                                </tr>
                            </thead> 	
                            <tbody>
                                <?php 

                                    $enrolledSubjectsGradeLevelSemesterBased = $subject_program->
                                        GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                            $GRADE_ELEVEN, $SECOND_SEMESTER);

                                    $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                        $checkEnrollmentEnrolled, $student_id);
                                ?>
                            </tbody>
                        </table>
                    </main>


                </div>

                <!-- GR GRADE 12 1st Semester -->
                <div class="floating">

                    <?php 

                        $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                            $student_id, $GRADE_TWELVE, $FIRST_SEMESTER);

                        if($enrollment_school_year !== null){
                            $term = $enrollment_school_year['term'];
                            $period = $enrollment_school_year['period'];
                            $school_year_id = $enrollment_school_year['school_year_id'];
                            $enrollment_course_id = $enrollment_school_year['course_id'];
                            $enrollment_form_id = $enrollment_school_year['enrollment_form_id'];

                            $section = new Section($con, $enrollment_course_id);
                            $enrollment_course_level = $section->GetSectionGradeLevel();

                            $enrollment_section_name = $section->GetSectionName();
                            
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
                                text-align: center"
                            class="cards">
                            <div class="card">
                                <p>Semester</p>
                                <p><?php echo $enrollment_period12_1st ?? "-"?></p>
                            </div>
                            <div class="card">
                                <p>Grade level</p>
                                <p><?php echo $enrollment_section_level12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Strand</p>
                                <p><?php echo $enrollment_section_acronym12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Scholastic Status</p>
                                <p><?php echo $enrollment_student_status12_1st ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Added</p>
                                <p><?php echo $enrollment_date_approved12_1st ?? "-";?></p>
                            </div>

                        </div>
                        <table class="a">
                            <thead>
                                <tr> 
                                    <th>Subject</th>  
                                    <th>Code</th>
                                    <!-- <th>Type</th> -->
                                    <th>Unit</th>
                                    <th>Section</th>  
                                    <th>Prelim</th>  
                                    <th>Midterm</th>  
                                    <th>Pre-Final</th>  
                                    <th>Final</th>  
                                    <!-- <th>Average</th>   -->
                                    <th >Remarks</th>  
                                </tr>
                            </thead> 	
                            <tbody>
                                <?php 

                                    $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                        $GRADE_TWELVE, $FIRST_SEMESTER);

                                    $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                        $checkEnrollmentEnrolled, $student_id);
                                        
                                ?>
                            </tbody>
                        </table>
                    </main>
                </div>

                <!-- GR GRADE 12 2nd Semester -->
                <div class="floating">

                    <?php 

                        $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                            $student_id, $GRADE_TWELVE, $SECOND_SEMESTER);

                        if($enrollment_school_year !== null){
                            $term = $enrollment_school_year['term'];
                            $period = $enrollment_school_year['period'];
                            $school_year_id = $enrollment_school_year['school_year_id'];
                            $enrollment_course_id = $enrollment_school_year['course_id'];
                            $enrollment_form_id = $enrollment_school_year['enrollment_form_id'];

                            $section = new Section($con, $enrollment_course_id);
                            $enrollment_course_level = $section->GetSectionGradeLevel();

                            $enrollment_section_name = $section->GetSectionName();
                            
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
                                text-align: center"
                            class="cards">
                            <div class="card">
                                <p>Semester</p>
                                <p><?php echo $enrollment_period12_2nd ?? "-"?></p>
                            </div>
                            <div class="card">
                                <p>Grade level</p>
                                <p><?php echo $enrollment_section_level12_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Strand</p>
                                <p><?php echo $enrollment_section_acronym12_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Scholastic Status</p>
                                <p><?php echo $enrollment_student_status12_2nd ?? "-";?></p>
                            </div>
                            <div class="card">
                                <p>Added</p>
                                <p><?php echo $enrollment_period12_2nd ?? "-";?></p>
                            </div>

                        </div>
                        <table class="a">
                            <thead>
                                <tr> 
                                    <th>Subject</th>  
                                    <th>Code</th>
                                    <!-- <th>Type</th> -->
                                    <th>Unit</th>
                                    <th>Section</th>  
                                    <th>Prelim</th>  
                                    <th>Midterm</th>  
                                    <th>Pre-Final</th>  
                                    <th>Final</th>  
                                    <!-- <th>Average</th>   -->
                                    <th >Remarks</th>  
                                </tr>
                            </thead> 	
                            <tbody>
                                <?php 

                                    $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                        $GRADE_TWELVE, $SECOND_SEMESTER);

                                    $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                        $checkEnrollmentEnrolled, $student_id);
                                ?>
                            </tbody>
                        </table>
                    </main>
                </div>

                <!-- RETAKE -->
                <?php 

                    // $retakeDate = $enrollment->GetRetake($student_id);
                    $retakeEnrollment = $enrollment->RetakeEnrollment($student_id);

                    if(count($retakeEnrollment) > 0){
                        foreach ($retakeEnrollment as $key => $value) {

                            $retake_enrollment_id = $value['enrollment_id'];
                            $term = $value['term'];
                            $period = $value['period'];
                            $course_level = $value['course_level'];
                            $course_id = $value['course_id'];
                            $student_status = $value['student_status'];
                            $enrollment_approve = $value['enrollment_approve'];
                            $enrollment_form_id = $value['enrollment_form_id'];

                            $section = new Section($con, $course_id);

                            $sectionProgramId = $section->GetSectionProgramId($course_id);
                            $sectionAcronym = $section->GetAcronymByProgramId($sectionProgramId);
                            // $sp_subjectCode = $value['sp_subjectCode'];
                            // $subject_code = $value['subject_code'];
                            ?>
                                <div style="display: none;" class="floating">
                                    <header>
                                        <div class='col-md-12' class='title'>
                                            <p class='mb-0 text-right text-muted'>Form ID: #<?php echo $enrollment_form_id;?></p>
                                            
                                            <p  class="mb-0 text-right text-primary">Retake Section</p>
                                            <h4 class='text-warning'>
                                                S.Y <?php echo $term;?>
                                            </h4>
                                        </div>
                                    </header>

                                    <main>

                                        <div style="
                                                display: flex;
                                                justify-content: space-around;
                                                gap: 10px;
                                                flex-wrap: wrap;
                                                text-align: center"  class="cards">
                                            <div class="card">
                                                <p>Semester</p>
                                                <p><?php echo $period;?></p>
                                            </div>
                                            <div class="card">
                                                <p>Grade level</p>
                                                <p><?php echo $course_level;?></p>
                                                
                                            </div>
                                            <div class="card">
                                                <p>Strand</p>
                                                <p><?php echo $sectionAcronym;?></p>
                                                
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
                                                    <!-- <th>Type</th> -->
                                                    <th>Unit</th>
                                                    <th>Section</th>  
                                                    <th>Prelim</th>  
                                                    <th>Midterm</th>  
                                                    <th>Pre-Final</th>  
                                                    <th>Final</th>  
                                                    <!-- <th>Average</th>   -->
                                                    <th >Remarks</th>  
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
                                                    t5.subject_code AS sp_subjectCode,
                                                    t5.subject_type,
                                                    t5.subject_title,
                                                    t5.unit,

                                                    t6.program_section,

                                                    t7.student_subject_id as graded_student_subject_id,
                                                    t7.remarks

                                                    FROM student_subject AS t4 

                                                    LEFT JOIN subject_program AS t5 ON t5.subject_program_id = t4.subject_program_id
                                                    LEFT JOIN course AS t6 ON t6.course_id = t4.course_id
                                                    LEFT JOIN student_subject_grade AS t7 ON t7.student_subject_id = t4.student_subject_id

                                                    WHERE t4.student_id=:student_id
                                                    AND t4.enrollment_id=:enrollment_id

                                                    ");

                                                    $query->bindValue(":student_id", $student_id); 
                                                    $query->bindValue(":enrollment_id", $retake_enrollment_id); 
                                                    $query->execute(); 

                                                    if($query->rowCount() > 0){

                                                        while($row_inner = $query->fetch(PDO::FETCH_ASSOC)){
                                                            $student_subject_code = $row_inner['student_subject_code'];
                                                            $sp_subjectCode = $row_inner['sp_subjectCode'];
                                                            $subject_title = $row_inner['subject_title'];
                                                            
                                                            $subject_type = $row_inner['subject_type'];
                                                            $unit = $row_inner['unit'];
                                                            $program_section = $row_inner['program_section'];
                                                            $remarks = $row_inner['remarks'];


                                                            $student_subject_code = $row_inner['student_subject_code'];


                                                            $student_subject_id = $row_inner['student_subject_id'];
                                                            $is_final = $row_inner['is_final'];

                                                            
                                                            $graded_student_subject_id = $row_inner['graded_student_subject_id'];

                                                            $remarks_url = "Pending";

                                                            // echo $student_subject_id;
                                                            // echo "<br>";

                                                            // $subject_code = "";

                                                            if ($student_subject_code != null && $is_final == 1) {

                                                                $subject_code = $student_subject_code;

                                                                if ($student_subject_id != $graded_student_subject_id) {

                                                                    // echo "qwe";
                                                                    $remarkAsPassed = "RemarkAsPassed($student_subject_id, $student_id, \"Passed\", \"$subject_title\")";
                                                                    
                                                                    $remarks_url = "
                                                                        <i style='color:blue; cursor:pointer;' 
                                                                        onclick='$remarkAsPassed' class='fas fa-marker'></i>
                                                                    ";
                                                                }
                                                                if ($student_subject_id == $graded_student_subject_id) {

                                                                    $remarks_url = "
                                                                        $remarks
                                                                    ";
                                                                }
                                                            }

                                                            $db_enrollment_id = $row_inner['enrollment_id'];
                                                            $db_is_transferee = $row_inner['is_transferee'];

                                                            // echo $db_is_transferee;

                                                            if($db_enrollment_id == NULL 
                                                                    && $db_is_transferee == 1 && $is_final == 1){
                                                                $program_section = "-";
                                                                $remarks_url = "Credited";
                                                            }

                                                            $program_section = $is_final == 0 ? "" : $program_section;

                                                            
                                                            // $remarks = "";

                                                            echo "
                                                                <tr>
                                                                    <td>$subject_title</td>
                                                                    <td>$subject_code</td>
                                                                    <td>$subject_type</td>
                                                                    <td>$unit</td>
                                                                    <td>$program_section</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>$remarks_url</td>
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
        <?php

    }
?>



<script>
    var saveBtn = document.querySelector('#savePDF');

    // saveBtn.addEventListener('click', function () {
    //     // Capture the HTML element you want to convert to PDF
    //     var element = document.querySelector('#firstSemCapture');

    //     // Create options for the PDF generation
    //     var opt = {
    //         margin: 0,
    //         filename: '123456.pdf', // Set the filename for the generated PDF
    //         image: { type: 'png', quality: 1 },
    //         html2canvas: { scale: 2 },
    //         jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
    //     };

    //     // Generate the PDF from the HTML element
    //     html2pdf().from(element).set(opt).save();
    // });

    const pdfForm = document.getElementById('pdfForm');
    pdfForm.addEventListener('submit', (event) => {
        event.preventDefault();
        
        // Open a new browser window or tab when the form is submitted
        // window.open('new_page.html', '_blank');
        
        // Submit the form after opening the new window
        pdfForm.submit();
    });

    saveBtn.addEventListener('click', function () {
    // Capture the HTML element you want to convert to PDF
    var element = document.querySelector('#firstSemCapture');

    // Define the font size you want for the table tbody content
    var fontSize = '10px'; // You can adjust this value as needed

    // Apply the font size to the table tbody content using CSS
    var tbodyElements = element.querySelectorAll('table tbody *');
    tbodyElements.forEach(function (el) {
        el.style.fontSize = fontSize;
    });

    // Create options for the PDF generation
    var opt = {
        margin: 0,
        filename: '123456.pdf', // Set the filename for the generated PDF
        image: { type: 'png', quality: 1 },
        html2canvas: { scale: 2 },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
    };

    // Generate the PDF from the HTML element
    html2pdf().from(element).set(opt).save();

    // Reset font size after generating the PDF (optional)
    tbodyElements.forEach(function (el) {
        el.style.fontSize = ''; // Reset to default
    });
});









</script>