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

    }

?>
