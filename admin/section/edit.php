<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Department.php');
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

    if(isset($_GET['id']) && isset($_GET['p_id'])){

        $course_id = $_GET['id'];
        $program_id = $_GET['p_id'];

        $program = new Program($con, $program_id);

        $department_id = $program->GetProgramDepartmentId();

        $department = new Department($con, $department_id);

        $program_name = $program->GetProgramName();

        $department_name = $department->GetDepartmentName();


        $section = new Section($con, $course_id);

        $db_course_level = $section->GetSectionGradeLevel();
        $db_program_section = $section->GetSectionName();
        $db_room = $section->GetSectionRoom();
        $db_capacity = $section->GetSectionCapacity();
        $db_min_capacity = $section->GetSectionMinimumCapacity();
        $db_advisery_id = $section->GetSectionAdviseryId();
        $db_isFull = $section->GetSectionIsFull();
        $db_isActive = $section->GetSectionIsActive();
 

        $first_period_room_id = $section->GetSectionFirstPeriodRoomId();
        $second_period_room_id = $section->GetSectionSecondPeriodRoomId();


        // echo $first_period_room_id;
        $current_period_room_id = $current_school_year_period == "First" ? $first_period_room_id : 
            ($current_school_year_period == "Second" ? $second_period_room_id : NULL);

        // echo $current_period_room_id;

        // return;

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
        if($department_name === "Senior High School"){
            $back_url = "shs_list.php?id=$program_id&term=$current_school_year_term";

        }else if($department_name === "Tertiary"){
            $back_url = "tertiary_list.php?id=$program_id&term=$current_school_year_term";
        }

        $courseLevelDropdown = $section->
            CreateCourseLevelDropdownDepartmentBased($department_name,
                $db_course_level);


        if(isset($_POST['edit_section_btn_' . $program_id]) &&
            isset($_POST['program_section']) && 
            isset($_POST['program_id']) &&
            isset($_POST['min_student']) &&
            isset($_POST['capacity']) &&
            isset($_POST['is_full']) &&
            isset($_POST['active_status']) &&
            
            // isset($_POST['adviser_teacher_id']) &&
            // isset($_POST['room_id']) &&
            // isset($_POST['room']) &&
            isset($_POST['course_level'])
        ){

            // echo "hiot";
            $is_tertiary = 1;

            $program_section = $_POST['program_section'];
            $program_id = $_POST['program_id'];
            $capacity = $_POST['capacity'];
            $min_student = $_POST['min_student'];
            $is_full = $_POST['is_full'];
            $active_status = $_POST['active_status'];

            // echo $is_full;
            // return;

            $adviser_teacher_id = isset($_POST['adviser_teacher_id']) 
                ? ($_POST['adviser_teacher_id'] == 0 ? NULL : $_POST['adviser_teacher_id']) : NULL;


            $first_period_room_id = isset($_POST['first_period_room_id']) 
                ? ($_POST['first_period_room_id'] == 0 ? NULL : $_POST['first_period_room_id']) : NULL;

            $second_period_room_id = isset($_POST['second_period_room_id']) 
                ? ($_POST['second_period_room_id'] == 0 ? NULL : $_POST['second_period_room_id']) : NULL;

            $course_level = (int) $_POST['course_level'];

            $is_active = "yes";
            $not_full = "no";

            $is_tertiary = ($department_type_section == "Senior High School") ? 0 : 1;

            // if($section->CheckSetionExistsWithinCurrentSY($program_section,
            //     $current_school_year_term) == true){
            //     Alert::error("$program_section already exists within $current_school_year_term term", "add_section.php?id=$program_id&level=$course_level");
            //     exit();
            // }

            // if($current_school_year_period == "First"
            //     && $section->CheckRoomIsTakenCurrentSemester($first_period_room_id,
            //     "first_period_room_id",
            //     $current_school_year_term)){

            //     $url = "shs_list.php?id=$program_id&term=$current_school_year_term";

            //     Alert::error("The1 chosen room already has been taken within semester. Please choose an available one.",
            //         "");
            //     return;
            // }

            // if($current_school_year_period == "Second"
            //     && $section->CheckRoomIsTakenCurrentSemester($second_period_room_id,
            //     "second_period_room_id",
            //     $current_school_year_term)){
                    
            //     $url = "shs_list.php?id=$program_id&term=$current_school_year_term";

            //     Alert::error("The2 chosen room already has been taken within semester. Please choose an available one.",
            //         "");
            //     return;
            // }
                // echo "qqwe";

            // if ($current_school_year_period == "First") {

            //     // echo "qwe";
            //     $update = $con->prepare("UPDATE course 

            //         SET program_section = :program_section,
            //             -- program_id = :program_id,
            //             capacity = :capacity,
            //             adviser_teacher_id = :adviser_teacher_id,
            //             first_period_room_id = :first_period_room_id,
            //             course_level = :course_level,
            //             min_student = :min_student,
            //             is_full = :is_full


            //         WHERE course_id = :course_id");

            //     $update->bindParam(":first_period_room_id", $first_period_room_id);

            // }else if ($current_school_year_period == "Second") {

            //     $update = $con->prepare("UPDATE course SET
            //         program_section = :program_section,
            //         -- program_id = :program_id,
            //         capacity = :capacity,
            //         adviser_teacher_id = :adviser_teacher_id,
            //         second_period_room_id = :second_period_room_id,
            //         course_level = :course_level,
            //         min_student = :min_student,
            //         is_full = :is_full
                    
            //         WHERE course_id = :course_id");

            //     $update->bindParam(":second_period_room_id", $second_period_room_id);

            // }

            $update = $con->prepare("UPDATE course SET
                program_section = :program_section,
                -- program_id = :program_id,
                capacity = :capacity,
                adviser_teacher_id = :adviser_teacher_id,
                -- second_period_room_id = :second_period_room_id,
                course_level = :course_level,
                min_student = :min_student,
                is_full = :is_full,
                active = :active
                
                WHERE course_id = :course_id
            ");

            $update->bindParam(":program_section", $program_section, PDO::PARAM_STR);
            $update->bindParam(":capacity", $capacity, PDO::PARAM_INT);
            $update->bindParam(":adviser_teacher_id", $adviser_teacher_id, PDO::PARAM_INT);
            $update->bindParam(":course_level", $course_level, PDO::PARAM_INT);
            $update->bindParam(":min_student", $min_student, PDO::PARAM_INT);
            $update->bindParam(":course_id", $course_id, PDO::PARAM_INT);
            $update->bindParam(":is_full", $is_full, PDO::PARAM_STR);
            $update->bindParam(":active", $active_status, PDO::PARAM_STR);
            
            $update->execute();

            if($update->rowCount() > 0){

                Alert::success("Section: $program_section has been successfully edited",
                    "$back_url");

                exit();

                $sectionExec = new Section($con, $course_id);
                $section_type = $sectionExec->GetSectionType();
                
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
                            <h4 Edit Section class='text-center mb-3'><?php echo $section->GetSectionName();?> Section Editing  Module</h4>
                        </div>

                        <div class="card-body">
                            <form method='POST'>

                                <!-- <?php echo $trackDropdown;?> -->

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Program</label>

                                    <input style="pointer-events: none;" required class='form-control' 
                                        type='text' 
                                        value="<?php echo $program_name;?>" placeholder='e.g: STEM11-A, ABM11-A' name='program_id'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Section</label>

                                    <input required class='form-control' type='text' 
                                        value="<?php echo $db_program_section;?>" placeholder='e.g: STEM11-A, ABM11-A' name='program_section'>
                                </div>
    
                                <?php echo $courseLevelDropdown;?>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Minimum Capacity</label>
                                    <input required class='form-control' value="<?php echo $db_min_capacity; ?>" type='number' placeholder='Minimum Capacity' name='min_student'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Max Capacity</label>
                                    <input required class='form-control' value="<?php echo $db_capacity; ?>" type='number' placeholder='Maximum Capacity' name='capacity'>
                                </div>
 
                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Advisery Name</label>

                                    <select class="form-control" name="adviser_teacher_id" id="adviser_teacher_id">
                                        <?php
                                            $query = $con->prepare("SELECT * FROM teacher");
                                            $query->execute();

                                            $output = "";
                                            
                                            $output .= "<option value='' disabled selected>Choose Teacher</option>";
                                            // $output .= "<option value='' disabled selected>Choose Teacher</option>";
                                            $output .= "<option value='0'>Clear</option>";


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

                                <div class="form-group">
                                    <label for="active_status">Active Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" <?php echo $db_isActive === "yes" ? "checked" : "" ?> name="active_status" id="active_yes" value="yes">
                                        <label class="form-check-label" for="active_yes">
                                            Active
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" <?php echo $db_isActive === "no" ? "checked" : "" ?> type="radio" name="active_status" id="active_no" value="no">
                                        <label class="form-check-label" for="active_no">
                                            In-active
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="is_full">Capacity Status</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" <?php echo $db_isFull === "yes" ? "checked" : "" ?> name="is_full" id="full" value="yes">
                                        <label class="form-check-label" for="full">
                                            Full
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" <?php echo $db_isFull === "no" ? "checked" : "" ?> type="radio" name="is_full" id="notFull" value="no">
                                        <label class="form-check-label" for="notFull">
                                            Not Full
                                        </label>
                                    </div>
                                </div>



                                <?php
                                
                                    if($current_school_year_period == "First"){
                                        ?>
                                            <!-- <div class='form-group mb-2'>
                                                <label class='mb-2'>* Room for 1st Semester</label>
                                                    
                                                <select <?php echo $current_school_year_period == "First" ? "" : "disabled='disabled'"; ?> class="form-control" 
                                                        name="first_period_room_id" id="first_period_room_id">

                                                    <?php

                                                        $query = $con->prepare("SELECT * FROM room
                                                            -- WHERE room_id=:room_id
                                                            ");

                                                        // $query->bindParam(":room_id", $current_period_room_id);
                                                        $query->execute();
                                                        
                                                        echo "<option value='' disabled selected>Choose Room</option>";
                                                        echo "<option value='0'>Clear</option>";

                                                        if ($query->rowCount() > 0) {
                                                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = "";  
                                                                if($row['room_id'] == $current_period_room_id) $selected = "selected";
                                                                echo "<option value='" . $row['room_id'] . "' $selected>" . $row['room_number'] ."</option>";
                                                            }
                                                        }

                                                    ?>
                                                </select>
                                            </div> -->
                                        <?php
                                    }
                                    
                                    if($current_school_year_period == "Second"){

                                        ?>
                                            <!-- <div class='form-group mb-2'>

                                                <label class='mb-2'>* Room for 2nd Semester</label>

                                                <select <?php echo $current_school_year_period == "Second" ? "" : "disabled='disabled'"; ?> class="form-control" name="second_period_room_id" id="second_period_room_id">
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
                                                
                                            </div> -->
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