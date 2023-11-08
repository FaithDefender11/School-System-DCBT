<?php

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/StudentSubjectGrade.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SubjectProgram.php');

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


    if(isset($_GET['st_id'])
        && isset($_GET['sg_id'])){

        $student_id = $_GET['st_id'];
        $student_subject_grade_id = $_GET['sg_id'];
        $student_grade = new StudentSubjectGrade($con, $student_subject_grade_id);


        $firstGrading = $student_grade->GetFirstQuarterGrade();
        $secondGrading = $student_grade->GetSecondQuarterGrade();
        $thirdGrading = $student_grade->GetThirdQuarterGrade();
        $fourthGrading = $student_grade->GetFourthQuarterGrade();
        $remarks = $student_grade->GetRemarks();

        $mean = round(85.5); // Round the mean to the nearest whole number

        // var_dump($mean);

        $getStudentSubjectId = $student_grade->GetStudentSubjectId();

        
        $student_subject = new StudentSubject($con, $getStudentSubjectId);

        $student_enrollment_id = $student_subject->GetEnrollmentId();

        $enrollment = new Enrollment($con);


        $enrollment_status = $enrollment->GetEnrollmentFormStudentStatus($student_id, $student_enrollment_id);


        $enrolled_student_subject_code = $student_subject->GetStudentSubjectCode();
        $subject_program_id = $student_subject->GetStudentSubjectProgramId();

        $subjectProgram = new SubjectProgram($con, $subject_program_id);

        $subject_title = $subjectProgram->GetTitle();

        $student = new Student($con, $student_id);

        $studentStatus = $student->GetStudentStatus();

        // var_dump($enrollment_status);

    
        $student_name = $student->GetFullName();
        $back_url = "teaching_code.php?c=$enrolled_student_subject_code&id=$current_school_year_id";

        # Check student failed subject count within semester.
        $student_subject_failed_count = $student_grade->GetStudentFailedSubjectsCount(
            $current_school_year_id, $getStudentSubjectId, $student_id, $current_school_year_id);

        // var_dump($remarks);
        // echo "Subject failed count: $student_subject_failed_count";
        // echo "<br>";
        // echo "<br>";

        // echo "Current remark: $remarks";
        // echo "<br>";

        // echo "Student status: $studentStatus";
        // echo "<br>";
        

        $sampleremarks = "";



        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['editGradeCode_' . $student_subject_grade_id])){

            $first_quarter_input = $_POST['first_quarter_input'];
            $second_quarter_input = $_POST['second_quarter_input'];
            $third_quarter_input = $_POST['third_quarter_input'];
            $fourth_quarter_input = $_POST['fourth_quarter_input'];

            // $remark = isset($_POST['remark']) ? $_POST['remark'] : NULL;
 
            $remark = isset($_POST['remark']) && $_POST['remark'] == 0 ? NULL : $_POST['remark'];

            // var_dump($remark);
            // return;

            $generatedRemarks = NULL;

            $mean = NULL;
            

            if($first_quarter_input != 0 
                && $second_quarter_input != 0
                && $third_quarter_input != 0
                && $fourth_quarter_input != 0
            ){

                $totalGrade = $first_quarter_input + $second_quarter_input 
                    + $third_quarter_input + $fourth_quarter_input;

                // $mean = ($totalGrade / 4);
                $mean = round($totalGrade / 4); // Round the mean to the nearest whole number
                if($mean >= 75){
                    $generatedRemarks = "Passed";
                }else{
                    $generatedRemarks = "Failed";
                }

            }

            // var_dump($mean);
            // echo "<br>";
            // var_dump($generatedRemarks);
            // return;

            $wasSuccess = $student_grade->UpdateGradeForSubjectCode(
                $student_subject_grade_id,
                $student_id,
                $teacherLoggedInId,


                $first_quarter_input,
                $second_quarter_input,
                $third_quarter_input,
                $fourth_quarter_input, 
                $generatedRemarks
            );

            if($generatedRemarks != NULL && $wasSuccess){


                # Drawback Scenario.

                # If student has only one failed subjects and from this teacher 
                # and decided to mark him as Passed, it should back to the Regular


                if($student_subject_failed_count <= 1 
                    && $enrollment_status == "Regular"
                    ## Current remarks
                    && $remarks == "Failed" ){

                    if($generatedRemarks == "Passed"){

                        # Student status could be back to Regular ( previous status on student table ).
                        $changeIntoRegular = $student->UpdateStudentToBeRegular($student_id);

                        if($changeIntoRegular){

                            Alert::success("Successfully input the grade. Average is >= 75, Grade remarks is $generatedRemarks, Student status is now back to Regular", $back_url);
                            exit();
                        }

                    }

                }

                if($generatedRemarks == "Failed"){

                    # Update student_status into Irregular.
                    $changeIntoIrregular = $student->UpdateStudentToBeIrregular($student_id);

                    if($changeIntoIrregular){
                        Alert::success("Successfully input the grade. Average is <= 74, Grade remarks is $generatedRemarks, Student status is now Irregular.", $back_url);
                        exit();
                    }
                }


                // Alert::success("Successfully input the grade. Grade remarks is $generatedRemarks", $back_url);
                // exit();

            }
            
            # As long as greater than to 1, it could not be back into previous student student_status
            
            if($generatedRemarks != NULL && $wasSuccess){
                Alert::success("Successfully input the grade, has remarks of $generatedRemarks", $back_url);
                exit();
            }

            if($generatedRemarks == NULL && $wasSuccess){
                Alert::success("Successfully input the grade, dont have remarks yet", $back_url);
                exit();
            }
        }

        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                <div style="min-width: 750px; margin-top: -5px" class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class='modal-title text-center'>Editing Grades of: <span id="modalStudentName"><?php echo $student_name;?> in <span style="font-weight: bold;" class="text-primary"><?= $subject_title?></span> subject</span></h4>
                            
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form method="POST">
                            <div class="modal-body">

                                <div id="errorMessage" class="alert alert-warning d-none"></div>

                                <div class="form-group">
                                    <div class="col-md-12 row">
                                        <label class="col-md-4 control-label" for="first_quarter_input">First Grading:</label>

                                        <div class="col-md-6">
                                            <input maxlength="3" class="form-control input-sm" id="first_quarter_input" name="first_quarter_input" placeholder="First Grading" type="text" value="<?php echo $firstGrading; ?>" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-12 row">
                                        <label class="col-md-4 control-label" for="second_quarter_input">Second Grading:</label>

                                        <div class="col-md-6">
                                            <input maxlength="3" class="form-control input-sm" id="second_quarter_input" name="second_quarter_input" placeholder="First Grading" type="text" value="<?php echo $secondGrading; ?>" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>



                                
                                <div class="form-group">
                                    <div class="col-md-12 row">
                                        <label class="col-md-4 control-label" for="third_quarter_input">Third Grading:</label>

                                        <div class="col-md-6">
                                            <input maxlength="3" class="form-control input-sm" id="third_quarter_input" name="third_quarter_input" placeholder="First Grading" type="text" value="<?php echo $thirdGrading; ?>" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-12 row">
                                        <label class="col-md-4 control-label" for="fourth_quarter_input">Fourth Grading:</label>

                                        <div class="col-md-6">
                                            <input maxlength="3" class="form-control input-sm" na id="fourth_quarter_input" name="fourth_quarter_input" placeholder="" type="text" value="<?php echo $fourthGrading; ?>" autocomplete="off" required>
                                        </div>
                                    </div>
                                </div>

                                <div style="display: none;" class="form-group">
                                    <div class="col-md-12 row">
                                        <label for="remark" class="col-md-4 control-label"
                                            >Remark:</label>

                                        <div class="col-md-6">
                                            <select required class="form-control" 
                                                name="remark" id="remark">
                                                <!-- <option value="" selected disabled>Choose</option> -->
                                                <option value="0">Reset</option>
                                                <option value="Passed" <?php echo $remarks === "Passed" ? "selected" : "" ?>>Passed</option>
                                                <option value="Failed" <?php echo $remarks === "Failed" ? "selected" : "" ?>>Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>


                            </div>

                            <div class="modal-footer">

                                <button type="submit" name="editGradeCode_<?php echo $student_subject_grade_id?>" class="default clean large">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        <?php
    }
?>
