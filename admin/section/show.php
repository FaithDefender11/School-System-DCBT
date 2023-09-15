<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/StudentSubject.php');

    $school_year = new SchoolYear($con, null);
    $enrollment = new Enrollment($con);
    
    $room = new Room($con);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $GRADE_ELEVEN = 11;
    $GRADE_TWELVE = 12;

    // $period_acronym = $current_school_year_period === "First" ? "S1" : $current_school_year_period === "Second" ? "S2" : "";
    $period_acronym = ($current_school_year_period === "First") ? "S1" : (($current_school_year_period === "Second") ? "S2" : "");

    if (isset($_GET['id'])
        && isset($_GET['per_semester'])
        && isset($_GET['term'])
        
        ) {

        $course_id = $_GET['id'];
        $term = $_GET['term'];


        $section = new Section($con, $course_id);
        $student_subject = new StudentSubject($con);

        $promptIfIdNotExists = $section->CheckIdExists($course_id);
        $section_name = $section->GetSectionName($course_id);
        $section_level = $section->GetSectionGradeLevel($course_id);
        $section_program_id = $section->GetSectionProgramId($course_id);
        $section_acronym = $section->GetAcronymByProgramId($section_program_id);



        $recordsPerPageOptions = ["First", "Second"]; 

        $schedule = new Schedule($con);
        $subject_program = new SubjectProgram($con);

        $selectedSemester = isset($_GET['per_semester']) 
            ? ucfirst($_GET['per_semester']) : $recordsPerPageOptions[0];

        
        $sectionRoomFirstSemester = $section->GetSectionRoomNumberBySemester(
            $selectedSemester, $course_id, $term) ?? NULL;
        
        $sectionRoomOutput = "N/A";

        if($sectionRoomFirstSemester != NULL){
            $sectionRoomOutput = "RM $sectionRoomFirstSemester";

        }

        $recordsPerPageDropdown = '<select class="ml-2 form-control" 
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

        $db_school_year_id = $school_year->GetSchoolYearIdBySyID($selectedSemester,
            $section_term);

        $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, 
            $db_school_year_id);


            // echo $db_school_year_id;
            // echo "<br>";
        $department_type_section = "";

        // echo $section_term;

        if(isset($_SESSION['department_type_section'])){
            $department_type_section = $_SESSION['department_type_section'];
        }

        if($department_type_section === "Senior High School"){
            $back_url = "shs_list.php?id=$section_program_id&term=$section_term";

        }else if($department_type_section === "Tertiary"){
            $back_url = "tertiary_list.php?id=$section_program_id&term=$section_term";
        }


        $enrollment_form_id= "";
        $date_creation= "";
        $admission_status= "";
        $type = "";

        // $room_number = $room->GetR

        if(isset($_POST['deleteScheduleBtn'])
            && isset($_POST['subject_schedule_id'])
        ){

            $subject_schedule_id = $_POST['subject_schedule_id'];

            echo "deleted $subject_schedule_id";

            $delete = $con->prepare("DELETE FROM subject_schedule 
                WHERE subject_schedule_id = :subject_schedule_id");

            $delete->bindParam(":subject_schedule_id", $subject_schedule_id);
            $delete->execute();

            if($delete->rowCount() > 0){
                header("Location: show.php?id=$course_id");
                exit();
            }
        }
        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url; ?>"
                    ><i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                    </a>
                </nav>

                <div class="content-header">
                    <div style="display: flex;justify-content: center;" class="text-center mb-3">
                        <form method="GET" class="form-inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                            <!-- Hidden input field to preserve the 'id' parameter -->
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <input type="hidden" name="term" value="<?php echo $_GET['term']; ?>">
                            <label for="per_semester">Choose Semester:</label>
                            <?php echo $recordsPerPageDropdown; ?>
                        </form>
                    </div>
                    
                    <?php echo Helper::RevealStudentTypePending($type); ?>
                    <header style="margin-top: -45px;">
                        <div class="title">
                            <h1><?php echo $section_name?></h1> 
                        </div>
                        <span >Room No. <span class="text-primary"><?php echo $sectionRoomOutput; ?></span></span>
                        
                        <!-- <div class="action">
                            <div class="dropdown">

                                <button class="icon">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>

                                <div class="dropdown-menu">
                                    <a href="#" class="dropdown-item" style="color: red">
                                        <i class="bi bi-file-earmark-x"></i>Delete form
                                    </a>
                                    <a href="form_alignment.php?id=<?php echo $pending_enrollees_id;?>" class="text-primary dropdown-item">
                                        <i class="bi bi-pencil"></i>Edit form
                                    </a>
                                </div>
                                
                            </div>
                        </div> -->

                    </header>

                    <?php echo Helper::SectionHeaderCards($course_id,
                        $term, $selectedSemester, $section_acronym, $section_level,
                        $totalStudent); ?>
                    
                </div>

                <div class="content">
                    <main>
                        <div class="floating" id="shs-sy">
                            <header>

                                <div class="title">
                                    <h4>Section Subjects</h4>
                                </div>

                                <div class="action">
                                    <a href="students_enrolled.php?course_id=<?php echo $course_id?>&sy_id=<?php echo $db_school_year_id;?>">

                                        <button type="button" class="default large">Show Student</button>
                                    </a>
                                </div>
                            </header>
                            
                            <main>

                                <table id="department_table" class="a" style="margin: 0">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Code</th>
                                            <th>Days</th>
                                            <th>Time</th>
                                            <th>Enrolled</th>
                                            <th>Room</th>
                                            <th>Instructor</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                            $sql = $con->prepare("SELECT 
                                            
                                                DISTINCT t1.subject_title
                                                ,t1.subject_program_id
                                                ,t1.pre_req_subject_title
                                                ,t1.subject_type
                                                ,t1.course_level
                                                ,t1.semester
                                                ,t1.unit
                                                ,t1.subject_code
                                                
                                                ,t2.program_section, t2.course_id,
                                                -- t3.subject_code AS student_subject_code,

                                                t4.subject_code AS schedule_code,

                                                t4.time_to,
                                                t4.time_from,
                                                t4.schedule_time,
                                                t4.schedule_day,
                                                t4.room_id,
                                                t4.subject_schedule_id,
                                                t4.course_id AS schedule_course_id,

                                                t5.teacher_id,
                                                t5.firstname,
                                                t5.lastname

                                                -- ,t6.student_subject_id

                                                ,t6.room_number


                                                FROM subject_program as t1
                                                
                                                INNER JOIN course as t2 ON t2.program_id = t1.program_id

                                                -- LEFT JOIN student_subject as t3 ON t3.course_id = t2.course_id
                                                -- AND t3.subject_program_id = t1.subject_program_id



                                                LEFT JOIN subject_schedule as t4 ON t4.course_id = t2.course_id
                                                AND t4.subject_program_id = t1.subject_program_id

                                                LEFT JOIN teacher as t5 ON t5.teacher_id = t4.teacher_id


                                                LEFT JOIN room as t6 ON t6.room_id = t4.room_id
                                                -- LEFT JOIN student_subject as t6 ON t6.subject_program_id = t1.subject_program_id


                                                WHERE t2.course_id=:course_id
                                                AND t1.semester=:semester
                                                AND t1.program_id=:program_id
                                                AND t1.course_level=:course_level

                                                -- ORDER BY t1.course_level DESC,
                                                -- t1.semester

                                                -- ORDER BY t4.day_count ASC
                                                ORDER BY t1.subject_title DESC
                                                
                                            ");
                                            
                                            $sql->bindParam(":program_id", $section_program_id);
                                            $sql->bindParam(":course_level", $section_level);
                                            $sql->bindParam(":semester", $selectedSemester);
                                            $sql->bindParam(":course_id", $course_id);
                                            
                                            $sql->execute();

                                            if($sql->rowCount() > 0){

                                                $_SESSION['session_course_id'] = $course_id;

                                                $subject_titles_occurrences = [];
                                                $subject_code_occurrences = [];
                                                $room_occurrences = [];
                                                $teacher_fullname_occurrences = [];
                                                $days_occurrences = [];

                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                                    

                                                    
                                                    $course_id = $row['course_id'];
                                                    $section = new Section($con, $course_id);
                                                    $program_section = $row['program_section'];
                                                    $subject_code = $row['subject_code'];

                                                    $section_subject_code = $section->CreateSectionSubjectCode(
                                                        $program_section, $subject_code
                                                    );

                                                    $subject_title = $row['subject_title'];

                                                    // $room = $row['room'] ?? "-";
                                                    // $room = $row['room'] == 0 ? "-" : $row['room'];

                                                    $schedule_day = $row['schedule_day'] ?? "-";
                                                    $room_number = $row['room_number'] ?? "-";



                                                    $add_teacher_url = "
                                                        <button class='btn btn-sm btn-primary'>
                                                            <i class='fas fa-pencil'></i>
                                                        </button>
                                                    ";
                                                    $teacher_id = $row['teacher_id'];
                                                    $teacherFullName = $row['teacher_id'] != 0 ? ucfirst($row['firstname']) . " " . ucfirst($row['lastname']) : "-";

                                                    $schedule->filterSubsequentOccurrences($subject_titles_occurrences, $subject_title);
                                                    $schedule->filterSubsequentOccurrences($subject_code_occurrences, $section_subject_code);
                                                    // $schedule->filterSubsequentOccurrences($room_occurrences, $room);
                                                    // $schedule->filterSubsequentOccurrences($teacher_fullname_occurrences, $teacherFullName);
                                                    // $schedule->filterSubsequentOccurrences($days_occurrences, $schedule_day);

                                                    // $student_subject_id = $row['student_subject_id'];
                                                    // echo $student_subject_id;

                                                    $subject_program_id = $row['subject_program_id'];
                                                    $course_level = $row['course_level'];
                                                    $semester = $row['semester'];
                                                    $unit = $row['unit'];
                                                    // $pre_requisite = $row['pre_requisite'];
                                                    $pre_requisite = $row['pre_req_subject_title'];
                                                    $subject_type = $row['subject_type'];
                                                    $subject_program_id = $row['subject_program_id'];

                                                    $time_to = $row['time_to'];
                                                    $time_from = $row['time_from'];
                                                    $schedule_time = $row['schedule_time'] ?? "-";

                                                    $schedule_course_id = $row['schedule_course_id'];
                                                    $subject_schedule_id = $row['subject_schedule_id'];


                                                    $haveSchedule = "";

                                                    $statuss = "N/A";

                                                    $type_level = $department_type_section == "Tertiary" ? "Year" : ($department_type_section == "Senior High School" ? "Grade" : "");

                                                    $add_schedule_url = "add_schedule_code.php?sp_id=$subject_program_id&id=$course_id";
                                                    $edit_schedule_url = "edit_schedule_code.php?s_id=$subject_schedule_id";

                                                    $deleteSchedule = "";
                                                    if($schedule_course_id != NULL && $schedule_course_id == $course_id){

                                                        $haveSchedule = "
                                                            <button class='btn btn-sm btn-primary'
                                                                onclick=\"window.location.href = '" . $edit_schedule_url . "'\">
                                                                <i class='bi bi-pencil'></i>
                                                            </button>
                                                        "; 
                                                        
                                                        $deleteScheduleBtn = "deleteScheduleBtn($subject_schedule_id)";

                                                        $deleteSchedule = "
                                                            <form method='POST'>
                                                                <input type='hidden' name='subject_schedule_id' value='$subject_schedule_id'>
                                                                <button type='submit' name='deleteScheduleBtn' style='margin-left: 5px;' class='btn btn-sm btn-danger'
                                                                    onclick= \"return confirm('Are you sure you want to change section? This can\'t be undone.')\" >
                                                                    <i class='bi bi-trash'></i>
                                                                </button>
                                                            </form>
                                                           
                                                        "; 

                                                    }else if($schedule_course_id == NULL){

                                                        $haveSchedule = "
                                                                <button  onclick=\"window.location.href = '" . $add_schedule_url . "'\"
                                                                    class='btn btn-sm btn-primary'>
                                                                    <i class='bi bi-calendar'></i>
                                                                </button>
                                                        "; 
                                                    }


                                                    $subject_enrolled_url = "";


                                                    $student_subject_enrolled = $subject_program->GetSectionSubjectEnrolledStudents($subject_program_id,
                                                        $course_id, $section_subject_code);

                                                    $student_subject_enrolled = $student_subject_enrolled == 0 ? "" : $student_subject_enrolled;
                                                    

                                                    $subject_enrolled_url = "
                                                        <a style='color: inherit' href='subject_code_enrolled.php?id=$current_school_year_id&cd=$section_subject_code'>
                                                            $student_subject_enrolled
                                                        </a>
                                                    ";

                                                    // $asd = $student_subject->GetIdBySubjectCode($section_subject_code,
                                                    //     $current_school_year_id);
                                                    
                                                    // echo $asd;
                                                    
                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$subject_title</td>
                                                            <td>
                                                                <a style='color: #333' href='$add_schedule_url'>
                                                                    $section_subject_code
                                                                </a>
                                                            </td>
                                                            <td>$schedule_day</td>
                                                            <td>$schedule_time</td>
                                                            <td>$subject_enrolled_url</td>
                                                            <td>$room_number</td>
                                                            
                                                            <td>$teacherFullName</td>
                                                            <td>
                                                                <div style='display: flex;'>
                                                                    $haveSchedule
                                                                    $deleteSchedule
                                                                </div>
                                                               
                                                            </td>
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

<script>
    // function deleteScheduleBtn(schedule_id){
    //     Swal.fire({
    //             icon: 'question',
    //             title: `I agreed to removed Subject Program ID: ${subject_program_id}`,
    //             text: 'Please note that this action cannot be undone',
    //             showCancelButton: true,
    //             confirmButtonText: 'Yes',
    //             cancelButtonText: 'Cancel'
    //         }).then((result) => {
    //             if (result.isConfirmed) {

    //                 $.ajax({
    //                     url: "../../ajax/subject/remove_subject_program.php",
    //                     type: 'POST',
    //                     data: {
    //                         subject_program_id
    //                     },
    //                     success: function(response) {
    //                         response = response.trim();

    //                         // console.log(response);
    //                         if(response == "success_delete"){
    //                             Swal.fire({
    //                             icon: 'success',
    //                             title: `Successfully Removed`,
    //                             showConfirmButton: false,
    //                             timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
    //                             toast: true,
    //                             position: 'top-end',
    //                                 showClass: {
    //                                 popup: 'swal2-noanimation',
    //                                 backdrop: 'swal2-noanimation'
    //                                 },
    //                                 hideClass: {
    //                                 popup: '',
    //                                 backdrop: ''
    //                             }
    //                         }).then((result) => {

    //                             $('#strand_subject_view_table').load(
    //                                 location.href + ' #strand_subject_view_table'
    //                             );
    //                         });

    //                         }
    //                     },
    //                     error: function(xhr, status, error) {
    //                         // handle any errors here
    //                     }
    //                 });
    //             } else {
    //                 // User clicked "No," perform alternative action or do nothing
    //             }
    //     });
    // }
</script>