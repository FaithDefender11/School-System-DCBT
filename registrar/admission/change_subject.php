<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Enrollment.php');
        
    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


    if(isset($_GET['id'])){

        $student_subject_id = $_GET['id'];

        $student_subject = new StudentSubject($con, $student_subject_id);

        $student_subject_course_id = $student_subject->GetStudentSubjectCourseId();
        $student_subject_program_id = $student_subject->GetStudentSubjectProgramId();
        $student_subject_enrollment_id = $student_subject->GetStudentSubjectEnrollmentId();
        $student_subject_student_id = $student_subject->GetStudentSubjectStudentId();

        $studentSubjectCode = $student_subject->GetStudentSubjectCode();

        $section = new Section($con, $student_subject_course_id);
        $student = new Student($con, $student_subject_student_id);
        $student_id = $student->GetStudentId();

        $section_name = $section->GetSectionName();

        $student_course_id = $student->GetStudentCurrentCourseId();

        $section_program_id = $section->GetSectionProgramId($student_subject_course_id);

        $enrollment = new Enrollment($con);

        $student_enrollment_status = $enrollment->CheckEnrollmentEnrolledStatus($student_id, $current_school_year_id,
            $student_subject_enrollment_id);

            // echo $student_enrollment_status;

        $sectionDropdown = $section->CreateSectionSubjectDropdownProgramBased(
            $section_program_id, $student_subject_course_id, "Available Subject",
            $current_school_year_id, $section, $current_school_year_period,
            $student_subject_program_id, $current_school_year_term);


        $back_url= "process_enrollment.php?subject_review=show&st_id=$student_subject_student_id&selected_course_id=$student_course_id";

        // http://localhost/school-system-dcbt/registrar/admission/process_enrollment.php?
        // subject_review=show&st_id=544&selected_course_id=816


        if(isset($_POST['change_subject_btn' . $student_subject_id])
            && isset($_POST['course_id'])){


            $selected_course_id = $_POST['course_id'];

            $changesSuccess = $student_subject->ChangingStudentSubjectCourseId(
                $student_subject_enrollment_id, $student_subject_course_id,
                $student_subject_student_id, $current_school_year_id,
                $selected_course_id, $student_subject_id,
                $student_subject_program_id, $student_enrollment_status
            );

            if($changesSuccess){
                Alert::success("Changing section subject success", $back_url);
                exit();
            }
        }

        ?>

            <div class='col-md-12 row'>
                <div class='col-md-10 offset-md-1'>
                    <div class='card'>
                        <hr>
                        <a style="margin-left: 10px;" href="<?php echo $back_url;?>">
                            <button class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </a>
                        <div class='card-header'>
                            <h4 Changing Section class='text-center mb-3'></h4>
                        </div>

                        <div class="card-body">
                            <form method="POST">

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Current Subject Code</label>

                                    <input style="pointer-events: none;" class='form-control' type='text' 
                                        value="<?php echo $studentSubjectCode;?>" placeholder='' name='section_name'>
                                </div>

                                <?php echo $sectionDropdown;?>

                                <div class="modal-footer">

                                    <button 
                                    

                                    type='submit' class='btn btn-success'
                                     name='change_subject_btn<?php echo $student_subject_id;?>'>Save Changes</button>

                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }
?>  

<!-- onclick="return confirm('Are you sure you want to change section? This can\'t be undone.');" -->