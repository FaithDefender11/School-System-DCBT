<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
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

    if(isset($_GET['id']) && isset($_GET['level'])){

        $course_level = $_GET['level'];
        $program_id = $_GET['id'];

        $program = new Program($con, $program_id);

        $SHS = "Senior High School";
        $TERTIARY = "Tertiary";

        $section = new Section($con, null);

        $trackDropdown = $section->createProgramSelection($program_id);


        $section_term = "";
        
        if(isset($_SESSION['section_term'])){
            $section_term = $_SESSION['section_term'];
        }

        $department_type_section = $_SESSION['department_type_section'];

        $back_url = "";
        if($department_type_section === "Senior High School"){
            $back_url = "shs_list.php?id=$program_id&term=$section_term";

        }else if($department_type_section === "Tertiary"){
            $back_url = "tertiary_list.php?id=$program_id&term=$section_term";
        }
        
        $courseLevelDropdown = $section->
            CreateCourseLevelDropdownDepartmentBased($department_type_section,
                $course_level);
 
        if(isset($_POST['create_section_btn']) &&
            isset($_POST['program_section']) && 
            isset($_POST['program_id']) &&
            isset($_POST['capacity']) &&
            isset($_POST['adviser_teacher_id']) &&
            // isset($_POST['room'])
            isset($_POST['room_id'])
            // isset($_POST['course_level'])

        ){

            $is_tertiary = 1;

            $program_section = $_POST['program_section'];
            $program_id = $_POST['program_id'];
            $capacity = $_POST['capacity'];
            $adviser_teacher_id = $_POST['adviser_teacher_id'];
            // $room = $_POST['room'];
            $room_id = $_POST['room_id'];

            // $course_level = (int) $_POST['course_level'];

            $is_active = "yes";
            $not_full = "no";

            $sql = $con->prepare("SELECT t2.department_name FROM program as t1

                INNER JOIN department as t2 ON t2.department_id = t1.department_id 
                WHERE t1.program_id=:program_id
                LIMIT 1");

            $sql->bindValue(":program_id", $program_id);
            $sql->execute();

            if($sql->rowCount() > 0){

                $department_name = $sql->fetchColumn();
                $is_tertiary = ($department_name == "Senior High School") ? 0 : 1;
            }

            if($section->CheckSetionExistsWithinCurrentSY($program_section,
                $current_school_year_term) == true){
                Alert::error("$program_section already exists within $current_school_year_term term", "add_section.php?id=$program_id&level=$course_level");
                exit();
            }

            if(false){
            // if($current_school_year_period == "First"){

                $insert = $con->prepare("INSERT INTO course
                    (program_section, program_id, capacity, adviser_teacher_id, 
                        school_year_term, first_period_room_id, active, is_full, course_level, is_tertiary)

                    VALUES(:program_section, :program_id, :capacity, :adviser_teacher_id,
                        :school_year_term, :first_period_room_id, :active, :is_full, :course_level, :is_tertiary)");
                
                $insert->bindParam(":program_section", $program_section);
                $insert->bindParam(":program_id", $program_id);
                $insert->bindParam(":capacity", $capacity);
                $insert->bindParam(":adviser_teacher_id", $adviser_teacher_id);
                // $insert->bindParam(":room", $room);
                $insert->bindParam(":school_year_term", $current_school_year_term);
                $insert->bindParam(":first_period_room_id", $room_id);
                $insert->bindParam(":active", $is_active);
                $insert->bindParam(":is_full", $not_full);
                $insert->bindParam(":course_level", $course_level, PDO::PARAM_INT);
                $insert->bindParam(":is_tertiary", $is_tertiary, PDO::PARAM_INT);
                $insert->execute();

                if($insert->rowCount() > 0){

                    $recently_created_course_id = $con->lastInsertId();

                    $sectionExec = new Section($con, $recently_created_course_id);
                    $section_type = $sectionExec->GetSectionType();

                    if($recently_created_course_id != 0){

                        $room = new Room($con);

                        $wasSuccess = $room->RoomTypeUpdate(
                            $room_id, $section_type);

                        if($wasSuccess){
                            Alert::success("Successfully created $program_section section (S.Y $current_school_year_term).",
                                    "$back_url");
                            exit();
                        }
                    }
                }
                
            }

            // $current_school_year_period = "Second";

            if ($current_school_year_period == "First") {

                $insert = $con->prepare("INSERT INTO course
                    (program_section, program_id, capacity, adviser_teacher_id, 
                    school_year_term, first_period_room_id, active, is_full, course_level, is_tertiary)

                    VALUES(:program_section, :program_id, :capacity, :adviser_teacher_id,
                    :school_year_term, :first_period_room_id, :active, :is_full, :course_level, :is_tertiary)");

                $insert->bindParam(":first_period_room_id", $room_id);

            } 
            else if ($current_school_year_period == "Second") {

                $insert = $con->prepare("INSERT INTO course
                    (program_section, program_id, capacity, adviser_teacher_id, 
                    school_year_term, second_period_room_id, active, is_full, course_level, is_tertiary)

                    VALUES(:program_section, :program_id, :capacity, :adviser_teacher_id,
                    :school_year_term, :second_period_room_id, :active, :is_full, :course_level, :is_tertiary)");

                $insert->bindParam(":second_period_room_id", $room_id);
            }

            $insert->bindParam(":program_section", $program_section);
            $insert->bindParam(":program_id", $program_id);
            $insert->bindParam(":capacity", $capacity);
            $insert->bindParam(":adviser_teacher_id", $adviser_teacher_id);
            $insert->bindParam(":school_year_term", $current_school_year_term);
            $insert->bindParam(":active", $is_active);
            $insert->bindParam(":is_full", $not_full);
            $insert->bindParam(":course_level", $course_level, PDO::PARAM_INT);
            $insert->bindParam(":is_tertiary", $is_tertiary, PDO::PARAM_INT);
            $insert->execute();

            if($insert->rowCount() > 0){

                $recently_created_course_id = $con->lastInsertId();

                $sectionExec = new Section($con, $recently_created_course_id);
                $section_type = $sectionExec->GetSectionType();

                if($recently_created_course_id != 0){

                    $room = new Room($con);

                    $wasSuccess = $room->RoomTypeUpdate(
                        $room_id, $section_type);

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
                            <h4 class='text-center mb-3'><?php echo $program->GetProgramSectionName();?><?php echo $course_level;?> Add Section</h4>
                        </div>

                        <div class="card-body">
                            <form method='POST'>

                                <?php echo $trackDropdown;?>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Section</label>

                                    <input required class='form-control' type='text' 
                                        value="<?php echo $program->GetProgramSectionName();?><?php echo $course_level;?>-" placeholder='e.g: STEM11-A, ABM11-A' name='program_section'>
                                </div>
    
                                <!-- <?php echo $courseLevelDropdown;?> -->

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Capacity</label>
                                    <input required class='form-control' value="30" type='number' placeholder='Room Capacity' name='capacity'>
                                </div>

                                <!-- <div class='form-group mb-2'>
                                    <input class='form-control' type='text' placeholder='Adviser Name' name='adviser_teacher_id'>
                                </div> -->

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Adviser Name</label>

                                    <select required class="form-control" name="adviser_teacher_id" id="adviser_teacher_id">
                                        <?php
                                            $query = $con->prepare("SELECT * FROM teacher");
                                            $query->execute();
                                            
                                            echo "<option value='' disabled selected>Choose Teacher</option>";

                                            if ($query->rowCount() > 0) {
                                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = "";  

                                                    // Add condition to check if the option should be selected
                                                    if ($row['teacher_id'] == $selectedTeacherId) {
                                                        $selected = "selected";
                                                    }
                                                    echo "<option value='" . $row['teacher_id'] . "' $selected>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>

                                <!-- <div class='form-group mb-2'>
                                    <label class='mb-2'>Room</label>
                                    <input required class='form-control' type='number' placeholder='Room' name='room'>
                                </div> -->

                                <div class='form-group mb-2'>

                                    <label class='mb-2'>* Room</label>

                                    <select required class="form-control" name="room_id" id="room_id">
                                        <?php
                                            $query = $con->prepare("SELECT * FROM room
                                                -- WHERE school_year_id=:school_year_id
                                                ");

                                            // $query->bindParam(":school_year_id", $current_school_year_id);
                                            $query->execute();
                                            
                                            echo "<option value='' disabled selected>Choose Room</option>";
                                            if ($query->rowCount() > 0) {
                                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = "";  
                                                    echo "<option value='" . $row['room_id'] . "' $selected>" . $row['room_number'] ."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>

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
                                                    echo "<option value='" . $row['room_id'] . "' $selected>" . $row['room_number'] ."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div> -->

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='create_section_btn'>Save Section</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                

            </div>
        <?php

    }

?>

    <script>
        $('#program_id').on('change', function() {

            var program_id = parseInt($(this).val());
            console.log(program_id)


            $.ajax({
                url: '../../ajax/section/get_level_from_program.php',
                type: 'POST',
                data: {
                    program_id
                },
                dataType: 'json',

                success: function(response) {
                    var options = '<option value="">Choose Level</option>';

                    $.each(response, function(index, value) {

                        if(value.level > 5){
                            options += '<option value="' + value.level + '">Grade ' + value.level +'</option>';

                        }
                        else if(value.level <= 4){
                            var yearLabel;
                            switch (value.level) {
                                case "1":
                                    yearLabel = "1st year";
                                    break;
                                case "2":
                                    yearLabel = "2nd year";
                                    break;
                                case "3":
                                    yearLabel = "3rd year";
                                    break;
                                case "4":
                                    yearLabel = "4th year";
                                    break;
                                default:
                                    yearLabel = value.level + "th year";
                            }
                            options += '<option value="' + value.level + '">' + yearLabel + '</option>';
                        }
                    });

                    $('#course_level').html(options);
                }
            });
        });
    </script>