<?php

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Teacher.php');
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

    if(isset($_GET['s_id'])){

        $schedule_id = $_GET['s_id'];

        $schedule = new Schedule($con, $schedule_id);

        $checkIdExists = $schedule->CheckIdExists($schedule_id);

        $schedule_course_id = $schedule->GetScheduleCourseId();
        $schedule_room = $schedule->GetRoom();
        $schedule_time_to = $schedule->GetTimeTo();
        $schedule_time_from = $schedule->GetTimeFrom();
        $schedule_day = $schedule->GetScheduleDay();
        $schedule_time = $schedule->GetScheduleTime();
        $schedule_teacher_id = $schedule->GetScheduleTeacherId();

        $teacher = new Teacher($con, $schedule_teacher_id);

        $section_subject_code = $schedule->GetScheduleScheduleSubjectCode();

        $back_url = "show.php?id=$schedule_course_id&per_semester=$current_school_year_period&term=$current_school_year_term";

        if (isset($_POST['edit_subject_code_schedule_' . $schedule_id]) &&
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
            // echo "Course ID: " . $schedule_course_id . "<br>";

            // if(false){
            if($schedule_course_id != 0){

                $teacher = new Teacher($con, $teacher_id);
                $new_teacher_fullname = $teacher->GetTeacherFullName();

                // Check if  teacher has already scheduled in the subject.

                // if($schedule->CheckIfTeacherAlreadyScheduleToTheSubject(
                //     $subject_id, $teacher_id) == true){

                //     Alert::error("Subject Code $section_subject_code has already been schedule to $new_teacher_fullname", 'create.php');
                //     exit();
                //     return;
                // }


                // echo "teacher_id; $teacher_id";
                // echo "<br>";

                // echo "schedule_teacher_id; $schedule_teacher_id";
                // echo "<br>";
                // return;

                // $subject_program_id = 0;
                $scheduleUpdateSuccess = $schedule->UpdateScheduleCodeBase(
                    $schedule_id, $time_from_meridian, $time_to_meridian,
                    $schedule_day, $time_from_meridian_military, $time_to_meridian_military,
                    $schedule_time, $current_school_year_id,
                    $teacher_id, $section_subject_code, $schedule_teacher_id
                );

                if($scheduleUpdateSuccess){
                    Alert::success("Schedule Subject Code: $section_subject_code has been modified", $back_url);
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
                                <h3 style="font-weight: bold;" class="text-center text-primary">Editing Schedule of Code: <?php echo $section_subject_code;?></h3>
                            </div>
                        </header>
                    </div>

                    <form method='POST'>

                            <!-- <div class="mb-3">
                                <label for="">* Room Number</label>
                                <input value='<?php echo $schedule_room; ?>' required type="text" placeholder="Input Room" name="room" id="room" class="form-control" />
                            </div> -->

                            <div class="mb-3" style="position: relative">
                                <label for="">* Time From</label>
                                <input id="datetime" type="text" required value="<?php echo $schedule_time_from;?>"   name="time_from" id="time_from" class="form-control" />
                            </div>

                            <div class="mb-3" style="position: relative">
                                <label for="">* Time To</label>
                                <input id="datetimex" required type="text" value="<?php echo $schedule_time_to;?>" name="time_to" id="time_to" class="form-control" />
                            </div>
 

                            <div class="mb-3">
                                <label for="">* Instructor</label>
                                <select required class="form-control" name="teacher_id" id="teacher_id">
                                    <?php
                                        $query = $con->prepare("SELECT * FROM teacher
                                            WHERE teacher_status = :teacher_status
                                        ");
                                        $query->bindValue(":teacher_status", "Active");
                                        $query->execute();

                                        if($query->rowCount() > 0){
                                        

                                            echo "<option value='' disabled selected>Select Instructor</option>";

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                                $teacher_id = $row['teacher_id'];

                                                $teacher = new Teacher($con, $teacher_id);

                                                $fullname = $teacher->GetTeacherFullName();

                                                    $selected = "";
                                                if($schedule_teacher_id == $teacher_id){
                                                    $selected = "selected";
                                                }

                                                echo "<option $selected value='$teacher_id'>$fullname</option>";
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
                                    <option value="">- Select Day -</option>
                                    <option <?php echo $schedule_day == "M" ? "selected" : ""?> value="M">Monday</option>
                                    <option <?php echo $schedule_day == "T" ? "selected" : ""?> value="T">Tuesday</option>
                                    <option <?php echo $schedule_day == "W" ? "selected" : ""?> value="W">Wednesday</option>
                                    <option <?php echo $schedule_day == "TH" ? "selected" : ""?> value="TH">Thursday</option>
                                    <option <?php echo $schedule_day == "F" ? "selected" : ""?> value="F">Friday</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">* Semester</label>
                                <input required type="text" readonly value="<?php echo $current_school_year_period?>" placeholder="Semester Period" name="semester" id="semester" class="form-control" />
                            </div>
                            
                        <div class="modal-footer">
                            <button name="edit_subject_code_schedule_<?php echo $schedule_id?>" type="submit" class="default clean">Save Changes</button>
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