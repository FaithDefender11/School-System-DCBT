<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');

    $school_year = new SchoolYear($con, null);
    $schedule = new Schedule($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];



        $teacher = new Teacher($con);

        // $fullname = $teacher->GetTeacherFullName();

        if (
            isset($_POST['create_teacher_schedule']) &&
            isset($_POST['teacher_id']) &&
            isset($_POST['course_id']) &&
            isset($_POST['subject_id']) &&
            isset($_POST['room']) &&
            isset($_POST['schedule_day']) &&
            isset($_POST['time_from']) &&
            isset($_POST['time_to']) &&
            isset($_POST['time_from_am_pm']) &&
            isset($_POST['time_to_am_pm'])) {

            $room = $_POST['room'];
            $schedule_day = $_POST['schedule_day'];
            $time_from = $_POST['time_from'];
            $time_to = $_POST['time_to'];
            $schedule_time = $time_from . "-" . $time_to;
            $course_id = $_POST['course_id'];
            $subject_id = $_POST['subject_id'];
            $teacher_id = $_POST['teacher_id'];

            $time_from_am_pm = $_POST['time_from_am_pm'];
            $time_to_am_pm = $_POST['time_to_am_pm'];

            // echo "Room: " . $room . "<br>";
            // echo "Schedule Day: " . $schedule_day . "<br>";
            // echo "Time From: " . $time_from . "<br>";
            // echo "Time To: " . $time_to . "<br>";
            // echo "Schedule Time: " . $schedule_time . "<br>";
            // echo "Course ID: " . $course_id . "<br>";
            // echo "Subject ID: " . $subject_id . "<br>";
            // echo "Time From AM/PM: " . $time_from_am_pm . "<br>";
            // echo "Time To AM/PM: " . $time_to_am_pm . "<br>";

            if($course_id != 0){

                $teacher = new Teacher($con, $teacher_id);
                $new_teacher_fullname = $teacher->GetTeacherFullName();

                $section_query = $con->prepare("SELECT program_section, room FROM course
                    WHERE course_id=:course_id
                    LIMIT 1");
                $section_query->bindValue(":course_id", $course_id);
                $section_query->execute();


                // Check if  teacher has already scheduled in the subject.

                if($schedule->CheckIfTeacherAlreadyScheduleToTheSubject(
                    $subject_id, $teacher_id) == true){

                    Alert::error("Subject $subject_id has already been schedule to $new_teacher_fullname", 'create.php');
                    exit();
                    return;
                }else{
                     
                    $sql = $con->prepare("INSERT INTO subject_schedule
                        (room, schedule_day, time_from, time_to, schedule_time, school_year_id, course_id, subject_id, teacher_id)
                        VALUES(:room, :schedule_day, :time_from, :time_to, :schedule_time, :school_year_id, :course_id, :subject_id, :teacher_id)");

                    $schedule_time = $time_from . ' '. $time_from_am_pm . ' - ' . $time_to. ' ' . $time_to_am_pm;
                    $sql->bindParam(":room", $room);
                    $sql->bindParam(":schedule_day", $schedule_day);
                    $sql->bindParam(":time_from", $time_from);
                    $sql->bindParam(":time_to", $time_to);
                    $sql->bindParam(":schedule_time", $schedule_time);
                    $sql->bindParam(":school_year_id", $current_school_year_id);
                    $sql->bindParam(":course_id", $course_id);
                    $sql->bindParam(":subject_id", $subject_id);
                    $sql->bindParam(":teacher_id", $teacher_id);

                    if($sql->execute()){
                        Alert::success("Subject $subject_id has been inserted to $new_teacher_fullname", '../teacher/subject_load.php');
                        exit();
                    }

                }

                
            }
        }
        
        ?>

            <div class='col-md-10 row offset-md-1'>

                <div class="card">
                    <div class="card-header">
                        <h3 class='mb-3'>Create Schedule</h3>
                        <h5 class="text-muted text-center">S.Y (<?php echo $current_school_year_term;?>) <?php echo $current_school_year_period;?> Semester</h5>
                    </div>

                    <div class="card-body">

                        <form method='POST'>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="">Room number</label>
                                        <input value='55' required type="text" placeholder="Input Room" name="room" id="room" class="form-control" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Time From</label>
                                        <input type="text" required value="8:00" placeholder="(7:00)" name="time_from" id="time_from" class="form-control" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Time From AM/PM</label>
                                        <select required name="time_from_am_pm" id="time_from_am_pm" class="form-control">
                                            <option value="AM">AM</option>
                                            <option value="PM">PM</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Time to</label>
                                        <input required type="text" value="9:30" placeholder="(7:00)" name="time_to" id="time_to" class="form-control" />
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Time to AM/PM</label>
                                        <select required name="time_to_am_pm" id="time_to_am_pm" class="form-control">
                                            <option value="AM">AM</option>
                                            <option selected value="PM">PM</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Select Teacher</label>
                                        <select required class="form-control" name="teacher_id" id="teacher_id">
                                            <?php
                                                $query = $con->prepare("SELECT * FROM teacher
                                                    WHERE teacher_status = :teacher_status
                                                    -- AND active=:active
                                                ");
                                                $query->bindValue(":teacher_status", "Active");
                                                $query->execute();

                                                if($query->rowCount() > 0){
                                                  

                                                    // echo "<option value='Teach-Section' disabled selected>Select-Section</option>";

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
                                        <label for="schedule_day">Day</label>
                                        <select required name="schedule_day" id="schedule_day" class="form-control">
                                            <option value="">-- Select Day --</option>
                                            <option value="M">Monday</option>
                                            <option value="T">Tuesday</option>
                                            <option value="W">Wednesday</option>
                                            <option value="Th">Thursday</option>
                                            <option value="F">Friday</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Strand Section</label>
                                        <!-- <input type="text" placeholder="Section" name="section" id="section" class="form-control" /> -->
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
                                    </div>

                                    <div class="mb-3">
                                        <label for="">Semester</label>
                                        <input required type="text" readonly value="<?php echo $current_school_year_period?>" placeholder="Semester Period" name="semester" id="semester" class="form-control" />
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="subject_id">Subject:</label>

                                        <!-- <select class="form-control" name="subject_id" id="subject_id">
                                            <option value="">Pick Subject</option>
                                        </select> -->

                                        <select  required class="form-control" name="subject_id" id="subject_id">
                                            <?php
                                                $query = $con->prepare("SELECT * FROM subject as t1
                                                    INNER JOIN course as t2 ON t2.course_id = t1.course_id
                                                    WHERE t1.semester = :semester
                                                    -- AND active=:active
                                                ");
                                                $query->bindValue(":semester", $current_school_year_period);
                                                // $query->bindValue(":active", "yes");
                                                $query->execute();

                                                echo "<option value='' disabled selected>Select-Section</option>";

                                                while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<option value='" . $row['subject_id'] . "'> [". $row['subject_id']."]  (". $row['program_section'].") "  . $row['subject_title'] . "</option>";
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="modal-footer">
                                    <button name="create_teacher_schedule" type="submit" class="btn btn-primary">Save Schedule</button>
                                </div>
                        </form>

                    </div>
                </div>


            </div>

        <?php

?>


<script>
    
    // $('#course_id').on('change', function() {

    //     var course_id = parseInt($(this).val());

    //     // var course_id = parseInt($("#course_id").val());
    //     // console.log(course_id);
        
    //     if (!course_id) {
    //         $('#select_semester').html('<option value="">Select Semester</option>');
    //         return;
    //     }

    //     $.ajax({
    //         url: '../../ajax/subject/schedule_populate_subject.php',
    //         type: 'POST',
    //         data: {
    //             course_id: course_id},

    //         dataType: 'json',
    //         success: function(response) {

    //             // response = response.trim();
    //             // console.log(response);
    //             var options = '<option value="">Select a Subject</option>';

    //             $.each(response, function(index, value) {
    //                 options += '<option value="' + value.subject_id + '"> ('+value.subject_id+') ' + value.subject_title +'</option>';
    //             });
    //             $('#subject_id').html(options);
    //         }
    //     });
    // });
</script>
