<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/User.php");
    require_once("../../includes/classes/SubjectCodeAssignment.php");
    
    $teacher_id = NULL;
    $school_year_id = NULL;

    
    if(isset($_GET['t_id'])){
        $teacher_id = $_GET['t_id'];
    }
    if(isset($_GET['sy_id'])){
        $school_year_id = $_GET['sy_id'];
    }

    // echo $teacher_id;
    // echo $school_year_id;

    $query = $con->prepare("SELECT 

        t1.*
        ,t2.subject_period_code_topic_template_id
        ,t2.subject_period_code_topic_id
    
        FROM subject_code_assignment as t1
        
        INNER JOIN subject_period_code_topic as t2 ON t2.subject_period_code_topic_id = t1.subject_period_code_topic_id
        AND t2.teacher_id=:teacher_id
        AND t2.school_year_id=:school_year_id
        ");

    $query->bindValue(":teacher_id", $teacher_id);
    $query->bindValue(":school_year_id", $school_year_id);
    $query->execute();

    $subjectCodeAssignment = new SubjectCodeAssignment($con);

    $teachingSubjectCodeAnnouncement = $subjectCodeAssignment->GetTeacherTeachingSubjectsWithAnnouncement(
        $teacher_id,
        $school_year_id);

    if(count($teachingSubjectCodeAnnouncement) > 0){
        

        foreach ($teachingSubjectCodeAnnouncement as $key => $row) {

            # code...

            $announcement_id = $row['announcement_id'];
            $users_id = $row['users_id'];
            $title = $row['title'];

            $date_creation = $row['date_creation'];
            $date_creation = date("Y-m-d", strtotime($date_creation));

            // $due_date_db = $row['due_date'];
            // $due_date = date("Y-m-d", strtotime($due_date_db));

            $due_time_hours = date("h:i a", strtotime($date_creation));
 
            $url = "";
            
            $mine = "";
            if($users_id == NULL){
                $mine = "**";
                $url = "../class/subject_announcement.php?id=$announcement_id";
            }else{
                $url = "../class/subject_announcement.php?id=$announcement_id";

            }

            $dataCalendar[] = array(
                'announcement_id' => $announcement_id,
                'title' => $mine ." ". $due_time_hours . " " . $title,
                'color' => '2312',

                'start' => $date_creation,
                'end' => $date_creation,
                'url' => $url
            );
        }
    }

    $data = array(
        'status' => true,
        'msg' => 'successfully!',
        'data' => $dataCalendar
    );

    if(empty($dataCalendar)){
        echo json_encode([]);
    }else{
        echo json_encode($data);
    }

?>