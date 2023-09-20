<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Room.php');


    $school_year = new SchoolYear($con, null);
    $schedule = new Schedule($con);
    $teacher = new Teacher($con);
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


    if(isset($_GET['id'])){

        $subject_schedule_id = $_GET['id'];

        $schedule = new Schedule($con, $subject_schedule_id);

        $section = new Section($con);


        $schedule_course_id = $schedule->GetScheduleCourseId();
        $schedule_subject_code = $schedule->GetSubjectCode();
        $schedule_room_id = $schedule->GetRoomId();
        $schedule_teacher_id = $schedule->GetScheduleTeacherId();
        $schedule_day = $schedule->GetScheduleDay();



        $room = new Room($con, $schedule_room_id);

        $roomNumber = $room->GetRoomNumber();

        // echo $schedule_subject_code;

        $back_url = "index.php";

        $allSection = $section->GetAllCreatedSectionWithinSYSemester($current_school_year_term);


        ?>
            <div class='content'>
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <div class='col-md-10 offset-md-1'>
                    <div class='card'>
                        
                        <div class='card-header'>
                            <h4 class='text-center mb-3'> Edit Schedule</h4>
                        </div>

                        <div class="card-body">

                            <form method='POST'>

                                <div class="form-group mb-3" style="position: relative">
                                    <label for="course_id">* Section</label>
                                    <select required name="course_id" id="course_id" class="form-control">
                                        <?php 
                                            if(count($allSection) > 0){

                                                echo "<option value='' disabled selected>Select Section</option>";

                                                foreach ($allSection as $key => $value) {

                                                    $course_id = $value['course_id'];
                                                    $program_section = $value['program_section'];

                                                    $selected = "";

                                                    if($course_id === $schedule_course_id){
                                                        $selected = "selected";
                                                    }

                                                    echo "<option $selected value='$course_id'>$program_section</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="subject_program_id">* Subject Code</label>
                                    <select required name="subject_program_id" id="subject_program_id" class="form-control">
                                        <option value="<?php echo $schedule_subject_code; ?>"><?php echo $schedule_subject_code; ?></option>
                                    </select>
                                    <!-- <input type="hidden" id="subject_program_id" name="subject_program_id"> -->
                                </div>

                                <div class="form-group mb-3">
                                    <label for="room_id">* Room</label>
                                    <select name="room_id" id="room_id" class="form-control">
                                        
                                        <?php
                                            $query = $con->prepare("SELECT * FROM room
                                            ");
                                            $query->execute();

                                            if($query->rowCount() > 0){
                                            
                                                echo "<option value='' disabled selected>Select Room</option>";
                                                echo "<option value='0'>TBA</option>";

                                                while($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                                    $room_id = $row['room_id'];
                                                    $room_number = $row['room_number'];

                                                    // $teacher = new Teacher($con, $teacher_id);
                                                    // $fullname = $teacher->GetTeacherFullName();
                                                    
                                                    $selected = "";

                                                    if($schedule_room_id === $room_id){
                                                        $selected = "selected";
                                                    }

                                                    echo "<option $selected value='$room_id'>$room_number</option>";
                                                }
                                            }else{
                                                echo "<option value=''>No available room.</option>";
                                            }
                                            
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3 form-group" style="position: relative">
                                    <label for="">* Time from</label>
                                    <input id="datetime" type="text" required value="8:00" placeholder=""
                                        name="time_from" id="time_from" class="form-control" />
                                </div>

                                <div class="mb-3 form-group" style="position: relative">
                                    <label for="">* Time to</label>
                                    <input id="datetimex" required type="text" value="9:30" placeholder="(7:00)" name="time_to" id="time_to" class="form-control" />
                                </div>

                                <div class="mb-3">
                                    <label for=""> Instructor</label>
                                    <select class="form-control" name="teacher_id" id="teacher_id">
                                        <?php
                                            $query = $con->prepare("SELECT * FROM teacher
                                                WHERE teacher_status = :teacher_status
                                            ");
                                            $query->bindValue(":teacher_status", "Active");
                                            $query->execute();

                                            if($query->rowCount() > 0){
                                            

                                                echo "<option value='' disabled selected>Select Instructor</option>";
                                                echo "<option value='0'>TBA</option>";

                                                while($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                                    $teacher_id = $row['teacher_id'];

                                                    $teacher = new Teacher($con, $teacher_id);

                                                    $fullname = $teacher->GetTeacherFullName();

                                                    $selected = "";

                                                    if($teacher_id === $schedule_teacher_id){
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
                                        <option value="">-- Select Day --</option>
                                        <option value="M"  <?php echo $schedule_day === "M" ? "selected" : "" ?> >Monday</option>
                                        <option value="T" <?php echo $schedule_day === "T" ? "selected" : "" ?>>Tuesday</option>
                                        <option value="W" <?php echo $schedule_day === "W" ? "selected" : "" ?>>Wednesday</option>
                                        <option value="TH" <?php echo $schedule_day === "TH" ? "selected" : "" ?>>Thursday</option>
                                        <option value="F" <?php echo $schedule_day === "F" ? "selected" : "" ?>>Friday</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="">* Year Semester</label>
                                    <input required type="text" readonly value="<?php echo "$current_school_year_term $current_school_year_period Semester"?>" placeholder="Semester Period" name="semester" id="semester" class="form-control" />
                                </div>
                                    
                                <div class="modal-footer">
                                    <button name="add_section_subject_code_schedule" type="submit" class="btn btn-success">Save Schedule</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
                

            </div>
        <?php
    }
?>

<script>

    $('#course_id').on('change', function() {

        var course_id = parseInt($(this).val());

        var term = `
            <?php echo $current_school_year_term; ?>
        `;
        var school_year_id = `
            <?php echo $current_school_year_id; ?>
        `;
 

        $.ajax({
            url: '../../ajax/schedule/generateSubjectCode.php',
            type: 'POST',
            data: {
                course_id,
                term, school_year_id
            },
            dataType: 'json',

            success: function(response) {

                // response = response.trim();

                // console.log(response);

                if(response.length > 0){
                    var options = '<option selected value="">Choose Subjects</option>';
                    
                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.subject_program_id + '">' + value.subject_code + '</option>';
                        
                        // $('#subject_program_id').val(value.subject_program_id);
                    });

                    $('#subject_program_id').html(options);
                    
                }else{
                    $('#subject_program_id').html('<option selected value="">No data found(s).</option>');

                }

            }
        });

    });


    $('#datetime').datetimepicker({
        format: 'hh:mm A'
    });
    $('#datetimex').datetimepicker({
        format: 'hh:mm A'
    });
</script>




