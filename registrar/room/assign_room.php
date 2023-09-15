
<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');

    $room = new Room($con);
    $enrollment = new Enrollment($con);
    $section = new Section($con);

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id'])){

        $course_id = $_GET['id'];

        $semesterSectionHasRoomIds = $section->GetSectionIdHasRoomSemester($current_school_year_period,
            $current_school_year_term);

        // $placeholders = implode(',', array_fill(0, count($semesterSectionHasRoomIds), '?'));

    //    print_r($semesterSectionHasRoomIds);

        // echo $placeholders;

        $back_url = "opened_section.php?id=$current_school_year_id";

        if(isset($_POST['assign_room_' . $course_id])){

            $room_id = $_POST['room_id'];
          
            $statement = $room->AssigningOpenedRoom($current_school_year_period,
                $room_id, $course_id, 
                $current_school_year_term);

            if ($statement == true) {
                Alert::successAutoRedirect("Room Successfully Created",
                    "$back_url");
                exit();
            } else {
                Alert::error("Error Occured", "");
                exit();
            }
        }
        
        ?>
        <div class='col-md-12 row '>
            <div class='col-md-10 offset-md-1'>

                <div class='card'>
                    <hr>

                    <a href="<?php echo $back_url; ?>">
                        <button class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>

                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Select Room for <?php echo $current_school_year_period;?> Semester</h4>
                    </div>

                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>
                            <div class='form-group mb-2'>
                                <label for='room_id'>Available Room</label>
                                <select class="form-control" name="room_id" id="room_id">
                                <?php 

                                    if (empty($semesterSectionHasRoomIds)) {
                                        $query = $con->prepare("SELECT * FROM room");
                                    } else {
                                        $query = $con->prepare("SELECT * FROM room
                                            WHERE room_id NOT IN (" . implode(',', $semesterSectionHasRoomIds) . ")");
                                    }

                                    // $query = $con->prepare("SELECT * FROM room
                                    //     WHERE room_id NOT IN (" . implode(',', $semesterSectionHasRoomIds) . ")");

                                    $query->execute();

                                    if($query->rowCount() > 0){

                                        echo "
                                            <option value='' selected disabled>Select Room</option>
                                            <option value='NULL'>Reset</option>
                                        ";

                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){
                                            
                                            $room_id = $row['room_id'];
                                            $room_name = $row['room_name'];

                                            echo "
                                                <option value='$room_id'>$room_name</option>
                                            ";
                                        }
                                       
                                    }
                                
                                ?>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='assign_room_<?php echo $course_id;?>'>Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php

    }
?>