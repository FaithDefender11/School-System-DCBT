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

            <div class='row offset-md-1'>
                <div class="col-md-10">
                    <div class='card'>
                        <hr>
                        <a href="index.php">
                            <button class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </a>
                        <div class='card-header'>
                            <h4 class='text-center mb-3'>Edit Room</h4>
                        </div>
                        <div class='card-body'>
                            <form method='POST' enctype='multipart/form-data'>
                                <div class='form-group mb-2'>
                                    <label for=''>Room Name</label>
                                    <input class='form-control' type='text' value="<?php echo $room_name;?>" placeholder='' name='room_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for=''>Room Capacity</label>
                                    <input class='form-control' type='text' value="<?php echo $room_capacity;?>" placeholder='' name='room_capacity'>
                                </div>

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name="edit_room_btn_<?php echo $room_id;?>">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        <?php

    }

?>

