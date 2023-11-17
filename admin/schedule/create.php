<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/Room.php');


    // var_dump($getAllDefaultTopicTemplate);


    if(isset($_GET['course_id'])
        && isset($_GET['sp_id'])
        && isset($_GET['type'])
    
        ){

        $url_course_id = null;
        $url_sp_id = null;
        $url_t_id = null;

            
        $type = $_GET['type'];
        
        $url_course_id = $_GET['course_id'];
        $url_sp_id = $_GET['sp_id'];

        $school_year = new SchoolYear($con, null);
        $schedule = new Schedule($con);
        $teacher = new Teacher($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        $back_url = "index.php";

        $section = new Section($con, $url_course_id);

        $db_section_capacity = $section->GetSectionCapacity();

        $allSection = $section->GetAllCreatedSectionWithinSYSemester($current_school_year_term);


        $time_from = "";
        $time_to = "";


        function getInputValue($name, $value) {
            if (isset($_POST[$name]) && $_POST[$name] === $value) {
                echo 'selected';
            }
        }

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con);

        $getAllDefaultTopicTemplate = $subjectPeriodCodeTopicTemplate
            ->GetTopicTemplateDefaultTopics("ELS131");
        
        if(isset($_GET['t_id'])){

            $url_t_id = $_GET['t_id'];

        }

        $back_url = "../section/show.php?id=$url_course_id&per_semester=$current_school_year_period&term=$current_school_year_term&type=$type";
    
        if($_SERVER['REQUEST_METHOD'] === "POST" 
            && isset($_POST['add_section_subject_code_schedule'])
            && isset($_POST['course_id'])
            && isset($_POST['subject_program_id'])
            && isset($_POST['time_from'])
            && isset($_POST['time_to'])
            && isset($_POST['schedule_day'])
            && isset($_POST['schedule_room_capacity'])
            
            // && isset($_POST['searchInputTeacher'])
            
            ){

            $course_id = $_POST['course_id']; 


            // $course_id = 1253;

            $section = new Section($con, $course_id);

            $sectionName = $section->GetSectionName();

            // $subject_code = $_POST['subject_code'];
            // $time_from = $_POST['time_from'];
            // $time_to = $_POST['time_to'];

            $schedule_day = $_POST['schedule_day'];

            $schedule_room_capacity = intval($_POST['schedule_room_capacity']);

            // var_dump($schedule_room_capacity);
            // return;

            $subject_program_id = $_POST['subject_program_id'];

            $subjectProgram = new SubjectProgram($con, $subject_program_id);

            $get_subject_code = $subjectProgram->GetSubjectProgramRawCode();

        
            // $room_id = isset($_POST['room_id']) && $_POST['room_id'] == 0 ? NULL : intval($_POST['room_id']);

            $room_id = !isset($_POST['room_id']) ? NULL : intval($_POST['room_id']);
            $room_id = $room_id == 0 ? NULL : $room_id;

            # SIMPLIFY ABOUVE.
            // $room_id = isset($_POST['room_id']) ? (intval($_POST['room_id']) ?: NULL) : NULL;

            // $teacher_id = $_POST['teacher_id']  == 0 ? NULL : intval($_POST['teacher_id']);

            // $selectedTeacherId = !isset($_POST['selectedTeacherId']) ? $teacher_id : intval($_POST['selectedTeacherId']);
            
            $selectedTeacherId = $_POST['selectedTeacherId'] == "" ? NULL : intval($_POST['selectedTeacherId']);
            
            
            // $searchInputTeacher = $_POST['searchInputTeacher']; 

            // var_dump($teacher_id);
            // var_dump($selectedTeacherId);
            // return;
            
            // $room_id = isset($_POST['room_id']) ?? NULL;
            // $teacher_id = isset($_POST['teacher_id']) ?? NULL;

            // var_dump($teacher_id);
            // return;

            $section_subject_code = $section->CreateSectionSubjectCode(
                $sectionName, $get_subject_code);

            // 02:00 PM
            $time_from_meridian = $_POST['time_from'];
            $date = DateTime::createFromFormat("h:i A", $time_from_meridian);

            // Format the DateTime object into military (24-hour) format
            // 14:00

            $time_from_meridian_military = $date->format("H:i");

    
            $time_from = str_replace(["AM", "PM"], "", $time_from_meridian);
        
            $time_to_meridian = $_POST['time_to'];
            $date = DateTime::createFromFormat("h:i A", $time_to_meridian);

            $checkIfTimeIsEqualPrompt = $schedule->CheckIfTimeIsEqual($time_from_meridian,
                $time_to_meridian);

            // Format the DateTime object into military (24-hour) format
            $time_to_meridian_military = $date->format("H:i");
    
            $time_to = str_replace(["AM", "PM"], "", $time_to_meridian);

            // 02:00 -10:30
            $schedule_time = $time_from . "-" . $time_to;

            // echo "time_to_meridian_military: " . var_dump($time_to_meridian_military);
            // echo "<br>";

            // echo "time_from_meridian_military: " . var_dump($time_from_meridian_military);
            // echo "<br>";
            // return;

            $hasNoError = true;
                    
            $hasNoError = $schedule->CheckIfTimeFromIsGreater($time_from_meridian,
                $time_to_meridian);

            if($room_id != NULL){

                $room = new Room($con, $room_id);
                $selected_room_capacity = $room->GetRoomCapacity();

                $hasNoError = $schedule->CheckScheduleRoom($schedule_room_capacity,
                    $selected_room_capacity);
            }

            // var_dump($hasNoError);
            // return;


            // echo "time_to_meridian: " . $time_to_meridian . "<br>";
            // echo "time_to_meridian_military: " . $time_to_meridian_military . "<br>";
            // echo "schedule_time: " . $schedule_time . "<br>";

            // var_dump($checkIfTimeFromIsGreaterPrompt);

            if($hasNoError == true){

                $addScheduleSuccess = $schedule->AddSubjectCodeSchedule(
                    $time_from_meridian, $time_to_meridian,$schedule_day,
                    $time_from_meridian_military, $time_to_meridian_military,
                    $schedule_time, $current_school_year_id, $course_id,
                    $selectedTeacherId, $section_subject_code, $subject_program_id, $room_id, $back_url
                );

            }


            // if($addScheduleSuccess){
            //     Alert::success("Schedule has been added successfully.", $back_url);
            //     exit();
            // }

        }

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

            <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
            <!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->

            <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

            <style>
                .ui-autocomplete {
                    max-height: 200px; /* Set the maximum height you desire */
                    overflow-y: auto; /* Add a vertical scrollbar if necessary */
                }
            </style>
        </head>

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
                        <h4 class='text-center mb-3'> Add Schedule</h4>
                    </div>

                    <div class="card-body">

                        <form method='POST'>


                            <?php if($url_course_id != NULL && $url_sp_id != NULL): ?>

                                <div class="form-group mb-3" style="position: relative">

                                    <label for="course_id">* Section</label>

                                    <select required name="course_id" id="course_id" class="form-control">

                                        <?php 

                                            $section_url = new Section($con, $url_course_id);
                                            $section_url_program_section = $section_url->GetSectionName();

                                            // echo "<option value='' selected>Select Section</option>";
                                            echo "<option selected value='$url_course_id'>$section_url_program_section</option>";

                                            // foreach ($allSection as $key => $value) {

                                            //     $course_id = $value['course_id'];
                                            //     $program_section = $value['program_section'];
                                                
                                            //     echo "<option value='$course_id'>$program_section</option>";
                                            // }
                                        ?>

                                    </select>
                                </div>


                                <div class="form-group mb-3">
                                    <label for="subject_program_id">* Subject Code</label>

                                    <select required name="subject_program_id" id="subject_program_id" class="form-control">

                                        <?php 

                                            $sp = new SubjectProgram($con, $url_sp_id);
                                            $sp_code = $sp->GetSubjectProgramRawCode();
                                            $sp_title = $sp->GetTitle();

                                            // echo "<option value='' selected>Select Section</option>";
                                            echo "<option selected value='$url_sp_id'>$sp_title ($sp_code)</option>";
                                        
                                        ?>
                                    </select>
                                </div>


                            <?php endif;?>



                            <?php if($url_course_id == NULL && $url_sp_id == NULL): ?>
                                
                                <div class="form-group mb-3" style="position: relative">

                                    <label for="course_id">* Section</label>

                                    <select required name="course_id" id="course_id" class="form-control">

                                        <?php 
                                            if(count($allSection) > 0){

                                                echo "<option value='' selected>Select Section</option>";

                                                foreach ($allSection as $key => $value) {

                                                    $course_id = $value['course_id'];
                                                    $program_section = $value['program_section'];
                                                    
                                                    echo "<option value='$course_id'>$program_section</option>";
                                                }
                                            }
                                        ?>

                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="subject_program_id">* Subject Code</label>
                                    <select required name="subject_program_id" id="subject_program_id" class="form-control">
                                    </select>
                                </div>

                            <?php endif;?>

                            <div class="mb-3">
                                <label for="schedule_room_capacity">* Capacity</label>
                                <input style="pointer-events: none;" value="<?= $db_section_capacity?>" required name="schedule_room_capacity" id="schedule_room_capacity" class="form-control" />
                            
                            </div>


                            <div class="form-group mb-3">
                                <label for="room_id">* Room ( Note: Leave it blank serves as TBA )</label>
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
                                                $room_capacity = $row['room_capacity'];

                                                // $teacher = new Teacher($con, $teacher_id);
                                                // $fullname = $teacher->GetTeacherFullName();

                                                echo "<option value='$room_id'>Room: $room_number &nbsp; &nbsp; Capacity: $room_capacity</option>";
                                            
                                            }
                                        }else{
                                            echo "<option value=''>No available room.</option>";
                                        }
                                        
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3 form-group" style="position: relative">
                                <label for="">* Time from</label>

                                <input id="datetime" type="text" required 
                                
                                    value="<?php 
                                        echo Helper::DisplayText('time_from', $time_from);
                                    ?>" 
                                    placeholder=""
                                    name="time_from" id="time_from" class="form-control" />
                            </div>

                            <div class="mb-3 form-group" style="position: relative">
                                <label for="">* Time to</label>
                                <input id="datetimex" required type="text"
                                value="<?php 
                                    echo Helper::DisplayText('time_to', $time_to);
                                ?>" 
                                
                                placeholder="(7:00)" name="time_to" id="time_to" class="form-control" />
                            </div>

                            <div style="display: none;" class="mb-3">
                                <label for=""> Instructor</label>

                                <?php 
                                    $disabled = "";

                                    if($url_t_id != NULL){
                                        $disabled = "pointer-events: none;";
                                        // echo "hey";
                                    }
                                ?>

                                <select style="<?= $disabled; ?>" class="form-control" name="teacher_id" id="teacher_id">
                                    <?php
                                        $query = $con->prepare("SELECT * FROM teacher
                                            WHERE teacher_status = :teacher_status
                                            -- AND active=:active
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

                                                if($teacher_id == $url_t_id){
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

                            <?php
                            
                                if($url_t_id == NULL){

                                    ?>
                                        <div class="mb-3">
                                            <label for="searchInputTeacher">* Teacher ( Note: Leave it blank serves as TBA )</label>
                                            <input class="form-control" type="text" name="searchInputTeacher" id="searchInputTeacher" placeholder="Search teacher...">
                                            <input type="hidden" id="selectedTeacherId" name="selectedTeacherId">
                                        </div>
                                    <?php

                                }else{
                                    ?>
                                        <div class="mb-3">
                                            <label for=""> Instructor</label>

                                            <?php 
                                                $disabled = "";
                                                if($url_t_id != NULL){
                                                    $disabled = "pointer-events: none;";
                                                    // echo "hey";
                                                }
                                            ?>

                                            <select style="<?= $disabled; ?>" class="form-control" name="selectedTeacherId" id="selectedTeacherId">
                                                <?php
                                                    $query = $con->prepare("SELECT * FROM teacher
                                                        WHERE teacher_status = :teacher_status
                                                        -- AND active=:active
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

                                                            if($teacher_id == $url_t_id){
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
                                    <?php
                                }
                            ?>

                            <div class="mb-3">
                                <label for="schedule_day">* Schedule Day</label>
                                <select required name="schedule_day" id="schedule_day" class="form-control">
                                    <option value="">-- Select Day --</option>

                                    <!-- <option value="M">Monday</option> -->
                                    <option value="M" <?php getInputValue('schedule_day', 'M'); ?>>Monday</option>
                                    <option value="T" <?php getInputValue('schedule_day', 'T'); ?>>Tuesday</option>
                                    <option value="W" <?php getInputValue('schedule_day', 'W'); ?>>Wednesday</option>
                                    <option value="TH" <?php getInputValue('schedule_day', 'TH'); ?>>Thursday</option>
                                    <option value="F" <?php getInputValue('schedule_day', 'F'); ?>>Friday</option>
                                
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="">* A.Y Semester</label>
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

        <script>

            $(document).ready(function() {
                
                const searchInputTeacher = $('#searchInputTeacher');

                searchInputTeacher.autocomplete({
                    source: function(request, response) {
                        $.ajax({
                            url: '../../ajax/schedule/typeahead.php',
                            dataType: 'json',
                            data: {
                                query: request.term
                            },
                            success: function(data) {
                                response(data);
                                console.log(data)
                            }
                        });
                    },
                    minLength: 0,
                    select: function(event, ui) {
                        // Handle selection, e.g., redirect to a page or perform an action.
                        $('#selectedTeacherId').val(ui.item.teacher_id);
                    },
                    change: function(event, ui) {
                        // If the user clears the input or doesn't choose a teacher, set the teacher_id to 0
                        if (!ui.item) {
                            $('#selectedTeacherId').val(0);
                        }
                    }
                });

                // Attach focus event to open the autocomplete dropdown (didnt worked)
                searchInputTeacher.on('focus', function() {
                    searchInputTeacher.autocomplete('search', ''); // Open the dropdown with an empty query
                });

            });

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
                        term,
                        school_year_id
                    },
                    dataType: 'json',

                    success: function(response) {

                        // response = response.trim();

                        // console.log(response);

                        if(response.length > 0){
                            var options = '<option selected value="">Choose Subjects</option>';
                            
                            $.each(response, function (index, value) {
                                options +=
                                '<option value="' + value.subject_program_id + '">' + value.subject_title + ' &nbsp; (' + value.subject_code + ')</option>';
                                
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

        <?php
    }

    


?>



<!-- <script>
  $( function() {
    var availableTags = [
      "ActionScript",
      "AppleScript",
      "Asp",
      "BASIC",
      "C",
      "C++",
      "Clojure",
      "COBOL",
      "ColdFusion",
      "Erlang",
      "Fortran",
      "Groovy",
      "Haskell",
      "Java",
      "JavaScript",
      "Lisp",
      "Perl",
      "PHP",
      "Python",
      "Ruby",
      "Scala",
      "Scheme"
    ];

    $( "#tags" ).autocomplete({
      source: availableTags
    });

  } );
</script> -->


