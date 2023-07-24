<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Room.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con);

    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id']) && isset($_GET['p_id'])){

        $course_id = $_GET['id'];
        $program_id = $_GET['p_id'];

        $program = new Program($con, $program_id);

        $section = new Section($con, $course_id);


        $get_subjects_linked = $con->prepare("SELECT * 
            
            FROM subject

            WHERE course_id=:course_id
            ");

        $get_subjects_linked->bindParam(":course_id", $course_id);
        $get_subjects_linked->execute();

        // if($get_subjects_linked->rowCount()> 0){
        //     while($row = $get_subjects_linked->fetch(PDO::FETCH_ASSOC)){

        //         $subject_code = $row['subject_code'];

        //         $string = "PE101-STEM11-E";
        //         $substring = substr($string, strpos($string, '-') + 1);


        //         $newString = str_replace($substring, "New String", $string);
        //         echo $newString . "<br>";
                
        //         // UPDATE
        //     }
        // }



        $db_course_level = $section->GetSectionGradeLevel();
        $db_program_section = $section->GetSectionName();
        $db_room = $section->GetSectionRoom();
        $db_capacity = $section->GetSectionCapacity();
        $db_advisery_id = $section->GetSectionAdviseryId();

        $first_period_room_id = $section->GetSectionFirstPeriodRoomId();
        $second_period_room_id = $section->GetSectionSecondPeriodRoomId();

        $current_period_room_id = $current_school_year_period == "First" ? $first_period_room_id : 
            ($current_school_year_period == "Second" ? $second_period_room_id : NULL);

        // echo $second_period_room_id;

        $trackDropdown = $section->createProgramSelection($program_id);

        $promptIfIDNotExists = $program->CheckIdExists($program_id);

        $SHS = "Senior High School";
        $TERTIARY = "Tertiary";

        $section_term = "";

        if(isset($_SESSION['section_term'])){
            $section_term = $_SESSION['section_term'];
        }

        $department_type_section = "";

        if(isset($_SESSION['department_type_section'])){
            $department_type_section = $_SESSION['department_type_section'];
        }


        $back_url = "";
        if($department_type_section === "Senior High School"){
            $back_url = "shs_list.php?id=$program_id&term=$section_term";

        }else if($department_type_section === "Tertiary"){
            $back_url = "tertiary_list.php?id=$program_id&term=$section_term";
        }

        $courseLevelDropdown = $section->
            CreateCourseLevelDropdownDepartmentBased($department_type_section,
                $db_course_level);


        if(isset($_POST['edit_section_btn_' . $program_id]) &&
            isset($_POST['program_section']) && 
            isset($_POST['program_id']) &&
            isset($_POST['capacity']) &&
            isset($_POST['adviser_teacher_id']) &&
            isset($_POST['room_id']) &&
            // isset($_POST['room']) &&
            isset($_POST['course_level'])
        ){

            $is_tertiary = 1;

            $program_section = $_POST['program_section'];
            $program_id = $_POST['program_id'];
            $capacity = $_POST['capacity'];
            $adviser_teacher_id = $_POST['adviser_teacher_id'];
            $room_id = $_POST['room_id'];

            $room_id = ($_POST['room_id'] == 0) ? NULL : $_POST['room_id'];

            $course_level = (int) $_POST['course_level'];

            $is_active = "yes";
            $not_full = "no";


            $is_tertiary = ($department_type_section == "Senior High School") ? 0 : 1;

            // echo $is_tertiary;

            // if($section->CheckSetionExistsWithinCurrentSY($program_section,
            //     $current_school_year_term) == true){
            //     Alert::error("$program_section already exists within $current_school_year_term term", "add_section.php?id=$program_id&level=$course_level");
            //     exit();
            // }

            if ($current_school_year_period == "First") {
                $update = $con->prepare("UPDATE course SET
                    program_section = :program_section,
                    program_id = :program_id,
                    capacity = :capacity,
                    adviser_teacher_id = :adviser_teacher_id,
                    first_period_room_id = :first_period_room_id,
                    course_level = :course_level
                    WHERE course_id = :course_id");

                $update->bindParam(":first_period_room_id", $room_id);

            }else if ($current_school_year_period == "Second") {

                $update = $con->prepare("UPDATE course SET
                    program_section = :program_section,
                    program_id = :program_id,
                    capacity = :capacity,
                    adviser_teacher_id = :adviser_teacher_id,
                    second_period_room_id = :second_period_room_id,
                    course_level = :course_level
                    WHERE course_id = :course_id");

                $update->bindParam(":second_period_room_id", $room_id);

            }

            // $update = $con->prepare("UPDATE course SET
            //     program_section = :program_section,
            //     program_id = :program_id,
            //     capacity = :capacity,
            //     adviser_teacher_id = :adviser_teacher_id,
            //     -- room = :room,
            //     course_level = :course_level
            //     WHERE course_id = :course_id");

            $update->bindParam(":program_section", $program_section);
            $update->bindParam(":program_id", $program_id);
            $update->bindParam(":capacity", $capacity);
            $update->bindParam(":adviser_teacher_id", $adviser_teacher_id);
            $update->bindParam(":course_level", $course_level, PDO::PARAM_INT);
            $update->bindParam(":course_id", $course_id);
            $update->execute();

            if($update->rowCount() > 0){

                // $recently_created_course_id = $con->lastInsertId();

                // Alert::success("Successfully created $program_section section (S.Y $current_school_year_term).",
                //         "$back_url");
                // exit();

                $sectionExec = new Section($con, $course_id);
                $section_type = $sectionExec->GetSectionType();

                if($course_id != 0){

                    $room = new Room($con);

                    $wasSuccess = $room->RoomTypeUpdate(
                        $room_id, $section_type, $current_period_room_id);

                    // echo $room_id;
                    // echo $current_period_room_id;

                    if($wasSuccess){
                        Alert::success("Successfully created $program_section section (S.Y $current_school_year_term).",
                            "$back_url");
                        exit();
                    }
                }
                
            }
        }

        ?>

            <div class='col-md-12 row'>
                <div class='col-md-10 offset-md-1'>
                    <div class='card'>
                        <hr>
                        <a style="margin-left: 10px;" href="<?php echo $back_url;?>">
                            <button class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </a>
                        <div class='card-header'>
                            <h4 Edit Section class='text-center mb-3'><?php echo $section->GetSectionName();?></h4>
                        </div>

                        <div class="card-body">
                            <form method='POST'>

                                <?php echo $trackDropdown;?>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Section</label>

                                    <input required class='form-control' type='text' 
                                        value="<?php echo $db_program_section;?>" placeholder='e.g: STEM11-A, ABM11-A' name='program_section'>
                                </div>
    
                                <?php echo $courseLevelDropdown;?>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Capacity</label>
                                    <input required class='form-control' value="<?php echo $db_capacity; ?>" type='number' placeholder='Room Capacity' name='capacity'>
                                </div>

                                <!-- <div class='form-group mb-2'>
                                    <input class='form-control' type='text' placeholder='Adviser Name' name='adviser_teacher_id'>
                                </div> -->

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Advisery Name</label>

                                    <select required class="form-control" name="adviser_teacher_id" id="adviser_teacher_id">
                                        <?php
                                            $query = $con->prepare("SELECT * FROM teacher");
                                            $query->execute();

                                            $output = "";
                                            
                                            $output .= "<option value='' disabled selected>Choose Teacher</option>";
                                            // $output .= "<option value='' disabled selected>Choose Teacher</option>";
                                            // $output .= "<option value=''>Remove Room</option>";


                                            if ($query->rowCount() > 0) {
                                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = "";  

                                                    // Add condition to check if the option should be selected
                                                    if ($row['teacher_id'] == $db_advisery_id) {
                                                        $selected = "selected";
                                                    }
                                                    $output .= "<option value='" . $row['teacher_id'] . "' $selected>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                                }
                                            }
                                            echo $output;
                                        ?>
                                    </select>
                                </div>

                                <!-- <div class='form-group mb-2'>
                                    <label class='mb-2'>Room</label>
                                    <input required class='form-control' value="<?php echo $db_room;?>" type='number' placeholder='Room' name='room'>
                                </div> -->

                                <!-- <div class='form-group mb-2'>
                                    <label class='mb-2'>* Room</label>

                                    <select required class="form-control" name="room_id" id="room_id">
                                        <?php
                                            $query = $con->prepare("SELECT * FROM room
                                                WHERE school_year_id=:school_year_id");
                                            $query->bindParam(":school_year_id", $current_school_year_id);
                                            $query->execute();
                                            
                                            echo "<option value='' disabled selected>Choose Room</option>";
                                            if ($query->rowCount() > 0) {
                                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = "";  
                                                    if($row['course_id'] == $course_id) $selected = "selected";
                                                    echo "<option value='" . $row['room_id'] . "' $selected>" . $row['room_number'] ."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div> -->

                                <?php
                                
                                    if($current_school_year_period == "First"){
                                        ?>
                                            <div class='form-group mb-2'>

                                                <label class='mb-2'>* Room</label>

                                                <select required class="form-control" name="room_id" id="room_id">
                                                    <?php
                                                        $query = $con->prepare("SELECT * FROM room
                                                            -- WHERE room_id=:room_id
                                                            ");

                                                        // $query->bindParam(":room_id", $current_period_room_id);
                                                        $query->execute();
                                                        
                                                        echo "<option value='' disabled selected>Choose Room</option>";
                                                        echo "<option value='0'>Reset</option>";

                                                        if ($query->rowCount() > 0) {
                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = "";  
                                                                if($row['room_id'] == $current_period_room_id) $selected = "selected";
                                                                echo "<option value='" . $row['room_id'] . "' $selected>" . $row['room_number'] ."</option>";
                                                            }
                                                        }

                                                    ?>
                                                </select>
                                            </div>

                                        <?php   
                                    }

                                    else if($current_school_year_period == "Second"){
                                        ?>
                                            <div class='form-group mb-2'>

                                                <label class='mb-2'>* Room</label>

                                                <select required class="form-control" name="room_id" id="room_id">
                                                    <?php
                                                        $query = $con->prepare("SELECT * FROM room
                                                            -- WHERE room_id=:room_id
                                                            ");

                                                        // $query->bindParam(":room_id", $current_period_room_id);
                                                        $query->execute();
                                                        
                                                        echo "<option value='' disabled selected>Choose Room</option>";

                                                        if ($query->rowCount() > 0) {
                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = "";  
                                                                if($row['room_id'] == $current_period_room_id) $selected = "selected";
                                                                echo "<option value='" . $row['room_id'] . "' $selected>" . $row['room_number'] ."</option>";
                                                            }
                                                        }

                                                    ?>
                                                </select>
                                            </div>

                                        <?php   
                                    }

                                ?>


                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='edit_section_btn_<?php echo $program_id;?>'>Save Section</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                

            </div>
        <?php

    }
?>