<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/StudentSubjectGrade.php');

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

        $getStudentSubjectId = $student_grade->GetStudentSubjectId();

        
        $student_subject = new StudentSubject($con, $getStudentSubjectId);

        $enrolled_student_subject_code = $student_subject->GetStudentSubjectCode();
        $student = new Student($con, $student_id);

    
        $student_name = $student->GetFullName();
        $back_url = "subject_code_enrolled.php?id=$current_school_year_id&cd=$enrolled_student_subject_code";

        // if (isset($_POST['edit_subject_code_schedule_' . $schedule_id]) &&
        //     isset($_POST['teacher_id']) &&
        //     // isset($_POST['course_id']) &&
        //     // isset($_POST['room']) &&
        //     isset($_POST['schedule_day']) &&
        //     isset($_POST['time_from']) &&
        //     isset($_POST['time_to'])) {

        //     // $room = $_POST['room'];

        //     $schedule_day = $_POST['schedule_day'];
        //     $time_from_meridian = $_POST['time_from'];


        //     $date = DateTime::createFromFormat("h:i A", $time_from_meridian);

        //     // Format the DateTime object into military (24-hour) format
        //     $time_from_meridian_military = $date->format("H:i");

        //     // Output the result
        //     // echo "Time from Meridian: " . $time_from_meridian . "<br>";
        //     // echo "Military Time from: $time_from_meridian_military ";  // Output: "21:30"
        //     // echo "<br>";
        //     $time_from = str_replace(["AM", "PM"], "", $time_from_meridian);
        
        //     $time_to_meridian = $_POST['time_to'];

        //     $date = DateTime::createFromFormat("h:i A", $time_to_meridian);

        //     // Format the DateTime object into military (24-hour) format
        //     $time_to_meridian_military = $date->format("H:i");

        //     // echo "Time to Meridian: " . $time_to_meridian . "<br>";
        //     // echo "Military Time to: $time_to_meridian_military ";  // Output: "21:30"
        //     // echo "<br>";

        //     $time_to = str_replace(["AM", "PM"], "", $time_to_meridian);

        //     $schedule_time = $time_from . "-" . $time_to;
        //     // $course_id = $_POST['course_id'];
        //     $teacher_id = $_POST['teacher_id'];

        //     // $time_from_am_pm = $_POST['time_from_am_pm'];
        //     // $time_to_am_pm = $_POST['time_to_am_pm'];

        //     // echo "Room: " . $room . "<br>";
        //     // echo "Schedule Day: " . $schedule_day . "<br>";
        //     // echo "Time From: " . $time_from . "<br>";
        //     // echo "Time To: " . $time_to . "<br>";
        //     // echo "Schedule Time: " . $schedule_time . "<br>";
        //     // echo "Course ID: " . $schedule_course_id . "<br>";

        //     // if(false){
        //     if($schedule_course_id != 0){

        //         $teacher = new Teacher($con, $teacher_id);
        //         $new_teacher_fullname = $teacher->GetTeacherFullName();

        //         // Check if  teacher has already scheduled in the subject.

        //         // if($schedule->CheckIfTeacherAlreadyScheduleToTheSubject(
        //         //     $subject_id, $teacher_id) == true){

        //         //     Alert::error("Subject Code $section_subject_code has already been schedule to $new_teacher_fullname", 'create.php');
        //         //     exit();
        //         //     return;
        //         // }

        //         // $subject_program_id = 0;
        //         $scheduleUpdateSuccess = $schedule->UpdateScheduleCodeBase(
        //             $schedule_id, $time_from_meridian, $time_to_meridian,
        //             $schedule_day, $time_from_meridian_military, $time_to_meridian_military,
        //             $schedule_time, $current_school_year_id,
        //             $teacher_id, $section_subject_code
        //         );

        //         if($scheduleUpdateSuccess){
        //             Alert::success("Schedule Subject Code: $section_subject_code has been modified", $back_url);
        //             exit();
        //         }

        //     }
        // }

        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['editGradeCode_' . $student_subject_grade_id])
            ){

            $first_quarter_input = $_POST['first_quarter_input'];
            $second_quarter_input = $_POST['second_quarter_input'];
            $third_quarter_input = $_POST['third_quarter_input'];
            $fourth_quarter_input = $_POST['fourth_quarter_input'];
 
            // echo $student_subject_grade_id;
            // echo "<br>";
            // echo $student_id;

            $wasSuccess = $student_grade->UpdateGradeForSubjectCode(
                $student_subject_grade_id,
                $student_id,
                $first_quarter_input,
                $second_quarter_input,
                $third_quarter_input,
                $fourth_quarter_input
            );

            if($wasSuccess){
                Alert::success("Successfully changes made", $back_url);
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
                            <h4 class='modal-title text-center'>Editing Grades to: <span id="modalStudentName"><?php echo $student_name;?></span></h4>
                            
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <form method="POST">
                            <div class="modal-body">

                                <div id="errorMessage" class="alert alert-warning d-none"></div>

                                <div class="form-group">
                                    <div class="col-md-12 row">
                                        <label class="col-md-4 control-label" for="first_quarter_input">First Grading:</label>

                                        <div class="col-md-6">
                                            <input maxlength="3" class="form-control input-sm" id="first_quarter_input" name="first_quarter_input" placeholder="First Grading" type="text" value="<?php echo $firstGrading; ?>" autocomplete="off" required="">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-12 row">
                                        <label class="col-md-4 control-label" for="second_quarter_input">Second Grading:</label>

                                        <div class="col-md-6">
                                            <input maxlength="3" class="form-control input-sm" id="second_quarter_input" name="second_quarter_input" placeholder="First Grading" type="text" value="<?php echo $secondGrading; ?>" autocomplete="off" required="">
                                        </div>
                                    </div>
                                </div>



                                
                                <div class="form-group">
                                    <div class="col-md-12 row">
                                        <label class="col-md-4 control-label" for="third_quarter_input">Third Grading:</label>

                                        <div class="col-md-6">
                                            <input maxlength="3" class="form-control input-sm" id="third_quarter_input" name="third_quarter_input" placeholder="First Grading" type="text" value="<?php echo $fourthGrading; ?>" autocomplete="off" required="">
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-12 row">
                                        <label class="col-md-4 control-label" for="fourth_quarter_input">Fourth Grading:</label>

                                        <div class="col-md-6">
                                            <input maxlength="3" class="form-control input-sm" na id="fourth_quarter_input" name="fourth_quarter_input" placeholder="First Grading" type="text" value="<?php echo $thirdGrading; ?>" autocomplete="off" required="">
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
