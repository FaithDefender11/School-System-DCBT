<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/Student.php");
    require_once("../../includes/classes/SchoolYear.php");


    if(isset($_POST['selected_school_year_id'])){

        $selected_school_year_id = $_POST['selected_school_year_id'];
        $current_school_year_id = $_POST['current_school_year_id'];


        $section = new Section($con);
        $enrollment = new Enrollment($con);
        $student = new Student($con);

        $school_year = new SchoolYear($con, $selected_school_year_id);

        $selected_school_year_term = $school_year->GetTerm();
        $selected_school_year_period = $school_year->GetPeriod();


        $current_school_year = new SchoolYear($con, $current_school_year_id);

        $current_school_year_term = $current_school_year->GetTerm();
        $current_school_year_period = $current_school_year->GetPeriod();

        $now = date("Y-m-d H:i:s");

        #
        $selected_year_end_term_date = $school_year->GetfinalEndDate();

        # Check if selected A.Y Semester End term is lower than todays date.

        $selected_year_end_term_date = new DateTime($selected_year_end_term_date);
        $now = new DateTime(); // This defaults to the current date and time

        // echo "end_term_date: $end_term_date";
        // var_dump($now);
        // echo "<br>";
        // var_dump($selected_year_end_term_date);
        // echo "<br>";

        if ($selected_year_end_term_date > $now) {

            echo "school_year_changing_invalid";
            return;
        }
        // elseif ($selected_year_end_term_date < $now) {
        //     echo "selected_year_end_term_date is in the past.";
        // }
        // else {
        //     echo "selected_year_end_term_date is equal to the current date.";
        // }



        // return;

        // echo $current_school_year_period;

        // return;

        # Next S.Y Change only into Active
        $hasChangeIntoNextSY = $school_year->SetSchoolYearIdIntoActive(
                $selected_school_year_id);
        
        if($hasChangeIntoNextSY){

            // Current Year Period Should be InActive.
            $currentYearPeriodInActive = $school_year->SetCurrentYearPeriodInActive(
                $current_school_year_id);

            if($currentYearPeriodInActive){

                if($current_school_year_period == "First"){
                    
                    // Check if student in Grade Records has failed atleast one subject

                    // TODO. All IsFull In First Semester Sections should be reset.
                    
                    # ==
                    $resetSection = $section->ResetCurrentActiveSections($current_school_year_term);
                    
                    $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($current_school_year_id);
                    foreach ($currentNewEnrolled as $key => $student_ids) {
                        // All new enrolled student in the enrollment form will update as OLD
                        $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);
                    }


                    # ==
                    # First semester room transfered to 2nd Semester.
                    // $roomSectionTransfer = $section->SectionHasRoomTransfer(
                    //     $current_school_year_term, $current_school_year_period);
                    echo "success_update";
                    return;
                }

                if($current_school_year_period == "Second"){

                    // echo $current_school_year_term;
                    // return;

                    # ==

                    // # Second Semester Successful opened room move-up sections.
                    $movingUpSection = $section->MovingUpCurrentActiveSections($current_school_year_term);
                    
                    // # As finals end, there`s no reason to accomodate students in the section
                    // # InActive opened sections
                    $deactiveCurrentSection = $section->DeactiveCurrentActiveSections($current_school_year_term);
                    
                    // // Create each one section to be used for new students section.
                    // # Alternative, Admin create manual room.

                    // $createEachNewSection = $section->CreateEachSectionStrandCourse($current_school_year_term);

                    $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($current_school_year_id);
                    foreach ($currentNewEnrolled as $key => $student_ids) {
                        // All new enrolled student in the enrollment form will update as OLD
                        $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);
                    }

                    # ==

                    echo "success_update"; 
                    return;

                }
            }
        }

    }

?>