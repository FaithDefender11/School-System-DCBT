<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con);

    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

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
            isset($_POST['room']) &&
            isset($_POST['course_level'])
        ){

            $is_tertiary = 1;

            $program_section = $_POST['program_section'];
            $program_id = $_POST['program_id'];
            $capacity = $_POST['capacity'];
            $adviser_teacher_id = $_POST['adviser_teacher_id'];
            $room = $_POST['room'];
            $course_level = (int) $_POST['course_level'];

            $is_active = "yes";
            $not_full = "no";

            // $sql = $con->prepare("SELECT t2.department_name FROM program as t1

            //     INNER JOIN department as t2 ON t2.department_id = t1.department_id 
            //     WHERE t1.program_id=:program_id
            //     LIMIT 1");

            // $sql->bindValue(":program_id", $program_id);
            // $sql->execute();

            // if($sql->rowCount() > 0){

            //     $department_name = $sql->fetchColumn();
            //     $is_tertiary = ($department_name == "Senior High School") ? 0 : 1;
            // }

            $is_tertiary = ($department_type_section == "Senior High School") ? 0 : 1;

            // echo $is_tertiary;

            // if($section->CheckSetionExistsWithinCurrentSY($program_section,
            //     $current_school_year_term) == true){
            //     Alert::error("$program_section already exists within $current_school_year_term term", "add_section.php?id=$program_id&level=$course_level");
            //     exit();
            // }

            $update = $con->prepare("UPDATE course SET
                program_section = :program_section,
                program_id = :program_id,
                capacity = :capacity,
                adviser_teacher_id = :adviser_teacher_id,
                room = :room,
                course_level = :course_level
                WHERE course_id = :course_id");

            $update->bindParam(":program_section", $program_section);
            $update->bindParam(":program_id", $program_id);
            $update->bindParam(":capacity", $capacity);
            $update->bindParam(":adviser_teacher_id", $adviser_teacher_id);
            $update->bindParam(":room", $room);
            $update->bindParam(":course_level", $course_level, PDO::PARAM_INT);
            $update->bindParam(":course_id", $course_id);

            if($update->execute()){
            
                // Update all subjects that are linked with course_id

                $get_subjects_linked = $con->prepare("SELECT * 
                    
                    FROM subject

                    WHERE course_id=:course_id
                    ");

                $get_subjects_linked->bindParam(":course_id", $course_id);
                $get_subjects_linked->execute();

                if($get_subjects_linked->rowCount() > 0){

                    $isSuccess = false;
                    $str = "";
                    $i = 0;

                    $update_subject_code = $con->prepare("UPDATE subject 
                        SET subject_code = :subject_code
                        WHERE course_id = :course_id
                        AND subject_program_id = :subject_program_id
                        ");

                    while($row = $get_subjects_linked->fetch(PDO::FETCH_ASSOC)){

                        $subject_code = $row['subject_code'];
                        $subject_program_id = $row['subject_program_id'];

                        $subject_program = new SubjectProgram($con, $subject_program_id);

                        $sp_subject_code = $subject_program->GetSubjectProgramRawCode();
                        $sp_id = $subject_program->GetSubjectProgramId();

                        // BUILD - PE101-STEM11-EX
                        $build = $sp_subject_code . "-". $program_section;

                        // $str .= $sp_subject_code . " ";
                        $str .= $build . " ";

                        // $substring = substr($subject_code, strpos($subject_code, '-') + 1);

                        // Replace the $substring into $program_section
                        // $newString = str_replace($substring, $program_section, $subject_code);
                        
                        // UPDATE

                        $update_subject_code->bindParam(":subject_code", $build);
                        $update_subject_code->bindParam(":subject_program_id", $sp_id);
                        $update_subject_code->bindParam(":course_id", $course_id);

                        if($update_subject_code->execute()){

                            $i++;
                            $isSuccess = true;
                        }

                    }

                    // if($i == 1){
                    //     echo $i;
                    // } 
                    if($isSuccess === true){
                        // echo $str;
                        Alert::success("Successfully Edited: $program_section section and its Subject Code has been aligned.",
                                "$back_url");
                        exit();
                    }
                }
                

                
            }

            // $insert = $con->prepare("INSERT INTO course
            //     (program_section, program_id, capacity, adviser_teacher_id, room, school_year_term, active, is_full, course_level, is_tertiary)
            //     VALUES(:program_section, :program_id, :capacity, :adviser_teacher_id, :room, :school_year_term, :active, :is_full, :course_level, :is_tertiary)");
            
            // $insert->bindParam(":program_section", $program_section);
            // $insert->bindParam(":program_id", $program_id);
            // $insert->bindParam(":capacity", $capacity);
            // $insert->bindParam(":adviser_teacher_id", $adviser_teacher_id);
            // $insert->bindParam(":room", $room);
            // $insert->bindParam(":school_year_term", $current_school_year_term);
            // $insert->bindParam(":active", $is_active);
            // $insert->bindParam(":is_full", $not_full);
            // $insert->bindParam(":course_level", $course_level, PDO::PARAM_INT);
            // $insert->bindParam(":is_tertiary", $is_tertiary, PDO::PARAM_INT);

            // if($insert->execute()){
            
            //     $recently_created_course_id = $con->lastInsertId();

            //     $sectionObj = new Section($con, $recently_created_course_id);
                
            //     $created_program_section = $sectionObj->GetSectionName();

            //     $get_program_section = $section->GetSectionName();

            //     if($current_school_year_period == "First"){

            //         $get_subject_program = $con->prepare("SELECT * 
                    
            //             FROM subject_program

            //             WHERE program_id=:program_id
            //             AND course_level=:course_level
            //             ");

            //         # Second Semester Subjects only,
            //         # None usage of First Semester subject here.
                    
            //         $get_subject_program->bindParam(":program_id", $program_id);
            //         $get_subject_program->bindParam(":course_level", $course_level);
            //         // $get_subject_program->bindParam(":semester", $current_school_year_period);
            //         $get_subject_program->execute();

            //         if($get_subject_program->rowCount() > 0){

            //             $isSubjectCreated = false;

            //             $insert_section_subject = $con->prepare("INSERT INTO subject
            //                 (subject_title, description, subject_program_id, unit, semester,
            //                     program_id, course_level, course_id, subject_type, subject_code,
            //                     pre_requisite)
            //                 VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, 
            //                     :program_id, :course_level, :course_id, :subject_type, :subject_code,
            //                     :pre_requisite)");

            //             while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

            //                 $program_program_id = $row['subject_program_id'];
            //                 $program_course_level = $row['course_level'];
            //                 $program_semester = $row['semester'];
            //                 $program_subject_type = $row['subject_type'];
            //                 $program_subject_title = $row['subject_title'];
            //                 $program_subject_description = $row['description'];
            //                 $program_subject_unit = $row['unit'];
            //                 $program_subject_pre_requisite = $row['pre_req_subject_title'];

            //                 $program_subject_code = $row['subject_code'] . "-". $program_section; 
            //                 // $program_subject_code = $row['subject_code']; 

            //                 $insert_section_subject->bindValue(":subject_title", $program_subject_title);
            //                 $insert_section_subject->bindValue(":description", $program_subject_description);
            //                 $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
            //                 $insert_section_subject->bindValue(":unit", $program_subject_unit);
            //                 $insert_section_subject->bindValue(":semester", $program_semester);
            //                 $insert_section_subject->bindValue(":program_id", $program_id);
            //                 $insert_section_subject->bindValue(":course_level", $program_course_level);
            //                 $insert_section_subject->bindValue(":course_id", $recently_created_course_id);
            //                 $insert_section_subject->bindValue(":subject_type", $program_subject_type);
            //                 $insert_section_subject->bindValue(":subject_code", $program_subject_code);
            //                 $insert_section_subject->bindValue(":pre_requisite", $program_subject_pre_requisite);

            //                 // $insert_section_subject->execute();
            //                 if($insert_section_subject->execute()){
            //                     $isSubjectCreated = true;
            //                 }
            //             }

            //             if($isSubjectCreated){

            //                 Alert::success("Successfully created $program_section section (S.Y $current_school_year_term).",
            //                     "$back_url");

            //                 exit();
            //             }

            //         }else{
            //             Alert::error("It seems section subjects is not populated properly.", "shs_list.php?id=$program_id&term=$current_school_year_term");
            //             exit();
            //         }
            //     }

            //     else if($current_school_year_period == "Second"){

            //         $get_subject_program = $con->prepare("SELECT * 
                    
            //             FROM subject_program

            //             WHERE program_id=:program_id
            //             AND course_level=:course_level
            //             AND semester=:semester
            //             ");

            //         # Second Semester Subjects only,
            //         # None usage of First Semester subject here.
                    
            //         $get_subject_program->bindParam(":program_id", $program_id);
            //         $get_subject_program->bindParam(":course_level", $course_level);
            //         $get_subject_program->bindParam(":semester", $current_school_year_period);
            //         $get_subject_program->execute();

            //         if($get_subject_program->rowCount() > 0){

            //             $isSubjectCreated = false;

            //             $insert_section_subject = $con->prepare("INSERT INTO subject
            //                 (subject_title, description, subject_program_id, unit, semester,
            //                     program_id, course_level, course_id, subject_type, subject_code,
            //                     pre_requisite)
            //                 VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, 
            //                     :program_id, :course_level, :course_id, :subject_type, :subject_code,
            //                     :pre_requisite)");

            //             while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

            //                 $program_program_id = $row['subject_program_id'];
            //                 $program_course_level = $row['course_level'];
            //                 $program_semester = $row['semester'];
            //                 $program_subject_type = $row['subject_type'];
            //                 $program_subject_title = $row['subject_title'];
            //                 $program_subject_description = $row['description'];
            //                 $program_subject_unit = $row['unit'];
            //                 $program_subject_pre_requisite = $row['pre_req_subject_title'];

            //                 $program_subject_code = $row['subject_code'] . "-". $program_section; 
            //                 // $program_subject_code = $row['subject_code']; 

            //                 $insert_section_subject->bindValue(":subject_title", $program_subject_title);
            //                 $insert_section_subject->bindValue(":description", $program_subject_description);
            //                 $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
            //                 $insert_section_subject->bindValue(":unit", $program_subject_unit);
            //                 $insert_section_subject->bindValue(":semester", $program_semester);
            //                 $insert_section_subject->bindValue(":program_id", $program_id);
            //                 $insert_section_subject->bindValue(":course_level", $program_course_level);
            //                 $insert_section_subject->bindValue(":course_id", $recently_created_course_id);
            //                 $insert_section_subject->bindValue(":subject_type", $program_subject_type);
            //                 $insert_section_subject->bindValue(":subject_code", $program_subject_code);
            //                 $insert_section_subject->bindValue(":pre_requisite", $program_subject_pre_requisite);

            //                 // $insert_section_subject->execute();
            //                 if($insert_section_subject->execute()){
            //                     $isSubjectCreated = true;
            //                 }
            //             }

            //             if($isSubjectCreated){

            //                 Alert::success("Successfully created $program_section section (S.Y $current_school_year_term).", "shs_list.php?id=$program_id&term=$current_school_year_term");
            //                 exit();
            //             }

            //         }
            //     }
                
            // }

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
                                            
                                            echo "<option value='' disabled selected>Choose Teacher</option>";

                                            if ($query->rowCount() > 0) {
                                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = "";  

                                                    // Add condition to check if the option should be selected
                                                    if ($row['teacher_id'] == $db_advisery_id) {
                                                        $selected = "selected";
                                                    }
                                                    echo "<option value='" . $row['teacher_id'] . "' $selected>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Room</label>
                                    <input required class='form-control' value="<?php echo $db_room;?>" type='number' placeholder='Room' name='room'>
                                </div>

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