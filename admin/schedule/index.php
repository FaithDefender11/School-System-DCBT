

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    ?>
        <head>
            <style>
                .show_search{
                    position: relative;
                    /* margin-top: -38px;
                    margin-left: 215px; */
                }
                div.dataTables_length {
                    display: none;
                }

                #evaluation_table_filter{
                margin-top: 15px;
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: start;
                margin-bottom: 7px;
                }

                #evaluation_table_filter input{
                width: 250px;
                }

            </style>

            <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        </head>
        <style>
            table{
                min-width: 100%;
                overflow-y: auto;
            }
        </style>
    <?php

    $schedule = new Schedule($con);

    $userTimeFrom = '11:29';
    $userTimeTo = '13:00';
    $userScheduleDay = 'M';
    $room_id = 9;
    // $room_id = NULL;


    $check = $schedule->CheckScheduleDayWithRoomConflict(
        $userTimeFrom, $userTimeTo, $userScheduleDay, $room_id);

    // var_dump($check);

    $sy_id = "";
    $selected_program_id = "";
    $school_year_search = "";
    $selected_course_id = "";


    
    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){

        $sy_id = $_POST['school_year_id'] ?? NULL;
        $selected_program_id = $_POST['program_id'] ?? NULL;

        $school_year = new SchoolYear($con, $sy_id);

        $get_term = $school_year->GetTerm();
        $get_period = $school_year->GetPeriod();


        $program = new Program($con, $selected_program_id);
        $program_name = $program->GetProgramName();


        $selected_course_id = $_POST['course_id'] ?? NULL;

        // $redirectUrl = "enrollmentListData.php?sy_id=$sy_id&p_id=$selected_program_id&c_id=$selected_course_id";
        // header("Location: $redirectUrl");


 

    }

    if(isset($_POST['reset_btn'])){
        $sy_id = NULL;
        $selected_program_id = NULL;
    }
    
