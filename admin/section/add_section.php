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

    // $current_school_year_period = "Second";

    if(isset($_GET['id']) 
        && isset($_GET['level'])
        && isset($_GET['term'])){

        $course_level = $_GET['level'];
        $program_id = $_GET['id'];
        $selected_term = $_GET['term'];

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
 
        if($_SERVER['REQUEST_METHOD'] === "POST" &&
            isset($_POST['create_section_btn']) &&
            isset($_POST['program_section']) && 
            isset($_POST['program_id']) &&
            isset($_POST['capacity']) &&
            isset($_POST['min_student']) 
            // && isset($_POST['adviser_teacher_id'])
            // isset($_POST['room'])
            // isset($_POST['room_id'])
            // isset($_POST['course_level'])

        ){

            $is_tertiary = 1;

            $program_section = $_POST['program_section'];
            $program_id = $_POST['program_id'];
            $capacity = $_POST['capacity'];

            $min_student = $_POST['min_student'];

            $adviser_teacher_id = $_POST['adviser_teacher_id'] ?? NULL;
            // $room = $_POST['room'];

            // $room_id = $_POST['room_id'];

            $first_period_room_id = isset($_POST['first_period_room_id']) ? $_POST['first_period_room_id'] : NULL;
            $second_period_room_id = isset($_POST['second_period_room_id']) ? $_POST['second_period_room_id'] : NULL;

            // echo $first_period_room_id;
            // echo "<br>";

            // echo $second_period_room_id;
            // echo "<br>";

            $is_active = "yes";
            $not_full = "no";

            if($first_period_room_id != 0 && $current_school_year_period == "First"
                && $section->CheckRoomIsTakenCurrentSemester($first_period_room_id,
                "first_period_room_id",
                $current_school_year_term)){

                Alert::error("The chosen Room already has been taken. Please choose an available one.",
                    "");
                return;
            }

            if($second_period_room_id != 0 && 
                $current_school_year_period == "Second"
                && $section->CheckRoomIsTakenCurrentSemester($second_period_room_id,
                    "second_period_room_id",
                    $current_school_year_term)){
                    
                Alert::error("The chosen Room already has been taken. Please choose an available one.",
                    "");
                return;
            }

            if($section->CheckSetionExistsWithinCurrentSY($program_section,
                $selected_term) == true){
                Alert::error("$program_section already exists within $term term", "add_section.php?id=$program_id&level=$course_level&term=$selected_term");
                exit();
            }

            $insert = "";
            // if (false) {
            if ($current_school_year_period == "First") {

                $insert = $con->prepare("INSERT INTO course
                    (program_section, program_id, capacity, adviser_teacher_id, 
                    school_year_term, first_period_room_id, active, is_full, course_level, is_tertiary, min_student)

                    VALUES(:program_section, :program_id, :capacity, :adviser_teacher_id,
                    :school_year_term, :first_period_room_id, :active, :is_full, :course_level, :is_tertiary, :min_student)");

                $insert->bindParam(":first_period_room_id", $first_period_room_id);
            } 
            // else if (false) {
            else if ($current_school_year_period == "Second") {

                $insert = $con->prepare("INSERT INTO course
                    (program_section, program_id, capacity, adviser_teacher_id, 
                    school_year_term, second_period_room_id, active, is_full, course_level, is_tertiary, min_student)

                    VALUES(:program_section, :program_id, :capacity, :adviser_teacher_id,
                    :school_year_term, :second_period_room_id, :active, :is_full, :course_level, :is_tertiary, :min_student)");

                $insert->bindParam(":second_period_room_id", $second_period_room_id);
            }

            $insert->bindParam(":program_section", $program_section);
            $insert->bindParam(":program_id", $program_id);
            $insert->bindParam(":capacity", $capacity);
            $insert->bindParam(":adviser_teacher_id", $adviser_teacher_id);
            $insert->bindParam(":school_year_term", $selected_term);
            $insert->bindParam(":active", $is_active);
            $insert->bindParam(":is_full", $not_full);
            $insert->bindParam(":course_level", $course_level, PDO::PARAM_INT);
            $insert->bindParam(":is_tertiary", $is_tertiary, PDO::PARAM_INT);
            $insert->bindParam(":min_student", $min_student, PDO::PARAM_INT);
            
            $insert->execute();

            // if(false){
            if($insert->rowCount() > 0){

                $recently_created_course_id = $con->lastInsertId();

                $sectionExec = new Section($con, $recently_created_course_id);
                $section_type = $sectionExec->GetSectionType();

                if($recently_created_course_id != 0
                    && ($first_period_room_id == 0 || $second_period_room_id == 0)){

                    Alert::success("Successfully created1 $program_section section (S.Y $current_school_year_term).",
                        "$back_url");
                    exit();
                }

                if($recently_created_course_id != 0
                    && ($first_period_room_id != 0 || $second_period_room_id != 0)){

                    $room = new Room($con);

                    $room_id = $current_school_year_period == "First" ? $first_period_room_id
                        : ($current_school_year_period == "Second" ? $second_period_room_id : 0);

                    $wasSuccess = $room->RoomTypeUpdate(
                        $room_id, $section_type);

                    if($wasSuccess){
                        Alert::success("Successfully created2 $program_section section (S.Y $current_school_year_term).",
                            "$back_url");
                        exit();
                    }
                }
                
            }
        }

        ?>
            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <div class='col-md-10 offset-md-1'>
                    <div class='card'>
                        
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
    
                                <div class='form-group mb-2'>
                                    <label class='mb-2'>* Minimum Capacity</label>
                                    <input required class='form-control' value="<?php echo $db_min_capacity; ?>" type='number' placeholder='Minimum Capacity' name='min_student'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>* Maximum Capacity</label>
                                    <input required class='form-control' value="30" type='number' placeholder='Room Capacity' name='capacity'>
                                </div>

                                <!-- <div class='form-group mb-2'>
                                    <input class='form-control' type='text' placeholder='Adviser Name' name='adviser_teacher_id'>
                                </div> -->

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Adviser Name</label>

                                    <select class="form-control" name="adviser_teacher_id" id="adviser_teacher_id">
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

                                <!-- <div class='form-group mb-2'>

                                    <label class='mb-2'>* Room for 1st Semester</label>
                                            
                                    <select <?php echo $current_school_year_period == "First" ? "" : "disabled='disabled'"; ?> class="form-control" 
                                            name="first_period_room_id" id="first_period_room_id">
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

                                <div class='form-group mb-2'>

                                    <label class='mb-2'>* Room for 2nd Semester</label>

                                    <select <?php echo $current_school_year_period == "Second" ? "" : "disabled='disabled'"; ?> class="form-control" name="second_period_room_id" id="second_period_room_id">
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