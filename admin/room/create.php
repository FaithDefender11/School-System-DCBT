<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con);

    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    if(isset($_POST['create_room_btn'])){

        $room_name = $_POST['room_name'];
        $room_capacity = $_POST['room_capacity'];


        $statement = $con->prepare("INSERT INTO room (room_name, room_capacity) 
            VALUES (:room_name, :room_capacity)");

        $statement->bindParam(":room_name", $room_name);
        $statement->bindParam(":room_capacity", $room_capacity);

        if ($statement->execute()) {
            Alert::success("Room Successfully Created", "index.php");
            exit();
        } else {
            Alert::error("Error Occured", "index.php");
            exit();
        }
    }
    
    ?>
        <div class='col-md-12 row '>
            <div class='col-md-10 offset-md-1'>
                <div class='card'>
                    <hr>
                    <a href="index.php">
                        <button class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>
                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Create Room</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>
                            <div class='form-group mb-2'>
                                <label for=''>Room Name</label>
                                <input class='form-control' type='text' placeholder='' name='room_name'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Room Capacity</label>
                                <input class='form-control' type='text' placeholder='' name='room_capacity'>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='create_room_btn'>Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
?>

