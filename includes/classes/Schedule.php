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
        $teacher_id, $section_subject_code, $subject_program_id, $room_id, $back_url = null){

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

        // $subjectProgram = new SubjectProgram($con, $course_id);

        $subjectProgram = new SubjectProgram($this->con, $subject_program_id);
        $rawCode = $subjectProgram->GetSubjectProgramRawCode();
        $selected_title = $subjectProgram->GetTitle();

        // $section = new Section($this->con, $course_id);
        // $programName = $section->GetSectionName();

        // echo "time_from_meridian_military: $time_from_meridian_military";
        // echo "<br>";

        // echo "time_to_meridian_military: $time_to_meridian_military";
        // echo "<br>";
        // return;

        # Room, teacher are all TBA conflict prompt under SUBJECT CODE.
        $schedule_course_id_conflict = NULL;

        if($course_id !== NULL && $teacher_id == NULL){
            
            $schedule_course_id_conflict = $this->CheckScheduleDayConflictWithinSection(
                $time_from_meridian_military, $time_to_meridian_military,
                $schedule_day, $current_school_year_id, $course_id, null, $section_subject_code);

            
            if($schedule_course_id_conflict !== NULL){

                // var_dump($schedule_course_id_conflict);
                // return;

                $scheduleConflict = new Schedule($this->con, $schedule_course_id_conflict);

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

                $subject_code_conflict = $scheduleConflict->GetSubjectCode();

                
                $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

 
                $subject_code_conflict = $scheduleConflict->GetSubjectCode();
                $subjectProgramConflictId = $scheduleConflict->GetSubjectProgramId();

                $subjectProgramConflict = new SubjectProgram($this->con, $subjectProgramConflictId);

                $title_conflict = $subjectProgramConflict->GetTitle();
                
                $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);
 
                Alert::conflictedMessage("Teacher Conflicted Schedule: $time_from - $time_to <br> ( $day ) Room: $room_number <br> Subject: $title_conflict ($subject_code_conflict)",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ( $schedule_day ) Room: $room_inserted_number <br> Subject: $selected_title ($section_subject_code)", "");
                
 
                // exit();  
                    
                // Alert::error("Selected schedule day along with time for teacher is conflicted", "");
                // exit();  

            }
            
        }

        if($room_id !== NULL){

            # OOp
            $room_schedule_id_conflict = $this->CheckScheduleDayWithRoomConflict(
                $time_from_meridian_military, $time_to_meridian_military,
                $schedule_day, $current_school_year_id, $room_id);

                // var_dump($room_id);
                // return;
            
            if($room_schedule_id_conflict !== NULL){

                $scheduleConflict = new Schedule($this->con, $room_schedule_id_conflict);

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


                $subject_code_conflict = $scheduleConflict->GetSubjectCode();
                $subjectProgramConflictId = $scheduleConflict->GetSubjectProgramId();

                $subjectProgramConflict = new SubjectProgram($this->con, $subjectProgramConflictId);

                $title_conflict = $subjectProgramConflict->GetTitle();
                
                Alert::conflictedMessage("Room Conflicted Schedule: $time_from - $time_to <br> ( $day ) Room: $room_number <br> Subject: $title_conflict ($subject_code_conflict)",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ($schedule_day) Room: $room_inserted_number <br> Subject: $selected_title ($section_subject_code)", "");

                exit();
            }

        }

        // return;


        if($teacher_id !== NULL){

            $check_teacher_id_conflict = $this->CheckTeacherScheduleConflicted(
                $time_from_meridian_military, $time_to_meridian_military,
                $schedule_day, $teacher_id, $current_school_year_id, null);

            $schedule = new Schedule($this->con);
            $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

            $check_subject_schedule_assigned_teacher_id = $schedule
                ->GetAssignedSubjectScheduleTeacherId($section_subject_code,
                $current_school_year_id);
            
            $check_assigned_subject_topic_teacher_id = $subjectPeriodCodeTopic
                ->GetAssignedSectionCodeTeacherId($section_subject_code,
                $current_school_year_id);

            # UPDATED SCHEDULE CONFLICT PROMPT
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

                $subject_code_conflict = $scheduleConflict->GetSubjectCode();
                $subjectProgramConflictId = $scheduleConflict->GetSubjectProgramId();

                $subjectProgramConflict = new SubjectProgram($this->con, $subjectProgramConflictId);

                $title_conflict = $subjectProgramConflict->GetTitle();
                
                $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);
 
                Alert::conflictedMessage("Teacher Conflicted Schedule: $time_from - $time_to <br> ( $day ) Room: $room_number <br> Subject: $title_conflict ($subject_code_conflict)",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ( $schedule_day ) Room: $room_inserted_number <br> Subject: $selected_title ($section_subject_code)", "");
                
                exit();  
                    
                // Alert::error("Selected schedule day along with time for teacher is conflicted", "");
                // exit();  

            }

            if($check_assigned_subject_topic_teacher_id != NULL &&
                $check_subject_schedule_assigned_teacher_id != NULL &&
                $check_assigned_subject_topic_teacher_id != $teacher_id &&
                $check_subject_schedule_assigned_teacher_id != $teacher_id
                ){
                
                
                # You`re about to place a other teacher to the subject_code,
                # considering there`s a previous selected teacher to that subject_code
                Alert::error("You`re about to place an another teacher under the selected subject_code", "");
                exit();  
            }
 
  

            # STEM201 -> Albert Eistein. - OK, 2021-2022 2nd SEM
            # STEM201 -> Michael Picasso - NOT OK, 2021-2022 2nd SEM
            # Rule. Only one teacher for every subject_code.
            # Check if selected subject_code has already been assign.

            # 1. to previous teacher (selected before), it will not prompt
            # 2. If assigned to other teacher, 
            # even there`s an existing previous teacher before the selected teacher.


            if($check_teacher_id_conflict == NULL){

                # If selected teacher is the previous subject_schedule_assigned_teacher
                # if subject_topic_teacher is the subject_schedule_assigned_teacher
                # if subject_topic_teacher is selected teacher

                if($check_assigned_subject_topic_teacher_id != NULL &&
                    $check_subject_schedule_assigned_teacher_id != NULL &&
                    $check_assigned_subject_topic_teacher_id == $check_subject_schedule_assigned_teacher_id &&
                    $check_assigned_subject_topic_teacher_id == $teacher_id){

                    # Should only add subject_schedule data.
                    # This happens when you`re adding schedule 
                    # with the same subject code with the same selected teacher 
                    # and same previously assigned subject schedule teacher

                    if(true){

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
                            // return true;
                            Alert::success("Schedule has been Successfully added.", $back_url);
                            exit();
                        }
                    }

                }
               

                // var_dump($check_assigned_subject_topic_teacher_id);
                // echo "<br>";

                // var_dump($check_subject_schedule_assigned_teacher_id);
                // echo "<br>";


                # If no assign subject_topic_teacher
                # If no assign subject_schedule_assigned_teacher

                if($check_assigned_subject_topic_teacher_id == NULL &&
                    $check_subject_schedule_assigned_teacher_id == NULL 
                ){

                    # This happens when 1 or more subject codes is TBA
                    # You`re about to add a teacher to desired/creating subject code
                    # which will placed other 1 or more subject codes TBA with chosen teacher

                    # This will prompt ihe schedule conflict, because those 1 or more subject codes
                    # doesnt have a teacher to valide, so subject code would be its validation.

                    $schedule_course_id_conflict = $this->CheckScheduleDayConflictWithinSection(
                        $time_from_meridian_military, $time_to_meridian_military,
                        $schedule_day, $current_school_year_id, $course_id, null, $section_subject_code);

                
                    if($schedule_course_id_conflict !== NULL){

                        // var_dump($schedule_course_id_conflict);
                        // return;

                        $scheduleConflict = new Schedule($this->con, $schedule_course_id_conflict);

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

                        $subject_code_conflict = $scheduleConflict->GetSubjectCode();


                        
                        $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                        $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

                        // $time_from_meridian_military = $this->convertTo12HourFormat($raw_time_from);
                        // $time_to_meridian_military = $this->convertTo12HourFormat($raw_time_to);


                        $subject_code_conflict = $scheduleConflict->GetSubjectCode();
                        $subjectProgramConflictId = $scheduleConflict->GetSubjectProgramId();

                        $subjectProgramConflict = new SubjectProgram($this->con, $subjectProgramConflictId);

                        $title_conflict = $subjectProgramConflict->GetTitle();
                        
                        // $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                        // $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

                        // // $time_from_meridian_military = $this->convertTo12HourFormat($raw_time_from);
                        // // $time_to_meridian_military = $this->convertTo12HourFormat($raw_time_to);

                        // Alert::conflictedMessage("Teacher Conflicted Schedule: $time_from - $time_to <br> ( $day ) Room: $room_number <br> Subject: $title_conflict ($subject_code_conflict)",
                        //     "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ( $schedule_day ) Room: $room_inserted_number <br> Subject: $selected_title ($section_subject_code)", "");
                

                        Alert::conflictedMessage("Teacher Conflicted Schedule: $time_from - $time_to <br> ( $day ) <br> Room: $room_number <br> Subject: $title_conflict ($subject_code_conflict)",
                            "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ( $schedule_day ) <br> Room: $room_inserted_number <br> Subject: $selected_title ($section_subject_code)", "");
                        exit();  
                            
                        // Alert::error("Selected schedule day along with time for teacher is conflicted", "");
                        // exit();  

                    }

                    
                    #
                    if($schedule_course_id_conflict == NULL){

                        # Check if chosen subject code doesnt have a teacher
                        # Placing selected teachers to those tba subject codes.
                        $doesPlacedAll = $this->GetAllScheduleWithinSubjectCodesAndPlaceTeacher(
                            $course_id, $section_subject_code,
                            $current_school_year_id, $teacher_id);
                        

                        // var_dump($doesPlacedAll);
                        // return;


                        # It means, no subject_period_code_topic data and no subject_schedule data

                        # Add subject_schedule

                        # Add subject_period_code_topic (only once)

                        # Insert the default topic for subject assigned teacher
                        # Subject Code OCC -> Prelim, Midterm etc Topics for LMS

                        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($this->con);
                        
                        $doesFinish = false;

                        $getAllDefaultTopicTemplate = $subjectPeriodCodeTopicTemplate
                            ->GetTopicTemplateDefaultTopics($rawCode);

                        // var_dump($rawCode);

                        
                        if(count($getAllDefaultTopicTemplate) > 0){
    
                            foreach ($getAllDefaultTopicTemplate as $key => $row) {

                                $topic = $row['topic'];
                                $description = $row['description'];
                                $subject_period_name = $row['subject_period_name'];
                                $program_code = $row['program_code'];

                                # Populate default topic to the chosen teacher
                                $wasSuccess = $subjectPeriodCodeTopic->AddTopic(
                                    $course_id, $teacher_id,
                                    $current_school_year_id,
                                    $topic, $description,
                                    $subject_period_name,
                                    $section_subject_code,
                                    $program_code,
                                    $subject_program_id);
                                
                                if($wasSuccess){

                                    $doesFinish = true;
                                }
                            }
                        }

                        if($doesFinish){

                            # All subject codes that are TBA and subject topic teacher is empty
                            # will place to the selected teacher.

                            // Alert::success("Schedule and LMS teaching topics has been Successfully added.", $back_url);
                            // exit();

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
                                // return true;

                                # ADT1

                                Alert::success("Schedule and LMS teaching topics has been Successfully added.", $back_url);
                                exit();

                            }


                        }

                    }


                }

            }

        }
        
        if($teacher_id == NULL && $schedule_course_id_conflict == NULL){

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
                // return true;
                Alert::success("Schedule has been added successfully. Teacher: TBA", $back_url);
                exit();
            }
            

        }

        // $sql = $this->con->prepare("INSERT INTO subject_schedule
        //     (schedule_day, time_from, time_to, schedule_time,
        //         school_year_id, course_id, teacher_id, subject_code,
        //         subject_program_id, day_count, room_id)

        //     VALUES(:schedule_day, :time_from, :time_to, :schedule_time,
        //         :school_year_id, :course_id, :teacher_id, :subject_code,
        //         :subject_program_id, :day_count, :room_id)
        // ");

        // $schedule_time = $time_from_meridian . ' - ' . $time_to_meridian;

        // // $sql->bindParam(":room", $room);
        // $sql->bindParam(":schedule_day", $schedule_day);
        // $sql->bindParam(":time_from", $time_from_meridian_military);
        // $sql->bindParam(":time_to", $time_to_meridian_military);
        // $sql->bindParam(":schedule_time", $schedule_time);
        // $sql->bindParam(":school_year_id", $current_school_year_id);
        // $sql->bindParam(":course_id", $course_id);
        // $sql->bindValue(":teacher_id", $teacher_id);
        // $sql->bindParam(":subject_code", $section_subject_code);
        // $sql->bindParam(":subject_program_id", $subject_program_id);
        // $sql->bindParam(":day_count", $day_count);
        // $sql->bindParam(":room_id", $room_id);
        // $sql->execute();

        // if($sql->rowCount() > 0){
        //     return true;
        // }

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
        $subject_program_id, $room_id,
        
        $rawCode = null, $current_school_year_id = null, $back_url = null) {

        // var_dump($teacher_id);
        // return;

        $time_from = trim($time_from);
        $time_to = trim($time_to);

        $raw_time_from = trim($raw_time_from);
        $raw_time_to = trim($raw_time_to);
 

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

        // if($this->CheckScheduleDayWithRoomConflict(
        //     $time_from, $time_to,
        //     $schedule_day, $school_year_id,
        //     $room_id, $subject_schedule_id) === true){

        //     Alert::errorToast("Schedule day with time has conflicted with selected room.", "");
        //     exit();
        // }

        $schedule = new Schedule($this->con, $subject_schedule_id);

        $existing_subject_code = $schedule->GetSubjectCode();
        $existing_course_id = $schedule->GetScheduleCourseId();
        $existing_subject_program_id = $schedule->GetSubjectProgramId();
        $existing_teacher_id = $schedule->GetScheduleTeacherId();

        $sp = new SubjectProgram($this->con, $existing_subject_program_id);

        $existing_program_code = $sp->GetSubjectProgramRawCode();
        $selected_title = $sp->GetTitle();

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

        $check_subject_schedule_assigned_teacher_id = $schedule
            ->GetAssignedSubjectScheduleTeacherId(
                $subject_code,
                $current_school_year_id);
        
        # Get the teacher of subject period code (teacher id)
        $check_assigned_subject_topic_teacher_id = $subjectPeriodCodeTopic
            ->GetAssignedSectionCodeTeacherId(
                $existing_subject_code,
                $current_school_year_id);

        $getTeacherScheduleCount = $schedule
            ->GetSubjectScheduleCountForTeacher(
            $existing_subject_code,
            $current_school_year_id);

        // var_dump($getTeacherScheduleCount);
        // return;

        if($course_id != NULL && $teacher_id !== NULL && $subject_code){

            $schedule_course_id_conflict = $this->CheckScheduleDayConflictWithinSection(
                $time_from, $time_to,
                $schedule_day, $current_school_year_id,
                $course_id, $subject_schedule_id, null, $subject_code);

            // echo "time_from: $time_from";
            // echo "<br>";

            // echo "time_to: $time_to";
            // echo "<br>";


            // var_dump($schedule_course_id_conflict);
            // return;

            if($schedule_course_id_conflict != NULL){

                // var_dump($schedule_course_id_conflict);
                // return;

                $scheduleConflict = new Schedule($this->con, $schedule_course_id_conflict);

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

                if($room_inserted_number == NULL){
                    $room_inserted_number = "N/A";
                }

                $subject_code_conflict = $scheduleConflict->GetSubjectCode();


                // $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                // $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

                $time_from_meridian_military = $this->convertTo12HourFormat($raw_time_from);
                $time_to_meridian_military = $this->convertTo12HourFormat($raw_time_to);

                Alert::conflictedMessage(
                    "Section Conflicted Schedule: $time_from - $time_to <br> ( $day ) <br> Code1: $subject_code_conflict",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ( $schedule_day ) <br> Code1: $subject_code", "");
                
                exit();  
                    
                // Alert::error("Selected schedule day along with time for teacher is conflicted", "");
                // exit();  

            }

        }
           

        # ROOM Conflict Section. 

        if($room_id !== NULL){


            $room_schedule_id_conflict = $this->CheckScheduleDayWithRoomConflict(
                $time_from, $time_to,
                $schedule_day, $school_year_id,
                $room_id, $subject_schedule_id);

            // var_dump($room_schedule_id_conflict);
            // return;
          
            if($room_schedule_id_conflict !== NULL){

                // var_dump($room_schedule_id_conflict);
                // return;
                $scheduleConflict = new Schedule($this->con, $room_schedule_id_conflict);

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

                $subject_code_conflict = $scheduleConflict->GetSubjectCode();
                $subjectProgramConflictId = $scheduleConflict->GetSubjectProgramId();

                $subjectProgramConflict = new SubjectProgram($this->con, $subjectProgramConflictId);

                $title_conflict = $subjectProgramConflict->GetTitle();
                

                $time_from_meridian_military = $this->convertTo12HourFormat($raw_time_from);
                $time_to_meridian_military = $this->convertTo12HourFormat($raw_time_to);

                Alert::conflictedMessage("Room Conflicted Schedule: $time_from - $time_to <br> ( $day ) Room: $room_number <br> Subject: $title_conflict ($subject_code_conflict)",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ($schedule_day) Room: $room_inserted_number <br> Subject: $selected_title ($existing_subject_code)", "");

                

                // Alert::conflictedMessage("Conflicted Schedule: $time_from - $time_to <br> ($day) Room: $room_number",
                //     "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ($schedule_day) Room: $room_inserted_number", "");
                
                // Alert::errorToast("Schedule day with time has conflicted with selected room.", "");
                exit();

            }   

            if($room_schedule_id_conflict == NULL){

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
                if(true){

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

                    $sql->execute();

                    if ($sql->rowCount() > 0) {
                        Alert::success("Schedule with the same teacher has been updated successfully.", $back_url);
                        exit();
                    } 
                }
            }
        }

        
        if($teacher_id !== NULL){

            $check_teacher_id_conflict = $this->CheckTeacherScheduleConflicted(
                $time_from, $time_to, $schedule_day,
                $teacher_id, $school_year_id,
                $subject_schedule_id
            );

            # Get all 

            // var_dump($check_subject_schedule_assigned_teacher_id);
            // echo "<br>";
            // var_dump($check_assigned_subject_topic_teacher_id);
            // echo "<br>";

            // var_dump($teacher_id);

            // return;
            
            # Teacher Schedule Conflict Section.

            if($check_teacher_id_conflict != NULL){

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

                if($room_inserted_number == NULL){
                    $room_inserted_number = "N/A";
                }

                $subject_code_conflict = $scheduleConflict->GetSubjectCode();

                // $time_from_meridian_military = $this->convertTo12HourFormat($time_from_meridian_military);
                // $time_to_meridian_military = $this->convertTo12HourFormat($time_to_meridian_military);

                $time_from_meridian_military = $this->convertTo12HourFormat($raw_time_from);
                $time_to_meridian_military = $this->convertTo12HourFormat($raw_time_to);
                
                $subjectProgramConflictId = $scheduleConflict->GetSubjectProgramId();

                $subjectProgramConflict = new SubjectProgram($this->con, $subjectProgramConflictId);

                $title_conflict = $subjectProgramConflict->GetTitle();
                
       
                // Alert::conflictedMessage("Teacher Conflicted Schedule: $time_from - $time_to <br> ( $day ) Room: $room_number <br> Subject: $title_conflict ($subject_code_conflict)",
                //     "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ( $schedule_day ) Room: $room_inserted_number <br> Subject: $selected_title ($section_subject_code)", "");
                

                
                Alert::conflictedMessage(
                    "Teacher Conflicted Schedule: $time_from - $time_to <br> ( $day ) Room: $room_number <br> Subject: $title_conflict ($subject_code_conflict)",
                    "Desired Schedule: $time_from_meridian_military - $time_to_meridian_military <br> ( $schedule_day ) Room: $room_inserted_number <br> Subject: $selected_title ($existing_subject_code)", "");
                exit();  
                    
                // Alert::error("Selected schedule day along with time for teacher is conflicted", "");
                // exit();  

            }

            # Changing subject_schedule and adjustment of subject_period_code_topic (teacher id)

            if($check_assigned_subject_topic_teacher_id != NULL
                && $check_assigned_subject_topic_teacher_id != $teacher_id 
                && $getTeacherScheduleCount <= 1){

                    // echo "hey";
                    // return;

                # Remove all subject_period_code_topic 
                # based on subject_code, current_school_year_id, check_assigned_subject_topic_teacher_id

                // $doesAllRemoved = $subjectPeriodCodeTopic->RemovingTeachingCodeTopic(
                //     $check_assigned_subject_topic_teacher_id,
                //     $subject_code,
                //     $current_school_year_id);
                
                # Add appropriate subject code topic

                $doesFinish = false;

                // if($doesAllRemoved){

                //     $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($this->con);
                
                //     $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

                //     $getAllDefaultTopicTemplate = $subjectPeriodCodeTopicTemplate->GetTopicTemplateDefaultTopics($rawCode);
 
                //     if(count($getAllDefaultTopicTemplate) > 0){

                //         foreach ($getAllDefaultTopicTemplate as $key => $row) {

                //             $topic = $row['topic'];
                //             $description = $row['description'];
                //             $subject_period_name = $row['subject_period_name'];
                //             $program_code = $row['program_code'];

                //             # Populate default topic to the chosen teacher
                //             $wasSuccess = $subjectPeriodCodeTopic->AddTopic(
                //                 $course_id, $teacher_id, $current_school_year_id,
                //                 $topic, $description,
                //                 $subject_period_name, $subject_code,
                //                 $program_code, $subject_program_id);
                            
                //             if($wasSuccess){
                //                 $doesFinish = true;
                //             }
                //         }
                //     }
                // }

                # subject_period_code_topic adjustment

                $subjectTopicUpdatingSuccess = $subjectPeriodCodeTopic
                    ->AdjustmentOfAssignTeacherOnSubjectCodeTopic(
                        $existing_course_id, $teacher_id, $current_school_year_id,
                        $subject_code, $existing_subject_code, $existing_program_code);

                # subject_schedule adjustment
                // if($doesFinish == true){
                if($subjectTopicUpdatingSuccess == true){

                    $updateS = $this->con->prepare("UPDATE subject_schedule
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

                    // Bind parameters
                    $updateS->bindParam(":subject_schedule_id", $subject_schedule_id);
                    $updateS->bindParam(":schedule_day", $schedule_day);
                    $updateS->bindParam(":schedule_time", $schedule_time);
                    $updateS->bindParam(":time_from", $time_from);
                    $updateS->bindParam(":time_to", $time_to);
                    $updateS->bindParam(":school_year_id", $school_year_id);
                    $updateS->bindParam(":course_id", $course_id);
                    $updateS->bindParam(":teacher_id", $teacher_id);
                    $updateS->bindParam(":subject_code", $subject_code);
                    $updateS->bindParam(":subject_program_id", $subject_program_id);
                    $updateS->bindParam(":day_count", $day_count);
                    $updateS->bindParam(":room_id", $room_id);

                    $updateS->execute();

                    if ($updateS->rowCount() > 0) {

                        Alert::success("Schedule has been update & LMS subject code has been placed to selected teacher.", $back_url);
                        exit();

                    }
                }
            }


            if($check_assigned_subject_topic_teacher_id != NULL
                && $check_assigned_subject_topic_teacher_id != $teacher_id 
                && $getTeacherScheduleCount > 1){
                
                Alert::error("There`s a scheduled teacher under of selected $subject_code code", "");
                exit(); 
            }
 
            $subject_schedule = new Schedule($this->con, $subject_schedule_id);
            $subject_schedule_teacher_id = $subject_schedule->GetScheduleTeacherId();

            // echo "subject_schedule_teacher_id: $subject_schedule_teacher_id";
            // echo "<br>";

            // var_dump($subject_schedule_teacher_id);
            // echo "selected_teacher_id: $teacher_id";
            // echo "<br>";
            // return;
            
            $doesTeacherTheSame = $subject_schedule_teacher_id == $teacher_id;
            $doesTheSameSubjectCode = $existing_subject_code == $subject_code;

            # * SHOULD ADAPT BY ROOM ID

            # Editing with the same teacher, but not modifying the subject_code.

            if($check_teacher_id_conflict == NULL 
                && $subject_schedule_teacher_id != NULL
                && $doesTheSameSubjectCode == true
                && $doesTeacherTheSame == true){

                // echo "same teacher";
                // return;

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
                if(true){

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

                    $sql->execute();

                    if ($sql->rowCount() > 0) {
                        Alert::success("Schedule with the same teacher has been updated successfully.", $back_url);
                        exit();
                    } 
                }
            }

            # * SHOULD ADAPT BY ROOM ID
            
            # FROM TBA to chosen teacher_id
            # Can be one or more TBA subject codes to selected teacher.
            # and update subject topic to assign teacher

            if($check_teacher_id_conflict == NULL 
                && $subject_schedule_teacher_id == NULL){

                // echo "hey";
                // return;
                # Get all data of subject codes and placed the teacher to that collected subject codes

                $doesPlacedAll = $this->GetAllScheduleWithinSubjectCodesAndPlaceTeacher(
                    $course_id, $subject_code,
                    $current_school_year_id, $teacher_id);

                // var_dump($doesPlacedAll);
                // return;

                // echo "hey";

                # From Non subject code teacher assigned to 
                # Insert the default topic for subject assigned teacher

                # Subject Code OCC -> Prelim, Midterm etc Topics for LMS

                $doesChoosingTeacherFinish = false;

                $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($this->con);
                
                $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

                $getAllDefaultTopicTemplate = $subjectPeriodCodeTopicTemplate
                    ->GetTopicTemplateDefaultTopics($rawCode);

                // var_dump($getAllDefaultTopicTemplate);
                // return;

                $subject_schedule = new Schedule($this->con, $subject_schedule_id);
                $subjectProgramId = $subject_schedule->GetSubjectProgramId();

                if(count($getAllDefaultTopicTemplate) > 0 && $doesPlacedAll){

                    foreach ($getAllDefaultTopicTemplate as $key => $row) {

                        $topic = $row['topic'];
                        $description = $row['description'];
                        $subject_period_name = $row['subject_period_name'];
                        $program_code = $row['program_code'];

                        # Populate default topic to the chosen teacher
                        $wasSuccess = $subjectPeriodCodeTopic->AddTopic(
                            $course_id, $teacher_id, $current_school_year_id,
                            $topic, $description,
                            $subject_period_name, $subject_code,
                            $program_code, $subjectProgramId);
                        
                        if($wasSuccess){

                            $doesChoosingTeacherFinish = true;
                        }
                    }

                }

                // var_dump($doesChoosingTeacherFinish);
                // return;

                if($doesChoosingTeacherFinish){

                    Alert::success("Schedule successfully updated all selected $subject_code subjects from TBA to selected teacher. LMS subject topic has been added.", "$back_url");
                    exit();

                    // $sql = $this->con->prepare("UPDATE subject_schedule
                    //     SET schedule_day = :schedule_day,
                    //         time_from = :time_from,
                    //         time_to = :time_to,
                    //         school_year_id = :school_year_id,
                    //         course_id = :course_id,
                    //         teacher_id = :teacher_id,
                    //         subject_code = :subject_code,
                    //         subject_program_id = :subject_program_id,
                    //         day_count = :day_count,
                    //         room_id = :room_id,
                    //         schedule_time = :schedule_time
                    //     WHERE subject_schedule_id = :subject_schedule_id
                    // ");

                    // // Concatenate time_from and time_to for schedule_time

                    // $schedule_time = $raw_time_from . ' - ' . $raw_time_to;

                    // // Bind parameters
                    // $sql->bindParam(":subject_schedule_id", $subject_schedule_id);
                    // $sql->bindParam(":schedule_day", $schedule_day);
                    // $sql->bindParam(":schedule_time", $schedule_time);
                    // $sql->bindParam(":time_from", $time_from);
                    // $sql->bindParam(":time_to", $time_to);
                    // $sql->bindParam(":school_year_id", $school_year_id);
                    // $sql->bindParam(":course_id", $course_id);
                    // $sql->bindParam(":teacher_id", $teacher_id);
                    // $sql->bindParam(":subject_code", $subject_code);
                    // $sql->bindParam(":subject_program_id", $subject_program_id);
                    // $sql->bindParam(":day_count", $day_count);
                    // $sql->bindParam(":room_id", $room_id);

                    // $sql->execute();

                    // if ($sql->rowCount() > 0) {
                    //     Alert::success("Schedule successfully updated from TBA to selected teacher. LMS subject topic has been added.", "$back_url");
                    //     exit();
                    // } 

                }

            }

            

        }

        if($teacher_id === NULL){


            if($existing_teacher_id != NULL){

                // var_dump($existing_teacher_id);

                // return;

                # All of teachers handle to the selected subject would be TBA.
                # we dont have this.

                # Taekwondo 2 -> Kick Butowskie
                # Taekwondo 2 -> TBA

                $doesTBAOperationSuccess = $this->GetAllSubjectCodeUnderTeacherIdsIntoTBA(
                    $existing_teacher_id, $course_id,
                    $subject_code, $current_school_year_id);

                
                if($doesTBAOperationSuccess == true){
                    
                // if(true){

                    $subject_schedule = new Schedule($this->con, $subject_schedule_id);
                    $subject_schedule_teacher_id = $subject_schedule->GetScheduleTeacherId();
                    $subject_code_db = $subject_schedule->GetSubjectCode();

                    $doesTBAFinishProcess = false;

                    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

                    # Check if it has given default subject topics using Raw Code
                    $checkTeacherGivenDefaultTopicsBySubjectCode = $subjectPeriodCodeTopic
                        ->CheckTeacherHasGivenSubjectCodeDefaultTopics(
                            $subject_code, $existing_teacher_id,
                            $current_school_year_id
                        );
                    
                    // var_dump($checkTeacherGivenDefaultTopicsBySubjectCode);
                    // return;

                        
                    if(count($checkTeacherGivenDefaultTopicsBySubjectCode) > 0){
                        // echo "check success";

                        foreach ($checkTeacherGivenDefaultTopicsBySubjectCode as $key => $value) {

                            $subject_period_code_topic_id = $value['subject_period_code_topic_id'];
                            
                            $defaultTopicRemoval = $subjectPeriodCodeTopic
                                ->RemovalOfDefaultSubjectCodeTopics(
                                    $subject_period_code_topic_id);
                        
                            if($defaultTopicRemoval){
                                $doesTBAFinishProcess = true;
                            }
                            # code...
                        }

                        // var_dump($doesTBAFinishProcess);
                        // return;

                        if($doesTBAFinishProcess){

                            Alert::success("All teaching $subject_code subjects of selected teacher were all now TBA. LMS subject topics has been removed.", "$back_url");
                            exit();
                        }
                    }

                }
            }


            # PE301 Kick Buttowskie has 2 subject_schedule data
            # PE301 Kick Buttowskie 1 data = TBA -> Should not considered.

            # Results would be.
            # = PE301 Kick Buttowskie 1 data and PE301 TBA 1 data

            if(
                // $check_assigned_subject_topic_teacher_id != NULL
                // && $check_assigned_subject_topic_teacher_id != $teacher_id &&
                
                $getTeacherScheduleCount > 1){

                // Alert::error("Teacher TBA not guaranteed. There`s an existing scheduled
                //     teacher under of selected $subject_code code", "");

                // exit(); 

                
            }

            # Check current subject_schedule_id assigned teacher

            // $subject_schedule = new Schedule($this->con, $subject_schedule_id);
            // $subject_schedule_teacher_id = $subject_schedule->GetScheduleTeacherId();
            // $subject_code_db = $subject_schedule->GetSubjectCode();

            // $doesTBAFinishProcess = false;

            // $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($this->con);

            // # Check if it has given default subject topics using Raw Code
            // $checkTeacherGivenDefaultTopicsBySubjectCode = $subjectPeriodCodeTopic
            //     ->CheckTeacherHasGivenSubjectCodeDefaultTopics(
            //         $subject_code_db, $subject_schedule_teacher_id, $current_school_year_id);
            
            // if(count($checkTeacherGivenDefaultTopicsBySubjectCode) > 0){
            //     // echo "check success";

            //     foreach ($checkTeacherGivenDefaultTopicsBySubjectCode as $key => $value) {

            //         $subject_period_code_topic_id = $value['subject_period_code_topic_id'];
                    
            //         $defaultTopicRemoval = $subjectPeriodCodeTopic
            //         ->RemovalOfDefaultSubjectCodeTopics(
            //             $subject_period_code_topic_id);
                
            //         if($defaultTopicRemoval){
            //             $doesTBAFinishProcess = true;
            //         }
            //         # code...
            //     }

            // }

            // if(true){
            // // if($doesTBAFinishProcess){

            //     if(true){

            //         $sql = $this->con->prepare("UPDATE subject_schedule
            //             SET schedule_day = :schedule_day,
            //                 time_from = :time_from,
            //                 time_to = :time_to,
            //                 school_year_id = :school_year_id,
            //                 course_id = :course_id,
            //                 teacher_id = :teacher_id,
            //                 subject_code = :subject_code,
            //                 subject_program_id = :subject_program_id,
            //                 day_count = :day_count,
            //                 room_id = :room_id,
            //                 schedule_time = :schedule_time
            //             WHERE subject_schedule_id = :subject_schedule_id
            //         ");

            //         // Concatenate time_from and time_to for schedule_time

            //         $schedule_time = $raw_time_from . ' - ' . $raw_time_to;

            //         // Bind parameters
            //         $sql->bindParam(":subject_schedule_id", $subject_schedule_id);
            //         $sql->bindParam(":schedule_day", $schedule_day);
            //         $sql->bindParam(":schedule_time", $schedule_time);
            //         $sql->bindParam(":time_from", $time_from);
            //         $sql->bindParam(":time_to", $time_to);
            //         $sql->bindParam(":school_year_id", $school_year_id);
            //         $sql->bindParam(":course_id", $course_id);
            //         $sql->bindParam(":teacher_id", $teacher_id);
            //         $sql->bindParam(":subject_code", $subject_code);
            //         $sql->bindParam(":subject_program_id", $subject_program_id);
            //         $sql->bindParam(":day_count", $day_count);
            //         $sql->bindParam(":room_id", $room_id);

            //         $sql->execute();

            //         if ($sql->rowCount() > 0) {

            //             $tbaWithRemovalSubjectTopic = "";

            //             if($doesTBAFinishProcess == true){

            //                 $tbaWithRemovalSubjectTopic = "Schedule successfully updated from selected teacher to TBA. LMS subject topic has been removed.";

            //             }else{
            //                 $tbaWithRemovalSubjectTopic = "Successfully updated TBA Schedule";
            //             }

            //             Alert::success("$tbaWithRemovalSubjectTopic", "$back_url");
            //             exit();
            //         } 

            //     }

            // }

            // return;

        }

        

        // Prepare the UPDATE query
        // $sql = $this->con->prepare("UPDATE subject_schedule
        //     SET schedule_day = :schedule_day,
        //         time_from = :time_from,
        //         time_to = :time_to,
        //         school_year_id = :school_year_id,
        //         course_id = :course_id,
        //         teacher_id = :teacher_id,
        //         subject_code = :subject_code,
        //         subject_program_id = :subject_program_id,
        //         day_count = :day_count,
        //         room_id = :room_id,
        //         schedule_time = :schedule_time
        //     WHERE subject_schedule_id = :subject_schedule_id
        // ");

        // // Concatenate time_from and time_to for schedule_time

        // $schedule_time = $raw_time_from . ' - ' . $raw_time_to;
        
        // // $schedule_time = $time_from . ' - ' . $time_to;

        // // Bind parameters
        // $sql->bindParam(":subject_schedule_id", $subject_schedule_id);
        // $sql->bindParam(":schedule_day", $schedule_day);
        // $sql->bindParam(":schedule_time", $schedule_time);
        // $sql->bindParam(":time_from", $time_from);
        // $sql->bindParam(":time_to", $time_to);
        // $sql->bindParam(":school_year_id", $school_year_id);
        // $sql->bindParam(":course_id", $course_id);
        // $sql->bindParam(":teacher_id", $teacher_id);
        // $sql->bindParam(":subject_code", $subject_code);
        // $sql->bindParam(":subject_program_id", $subject_program_id);
        // $sql->bindParam(":day_count", $day_count);
        // $sql->bindParam(":room_id", $room_id);

        // $sql->execute();

        // if ($sql->rowCount() > 0) {
        //     return true;  
        // } 

        return false; // Update failed
    }

    public function GetAllSubjectCodeUnderTeacherIdsIntoTBA($teacher_id, $course_id,
        $subject_code, $school_year_id){



        $stmt = $this->con->prepare("SELECT 
        
            t1.* 
            
            FROM subject_schedule as t1
 
            WHERE t1.teacher_id=:teacher_id
            AND t1.school_year_id=:school_year_id
            AND t1.subject_code=:subject_code
            AND t1.course_id=:course_id

        ");

        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->bindParam(':school_year_id', $school_year_id);
        $stmt->bindParam(':subject_code', $subject_code);
        $stmt->bindParam(':course_id', $course_id);

        $stmt->execute();

        $doesFinish = false;

        if($stmt->rowCount() > 0){

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sql = $this->con->prepare("UPDATE subject_schedule
                SET teacher_id = :teacher_id
                    
                WHERE subject_schedule_id = :subject_schedule_id
            ");

            foreach ($result as $key => $value) {

                # code...
                $subject_schedule_id = $value['subject_schedule_id'];

                $sql->bindParam(":subject_schedule_id", $subject_schedule_id);
                $sql->bindValue(":teacher_id", NULL);
                $sql->execute();

                if($sql->rowCount() > 0){

                    $doesFinish = true;
                }
            }
        }

        return $doesFinish;

    }

    public function GetAllScheduleWithinSubjectCodesAndPlaceTeacher(
        $course_id, $subject_code, $school_year_id, $chosen_teacher_id){

        $stmt = $this->con->prepare("SELECT 
        
            t1.* 
            
            FROM subject_schedule as t1
 
            WHERE t1.school_year_id=:school_year_id
            AND t1.subject_code=:subject_code
            AND t1.course_id=:course_id

        ");

        $stmt->bindParam(':school_year_id', $school_year_id);
        $stmt->bindParam(':subject_code', $subject_code);
        $stmt->bindParam(':course_id', $course_id);

        $stmt->execute();

        $doesFinish = false;

        if($stmt->rowCount() > 0){

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sql = $this->con->prepare("UPDATE subject_schedule
                SET teacher_id = :teacher_id
                    
                WHERE subject_schedule_id = :subject_schedule_id
            ");

            foreach ($result as $key => $value) {

                # code...
                $subject_schedule_id = $value['subject_schedule_id'];

                $sql->bindParam(":subject_schedule_id", $subject_schedule_id);
                $sql->bindValue(":teacher_id", $chosen_teacher_id);
                $sql->execute();

                if($sql->rowCount() > 0){
                    $doesFinish = true;
                }
            }
        }

        return $doesFinish;

    }

     public function GetAllScheduleSubjectLoadCart(
        $course_id = null, $student_id,
        $school_year_id, $schedule_day = null) {


        // echo "subject_code: $subject_code";
        // echo "<br>";

        // echo "student_id: $student_id";
        // echo "<br>";

        // echo "subject_program_id: $subject_program_id";
        // echo "<br>";

        // echo "school_year_id: $school_year_id";
        // echo "<br>";

        // $subject_code = "ABE1-A-FL 2";

        $stmt = $this->con->prepare("SELECT 
        
            t2.subject_schedule_id,
            t2.schedule_day 
            -- t1.student_subject_id 
            
            FROM student_subject as t1
        
            INNER JOIN subject_schedule as t2 ON t2.subject_code = t1.subject_code
            AND t2.subject_program_id = t1.subject_program_id
            AND t2.school_year_id = :schedule_school_year_id

            -- AND t2.schedule_day = :schedule_day

            -- WHERE t1.course_id = :course_id
            -- AND t1.subject_program_id=:subject_program_id
            
            WHERE t1.student_id=:student_id
            AND t1.school_year_id=:school_year_id

        ");

        $stmt->bindParam(':schedule_school_year_id', $school_year_id);
        // $stmt->bindParam(':schedule_day', $schedule_day);
        
        # If enabled this, all schedule outside your enrollment course id would not be considered.
        // $stmt->bindParam(':course_id', $course_id);
        // $stmt->bindParam(':subject_program_id', $subject_program_id);

        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':school_year_id', $school_year_id);

        $stmt->execute();

        if($stmt->rowCount() > 0){

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];


    }

    public function CheckScheduleConflictOnSubjectLoad(
        $userTimeFrom, $userTimeTo, $schedule_day,
        $school_year_id) {


        $stmt = $this->con->prepare("SELECT * FROM subject_schedule 
        
            WHERE schedule_day = :schedule_day
            AND school_year_id=:school_year_id

        ");

        $stmt->bindParam(':schedule_day', $schedule_day);
        $stmt->bindParam(':school_year_id', $school_year_id);

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

                    // return true; // Conflict found

                    // Conflict schedule ID FOUND
                    return $subject_schedule_id;

                    
                }
            }

        }

        return NULL;
    }

    public function CheckScheduleDayConflictWithinSection(
        $userTimeFrom, $userTimeTo, $schedule_day,
        $school_year_id, $course_id = null, $subject_schedule_id = null,
        $section_subject_code = null) {
 
        $subject_schedule_output_query = "";

        if($subject_schedule_id !== NULL){
            $subject_schedule_output_query = "AND subject_schedule_id !=:subject_schedule_id";
        }

        $stmt = $this->con->prepare("SELECT * FROM subject_schedule 
        
            WHERE schedule_day = :schedule_day

            AND course_id=:course_id
            AND subject_code=:subject_code
            AND school_year_id=:school_year_id
            $subject_schedule_output_query

        ");

        $stmt->bindParam(':schedule_day', $schedule_day);
        $stmt->bindParam(':course_id', $course_id);
        $stmt->bindParam(':subject_code', $section_subject_code);
        $stmt->bindParam(':school_year_id', $school_year_id);

        if($subject_schedule_id != NULL){
            $stmt->bindParam(':subject_schedule_id', $subject_schedule_id);
        }

        $stmt->execute();

        if($stmt->rowCount() > 0){

            // echo "Hey";

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
                    ($existingTimeTo == $userTimeFrom) // Add this condition
                ) {
                    continue; // No conflict found, check the next schedule
                } else {

                    // echo "subject_schedule_id: $subject_schedule_id";
                    return $subject_schedule_id;
                    
                }

            }
        }

        return NULL; // No conflicts found
    }

    public function CheckScheduleDayWithRoomConflict(
        $userTimeFrom, $userTimeTo, $schedule_day,
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
        
            WHERE schedule_day = :schedule_day
            AND room_id=:room_id
            AND school_year_id=:school_year_id
            $subject_schedule_output_query
        ");

        $stmt->bindParam(':schedule_day', $schedule_day);
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
                    ($userTimeTo <= $existingTimeFrom)
                    //  || ($existingTimeTo == $userTimeFrom) // Add this condition
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
            Alert::errorNonRedirect("Time from should greater than Time to", "");
            return false;
            // exit();
        }

        return true;
    }
    public function CheckScheduleRoom($schedule_room_capacity, $selected_room_capacity
    ) {

        if($schedule_room_capacity > $selected_room_capacity){
            Alert::errorNonRedirect("Selected room is less than to actual subject capacity", "");   
            return false;
        }

        return true;
    }

    public function CheckTeacherScheduleConflicted(
        $userTimeFrom, $userTimeTo, $schedule_day,
        $teacher_id, $school_year_id, $subject_schedule_id = null
    ) {

        // var_dump($teacher_id);
        // return;

        $subject_schedule_output_query = "";

        if($subject_schedule_id !== NULL){
            $subject_schedule_output_query = "AND subject_schedule_id !=:subject_schedule_id";
        }

        $stmt = $this->con->prepare("SELECT 
        
            subject_schedule_id, time_from, time_to 
        
            FROM subject_schedule

            WHERE schedule_day = :schedule_day
            AND teacher_id = :teacher_id
            AND school_year_id = :school_year_id
            $subject_schedule_output_query

        ");

        $stmt->bindParam(':schedule_day', $schedule_day);
        $stmt->bindParam(':teacher_id', $teacher_id);
        $stmt->bindParam(':school_year_id', $school_year_id);

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
            return "";
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

        return [];
    }

     public function GetAssignedSubjectScheduleTeacherId($subject_code,
        $school_year_id){

        $check = $this->con->prepare("SELECT teacher_id FROM subject_schedule
            
            WHERE subject_code=:subject_code
            AND school_year_id=:school_year_id

        ");

        $check->bindValue(":subject_code", $subject_code);
        $check->bindValue(":school_year_id", $school_year_id);
        $check->execute();

        if($check->rowCount() > 0){
            return $check->fetchColumn();
        }

        return NULL;
    }

    public function GetSubjectScheduleCountForTeacher($subject_code,
        $school_year_id){

        $check = $this->con->prepare("SELECT subject_schedule_id 
        
            FROM subject_schedule
            
            WHERE subject_code=:subject_code
            AND school_year_id=:school_year_id

        ");

        $check->bindValue(":subject_code", $subject_code);
        $check->bindValue(":school_year_id", $school_year_id);
        $check->execute();

       
        return $check->rowCount();


    }




}

 
?>