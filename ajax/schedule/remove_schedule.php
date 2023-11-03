<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Schedule.php");
    require_once("../../includes/classes/SchoolYear.php");
    require_once("../../includes/classes/SubjectPeriodCodeTopic.php");
    
    if (isset($_POST['subject_schedule_id'])) {

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
        $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
        $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');



        $subject_schedule_id = $_POST['subject_schedule_id'];

        $schedule = new Schedule($con, $subject_schedule_id);
        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_schedule_id);



        $subject_code = $schedule->GetSubjectCode();
        $subject_teacher_id = $schedule->GetScheduleTeacherId();


        $getTeacherScheduleCount = $schedule->GetSubjectScheduleCountForTeacher(
            $subject_code,
            $current_school_year_id);

        $deleteQuery = $con->prepare("DELETE FROM subject_schedule 
            WHERE subject_schedule_id = :subject_schedule_id
        ");

        if($getTeacherScheduleCount <= 1){



            if($subject_teacher_id == NULL){

                $deleteQuery->bindParam(":subject_schedule_id", $subject_schedule_id);
                $deleteQuery->execute();

                if($deleteQuery->rowCount() > 0){

                    // echo "success_delete_with_subject_topic";
                    echo "success_delete";
                    return;
                }

            }
            if($subject_teacher_id != NULL && $subject_code != ""){

                $successRemoval = $subjectPeriodCodeTopic
                    ->RemovingTeachingCodeTopic(
                    $subject_teacher_id,
                    $subject_code,
                    $current_school_year_id);

                $deleteQuery->bindParam(":subject_schedule_id", $subject_schedule_id);
                $deleteQuery->execute();

                if($deleteQuery->rowCount() > 0){

                    echo "success_delete_with_subject_topic";
                    return;
                }

            }
            

        }

        if($getTeacherScheduleCount > 1){

            $query = $con->prepare("DELETE FROM subject_schedule 
                WHERE subject_schedule_id = :subject_schedule_id
            ");

            $query->bindParam(":subject_schedule_id", $subject_schedule_id);

            if($query->execute()){
                echo "success_delete";
                return;
            }
        }

        

        # TODO. it should also remove the subject_period_code_topic,


    }
   
?>