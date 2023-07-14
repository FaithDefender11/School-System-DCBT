<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectSchedule.php');
 

    if(isset($_GET['c'])){

        $school_year = new SchoolYear($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

        $schedule_code = $_GET['c'];

        // if (!$subjectScheduleObj) {
        //     // handle error, the 'id' value does not exist in the database
        //     echo "error id";
        //     exit();
        // }

        $subject_schedule = new SubjectSchedule($con, $schedule_code);

        

        $course_id = $subject_schedule->GetScheduleCourseId();
        $subject_schedule_id = $subject_schedule->GetScheduleId();
        $room = $subject_schedule->GetScheduleRoom();
        $time_from = $subject_schedule->GetTimeFrom();
        $time_to = $subject_schedule->GetTimeTo();
        $schedule_day = $subject_schedule->GetScheduleDay();
        $teacher_id = $subject_schedule->GetScheduleTeacherId();
        $sched_year_id = $subject_schedule->GetScheduleSchoolYearId();


        $school_year_sc = new SchoolYear($con, $sched_year_id);
        $schedule_period = $school_year_sc->GetSchoolYearPeriod();



        if(
            isset($_POST['subject_edit_btn'])
            && isset($_POST['teacher_id'])
            && isset($_POST['room'])
            && isset($_POST['schedule_day'])
            && isset($_POST['time_from'])
            && isset($_POST['time_to'])
            && isset($_POST['time_from_am_pm'])
            && isset($_POST['time_to_am_pm'])
            ){
                // echo "qwe";
                
                $room = $_POST['room'];

                $schedule_day = $_POST['schedule_day'];

                $time_from = $_POST['time_from'];

                $time_to = $_POST['time_to'];

                // $course_id = $subjectScheduleObj['course_id'];

                $schedule_time = $time_from . "-" . $time_to;

                $teacher_id = $_POST['teacher_id'];

                $time_from_am_pm = $_POST['time_from_am_pm'];
                $time_to_am_pm = $_POST['time_to_am_pm'];

                $sql = $con->prepare("UPDATE subject_schedule
                                    SET room = :room,
                                        schedule_day = :schedule_day,
                                        time_from = :time_from,
                                        time_to = :time_to,
                                        schedule_time = :schedule_time,
                                        school_year_id = :school_year_id,
                                        teacher_id = :teacher_id,
                                        course_id = :course_id
                                    WHERE subject_schedule_id = :subject_schedule_id");

                $schedule_time = $time_from . ' ' . $time_from_am_pm . ' - ' . $time_to . ' ' . $time_to_am_pm;

                $sql->bindParam(":room", $room);
                $sql->bindParam(":schedule_day", $schedule_day);
                $sql->bindParam(":time_from", $time_from);
                $sql->bindParam(":time_to", $time_to);
                $sql->bindParam(":schedule_time", $schedule_time);
                $sql->bindParam(":school_year_id", $current_school_year_id);
                $sql->bindParam(":teacher_id", $teacher_id);
                $sql->bindParam(":course_id", $course_id);

                $sql->bindParam(":subject_schedule_id", $subject_schedule_id); // Assuming you have the subject_schedule_id to identify the row to update

                if($sql->execute()){
                    Alert::success("Schedule Code: $schedule_code has been updated.",
                        "../section/subject_list.php?id=$course_id");
                    exit();
                }else{
                    echo "not";
                }
            
            }

        ?>

        <div class='col-md-10 row offset-md-1'>

            <div class="card">
                <div class="card-header">
                    <h4 class='mb-3'>Edit Schedule Code <?php echo $schedule_code;?> </h4>
                    <h5 class="text-muted text-center">S.Y (<?php echo $current_school_year_term;?>) <?php echo $current_school_year_period;?> Semester</h5>
                </div>

                <div class="card-body">

                    <form method='POST'>
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="">Room number</label>
                                    <input value="<?php echo $room ?>" type="text" placeholder="(Room: 501)" name="room" id="room" class="form-control" />
                                </div>

                                <div class="mb-3">
                                    <label for="">Time From</label>
                                    <input type="text" value="<?php echo  $time_from ?>" placeholder="(7:00)" name="time_from" id="time_from" class="form-control" />
                                </div>

                                <div class="mb-3">
                                    <label for="">Time From AM/PM</label>
                                    <select name="time_from_am_pm" id="time_from_am_pm" class="form-control">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Time to</label>
                                    <input type="text" value="<?php echo $time_to ?>" placeholder="(7:00)" name="time_to" id="time_to" class="form-control" />
                                </div>

                                <div class="mb-3">
                                    <label for="">Time to AM/PM</label>
                                    <select name="time_to_am_pm" id="time_to_am_pm" class="form-control">
                                        <option value="AM">AM</option>
                                        <option selected value="PM">PM</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Instructor</label>

                                    <select class="form-control" name="teacher_id" id="teacher_id">
                                        <?php
                                            $query = $con->prepare("SELECT * FROM teacher");
                                            $query->execute();
                                            
                                            echo "<option value='' disabled selected>Choose Teacher</option>";

                                            if ($query->rowCount() > 0) {
                                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                    $selected = "";  

                                                    // Add condition to check if the option should be selected
                                                    if ($row['teacher_id'] == $teacher_id) {
                                                        $selected = "selected";
                                                    }

                                                    echo "<option value='" . $row['teacher_id'] . "' $selected>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
        
                                <div class="mb-3">
                                    <label for="schedule_day">Day</label>
                                    <select name="schedule_day" id="schedule_day" class="form-control">
                                        <option value="M" <?php echo ($schedule_day == 'M') ? 'selected' : ''; ?>>Monday</option>
                                        <option value="T" <?php echo ($schedule_day == 'T') ? 'selected' : ''; ?>>Tuesday</option>
                                        <option value="W" <?php echo ($schedule_day == 'W') ? 'selected' : ''; ?>>Wednesday</option>
                                        <option value="Th" <?php echo ($schedule_day == 'Th') ? 'selected' : ''; ?>>Thursday</option>
                                        <option value="F" <?php echo ($schedule_day == 'F') ? 'selected' : ''; ?>>Friday</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">Semester</label>
                                    <input type="text" readonly value="<?php echo $schedule_period?>" placeholder="Schedule Period" name="semester" id="semester" class="form-control" />
                                </div>

                                <div class="mb-3">
                                    <label for="">Schedule Code</label>
                                    <input type="text" name="subject_id" readonly value="<?php echo $schedule_code;?>" class="form-control">
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button name="subject_edit_btn" type="submit"
                                class="btn btn-primary">Save Schedule</button>
                            </div>
                    </form>

                </div>
            </div>

        </div>
        <?php
    }
?>