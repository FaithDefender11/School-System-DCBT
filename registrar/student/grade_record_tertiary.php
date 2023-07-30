<?php 


    $FIRST_YEAR = 1;
    $SECOND_YEAR = 2;
    $THIRD_YEAR = 3;
    $FOURTH_YEAR = 4;

    $FIRST_SEMESTER = "First";
    $SECOND_SEMESTER = "Second";


    $enrollmentRecordDetails1_1st = $enrollment->getEnrollmentSectionDetails($student_id,
            $FIRST_YEAR, $FIRST_SEMESTER);

    if($enrollmentRecordDetails1_1st != null){

        $enrollment_date_approved1_1st = $enrollmentRecordDetails1_1st['enrollment_date_approved'];
        $enrollment_section_acronym1_1st = $enrollmentRecordDetails1_1st['enrollment_section_acronym'];
        $enrollment_section_level1_1st = $enrollmentRecordDetails1_1st['enrollment_section_level'];
        $enrollment_period1_1st = $enrollmentRecordDetails1_1st['enrollment_period'];
        $enrollment_student_status1_1st = $enrollmentRecordDetails1_1st['enrollment_student_status'];
    }

    $enrollmentRecordDetails1_2nd = $enrollment->getEnrollmentSectionDetails($student_id,
            $FIRST_YEAR, $SECOND_SEMESTER);

    if($enrollmentRecordDetails1_2nd != null){

        $enrollment_date_approved1_2nd = $enrollmentRecordDetails1_2nd['enrollment_date_approved'];
        $enrollment_section_acronym1_2nd = $enrollmentRecordDetails1_2nd['enrollment_section_acronym'];
        $enrollment_section_level1_2nd = $enrollmentRecordDetails1_2nd['enrollment_section_level'];
        $enrollment_period1_2nd = $enrollmentRecordDetails1_2nd['enrollment_period'];
        $enrollment_student_status1_2nd = $enrollmentRecordDetails1_2nd['enrollment_student_status'];
    }


    $enrollmentRecordDetails2_1st = $enrollment->getEnrollmentSectionDetails($student_id,
            $FIRST_YEAR, $FIRST_SEMESTER);

    if($enrollmentRecordDetails2_1st != null){

        $enrollment_date_approved2_1st = $enrollmentRecordDetails2_1st['enrollment_date_approved'];
        $enrollment_section_acronym2_1st = $enrollmentRecordDetails2_1st['enrollment_section_acronym'];
        $enrollment_section_level2_1st = $enrollmentRecordDetails2_1st['enrollment_section_level'];
        $enrollment_period2_1st = $enrollmentRecordDetails2_1st['enrollment_period'];
        $enrollment_student_status2_1st = $enrollmentRecordDetails2_1st['enrollment_student_status'];
    }


    $enrollmentRecordDetails2_2nd = $enrollment->getEnrollmentSectionDetails($student_id,
            $FIRST_YEAR, $FIRST_SEMESTER);

    if($enrollmentRecordDetails2_2nd != null){

        $enrollment_date_approved2_2nd = $enrollmentRecordDetails2_2nd['enrollment_date_approved'];
        $enrollment_section_acronym2_2nd = $enrollmentRecordDetails2_2nd['enrollment_section_acronym'];
        $enrollment_section_level2_2nd = $enrollmentRecordDetails2_2nd['enrollment_section_level'];
        $enrollment_period2_2nd = $enrollmentRecordDetails2_2nd['enrollment_period'];
        $enrollment_student_status2_2nd = $enrollmentRecordDetails2_2nd['enrollment_student_status'];
    }


    $enrollmentRecordDetails3_1st = $enrollment->getEnrollmentSectionDetails($student_id,
            $FIRST_YEAR, $FIRST_SEMESTER);

    if($enrollmentRecordDetails3_1st != null){

        $enrollment_date_approved3_1st = $enrollmentRecordDetails3_1st['enrollment_date_approved'];
        $enrollment_section_acronym3_1st = $enrollmentRecordDetails3_1st['enrollment_section_acronym'];
        $enrollment_section_level3_1st = $enrollmentRecordDetails3_1st['enrollment_section_level'];
        $enrollment_period3_1st = $enrollmentRecordDetails3_1st['enrollment_period'];
        $enrollment_student_status3_1st = $enrollmentRecordDetails3_1st['enrollment_student_status'];
    }

    $enrollmentRecordDetails3_2nd = $enrollment->getEnrollmentSectionDetails($student_id,
            $FIRST_YEAR, $FIRST_SEMESTER);

    if($enrollmentRecordDetails3_2nd != null){

        $enrollment_date_approved3_2nd = $enrollmentRecordDetails3_2nd['enrollment_date_approved'];
        $enrollment_section_acronym3_2nd = $enrollmentRecordDetails3_2nd['enrollment_section_acronym'];
        $enrollment_section_level3_2nd = $enrollmentRecordDetails3_2nd['enrollment_section_level'];
        $enrollment_period3_2nd = $enrollmentRecordDetails3_2nd['enrollment_period'];
        $enrollment_student_status3_2nd = $enrollmentRecordDetails3_2nd['enrollment_student_status'];
    }


    $enrollmentRecordDetails4_1st = $enrollment->getEnrollmentSectionDetails($student_id,
            $FIRST_YEAR, $FIRST_SEMESTER);

    if($enrollmentRecordDetails4_1st != null){

        $enrollment_date_approved4_1st = $enrollmentRecordDetails4_1st['enrollment_date_approved'];
        $enrollment_section_acronym4_1st = $enrollmentRecordDetails4_1st['enrollment_section_acronym'];
        $enrollment_section_level4_1st = $enrollmentRecordDetails4_1st['enrollment_section_level'];
        $enrollment_period4_1st = $enrollmentRecordDetails4_1st['enrollment_period'];
        $enrollment_student_status4_1st = $enrollmentRecordDetails4_1st['enrollment_student_status'];
    }

    $enrollmentRecordDetails4_2nd = $enrollment->getEnrollmentSectionDetails($student_id,
            $FIRST_YEAR, $FIRST_SEMESTER);

    if($enrollmentRecordDetails4_2nd != null){

        $enrollment_date_approved4_2nd = $enrollmentRecordDetails4_2nd['enrollment_date_approved'];
        $enrollment_section_acronym4_2nd = $enrollmentRecordDetails4_2nd['enrollment_section_acronym'];
        $enrollment_section_level4_2nd = $enrollmentRecordDetails4_2nd['enrollment_section_level'];
        $enrollment_period4_2nd = $enrollmentRecordDetails4_2nd['enrollment_period'];
        $enrollment_student_status4_2nd = $enrollmentRecordDetails4_2nd['enrollment_student_status'];
    }


