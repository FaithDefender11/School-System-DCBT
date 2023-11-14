<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con);

    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    if(
        isset($_POST['create_room_btn']) &&
        isset($_POST['room_number']) &&
        isset($_POST['room_capacity'])
        ){

        // $room_name = $_POST['room_name'];

        $room_number = $_POST['room_number'];
        
        $room_capacity = $_POST['room_capacity'];


        $statement = $con->prepare("INSERT INTO room (room_number, room_capacity) 
            VALUES (:room_number, :room_capacity)");

        $statement->bindParam(":room_number", $room_number);
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
                        <span>
                            <label for="room_number">* Room number</label>
                            <div>
                                <input class="form-control" type="text" name="room_number" placeholder="">
                            </div>
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <label for="room_capacity">* Room Capacity</label>
                            <div>
                                <input class="form-control"  type="text" name="room_capacity" placeholder="">
                            </div>
                        </span>
                    </div>
                    <div class="action">
                        <button type="submit" class="clean large" name="create_room_btn">Save</button>
                    </div>
                </form>
            </main>
        </div>
    </body>
    <?php
?>

