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
                style='background-color: var(--them)'
                onclick=\"window.location.href = 'info.php?details=show&id=$teacher_id';\">
                <i class='bi bi-clipboard-check'></i>
                Details
            </button>
        ";

        echo "
            <button class='tab' 
                id='shsPayment'
                style='background-color: var(--mainContentBG); color: white'
                onclick=\"window.location.href = 'info.php?subject_load=show&id=$teacher_id';\">
                <i class='bi bi-book'></i>
                Subject Load
            </button>
        ";
    ?>
</div>




<div class="content">
    <main>
        <div style="display: none;" class="floating">
            <header>
                <div class="title">
                    <h3>Schedule List</h3>
                </div>

                <div class="form-group">
                    <label for="select_term">Term</label>
                    <select name="" id="select_term" class="form-control">
                        <option value="">First Semester</option>
                    </select>
                </div>
                
            </header>
            <main>

                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Code</th>
                            <th>Section</th>
                            <th>Days</th>
                            <th>Schedule</th>
                            <th>Hrs/Week</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $subject_titles_occurrences = [];
                            $subject_code_occurrences = [];
                            $section_occurrences = [];

                            $section = new Section($con);

                            $query = $con->prepare("SELECT 
                                t1.subject_schedule_id,
                                t1.course_id AS subject_schedule_course_id,
                                t1.subject_program_id AS subject_subject_program_id,
                                t1.time_from,
                                t1.time_to,
                                t1.schedule_day,
                                t1.schedule_time,
                                t1.room,
                                t1.course_id, t1.subject_code,


                                -- t3.subject_code, 
                                t4.program_section,
                                t4.course_id as courseCourseId,

                                t3.subject_title,
                                t3.subject_program_id,
                                t3.subject_code AS sp_subject_code
                                
                                FROM subject_schedule as t1
                                INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id
                                
                                LEFT JOIN subject_program as t3 ON t3.subject_program_id = t1.subject_program_id
                                LEFT JOIN course as t4 ON t4.course_id = t1.course_id

                                WHERE t1.teacher_id = :teacher_id

                                ORDER BY t3.subject_title DESC

                                ");

                            $query->bindParam(":teacher_id", $teacher_id);
                            $query->execute();
                            if($query->rowCount() > 0){

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $subject_title = $row['subject_title'];
                                    $course_id = $row['course_id'];
                                    $subject_code = $row['subject_code'];
                                    $program_section = $row['program_section'];
                                    $subject_program_id = $row['subject_program_id'];
                                    $courseCourseId = $row['courseCourseId'];
                                    $schedule_day = $row['schedule_day'];
                                    $time = $row['schedule_time'];
                                    $subject_subject_program_id = $row['subject_subject_program_id'];
                                    
                                    $subject_schedule_course_id = $row['subject_schedule_course_id'];

                                    $sp_subject_code = $row['sp_subject_code'];

                                    $status = "";
                                    $hrs_per_week = "";


                                    // $section_subject_code = $section->CreateSectionSubjectCode(
                                    //     $program_section, $subject_code
                                    // );

                                    // echo $subject_code;


                                    $schedule->filterSubsequentOccurrencesSa($subject_titles_occurrences,
                                        $subject_title, $subject_schedule_course_id, $subject_program_id);

                                    $schedule->filterSubsequentOccurrencesSa($subject_code_occurrences,
                                        $subject_code, $subject_schedule_course_id, $subject_subject_program_id);

                                    // $schedule->filterSubsequentOccurrencesSa($section_occurrences,
                                    //     $program_section, $subject_schedule_course_id, $subject_subject_program_id);

                                    echo "
                                        <tr>
                                            <td>$subject_title</td>
                                            <td>
                                                <a style='color: inherit' href='subject_enrolled.php?term=$current_school_year_term&cd=$subject_code&c=$course_id'>
                                                    $subject_code
                                                </a>
                                            </td>
                                            <td>$program_section</td>
                                            <td>$schedule_day</td>
                                            <td>$time</td>
                                            
                                            <td>$hrs_per_week</td>
                                        </tr>
                                    ";

                                }
                            }else{
                                echo "
                                    <div class='col-md-12'>
                                        <h4 class='text-info text-center'>No Subject Load</h4>
                                    </div>
                                ";
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
                            <th>Code</th>
                            <th>Section</th>
                            <th>A.Y - Term</th>
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
            { data: 'subject_code', orderable: false },  
            { data: 'program_section', orderable: false },
            { data: 'term_period', orderable: false },
            { data: 'schedule_day', orderable: false },
            { data: 'schedule_time', orderable: false }, 
            ],
            'ordering': true
        });
    });
</script>
