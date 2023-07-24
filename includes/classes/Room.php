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

            if($select->rowCount() > 0){
                // echo "qweew";
                // echo "1";
                $init = $this->con->prepare("UPDATE room
                    SET type=:reset_type
                    WHERE room_id=:room_id
                ");

                $init->bindValue(":reset_type", "");
                $init->bindParam(":room_id", $current_room_id);
                $init->execute();

                if($init->rowCount() > 0){
                    $update_type = $this->con->prepare("UPDATE room
                        SET type=:type
                        WHERE room_id=:room_id
                    ");

                    $update_type->bindParam(":type", $roomType);
                    $update_type->bindParam(":room_id", $selected_room_id);
                    $update_type->execute();

                    if($update_type->rowCount() > 0) return true;

                }
            
            }else if($select->rowCount() == 0){
                $update_type = $this->con->prepare("UPDATE room
                    SET type=:type
                    WHERE room_id=:room_id
                ");

                $update_type->bindParam(":type", $roomType);
                $update_type->bindParam(":room_id", $selected_room_id);
                $update_type->execute();

                if($update_type->rowCount() > 0) return true;
            }

            return false;
        }
    }
?>