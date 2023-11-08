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

    # Given assignment by Teacher ONLY.

    public function GetStudentAssignmentNotification(
        $enrolledSubjectList, $school_year_id) {

        if(count($enrolledSubjectList) > 0){

            $inPlaceholders = implode(', ', array_map(function($value, $index) {
                return ":subject_code$index";
            }, $enrolledSubjectList, array_keys($enrolledSubjectList)));

            
            $query = $this->con->prepare("SELECT * 
                FROM notification 
                WHERE subject_code IN ($inPlaceholders)
                AND school_year_id = :school_year_id
                AND sender_role = 'teacher'
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
        }


        return [];
    }

    # Given assignment by Teacher ONLY v2.
    public function GetStudentAssignmentNotificationv2(
        $enrolledSubjectList, $school_year_id) {

        if(count($enrolledSubjectList) > 0){

            $inPlaceholders = implode(', ', array_map(function($value, $index) {
                return ":subject_code$index";
            }, $enrolledSubjectList, array_keys($enrolledSubjectList)));

            
            $query = $this->con->prepare("SELECT * 
                FROM notification 
                WHERE subject_code IN ($inPlaceholders)
                AND school_year_id = :school_year_id
                AND sender_role = 'teacher'
                AND subject_code_assignment_id IS NOT NULL
                AND subject_assignment_submission_id IS NULL

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
        }


        return [];
    }

    # Student assignment by graded by teacher
    public function GetStudentGradedAssignmentNotification(
        $enrolledSubjectList, $school_year_id, $student_id) {

        if(count($enrolledSubjectList) > 0){

            $inPlaceholders = implode(', ', array_map(function($value, $index) {
                return ":subject_code$index";
            }, $enrolledSubjectList, array_keys($enrolledSubjectList)));

            
            $query = $this->con->prepare("SELECT t1.* 
                FROM notification as t1

                INNER JOIN notification_view as t2 ON t2.notification_id = t2.notification_id
                AND category = 'assignment'
                AND student_id = :student_id

                WHERE t1.subject_code IN ($inPlaceholders)
                AND t1.school_year_id = :school_year_id
                AND t1.sender_role = 'teacher'
                AND t1.subject_code_assignment_id IS NOT NULL
                AND t1.subject_assignment_submission_id IS NOT NULL

                GROUP BY t1.notification_id
            ");

            $query->bindValue(':student_id', $student_id, PDO::PARAM_INT);

            // Bind values to named placeholders in the IN clause
            foreach ($enrolledSubjectList as $index => $subjectCode) {
                $placeholderName = ":subject_code$index";
                $query->bindValue($placeholderName, $subjectCode);
            }

            // Bind the school_year_id
            $query->bindValue(':school_year_id', $school_year_id, PDO::PARAM_INT);

            $query->execute();

            if($query->rowCount() > 0){
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                // var_dump($result);
                return $result;
            }
        }


        return [];
    }

    public function GetStudentDueDateNotifications(
        $enrolledSubjectList, $school_year_id,
        $student_id) {

        // var_dump($enrolledSubjectList);
        if(count($enrolledSubjectList) > 0){

            $inPlaceholders = implode(', ', array_map(function($value, $index) {
                return ":subject_code$index";
            }, $enrolledSubjectList, array_keys($enrolledSubjectList)));

            $get = $this->con->prepare("SELECT t1.* FROM notification as t1

                INNER JOIN notification_view as t2 ON t2.notification_id = t1.notification_id

                WHERE t1.subject_code IN ($inPlaceholders)
                AND t1.sender_role = :sender_role
                AND t1.school_year_id = :school_year_id
                AND t2.student_id = :student_id
                AND t2.category = 'deadline'

            ");


            // Bind values to named placeholders in the IN clause
            foreach ($enrolledSubjectList as $index => $subjectCode) {
                $placeholderName = ":subject_code$index";
                $get->bindValue($placeholderName, $subjectCode);

            }

            $get->bindValue(":sender_role", "auto");
            $get->bindValue(":school_year_id", $school_year_id);
            $get->bindValue(":student_id", $student_id);

            

            $get->execute();

            if($get->rowCount() > 0){

                $result = $get->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }   
        }



        return [];
    }

    public function CheckStudentEnrolledCodeHasIncludedInDueDateNotification(
        $enrolledSubjectList, $school_year_id,
        $student_id, $getAllIncomingDueAssignmentsIds) {

        // print_r($enrolledSubjectList);
        // echo "<br>";
        // print_r($getAllIncomingDueAssignmentsIds);
        // echo "<br>";
        // return;

        if(count($enrolledSubjectList) > 0 && count($getAllIncomingDueAssignmentsIds)){

            $inPlaceholders = implode(', ', array_map(function($value, $index) {
                return ":subject_code$index";
            }, $enrolledSubjectList, array_keys($enrolledSubjectList)));

            $inPlaceholders2 = implode(', ', array_map(function($value, $index) {
                return ":subject_code_assignment_id$index";
            }, $getAllIncomingDueAssignmentsIds, array_keys($getAllIncomingDueAssignmentsIds)));

            $get = $this->con->prepare("SELECT t1.* FROM notification as t1

                WHERE t1.subject_code IN ($inPlaceholders)
                AND t1.subject_code_assignment_id IN ($inPlaceholders2)

                AND t1.sender_role = :sender_role
                AND t1.school_year_id = :school_year_id

            ");

            // $get->bindValue(":student_id", $student_id);

            // Bind values of subject_code to named  placeholders in the IN clause
            foreach ($enrolledSubjectList as $index => $subjectCode) {
                $placeholderName = ":subject_code$index";
                $get->bindValue($placeholderName, $subjectCode);
            }

            // Bind values of subject_code_assignment_id to named  placeholders in the IN clause
            foreach ($getAllIncomingDueAssignmentsIds as $index => $subject_code_assignment_ids) {
                $placeholderName2 = ":subject_code_assignment_id$index";
                $get->bindValue($placeholderName2, $subject_code_assignment_ids);
            }

            $get->bindValue(":sender_role", "auto");
            $get->bindValue(":school_year_id", $school_year_id);

            $get->execute();

            if($get->rowCount() > 0){

                $result = $get->fetchAll(PDO::FETCH_ASSOC);

                var_dump($result);
                return;

                // return $result;
                foreach ($result as $key => $value) {
                    # code...

                    $notification_id = $value['notification_id'];

                    # Check if student_id is included in the notification_view

                    // $check = $this->con->prepare("SELECT t1.* FROM notification_view as t1

                    //     WHERE t1.notification_id =:notification_id
                    //     AND t1.student_id = :student_id
                    //     AND t1.category = 'deadline'
                    // ");

                    // $check->bindValue(":notification_id", $notification_id);
                    // $check->bindValue(":student_id", $student_id);

                    // $check->execute();
                    

                    // if($check->rowCount() == 0){
                    if($this->CheckIfNotificationViewDueDateStudentExist($student_id,
                        $notification_id) == false){

                        # Create student who should have received the notification
                        # because the due date assignment has the 1 day span time

                        $doesInserted = $this->InsertStudentDueDateNotification(
                            $notification_id, $student_id);
                        
                    }

                }

            }

            else if($get->rowCount() == 0){

                echo "== 0";
                return;

                #xD Check first if subject_code_assignment_id and subject_code is not created

                # Create subject_code_assignment_id ( SHOULD HAVE A DUE DATE ) 
                # and subject_code ( ENROLLED SUBJECT )
            
                foreach ($getAllIncomingDueAssignmentsIds as $key => $subjectCodeAssignmentIds) {
                    # code...

                    $subjectCodeAssignment = new SubjectCodeAssignment($this->con, $subjectCodeAssignmentIds);

                    $subjectPeriodTopicId =  $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();

                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con, $subjectPeriodTopicId);

                    $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

                    # Check Notifcation Table
                    $checkNotif = $this->con->prepare("SELECT t1.* FROM notification as t1

                        WHERE t1.subject_code_assignment_id =:subject_code_assignment_id
                        AND t1.subject_code = :subject_code
                    ");

                    $checkNotif->bindValue(":subject_code_assignment_id", $subjectCodeAssignmentIds);
                    $checkNotif->bindValue(":subject_code", $subject_code);

                    $checkNotif->execute();

                    if($checkNotif->rowCount() > 0){

                        $dueDateNotificationList = $checkNotif->fetchAll(PDO::FETCH_ASSOC);

                        echo "<br>";
                        var_dump($dueDateNotificationList);
                        echo "<br>";
                        // foreach ($dueDateNotificationList as $key => $value) {
                        //     # code...
                        // }

                    }

                    if($checkNotif->rowCount() == 0){

                        $doesDueDateNotifInserted = $this->InsertDueDateNotification(
                            $subjectCodeAssignmentIds, $school_year_id, $subject_code);

                        if($doesDueDateNotifInserted){

                            $generated_notification_id = $this->con->lastInsertId();

                            if($this->CheckIfNotificationViewDueDateStudentExist($student_id,
                                $generated_notification_id) == false){

                                # Create student who should have received the notification
                                # because the due date assignment has the 1 day span time

                                $doesInserted = $this->InsertStudentDueDateNotification(
                                    $generated_notification_id, $student_id);
                                
                            }
                        }
                    }

                }

            }
        }



        return [];
    }


    public function CheckStudentEnrolledCodeHasIncludedInDueDateNotificationv2(
        $enrolledSubjectList, $school_year_id,
        $student_id, $getAllIncomingDueAssignmentsIds) {


        # If theres a due date and the notification table for due date is not initialized
        # the first student who log in with the subject assignment due date is less than 1 day
        # it will create a table for due date notification

        # If theres already due date notification that are linked to the subject code assignment id
        # then, the only notification_user will be generated.


        if(count($getAllIncomingDueAssignmentsIds) > 0){

            foreach ($getAllIncomingDueAssignmentsIds as $key => $subjectCodeAssignmentIds) {
                    # code...
                $subjectCodeAssignment = new SubjectCodeAssignment($this->con, $subjectCodeAssignmentIds);

                $subjectPeriodTopicId =  $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();

                $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con, $subjectPeriodTopicId);

                $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

                # Check Due Date Notifcation Table
                $checkNotif = $this->con->prepare("SELECT t1.* FROM notification as t1

                    WHERE t1.subject_code_assignment_id =:subject_code_assignment_id
                    -- AND t1.subject_code = :subject_code
                    AND t1.sender_role = 'auto'
                ");

                $checkNotif->bindValue(":subject_code_assignment_id", $subjectCodeAssignmentIds);
                // $checkNotif->bindValue(":subject_code", $subject_code);
                $checkNotif->execute();

                // var_dump($subject_code);
                // var_dump($checkNotif->rowCount());
                // echo "<br>";

                if($checkNotif->rowCount() > 0){
 
                    $dueDateNotificationList = $checkNotif->fetchAll(PDO::FETCH_ASSOC);

                    // var_dump($subject_code);
                    // echo "<br>";

                    // echo "<br>";
                    // var_dump($dueDateNotificationList);
                    // echo "<br>";
                    // echo "<br>";

                    foreach ($dueDateNotificationList as $key => $value) {
                        # code...

                        $notification_id = $value['notification_id'];

                        // echo "notification_id: $notification_id";
                        // echo "<br>";

                        if($this->CheckIfNotificationViewDueDateStudentExist($student_id,
                            $notification_id) == false){

                            # Create student who should have received the notification
                            # because the due date assignment has the 1 day span time

                            $doesInserted = $this->InsertStudentDueDateNotification(
                                $notification_id, $student_id);
                            
                        }
                    }

                }

                if($checkNotif->rowCount() == 0){
                // if(false){

                    // echo "RowCount 0: subjectCodeAssignmentIds: $subjectCodeAssignmentIds";
                    // echo "<br>";

                    $doesDueDateNotifInserted = $this->InsertDueDateNotification(
                        $subjectCodeAssignmentIds, $school_year_id, $subject_code);

                    if($doesDueDateNotifInserted){

                        $generated_notification_id = $this->con->lastInsertId();

                        if($this->CheckIfNotificationViewDueDateStudentExist($student_id,
                            $generated_notification_id) == false){

                            # Create student who should have received the notification
                            # because the due date assignment has the 1 day span time

                            $doesInserted = $this->InsertStudentDueDateNotification(
                                $generated_notification_id, $student_id);
                            
                        }
                    }

                }

            }

            // return;
        }

    }


    public function CheckIfNotificationViewDueDateStudentExist($student_id, $notification_id) {

        $check = $this->con->prepare("SELECT t1.* 
        
            FROM notification_view as t1

            WHERE t1.notification_id =:notification_id
            AND t1.student_id = :student_id
            AND t1.category = 'deadline'
            
        ");

        $check->bindValue(":notification_id", $notification_id);
        $check->bindValue(":student_id", $student_id);

        $check->execute();

        return $check->rowCount() > 0;
        
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
            AND t1.school_year_id=:school_year_id
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
            AND t1.category = 'assignment'
            
            ");
        $get->bindValue(":notification_id", $notification_id);
        $get->bindValue(":student_id", $student_id);
        $get->execute();

        return $get->rowCount() > 0;
    }

    public function CheckStudentViewedDueDateNotification(
        $notification_id, $student_id) {

        $get = $this->con->prepare("SELECT t1.* 
        
            FROM notification_view as t1

            WHERE t1.notification_id=:notification_id
            AND t1.student_id=:student_id
            AND t1.date_viewed IS NOT NULL
            AND t1.category = 'deadline'
            
            ");
        $get->bindValue(":notification_id", $notification_id);
        $get->bindValue(":student_id", $student_id);
        $get->execute();

        return $get->rowCount() > 0;
    }

    public function GetTeacherViewedNotificationCount(
        $studentListSubmittedNotification, $teacher_id) {

        $count = 0;
        
        if(count($studentListSubmittedNotification) > 0){

            foreach ($studentListSubmittedNotification as $key => $value) {
                # code...

                $subject_code_teacher_id = $value['subject_code_teacher_id'];
                $notification_id = $value['notification_id'];

                $get = $this->con->prepare("SELECT t1.* 
                
                    FROM notification_view as t1

                    WHERE t1.notification_id=:notification_id
                    AND t1.teacher_id=:teacher_id
                    
                    ");
                $get->bindValue(":notification_id", $notification_id);
                $get->bindValue(":teacher_id", $teacher_id);
                $get->execute();

                if($get->rowCount() > 0){
                    $count++;
                }
            }


        }

        return $count;

    }

    public function GetTeacherUnViewedNotificationCount(
        $studentListSubmittedNotification, $teacher_id) {

        $count = 0;
        
        if(count($studentListSubmittedNotification) > 0){

            foreach ($studentListSubmittedNotification as $key => $value) {
                # code...

                $subject_code_teacher_id = $value['subject_code_teacher_id'];
                $notification_id = $value['notification_id'];

                $get = $this->con->prepare("SELECT t1.* 
                
                    FROM notification_view as t1

                    WHERE t1.notification_id=:notification_id
                    AND t1.teacher_id=:teacher_id
                    
                    ");
                $get->bindValue(":notification_id", $notification_id);
                $get->bindValue(":teacher_id", $teacher_id);
                $get->execute();

                if($get->rowCount() == 0){
                    $count++;
                }
            }


        }

        return $count;

    }

    public function GetNotificationIdByAnnouncementId(
        $announcement_id, $school_year_id) {
            

        $get = $this->con->prepare("SELECT notification_id FROM notification 

            WHERE announcement_id = :announcement_id
            AND school_year_id = :school_year_id
            
        ");

        $get->bindValue(":announcement_id", $announcement_id);
        $get->bindValue(":school_year_id", $school_year_id);
        $get->execute();

        if($get->rowCount() > 0){
            return $get->fetchColumn();
        }   

        return NULL;
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

    public function StudentDueNotificationUpdateViewed(
        $notification_id, $student_id) {

        $now = date("Y-m-d H:i:s");

        if($this->CheckStudentViewedDueDateNotification($notification_id,
            $student_id) == false){

            $add = $this->con->prepare("UPDATE notification_view
            
                SET date_viewed=:now_date
                WHERE notification_id=:notification_id
                AND student_id=:student_id
                AND category= 'deadline'
                
            ");
            
            $add->bindValue(":now_date", $now);
            $add->bindValue(":notification_id", $notification_id);
            $add->bindValue(":student_id", $student_id);

            $add->execute();

            if($add->rowCount() > 0){
                return true;
            }
        }
        return false;
    }

    public function InsertStudentDueDateNotification(
        $notification_id, $student_id) {

        // $now = date("Y-m-d H:i:s");

        // if($this->CheckStudentViewedNotification($notification_id,
        //     $student_id) == false){

            $add = $this->con->prepare("INSERT INTO notification_view
                (student_id, notification_id, date_viewed, viewed_role, category)
                VALUES(:student_id, :notification_id, :date_viewed, :viewed_role, :category)");
            
            $add->bindValue(":student_id", $student_id);
            $add->bindValue(":notification_id", $notification_id);
            $add->bindValue(":date_viewed", NULL);
            $add->bindValue(":viewed_role", "student");
            $add->bindValue(":category", "deadline");
            $add->execute();

            if($add->rowCount() > 0){
                return true;
            }

        // }

        return false;
    }

    public function InsertDueDateNotification(
        $subject_code_assignment_id, $school_year_id, $subject_code) {

        // $now = date("Y-m-d H:i:s");

        // if($this->CheckStudentViewedNotification($notification_id,
        //     $student_id) == false){

            $add = $this->con->prepare("INSERT INTO notification
                (subject_code_assignment_id, school_year_id, subject_code, sender_role)
                VALUES(:subject_code_assignment_id, :school_year_id, :subject_code, :sender_role)");
            
            $add->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);
            $add->bindValue(":school_year_id", $school_year_id);
            $add->bindValue(":subject_code", $subject_code);
            $add->bindValue(":sender_role", "auto");
            $add->execute();

            if($add->rowCount() > 0){
                return true;
            }

        // }

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

    public function TeacherGradedStudentSubmissionNotification(
        $subject_code, $school_year_id,
        $subject_assignment_submission_id,
        $subject_code_assignment_id) {

        $sender_role = "teacher";

        $add = $this->con->prepare("INSERT INTO notification
            (sender_role, subject_code, school_year_id, subject_assignment_submission_id,
                subject_code_assignment_id)
            VALUES(:sender_role, :subject_code, :school_year_id, :subject_assignment_submission_id,
                :subject_code_assignment_id)");
        
        $add->bindValue(":sender_role", $sender_role);
        $add->bindValue(":subject_code", $subject_code);
        $add->bindValue(":school_year_id", $school_year_id);
        $add->bindValue(":subject_assignment_submission_id", $subject_assignment_submission_id);
        $add->bindValue(":subject_code_assignment_id", $subject_code_assignment_id);

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

    public function UpdateNotificationForTeacherAnnouncement(
        $school_year_id, $db_subject_code,
        $chosen_subject_code, $announcement_id) {

            $now = date("Y-m-d H:i:s");


        $sql = $this->con->prepare("UPDATE notification 
        
            SET 
            subject_code = :chosen_subject_code,
            date_creation = :todays_date

            WHERE announcement_id = :announcement_id
            AND school_year_id = :school_year_id
            AND subject_code = :subject_code
            
        ");

        $sql->bindValue(":chosen_subject_code", $chosen_subject_code);
        $sql->bindValue(":todays_date", $now);
        
        $sql->bindValue(":announcement_id", $announcement_id);
        $sql->bindValue(":school_year_id", $school_year_id);
        $sql->bindValue(":subject_code", $db_subject_code);

        $sql->execute();

        if ($sql->rowCount() > 0) {
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


    public function CheckGradedNotifFromTeacherBelongsToStudent(
        $notification_id, $student_id, $subject_assignment_submission_student_id) {
            
        $get = $this->con->prepare("SELECT notification_id FROM notification as t1

            INNER JOIN notification_view as t2 ON t2.notification_id = t1.notification_id

            AND t2.student_id = :student_id
            AND t2.student_id = :subject_assignment_submission_student_id


            WHERE t1.notification_id = :notification_id
            -- AND student_id = :student_id
            
        ");

        $get->bindValue(":notification_id", $notification_id);
        $get->bindValue(":student_id", $student_id);
        $get->bindValue(":subject_assignment_submission_student_id", $subject_assignment_submission_student_id);
        $get->execute();
 
        return $get->rowCount() > 0;

    }

}
?>