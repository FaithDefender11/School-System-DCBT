
<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/WaitingList.php');


    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id'])){

        $student_id = $_GET['id'];

        $student = new Student($con, $student_id);
        $enrollment = new Enrollment($con);


        $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
            $current_school_year_id);
            
        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $student_enrollment_id, $current_school_year_id);


        // $student_course_id = $student->GetStudentCurrentCourseId();
        $student_level = $student->GetStudentLevel($student_id);

        $section = new Section($con, $student_enrollment_course_id);
        $sectionLevel = $section->GetSectionGradeLevel();

        $section_program_id = $section->GetSectionProgramId($student_enrollment_course_id);

        $program = new Program($con, $section_program_id);

        $program_name = $program->GetProgramName();

        $checkIdPrompt = $student->CheckIdExists($student_id);

        $semesterSectionHasRoomIds = $section->GetSectionIdHasRoomSemester($current_school_year_period,
            $current_school_year_term);

        $back_url = "index.php";

        if(isset($_POST['assign_waitlist_btn_' . $student_id])){

            $program_id = $_POST['program_id'];
            $course_level = $_POST['course_level'];
            $school_year_id = $_POST['school_year_id'];

        }
        
        ?>
        <div class='col-md-12 row '>
            <div class='col-md-10 offset-md-1'>

                <div class='card'>
                    <hr>

                    <a href="<?php echo $back_url; ?>">
                        <button class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>

                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Waiting List Maintenance</h4>
                    </div>

                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>
                            <div class='form-group mb-2'>
                                <label for='room_id'>* Program</label>
                                <select  class="form-control" name="program_id" >
                                    <option value="<?php echo $section_program_id;?>"><?php echo $program_name;?></option>
                                </select>
                            </div>

                            <div class='form-group mb-2'>
                                <label for='room_id'>* Course Level</label>
                                <input type="text" value="<?php echo $sectionLevel; ?>" name="course_level" class="form-control">
                            </div>

                            <div class='form-group mb-2'>
                                <label for='room_id'>* School Year</label>
                                <input style="pointer-events: none;" type="text" value="<?php echo $current_school_year_term?>" name="school_year_id" class="form-control">
                            </div>

                            <div class='form-group mb-2'>
                                <label for='room_id'>* Semester</label>
                                <input style="pointer-events: none;" type="text" value="<?php echo $current_school_year_period?>" name="period" class="form-control">
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='assign_waitlist_btn_<?php echo $student_id;?>'>Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php

    }


    if(isset($_GET['pending_id'])){

        $pending_new_enrollee_id = $_GET['pending_id'];

        echo $pending_new_enrollee_id;
    }
?>