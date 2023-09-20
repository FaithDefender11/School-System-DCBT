<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/SubjectPeriodCode.php');

    $school_year = new SchoolYear($con, null);
    $schedule = new Schedule($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    ?>
        <head>
 
            <script src=
                "https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js">
            </script>
        
            <!-- Include Moment.js CDN -->
            <script type="text/javascript" src=
                "https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js">
            </script>
        
            <!-- Include Bootstrap DateTimePicker CDN -->
            <link
                href=
                "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css"
                rel="stylesheet">
        
            <script src=
                "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js">
                </script>
                
        </head>
    <?php

    if(isset($_GET['sp_id'])
        && isset($_GET['id'])){

        $subject_program_id = $_GET['sp_id'];
        $course_id = $_GET['id'];

        $subject_program = new SubjectProgram($con, $subject_program_id);
        $section = new Section($con, $course_id);

        $sectionName = $section->GetSectionName();
        $first_period_room_id = $section->GetSectionFirstPeriodRoomId();
        $second_period_room_id = $section->GetSectionSecondPeriodRoomId();

        $subject_period_code = new SubjectPeriodCode($con);


        // $current_school_year_period = "Second";

        $room = new Room($con, $current_school_year_period == "First" ? $first_period_room_id 
            : ($current_school_year_period == "Second" ? $second_period_room_id : 0));

        $room_number = $room->GetRoomNumber();


        $sp_subject_code = $subject_program->GetSubjectProgramRawCode();


        $section_subject_code = $section->CreateSectionSubjectCode($sectionName, $sp_subject_code);

        $back_url = "show.php?id=$course_id&per_semester=$current_school_year_period&term=$current_school_year_term";

        $teacher = new Teacher($con);

        $subject_period_name = "";
        $program_code = $sp_subject_code;

    // $fullname = $teacher->GetTeacherFullName();

    if (isset($_POST['create_teacher_schedule']) &&
        isset($_POST['teacher_id']) &&
        // isset($_POST['course_id']) &&
        // isset($_POST['room']) &&
        isset($_POST['schedule_day']) &&
        isset($_POST['time_from']) &&
        isset($_POST['time_to'])) {

        // $room = $_POST['room'];
        $schedule_day = $_POST['schedule_day'];
        $time_from_meridian = $_POST['time_from'];

        $date = DateTime::createFromFormat("h:i A", $time_from_meridian);

        // Format the DateTime object into military (24-hour) format
        $time_from_meridian_military = $date->format("H:i");

        // Output the result
        // echo "Time from Meridian: " . $time_from_meridian . "<br>";
        // echo "Military Time from: $time_from_meridian_military ";  // Output: "21:30"
        // echo "<br>";
        $time_from = str_replace(["AM", "PM"], "", $time_from_meridian);
    
        $time_to_meridian = $_POST['time_to'];

        $date = DateTime::createFromFormat("h:i A", $time_to_meridian);

        // Format the DateTime object into military (24-hour) format
        $time_to_meridian_military = $date->format("H:i");

        // echo "Time to Meridian: " . $time_to_meridian . "<br>";
        // echo "Military Time to: $time_to_meridian_military ";  // Output: "21:30"
        // echo "<br>";

        $time_to = str_replace(["AM", "PM"], "", $time_to_meridian);

        $schedule_time = $time_from . "-" . $time_to;
        // $course_id = $_POST['course_id'];
        $teacher_id = $_POST['teacher_id'];

        // $time_from_am_pm = $_POST['time_from_am_pm'];
        // $time_to_am_pm = $_POST['time_to_am_pm'];


        // echo "Room: " . $room . "<br>";
        // echo "Schedule Day: " . $schedule_day . "<br>";
        // echo "Time From: " . $time_from . "<br>";
        // echo "Time To: " . $time_to . "<br>";
        // echo "Schedule Time: " . $schedule_time . "<br>";
        // echo "Course ID: " . $course_id . "<br>";
        // echo "Time From AM/PM: " . $time_from_am_pm . "<br>";
        // echo "Time To AM/PM: " . $time_to_am_pm . "<br>";

        // if(false){
        if($course_id != 0){

            $teacher = new Teacher($con, $teacher_id);
            $new_teacher_fullname = $teacher->GetTeacherFullName();

            $section_query = $con->prepare("SELECT program_section, 
                room FROM course
                WHERE course_id=:course_id
                LIMIT 1");

            $section_query->bindValue(":course_id", $course_id);
            $section_query->execute();

            // Check if  teacher has already scheduled in the subject.

            // if($schedule->CheckIfTeacherAlreadyScheduleToTheSubject(
            //     $subject_id, $teacher_id) == true){

            //     Alert::error("Subject Code $section_subject_code has already been schedule to $new_teacher_fullname", 'create.php');
            //     exit();
            //     return;
            // }
            
            $scheduleAddedSuccess = $schedule->AddScheduleCodeBase(
                $time_from_meridian, $time_to_meridian,
                $schedule_day, $time_from_meridian_military, $time_to_meridian_military,
                $schedule_time, $current_school_year_id, $course_id,
                $teacher_id, $section_subject_code, $subject_program_id
            );

            if($scheduleAddedSuccess){

                // $attachTeaching = $subject_period_code->AttachTeacherTeachingCode($teacher_id,
                //     $subject_period_name, $current_school_year_id, $section_subject_code, $program_code);

                Alert::success("Subject Code: $section_subject_code has been placed to $new_teacher_fullname",
                    $back_url);
                exit();
            }
        }
    }

        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="floating">
                        <header>
                            <div class="title">
                                <h4 style="font-weight: bold;" class="text-center text-muted">Add Schedule to: <?php echo $section_subject_code;?></h4>
                            </div>
                        </header>
                    </div>

                    <form method='POST'>

                        
                                    <!-- Include input field with id so
                                        that we can use it in JavaScript
                                        to set attributes.-->
                            <!-- <div class="mb-3">
                                <input class="form-control" type="text" 
                                    id="datetime" />
                            </div> -->
                            <!-- <div>
                                <div style="position: relative">
                                    <input class="form-control"
                                        type="text" id="datetime" />
                                </div>
                            </div> -->

                            <!-- <div class="mb-3">
                                <label for="">* Room Number</label>
                                <input value='<?php echo $room_number;?>' type="text" placeholder="Input Room" name="room" id="room" class="form-control" />
                            </div> -->

                            

                            <div class="mb-3" style="position: relative">
                                <label for="">* Time From</label>
                                <input id="datetime" type="text" required value="8:00" placeholder=""
                                    name="time_from" id="time_from" class="form-control" />
                            </div>
 

                            <div class="mb-3" style="position: relative">
                                <label for="">* Time To</label>
                                <input id="datetimex" required type="text" value="9:30" placeholder="(7:00)" name="time_to" id="time_to" class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label for="">* Instructor</label>
                                <select required class="form-control" name="teacher_id" id="teacher_id">
                                    <?php
                                        $query = $con->prepare("SELECT * FROM teacher
                                            WHERE teacher_status = :teacher_status
                                            -- AND active=:active
                                        ");
                                        $query->bindValue(":teacher_status", "Active");
                                        $query->execute();

                                        if($query->rowCount() > 0){
                                        

                                            echo "<option value='' disabled selected>Select Instructor</option>";

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                                $teacher_id = $row['teacher_id'];

                                                $teacher = new Teacher($con, $teacher_id);

                                                $fullname = $teacher->GetTeacherFullName();

                                                echo "<option value='$teacher_id'>$fullname</option>";
                                            }
                                        }else{
                                            echo "<option value=''>No Available Teacher. Please Contact the Admin.</option>";
                                        }

                                        
                                    ?>
                                </select>
                            </div>
    
                            <div class="mb-3">
                                <label for="schedule_day">* Day</label>
                                <select required name="schedule_day" id="schedule_day" class="form-control">
                                    <option value="">-- Select Day --</option>
                                    <option value="M">Monday</option>
                                    <option value="T">Tuesday</option>
                                    <option value="W">Wednesday</option>
                                    <option value="TH">Thursday</option>
                                    <option value="F">Friday</option>
                                </select>
                            </div>

                            <!-- <div class="mb-3">
                                <label for="">Strand Section</label>
                                <select required class="form-control" name="course_id" id="course_id">
                                    <?php
                                        $query = $con->prepare("SELECT * FROM course
                                            WHERE course_level > :course_level
                                            AND active=:active
                                        ");
                                        $query->bindValue(":course_level", 10);
                                        $query->bindValue(":active", "yes");
                                        $query->execute();

                                        echo "<option value='' disabled selected>Select-Section</option>";

                                        while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . $row['course_id'] . "'>" . $row['program_section'] . "</option>";
                                        }
                                    ?>
                                </select>
                            </div> -->

                            <div class="mb-3">
                                <label for="">* Semester</label>
                                <input required type="text" readonly value="<?php echo $current_school_year_period?>" placeholder="Semester Period" name="semester" id="semester" class="form-control" />
                            </div>
                            
                        <div class="modal-footer">
                            <button name="create_teacher_schedule" type="submit" class="btn btn-success">Save Schedule</button>
                        </div>
                    </form>

                </main>

            </div>
        <?php
    }


?>

<script>
    $('#datetime').datetimepicker({
        format: 'hh:mm A'
    });
    $('#datetimex').datetimepicker({
        format: 'hh:mm A'
    });
</script>
