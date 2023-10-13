<?php

class Notification{

    private $con, $sqlData, $notification_id;

    public static function SortByDateCreation($a, $b) {
        return strtotime($a['date_creation']) - strtotime($b['date_creation']);
    }

    public function __construct($con, $notification_id = null){

        $this->con = $con;
        $this->notification_id = $notification_id;

        $query = $this->con->prepare("SELECT * FROM notification
                WHERE notification_id=:notification_id");

        $query->bindValue(":notification_id", $notification_id);
        $query->execute();

        $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
    }

    public function GetSubjectCodeAssignmentId() {
        return isset($this->sqlData['subject_code_assignment_id']) ? $this->sqlData["subject_code_assignment_id"] : NULL; 
    }

    public function GetSubjectCodeSubmissionId() {
        return isset($this->sqlData['subject_code_submission_id']) ? $this->sqlData["subject_code_submission_id"] : NULL; 
    }

    public function GetDateCreation() {
        return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : NULL; 
    }

    public function GetSenderRole() {
        return isset($this->sqlData['sender_role']) ? $this->sqlData["sender_role"] : NULL; 
    }

    public function GetStudentAssignmentNotification(
        $enrolledSubjectList, $school_year_id) {

        $inPlaceholders = implode(', ', array_map(function($value, $index) {
            return ":subject_code$index";
        }, $enrolledSubjectList, array_keys($enrolledSubjectList)));

        $query = $this->con->prepare("SELECT * 
            FROM notification 
            WHERE subject_code IN ($inPlaceholders)
            AND school_year_id = :school_year_id
            AND sender_role != 'student'
        ");

        // Bind values to named placeholders in the IN clause
        foreach ($enrolledSubjectList as $index => $subjectCode) {
            $placeholderName = ":subject_code$index";
            $query->bindValue($placeholderName, $subjectCode);
        }

        // Bind the school_year_id
        $query->bindValue(':school_year_id', $school_year_id, PDO::PARAM_INT); // Assuming school_year_id is an integer

        $query->execute();

        if($query->rowCount() > 0){
            $result = $query->fetchAll(PDO::FETCH_ASSOC);
            // var_dump($result);
            return $result;
        }

        return [];
    }


    public function GetStudentSubmittedAssignmentNotification(
        $teachingSubjects, $school_year_id) {

        if(count($teachingSubjects) > 0){

            $inPlaceholders = implode(', ', array_map(function($value, $index) {
                return ":subject_code$index";
            }, $teachingSubjects, array_keys($teachingSubjects)));

            $query = $this->con->prepare("SELECT * 
                FROM notification 
                WHERE subject_code IN ($inPlaceholders)
                AND school_year_id = :school_year_id
                AND sender_role = 'student'
                AND subject_assignment_submission_id IS NOT NULL

            ");

            // Bind values to named placeholders in the IN clause
            foreach ($teachingSubjects as $index => $subjectCode) {
                $placeholderName = ":subject_code$index";
                $query->bindValue($placeholderName, $subjectCode);
            }

            // Bind the school_year_id
            $query->bindValue(':school_year_id', $school_year_id, PDO::PARAM_INT); // Assuming school_year_id is an integer

            $query->execute();

            if($query->rowCount() > 0){
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                // var_dump($result);
                return $result;
            }
        }


        return [];
    }

    # Applicable for Teacher and Student
    public function GetAdminAnnouncement($school_year_id) {

        $get = $this->con->prepare("SELECT t1.* 
        
            FROM notification as t1

            WHERE t1.sender_role=:sender_role
            AND t1.sender_role=:sender_role
            AND t1.announcement_id IS NOT NULL
        ");

        $get->bindValue(":sender_role", "admin");
        $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        if($get->rowCount() > 0){
            return $get->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }
   

    public function CheckStudentViewedNotification(
        $notification_id, $student_id) {

        $get = $this->con->prepare("SELECT t1.* 
        
            FROM notification_view as t1

            WHERE t1.notification_id=:notification_id
            AND t1.student_id=:student_id
            AND t1.date_viewed IS NOT NULL
            
            ");
        $get->bindValue(":notification_id", $notification_id);
        $get->bindValue(":student_id", $student_id);
        $get->execute();

        return $get->rowCount() > 0;
    }
    public function CheckTeacherViewedNotification(
        $notification_id, $teacher_id) {

        $get = $this->con->prepare("SELECT t1.* 
        
            FROM notification_view as t1

            WHERE t1.notification_id=:notification_id
            AND t1.teacher_id=:teacher_id
            AND t1.date_viewed IS NOT NULL
            
            ");
        $get->bindValue(":notification_id", $notification_id);
        $get->bindValue(":teacher_id", $teacher_id);
        $get->execute();

        return $get->rowCount() > 0;
    }

    public function CheckStudentHasSubmittedNotifiedOnAssignment(
        $subject_assignment_submission_id, 
        $student_id,
        $subject_code,
        $school_year_id) {

        $get = $this->con->prepare("SELECT t1.* 
        
            FROM notification as t1

            INNER JOIN subject_assignment_submission as t2 ON t2.subject_assignment_submission_id = t1.subject_assignment_submission_id
            AND t2.student_id=:student_id
            AND t2.school_year_id=:school_year_id

            WHERE t1.subject_assignment_submission_id=:subject_assignment_submission_id
            AND t1.subject_code=:subject_code
            AND t1.sender_role= 'student'

            ORDER BY notification_id DESC
            LIMIT 1
        ");

        $get->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);
        $get->bindValue(":subject_code", $subject_code);
        $get->bindValue(":student_id", $student_id);
        $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        if($get->rowCount() > 0){
            return $get->fetch(PDO::FETCH_ASSOC);
        }

        return NULL;
    }

    public function RemovePrevSubmittedNotification(
        $subject_assignment_submission_id, 
        $student_id,
        $subject_code,
        $school_year_id) {

        $notification_obj = $this->CheckStudentHasSubmittedNotifiedOnAssignment(
            $subject_assignment_submission_id, 
            $student_id,
            $subject_code,
            $school_year_id);

        if($notification_obj !== NULL){

            $notification_id = $notification_obj['notification_id'];

            // echo "notification_id: $notification_id";
            // echo "<br>";
            $remove = $this->con->prepare("DELETE FROM notification
            
                WHERE notification_id=:notification_id
                AND school_year_id=:school_year_id
            ");
        
            $remove->bindValue(":notification_id", $notification_id);
            $remove->bindValue(":school_year_id", $school_year_id);
            $remove->execute();

            if($remove->rowCount() > 0){
                return true;
            }

        }

        return false;
    }

    public function GetStudentViewedNotification(
        $notification_id, $student_id) {

        $get = $this->con->prepare("SELECT t1.* 
        
            FROM notification_view as t1

            WHERE t1.notification_id=:notification_id
            AND t1.student_id=:student_id
            AND t1.date_viewed IS NOT NULL
        ");

        $get->bindValue(":notification_id", $notification_id);
        $get->bindValue(":student_id", $student_id);
        $get->execute();

        if($get->rowCount() > 0){
            return $get->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    public function CheckGivenAssignmentHasNotification(
        $subject_code_assignment_id, $school_year_id) {

        $get = $this->con->prepare("SELECT t1.* 
        
            FROM notification as t1
            WHERE t1.subject_code_assignment_id=:subject_code_assignment_id
            AND t1.school_year_id=:school_year_id
        ");

        $get->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
        $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        return $get->rowCount() > 0;
    }

    public function RemoveGivenAssignmentNotification(
        $subject_code_assignment_id, $school_year_id) {

        if($this->CheckGivenAssignmentHasNotification(
            $subject_code_assignment_id, $school_year_id) == true){

            $remove = $this->con->prepare("DELETE FROM notification
            
                WHERE subject_code_assignment_id=:subject_code_assignment_id
                AND school_year_id=:school_year_id
            ");
        
            $remove->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
            $remove->bindValue(":school_year_id", $school_year_id);
            $remove->execute();

            if($remove->rowCount() > 0){
                return true;
            }

        }
        return false;

    }

    public function RemoveGivenAnnouncement(
        $announcement_id, $school_year_id) {

        $remove = $this->con->prepare("DELETE FROM notification
        
            WHERE announcement_id=:announcement_id
            AND school_year_id=:school_year_id
        ");
    
        $remove->bindValue(":announcement_id", $announcement_id);
        $remove->bindValue(":school_year_id", $school_year_id);
        $remove->execute();

        if($remove->rowCount() > 0){
            return true;
        }

        return false;

    }

    public function StudentNotificationMarkAsViewed(
        $notification_id, $student_id) {

        $now = date("Y-m-d H:i:s");

        if($this->CheckStudentViewedNotification($notification_id,
            $student_id) == false){

            $add = $this->con->prepare("INSERT INTO notification_view
                (student_id, notification_id, date_viewed, viewed_role)
                VALUES(:student_id, :notification_id, :date_viewed, :viewed_role)");
            
            $add->bindValue(":student_id", $student_id);
            $add->bindValue(":notification_id", $notification_id);
            $add->bindValue(":date_viewed", $now);
            $add->bindValue(":viewed_role", "student");
            $add->execute();

            if($add->rowCount() > 0){
                return true;
            }
        }
        return false;
    }

    public function TeacherNotificationMarkAsViewed(
        $notification_id, $teacher_id) {

        $now = date("Y-m-d H:i:s");

        if($this->CheckTeacherViewedNotification($notification_id,
            $teacher_id) == false){

            $add = $this->con->prepare("INSERT INTO notification_view
                (teacher_id, notification_id, date_viewed, viewed_role)
                VALUES(:teacher_id, :notification_id, :date_viewed, :viewed_role)");
            
            $add->bindValue(":teacher_id", $teacher_id);
            $add->bindValue(":notification_id", $notification_id);
            $add->bindValue(":date_viewed", $now);
            $add->bindValue(":viewed_role", "teacher");
            $add->execute();

            if($add->rowCount() > 0){
                return true;
            }
        }
        return false;
    }

    public function StudentSubmitTaskNotification(
        $subject_code, $school_year_id,
        $subject_assignment_submission_id) {

        $now = date("Y-m-d H:i:s");

        $sender_role = "student";

        $add = $this->con->prepare("INSERT INTO notification
            (sender_role, subject_code, school_year_id, subject_assignment_submission_id)
            VALUES(:sender_role, :subject_code, :school_year_id, :subject_assignment_submission_id)");
        
        $add->bindValue(":sender_role", $sender_role);
        $add->bindValue(":subject_code", $subject_code);
        $add->bindValue(":school_year_id", $school_year_id);
        $add->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);

        $add->execute();

        if($add->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function InsertNotificationForTeacherAnnouncement($school_year_id,
        $subject_code, $announcement_id){

        $sender_role = "teacher";


        $sql = $this->con->prepare("INSERT INTO notification
            (sender_role, subject_code, school_year_id, announcement_id)
            VALUES(:sender_role, :subject_code, :school_year_id, :announcement_id)");
        
        $sql->bindValue(":sender_role", $sender_role);
        $sql->bindValue(":subject_code", $subject_code);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->bindValue(":announcement_id", $announcement_id);

        $sql->execute();

        if($sql->rowCount() > 0){

            return true;
        }

        return false;
    }

    public function InsertNotificationForTeacherGivingAssignment(
        $school_year_id, $subject_code_assignment_id, $subject_code){

        $sender_role = "teacher";

        $sql = $this->con->prepare("INSERT INTO notification
            (sender_role, subject_code, school_year_id, subject_code_assignment_id)
            VALUES(:sender_role, :subject_code, :school_year_id, :subject_code_assignment_id)");
        
        $sql->bindValue(":sender_role", $sender_role);
        $sql->bindValue(":subject_code", $subject_code);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);

        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }

        return false;
    }

}
?>