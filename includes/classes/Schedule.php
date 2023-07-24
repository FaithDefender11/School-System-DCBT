<?php

    class Schedule{

    private $con, $sqlData;


    public function __construct($con, $subject_schedule_id = null)
    {
        $this->con = $con;
        $this->sqlData = $subject_schedule_id;

        if(!is_array($subject_schedule_id)){
            
            $query = $this->con->prepare("SELECT * FROM subject_schedule
                WHERE subject_schedule_id=:subject_schedule_id");

            $query->bindValue(":subject_schedule_id", $subject_schedule_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetRoom() {
        return isset($this->sqlData['room']) ? $this->sqlData["room"] : ""; 
    }

    public function GetScheduleCourseId() {
        return isset($this->sqlData['course_id']) ? $this->sqlData["course_id"] : 0; 
    }

    public function GetTimeTo() {
        return isset($this->sqlData['time_to']) ? $this->sqlData["time_to"] : ""; 
    }

    public function GetTimeFrom() {
        return isset($this->sqlData['time_from']) ? $this->sqlData["time_from"] : 0; 
    }

    public function GetScheduleDay() {
        return isset($this->sqlData['schedule_day']) ? $this->sqlData["schedule_day"] : 0; 
    }

    public function GetScheduleTime() {
        return isset($this->sqlData['schedule_time']) ? $this->sqlData["schedule_time"] : 0; 
    }

    public function GetScheduleTeacherId() {
        return isset($this->sqlData['teacher_id']) ? $this->sqlData["teacher_id"] : 0; 
    }

    public function GetScheduleScheduleSubjectCode() {
        return isset($this->sqlData['subject_code']) ? $this->sqlData["subject_code"] : 0; 
    }




    public function CheckIdExists($subject_schedule_id) {

        $query = $this->con->prepare("SELECT * FROM subject_schedule
                WHERE subject_schedule_id=:subject_schedule_id");

        $query->bindParam(":subject_schedule_id", $subject_schedule_id);
        $query->execute();

        if($query->rowCount() == 0){
            echo "
                <div class='col-md-12'>
                    <h4 class='text-center text-warning'>ID Doesnt Exists.</h4>
                </div>
            ";
            exit();
        }
    }

    public function CheckIfTeacherAlreadyScheduleToTheSubject($subject_id,
        $teacher_id){

        echo $subject_id;
        echo "<br>";
        echo $teacher_id;
        echo "<br>";

        $sql = $this->con->prepare("SELECT * FROM subject_schedule as t1

            WHERE t1.subject_id=:subject_id
            AND t1.teacher_id=:teacher_id
        ");
                
        $sql->bindParam(":subject_id", $subject_id);
        $sql->bindParam(":teacher_id", $teacher_id);
        $sql->execute();

        return $sql->rowCount() > 0;
    }

    public function AddScheduleCodeBase($room, $time_from_meridian, $time_to_meridian,
        $schedule_day, $time_from_meridian_military, $time_to_meridian_military,
        $schedule_time, $current_school_year_id, $course_id,
        $teacher_id, $section_subject_code, $subject_program_id){

        $day_count = NULL;

        if($schedule_day == "M"){
            $day_count = 1;
        }
        if($schedule_day == "T"){
            $day_count = 2;
        }if($schedule_day == "W"){
            $day_count = 3;
        }if($schedule_day == "TH"){
            $day_count = 4;
        }if($schedule_day == "F"){
            $day_count = 5;
        }

        $sql = $this->con->prepare("INSERT INTO subject_schedule
                (room, schedule_day, time_from, time_to, schedule_time,
                    school_year_id, course_id, teacher_id, subject_code, subject_program_id, day_count)

                VALUES(:room, :schedule_day, :time_from, :time_to, :schedule_time,
                    :school_year_id, :course_id, :teacher_id, :subject_code, :subject_program_id, :day_count)");

        $schedule_time = $time_from_meridian . ' - ' . $time_to_meridian;

        $sql->bindParam(":room", $room);
        $sql->bindParam(":schedule_day", $schedule_day);
        $sql->bindParam(":time_from", $time_from_meridian_military);
        $sql->bindParam(":time_to", $time_to_meridian_military);
        $sql->bindParam(":schedule_time", $schedule_time);
        $sql->bindParam(":school_year_id", $current_school_year_id);
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":teacher_id", $teacher_id);
        $sql->bindParam(":subject_code", $section_subject_code);
        $sql->bindParam(":subject_program_id", $subject_program_id);
        $sql->bindParam(":day_count", $day_count);

        if($sql->execute() && $sql->rowCount() > 0){
            return true;
        }

        return false;
    }

    public function updateScheduleCodeBase($subject_schedule_id, $room, $time_from_meridian, $time_to_meridian,
        $schedule_day, $time_from_meridian_military, $time_to_meridian_military,
        $schedule_time, $current_school_year_id,
        $teacher_id, $section_subject_code){

        try {
            $sql = $this->con->prepare("UPDATE subject_schedule SET
                room = :room,
                schedule_day = :schedule_day,
                time_from = :time_from,
                time_to = :time_to,
                schedule_time = :schedule_time,
                school_year_id = :school_year_id,
                -- course_id = :course_id,
                teacher_id = :teacher_id,
                subject_code = :subject_code
                -- subject_program_id = :subject_program_id
                WHERE subject_schedule_id = :subject_schedule_id");

            $schedule_time = $time_from_meridian . ' - ' . $time_to_meridian;

            $sql->bindParam(":subject_schedule_id", $subject_schedule_id);
            $sql->bindParam(":room", $room);
            $sql->bindParam(":schedule_day", $schedule_day);
            $sql->bindParam(":time_from", $time_from_meridian_military);
            $sql->bindParam(":time_to", $time_to_meridian_military);
            $sql->bindParam(":schedule_time", $schedule_time);
            $sql->bindParam(":school_year_id", $current_school_year_id);
            // $sql->bindParam(":course_id", $course_id);
            $sql->bindParam(":teacher_id", $teacher_id);
            $sql->bindParam(":subject_code", $section_subject_code);
            // $sql->bindParam(":subject_program_id", $subject_program_id);

            $sql->execute();

            // Check if any rows were affected by the update
            if ($sql->rowCount() > 0) {
                return true;
            }

            return false;
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or display an appropriate message
            error_log("Database Error: " . $e->getMessage());
            return false;
        } catch (Exception $e) {
            // Log the error (optional) and provide a user-friendly message
            error_log("Application Error: " . $e->getMessage() . " Please reach-out immediately for administrator.");
            return false;
        }
    }


    // Should be passed by reference type to affect the original variable outside the function.
    public function filterSubsequentOccurrences(&$occurrences, &$subject) {

        if (isset($occurrences[$subject])) {
            // If subject occurred before, set it to an empty string
            $subject = "";
        } else {
            // Mark the subject as occurred
            $occurrences[$subject] = true;
        }
    }

}
?>