<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    $school_year = new SchoolYear($con, null);
    $section = new Section($con, null);
    $enrollment = new Enrollment($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $GRADE_ELEVEN = 11;
    $GRADE_TWELVE = 12;

    // $period_acronym = $current_school_year_period === "First" ? "S1" : $current_school_year_period === "Second" ? "S2" : "";
    $period_acronym = ($current_school_year_period === "First") ? "S1" : (($current_school_year_period === "Second") ? "S2" : "");


    if (isset($_GET['id'])
        || isset($_GET['per_semester'])) {

        $course_id = $_GET['id'];

        $section = new Section($con, $course_id);

        $promptIfIdNotExists = $section->CheckIdExists($course_id);
        $section_name = $section->GetSectionName($course_id);
        $section_level = $section->GetSectionGradeLevel($course_id);
        $section_program_id = $section->GetSectionProgramId($course_id);
        $section_acronym = $section->GetAcronymByProgramId($section_program_id);
 
        // Requirements
        # Can view First and Second Semester within the specific S.Y

        # (TOP) Section name, Grade Level, Strand/Course, Number of students inside of the section

        # (Bottom) List of Subjects with regards to its Specific Period, Section and S.Y
        
        $recordsPerPageOptions = ["First", "Second"]; 

        $selectedSemester = isset($_GET['per_semester']) 
            ? $_GET['per_semester'] : $recordsPerPageOptions[0];
        
            // echo $_GET['per_semester'];

        $db_school_year_id = $school_year->GetSchoolYearIdBySyID($selectedSemester,
            $current_school_year_term);

        // $query = $con->prepare("SELECT * FROM school_year

        //     WHERE period=:period
        //     AND term=:term
        //     LIMIT 1
        //     -- AND sy.period = 11
        // ");

        // $query->bindParam(":period", $selectedSemester);
        // $query->bindParam(":term", $current_school_year_term);
        // $query->execute();
        // if($query->rowCount() > 0){
        //     $get_row = $query->fetch(PDO::FETCH_ASSOC);
        //     $db_school_year_id = $get_row['school_year_id'];
        // }

        $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, 
            $db_school_year_id);

        $recordsPerPageDropdown = '<select class="form-control" 
            name="per_semester" onchange="this.form.submit()">';

        foreach ($recordsPerPageOptions as $option) {

            $recordsPerPageDropdown .= "<option value=$option";

            if ($option == $selectedSemester) {
                $recordsPerPageDropdown .= ' selected';
            }

            $recordsPerPageDropdown .= ">" . $option . " Semester</option>";
        }

        $recordsPerPageDropdown .= '</select>';

        // echo $_SERVER['PHP_SELF'];

        $back_url = "";

        $section_term = "";

        if(isset($_SESSION['section_term'])){
            $section_term = $_SESSION['section_term'];
        }

        $department_type_section = "";

        if(isset($_SESSION['department_type_section'])){
            $department_type_section = $_SESSION['department_type_section'];
        }

        if($department_type_section === "Senior High School"){
            $back_url = "shs_list.php?id=$section_program_id&term=$section_term";

        }else if($department_type_section === "Tertiary"){
            $back_url = "tertiary_list.php?id=$section_program_id&term=$section_term";
        }


        ?>

            <div class="row col-md-12">

                <div class="col-md-12">
                    <h3 class="text-right">S.Y <?php echo $current_school_year_term;?> <?php echo $period_acronym;?></h3>
                    <a href="<?php echo $back_url;?>">
                        <button class="btn btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>

                    <div style="display: flex;
                            justify-content: center;" class="text-center mb-3">
                        <form method="GET" class="form-inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                            <!-- Hidden input field to preserve the 'id' parameter -->
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <label for="per_semester">Choose Semester:</label>
                            <?php echo $recordsPerPageDropdown; ?>
                        </form>
                    </div>

                   
                </div>

                <div class="card col-md-12">
                    <div class="card-body">

                        <div class="card-header">
                            <h3><?php echo $section_name;?></h3>
                        </div>

                        <div class="row col-md-12">

                            <div class="col-md-2">
                                <h5>Section Id</h5>
                                <span><?php echo $course_id;?></span>
                            </div>

                            <div class="col-md-2">
                                <h5>School Year</h5>
                                <span><?php echo $current_school_year_term;?></span>
                            </div>

                            <div class="col-md-2">
                                <h5>Semester</h5>
                                <span><?php echo $selectedSemester;?></span>
                            </div>

                            <div class="col-md-2">
                                <h5>Level</h5>
                                <span><?php echo $section_level;?></span>
                            </div>
                            
                            <div class="col-md-2">
                                <h5>Strand</h5>
                                <span><?php echo $section_acronym;?></span>
                            </div>

                            <div class="col-md-2">
                                <h5>Students</h5>
                                <span><?php echo $totalStudent;?></span>
                            </div>

                        </div>
                    </div>
 
                </div>

            </div>

            <div class="content">
                <main>
                    <div class="floating" id="shs-sy">
                        <header>

                            <div class="title">
                                <h3>Offered Subjects for <?php echo $current_school_year_term;?> <?php echo $selectedSemester;?> Semester</h3>
                            </div>

                            <div class="action">
                                <a href="students_enrolled.php?course_id=<?php echo $course_id?>&sy_id=<?php echo $db_school_year_id;?>">
                                    <button type="button" class="default large">Show Student</button>
                                </a>
                            </div>
                        </header>
                        <main>

                            <table id="department_table" class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Subject</th>
                                        <th>Level</th>
                                        <th>Semester</th>
                                        <th>Pre-Requisite</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 

                                        $sql = $con->prepare("SELECT 
                                        
                                            t1.*,
                                            t2.subject_code as t2_subject_code
    
                                            FROM subject_program as t1

                            
                                            LEFT JOIN subject as t2 ON t2.subject_program_id = t1.subject_program_id
                                
                                            WHERE t1.program_id=:program_id

                                            AND t1.course_level=:course_level
                                            AND t1.semester=:semester
                                            AND t2.course_id=:course_id
                                            -- AND (t1.semester='First'
                                            --     OR
                                            --     t1.semester='Second'
                                            -- )

                                            ORDER BY t1.course_level DESC,
                                            t1.semester
                                            ");
                                        
                                        // $sql->bindValue(":course_id", $course_id);
                                        $sql->bindValue(":program_id", $section_program_id);
                                        $sql->bindValue(":course_level", $section_level);
                                        $sql->bindValue(":semester", $selectedSemester);
                                        $sql->bindValue(":course_id", $course_id);
                                        // $sql->bindValue(":semester", $current_school_year_period);
                                        
                                        $sql->execute();

                                        if($sql->rowCount() > 0){

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                
                                                $t2_subject_code = $row['t2_subject_code'];
                                                $subject_program_id = $row['subject_program_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $course_level = $row['course_level'];
                                                $semester = $row['semester'];
                                                // $pre_requisite = $row['pre_requisite'];
                                                $pre_requisite = $row['pre_req_subject_title'];
                                                $subject_type = $row['subject_type'];
                                                // $subject_subject_program_id = $row['subject_subject_program_id'];
                                                // $subject_subject_title = $row['subject_subject_title'];

                                                # Find missing subjects based on subject_program

                                                $statuss = "N/A";


                                                $subject_real = $con->prepare("SELECT 
                                                        
                                                    t1.subject_title as t1_subject_title,
                                                    t1.subject_program_id as t1_subject_program_id

                                                    FROM subject as t1 

                                                    WHERE t1.subject_program_id=:subject_program_id
                                                    AND t1.course_id=:course_id
                                                    LIMIT 1");
                                                                
                                                $subject_real->bindValue(":subject_program_id", $subject_program_id);
                                                $subject_real->bindValue(":course_id", $course_id);
                                                $subject_real->execute();

                                                $t1_subject_program_id = null;

                                                if($subject_real->rowCount() > 0){

                                                    $row = $subject_real->fetch(PDO::FETCH_ASSOC);

                                                    $t1_subject_title = $row['t1_subject_title'];
                                                    $t1_subject_program_id = $row['t1_subject_program_id'];
                                                }

                                                if($t1_subject_program_id != null && $t1_subject_program_id == $subject_program_id){
                                                    $statuss = "
                                                        <i class='fas fa-check'></i>
                                                    ";
                                                }
                                                else{
                                                    $statuss = "
                                                        <button class='btn btn-sm btn-primary'>Populate</button>
                                                    ";
                                                }

                                                $type_level = $department_type_section == "Tertiary" ? "Year" : ($department_type_section == "Senior High School" ? "Grade" : "");
                                                
                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$t2_subject_code</td>
                                                        <td>$subject_title</td>
                                                        <td>$type_level $course_level</td>
                                                        <td>$semester</td>
                                                        <td>$pre_requisite</td>
                                                        <td>$subject_type</td>
                                                        <td>$statuss</td>
                                                    </tr>
                                                ";

                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>

                        </main>

                        <hr>
                        <hr>
                        <main>

                            <table id="department_table" class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Unit</th>
                                        <th>Code</th>
                                        <th>Days</th>
                                        <th>Time</th>
                                        <th>Room</th>
                                        <th>Instructor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 

                                        

                                        $sql = $con->prepare("SELECT 
                                        
                                            t1.*, t2.program_section,
                                            -- t3.subject_code AS student_subject_code,

                                            t4.subject_code AS schedule_code,

                                            t4.time_to,
                                            t4.time_from,
                                            t4.schedule_time,
                                            t4.schedule_day,
                                            t4.room

                                            FROM subject_program as t1
                                            
                                            INNER JOIN course as t2 ON t2.program_id = t1.program_id

                                            -- LEFT JOIN student_subject as t3 ON t3.course_id = t2.course_id
                                            -- AND t3.subject_program_id = t1.subject_program_id



                                            LEFT JOIN subject_schedule as t4 ON t4.course_id = t2.course_id
                                            AND t4.subject_program_id = t1.subject_program_id

                                            WHERE t2.course_id=:course_id
                                            AND t1.semester=:semester
                                            AND t1.program_id=:program_id
                                            AND t1.course_level=:course_level

                                            ORDER BY t1.course_level DESC,
                                            t1.semester
                                            
                                        ");
                                        
                                        // $sql->bindValue(":course_id", $course_id);
                                        $sql->bindParam(":program_id", $section_program_id);
                                        $sql->bindParam(":course_level", $section_level);
                                        $sql->bindParam(":semester", $selectedSemester);
                                        $sql->bindParam(":course_id", $course_id);
                                        // $sql->bindValue(":semester", $current_school_year_period);
                                        
                                        $sql->execute();

                                        if($sql->rowCount() > 0){

                                            $_SESSION['session_course_id'] = $course_id;

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                                
                                                $subject_program_id = $row['subject_program_id'];
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $course_level = $row['course_level'];
                                                $semester = $row['semester'];
                                                $unit = $row['unit'];
                                                // $pre_requisite = $row['pre_requisite'];
                                                $pre_requisite = $row['pre_req_subject_title'];
                                                $subject_type = $row['subject_type'];
                                                $subject_program_id = $row['subject_program_id'];


                                               


                                                $time_to = $row['time_to'];
                                                $time_from = $row['time_from'];
                                                $schedule_day = $row['schedule_day'];
                                                $schedule_time = $row['schedule_time'];
                                                $room = $row['room'];

                                                $haveSchedule = "";

                                                // echo $schedule_code;
                                                // echo "<br>";
                                                // echo $student_subject_code;
                                                
                                                $program_section = $row['program_section'];

                                    

                                                $t1_subject_code = $program_section . "-" . $subject_code;

                                                $statuss = "N/A";

                                                $type_level = $department_type_section == "Tertiary" ? "Year" : ($department_type_section == "Senior High School" ? "Grade" : "");

                                                $add_schedule_url=  "";

                                                $add_schedule_url = "../schedule/subject_creation.php?id=$subject_program_id";


                                                $haveSchedule = "
                                                    <a href='$add_schedule_url'>
                                                        <button class='btn  btn-primary'>
                                                            <i class='bi bi-calendar'></i>
                                                        </button>
                                                    </a>
                                                "; 


                                                // $student_subject_code = $row['student_subject_code'];

                                                $schedule_code = $row['schedule_code'];

                                                if($schedule_code !== NULL || $schedule_code !== ""){

                                                    $edit_schedule_url = "../schedule/subject_modification.php?c=$schedule_code";

                                                    $haveSchedule = "
                                                            <button class='btn  btn-primary'
                                                                onclick=\"window.location.href = '$edit_schedule_url';\">
                                                                <i class='bi bi-pencil'></i>
                                                            </button>
                                                    "; 
                                                }else{
                                                   
                                                    $haveSchedule = "
                                                        <button class='btn  btn-primary'
                                                            onclick=\"window.location.href = '$add_schedule_url';\">
                                                                <i class='bi bi-calendar'></i>
                                                        </button>
                                                    "; 
                                                }

                                                echo "
                                                    <tr class='text-center'>
                                                        <td>$subject_title</td>
                                                        <td>$unit</td>
                                                        <td>$t1_subject_code</td>
                                                        <td>$schedule_day</td>
                                                        <td>$schedule_time</td>
                                                        <td>$room</td>
                                                        <td>$haveSchedule</td>
                                                    </tr>
                                                ";

                                            }
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </main>
                    </div>
                </main>
            </div>
        <?php

    }

?>