<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/User.php");
    



    // $data_arr = [
    //     [
    //         'event_id' => 1,
    //         'title' => 'Focusing Event 1',
    //         'start' => '2023-09-21',
    //         'end' => '2023-09-22',
    //         'color' => '2312',
    //         'url' => 'https://www.shinerweb.com'
    //     ],
    //     [
    //         'event_id' => 1,
    //         'title' => 'Focusing Event 2',
    //         'start' => '2023-09-21',
    //         'end' => '2023-09-22',
    //         'color' => '2312',
    //         'url' => 'https://www.shinerweb.com'
    //     ],
    //     [
    //         'event_id' => 1,
    //         'title' => 'Focusing Event 3',
    //         'start' => '2023-09-23',
    //         'end' => '2023-09-26',
    //         'color' => '2312',
    //         'url' => 'https://www.shinerweb.com'
    //     ],
    //     [
    //         'event_id' => 2,
    //         'title' => 'Hoping Event',
    //        'start' => '2023-09-22',
    //         'end' => '2023-09-23',
    //         'color' => '23132',
    //         'url' => 'https://www.shinerweb.com'
    //     ],
    //     // Add more events as needed
    // ];

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

    if($query->rowCount() > 0){

        // $data_arr = [
        // [
        //     'event_id' => 1,
        //     'title' => 'Focusing Event 1',
        //     'start' => '2023-09-21',
        //     'end' => '2023-09-22',
        //     'color' => '2312',
        //     'url' => 'https://www.shinerweb.com'
        // ],

        while($row = $query->fetch(PDO::FETCH_ASSOC)){


            $subject_code_assignment_id = $row['subject_code_assignment_id'];
            $subject_period_code_topic_template_id = $row['subject_period_code_topic_template_id'];
            $subject_period_code_topic_id = $row['subject_period_code_topic_id'];
            $assignment_name = $row['assignment_name'];

            $date_creation = $row['date_creation'];
            $date_creation = date("Y-m-d", strtotime($date_creation));

            

            $due_date_db = $row['due_date'];
            $due_date = date("Y-m-d", strtotime($due_date_db));

            $due_time_hours = date("h:i a", strtotime($due_date_db));


            // $url = "../../teacher/class/section_topic.php?id=$subject_period_code_topic_template_id&ct_id=$subject_period_code_topic_id";
           
            // $url = "../../teacher/class/grade_book_topic.php?ct_id=$subject_period_code_topic_id";


            $url = "../../teacher/class/task_summary.php?ct_id=$subject_period_code_topic_id&calendar_clicked=true";
            
            $dataCalendar[] = array(
                'subject_code_assignment_id' => $subject_code_assignment_id,
                'title' => $due_time_hours . " " . $assignment_name,

                'start' => $due_date,
                'end' => $due_date,
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