<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/Student.php");


        // END Finals Date
    if(isset($_POST['school_year_id'])
        && isset($_POST['school_year_period'])
        && isset($_POST['name_period'])
        && $_POST['name_period'] == "finals"
        && isset($_POST['school_year_term'])){

        $school_year_id = $_POST['school_year_id'];
        $school_year_period = $_POST['school_year_period'];
        $school_year_term = $_POST['school_year_term'];

        $name_period = $_POST['name_period'];


        $section = new Section($con);

        $now = date("Y-m-d H:i:s");

        $end_finals_date = $con->prepare("UPDATE school_year

                SET final_exam_enddate=:final_exam_enddate,
                    final_exam_ended=:final_exam_ended

                WHERE school_year_id=:school_year_id");
        
        $end_finals_date->bindValue(":final_exam_enddate", $now);
        $end_finals_date->bindValue(":final_exam_ended", 1);
        $end_finals_date->bindParam(":school_year_id", $school_year_id);

        if($end_finals_date->execute()){

            $student = new Student($con);
            $enrollment = new Enrollment($con);

            if($school_year_period == "First"){
                
                // Check if student in Grade Records has failed atleast one subject
                // 
                // If Regular, Mark them as Irregular. TODO In Irregular Enrollment

                // TODO. All IsFull In First Semester Sections should be reset.
                
                $resetSection = $section->ResetCurrentActiveSections($school_year_term);
                
                $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($school_year_id);
                foreach ($currentNewEnrolled as $key => $student_ids) {

                    // All new enrolled student in the enrollment form will update as OLD
                    $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);
                }

                # First semester room transfered to 2nd Semester.
                // $roomSectionTransfer = $section->SectionHasRoomTransfer(
                //     $school_year_term, $school_year_period);

                echo "success_update";
                return;
            }

            if($school_year_period == "Second"){

                # Second Semester Successful opened room move-up sections.
                $movingUpSection = $section->MovingUpCurrentActiveSections($school_year_term);
                
                # As finals end, there`s no reason to accomodate students in the section
                # InActive opened sections
                $deactiveCurrentSection = $section->DeactiveCurrentActiveSections($school_year_term);
                
                // Create each one section to be used for new students section.
                # Alternative, Admin create manual room.

                $createEachNewSection = $section->CreateEachSectionStrandCourse($school_year_term);

                $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($school_year_id);
                foreach ($currentNewEnrolled as $key => $student_ids) {
                    // All new enrolled student in the enrollment form will update as OLD
                    $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);
                }
                echo "success_update"; 
                return;
            }
        }
    }





    # BREAK END

    // if(isset($_POST['school_year_id'])
    //     && isset($_POST['school_year_period'])
    //     && !isset($_POST['name_period'])){

    //     $school_year_id = $_POST['school_year_id'];
    //     $school_year_period = $_POST['school_year_period'];

    //     $now = date("Y-m-d H:i:s");

    //     $update_current = $con->prepare("UPDATE school_year
    //             SET statuses=:statuses,
    //                 break_enddate=:break_enddate,
    //                 enrollment_status = 0,
    //                 is_finished = 1
    //             WHERE school_year_id=:school_year_id");
        
    //     $update_current->bindValue(":statuses", "InActive");
    //     $update_current->bindValue(":break_enddate", $now);
    //     $update_current->bindParam(":school_year_id", $school_year_id);

    //     if($update_current->execute()){

    //         $gettingNextRow = $con->prepare("SELECT * FROM school_year 
    //             WHERE school_year_id > (
    //                 SELECT school_year_id FROM school_year WHERE school_year_id = :school_year_id
    //             )
    //             ORDER BY school_year_id ASC
    //             LIMIT 1
    //         ");

    //         $gettingNextRow->bindParam(":school_year_id", $school_year_id);
    //         $gettingNextRow->execute();

    //         if($gettingNextRow->rowCount() > 0){

    //             $nextRow = $gettingNextRow->fetch(PDO::FETCH_ASSOC);

    //             $next_school_year_id =  $nextRow['school_year_id'];

    //             // Once hit the Break.
    //             // Next S_Y Id statuses = Acttive & 
    //             // enrollment_Status = 1 and start_enrollment_date = NOW

    //             $update = $con->prepare("UPDATE school_year
    //                 SET statuses=:statuses,
    //                     enrollment_status=:enrollment_status,
    //                     start_enrollment_date=:start_enrollment_date
    //                 WHERE school_year_id=:school_year_id");
                
    //             $update->bindValue(":statuses", "Active");
    //             $update->bindValue(":enrollment_status", 1, PDO::PARAM_INT);
    //             $update->bindValue(":start_enrollment_date", $now);
    //             $update->bindParam(":school_year_id", $next_school_year_id);

    //             if($update->execute()){

    //                 echo "success_update";
    //             }
    //         }
    //     }
    // }

    // END Enrollment Date move to endEnrollmentDate.php
    // if(isset($_POST['school_year_id'])
    //     && isset($_POST['school_year_period'])
    //     && isset($_POST['name_period'])
    //     && $_POST['name_period'] == "end_enrollment"
    //     && isset($_POST['school_year_term'])){

    //         echo "end";

    //     # TODO. NEW Can Add the clean -up scenarion of enrollment form
    //     # that all unprocessed by cashier would be removed.
        
    //     // SHS -> Only Grade 12 maximum, if current section level is grade 12
    //     // All that grade 12 should be deactivated and should not be moved-up
                
    //     $school_year_id = $_POST['school_year_id'];
    //     $school_year_period = $_POST['school_year_period'];
    //     $school_year_term = $_POST['school_year_term'];

    //     $name_period = $_POST['name_period'];

    //     $section = new Section($con);

    //     $now = date("Y-m-d H:i:s");

    //     // $getEnrolledSection = $enrollment->GetAllEnrolledEnrollmentCourseWithinSemester($school_year_id);

    //     // $removeUnEnrolledSections = $section->RemoveUnEnrolledCreatedSectionWithinSemester(
    //     //     $school_year_term, $school_year_id);


    //     if($school_year_period == "First"){
    //         // $firstSemExcessRoomRemove = $section->RemoveUnEnrolledSectionInFirstSemester(
    //         //     $current_school_year_term,
    //         //     $current_school_year_id);
            
    //         $first = $section->RemoveUnEnrolledSectionInFirstSemester(
    //             $current_school_year_term,
    //             $current_school_year_id);
            
    //     }

    //     // echo $school_year_period;
    //     if($school_year_period == "Second"){
    //         $secondSemExcessRoomRemove = $section->RemoveUnEnrolledSectionInSecondSemester(
    //             $current_school_year_term, "Second");
    //     }

    //     $end_enrollment_date_update = $con->prepare("UPDATE school_year
    //         SET end_enrollment_date=:end_enrollment_date,
    //             enrollment_status = 0,
    //             is_finished = 1

    //         WHERE school_year_id=:school_year_id
    //         AND statuses='Active'");
        
    //     $end_enrollment_date_update->bindParam(":end_enrollment_date", $now);
    //     $end_enrollment_date_update->bindParam(":school_year_id", $school_year_id);

    //     // if($end_enrollment_date_update->execute()){
    //     //     echo "success_update";
    //     //     return;
    //     // }
     
    // }

?>