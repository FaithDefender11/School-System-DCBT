<?php 

    $c_id = $_GET['c_id'];

    $checkSectionAlreadyAssigned = $student_subject->
        CheckStudentSectionSubjectAssignedWithinSY($student_enrollment_id,
            $student_enrollment_course_id, $student_id, $current_school_year_id);

    if(isset($_POST['student_choose_section'])
        && isset($_POST['find_selected_course_id'])){

        $chosen_course_id = intval($_POST['find_selected_course_id']);

        // And If Student chosen_course_id has data in Student_Subject 
        // along with School Year ID, should REDIRECT only to the page.
        // Else, it means, that student was enrolled in the prev S.Y
        // and in todays S.Y should be addadble in the Student_Subject

        if(false){
        // if($student_enrollment_course_id == $chosen_course_id){

            // Should not do anything, Just direct the next page.
            // echo "direct";
            if($checkSectionAlreadyAssigned == true
                || ($checkSectionAlreadyAssigned == false 
                    && $student_enrollment_course_id != 0)){
                        
                header("Location: process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$chosen_course_id");
                exit();
            }

            if($checkSectionAlreadyAssigned == false 
                ){

                $wasStudentSubjectPopulated = $student_subject
                    ->AddNonFinalDefaultEnrolledSubject($student_id, 
                    $student_enrollment_id, $chosen_course_id, $current_school_year_id,
                    $current_school_year_period,  $admission_status);
                
                $section = new Section($con, $chosen_course_id);
                $chosen_section_name = $section->GetSectionName();

                if($wasStudentSubjectPopulated){
                    Alert::success("Successfully selects section: $chosen_section_name.", "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$chosen_course_id");
                    exit();
                }
            }
        }
        else if(false){
        // else if($student_enrollment_course_id != $chosen_course_id){

            // FOR REMOVING.

            // If new student, means, recently came-up from pending table
            //  should remove the student_table, enrollment form, student_subject, parent
            // and pending student_status should be back in blank state

            $change_enrollment_course_id_success = $enrollment->ChangeEnrollmentCourseId($current_school_year_id,
                $student_id, $student_enrollment_form_id, $student_enrollment_course_id,
                $chosen_course_id);
            
            if($change_enrollment_course_id_success){

                // Regular In the Previous Semester
                // The subjects offered in todays semester should be all populated.


                $update_student_subject_success = $student_subject->UpdateStudentSubjectCourseId($student_id, $student_enrollment_course_id,
                        $chosen_course_id, $student_enrollment_id, $current_school_year_id,
                        $current_school_year_period);

                if($update_student_subject_success){

                    Alert::success("Successfully section changed.", "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$chosen_course_id");
                    exit();

                }else{
                    Alert::success("Successfully find a section.", "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$chosen_course_id");
                    exit();
                }
            }
        }

        // Default state student_enrollment_course_id = 0

        // echo $chosen_course_id;

        // $wasStudentSubjectPopulated = $student_subject
        //             ->AddNonFinalDefaultEnrolledSubject($student_id, 
        //             $student_enrollment_id, $chosen_course_id, $current_school_year_id,
        //             $current_school_year_period,  $admission_status);

        
        if($student_enrollment_course_id != 0 && 
            $student_enrollment_course_id == $chosen_course_id){

            if($checkSectionAlreadyAssigned == true
                || $checkSectionAlreadyAssigned == false){
                        
                header("Location: process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$chosen_course_id");
                exit();
            }
        }

        if($student_enrollment_course_id == 0 && 
            $student_enrollment_course_id != $chosen_course_id){

            $change_enrollment_course_id_success = $enrollment->ChangeEnrollmentCourseId($current_school_year_id,
                $student_id, $student_enrollment_form_id, $student_enrollment_course_id,
                $chosen_course_id);
            
            if($change_enrollment_course_id_success){

                // Populated the selected $chosen_course_id
                $wasStudentSubjectPopulated = $student_subject
                    ->AddNonFinalDefaultEnrolledSubject($student_id, 
                    $student_enrollment_id, $chosen_course_id, $current_school_year_id,
                    $current_school_year_period,  $admission_status);
                
                $section = new Section($con, $chosen_course_id);
                $chosen_section_name = $section->GetSectionName();

                if($wasStudentSubjectPopulated){
                    Alert::success("Successfully selects section: $chosen_section_name.", "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$chosen_course_id");
                    exit();
                }
            }
        }

        if($student_enrollment_course_id != 0 && 
            $student_enrollment_course_id != $chosen_course_id){

            $change_enrollment_course_id_success = $enrollment->ChangeEnrollmentCourseId($current_school_year_id,
                $student_id, $student_enrollment_form_id, $student_enrollment_course_id,
                $chosen_course_id);
            
            if($change_enrollment_course_id_success){

                // Regular In the Previous Semester
                // The subjects offered in todays semester should be all populated.

                $update_student_subject_success = $student_subject->UpdateStudentSubjectCourseId($student_id, $student_enrollment_course_id,
                        $chosen_course_id, $student_enrollment_id, $current_school_year_id,
                        $current_school_year_period);

                if($update_student_subject_success){

                    Alert::success("Successfully section changed.", "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$chosen_course_id");
                    exit();

                }
                // else{
                //     Alert::success("Successfully find a section.", "process_enrollment.php?subject_review=show&st_id=$student_id&selected_course_id=$chosen_course_id");
                //     exit();
                // }
            }
        }



    }

    ?>
        <div class="content">
            
            <div class="content-header">
                    <?php echo Helper::RevealStudentTypePending($type); ?>
                <header>
                <div class="title">
                    <h1>Enrollment form</h1>
                </div>
                <div class="action">
                    <div class="dropdown">
                    <button class="icon">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item" style="color: red"
                        ><i class="bi bi-file-earmark-x"></i>Delete form</a
                        >
                    </div>
                    </div>
                </div>
                </header>

                <?php echo Helper::ProcessStudentCards($student_id, $student_enrollment_form_id,
                    $student_unique_id, $enrollment_creation, $student_new_enrollee,
                    $student_enrollment_is_new, $student_enrollment_is_transferee, $student_status_st); ?>
            </div>

            <main>
                <div class="progress">
                    <span class="dot active"><p>Check form details</p></span>
                    <span class="line active"></span>
                    <span class="dot active"><p>Find section</p></span>
                    <span class="line inactive"></span>
                    <span class="dot inactive"><p>Subject confirmation</p></span>
                </div>

                <div class="floating">

                    <header>
                        <div class="title">
                            <h3>Enrollment Details</h3>
                        </div>

                    </header>

                    <main>
                        <form method="POST">
                            <div class="row">

                                <span>
                                    <label for="sy">S.Y.</label>
                                    <div>
                                        <input readonly class="text-center" type="text" name="sy" id="sy" value="<?php echo $current_school_year_term; ?>" />
                                    </div>
                                </span>

                                <!-- YYY -->
                                <?php
                                
                                    if($type == "Tertiary"){
                                        ?>
                                            <span>
                                                <label label for="track">Track</label>

                                                <div>
                                                    <select style="pointer-events: none;" id="inputTrack" class="form-select">
                                                        <?php 

                                                            // $SHS_DEPARTMENT = 4;


                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                                program_id, track, acronym 
                                                                
                                                                FROM program 

                                                                WHERE department_id !=:department_id
                                                                GROUP BY track
                                                            ");

                                                            $track_sql->bindValue(":department_id", $tertiary_department_id);
                                                            $track_sql->execute();
                                                            
                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $track = $row['track'];

                                                                $selected = ($row_program_id == $tertiary_department_id) ? "selected" : "";

                                                                echo "<option class='text-center' value='$row_program_id' $selected>$track</option>";
                                                            }
                                                        ?>

                                                    </select>
                                                </div>
                                            </span>

                                            <span>
                                                <label for="strand">Strand</label>

                                                <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                    name="strand" id="strand" class="form-select">
                                                    <?php 

                                                        $SHS_DEPARTMENT = 4;
                                                    
                                                        $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                            
                                                            FROM program 
                                                            WHERE department_id !=:department_id
                                                            GROUP BY acronym
                                                        ");

                                                        $track_sql->bindValue(":department_id", $department_id);
                                                        $track_sql->execute();

                                                        while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                            $row_program_id = $row['program_id'];

                                                            $acronym = $row['acronym'];

                                                            $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                            echo "<option class='text-center' value='$row_program_id' $selected>$acronym</option>";
                                                        }
                                                    ?>

                                                </select>
                                            </span>
                                        <?php
                                    }
                                    else if($type == "SHS"){

                                        ?>
                                            <span>
                                                <label label for="track">Track</label>
                                                <div>
                                                    <select style="pointer-events: none;" id="inputTrack" class="form-select">
                                                        <?php 
                                                            $SHS_DEPARTMENT = 4;

                                                            // echo $department_id;
                                                        
                                                            $track_sql = $con->prepare("SELECT 
                                                                program_id, track, acronym 
                                                                
                                                                FROM program 

                                                                WHERE department_id =:department_id
                                                                GROUP BY track
                                                            ");

                                                            $track_sql->bindValue(":department_id", $department_id);
                                                            $track_sql->execute();

                                                            while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                $row_program_id = $row['program_id'];

                                                                $track = $row['track'];

                                                                $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                echo "<option value='$row_program_id' $selected>$track</option>";
                                                            }
                                                        ?>
                                                        
                                                    </select>
                                                </div>
                                            </span>

                                            <span>
                                                <label for="strand">Strandx</label>
                                                <select onchange="chooseStrand(this, <?php echo $student_id;?>)" 
                                                    name="strand" id="strand" class="form-select">
                                                    <?php 
                                                    
                                                        $track_sql = $con->prepare("SELECT 
                                                            program_id, track, acronym 
                                                            
                                                            FROM program 
                                                            WHERE department_id =:department_id
                                                            GROUP BY acronym
                                                        ");

                                                        $track_sql->bindValue(":department_id", $shs_department_id);
                                                        $track_sql->execute();

                                                        while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                            $row_program_id = $row['program_id'];

                                                            $acronym = $row['acronym'];

                                                            $selected = ($row_program_id == $student_program_id) ? "selected" : "";

                                                            echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                        }
                                                    ?>

                                                </select>
                                            </span>
                                        <?php
                                    }
                                ?>

                            </div>

                            <div class="row">

                                <span>
                                    <label for="grade">Level</label>
                                    <div>
                                        <select name="grade" id="grade">
                                            <!-- <option class="text-center" value="11"<?php echo ($admission_status == "Standard" && $type == "SHS") ? " selected" : ""; ?>>11</option>
                                            <option class="text-center" value="1"<?php echo ($admission_status == "Standard" && $type == "Tertiary") ? " selected" : ""; ?>>1</option> -->
                                            <option class="text-center" value="<?php echo $student_course_level;?>"><?php echo $student_course_level;?></option>
                                        </select>
                                    </div>
                                </span>

                                <span>
                                    <label for="semester">Semester</label>
                                    <div>
                                        <select style="pointer-events: none;" name="semester" id="semester">
                                            <option class="text-center" value=""<?php echo ($current_school_year_period == "First") ? " selected" : ""; ?>>1st</option>
                                            <option class="text-center" value=""<?php echo ($current_school_year_period == "Second") ? " selected" : ""; ?>>2nd</option>
                                        </select>
                                    </div>
                                </span>

                            </div>

                        </form>

                    </main>
                </div>

                <div class="floating">

                        <header>
                            <div class="title">
                            <h3>Available sections</h3>
                            </div>
                        </header>

                        <form method="post">

                            <main>
                                <table class="a">
                                    <thead>
                                        <tr class="text-center"> 
                                            <th rowspan="2">Section Id</th>
                                            <th rowspan="2">Section Name</th>
                                            <th rowspan="2">Student</th>
                                            <th rowspan="2">Capacity</th>
                                            <th rowspan="2">Term</th>
                                            <th rowspan="2"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            $active = "yes";
                                            // course_level + 1, regular students are applying
                                            // for grade 12, but they are in their current course_level 11

                                            // $student_course_level = $student_status_st == "Regular" 
                                            //     && $current_school_year_period == "First" 
                                            //     && $student_new_enrollee == 0 ? $student_course_level + 1 : $student_course_level;

                                            // $regularOldSections = $section->GetRegularOldSectionList($student_program_id, $current_school_year_term,
                                            //     $student_enrollment_course_level);

                                            // $irregularOldSections = $section->GetIrregularOldSectionList($student_program_id,
                                            //     $current_school_year_term);
                                            // // echo $student_status_st;

                                            // $provideSection = [];

                                            // // print_r($regularOldSections);

                                            // // 

                                            // if($student_status_st == "Regular" 
                                            //     && $current_school_year_period == "First" 
                                            //     && ($student_new_enrollee == 0 || $student_new_enrollee == 1)){
                                            //         $provideSection = $regularOldSections;

                                            // }
                                            
                                            // // All Sections are available
                                            // // Registrar should look on student records history for better insights
                                            // else if($student_status_st == "Irregular" 
                                            //     && $current_school_year_period == "First" 
                                            //     && $student_new_enrollee == 0){
                                            //         $provideSection = $irregularOldSections;
                                            // }
                                            // else if($current_school_year_period == "Second"){

                                            //     $provideSection = $regularOldSections;
                                            // }

                                            $provideSection = [];

                                            if($student_enrollment_is_new == 1){

                                                // if($current_school_year_period == "First"){
                                                    // Enrollment Course Id is mandatory.

                                                    // PROB.  Enrollment Course id is for grade 11
                                                    // but you want to select the grade 12 

                                                    $regularOldSections = $section->GetIrregularOldSectionList(
                                                        $student_program_id, $current_school_year_term,
                                                        $student_enrollment_course_level);

                                                    $provideSection = $regularOldSections;
                                                // }
                                                
                                            }


                                            // Should have filter in selecting course level section.
                                            // 
                                            if($student_enrollment_is_new == 0){
                                                // echo $student_program_id;
                                                // echo $current_school_year_period;

                                                if($current_school_year_period == "First" 
                                                    // && $student_status_st == "Irregular"
                                                    ){
                                                    
                                                    // echo "irreg";

                                                    $irregularOldSections = $section->GetIrregularOldSectionList($student_program_id,
                                                        $current_school_year_term);

                                                    $provideSection = $irregularOldSections;

                                                }

                                                if($current_school_year_period == "First" 
                                                    // && $student_status_st == "Regular"
                                                    ){
                                                    
                                                    $irregularOldSections = $section->GetIrregularOldSectionList(
                                                        $student_program_id, $current_school_year_term, 
                                                        $student_enrollment_course_level);

                                                    $provideSection = $irregularOldSections;
                                                }

                                                if($current_school_year_period == "Second"){

                                                    $regularOldSections = $section->GetRegularOldSectionList($student_program_id, $current_school_year_term,
                                                        $student_enrollment_course_level);

                                                    $provideSection = $regularOldSections;

                                                }

                                            }

                                            // New 1st Semester



                                            // Old 1st Semester

                                            if(count($provideSection) > 0){

                                                foreach ($provideSection as $key => $get_course) {

                                                    $course_id = $get_course['course_id'];

                                                    $program_section = $get_course['program_section'];
                                                    $capacity = $get_course['capacity'];
                                                    $school_year_term = $get_course['school_year_term'];

                                                    $section = new Section($con, $course_id);

                                                    $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);

                                                    $capacity = $section->GetSectionCapacity();

                                                    $program_id = $section->GetSectionProgramId($course_id);
                                                    $course_level = $section->GetSectionGradeLevel();

                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$course_id</td>
                                                            <td>$program_section</td>
                                                            <td>$totalStudent</td>
                                                            <td>$capacity</td>
                                                            <td>$school_year_term</td>
                                                            <td>
                                                                <input name='find_selected_course_id' class='radio' value='$course_id' 
                                                                type='radio'" . ($course_id == $student_enrollment_course_id ? " checked" : "") . ">
                                                            </td>
                                                        </tr>
                                                    ";
                                                }
                                            }else{
                                                // TODO. 
                                                // It means Enrollment Course Id or Student Course Id is zero
                                                // Should based on the student program ex STEM,HUMMS etc
                                                // 1. Provide Program Selection
                                                // 2. Provide list of available section based on the program selected.
                                                // 3. Update the enrollment course_id.

                                               echo "

                                                    <div class='col-md-12'>
                                                        <h4 class='text-center text-muted'>No currently available section for $student_program_acronym</h4>
                                                    </div>
                                                ";
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </main>
                            
                            <div style="margin-top: 20px;" class="action">
                                <button
                                    type="button"
                                    class="default large "
                                    onclick="window.location.href = 'process_enrollment.php?details=show&st_id=<?php echo $student_id; ?>'">
                                    Return
                                </button>
                                <button class="default large success"
                                    name="student_choose_section" type="submit">
                                    Proceed
                                </button>
                            </div>
                            
                        </form>

                    </div>

            </main>

        </div>

    <?php

?>