?>

    

    <div class="col-md-12">

        <form method="POST">
            <div class="row invoice-info">
                
                <div class="col-sm-3 invoice-col">
                    Academic Year
                    <select name="school_year_id" id="school_year_id" class="form-control">
                        <?php 
                            $query = $con->prepare("SELECT t1.*
                                FROM school_year AS t1
                            ");

                            // $query->bindParam(":condition2", $Tertiary);
                            $query->execute();
                            if($query->rowCount() > 0){

                                echo "
                                    <option value='' selected>Select Term</option>
                                ";

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $term = $row['term'];
                                    $period = $row['period'];
                                    $school_year_id = $row['school_year_id'];

                                    $selected = "";
                                    if($sy_id == $school_year_id){
                                        $selected = "selected";
                                    }
                                    echo "
                                        <option $selected value='$school_year_id'>$term $period Semester</option>
                                    ";
                                }
                            }
                        ?>
                    </select>

                </div>

                <div class="col-sm-3 invoice-col">
                    Offered Program

                    <select name="program_id" id="program_id" class="form-control">
                        <?php 
                            $query = $con->prepare("SELECT t1.*

                                FROM program AS t1
                            ");

                            // $query->bindParam(":condition2", $Tertiary);
                            $query->execute();
                            if($query->rowCount() > 0){

                                echo "
                                    <option value='' selected>Choose Program</option>
                                ";

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $program_name = $row['program_name'];
                                    $acronym = $row['acronym'];
                                    $program_id = $row['program_id'];

                                    $selected = "";
                                    if($selected_program_id == $program_id){
                                        $selected = "selected";
                                    }
                                    echo "
                                        <option $selected value='$program_id'>$acronym</option>
                                    ";
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="col-sm-3 invoice-col">
                    Program - Section
                        <select name="course_id" id="course_id"  class="form-control">
                            <?php 

                                if($selected_course_id != "") {
                                    $query = $con->prepare("SELECT t1.*

                                    FROM course AS t1
                                    WHERE t1.course_id=:course_id
                                    ");

                                    $query->bindParam(":course_id", $selected_course_id);
                                    $query->execute();

                                    if($query->rowCount() > 0){

                                        $row = $query->fetch(PDO::FETCH_ASSOC);

                                        $program_section = $row['program_section'];
                                        // $acronym = $row['acronym'];
                                        $course_id = $row['course_id'];

                                        $selected = "";
                                        if($selected_course_id == $course_id){
                                            $selected = "selected";
                                        }
                                        echo "
                                            <option $selected value='$course_id'>$program_section</option>
                                        ";
                                    }   
                                }
                                
                            ?>
                        </select>
                </div>


                <div class="col-sm-0 invoice-col"> 
                    <br>
                    <div class="form-group"> 
                        <button type="submit" name="schedule_btn2" class="btn btn-primary">
                            <i class="fas fa-search fa-1x"></i>
                        </button>
                    </div>
                </div>
                <div class="col-sm-0 invoice-col"> 
                    <br>
                    <div class="form-group"> 
                        <button type="submit" name="reset_btn" class="btn btn-outline-primary">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="content">
        <main>
           
            <div style="display: none;" class="floating">
                <header>
                    <div class="title">
                        <h4 id="clickSchedule">Schedule</h4>
                    </div>
                
                   
                </header>
                <main >

                    <table class="a" style="margin: 0">
                        <thead>
                            <tr>
                                <th>Program - Section</th>
                                <th>Subject Code</th>
                                <th>Term</th>
                                <th>Period</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            
                                $query = $con->prepare("SELECT 
                                    t1.*, t2.program_section, t2.school_year_term,
                                    t3.term, t3.period

                                    FROM subject_schedule AS t1

                                    INNER JOIN course AS t2 ON t2.course_id = t1.course_id
                                    INNER JOIN school_year AS t3 ON t3.school_year_id = t1.school_year_id


                                    ORDER BY t2.program_section,
                                    t3.term DESC,
                                    t3.period ASC
                                ");

                                // $query->bindParam(":condition2", $Tertiary);
                                $query->execute();

                                if($query->rowCount() > 0){

                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                        $subject_code = $row['subject_code'];
                                        $schedule_day = $row['schedule_day'];
                                        $schedule_time = $row['schedule_time'];
                                        $program_section = $row['program_section'];
                                        $school_year_term = $row['school_year_term'];
                                        $term = $row['term'];
                                        $period = $row['period'];

                                        $removeDepartmentBtn= "";

                                        $days = $schedule->convertToDays($schedule_day);

                                        echo "
                                        <tr>
                                            <td>$program_section</td>
                                            <td>$subject_code</td>
                                            <td>$school_year_term</td>
                                            <td>$period</td>
                                            <td>$schedule_day</td>
                                            <td>$schedule_time</td>
                                            <td></td>
                                        </tr>
                                        ";
                                    }
                                }

                            ?>
                        </tbody>
                    </table>
                </main>

            </div>

            <div class="floating">
                <main>
                    <header>
                        <div class="title">
                            <h4 id="clickSchedule">Schedule</h4>

                            <p>
                                <?php 
                                    if($school_year_search !== ""){
                                        // echo "Results: $school_year_search";
                                    }
                                ?>
                            </p>

                            <?php 
                                if($selected_course_id != "" && $sy_id != "" && $selected_program_id != ""){
                                    ?>
                                        <form  action='print_schedule.php' method='POST'>

                                            <input type="hidden" name="selected_sy_id" id="selected_sy_id" value="<?php echo $sy_id;?>">
                                            <input type="hidden" name="selected_program_id" id="selected_program_id" value="<?php echo $selected_program_id;?>">
                                            <input type="hidden" name="selected_course_id" id="selected_course_id" value="<?php echo $selected_course_id;?>">
                                        
                                            <button style="cursor: pointer;"
                                                type='submit' 
                                                
                                                href='#' name="print_schedule"
                                                class=' btn btn-primary'>
                                                <i class='bi bi-file-earmark-x'></i>&nbsp Print
                                            </button>
                                        </form>
                                    <?php
                                }
                            ?>


                        </div>

                         <div class="action">
                        <button onclick="window.location.href= 'create.php' " class="default clean">
                            <i class="fas fa-plus"></i>
                        SCHEDULE</button>
                    </div>
                    </header>
                        

                    <table id="schedule_table_list" class="a" style="margin: 0">
                        <thead>
                            <tr >
                                <th>Program - Section</th>
                                <th>Subject Code</th>
                                <th>A.Y</th>
                                <th>Period</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Type</th>
                                <th>Instructor</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </main>
            </div>

        </main>
    </div>

<script>


    $('#program_id').on('change', function() {

        var program_id = parseInt($(this).val());
        var chosen_school_year_id = parseInt($("#school_year_id").val());

        $.ajax({
            url: '../../ajax/schedule/get_schedule_program_section.php',
            type: 'POST',
            data: {
                program_id,
                chosen_school_year_id
            },
            dataType: 'json',

            success: function(response) {

                // response = response.trim();

                console.log(response);

                if(response.length > 0){
                    var options = '<option selected value="">Available Sections</option>';
                    
                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.course_id + '">' + value.program_section + '</option>';
                    });

                    $('#course_id').html(options);
                }else{
                    $('#course_id').html('<option selected value="">No data found(s).</option>');

                }
            }
        });
    });

    $(document).ready(function() {
        console.log('Document is ready'); 

        $("#clickSectionRedirect").click(function(e) {
                e.preventDefault(); // Prevent the default link behavior
                console.log('Link clicked'); // Check if the click event is captured
        });

        $("#clickSchedule").on('click', function(){
            e.preventDefault(); // Prevent the default link behavior
                console.log('Link clicked');
        });
  
        var selected_sy_id = `
            <?php echo $sy_id; ?>
        `;

        var selected_program_id = `
            <?php echo $selected_program_id; ?>
        `;

        var selected_course_id = `
            <?php echo $selected_course_id; ?>
        `;

        selected_sy_id = selected_sy_id.trim();
        selected_program_id = selected_program_id.trim();

        var table = $('#schedule_table_list').DataTable({

            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `scheduleDataList.php?sy_id=${selected_sy_id}&p_id=${selected_program_id}&c_id=${selected_course_id}`,
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },

            'pageLength': 15,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available schedule.",
            },
            'columns': [
                { data: 'program_section', orderable: false },  
                { data: 'subject_code', orderable: true },  
                { data: 'term', orderable: false },  
                { data: 'period', orderable: false },  
                { data: 'day', orderable: false }, 
                { data: 'time', orderable: false },  
                { data: 'room', orderable: false },
                { data: 'type', orderable: false },
                { data: 'instructor', orderable: false },
                { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });

    });

</script>


