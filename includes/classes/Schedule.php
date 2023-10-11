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
        return isset($this->sqlData['room_id']) ? $this->sqlData["room_id"] : NULL; 
    }

    public function GetSubjectCode() {
        return isset($this->sqlData['subject_code']) ? $this->sqlData["subject_code"] : ""; 
    }

    public function GetSubjectProgramId() {
        return isset($this->sqlData['subject_program_id']) ? $this->sqlData["subject_program_id"] : NULL; 
    }


    public function GetRoomId() {
        return isset($this->sqlData['room_id']) ? $this->sqlData["room_id"] : NULL; 
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
        return isset($this->sqlData['teacher_id']) ? $this->sqlData["teacher_id"] : NULL; 
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



    public function AddScheduleCodeBase(
        $room_id,
        $time_from_meridian, $time_to_meridian,
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
                (room_id, schedule_day, time_from, time_to, schedule_time,
                    school_year_id, course_id, teacher_id, subject_code, subject_program_id, day_count)

                VALUES(:room_id, :schedule_day, :time_from, :time_to, :schedule_time,
                    :school_year_id, :course_id, :teacher_id, :subject_code, :subject_program_id, :day_count)");

        $schedule_time = $time_from_meridian . ' - ' . $time_to_meridian;

        $sql->bindParam(":room_id", $room_id);
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

    public function AddSubjectCodeSchedule( 
        $time_from_meridian, $time_to_meridian,
        $schedule_day, $time_from_meridian_military, $time_to_meridian_military,
        $schedule_time, $current_school_year_id, $course_id,
        $teacher_id, $section_subject_code, $subject_program_id, $room_id){

        $day_count = NULL;
        
        switch ($schedule_day) {
            case "M":
                $day_count = 1;
                break;
            case "T":
                $day_count = 2;
                break;
            case "W":
                $day_count = 3;
                break;
            case "TH":
                $day_count = 4;
                break;
            case "F":
                $day_count = 5;
                break;
            default:
                // Handle cases where $schedule_day doesn't match any of the specified values.
                // You may want to assign a default value or handle it differently.
                break;
        }


        # TO DELETE.
        $teacher_id = $teacher_id == 0 ? NULL : $teacher_id;
        $room_id = $room_id == 0 ? NULL : $room_id;


        // echo "time_from_meridian_military: $time_from_meridian_military";
        // echo "<br>";

        // echo "time_to_meridian_military: $time_to_meridian_military";
        // echo "<br>";
        // return;

        if($room_id !== NULL){

            $schedule_id_conflict = $this->CheckScheduleDayWithRoomConflict(
                $time_from_meridian_military, $time_to_meridian_military,
                $schedule_day, $current_school_year_id, $room_id);

            if($schedule_id_conflict !== NULL){

                $scheduleConflict = new Schedule($this->con, $schedule_id_conflict);

                $time_from = $scheduleConflict->GetTimeFrom();
                $time_from = $this->convertTo12HourFormat($time_from);

                $time_to = $scheduleConflict->GetTimeTo();
                $time_to = $this->convertTo12HourFormat($time_to);

                $day = $scheduleConflict->GetScheduleDay();
                $room_conflicted_id = $scheduleConflict->GetRoomId();

                $room_conflict = new Room($this->con, $room_conflicted_id);
                $room_number = $room_conflict->GetRoomNumber();

                $room_inserted = new Room($this->con, $room_id);
                $room_inserted_number = $room_inserted->GetRoomNumber();

                $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

                Alert::conflictedMessage("Conflicted Schedule: $time_from - $time_to <br> ($day) Room: $room_number",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ($schedule_day) Room: $room_inserted_number", "");

                exit();
            }

        }



        if($teacher_id !== NULL){

            $check_teacher_id_conflict = $this->CheckTeacherScheduleConflicted(
                $time_from_meridian_military, $time_to_meridian_military,
                $schedule_day, $teacher_id);

            if($check_teacher_id_conflict !== NULL){

                // var_dump($check_teacher_id_conflict);
                // return;

                $scheduleConflict = new Schedule($this->con, $check_teacher_id_conflict);

                $time_from = $scheduleConflict->GetTimeFrom();
                $time_from = $this->convertTo12HourFormat($time_from);

                $time_to = $scheduleConflict->GetTimeTo();
                $time_to = $this->convertTo12HourFormat($time_to);

                $day = $scheduleConflict->GetScheduleDay();
                $room_conflicted_id = $scheduleConflict->GetRoomId();

                $room_conflict = new Room($this->con, $room_conflicted_id);
                $room_number = $room_conflict->GetRoomNumber();

                if($room_number == NULL){
                    $room_number = "N/A";
                }
                $room_inserted = new Room($this->con, $room_id);
                $room_inserted_number = $room_inserted->GetRoomNumber();


                $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

                // $time_from_meridian_military = $this->convertTo12HourFormat($raw_time_from);
                // $time_to_meridian_military = $this->convertTo12HourFormat($raw_time_to);

                Alert::conflictedMessage("Teacher Conflicted Schedule: $time_from - $time_to <br> ($day) Room: $room_number",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ($schedule_day) Room: $room_inserted_number", "");
                exit();  
                    
                // Alert::error("Selected schedule day along with time for teacher is conflicted", "");
                // exit();  

            }
        }
        

        $sql = $this->con->prepare("INSERT INTO subject_schedule
            (schedule_day, time_from, time_to, schedule_time,
                school_year_id, course_id, teacher_id, subject_code,
                subject_program_id, day_count, room_id)

            VALUES(:schedule_day, :time_from, :time_to, :schedule_time,
                :school_year_id, :course_id, :teacher_id, :subject_code,
                :subject_program_id, :day_count, :room_id)
        ");

        $schedule_time = $time_from_meridian . ' - ' . $time_to_meridian;

        // $sql->bindParam(":room", $room);
        $sql->bindParam(":schedule_day", $schedule_day);
        $sql->bindParam(":time_from", $time_from_meridian_military);
        $sql->bindParam(":time_to", $time_to_meridian_military);
        $sql->bindParam(":schedule_time", $schedule_time);
        $sql->bindParam(":school_year_id", $current_school_year_id);
        $sql->bindParam(":course_id", $course_id);
        $sql->bindValue(":teacher_id", $teacher_id);
        $sql->bindParam(":subject_code", $section_subject_code);
        $sql->bindParam(":subject_program_id", $subject_program_id);
        $sql->bindParam(":day_count", $day_count);
        $sql->bindParam(":room_id", $room_id);
        $sql->execute();

        if($sql->rowCount() > 0){
            return true;
        }


        return false;
    }

    public function convertTo12HourFormat($time24Hour) {
        // Convert the 24-hour time to 12-hour format with AM/PM
        $time12Hour = date("h:i A", strtotime($time24Hour));
        return $time12Hour;
    }

    public function UpdateSubjectSchedule(
        $subject_schedule_id, $schedule_day,

        $time_from, $time_to, $raw_time_from, $raw_time_to,

        $school_year_id, $course_id,
        $teacher_id, $subject_code, 
        $subject_program_id, $room_id) {

        $time_from = trim($time_from);
        $time_to = trim($time_to);

        $raw_time_from = trim($raw_time_from);
        $raw_time_to = trim($raw_time_to);


        // var_dump($time_from);
        // echo "<br>";
        // var_dump($time_to);
        // echo "<br>";
        // return;

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


        // if($this->CheckScheduleDayWithRoomConflict(
        //     $time_from, $time_to,
        //     $schedule_day, $school_year_id,
        //     $room_id, $subject_schedule_id) === true){

        //     Alert::errorToast("Schedule day with time has conflicted with selected room.", "");
        //     exit();
        // }

        if($room_id !== NULL){

            $schedule_id_conflict = $this->CheckScheduleDayWithRoomConflict(
                $time_from, $time_to,
                $schedule_day, $school_year_id,
                $room_id, $subject_schedule_id);

            if($schedule_id_conflict !== NULL){

                // var_dump($schedule_id_conflict);
                // return;
                $scheduleConflict = new Schedule($this->con, $schedule_id_conflict);

                $time_from = $scheduleConflict->GetTimeFrom();
                $time_from = $this->convertTo12HourFormat($time_from);

                $time_to = $scheduleConflict->GetTimeTo();
                $time_to = $this->convertTo12HourFormat($time_to);

                $day = $scheduleConflict->GetScheduleDay();
                $room_conflicted_id = $scheduleConflict->GetRoomId();

                $room_conflict = new Room($this->con, $room_conflicted_id);
                $room_number = $room_conflict->GetRoomNumber();

                // var_dump($room_number);
                // return;

                if($room_number == NULL){
                    $room_number = "N/A";
                }

                $room_inserted = new Room($this->con, $room_id);
                $room_inserted_number = $room_inserted->GetRoomNumber();


                // $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                // $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

                
                $time_from_meridian_military = $this->convertTo12HourFormat($raw_time_from);
                $time_to_meridian_military = $this->convertTo12HourFormat($raw_time_to);

                Alert::conflictedMessage("Conflicted Schedule: $time_from - $time_to <br> ($day) Room: $room_number",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ($schedule_day) Room: $room_inserted_number", "");
                
                // Alert::errorToast("Schedule day with time has conflicted with selected room.", "");
                exit();

            }   
        }

        if($teacher_id !== NULL){

            $check_teacher_id_conflict = $this->CheckTeacherScheduleConflicted(
                $time_from, $time_to, $schedule_day, $teacher_id, $subject_schedule_id);

            if($check_teacher_id_conflict !== NULL){

                // var_dump($check_teacher_id_conflict);
                // return;

                $scheduleConflict = new Schedule($this->con, $check_teacher_id_conflict);

                $time_from = $scheduleConflict->GetTimeFrom();
                $time_from = $this->convertTo12HourFormat($time_from);

                $time_to = $scheduleConflict->GetTimeTo();
                $time_to = $this->convertTo12HourFormat($time_to);

                $day = $scheduleConflict->GetScheduleDay();
                $room_conflicted_id = $scheduleConflict->GetRoomId();

                $room_conflict = new Room($this->con, $room_conflicted_id);
                $room_number = $room_conflict->GetRoomNumber();
                if($room_number == NULL){
                    $room_number = "N/A";
                }
                $room_inserted = new Room($this->con, $room_id);
                $room_inserted_number = $room_inserted->GetRoomNumber();

                if($room_inserted_number == NULL){
                    $room_inserted_number = "N/A";
                }

                // $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                // $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

                $time_from_meridian_military = $this->convertTo12HourFormat($raw_time_from);
                $time_to_meridian_military = $this->convertTo12HourFormat($raw_time_to);

                Alert::conflictedMessage("Teacher Conflicted Schedule: $time_from - $time_to <br> ($day) Room: $room_number",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ($schedule_day) Room: $room_inserted_number", "");
                exit();  
                    
                // Alert::error("Selected schedule day along with time for teacher is conflicted", "");
                // exit();  

            }
        }

            // Prepare the UPDATE query
        $sql = $this->con->prepare("UPDATE subject_schedule
            SET schedule_day = :schedule_day,
                time_from = :time_from,
                time_to = :time_to,
                school_year_id = :school_year_id,
                course_id = :course_id,
                teacher_id = :teacher_id,
                subject_code = :subject_code,
                subject_program_id = :subject_program_id,
                day_count = :day_count,
                room_id = :room_id,
                schedule_time = :schedule_time
            WHERE subject_schedule_id = :subject_schedule_id
        ");

        // Concatenate time_from and time_to for schedule_time

        $schedule_time = $raw_time_from . ' - ' . $raw_time_to;
        
        // $schedule_time = $time_from . ' - ' . $time_to;

        // Bind parameters
        $sql->bindParam(":subject_schedule_id", $subject_schedule_id);
        $sql->bindParam(":schedule_day", $schedule_day);
        $sql->bindParam(":schedule_time", $schedule_time);
        $sql->bindParam(":time_from", $time_from);
        $sql->bindParam(":time_to", $time_to);
        $sql->bindParam(":school_year_id", $school_year_id);
        $sql->bindParam(":course_id", $course_id);
        $sql->bindParam(":teacher_id", $teacher_id);
        $sql->bindParam(":subject_code", $subject_code);
        $sql->bindParam(":subject_program_id", $subject_program_id);
        $sql->bindParam(":day_count", $day_count);
        $sql->bindParam(":room_id", $room_id);

        // Execute the UPDATE query
        $sql->execute();

        // Check if the UPDATE was successful
        if ($sql->rowCount() > 0) {
            return true; // Update successful
        } 

        return false; // Update failed
    }


    public function CheckScheduleDayWithRoomConflict(
        $userTimeFrom, $userTimeTo, $userScheduleDay,
        $school_year_id, $room_id = null, $subject_schedule_id = null) {

        // if($room_id === NULL) return false;
        
            // var_dump($userScheduleDay);

        // var_dump($userTimeFrom);
        // echo "<br>";
        // var_dump($userTimeTo);
        // echo "<br>";
        // echo "<br>";

        $room_output_query = NULL;
        // if($room_id !== NULL){
        //     $room_output_query = "AND room_id=:room_id";
        // }

        $subject_schedule_output_query = "";

        # If editing, it should remove the current subject_schedule_id
        # From finding other schedules

        if($subject_schedule_id !== NULL){
            $subject_schedule_output_query = "AND subject_schedule_id !=:subject_schedule_id";
        }

        $stmt = $this->con->prepare("SELECT * FROM subject_schedule 
        
            WHERE schedule_day = :userScheduleDay
            -- $room_output_query
            AND room_id=:room_id
            AND school_year_id=:school_year_id
            $subject_schedule_output_query
        ");

        $stmt->bindParam(':userScheduleDay', $userScheduleDay);
        $stmt->bindParam(':room_id', $room_id);
        $stmt->bindParam(':school_year_id', $school_year_id);

        // if($room_id !== NULL){
        //     $stmt->bindParam(':room_id', $room_id);
        // }

        if($subject_schedule_id !== NULL){
            $stmt->bindParam(':subject_schedule_id', $subject_schedule_id);
        }

        $stmt->execute();
        if($stmt->rowCount() > 0){

            $userTimeFrom = trim($userTimeFrom);
            $userTimeTo = trim($userTimeTo);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $existingTimeFrom = $row['time_from'];
                $existingTimeFrom = trim($existingTimeFrom);

                $existingTimeTo = $row['time_to'];
                $existingTimeTo = trim($existingTimeTo);
                $subject_schedule_id = $row['subject_schedule_id']; // Get the Class ID

                // if (
                //     ($userTimeFrom >= $existingTimeFrom && $userTimeFrom <= $existingTimeTo) ||
                //     ($userTimeTo >= $existingTimeFrom && $userTimeTo <= $existingTimeTo) ||
                //     ($userTimeFrom <= $existingTimeFrom && $userTimeTo >= $existingTimeTo))
                // {
                //     return true; // Conflict found
                // }

                // echo "userTimeFrom: " . var_dump($userTimeFrom);
                // echo "<br>";
                // echo "existingTimeTo: " . var_dump($existingTimeTo);
                // echo "<br>";


                if (
                    ($userTimeFrom >= $existingTimeTo) ||
                    ($userTimeTo <= $existingTimeFrom) ||
                    ($existingTimeTo == $userTimeFrom) // Add this condition
                ) {
                    continue; // No conflict found, check the next schedule
                } else {

                    // echo "subject_schedule_id: $subject_schedule_id";
                    return $subject_schedule_id;
                    // Conflict schedule ID FOUND
                    // return $subject_schedule_id; 

                    // return true; // Conflict found
                }
            }
        }
        return NULL; // No conflicts found
        // return false; // No conflicts found
    }

    public function CheckTeacherScheduleConflicted2(
        $userTimeFrom, $userTimeTo, $schedule_day,
            $teacher_id, $subject_schedule_id = null) {


        // if($teacher_id === NULL) return false;

        // var_dump($userTimeFrom);
        // echo "<br>";
        // var_dump($userTimeTo);
        // echo "<br>";
        // echo "<br>";

        $subject_schedule_output_query = "";

        if($subject_schedule_id !== NULL){
            $subject_schedule_output_query = "AND subject_schedule_id !=:subject_schedule_id";
        }
 
        $stmt = $this->con->prepare("SELECT * FROM subject_schedule 
        
            WHERE schedule_day = :schedule_day
            AND teacher_id = :teacher_id
            $subject_schedule_output_query
        ");
        
        $stmt->bindParam(':schedule_day', $schedule_day);
        $stmt->bindParam(':teacher_id', $teacher_id);

        if($subject_schedule_id !== NULL){
            $stmt->bindParam(':subject_schedule_id', $subject_schedule_id);
        }
       
        $stmt->execute();

        if($stmt->rowCount() > 0){

            $userTimeFrom = trim($userTimeFrom);
            $userTimeTo = trim($userTimeTo);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $existingTimeFrom = $row['time_from'];
                $existingTimeFrom = trim($existingTimeFrom);

                $existingTimeTo = $row['time_to'];
                $existingTimeTo = trim($existingTimeTo);
                $subject_schedule_id = $row['subject_schedule_id']; // Get the Class ID

               


                if (
                    ($userTimeFrom >= $existingTimeTo) ||
                    ($userTimeTo <= $existingTimeFrom) ||
                    ($userTimeFrom == $existingTimeFrom) // Add this condition
                ) {
                    continue; // No conflict found, check the next schedule
                } else {

                    // Conflict schedule ID FOUND
                    // return $subject_schedule_id; 
                    // return true; // Conflict found
                    return $subject_schedule_id;
                }
            }
        }
        return NULL; // No conflicts found
    }

    public function CheckTeacherScheduleConflictedH(
        $userTimeFrom, $userTimeTo, $schedule_day,
            $teacher_id, $subject_schedule_id = null) {
 
        $stmt = $this->con->prepare("SELECT * FROM subject_schedule 
        
            WHERE schedule_day = :schedule_day
            AND teacher_id = :teacher_id
        ");
        
        $stmt->bindParam(':schedule_day', $schedule_day);
        $stmt->bindParam(':teacher_id', $teacher_id);

        $stmt->execute();

        if($stmt->rowCount() > 0){

            $userTimeFrom = trim($userTimeFrom);
            $userTimeTo = trim($userTimeTo);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $existingTimeFrom = $row['time_from'];
                $existingTimeFrom = trim($existingTimeFrom);

                $existingTimeTo = $row['time_to'];
                $existingTimeTo = trim($existingTimeTo);
                $subject_schedule_id = $row['subject_schedule_id']; // Get the Class ID


                if (
                    ($userTimeFrom >= $existingTimeTo) ||
                    ($userTimeTo <= $existingTimeFrom) ||
                    ($userTimeFrom == $existingTimeFrom) // Add this condition
                ) {
                    continue; // No conflict found, check the next schedule
                } else {

                    // Conflict schedule ID FOUND
                    // return true; // Conflict found
                    return $subject_schedule_id;
                }
            }
        }
        return NULL; // No conflicts found
    }

    public function CheckIfTimeIsEqual($time_from_meridian, $time_to_meridian
    ) {

        if($time_from_meridian == $time_to_meridian){
            Alert::error("Schedule time should not be equal.", "");
            exit();
        }
    }

    public function CheckIfTimeFromIsGreater($time_from_meridian, $time_to_meridian
    ) {

        $timestamp_to = strtotime($time_to_meridian);
        $timestamp_from = strtotime($time_from_meridian);

        if ($timestamp_from > $timestamp_to) {
            // echo "time_from is greater than time_to";
            Alert::error("Time from should greater than Time to", "");
            exit();
        }
    }

    public function CheckTeacherScheduleConflicted(
        $userTimeFrom, $userTimeTo, $schedule_day,
        $teacher_id, $subject_schedule_id = null
    ) {

        $subject_schedule_output_query = "";

        if($subject_schedule_id !== NULL){
            $subject_schedule_output_query = "AND subject_schedule_id !=:subject_schedule_id";
        }
        $stmt = $this->con->prepare("SELECT 
        
            subject_schedule_id, time_from, time_to 
        
            FROM subject_schedule

            WHERE schedule_day = :schedule_day
            AND teacher_id = :teacher_id
            $subject_schedule_output_query

        ");

        $stmt->bindParam(':schedule_day', $schedule_day);
        $stmt->bindParam(':teacher_id', $teacher_id);

        if($subject_schedule_id !== NULL){
            $stmt->bindParam(':subject_schedule_id', $subject_schedule_id);
        }

        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $userTimeFrom = strtotime(trim($userTimeFrom));
            $userTimeTo = strtotime(trim($userTimeTo));

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $existingTimeFrom = strtotime(trim($row['time_from']));
                $existingTimeTo = strtotime(trim($row['time_to']));
                $subject_schedule_id = $row['subject_schedule_id']; // Get the Class ID

                if (
                    ($userTimeFrom >= $existingTimeTo) ||
                    ($userTimeTo <= $existingTimeFrom) 
                    // ||  ($userTimeFrom == $existingTimeFrom)
                    
                ) {
                    continue; // No conflict found, check the next schedule
                } else {
                    // Conflict schedule ID FOUND
                    return $subject_schedule_id;
                }
            }
        }

        return null; // No conflicts found
    }


    public function UpdateScheduleCodeBase(
        $room_id,
        $subject_schedule_id, $time_from_meridian, $time_to_meridian,
        $schedule_day, $time_from_meridian_military, $time_to_meridian_military,
        $schedule_time, $current_school_year_id,
        $teacher_id, $section_subject_code, $current_teacher_id = null){

        $selected_teacher_id = $teacher_id;
        

        try {


            if($selected_teacher_id != $current_teacher_id){

                # Update the Teaching Code.
                // $subjectPeriodCode = new SubjectPeriodCode($this->con);
                
                // $adjustTeacherOnSubjectCode = $subjectPeriodCode->AdjustTeacherOnTeachingSubjectCode(
                //     $current_teacher_id, $selected_teacher_id,
                //     $current_school_year_id, $section_subject_code
                // );
            }
            
            $sql = $this->con->prepare("UPDATE subject_schedule SET
                room_id = :room_id,
                schedule_day = :schedule_day,
                time_from = :time_from,
                time_to = :time_to,
                schedule_time = :schedule_time,
                school_year_id = :school_year_id,
                teacher_id = :teacher_id,
                subject_code = :subject_code
                -- subject_program_id = :subject_program_id
                WHERE subject_schedule_id = :subject_schedule_id");

            $schedule_time = $time_from_meridian . ' - ' . $time_to_meridian;

            $sql->bindParam(":subject_schedule_id", $subject_schedule_id);
            $sql->bindParam(":room_id", $room_id);
            $sql->bindParam(":schedule_day", $schedule_day);
            $sql->bindParam(":time_from", $time_from_meridian_military);
            $sql->bindParam(":time_to", $time_to_meridian_military);
            $sql->bindParam(":schedule_time", $schedule_time);
            $sql->bindParam(":school_year_id", $current_school_year_id);
            $sql->bindParam(":teacher_id", $teacher_id);
            $sql->bindParam(":subject_code", $section_subject_code);

            $sql->execute();

            // Check if any rows were affected by the update
            if ($sql->rowCount() > 0) {
                return true;
            }

            return false;
        } catch (PDOException $e) {

            error_log("Database Error: " . $e->getMessage());
            return false;

        } catch (Exception $e) {

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

    public function filterSubsequentOccurrencesSa(&$occurrences, &$subject, 
        $course_id, $subject_program_id) {

        $query = $this->con->prepare("SELECT * FROM subject_schedule
                WHERE course_id=:course_id
                AND subject_program_id=:subject_program_id
                ");

        $query->bindParam(":course_id", $course_id);
        $query->bindParam(":subject_program_id", $subject_program_id);
        $query->execute();

        if($query->rowCount() > 1){
            if (isset($occurrences[$subject])) {
                // If subject occurred before, set it to an empty string
                $subject = "";
            } else {
                // Mark the subject as occurred
                $occurrences[$subject] = true;
            }
        }
        
    }

    function convertToDays($name) {
        $name = strtoupper($name); // Convert to uppercase for case-insensitive comparison
        if ($name === 'M') {
            return "Monday";
        } elseif ($name === 'T') {
            return "Tuesday";
        } elseif ($name === 'W') {
            return "Wednesday";
        } elseif ($name === 'TH') {
            return "Thursday";
        } elseif ($name === 'F') {
            return "Friday";
        } else {
            return "Invalid input";
        }
    }


    public function GetSameSubjectCode($course_id, $subject_code,
        $school_year_id) {

        $query = $this->con->prepare("SELECT 

            t1.*,
            t2.room_number,
            t2.room_name

            FROM subject_schedule AS t1

            LEFT JOIN room AS t2 ON t2.room_id = t1.room_id

            WHERE t1.course_id=:course_id
            AND t1.subject_code=:subject_code
            AND t1.school_year_id=:school_year_id

            ORDER BY
            CASE schedule_day
                WHEN 'M' THEN 1
                WHEN 'T' THEN 2
                WHEN 'W' THEN 3
                WHEN 'TH' THEN 4
                WHEN 'F' THEN 5
                ELSE 6  
            END
            -- LIMIT 1
            ");

        $query->bindParam(":course_id", $course_id);
        $query->bindParam(":subject_code", $subject_code);
        $query->bindParam(":school_year_id", $school_year_id);
        $query->execute();

        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        // return NULL;
        return [];
    }

     

    public function GetTeacherScheduleSchoolYear($teacher_id){

        $query = $this->con->prepare("SELECT school_year_id

            FROM subject_schedule

            WHERE teacher_id=:teacher_id

            GROUP BY school_year_id
        ");

        $query->bindValue(":teacher_id", $teacher_id);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        return null;
    }

}

 
?>