<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con);

    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    if(isset($_GET['id']) && isset($_GET['level'])){

        $course_level = $_GET['level'];
        $program_id = $_GET['id'];
        $program = new Program($con, $program_id);

        $SHS = "Senior High School";
        $TERTIARY = "Tertiary";

        $section = new Section($con, null);
        $trackDropdown = $section->createProgramSelection($program_id);

        $courseLevelDropdown = $section->CreateCourseLevelDropdownDepartmentBased($SHS, $course_level);
 

        if(isset($_POST['create_section_btn']) &&
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

            $insert = $con->prepare("INSERT INTO course
                (program_section, program_id, capacity, adviser_teacher_id, room, school_year_term, active, is_full, course_level, is_tertiary)
                VALUES(:program_section, :program_id, :capacity, :adviser_teacher_id, :room, :school_year_term, :active, :is_full, :course_level, :is_tertiary)");
            
            $insert->bindParam(":program_section", $program_section);
            $insert->bindParam(":program_id", $program_id);
            $insert->bindParam(":capacity", $capacity);
            $insert->bindParam(":adviser_teacher_id", $adviser_teacher_id);
            $insert->bindParam(":room", $room);
            $insert->bindParam(":school_year_term", $current_school_year_term);
            $insert->bindParam(":active", $is_active);
            $insert->bindParam(":is_full", $not_full);
            $insert->bindParam(":course_level", $course_level, PDO::PARAM_INT);
            $insert->bindParam(":is_tertiary", $is_tertiary, PDO::PARAM_INT);

            if($insert->execute()){
            

                $recently_created_course_id = $con->lastInsertId();

                $sectionObj = new Section($con, $recently_created_course_id);
                
                $created_program_section = $sectionObj->GetSectionName();

                $get_program_section = $section->GetSectionName();

                if($current_school_year_period == "First"){

                    $get_subject_program = $con->prepare("SELECT * 
                    
                        FROM subject_program

                        WHERE program_id=:program_id
                        AND course_level=:course_level
                        ");

                    # Second Semester Subjects only,
                    # None usage of First Semester subject here.
                    
                    $get_subject_program->bindParam(":program_id", $program_id);
                    $get_subject_program->bindParam(":course_level", $course_level);
                    // $get_subject_program->bindParam(":semester", $current_school_year_period);
                    $get_subject_program->execute();

                    if($get_subject_program->rowCount() > 0){

                        $isSubjectCreated = false;

                        $insert_section_subject = $con->prepare("INSERT INTO subject
                            (subject_title, description, subject_program_id, unit, semester,
                                program_id, course_level, course_id, subject_type, subject_code,
                                pre_requisite)
                            VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, 
                                :program_id, :course_level, :course_id, :subject_type, :subject_code,
                                :pre_requisite)");

                        while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

                            $program_program_id = $row['subject_program_id'];
                            $program_course_level = $row['course_level'];
                            $program_semester = $row['semester'];
                            $program_subject_type = $row['subject_type'];
                            $program_subject_title = $row['subject_title'];
                            $program_subject_description = $row['description'];
                            $program_subject_unit = $row['unit'];
                            $program_subject_pre_requisite = $row['pre_req_subject_title'];

                            $program_subject_code = $row['subject_code'] . "-". $program_section; 
                            // $program_subject_code = $row['subject_code']; 

                            $insert_section_subject->bindValue(":subject_title", $program_subject_title);
                            $insert_section_subject->bindValue(":description", $program_subject_description);
                            $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
                            $insert_section_subject->bindValue(":unit", $program_subject_unit);
                            $insert_section_subject->bindValue(":semester", $program_semester);
                            $insert_section_subject->bindValue(":program_id", $program_id);
                            $insert_section_subject->bindValue(":course_level", $program_course_level);
                            $insert_section_subject->bindValue(":course_id", $recently_created_course_id);
                            $insert_section_subject->bindValue(":subject_type", $program_subject_type);
                            $insert_section_subject->bindValue(":subject_code", $program_subject_code);
                            $insert_section_subject->bindValue(":pre_requisite", $program_subject_pre_requisite);

                            // $insert_section_subject->execute();
                            if($insert_section_subject->execute()){
                                $isSubjectCreated = true;
                            }
                        }

                        if($isSubjectCreated){

                            Alert::success("Successfully created $program_section section (S.Y $current_school_year_term).", "shs_list.php?id=$program_id&term=$current_school_year_term");
                            exit();
                        }

                    }
                }

                else if($current_school_year_period == "Second"){

                    $get_subject_program = $con->prepare("SELECT * 
                    
                        FROM subject_program

                        WHERE program_id=:program_id
                        AND course_level=:course_level
                        AND semester=:semester
                        ");

                    # Second Semester Subjects only,
                    # None usage of First Semester subject here.
                    
                    $get_subject_program->bindParam(":program_id", $program_id);
                    $get_subject_program->bindParam(":course_level", $course_level);
                    $get_subject_program->bindParam(":semester", $current_school_year_period);
                    $get_subject_program->execute();

                    if($get_subject_program->rowCount() > 0){

                        $isSubjectCreated = false;

                        $insert_section_subject = $con->prepare("INSERT INTO subject
                            (subject_title, description, subject_program_id, unit, semester,
                                program_id, course_level, course_id, subject_type, subject_code,
                                pre_requisite)
                            VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, 
                                :program_id, :course_level, :course_id, :subject_type, :subject_code,
                                :pre_requisite)");

                        while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

                            $program_program_id = $row['subject_program_id'];
                            $program_course_level = $row['course_level'];
                            $program_semester = $row['semester'];
                            $program_subject_type = $row['subject_type'];
                            $program_subject_title = $row['subject_title'];
                            $program_subject_description = $row['description'];
                            $program_subject_unit = $row['unit'];
                            $program_subject_pre_requisite = $row['pre_req_subject_title'];

                            $program_subject_code = $row['subject_code'] . "-". $program_section; 
                            // $program_subject_code = $row['subject_code']; 

                            $insert_section_subject->bindValue(":subject_title", $program_subject_title);
                            $insert_section_subject->bindValue(":description", $program_subject_description);
                            $insert_section_subject->bindValue(":subject_program_id", $program_program_id);
                            $insert_section_subject->bindValue(":unit", $program_subject_unit);
                            $insert_section_subject->bindValue(":semester", $program_semester);
                            $insert_section_subject->bindValue(":program_id", $program_id);
                            $insert_section_subject->bindValue(":course_level", $program_course_level);
                            $insert_section_subject->bindValue(":course_id", $recently_created_course_id);
                            $insert_section_subject->bindValue(":subject_type", $program_subject_type);
                            $insert_section_subject->bindValue(":subject_code", $program_subject_code);
                            $insert_section_subject->bindValue(":pre_requisite", $program_subject_pre_requisite);

                            // $insert_section_subject->execute();
                            if($insert_section_subject->execute()){
                                $isSubjectCreated = true;
                            }
                        }

                        if($isSubjectCreated){

                            Alert::success("Successfully created $program_section section (S.Y $current_school_year_term).", "shs_list.php?id=$program_id&term=$current_school_year_term");
                            exit();
                        }

                    }
                }
                
            }

            // ADD CHECK EVERY SCENARIO of 1st sem, 2nd sem.

            # 1st Sem -> 1st and 2nd sem subjects
            # 2nd Sem -> 2nd sem subjects (Increase student scenario, so it needed to create new section).



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

            // # CONTROLLER.
            // #                               | POPULATE |
            // #  GRADE 11 Section Subjects -> 1st && 2nd Semester Subjects
            // #  GRADE 12 Section Subjects -> 2nd Semester Subjects

            // # Check if Section Name is already exists in the DB.

            // if($section->CheckSetionExistsWithinCurrentSY($program_section,
            //     $current_school_year_term) == true){
            //     Alert::error("$program_section already exists within $current_school_year_term term", "create.php");
            //     exit();
            // }

            // $insert = $con->prepare("INSERT INTO course
            //     (program_section, program_id, capacity, adviser_teacher_id, room, school_year_term, active, is_full, course_level, is_tertiary)
            //     VALUES(:program_section, :program_id, :capacity, :adviser_teacher_id, :room, :school_year_term, :active, :is_full, :course_level, :is_tertiary)");
            
            // $insert->bindValue(":program_section", $program_section);
            // $insert->bindValue(":program_id", $program_id);
            // $insert->bindValue(":capacity", $capacity);
            // $insert->bindValue(":adviser_teacher_id", $adviser_teacher_id);
            // $insert->bindValue(":room", $room);
            // $insert->bindValue(":school_year_term", $current_school_year_term);
            // $insert->bindValue(":active", $is_active);
            // $insert->bindValue(":is_full", $not_full);
            // $insert->bindValue(":course_level", $course_level, PDO::PARAM_INT);
            // $insert->bindValue(":is_tertiary", $is_tertiary, PDO::PARAM_INT);

            // if($insert->execute()){

            // // if(false){

            //     $recently_created_course_id = $con->lastInsertId();

            //     $sectionObj = new Section($con, $recently_created_course_id);
                
            //     $created_program_section = $sectionObj->GetSectionName();

            //     $get_program_section = $section->GetSectionNameByCourseId($recently_created_course_id);

            //     if($current_school_year_period == "First" 
            //         && $course_level == 11){

            //         $get_subject_program = $con->prepare("SELECT * FROM subject_program
            //             WHERE program_id=:program_id
            //             AND course_level=:course_level
            //             -- AND semester=:semester
            //             ");

            //         # Second Semester Subjects only,
            //         # None usage of First Semester subject here.
                    
            //         $get_subject_program->bindValue(":program_id", $program_id);
            //         $get_subject_program->bindValue(":course_level", $course_level);
            //         // $get_subject_program->bindValue(":semester", $current_school_year_period);
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

            //                 $program_subject_code = $row['subject_code'] . "-". $get_program_section; 
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

            //             if($isSubjectCreated == true){

            //                 if(isset($_SESSION['process_enrollment'])
            //                     && $_SESSION['process_enrollment'] == 'transferee'){

            //                         Alert::success("New section: $created_program_section has been created.", "../admission/transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id");
            //                         exit();

            //                 }else if(isset($_SESSION['process_enrollment'])
            //                     && $_SESSION['process_enrollment'] == 'non_transferee'){

            //                         Alert::success("New section: $created_program_section has been created.", "../admission/process_enrollment.php?step2=true&id=$pending_enrollees_id");
            //                         exit();
            //                 }
            //                 else{
            //                     Alert::success("New section: $created_program_section has been created.", "index.php");

            //                 }
            //             }
            //         }

            //     }
                
            //     else if($current_school_year_period == "Second" 
            //         && $course_level == 11){

            //         $get_subject_program = $con->prepare("SELECT * FROM subject_program
            //             WHERE program_id=:program_id
            //             AND semester=:semester
            //             AND course_level=:course_level
            //             ");

            //         # Second Semester Subjects only,
            //         # None usage of First Semester subject here.
                    
            //         $get_subject_program->bindValue(":program_id", $program_id);
            //         $get_subject_program->bindValue(":course_level", $course_level);
            //         $get_subject_program->bindValue(":semester", $current_school_year_period);
            //         $get_subject_program->execute();

            //         if($get_subject_program->rowCount() > 0){

            //             $isSubjectCreated = false;

            //             $insert_section_subject = $con->prepare("INSERT INTO subject
            //                 (subject_title, description, subject_program_id, unit, semester, program_id, course_level, course_id, subject_type, subject_code)
            //                 VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, :program_id, :course_level, :course_id, :subject_type, :subject_code)");

            //             while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

            //                 $program_program_id = $row['subject_program_id'];
            //                 $program_course_level = $row['course_level'];
            //                 $program_semester = $row['semester'];
            //                 $program_subject_type = $row['subject_type'];
            //                 $program_subject_title = $row['subject_title'];
            //                 $program_subject_description = $row['description'];
            //                 $program_subject_unit = $row['unit'];

            //                 $program_subject_code = $row['subject_code'] . "-". $get_program_section; 
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

            //                 // $insert_section_subject->execute();
            //                 if($insert_section_subject->execute()){
            //                     $isSubjectCreated = true;
            //                 }
            //             }

            //             if($isSubjectCreated == true){


            //                 if(isset($_SESSION['process_enrollment'])
            //                     && $_SESSION['process_enrollment'] == 'transferee'){

            //                         Alert::success("New section: $created_program_section has been created.", "../admission/transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id");
            //                         exit();

            //                 }else if(isset($_SESSION['process_enrollment'])
            //                     && $_SESSION['process_enrollment'] == 'non_transferee'){

            //                         Alert::success("New section: $created_program_section has been created.", "../admission/process_enrollment.php?step2=true&id=$pending_enrollees_id");
            //                         exit();
            //                 }
            //                 else{
            //                     Alert::success("New section: $created_program_section has been created.", "index.php");
            //                 }
            //             }
            //         }

            //     }

            //     else if($current_school_year_period == "First" 
            //         && $course_level == 12){

            //         $get_subject_program = $con->prepare("SELECT * FROM subject_program
            //             WHERE program_id=:program_id
            //             -- AND semester=:semester
            //             AND course_level=:course_level
            //             ");

            //         # Second Semester Subjects only,
            //         # None usage of First Semester subject here.
                    
            //         $get_subject_program->bindValue(":program_id", $program_id);
            //         // $get_subject_program->bindValue(":semester", $current_school_year_period);
            //         $get_subject_program->bindValue(":course_level", $course_level);
            //         $get_subject_program->execute();

            //         if($get_subject_program->rowCount() > 0){

            //             $isSubjectCreated = false;

            //             $insert_section_subject = $con->prepare("INSERT INTO subject
            //                 (subject_title, description, subject_program_id, unit, semester, program_id, course_level, course_id, subject_type, subject_code)
            //                 VALUES(:subject_title, :description, :subject_program_id, :unit, :semester, :program_id, :course_level, :course_id, :subject_type, :subject_code)");

            //             while($row = $get_subject_program->fetch(PDO::FETCH_ASSOC)){

            //                 $program_program_id = $row['subject_program_id'];
            //                 $program_course_level = $row['course_level'];
            //                 $program_semester = $row['semester'];
            //                 $program_subject_type = $row['subject_type'];
            //                 $program_subject_title = $row['subject_title'];
            //                 $program_subject_description = $row['description'];
            //                 $program_subject_unit = $row['unit'];

            //                 $program_subject_code = $row['subject_code'] . "-". $get_program_section; 
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

            //                 // $insert_section_subject->execute();
            //                 if($insert_section_subject->execute()){
            //                     $isSubjectCreated = true;
            //                 }
            //             }

            //             if($isSubjectCreated == true){


            //                 if(isset($_SESSION['process_enrollment'])
            //                     && $_SESSION['process_enrollment'] == 'transferee'){

            //                         Alert::success("New section: $created_program_section has been created.", "../admission/transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id");
            //                         exit();

            //                 }else if(isset($_SESSION['process_enrollment'])
            //                     && $_SESSION['process_enrollment'] == 'non_transferee'){

            //                         Alert::success("New section: $created_program_section has been created.", "../admission/process_enrollment.php?step2=true&id=$pending_enrollees_id");
            //                         exit();
            //                 }
            //                 else{
            //                     Alert::success("New section: $created_program_section has been created.", "index.php");

            //                 }
            //             }
            //         }
            //     }

            //     if($current_school_year_period == "First" 
            //         && $course_level == 1){
            //         $get_subject_program = $con->prepare("SELECT * FROM subject_program
            //             WHERE program_id=:program_id
            //             AND course_level=:course_level
            //             -- AND semester=:semester
            //             ");

            //         # Second Semester Subjects only,
            //         # None usage of First Semester subject here.
                    
            //         $get_subject_program->bindValue(":program_id", $program_id);
            //         $get_subject_program->bindValue(":course_level", $course_level);
            //         // $get_subject_program->bindValue(":semester", $current_school_year_period);
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

            //                 $program_subject_code = $row['subject_code'] . "-". $get_program_section; 
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

            //             if($isSubjectCreated == true){


            //                 if(isset($_SESSION['process_enrollment'])
            //                     && $_SESSION['process_enrollment'] == 'transferee'){

            //                         Alert::success("New section: $created_program_section has been created.", "../admission/transferee_process_enrollment.php?step2=true&id=$pending_enrollees_id");
            //                         exit();

            //                 }else if(isset($_SESSION['process_enrollment'])
            //                     && $_SESSION['process_enrollment'] == 'non_transferee'){

            //                         Alert::success("New section: $created_program_section has been created.", "../admission/process_enrollment.php?step2=true&id=$pending_enrollees_id");
            //                         exit();
            //                 }
            //                 else{
            //                     Alert::success("New section: $created_program_section has been created.", "index.php");

            //                 }
            //             }
            //         }
            //     }
            // }

        }
        
        ?>
            <div class='col-md-10 row offset-md-1'>
                
                <div class='card'>
                    <hr>
                    <a href="shs_list.php?id=<?php echo $program_id;?>&term=<?php echo $current_school_year_term;?>">
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

                                <input class='form-control' type='text' value="<?php echo $program->GetProgramSectionName();?><?php echo $course_level;?>-" placeholder='e.g: STEM11-A, ABM11-A' name='program_section'>
                            </div>
 
                            <?php echo $courseLevelDropdown;?>

                            <div class='form-group mb-2'>
                                <label class='mb-2'>Capacity</label>
                                <input class='form-control' value="30" type='number' placeholder='Room Capacity' name='capacity'>
                            </div>

                            <!-- <div class='form-group mb-2'>
                                <input class='form-control' type='text' placeholder='Adviser Name' name='adviser_teacher_id'>
                            </div> -->

                            <div class='form-group mb-2'>
                                <label class='mb-2'>Instructor</label>

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

                            <div class='form-group mb-2'>
                                <label class='mb-2'>Room</label>
                                <input class='form-control' type='number' placeholder='Room' name='room'>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='create_section_btn'>Save Section</button>
                            </div>


                        </form>
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