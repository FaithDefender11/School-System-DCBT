<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');




    if(isset($_GET['id'])){

        $room_id = $_GET['id'];

        $button_text = "Save";
        $disabled = "";
        $not_allowed_cursor = "pointer";

        $room = new Room($con, $room_id);



        $school_year = new SchoolYear($con, null);
        $schedule = new Schedule($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];


        $check = $room->CheckIdExists($room_id);

        if($room->CheckRoomHasAttachedSubjectWithinSYSemester(
            $room_id, $current_school_year_id) > 0){
            // echo "Has";
            $button_text = "Has room attached";
            $disabled = "disabled";
            $not_allowed_cursor = "not-allowed";
        } 

        $room_name = $room->GetRoomName();
        $room_number = $room->GetRoomNumber();
        $room_capacity = $room->GetRoomCapacity();

        // var_dump($room_capacity);

        if(isset($_POST['edit_room_btn_' . $room_id])){

            // $room_name = $_POST['room_name'];
            $room_number = $_POST['room_number'];
            $room_capacity = $_POST['room_capacity'];

            $statement = $con->prepare("UPDATE room 
                SET room_number = :new_room_number,
                    room_capacity = :room_capacity
                WHERE room_id = :room_id");

            // Assuming you have an 'id' column in the 'department' table to uniquely identify the department
            $statement->bindParam(":new_room_number", $room_number);
            $statement->bindParam(":room_capacity", $room_capacity);
            $statement->bindParam(":room_id", $room_id);

            if ($statement->execute()) {
                Alert::success("Room Successfully Updated", "index.php");
                exit();
            } else {
                Alert::error("Error Occured", "index.php");
                exit();
            }
        }
        
        ?>
        <body>
            <div class="content">
                <nav>
                    <a href="index.php">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                <main>
                    <form method="POST">
                        <span>

                            <?php 

                                
                            
                            ?>
                        </span>
                        <div class="row">
                            <label for="room_number">* Room number</label>
                            <div>
                                <input class="form-control" id="room_number" type="text" name="room_number" placeholder="" value="<?php echo $room_number; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <label for="room_capacity">* Room Capacity</label>
                            <div>
                                <input class="form-control" type="text" name="room_capacity" id="room_capacity" placeholder="" value="<?php echo $room_capacity; ?>">
                            </div>
                        </div>
                        <div class="action">
                            <button style="cursor: <?php echo $not_allowed_cursor;?>;" disabled type="submit"  class="clean large" name="edit_room_btn_<?php echo $room_id; ?>"><?= $button_text; ?></button>
                        </div>
                    </form>
                </main>
            </div>
        </body>
        <?php

    }

?>

