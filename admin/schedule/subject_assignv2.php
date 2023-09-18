<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectProgram.php');

    if(isset($_GET['id'])){

        $school_year = new SchoolYear($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();
        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];

        $subject_program_id = $_GET['id'];

        // $course_id = $subjectObj['course_id'];

        $subject_program = new SubjectProgram($con, $subject_program_id);

        $program_code = $subject_program->GetSubjectProgramRawCode();

        $session_course_id = 0;
        if(isset($_SESSION['session_course_id'])){
           $session_course_id = $_SESSION['session_course_id'];
        }

        $section = new Section($con, $session_course_id ?? 0);

        $program_section = $section->GetSectionName();

        # Should be accurate. This will be the basis of giving schedule.
        # Should be accurate to the student_subject subject_code or else
        # BIG PROBLEMS occur.
        $section_subject_code = $program_section . "-" . $program_code;

        $checkIDExists = $subject_program->CheckIdExists($subject_program_id);

        if(isset($_POST['subject_assign_btn'])
            // && isset($_POST['course_id'])
            && isset($_POST['teacher_id'])
            && isset($_POST['room'])
            && isset($_POST['schedule_day'])
            && isset($_POST['time_from'])
            && isset($_POST['time_to'])
            && isset($_POST['time_from_am_pm'])
            && isset($_POST['time_to_am_pm'])
            && isset($_POST['subject_code'])
            ){
            
                // echo "qwe";
                
                $subject= new Subject($con);

                $subject_name = $subject->GetSubjectTitle();
                
                $room = $_POST['room'];

                $schedule_day = $_POST['schedule_day'];

                $time_from = $_POST['time_from'];


                $time_to = $_POST['time_to'];

                $schedule_time = $time_from . "-" . $time_to;

                $teacher_id = $_POST['teacher_id'];

                // echo $teacher_id;

                $teacher_id = $teacher_id;

                $time_from_am_pm = $_POST['time_from_am_pm'];
                $time_to_am_pm = $_POST['time_to_am_pm'];

                $subject_code = $_POST['subject_code'];

                $sql = $con->prepare("INSERT INTO subject_schedule
                    (room, schedule_day, time_from, time_to, schedule_time, school_year_id, teacher_id, subject_code, subject_program_id, course_id)
                    VALUES(:room, :schedule_day, :time_from, :time_to, :schedule_time, :school_year_id, :teacher_id, :subject_code, :subject_program_id, :course_id)");
                
                $schedule_time = $time_from . ' '. $time_from_am_pm . ' - ' . $time_to. ' ' . $time_to_am_pm;

                $sql->bindParam(":room", $room);
                $sql->bindParam(":schedule_day", $schedule_day);
                $sql->bindParam(":time_from", $time_from);
                $sql->bindParam(":time_to", $time_to);
                $sql->bindParam(":schedule_time", $schedule_time);
                $sql->bindParam(":school_year_id", $current_school_year_id);
                // $sql->bindParam(":subject_id", $subject_id);
                $sql->bindParam(":teacher_id", $teacher_id);
                $sql->bindParam(":subject_code", $subject_code);
                $sql->bindParam(":subject_program_id", $subject_program_id);
                $sql->bindParam(":course_id", $session_course_id);

                // $sql->execute();
                // if(false){

                // FOR ELMS. SET ASIDE TEMPORARILY.
                if($sql->execute()){

                    Alert::success("Schedule of Subject Code: $subject_code has been assign to Teacher: $teacher_id",
                        "../section/subject_list.php?id=$session_course_id");
                    exit();
                    # First Step. Assigning schedule
                    # Second Step. Adding subject_period -> Prelim, Midterm, Pre-finals, Finals
                    
                    // $insert_subject_period = $con->prepare("INSERT INTO subject_period
                    //     (term, title, description, subject_id, school_year_id)
                    //     VALUES(:term, :title, :description, :subject_id, :school_year_id)");
                
                    // $insert_subject_period->bindValue(":term", "Prelim");
                    // $insert_subject_period->bindValue(":title", "Topic Name for $subject_name (Prelim)");
                    // $insert_subject_period->bindValue(":description", "");
                    // $insert_subject_period->bindValue(":subject_id", $subject_id);
                    // $insert_subject_period->bindValue(":school_year_id", $current_school_year_id);
                    // $insert_subject_period->execute();

                    // $insert_subject_period->bindValue(":term", "Midterm");
                    // $insert_subject_period->bindValue(":title", "Topic Name for $subject_name (Midterm)");
                    // $insert_subject_period->bindValue(":description", "");
                    // $insert_subject_period->bindValue(":subject_id", $subject_id);
                    // $insert_subject_period->bindValue(":school_year_id", $current_school_year_id);
                    
                    // if($insert_subject_period->execute()){
                    //     Alert::success("Subject $subject_id has been assign to Teacher: $teacher_id",
                    //         "../section/subject_list.php?id=$course_id");
                    // }


                }
        }

        ?>
            <div class='col-md-10 row offset-md-1'>
                <div class="card">
                    <div class="card-header">
                        <h4 class='mb-3'>Assigning schedule to <?php echo $section_subject_code; ?> subject code.</h4>
                        <h5 class="text-muted text-center">S.Y (<?php echo $current_school_year_term;?>) <?php echo $current_school_year_period;?> Semester</h5>
                    </div>

                    <div class="card-body">

                        <form method='POST'>
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="">Room number</label>
                                    <input value='55' type="text" placeholder="(Room: 501)" name="room" id="room" class="form-control" />
                                </div>

                                <div class="mb-3">
                                    <label for="">Time From</label>
                                    <input type="text" value="8:00" placeholder="(7:00)" name="time_from" id="time_from" class="form-control" />
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
                                    <input type="text" value="9:30" placeholder="(7:00)" name="time_to" id="time_to" class="form-control" />
                                </div>

                                <div class="mb-3">
                                    <label for="">Time to AM/PM</label>
                                    <select name="time_to_am_pm" id="time_to_am_pm" class="form-control">
                                        <option value=AM">AM</option>
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
                                                    if ($row['teacher_id'] == $selectedTeacherId) {
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
                                        <option value="">-- Select Day --</option>
                                        <option value="M">Monday</option>
                                        <option value="T">Tuesday</option>
                                        <option value="W">Wednesday</option>
                                        <option value="Th">Thursday</option>
                                        <option value="F">Friday</option>
                                    </select>
                                </div>



                                <div class="mb-3">
                                    <label for="">Semester</label>
                                    <input type="text" readonly value="<?php echo $current_school_year_period?>" placeholder="Semester Period" name="semester" id="semester" class="form-control" />
                                </div>

                                <div class="mb-3">
                                    <label for="">Subject Code</label>
                                    <input type="text" name="subject_code" readonly value="<?php echo $section_subject_code; ?>" class="form-control">
                                </div>

                                <!-- <div class="form-group mb-4">
                                    <label for="subject_id">Subject:</label>

                                    <select class="form-control" name="subject_id" id="subject_id">
                                        <option value="">Pick Subject</option>
                                    </select>
                                </div> -->
                            

                                <!-- <div class="form-group mb-4">
                                    <label for="teacher">Select Teacher:</label>
                                    <select class="form-control" name="teacher_id">
                                        <?php
                                            $query = $con->prepare("SELECT * FROM teacher");
                                            $query->execute();

                                            echo "<option value='' disabled selected>Select Teacher</option>";

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                                echo "<option value='" . $row['teacher_id'] . "'>" . $row['firstname'] . " " . $row['lastname'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div> -->

                            </div>
                            <div class="modal-footer">
                                <button name="subject_assign_btn" type="submit" class="btn btn-primary">Save Schedule</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        <?php
    }
?>
 