<?php 

    require_once("../../includes/config.php");

    require_once("../../includes/classes/StudentSubject.php");
    require_once("../../includes/classes/SubjectProgram.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/Room.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/Schedule.php");
    require_once("../../includes/classes/EnrollmentAudit.php");
    require_once("../../includes/classes/User.php");
    
    if (isset($_POST['subject_program_id'])
        && isset($_POST['current_school_year_id'])
        && isset($_POST['student_id'])
        && isset($_POST['student_enrollment_course_id'])
        && isset($_POST['enrollment_id'])
        && isset($_POST['course_id'])
        && isset($_POST['subject_schedule_arr'])
        && isset($_POST['doesFull'])
        
        ) {

        $registrarUserId = isset($_SESSION["registrarUserId"]) 
            ? $_SESSION["registrarUserId"] : "";

            

        $doesFull = intval($_POST['doesFull']);
        // echo "br";
        // var_dump($doesFull);
        // return;

        $subject_program_id = $_POST['subject_program_id'];

        $current_school_year_id = $_POST['current_school_year_id'];
        $student_id = $_POST['student_id'];
        $student_enrollment_course_id = $_POST['student_enrollment_course_id'];
        $enrollment_id = $_POST['enrollment_id'];
        $course_id = $_POST['course_id'];

        // $subject_schedule_arr = $_POST['subject_schedule_arr'];
        $subject_schedule_arr = json_decode($_POST['subject_schedule_arr'], true);

        $schedule = new Schedule($con);


        $sy = new SchoolYear($con, $current_school_year_id);

        // foreach ($subject_schedule_arr as $key => $schedule_ids) {
        //     # code...

        //     $schedule = new Schedule($con, $schedule_ids);

        //     $userTimeFrom = $schedule->GetTimeFrom();
        //     $userTimeTo = $schedule->GetTimeTo();
        //     $schedule_day = $schedule->GetScheduleDay();

        //     $check = $schedule->CheckScheduleConflictOnSubjectLoad(
        //         $userTimeFrom, $userTimeTo,
        //         $schedule_day, $current_school_year_id);

        //         var_dump($schedule_ids);
        // }

        // return;



        $current_semester = $sy->GetSchoolYearPeriod();

        $subject_program = new SubjectProgram($con, $subject_program_id);

        $subject_code = $subject_program->GetSubjectProgramRawCode();
        $subject_title = $subject_program->GetTitle();

        $pre_requisite_code = $subject_program->GetPreRequisiteSubjectByCode(
            $subject_code, $subject_program_id
        );

        $getAllCartSchedule = $schedule->GetAllScheduleSubjectLoadCart(
            null, $student_id,
            $current_school_year_id);

        // var_dump(count($get));

        $conflicted_schedule_id = NULL;
        $desired_schedule_id = NULL;
        $conflitectedArr = [];
        if(count($subject_schedule_arr) > 0){

            // var_dump(count($subject_schedule_arr));

            foreach ($subject_schedule_arr as $key => $schedule_idss) {
                # code...

                $schedule = new Schedule($con, $schedule_idss);

                $userTimeFrom = trim($schedule->GetTimeFrom());
                $userTimeTo = trim($schedule->GetTimeTo());
                $usersSchedule_day = trim($schedule->GetScheduleDay());

                // var_dump($schedule_idss);

                // $getAllCartSchedule = $schedule->GetAllScheduleSubjectLoadCart(
                //     null, $student_id,
                //     $current_school_year_id, $usersSchedule_day);

                if(count($getAllCartSchedule) > 0){

                    // var_dump(count($getAllCartSchedule));
                    
                    foreach ($getAllCartSchedule as $key => $val) {
                        # code...

                        $subject_schedule_id = $val['subject_schedule_id'];
                        $schedule_day = trim($val['schedule_day']);
                        $schedule = new Schedule($con, $subject_schedule_id);

                        # Existing schedules.

                        if($schedule_day == $usersSchedule_day){

                            $existingTimeFrom = trim($schedule->GetTimeFrom());
                            $existingTimeTo = trim($schedule->GetTimeTo());
                            $existingScheduleDay = trim($schedule->GetScheduleDay());

                            if (
                                ($userTimeFrom >= $existingTimeTo) ||
                                ($userTimeTo <= $existingTimeFrom) ||
                                ($existingTimeTo == $userTimeFrom) // Add this condition
                            ) {
                                array_push($conflitectedArr, $subject_schedule_id);
                                continue; // No conflict found, check the next schedule
                            } else {

                                // echo "subject_schedule_id: $subject_schedule_id";

                                // return true; // Conflict found

                                // Conflict schedule ID FOUND
                                // return $subject_schedule_id;
                                $conflicted_schedule_id = $subject_schedule_id;
                                $desired_schedule_id = $schedule_idss;
                                // $subject_schedule_id = $val['subject_schedule_id'];
                                // $schedule_day = trim($val['schedule_day']);
                                // $schedule = new Schedule($con, $subject_schedule_id);

                            }
                        }
                        
                    }

                }

            }
        }

        if($conflicted_schedule_id != NULL && $desired_schedule_id != NULL){

            // echo "cart conflicted_schedule_id: $conflicted_schedule_id conflicted to chosen $desired_schedule_id";

            $scheduleConflict = new Schedule($con, $conflicted_schedule_id);

            $time_from = $scheduleConflict->GetTimeFrom();
            $time_from = $scheduleConflict->convertTo12HourFormat($time_from);

            $time_to = $scheduleConflict->GetTimeTo();
            $time_to = $scheduleConflict->convertTo12HourFormat($time_to);

            $day = $scheduleConflict->GetScheduleDay();

            $cart_schedule_course_id = $scheduleConflict->GetScheduleCourseId();
            $cart_schedule_subject_program_id = $scheduleConflict->GetSubjectProgramId();


            $sectionCart = new Section($con, $cart_schedule_course_id);
            $conflicted_schedule_course_name = $sectionCart->GetSectionName();

            $spCart = new SubjectProgram($con, $cart_schedule_subject_program_id);
            $conflicted_schedule_subject = $spCart->GetTitle();

            # Desired schedule
            $desiredSchedule = new Schedule($con, $desired_schedule_id);

            $subject_code_conflict = $desiredSchedule->GetSubjectCode();
            $desired_schedule_day = $desiredSchedule->GetScheduleDay();
 
            $raw_time_from = $desiredSchedule->GetTimeFrom();
            $raw_time_from = $desiredSchedule->convertTo12HourFormat($raw_time_from);

            $raw_time_to = $desiredSchedule->GetTimeTo();
            $raw_time_to = $desiredSchedule->convertTo12HourFormat($raw_time_to);

            #

            $desired_schedule_course_id = $desiredSchedule->GetScheduleCourseId();
            $desired_schedule_subject_program_id = $desiredSchedule->GetSubjectProgramId();


            $sectionDesired = new Section($con, $desired_schedule_course_id);
            $desired_schedule_course_name = $sectionDesired->GetSectionName();

            $spDesired = new SubjectProgram($con, $desired_schedule_subject_program_id);
            $desired_schedule_subject = $spDesired->GetTitle();
            
            // "Teacher Conflicted Schedule: $time_from - $time_to <br> ( $day ) Room: $room_number <br> Code: $subject_code_conflict",

            $data[] = array(
                "output" => "conflicted_schedule",
                "conflicted_schedule_id" => $conflicted_schedule_id,
                "desired_schedule_id" => $desired_schedule_id,

                "conflicted_schedule_time_from" => $time_from,
                "conflicted_schedule_time_to" => $time_to,
                "conflicted_schedule_day" => $day,
                "conflicted_schedule_course_name" => $conflicted_schedule_course_name,
                "conflicted_schedule_subject" => $conflicted_schedule_subject,

                "conflicted_desired_schedule_time_from" => $raw_time_from,
                "conflicted_desired_schedule_time_to" => $raw_time_to,
                "conflicted_desired_schedule_day" => $desired_schedule_day,
                "conflicted_desired_schedule_course_name" => $desired_schedule_course_name,
                "conflicted_desired_schedule_subject" => $desired_schedule_subject,
            );

            echo json_encode($data);
            return; 

        }else{
            // echo "cart conflicted_schedule_id: $conflicted_schedule_id not conflicted";
        }

        // echo "<br>";
        // var_dump($conflitectedArr);

        // return;


        $section = new Section($con, $course_id);
        $student_subject = new StudentSubject($con);

        $sectionName = $section->GetSectionName();
        $student_enrollment_course_level = $section->GetSectionGradeLevel($student_enrollment_course_id);
 
        $student_subject_code = $section->CreateSectionSubjectCode($sectionName, $subject_code);
            // echo $student_subject_code;
        
        // Check if Subject Program ID is already inserted within semester.
        $alreadyInsertedThisSemester = $student_subject->CheckIfSubjectProgramAlreadyInsertedWithinSemester(
            $student_id, $subject_program_id,
            $current_school_year_id, $current_semester);

        // Check If Subject Program Code is already credited by the student records.
        // Within S.Y or Prev S.Y
        $checkIfSubjectAlreadyCredited = $student_subject->CheckIfSubjectAlreadyCredited(
            $student_id,
            $subject_code);

        // Check If Subject Program Code is already inserted
        // which registrar placed on different course/strand.
        // Example.
        // NSTP101 HUMMS -> ALREADY INSERTED
        // NSTP101 STEM -> HIT
        $checkIfSubjectAlreadyTaken = $student_subject->CheckIfSubjectAlreadyTaken(
            $student_id,
            $subject_code);

        $checkIfSubjectAlreadyPassed = $student_subject->CheckIfSubjectAlreadyPassed(
            $student_id,
            $subject_code);

        $checkIfSubjectPreRequisiteHasFailed = $student_subject->CheckIfSubjectPreRequisiteHasFailed(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);

            
        $checkIfPreRequisiteSubjectTakenPassed = $student_subject->CheckIfPreRequisiteSubjectTakenPassed(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);
        
        $checkIfSubjectCodeRetaken = $student_subject->CheckIfSubjectCodeRetaken(
            $student_id, $pre_requisite_code,
            $current_school_year_id, $subject_code);


        $checkIfChosenSubjectAlreadyCredited = $student_subject->CheckIfChosenSubjectAlreadyCredited(
            $student_id, $subject_code); 
            
        $checkIfPreRequisiteIsNotTaken = $student_subject->CheckIfPreRequisiteIsNotTaken(
            $student_id, $pre_requisite_code); 

        // var_dump($pre_requisite_code);
        // var_dump($checkIfPreRequisiteIsNotTaken);
        // var_dump($checkIfChosenSubjectAlreadyCredited);
        // return;

        $hasError = false;
        $hasErrorArr = [];
        $returnArr = [];

        // if($checkIfSubjectCodeRetaken == true){
        //     echo "taken_failed";
        //     array_push($hasErrorArr, "taken_failed");
        //     $hasError = true;
        // }

        if($doesFull == 1){

            // echo "full";
            // return;

            $hasError = true;

            array_push($hasErrorArr, "subject_is_full");
            array_push($returnArr, "subject_is_full");

            $data[] = array(
                "output" => "subject_is_full"
            );

            echo json_encode($data);
            return; 

        }

        if($checkIfChosenSubjectAlreadyCredited == true){

            // echo "already_credited";

            $hasError = true;
            array_push($hasErrorArr, "already_credited");
            array_push($returnArr, "already_credited");
            $data[] = array(
                "output" => "already_credited"
            );
            echo json_encode($data);
                return; 
        }

        // if($checkIfSubjectAlreadyCredited == true){
        //     echo "already_credited";
        //     $hasError = true;
        //     array_push($hasErrorArr, "already_credited");
        // }

        if($checkIfSubjectAlreadyTaken == true){
            // echo "subject_already_taken";
            // echo "already taken by different strand/course";
            $hasError = true;
            array_push($hasErrorArr, "subject_already_taken");
            array_push($returnArr, "subject_already_taken");
            $data[] = array(
                "output" => "subject_already_taken"
            );
            echo json_encode($data);
                return; 
        }
        if($checkIfSubjectAlreadyPassed == true){
            // echo "already_passed";
            $hasError = true;
            array_push($hasErrorArr, "already_passed");
            array_push($returnArr, "already_passed");

            $data[] = array(
                "output" => "already_passed"
            );
            echo json_encode($data);
                return; 
        }

        // Pre Requisite taken and failed
        if($checkIfSubjectPreRequisiteHasFailed == true
            && $checkIfSubjectPreRequisiteHasFailed != NULL
        ){

            // echo "failed_pre_requisite_of_selected_code";
            // echo "You had failed the subject $pre_requisite_code, so you cant get $subject_code";
            $hasError = true;
            array_push($hasErrorArr, "failed_pre_requisite_of_selected_code");
            array_push($returnArr, "failed_pre_requisite_of_selected_code");

            $data[] = array(
                "output" => "failed_pre_requisite_of_selected_code"
            );
            echo json_encode($data);
                return; 
        }

        if($checkIfPreRequisiteIsNotTaken == true
            && $checkIfChosenSubjectAlreadyCredited == false){
            
            // echo "subject_prerequisite_not_taken_and_not_credited";
            // echo "subject_prerequisite_not_taken";
            $hasError = true;
            array_push($hasErrorArr, "subject_prerequisite_not_taken");
            array_push($returnArr, "subject_prerequisite_not_taken");

            $data[] = array(
                "output" => "subject_prerequisite_not_taken"
            );
            echo json_encode($data);
                return; 
        }


        // Pre requisite is student subject grade is null

        // Chosen subject pre_requisite is not taken
        // And subject pre_requisite is not credited

        // if($checkIfPreRequisiteIsNotTaken == true
        //     && $checkIfChosenSubjectAlreadyCredited == false

        //     ){

        //     echo "subject_prerequisite_not_taken";
        //     array_push($hasErrorArr, "subject_prerequisite_not_taken");
        //     $hasError = true;
        // }


        // $subjectPrerequisiteNotTtaken = $student_subject->CheckIfPreRequisiteIsNotTakenEitherPassedOrCredited(
        //     $student_id, $pre_requisite_code);

        // if($subjectPrerequisiteNotTtaken == true){
        //     echo "subject_prerequisite_not_taken";
        //     array_push($hasErrorArr, "subject_prerequisite_not_taken");
        //     $hasError = true;
        // }

        if(empty($hasErrorArr) == true){

            // echo $hasError;
            // echo " without err";

            $enrollmentAudit = new EnrollmentAudit($con);

            $registrarName = "";
            if($registrarUserId != ""){
                $user = new User($con, $registrarUserId);
                $registrarName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
            }
            
            $now = date("Y-m-d H:i:s");
            $date_creation = date("M d, Y h:i a", strtotime($now));

            $school_year = new SchoolYear($con, null);
            $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
 
            $description = "Registrar '$registrarName' has been placed a subject load of '$subject_title ($student_subject_code)' on $date_creation";

            $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
                $enrollment_id,
                $description, $current_school_year_id, $registrarUserId
            );

            $insertSubjectLoad = $student_subject->InsertStudentSubjectNonFinal($student_id, $student_subject_code,
                $enrollment_id, $course_id, $subject_program_id,
                $current_school_year_id, $subject_code, $student_enrollment_course_level, $checkIfSubjectCodeRetaken);

            if($insertSubjectLoad == true){
            //    echo "add_success";
               $data[] = array(
                    "output" => "add_success"
                );
                echo json_encode($data);
                return; 
            //    return;
            }

        } 

        // Allow Subject to be taken if it was failed by the previous
        // if($checkIfSubjectAlreadyCredited == true){
        //     echo "already credited";
        // }else{
        //     echo "not credited";
        // }


 
    }
?>