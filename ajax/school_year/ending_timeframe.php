<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Section.php");
    require_once("../../includes/classes/Enrollment.php");
    require_once("../../includes/classes/Student.php");


    if(
        isset($_POST['school_year_id'])
        && isset($_POST['school_year_period'])
        && !isset($_POST['name_period'])
        
    ){

        $school_year_id = $_POST['school_year_id'];
        $school_year_period = $_POST['school_year_period'];

        $now = date("Y-m-d H:i:s");

        $update_current = $con->prepare("UPDATE school_year
                SET statuses=:statuses,
                    break_enddate=:break_enddate,
                    enrollment_status = 0,
                    is_finished = 1
                WHERE school_year_id=:school_year_id");
        
        $update_current->bindValue(":statuses", "InActive");
        $update_current->bindValue(":break_enddate", $now);
        $update_current->bindParam(":school_year_id", $school_year_id);

        if($update_current->execute()){

            $gettingNextRow = $con->prepare("SELECT * FROM school_year 
                WHERE school_year_id > (
                    SELECT school_year_id FROM school_year WHERE school_year_id = :school_year_id
                )
                ORDER BY school_year_id ASC
                LIMIT 1
            ");

            $gettingNextRow->bindParam(":school_year_id", $school_year_id);
            $gettingNextRow->execute();

            if($gettingNextRow->rowCount() > 0){

                $nextRow = $gettingNextRow->fetch(PDO::FETCH_ASSOC);

                $next_school_year_id =  $nextRow['school_year_id'];

                // Once hit the Break.
                // Next S_Y Id statuses = Acttive & 
                // enrollment_Status = 1 and start_enrollment_date = NOW

                $update = $con->prepare("UPDATE school_year
                    SET statuses=:statuses,
                        enrollment_status=:enrollment_status,
                        start_enrollment_date=:start_enrollment_date
                    WHERE school_year_id=:school_year_id");
                
                $update->bindValue(":statuses", "Active");
                $update->bindValue(":enrollment_status", 1, PDO::PARAM_INT);
                $update->bindValue(":start_enrollment_date", $now);
                $update->bindParam(":school_year_id", $next_school_year_id);

                if($update->execute()){


                    echo "success_update";
                }

            }

        }
    }

    if(
        isset($_POST['school_year_id'])
        && isset($_POST['school_year_period'])
        && isset($_POST['name_period'])
        && $_POST['name_period'] == "finals"
        && isset($_POST['school_year_term'])
    ){

        $school_year_id = $_POST['school_year_id'];
        $school_year_period = $_POST['school_year_period'];
        $school_year_term = $_POST['school_year_term'];

        $name_period = $_POST['name_period'];

        $section = new Section($con);

        $now = date("Y-m-d H:i:s");

        $end_finals_date = $con->prepare("UPDATE school_year
                SET final_exam_enddate=:final_exam_enddate
                WHERE school_year_id=:school_year_id");
        
        $end_finals_date->bindValue(":final_exam_enddate", $now);
        $end_finals_date->bindParam(":school_year_id", $school_year_id);

        if($end_finals_date->execute()){

            $student = new Student($con);
            $enrollment = new Enrollment($con);

            if($school_year_period == "First"){

                // Check if student in Grade Records has failed atleast one subject
                // 
                // If Regular, Mark them as Irregular. TODO In Irregular Enrollment

                // TODO. All IsFull In First Semester Sections would be reset.
                
                $resetSection = $section->ResetCurrentActiveSections($school_year_term);
                
                $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($school_year_id);
                            
                foreach ($currentNewEnrolled as $key => $student_ids) {

                    // All new enrolled student in the enrollment form will update as OLD
                    $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);
                }

                echo "success_update";
                return;
            }

            if($school_year_period == "Second"){

                // Check if student in Grade Records has failed atleast one subject
                // 
                // If Regular, Mark them as Irregular. TODO In Irregular Enrollment

                // Create new Moving Up Section based on deactivated section.
                // AND SHS -> Only Grade 12 maximum, if current section level is grade 12
                // All that grade 12 should be deactivated and should not be moved-up
                
                $movingUpSection = $section->MovingUpCurrentActiveSections($school_year_term);
                // if($movingUpSection) echo "update_success";

                // Section useless should be removed at the end of enrollment date.

             
                $deactiveCurrentSection = $section->DeactiveCurrentActiveSections($school_year_term);
            
                // Create each one section to be used for new students section.
                $createEachNewSection = $section->CreateEachSectionStrandCourse($school_year_term);

                if($createEachNewSection){

                    $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($school_year_id);
                    
                    foreach ($currentNewEnrolled as $key => $student_ids) {
                        // All new enrolled student in the enrollment form will update as OLD
                        $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);
                    }
                }
                echo "success_update";
                return;

            }
            // 
        }
    }
    
    if(
        isset($_POST['school_year_id'])
        && isset($_POST['school_year_period'])
        && isset($_POST['name_period'])
        && $_POST['name_period'] == "end_enrollment"
        && isset($_POST['school_year_term'])
    ){

        # TODO.
        
        // SHS -> Only Grade 12 maximum, if current section level is grade 12
        // All that grade 12 should be deactivated and should not be moved-up
                
        $school_year_id = $_POST['school_year_id'];
        $school_year_period = $_POST['school_year_period'];
        $school_year_term = $_POST['school_year_term'];

        $name_period = $_POST['name_period'];

        // echo $name_period;

        $section = new Section($con);

        $now = date("Y-m-d H:i:s");
        // $enrollment = new Enrollment($con);

        // $getEnrolledSection = $enrollment->GetAllEnrolledEnrollmentCourseWithinSemester($school_year_id);

        $removeUnEnrolledSections = $section->RemoveUnEnrolledCreatedSectionWithinSemester(
            $school_year_term, $school_year_id);

        // $createEachNewSection = $section->CreateEachSectionStrandCourse(
        //         $school_year_term);

        $end_finals_date = $con->prepare("UPDATE school_year
            SET end_enrollment_date=:end_enrollment_date,
                enrollment_status = 0,
                is_finished = 1

            WHERE school_year_id=:school_year_id
            AND statuses='Active'");
        
        $end_finals_date->bindParam(":end_enrollment_date", $now);
        $end_finals_date->bindParam(":school_year_id", $school_year_id);

        if($end_finals_date->execute()){

            # Only sets as is_remove = 1 (Did not totally removed in the DB.)
            if($removeUnEnrolledSections == true){

           
            }

            echo "success_update";
            return;
        }
     
    }


?>