?>


    <main>
    
        <!-- GR First Year 1st Semester -->
        <div class="floating">

            <?php 

                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                    $student_id, $FIRST_YEAR, $FIRST_SEMESTER);

                Helper::renderGradeRecordHeader($enrollment_school_year,  "1st Year 1st Semester");
    
            ?>
            
            <main>
                <div style="
                    display: flex;
                    justify-content: space-around;
                    gap: 10px;
                    flex-wrap: wrap;
                    text-align: center" class="cards">
                    <?php
                        Helper::renderCard('Semester', $enrollment_period1_1st);
                        Helper::renderCard('Grade level', $enrollment_section_level1_1st);
                        Helper::renderCard('Strand', $enrollment_section_acronym1_1st);
                        Helper::renderCard('Scholastic Status', $enrollment_student_status1_1st);
                        Helper::renderCard('Added', $enrollment_date_approved1_1st);
                    ?>
                </div>
                
                <table class="a">
                    <thead>
                        <tr> 
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th rowspan="2">Unit</th>
                            <th>Section</th>  
                            <th>Prelim</th>  
                            <th>Midterm</th>  
                            <th>Pre-Final</th>  
                            <th>Final</th>  
                            <th>Average</th>  
                            <th >Remarks</th>  
                        </tr>
                    </thead> 	
                    <tbody>
                        <?php 
                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                $FIRST_YEAR, $FIRST_SEMESTER);

                            $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                $checkEnrollmentEnrolled, $student_id);
                        ?>
                    </tbody>
                </table>

                
            </main>
        </div>

        <!-- GR First Year 2nd Semester -->
        <div class="floating">

            <?php 

                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                    $student_id, $FIRST_YEAR, $SECOND_SEMESTER);

                Helper::renderGradeRecordHeader($enrollment_school_year, "1st Year 2nd Semester");
    
            ?>
            
            <main>
                <div style="
                    display: flex;
                    justify-content: space-around;
                    gap: 10px;
                    flex-wrap: wrap;
                    text-align: center" class="cards">
                    <?php
                        Helper::renderCard('Semester', $enrollment_period1_2nd ?? "-");
                        Helper::renderCard('Grade level', $enrollment_section_level1_2nd ?? "-");
                        Helper::renderCard('Strand', $enrollment_section_acronym1_2nd ?? "-");
                        Helper::renderCard('Scholastic Status', $enrollment_student_status1_2nd ?? "-");
                        Helper::renderCard('Added', $enrollment_date_approved1_2nd ?? "-");
                    ?>
                </div>
                
                <table class="a">
                    <thead>
                        <tr> 
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th rowspan="2">Unit</th>
                            <th>Section</th>  
                            <th>Prelim</th>  
                            <th>Midterm</th>  
                            <th>Pre-Final</th>  
                            <th>Final</th>  
                            <th>Average</th>  
                            <th >Remarks</th>  
                        </tr>
                    </thead> 	
                    <tbody>
                        <?php 
                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                $FIRST_YEAR, $SECOND_SEMESTER);

                            $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                $checkEnrollmentEnrolled, $student_id);
                        ?>
                    </tbody>
                </table>

                
            </main>
        </div>


        <!-- GR Second Year 1st Semester -->
        <div class="floating">

            <?php 

                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                    $student_id, $SECOND_YEAR, $FIRST_SEMESTER);

                Helper::renderGradeRecordHeader($enrollment_school_year, "2nd Year 1st Semester");
    
            ?>
            
            <main>
                <div style="
                    display: flex;
                    justify-content: space-around;
                    gap: 10px;
                    flex-wrap: wrap;
                    text-align: center" class="cards">
                    <?php
                        Helper::renderCard('Semester', $enrollment_period2_1st ?? "-");
                        Helper::renderCard('Grade level', $enrollment_section_level2_1st ?? "-");
                        Helper::renderCard('Strand', $enrollment_section_acronym2_1st ?? "-");
                        Helper::renderCard('Scholastic Status', $enrollment_student_status2_1st ?? "-");
                        Helper::renderCard('Added', $enrollment_date_approved2_1st ?? "-");
                    ?>
                </div>
                
                <table class="a">
                    <thead>
                        <tr> 
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th rowspan="2">Unit</th>
                            <th>Section</th>  
                            <th>Prelim</th>  
                            <th>Midterm</th>  
                            <th>Pre-Final</th>  
                            <th>Final</th>  
                            <th>Average</th>  
                            <th >Remarks</th>  
                        </tr>
                    </thead> 	
                    <tbody>
                        <?php 
                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                $SECOND_YEAR, $FIRST_SEMESTER);

                            $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                $checkEnrollmentEnrolled, $student_id);
                        ?>
                    </tbody>
                </table>

                
            </main>
        </div>

        <!-- GR Second Year 2nd Semester -->
        <div class="floating">

            <?php 

                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                    $student_id, $SECOND_YEAR, $SECOND_SEMESTER);

                Helper::renderGradeRecordHeader($enrollment_school_year, "2nd Year 2nd Semester");
    
            ?>
            
            <main>
                <div style="
                    display: flex;
                    justify-content: space-around;
                    gap: 10px;
                    flex-wrap: wrap;
                    text-align: center" class="cards">
                    <?php
                        Helper::renderCard('Semester', $enrollment_period2_2nd ?? "-");
                        Helper::renderCard('Grade level', $enrollment_section_level2_2nd ?? "-");
                        Helper::renderCard('Strand', $enrollment_section_acronym2_2nd ?? "-");
                        Helper::renderCard('Scholastic Status', $enrollment_student_status2_2nd ?? "-");
                        Helper::renderCard('Added', $enrollment_date_approved2_2nd ?? "-");
                    ?>
                </div>
                
                <table class="a">
                    <thead>
                        <tr> 
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th rowspan="2">Unit</th>
                            <th>Section</th>  
                            <th>Prelim</th>  
                            <th>Midterm</th>  
                            <th>Pre-Final</th>  
                            <th>Final</th>  
                            <th>Average</th>  
                            <th >Remarks</th>  
                        </tr>
                    </thead> 	
                    <tbody>
                        <?php 
                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                $SECOND_YEAR, $SECOND_SEMESTER);

                            $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                $checkEnrollmentEnrolled, $student_id);
                        ?>
                    </tbody>
                </table>

                
            </main>
        </div>

        <!-- GR Third Year 1st Semester -->
        <div class="floating">

            <?php 

                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                    $student_id, $THIRD_YEAR, $FIRST_SEMESTER);

                Helper::renderGradeRecordHeader($enrollment_school_year, "3rd Year 1st Semester");
    
            ?>
            
            <main>
                <div style="
                    display: flex;
                    justify-content: space-around;
                    gap: 10px;
                    flex-wrap: wrap;
                    text-align: center" class="cards">
                    <?php
                        Helper::renderCard('Semester', $enrollment_period3_1st ?? "-");
                        Helper::renderCard('Grade level', $enrollment_section_level3_1st ?? "-");
                        Helper::renderCard('Strand', $enrollment_section_acronym3_1st ?? "-");
                        Helper::renderCard('Scholastic Status', $enrollment_student_status3_1st ?? "-");
                        Helper::renderCard('Added', $enrollment_date_approved3_1st ?? "-");
                    ?>
                </div>
                
                <table class="a">
                    <thead>
                        <tr> 
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th rowspan="2">Unit</th>
                            <th>Section</th>  
                            <th>Prelim</th>  
                            <th>Midterm</th>  
                            <th>Pre-Final</th>  
                            <th>Final</th>  
                            <th>Average</th>  
                            <th >Remarks</th>  
                        </tr>
                    </thead> 	
                    <tbody>
                        <?php 
                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                $THIRD_YEAR, $FIRST_SEMESTER);

                            $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                $checkEnrollmentEnrolled, $student_id);
                        ?>
                    </tbody>
                </table>

                
            </main>
        </div>

        <!-- GR Third Year 2nd Semester -->
        <div class="floating">

            <?php 

                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                    $student_id, $THIRD_YEAR, $SECOND_SEMESTER);

                Helper::renderGradeRecordHeader($enrollment_school_year, "3rd Year 2nd Semester");
    
            ?>
            
            <main>
                <div style="
                    display: flex;
                    justify-content: space-around;
                    gap: 10px;
                    flex-wrap: wrap;
                    text-align: center" class="cards">
                    <?php
                        Helper::renderCard('Semester', $enrollment_period3_2nd ?? "-");
                        Helper::renderCard('Grade level', $enrollment_section_level3_2nd ?? "-");
                        Helper::renderCard('Strand', $enrollment_section_acronym3_2nd ?? "-");
                        Helper::renderCard('Scholastic Status', $enrollment_student_status3_2nd ?? "-");
                        Helper::renderCard('Added', $enrollment_date_approved3_2nd ?? "-");
                    ?>
                </div>
                
                <table class="a">
                    <thead>
                        <tr> 
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th rowspan="2">Unit</th>
                            <th>Section</th>  
                            <th>Prelim</th>  
                            <th>Midterm</th>  
                            <th>Pre-Final</th>  
                            <th>Final</th>  
                            <th>Average</th>  
                            <th >Remarks</th>  
                        </tr>
                    </thead> 	
                    <tbody>
                        <?php 
                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                $THIRD_YEAR, $SECOND_SEMESTER);

                            $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                $checkEnrollmentEnrolled, $student_id);
                        ?>
                    </tbody>
                </table>

                
            </main>
        </div>

        <!-- GR Fourth Year 1st Semester -->
        <div class="floating">

            <?php 

                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                    $student_id, $FOURTH_YEAR, $FIRST_SEMESTER);

                Helper::renderGradeRecordHeader($enrollment_school_year, "4th Year 1st Semester");
    
            ?>
            
            <main>
                <div style="
                    display: flex;
                    justify-content: space-around;
                    gap: 10px;
                    flex-wrap: wrap;
                    text-align: center" class="cards">
                    <?php
                        Helper::renderCard('Semester', $enrollment_period4_1st ?? "-");
                        Helper::renderCard('Grade level', $enrollment_section_level4_1st ?? "-");
                        Helper::renderCard('Strand', $enrollment_section_acronym4_1st ?? "-");
                        Helper::renderCard('Scholastic Status', $enrollment_student_status4_1st ?? "-");
                        Helper::renderCard('Added', $enrollment_date_approved4_1st ?? "-");
                    ?>
                </div>
                
                <table class="a">
                    <thead>
                        <tr> 
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th rowspan="2">Unit</th>
                            <th>Section</th>  
                            <th>Prelim</th>  
                            <th>Midterm</th>  
                            <th>Pre-Final</th>  
                            <th>Final</th>  
                            <th>Average</th>  
                            <th >Remarks</th>  
                        </tr>
                    </thead> 	
                    <tbody>
                        <?php 
                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                $FOURTH_YEAR, $FIRST_SEMESTER);

                            $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                $checkEnrollmentEnrolled, $student_id);
                        ?>
                    </tbody>
                </table>

                
            </main>
        </div>

        <!-- GR Fourth Year 2nd Semester -->
        <div class="floating">

            <?php 

                $enrollment_school_year = $enrollment->GetStudentSectionGradeLevelSemester(
                    $student_id, $FOURTH_YEAR, $SECOND_SEMESTER);

                Helper::renderGradeRecordHeader($enrollment_school_year, "4th Year 2nd Semester");
    
            ?>
            
            <main>
                <div style="
                    display: flex;
                    justify-content: space-around;
                    gap: 10px;
                    flex-wrap: wrap;
                    text-align: center" class="cards">
                    <?php
                        Helper::renderCard('Semester', $enrollment_period4_2nd ?? "-");
                        Helper::renderCard('Grade level', $enrollment_section_level4_2nd ?? "-");
                        Helper::renderCard('Strand', $enrollment_section_acronym4_2nd ?? "-");
                        Helper::renderCard('Scholastic Status', $enrollment_student_status4_2nd ?? "-");
                        Helper::renderCard('Added', $enrollment_date_approved4_2nd ?? "-");
                    ?>
                </div>
                
                <table class="a">
                    <thead>
                        <tr> 
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Type</th>
                            <th rowspan="2">Unit</th>
                            <th>Section</th>  
                            <th>Prelim</th>  
                            <th>Midterm</th>  
                            <th>Pre-Final</th>  
                            <th>Final</th>  
                            <th>Average</th>  
                            <th >Remarks</th>  
                        </tr>
                    </thead> 	
                    <tbody>
                        <?php 
                            $enrolledSubjectsGradeLevelSemesterBased = $subject_program->GetStudentEnrolledSubjectCodeBase($student_program_id, $student_id,
                                $FOURTH_YEAR, $SECOND_SEMESTER);

                            $subject_program->GradeRecordsSHSBody($enrolledSubjectsGradeLevelSemesterBased,
                                $checkEnrollmentEnrolled, $student_id);
                        ?>
                    </tbody>
                </table>

                
            </main>
        </div>
    

    </main>

