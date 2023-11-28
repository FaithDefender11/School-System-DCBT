<?php 

    require_once("../../includes/config.php");


    // echo "yehey";

   if(isset($_POST['student_id'])
        && isset($_POST['subject_id'])
        && isset($_POST['remarks'])
        && isset($_POST['student_subject_id'])
        && isset($_POST['subject_title'])
        && isset($_POST['course_id'])
        && isset($_POST['school_year_id'])
        
        ) {


        $student_id = $_POST['student_id'];
        $subject_id = $_POST['subject_id'];
        $remarks = $_POST['remarks'];
        $school_year_id = $_POST['school_year_id'];

        $student_subject_id = $_POST['student_subject_id'];
        $subject_title = $_POST['subject_title'];
        $course_id = $_POST['course_id'];

        // echo $remarks;


        if($remarks == "Passed"){

            $sql = $con->prepare("INSERT INTO student_subject_grade 
            
                (student_id, subject_id, remarks, student_subject_id, subject_title, course_id, school_year_id)
                VALUES(:student_id, :subject_id, :remarks, :student_subject_id, :subject_title, :course_id, :school_year_id)");
            
            $sql->bindValue(":student_id", $student_id);
            $sql->bindValue(":subject_id", $subject_id);
            $sql->bindValue(":remarks", "Passed");
            $sql->bindValue(":student_subject_id", $student_subject_id);
            $sql->bindValue(":subject_title", $subject_title);
            $sql->bindValue(":course_id", $course_id);
            $sql->bindValue(":school_year_id", $school_year_id);

            if($sql->execute()){
                echo "success";
            }

        }
    }



    if(isset($_POST['student_subject_id'])
        && isset($_POST['student_id'])
        && isset($_POST['remarks'])
        ) {

        $student_id = $_POST['student_id'];
        $remarks = $_POST['remarks'];
        $student_subject_id = $_POST['student_subject_id'];


        if($remarks == "Passed"){

            $sql = $con->prepare("INSERT INTO student_subject_grade 
            
                (student_id, student_subject_id, remarks)
                VALUES(:student_id, :student_subject_id, :remarks)");
            
            $sql->bindValue(":student_id", $student_id);
            $sql->bindValue(":student_subject_id", $student_subject_id);
            $sql->bindValue(":remarks", "Passed");

            if($sql->execute()){
                echo "success";
            }

        }
    }

?>