<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Room.php');

    if(isset($_GET['id'])){

        $room_id = $_GET['id'];


        $room = new Room($con, $room_id);

        $check = $room->CheckIdExists($room_id);

        $room_name = $room->GetRoomName();
        $room_capacity= $room->GetRoomCapacity();

        if(isset($_POST['edit_room_btn_' . $room_id])){

            $room_name = $_POST['room_name'];

            $statement = $con->prepare("UPDATE room 
                SET room_name = :new_room_name,
                    room_capacity = :room_capacity
                WHERE room_id = :room_id");

            // Assuming you have an 'id' column in the 'department' table to uniquely identify the department
            $statement->bindParam(":new_room_name", $room_name);
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
                    <form method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <label for="room_name">Room Name</label>
                            <div>
                                <input type="text" name="room_name" value="<?php echo $room_name; ?>">
                            </div>
                        </div>
                        <div class="row">
                            <label for="room_capacity">Room Capacity</label>
                            <div>
                                <input type="text" name="room_capacity" value="<?php echo $room_id; ?>">
                            </div>
                        </div>
                        <div class="action">
                            <button type="submit" class="clean large" name="edit_room_btn_<?php echo $room_id; ?>">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </body>
        <?php

    }

?>

