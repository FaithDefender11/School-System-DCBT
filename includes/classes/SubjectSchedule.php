<?php

    class SubjectSchedule{

        private $con, $subject_schedule_id, $sqlData;


        public function __construct($con, $subject_schedule_id = null){
            $this->con = $con;
            $this->subject_schedule_id = $subject_schedule_id;

            $query = $this->con->prepare("SELECT * FROM subject_schedule
                 WHERE subject_schedule_id=:subject_schedule_id");

            $query->bindValue(":subject_schedule_id", $subject_schedule_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);

            if($this->sqlData == null){

                $schedule_code = $subject_schedule_id;

                $query = $this->con->prepare("SELECT * FROM subject_schedule
                 WHERE subject_code=:subject_code");

                $query->bindValue(":subject_code", $schedule_code);
                $query->execute();

                $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
            }
        }


        public function GetScheduleId() {
            return isset($this->sqlData['subject_schedule_id']) ? $this->sqlData["subject_schedule_id"] : ""; 
        }

        public function GetSubjectProgramId() {
            return isset($this->sqlData['subject_program_id']) ? $this->sqlData["subject_program_id"] : NULL; 
        }

        public function GetTimeFrom() {
            return isset($this->sqlData['time_from']) ? $this->sqlData["time_from"] : ""; 
        }

        public function GetTimeTo() {
            return isset($this->sqlData['time_to']) ? $this->sqlData["time_to"] : ""; 
        }

        public function GetScheduleTime() {
            return isset($this->sqlData['schedule_time']) ? $this->sqlData["schedule_time"] : ""; 
        }

        public function GetScheduleDay() {
            return isset($this->sqlData['schedule_day']) ? $this->sqlData["schedule_day"] : ""; 
        }

        public function GetScheduleRoom() {
            return isset($this->sqlData['room']) ? $this->sqlData["room"] : ""; 
        }

        public function GetScheduleSchoolYearId() {
            return isset($this->sqlData['school_year_id']) ? $this->sqlData["school_year_id"] : ""; 
        }

        public function GetScheduleCode() {
            return isset($this->sqlData['subject_code']) ? $this->sqlData["subject_code"] : ""; 
        }

        public function GetScheduleCourseId() {
            return isset($this->sqlData['course_id']) ? $this->sqlData["course_id"] : 0; 
        }

        
        public function GetScheduleTeacherId() {
            return isset($this->sqlData['teacher_id']) ? $this->sqlData["teacher_id"] : 0; 
        }

        public function GetScheduleSubjectProgramId() {
            return isset($this->sqlData['subject_program_id']) ? $this->sqlData["subject_program_id"] : 0; 
        }
        public function GetScheduleDateCreation() {
            return isset($this->sqlData['date_creation']) ? $this->sqlData["date_creation"] : 0; 
        }

        
        public function GetScheduleTeacherBySectionSubjectCode($subject_code) {

            $query = $this->con->prepare("SELECT teacher_id FROM subject_schedule
                WHERE subject_code=:subject_code");

            $query->bindValue(":subject_code", $subject_code);
            $query->execute();

            if($query->rowCount() > 0){
                return $query->fetchColumn();
            }
            return 0;
        }

    }
?>