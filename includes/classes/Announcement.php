<?php

    class Announcement{

        private $con, $announcement_id, $sqlData;

        public function __construct($con, $announcement_id = null){
            $this->con = $con;
            $this->announcement_id = $announcement_id;

            $query = $this->con->prepare("SELECT * FROM announcement
                WHERE announcement_id=:announcement_id");

            $query->bindValue(":announcement_id", $announcement_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

        }

        public function GetTeacherId() {
            return isset($this->sqlData['teacher_id']) ? $this->sqlData["teacher_id"] : NULL; 
        }
        public function GetUserId() {
            return isset($this->sqlData['users_id']) ? $this->sqlData["users_id"] : NULL; 
        }
        
        public function GetForStudents() {
            return isset($this->sqlData['for_student']) ? $this->sqlData["for_student"] : NULL; 
        }
        public function GetForTeachers() {
            return isset($this->sqlData['for_teacher']) ? $this->sqlData["for_teacher"] : NULL; 
        }

        public function GetTeachersIds() {
            return isset($this->sqlData['teachers_id']) ? $this->sqlData["teachers_id"] : NULL; 
        }

        public function GetSchoolYearId() {
            return isset($this->sqlData['school_year_id']) ? $this->sqlData["school_year_id"] : NULL; 
        }

        public function GetTitle() {
            return isset($this->sqlData['title']) ? $this->sqlData["title"] : ""; 
        }

        public function GetContent() {
            return isset($this->sqlData['content']) ? $this->sqlData["content"] : ""; 
        }

        public function GetSubjectCode() {
            return isset($this->sqlData['subject_code']) ? $this->sqlData["subject_code"] : ""; 
        }


        public function GetDateCreation() {
            return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : ""; 
        }

        
        public function InsertAnnouncement($teacher_id, $school_year_id,
            $subject_code, $title, $content){

            $now = date("Y-m-d H:i:s");
            $role = "teacher";


            $sql = $this->con->prepare("INSERT INTO announcement
                (role, teacher_id, school_year_id, title, content, subject_code)
                VALUES(:role, :teacher_id, :school_year_id, :title, :content, :subject_code)");
            
            $sql->bindValue(":role", $role);
            $sql->bindValue(":teacher_id", $teacher_id);
            $sql->bindValue(":school_year_id", $school_year_id);
            $sql->bindValue(":title", $title);
            $sql->bindValue(":content", $content);
            $sql->bindValue(":subject_code", $subject_code);

            $sql->execute();
            if($sql->rowCount() > 0){
                return true;
            }
            return false;
        }

        public function InsertAnnouncementForStudentOnly($users_id, $school_year_id, $title, $content){

            $role = "admin";

            $sql = $this->con->prepare("INSERT INTO announcement
                (users_id, role, school_year_id, title, content, for_student)
                VALUES(:users_id, :role, :school_year_id, :title, :content, :for_student)");
            
            $sql->bindValue(":users_id", $users_id);
            $sql->bindValue(":role", $role);
            $sql->bindValue(":school_year_id", $school_year_id);
            $sql->bindValue(":title", $title);
            $sql->bindValue(":content", $content);
            $sql->bindValue(":for_student", 1);

            $sql->execute();
            if($sql->rowCount() > 0){
                return true;
            }
            return false;
        }

        public function InsertAnnouncementForTeacherOnly($users_id, $school_year_id, $title, $content){

            $role = "admin";

            $sql = $this->con->prepare("INSERT INTO announcement
                (users_id, role, school_year_id, title, content, for_teacher)
                VALUES(:users_id, :role, :school_year_id, :title, :content, :for_teacher)");
            
            $sql->bindValue(":users_id", $users_id);
            $sql->bindValue(":role", $role);
            $sql->bindValue(":school_year_id", $school_year_id);
            $sql->bindValue(":title", $title);
            $sql->bindValue(":content", $content);
            $sql->bindValue(":for_teacher", 1);

            $sql->execute();
            if($sql->rowCount() > 0){
                return true;
            }
            return false;
        }

        public function InsertAnnouncementForBothStudentAndTeacher($users_id, $school_year_id, $title, $content){

            $role = "admin";

            $sql = $this->con->prepare("INSERT INTO announcement
                (users_id, role, school_year_id, title, content, for_teacher, for_student)
                VALUES(:users_id, :role, :school_year_id, :title, :content, :for_teacher, :for_student)");
            
            $sql->bindValue(":users_id", $users_id);
            $sql->bindValue(":role", $role);
            $sql->bindValue(":school_year_id", $school_year_id);
            $sql->bindValue(":title", $title);
            $sql->bindValue(":content", $content);
            $sql->bindValue(":for_teacher", 1);
            $sql->bindValue(":for_student", 1);

            $sql->execute();
            if($sql->rowCount() > 0){
                return true;
            }
            return false;
        }

        public function UpdateAnnouncement($announcement_id,
            $school_year_id, $teacher_id,
            $subject_code, $title, $content){


            $now = date("Y-m-d H:i:s");

            $sql = $this->con->prepare("UPDATE announcement SET
                title = :title,
                content = :content,
                subject_code = :subject_code,
                date_creation = :todays_date

                WHERE announcement_id = :announcement_id
                AND school_year_id = :school_year_id
                AND teacher_id = :teacher_id
                ");

            $sql->bindValue(":title", $title);
            $sql->bindValue(":content", $content);
            $sql->bindValue(":subject_code", $subject_code);
            $sql->bindValue(":todays_date", $now);

            $sql->bindValue(":announcement_id", $announcement_id);
            $sql->bindValue(":school_year_id", $school_year_id);
            $sql->bindValue(":teacher_id", $teacher_id);

            $sql->execute();

            if($sql->rowCount() > 0){
                return true;
            }
            return false;
        }



        public function InsertAdminAnnouncement($admin_id, $teacher_id,
            $school_year_id, $title, $content, $student_selected){

            if($student_selected == "on"){
                $student_selected = 1;
            }else{
                $student_selected = NULL;
            }
            $role = "admin";

            $sql = $this->con->prepare("INSERT INTO announcement
                (role, teachers_id, users_id, school_year_id,
                    title, content, for_student)
                VALUES(:role, :teachers_id, :users_id, :school_year_id,
                    :title, :content, :for_student)");
            
            $sql->bindValue(":role", $role);
            $sql->bindValue(":teachers_id", $teacher_id);
            $sql->bindValue(":users_id", $admin_id);
            $sql->bindValue(":school_year_id", $school_year_id);
            $sql->bindValue(":title", $title);
            $sql->bindValue(":content", $content);
            $sql->bindValue(":for_student", $student_selected);

            $sql->execute();

            if($sql->rowCount() > 0){
                return true;
            }

            return false;
        }

        public function EditAdminAnnouncement(
            $announcement_id,$admin_id,
            $school_year_id, $title, $content, $student_selected, $teacher_selected){

            if($student_selected == "on"){
                $student_selected = 1;
            }else{
                $student_selected = NULL;
            }

            if($teacher_selected == "on"){
                $teacher_selected = 1;
            }else{
                $teacher_selected = NULL;
            }
            
            $now = date("Y-m-d H:i:s");

            $role = "admin";

            $sql = $this->con->prepare("UPDATE announcement
                SET role = :role,
                    users_id = :users_id,
                    school_year_id = :school_year_id,
                    title = :title,
                    content = :content,
                    for_student = :for_student,
                    for_teacher = :for_teacher,
                    date_creation = :date_now
                WHERE announcement_id = :announcement_id
            ");
            
            $sql->bindValue(":announcement_id", $announcement_id);
            $sql->bindValue(":role", $role);
            $sql->bindValue(":users_id", $admin_id);
            $sql->bindValue(":school_year_id", $school_year_id);
            $sql->bindValue(":title", $title);
            $sql->bindValue(":content", $content);
            $sql->bindValue(":for_student", $student_selected);
            $sql->bindValue(":for_teacher", $teacher_selected);
            $sql->bindValue(":date_now", $now);

            $sql->execute();

            if($sql->rowCount() > 0){
                return true;
            }

            return false;
        }

        public function GetAnnouncementsWithinSubjectCode(
            $subject_code, $teacher_id) {

            $get = $this->con->prepare("SELECT * FROM announcement
                WHERE subject_code=:subject_code
                AND teacher_id=:teacher_id
                ");

            $get->bindValue(":subject_code", $subject_code);
            $get->bindValue(":teacher_id", $teacher_id);
            $get->execute();

            if($get->rowCount() > 0){

                return $get->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        }

        public function CheckTeacherIdBelongsToAdminAnnouncement(
            $school_year_id, $teacherLoggedInId) {

            $get = $this->con->prepare("SELECT * FROM announcement
                WHERE FIND_IN_SET(:teacherLoggedInId, teachers_id)
                AND school_year_id = :school_year_id");

            $get->bindValue(":teacherLoggedInId", $teacherLoggedInId);
            $get->bindValue(":school_year_id", $school_year_id);
            $get->execute();

            if($get->rowCount() > 0){

                return $get->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];

        }

        public function GetTeacherAdminAnnouncement(
            $school_year_id, $teacherLoggedInId) {

            $get = $this->con->prepare("SELECT * FROM announcement
                WHERE announcement IS NOT NULL
                AND school_year_id = :school_year_id");

            $get->bindValue(":teacherLoggedInId", $teacherLoggedInId);
            $get->bindValue(":school_year_id", $school_year_id);
            $get->execute();

            if($get->rowCount() > 0){

                return $get->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];

        }

        public function GetAnnouncementsAsStudentBasedOnSubject(
            $subject_code, $teacher_id, $student_id, $school_year_id) {

            $get = $this->con->prepare("SELECT t1.* FROM announcement as t1

                INNER JOIN student_subject as t2 ON t2.subject_code= t1.subject_code
                AND t2.student_id=:student_id
                AND t2.school_year_id=:school_year_id

                WHERE t1.subject_code=:subject_code
                AND t1.teacher_id=:teacher_id

            ");

            $get->bindValue(":subject_code", $subject_code);
            $get->bindValue(":teacher_id", $teacher_id);
            $get->bindValue(":student_id", $student_id);
            $get->bindValue(":school_year_id", $school_year_id);
            $get->execute();

            if($get->rowCount() > 0){

                return $get->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        }


        public function CheckStudentViewedGivenAnnouncement(
            $announcement_id, $student_id) {

            $get = $this->con->prepare("SELECT t1.* 
            
                FROM announcement_user as t1

                WHERE t1.announcement_id=:announcement_id
                AND t1.student_id=:student_id
                ");
            $get->bindValue(":announcement_id", $announcement_id);
            $get->bindValue(":student_id", $student_id);
            $get->execute();

            return $get->rowCount() > 0;
        }

        public function StudentAnnouncementAsViewed(
            $announcement_id, $student_id) {

            $now = date("Y-m-d H:i:s");

            if($this->CheckStudentViewedGivenAnnouncement($announcement_id,
                $student_id) == false){

                $add = $this->con->prepare("INSERT INTO announcement_user
                    (student_id, announcement_id, date_viewed)
                    VALUES(:student_id, :announcement_id, :date_viewed)");
                
                $add->bindValue(":student_id", $student_id);
                $add->bindValue(":announcement_id", $announcement_id);
                $add->bindValue(":date_viewed", $now);
                $add->execute();

                if($add->rowCount() > 0){
                    return true;
                }
            }
            return false;
        }


        public function GetAllTeacherAnnouncement($school_year_id) {

            $get = $this->con->prepare("SELECT t1.* 
            
                FROM announcement as t1

                WHERE t1.school_year_id=:school_year_id
                ");

            $get->bindValue(":school_year_id", $school_year_id);
            $get->execute();

            if($get->rowCount() > 0){

                return $get->fetchAll(PDO::FETCH_ASSOC);
            }
            return [];
        }

        

        public function GetStudentViewedAnnouncementId($announcement_id) {

            $get = $this->con->prepare("SELECT t1.*
            
                FROM announcement_user as t1

                WHERE t1.announcement_id=:announcement_id
                ORDER BY announcement_user_id DESC
                LIMIT 1
                ");

            $get->bindValue(":announcement_id", $announcement_id);
            $get->execute();

            if($get->rowCount() > 0){

                // return $get->fetchAll(PDO::FETCH_ASSOC);
                return $get->fetch(PDO::FETCH_ASSOC);
                // return $get->fetchColumn();

            }
            // return $get->rowCount() > 0;
            return NULL;
        }

        public function TeacherNotificationMarkAsViewed($announcement_id, $teacher_id) {

            $now = date("Y-m-d H:i:s");

            if($this->CheckTeacherViewedAnnouncement($announcement_id,
                $teacher_id) == false){

                $add = $this->con->prepare("INSERT INTO announcement_user
                    (teacher_id, announcement_id, date_viewed)
                    VALUES(:teacher_id, :announcement_id, :date_viewed)");
                
                $add->bindValue(":teacher_id", $teacher_id);
                $add->bindValue(":announcement_id", $announcement_id);
                $add->bindValue(":date_viewed", $now);
                $add->execute();

                if($add->rowCount() > 0){
                    return true;
                }
            }
            return false;
        }

        public function CheckTeacherViewedAnnouncement($announcement_id,
            $teacher_id) {

            $get = $this->con->prepare("SELECT t1.* 
            
                FROM announcement_user as t1

                WHERE t1.teacher_id=:teacher_id
                AND t1.announcement_id=:announcement_id
                AND t1.date_viewed IS NOT NULL
                
                ");
            $get->bindValue(":teacher_id", $teacher_id);
            $get->bindValue(":announcement_id", $announcement_id);
            $get->execute();

            return $get->rowCount() > 0;
        }

        public function GetAdminAnnouncementList(
            $school_year_id = null, $admin_id) {

            $get = $this->con->prepare("SELECT t1.* 
            
                FROM announcement as t1

                WHERE t1.role=:role
                AND t1.users_id=:users_id
                
            ");

            $get->bindValue(":role", "admin");
            $get->bindValue(":users_id", $admin_id);
            $get->execute();

            if($get->rowCount() > 0){

                return $get->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];
        }

        public function GetTeacherAnnouncementFromAdmin(
            $teacher_id = null, $admin_id) {

            $get = $this->con->prepare("SELECT t1.* 
            
                FROM announcement as t1

                WHERE t1.role=:role
                AND t1.users_id=:users_id
                
            ");

            $get->bindValue(":role", "admin");
            $get->bindValue(":users_id", $admin_id);
            $get->execute();

            if($get->rowCount() > 0){

                return $get->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];
        }

        public function GetAllAnnouncementFromAdmin($school_year_id) {

            $get = $this->con->prepare("SELECT t1.*
            
            -- , t2.firstName, t2.lastName, t2.role
            
                FROM announcement as t1

                INNER JOIN users as t2 ON t2.user_id = t1.users_id

                WHERE t1.for_student=:for_student
                AND t1.school_year_id=:school_year_id
                
            ");

            $get->bindValue(":for_student", 1);
            $get->bindValue(":school_year_id", $school_year_id);
            $get->execute();

            if($get->rowCount() > 0){

                return $get->fetchAll(PDO::FETCH_ASSOC);
            }

            return [];
        }


        public function GetAllTeacherAnnouncementUnderEnrolledSubjects(
            $school_year_id, $enrolledSubjectList) {
            
            if (count($enrolledSubjectList) > 0) {

                // echo "hey";

                $inPlaceholders = implode(', ', array_map(function($value, $index) {
                    return ":subject_code$index";
                }, $enrolledSubjectList, array_keys($enrolledSubjectList)));

                $query = $this->con->prepare("SELECT t1.* 
                    FROM announcement as t1
                    WHERE subject_code IN ($inPlaceholders)
                    AND school_year_id = :school_year_id
                ");

                foreach ($enrolledSubjectList as $index => $subjectCode) {
                    $placeholderName = ":subject_code$index";
                    $query->bindValue($placeholderName, $subjectCode);
                }
                
                $query->bindValue(":school_year_id", $school_year_id);
                $query->execute();

                if ($query->rowCount() > 0) {
                    return $query->fetchAll(PDO::FETCH_ASSOC);
                }
            }

            return [];
        }



    }


 


?>
