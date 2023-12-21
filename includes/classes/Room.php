<?php

    class Room{

        private $con, $room_id, $sqlData;

        public function __construct($con, $room_id = null){
            $this->con = $con;
            $this->room_id = $room_id;

            $query = $this->con->prepare("SELECT * FROM room
                 WHERE room_id=:room_id");

            $query->bindValue(":room_id", $room_id);
            $query->execute();

            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }

        public function GetRoomName(){
            return isset($this->sqlData['room_name']) ? ucfirst($this->sqlData["room_name"]) : ""; 
        }

        public function GetRoomCapacity(){
            return isset($this->sqlData['room_capacity']) ? $this->sqlData["room_capacity"] : ""; 
        }

        public function GetRoomNumber(){
            return isset($this->sqlData['room_number']) ? $this->sqlData["room_number"] : ""; 
        }

        public function CheckIdExists($room_id) {

            $query = $this->con->prepare("SELECT * FROM room
                    WHERE room_id=:room_id");

            $query->bindParam(":room_id", $room_id);
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

        public function CheckRoomHasAttachedSubjectWithinSYSemester(
            $room_id, $school_year_id) {

            $query = $this->con->prepare("SELECT * FROM room AS t1
                INNER JOIN subject_schedule AS t2 ON t2.room_id = t1.room_id
                AND t2.school_year_id=:school_year_id

                WHERE T1.room_id=:room_id
            ");

            $query->bindParam(":room_id", $room_id);
            $query->bindParam(":school_year_id", $school_year_id);
            $query->execute();
            
            return $query->rowCount() > 0;

        }

        public function DeleteSectionRoomUnreachedMinStudent($period,
            $course_id) {


            if($period == "First"){

                $update = $this->con->prepare("UPDATE course
                        SET first_period_room_id=:set_first_period_room_id
                        WHERE course_id=:course_id");

                $update->bindValue(":set_first_period_room_id", NULL);
                $update->bindValue(":course_id", $course_id);
                $update->execute();

                if($update->rowCount() > 0){
                    return true;
                }
            }

            if($period == "Second"){

                $update = $this->con->prepare("UPDATE course
                        SET second_period_room_id=:set_second_period_room_id
                        WHERE course_id=:course_id");

                $update->bindValue(":set_second_period_room_id", NULL);
                $update->bindParam(":course_id", $course_id);
                $update->execute();

                if($update->rowCount() > 0){
                    return true;
                }
            }
            
            return false;

        }

        public function GetRoomSectionFilled($room_id, $current_period) {

            $program_section = "";
            if($current_period == "First"){

                $query = $this->con->prepare("SELECT program_section FROM course
                    WHERE first_period_room_id=:first_period_room_id
                    LIMIT 1
                    ");

                $query->bindParam(":first_period_room_id", $room_id);
                $query->execute();

                if($query->rowCount() > 0){
                    $program_section = $query->fetchColumn();
                }   
            }else if($current_period == "Second"){

                $query = $this->con->prepare("SELECT program_section FROM course
                    WHERE second_period_room_id=:second_period_room_id
                    LIMIT 1
                    ");

                $query->bindParam(":second_period_room_id", $room_id);
                $query->execute();

                if($query->rowCount() > 0){
                    $program_section = $query->fetchColumn();
                }   
            }

            return $program_section;
        }

        public function RoomUpdating($room_id, $course_id, $school_year_id) {

            // echo $course_id;

            $select = $this->con->prepare("SELECT * FROM room
                WHERE course_id=:course_id
                AND school_year_id=:school_year_id
                ");

            $select->bindParam(":course_id", $course_id);
            $select->bindParam(":school_year_id", $school_year_id);
            $select->execute();

            if($select->rowCount() > 0){
                // echo "1";
                $init = $this->con->prepare("UPDATE room
                    SET course_id=:reset_course_id
                    WHERE course_id=:course_id
                    AND school_year_id=:school_year_id
                ");

                $init->bindValue(":reset_course_id", 0);
                $init->bindParam(":course_id", $course_id);
                $init->bindParam(":school_year_id", $school_year_id);
                $init->execute();

                if($init->rowCount() > 0){

                    $query = $this->con->prepare("UPDATE room
                        SET course_id=:course_id
                        WHERE room_id=:room_id");

                    $query->bindParam(":course_id", $course_id);
                    $query->bindParam(":room_id", $room_id);
                    $query->execute();

                    if($query->rowCount() > 0){
                        return true;
                    } 
                }
            }
            else if($select->rowCount() == 0){
                // echo "0";

                $query = $this->con->prepare("UPDATE room
                    SET course_id=:course_id
                    WHERE room_id=:room_id");

                $query->bindParam(":course_id", $course_id);
                $query->bindParam(":room_id", $room_id);
                $query->execute();

                if($query->rowCount() > 0){
                    return true;
                } 
                
            }
 
            

            
            return false;
        }


        // public function AssigningOpenedRoom($course_id,
        //     $room_id, $school_year_term, $period) {

        //     if(ucfirst($period) == "First"){
        //         $update = $this->con->prepare("UPDATE section
        //             SET first_period_room_id=:first_period_room_id
        //             WHERE course_id=:course_id
        //             AND school_year_term=:school_year_term
        //         ");

        //         $update->bindValue(":first_period_room_id", $room_id);
        //         $update->bindParam(":course_id", $course_id);
        //         $update->bindParam(":school_year_term", $school_year_term);
        //         $update->execute();
        //         if($update->rowCount() > 0){
        //             return true;
        //         }
        //     }

        //     if(ucfirst($period) == "Second"){
        //         $update = $this->con->prepare("UPDATE section
        //             SET second_period_room_id=:second_period_room_id
        //             WHERE course_id=:course_id
        //             AND school_year_term=:school_year_term
        //         ");

        //         $update->bindValue(":second_period_room_id", $room_id);
        //         $update->bindParam(":course_id", $course_id);
        //         $update->bindParam(":school_year_term", $school_year_term);
        //         $update->execute();
        //         if($update->rowCount() > 0){
        //             return true;
        //         }
        //     }

        //     return false;
        // }

        public function AssigningOpenedRoom($period, $room_id,
            $course_id, $school_year_term) {
                
            $period_column = strtolower($period) . "_period_room_id";

            $update = $this->con->prepare("UPDATE course
                SET $period_column = :room_id
                WHERE course_id = :course_id
                AND school_year_term = :school_year_term
            ");

            $update->bindParam(":room_id", $room_id);
            $update->bindParam(":course_id", $course_id);
            $update->bindParam(":school_year_term", $school_year_term);
            $update->execute();

            return $update->rowCount() > 0;
        }


        public function AssigningoomToCourseId($room_id, $course_id) {

            $query = $this->con->prepare("UPDATE room
                SET course_id=:course_id
                WHERE room_id=:room_id");

            $query->bindParam(":course_id", $course_id);
            $query->bindParam(":room_id", $room_id);
            $query->execute();

            if($query->rowCount() > 0){
                return true;
            }
            return false;
        }

        public function RoomTypeUpdate($selected_room_id, $type,
            $current_room_id = null) {

            $roomType = $type == 0 ? "SHS" : ($type == 1 ? "Tertiary" : "");


            // $query = $this->con->prepare("UPDATE room
            //     SET type=:type
            //     WHERE room_id=:room_id");

            // $query->bindParam(":type", $roomType);
            // $query->bindParam(":room_id", $room_id);
            // $query->execute();

            // if($query->rowCount() > 0){
            //     return true;
            // }

            $select = $this->con->prepare("SELECT * FROM room
                WHERE room_id=:room_id
                ");

            $select->bindParam(":room_id", $current_room_id);
            $select->execute();

            // $output = false;

            if($select->rowCount() > 0){
                // echo "1";
                $init = $this->con->prepare("UPDATE room
                    SET type=:reset_type
                    WHERE room_id=:room_id
                ");

                $init->bindValue(":reset_type", "");
                $init->bindParam(":room_id", $current_room_id);
                // $init->execute();

                if($init->execute()){

                    // echo "qweew";

                    $update_type = $this->con->prepare("UPDATE room
                        SET type=:type
                        WHERE room_id=:room_id
                    ");

                    $update_type->bindParam(":type", $roomType);
                    $update_type->bindParam(":room_id", $selected_room_id);
                    // $update_type->execute();

                    if($update_type->execute()){
                        return true;
                    }
                }

                
            }  
            if($select->rowCount() == 0){

                // echo "zero";
                $update_type2 = $this->con->prepare("UPDATE room
                    SET type=:type
                    WHERE room_id=:room_id
                ");

                $update_type2->bindParam(":type", $roomType);
                $update_type2->bindParam(":room_id", $selected_room_id);
                // $update_type2->execute();

                if($update_type2->execute()) {
                    return true;
                }
            }    

            return false;
        }


        public function AvailableSectionSYSemesterList(
            $semester_period, $school_year_term,
            $semesterSectionHasRoomIds) : array {
            
            // print_r($semesterSectionHasRoomIds);

            $enrollment = new Enrollment($this->con);

            $array = [];

            if($semester_period == "First"){

                if (empty($semesterSectionHasRoomIds)) {
                    $query = $this->con->prepare("SELECT * FROM room");
                } else {
                    $query = $this->con->prepare("SELECT * FROM room
                        WHERE room_id NOT IN (" . implode(',', $semesterSectionHasRoomIds) . ")");
                }

                $query->execute();

                if($query->rowCount() > 0){
                    return $query->fetchAll(PDO::FETCH_ASSOC);
                }

            }

            if($semester_period == "Second"){

                if (empty($semesterSectionHasRoomIds)) {
                    $query = $this->con->prepare("SELECT * FROM room");
                } else {
                    $query = $this->con->prepare("SELECT * FROM room
                        WHERE room_id NOT IN (" . implode(',', $semesterSectionHasRoomIds) . ")");
                }

                $query->execute();

                if($query->rowCount() > 0){
                    return $query->fetchAll(PDO::FETCH_ASSOC);
                }

            }

            return [];
        }

        # Open Room Section -> Enrolled student reached the min threshold
        # Specific for specified semester_period
        
        public function OpenedRoomSectionSemester(
            $semester_period, $school_year_term,
            $current_school_year_id) {

            $enrollment = new Enrollment($this->con);

            $array = [];

            if($semester_period == "First"){

                $query = $this->con->prepare("SELECT t1.* FROM course as t1

                    WHERE t1.school_year_term=:school_year_term
                    AND t1.active = 'yes'
                    -- AND t1.first_period_room_id IS NOT NULL

                    ");

                $query->bindParam(":school_year_term", $school_year_term);
                $query->execute();

                if($query->rowCount() > 0){

                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                        $min_student = $row['min_student'];
                        $course_id = $row['course_id'];
                       
                        $students_enrolled = $enrollment->GetStudentEnrolledInSection(
                            $course_id, $current_school_year_id, $school_year_term, $semester_period);

                        if($students_enrolled != 0 && $students_enrolled >= $min_student){
                            array_push($array, $row);

                            // echo $course_id;
                            // echo "<br>";
                        }else{
                            continue;
                        }
                            // array_push($array, $row);

                    }
                }
            }

            if($semester_period == "Second"){

                $query = $this->con->prepare("SELECT t1.*

                    -- ,t2.school_year_id 
                    
                    FROM course as t1

                    -- INNER JOIN enrollment as t2 ON t2.course_id = t1.course_id
                    -- AND t2.course_id

                    WHERE t1.school_year_term=:school_year_term
                    AND t1.active = 'yes'
                    -- AND t1.first_period_room_id IS NOT NULL

                    ");

                $query->bindParam(":school_year_term", $school_year_term);
                $query->execute();

                if($query->rowCount() > 0){

                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                        $min_student = $row['min_student'];
                        $course_id = $row['course_id'];
                       
                        $students_enrolled = $enrollment->GetStudentEnrolledInSection(
                            $course_id, $current_school_year_id,
                            $school_year_term, $semester_period);

                        // if($students_enrolled != 0 
                        //         && $students_enrolled >= $min_student){

                        //     array_push($array, $row);
                           
                        // }else{
                        //     continue;
                        // }
                            array_push($array, $row);

                    }
                }
            }

            // if($semester_period == "Second"){

            //     # Get all section who have students enrolled.


            //     $query = $this->con->prepare("SELECT t1.* FROM course as t1

            //         WHERE t1.school_year_term=:school_year_term
            //         AND t1.active = 'yes'
            //         AND t1.second_period_room_id IS NOT NULL
            //         ");

            //     $query->bindParam(":school_year_term", $school_year_term);
            //     $query->execute();

            //     if($query->rowCount() > 0){

            //         while($row = $query->fetch(PDO::FETCH_ASSOC)){

            //             $min_student = $row['min_student'];
            //             $course_id = $row['course_id'];
                       
            //             $students_enrolled = $enrollment->GetStudentEnrolled(
            //                 $course_id, $current_school_year_id);

            //             // if($students_enrolled >= $min_student){
            //             //     array_push($array, $row);

            //             //     // echo $course_id;
            //             //     // echo "<br>";
            //             // }else{
            //             //     continue;
            //             // }

            //                 array_push($array, $row);

            //         }
            //     }
            // }
            return $array;
        }



        # Section did not reached the threshold
        # and has student enrolled
        # Options -> 
        # 1. Waiting List, 
        # 2. Merge to similar program section level
        # 3. Un-enroll these students 
        # And set In-active the section since there`s no room to accomodate section

        public function UnReachedMinimumStudentSectionSemester($uniqueCourseIds) {

            $enrollment = new Enrollment($this->con);

            $array = [];

            foreach ($uniqueCourseIds as $key => $value) {

                $sql = $this->con->prepare("SELECT * FROM course
                    WHERE course_id=:course_id
                    LIMIT 1");

                $course_id = $value['course_id'];


                $sql->bindParam(":course_id", $course_id);
                $sql->execute();

                if($sql->rowCount() > 0){

                    $row = $sql->fetch(PDO::FETCH_ASSOC);

                    $course_id = $row['course_id'];
                    $min_student = $row['min_student'];

                    // echo $min_student;

                    $students_enrolled = $enrollment->GetStudentEnrolled($course_id);

                    if($students_enrolled < $min_student){
                        array_push($array, $row);
                    }else{
                        continue;
                    }
                }
            }

            return $array;

        }


        public function UnReachedMinimumStudentSectionSemesterv2(
            $semester_period, $school_year_term,
            $current_school_year_id) {

            $enrollment = new Enrollment($this->con);

            $array = [];

            if($semester_period == "First"){

                $query = $this->con->prepare("SELECT t1.* FROM course as t1

                    WHERE t1.school_year_term=:school_year_term
                    AND t1.active = 'yes'
                    -- AND t1.first_period_room_id IS NOT NULL

                    ");

                $query->bindParam(":school_year_term", $school_year_term);
                $query->execute();

                if($query->rowCount() > 0){

                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                        $min_student = $row['min_student'];
                        $course_id = $row['course_id'];
                       
                        $students_enrolled = $enrollment->GetStudentEnrolledInSection(
                            $course_id, $current_school_year_id, $school_year_term, $semester_period);

                        if($students_enrolled < $min_student){

                            array_push($array, $row);

                        }else{
                            continue;
                        }
                             

                    }
                }
            }

            if($semester_period == "Second"){

                $query = $this->con->prepare("SELECT t1.* FROM course as t1

                    WHERE t1.school_year_term=:school_year_term
                    AND t1.active = 'yes'
                    -- AND t1.first_period_room_id IS NOT NULL
 ");

                $query->bindParam(":school_year_term", $school_year_term);
                $query->execute();

                if($query->rowCount() > 0){

                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                        $min_student = $row['min_student'];
                        $course_id = $row['course_id'];
                       
                        $students_enrolled = $enrollment->GetStudentEnrolledInSection(
                            $course_id, $current_school_year_id, $school_year_term, $semester_period);

                        if($students_enrolled < $min_student){

                            array_push($array, $row);

                        }else{
                            continue;
                        }
                             

                    }
                }
            }


            return $array;

        }

        public function CheckStudentSectionHasRoom($student_course_id,
            $semester_period, $school_year_term) {

            
            if($semester_period == "First"){

                $query = $this->con->prepare("SELECT first_period_room_id 
                
                    FROM course

                    WHERE school_year_term=:school_year_term
                    AND course_id=:course_id
                    AND (first_period_room_id IS NOT NULL
                        AND first_period_room_id != 0)");

                $query->bindParam(":school_year_term", $school_year_term);
                $query->bindParam(":course_id", $student_course_id);
                $query->execute();

                if($query->rowCount() > 0){
                    // return $query->fetchColumn();
                    return true;
                }
            }else if($semester_period == "Second"){

                $query = $this->con->prepare("SELECT second_period_room_id FROM course
                    WHERE school_year_term=:school_year_term
                    AND course_id=:course_id
                    AND (second_period_room_id IS NOT NULL
                        AND second_period_room_id != 0)");

                $query->bindParam(":school_year_term", $school_year_term);
                $query->bindParam(":course_id", $student_course_id);
                $query->execute();

                if($query->rowCount() > 0){
                    // return $query->fetchColumn();
                    return true;
                }
            }

            return false;
        }
        
    }
?>