<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Teacher.php');

    $school_year = new SchoolYear($con, null);
    $schedule = new Schedule($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


    if(isset($_GET['sp_id']) && isset($_GET['id'])){

        $subject_program_id = $_GET['sp_id'];
        $course_id = $_GET['id'];

        $section = new Section($con, $course_id);

        $promptIfIdNotExists = $section->CheckIdExists($course_id);
        $section_name = $section->GetSectionName($course_id);
        $section_level = $section->GetSectionGradeLevel($course_id);
        $section_program_id = $section->GetSectionProgramId($course_id);
        $section_acronym = $section->GetAcronymByProgramId($section_program_id);




        $subject_program = new SubjectProgram($con, $subject_program_id);
        $sp_subject_code = $subject_program->GetSubjectProgramRawCode();

        $section_subject_code = $section->CreateSectionSubjectCode($section_name,
            $sp_subject_code);


        $back_url = "";

        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url; ?>"
                    ><i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                    </a>
                </nav>

                <div class="content">
                    <main>
                        <div class="floating" id="shs-sy">
                            <header>

                                <div class="title">
                                    <h4><?php echo $section_subject_code;?></h4>
                                </div>

                                <div class="action">
                                    <a href="students_enrolled.php?course_id=<?php echo $course_id?>&sy_id=<?php echo $db_school_year_id;?>">
                                        <button type="button" class="default clean large">Add Schedule</button>
                                    </a>
                                </div>
                            </header>
                            
                            <main>

                                <table id="department_table" class="a" style="margin: 0">
                                    <thead>
                                        <tr>
                                            <th>Monday</th>
                                            <th>Tuesday</th>
                                            <th>Wednesday</th>
                                            <th>Thursday</th>
                                            <th>Friday</th>
                                            <th>Instructor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
 
                                            $sql = $con->prepare("SELECT 
                                                t1.subject_title,
                                                t1.subject_program_id,
                                                t1.pre_req_subject_title,
                                                t1.subject_type,
                                                t1.course_level,
                                                t1.semester,
                                                t1.unit,
                                                t1.subject_code,
                                                t2.program_section,
                                                t2.course_id,
                                                t4.subject_code AS schedule_code,
                                                t4.time_to,
                                                t4.time_from,
                                                t4.schedule_time,
                                                t4.schedule_day,
                                                t4.room,
                                                t4.subject_schedule_id,
                                                t4.course_id AS schedule_course_id

                                                FROM subject_program as t1

                                                INNER JOIN course as t2 ON t2.program_id = t1.program_id
                                                LEFT JOIN subject_schedule as t4 ON t4.course_id = t2.course_id
                                                    AND t4.subject_program_id = t1.subject_program_id

                                                WHERE t2.course_id = :course_id
                                                    AND t1.semester = :semester
                                                    AND t1.program_id = :program_id
                                                    AND t1.course_level = :course_level

                                                GROUP BY t1.subject_title -- Distinct on t1.subject_title

                                                ORDER BY t1.course_level DESC, t1.semester
                                            ");

                                            
                                            $sql->bindParam(":program_id", $section_program_id);
                                            $sql->bindParam(":course_level", $section_level);
                                            $sql->bindParam(":semester", $selectedSemester);
                                            $sql->bindParam(":course_id", $course_id);
                                            
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
                                                    $course_id = $row['course_id'];
                                                    $subject_program_id = $row['subject_program_id'];

                                                    $section = new Section($con, $course_id);
                                                    $program_section = $row['program_section'];

                                                    $section_subject_code = $section->CreateSectionSubjectCode($program_section, $subject_code);

                                                    $time_to = $row['time_to'];
                                                    $time_from = $row['time_from'];
                                                    $schedule_day = $row['schedule_day'];
                                                    $schedule_time = $row['schedule_time'];
                                                    $room = $row['room'];

                                                    $schedule_course_id = $row['schedule_course_id'];
                                                    $subject_schedule_id = $row['subject_schedule_id'];


                                                    $haveSchedule = "";

                                                    $section_subject_code = $program_section . "-" . $subject_code;

                                                    $statuss = "N/A";

                                                    $type_level = $department_type_section == "Tertiary" ? "Year" : ($department_type_section == "Senior High School" ? "Grade" : "");

                                                    $add_schedule_url = "add_schedule_code.php?sp_id=$subject_program_id&id=$course_id";
                                                    $edit_schedule_url = "edit_schedule_code.php?s_id=$subject_schedule_id";

                                                    if($schedule_course_id != NULL && $schedule_course_id == $course_id){

                                                        $haveSchedule = "
                                                        <button class='btn btn-sm btn-primary'
                                                            onclick=\"window.location.href = '" . $edit_schedule_url . "'\">
                                                            <i class='bi bi-pencil'></i>
                                                        </button>
                                                    "; 

                                                    }else if($schedule_course_id == NULL){

                                                        $haveSchedule = "
                                                                <button onclick=\"window.location.href = '" . $add_schedule_url . "'\"
                                                                    class='btn btn-sm btn-primary'>
                                                                    <i class='bi bi-calendar'></i>
                                                                </button>
                                                        "; 
                                                    }

                                                    // <td>$schedule_day</td>
                                                    // <td>$schedule_time</td>
                                                    // <td>$room</td>

                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$subject_title</td>
                                                            <td>
                                                                <a href='subject_code_schedule.php?sp_id=$subject_program_id&id=$course_id'>
                                                                    $section_subject_code
                                                                </a>
                                                            </td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
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

            </div>
        <?php
    }

?>