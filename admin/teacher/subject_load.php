<?php 

    $schedule = new Schedule($con);

    $selected_school_year_id = "";
    $selected_course_id = "";

    if($_SERVER['REQUEST_METHOD'] === "POST" 
            && isset($_POST['teacher_search_btn'])){

        $selected_school_year_id = $_POST['school_year_id'] ?? NULL;

        $school_year = new SchoolYear($con, $selected_school_year_id);

        $selected_course_id = $_POST['course_id'] ?? NULL;
     
    }

    // echo $selected_school_year_id;
    // echo "<br>";
    // echo $selected_course_id;

    if(isset($_POST['reset_btn'])){

        $sy_id = NULL;
        $selected_program_id = NULL;
    }


    $userTimeFrom = '07:00';
    $userTimeTo = '08:31';
    $userScheduleDay = 'M';

    # NEW
    $check1 = $schedule->CheckTeacherScheduleConflicted(
        $userTimeFrom, $userTimeTo, $userScheduleDay, $teacher_id);

    # OLD
    // $check1 = $schedule->CheckTeacherScheduleConflictedH(
    //     $userTimeFrom, $userTimeTo, $userScheduleDay, $teacher_id);


    // $check2 = $schedule->CheckScheduleDayWithRoomConflict(
    //     $userTimeFrom, $userTimeTo, $userScheduleDay, $room_id);


    // var_dump($check1);
 
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

            #enrolled_students_table_filter{
            margin-top: 12px;
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: start;
            }

            #enrolled_students_table_filter input{
            width: 250px;
            }
        </style>

        <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    </head>


<div class="tabs">
    <?php
        echo "
            <button class='tab' 
                style='background-color: var(--theme); color: white'
                onclick=\"window.location.href = 'info.php?details=show&id=$teacher_id';\">
                <i class='bi bi-clipboard-check'></i>
                Details
            </button>
        ";

        echo "
            <button class='tab' 
                id='shsPayment'
                style='background-color: var(--mainContentBG); color: black'
                onclick=\"window.location.href = 'info.php?subject_load=show&id=$teacher_id';\">
                <i class='bi bi-book'></i>
                Subject Load
            </button>
        ";
    ?>
</div>


<div class="content">
    <main>

        <div class="floating">
            <main>
                <header>
                    <div class="title">
                        <h3>Schedule List</h3>

                        <?php 
                            if($selected_course_id != "" && $selected_school_year_id != ""){
                                ?>
                                    <form  action='print_schedule.php' method='POST'>

                                        <input type="hidden" name="selected_sy_id" id="selected_sy_id" value="<?php echo $selected_school_year_id;?>">
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

                    
                    
                </header>   

                <div class="col-md-12">

                    <form method="POST">
                        <div class="row invoice-info">
                            
                        <input type="hidden" id="teacher_id" value="<?php echo $teacher_id;?> ">

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

                            <!-- <div class="col-sm-3 invoice-col">
                                Program - Section

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
                            </div> -->

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
                                    <button type="submit" name="teacher_search_btn" class="btn btn-primary">
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

                <table id="teacher_subject_list" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <!-- <th>Code</th> -->
                            <th>Section</th>
                            <th>Term - Semester</th>
                            <th>Days</th>
                            <th>Schedule</th>
                        </tr>
                    </thead>
                </table>
            </main>
        </div>
    </main>
</div>

<script>

    $('#school_year_id').on('change', function() {

        var school_year_id = parseInt($(this).val());
        var teacher_id = parseInt($("#teacher_id").val());

        $.ajax({
            // url: '../../ajax/teacher/get_schedule_program_section.php',
            url: '../../ajax/teacher/populate_teaching_section.php',
            type: 'POST',
            
            data: {
                school_year_id,
                teacher_id
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

    var selected_sy_id = `
        <?php echo $selected_school_year_id; ?>
    `;

    selected_sy_id = selected_sy_id.trim();
    
    var selected_course_id = `
        <?php echo $selected_course_id; ?>
    `;

    selected_course_id = selected_course_id.trim();


    var teacher_id = parseInt($("#teacher_id").val());


    $(document).ready(function() {

        var table = $('#teacher_subject_list').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `subjectLoadList.php?sy_id=${selected_sy_id}&c_id=${selected_course_id}&t_id=${teacher_id}`,
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },
            // 'pageLength': 2,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available data for enrolled students."
            },
            
            'columns': [
            { data: 'subject_title', orderable: false },  
            // { data: 'subject_code', orderable: false },  
            { data: 'program_section', orderable: false },
            { data: 'term_period', orderable: false },
            { data: 'schedule_day', orderable: false },
            { data: 'schedule_time', orderable: false }, 
            ],
            'ordering': true
        });
    });
</script>
