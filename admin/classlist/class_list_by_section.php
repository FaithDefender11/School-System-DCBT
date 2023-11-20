

<?php

 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');

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
    <?php

    $schedule = new Schedule($con);

    $sy_id = "";
    $selected_program_id = "";
    $school_year_search = "";
    $selected_course_id = "";
    $selected_school_year_id = "";
    $selected_student_subject_id = "";
    $selected_course_level = "";

    $hasClicked = false;
    
    $get_period = NULL;
    $get_term = NULL;


    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");
 

    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){

        
        $selected_school_year_id = $_POST['school_year_id'] ?? NULL;
        $selected_program_id = $_POST['program_id'] ?? NULL;
        $selected_course_level = $_POST['course_level'] ?? NULL;

        $school_year = new SchoolYear($con, $selected_school_year_id);

        $get_term = $school_year->GetTerm();
        $get_period = $school_year->GetPeriod();


        $program = new Program($con, $selected_program_id);
        $program_name = $program->GetProgramName();


        $selected_course_id = $_POST['course_id'] ?? NULL;
        $selected_student_subject_id = $_POST['student_subject_id'] ?? NULL;

        $hasClicked = true;

        // $redirectUrl = "enrollmentListData.php?sy_id=$sy_id&p_id=$selected_program_id&c_id=$selected_course_id";
        // header("Location: $redirectUrl");

    }
    
    // echo "selected_course_level: $selected_course_level";

    if(isset($_POST['reset_btn'])){

        $selected_school_year_id = NULL;
        $selected_program_id = NULL;
        $selected_student_subject_id = NULL;
        $selected_course_level = NULL;
        
    }


?>

    <div class="content">
     
        <div class="content-header">

            <header>
                <div class="title">
                <h1>Class List <em>SHS | Tertiary</em></h1>
                <small
                    >Note: Numbers on tabs only count current school year and
                    semester</small
                >
                </div>
                <h5><?php echo $current_school_year_term; ?> <span><?php echo $period_short; ?></span></h5>

            </header>
        </div>

        <div class="tabs">
            <button
                class="tab"
                id="shsEvaluation"
                style="background-color: var(--them)"
                onclick="window.location.href = 'index.php';"
            >
                Students per Instructor 
            </button>
                
            <button
                class="tab"
                id="shsPayment"
                style="background-color: var(--mainContentBG);"
                onclick="window.location.href = 'class_list_by_section.php';"
            >
                Subjects per Section 
            </button>
            
            <button
                class="tab"
                id="shsApproval"
                style="background-color: var(--them); color: white"
                onclick="window.location.href = 'student_per_section.php';"
            >
                Students per Section 
            </button>

            <!-- <button
                class="tab"
                id="shsApproval"
                style="background-color: var(--them); color: white"
                onclick="window.location.href = 'student_per_subject.php';"
            >
                Students Per Subject 
            </button> -->
        
        </div>
        
        <br>

        <div class="col-lg-12">

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
                                        if($selected_school_year_id == $school_year_id){
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
                        Program(s)

                        <select name="program_id" id="program_id" class="form-control">
                            <?php 
                                $query = $con->prepare("SELECT t1.*

                                    FROM program AS t1
                                ");

                                // $query->bindParam(":condition2", $Tertiary);
                                $query->execute();
                                if($query->rowCount() > 0){

                                    echo "
                                        <option value='' selected>Select</option>
                                    ";

                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                        $program_name = $row['program_name'];
                                        $acronym = $row['acronym'];
                                        $program_id = $row['program_id'];

                                        $selected = "";
                                        if($selected_program_id == $program_id){
                                            // $selected = "selected";
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
                        Academic Level

                        <select name="course_level" id="course_level" class="form-control">
                        </select>
                        <input type="hidden" id="course_level_value" name="course_level_value">
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

                            <!-- <input type="hidden" id="student_subject_grade_id"> -->

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
            
            <div class="content">
                <main>
                    <header>

                        <div class="title">

                            <div class="row col-md-12">

                                <div class="col-md-6">
                                    <h4 style="margin-bottom: 13px;" id="clickSchedule">Subject list by Section</h4>
                                </div>

                                <div class="text-right col-md-6">

                                    <?php 
                                        if($selected_school_year_id != "" 
                                            && ($selected_program_id != "" 
                                                || $selected_course_id != "")){

                                            ?>
                                                <form action='print_classlist_by_section.php' 
                                                    method='POST'>

                                                    <input type="hidden" name="selected_school_year_id" id="selected_school_year_id" value="<?php echo $selected_school_year_id;?>">
                                                    <input type="hidden" name="selected_program_id" id="selected_program_id" value="<?php echo $selected_program_id;?>">
                                                    <input type="hidden" name="selected_course_id" id="selected_course_id" value="<?php echo $selected_course_id;?>">
                                                    <input type="hidden" name="selected_course_level" id="selected_course_level" value="<?php echo $selected_course_level;?>">
                                                
                                                    <button title="Export as pdf" style="cursor: pointer;"
                                                        type='submit' 
                                                        
                                                        href='#' name="print_classlist_by_section"
                                                        class='btn-sm btn btn-primary'>
                                                        <i class='bi bi-file-earmark-x'></i>&nbsp Print
                                                    </button>

                                                    <button style="cursor: pointer;"
                                                        type='submit' 
                                                        title="Export as excel"
                                                        href='#' name="print_excel"
                                                        class='btn-sm btn btn-success'>
                                                        <i class='bi bi-file-earmark-x'></i>&nbsp Excel
                                                    </button>

                                                </form>
                                            <?php
                                        }
                                    ?>
                                </div>

                            </div>
                            
                            

                        </div>
                        
                    </header>

                    <?php if($selected_program_id !== NULL):?>

                        <?php 

                            $course_query = "";
                            $course_level_query = "";

                            if($selected_course_id != ""){
                                $course_query = "AND t1.course_id = :course_id";
                            }

                            if($selected_course_level != ""){
                                $course_level_query = "AND t3.course_level = :course_level";
                            }

                            $get = $con->prepare("SELECT 

                                t1.student_subject_id,
                                t1.course_id,
                                t3.program_section
                                
                                FROM student_subject as t1

                                -- INNER JOIN student_subject as t2 ON t2.student_subject_id = t1.student_subject_id
                                INNER JOIN course as t3 ON t3.course_id = t1.course_id

                                AND t3.program_id=:program_id
                                AND t1.is_final = 1
                                AND t1.school_year_id=:school_year_id
                                $course_query
                                $course_level_query

                                GROUP BY t1.course_id
                            ");

                            $get->bindValue(":program_id", $selected_program_id);
                            $get->bindValue(":school_year_id", $selected_school_year_id);
                            
                            if($selected_course_id != ""){
                                $get->bindValue(":course_id", $selected_course_id);
                            }
                            if($selected_course_level != ""){
                                $get->bindValue(":course_level", $selected_course_level);
                            }

                            
                            $get->execute();

                            if($get->rowCount() > 0){


                                $sectionsByProgramList = $get->fetchAll(PDO::FETCH_ASSOC);

                                // echo "Count: " . $get->rowCount();
                                // echo "<br>";
                                // return;

                                foreach ($sectionsByProgramList as $key => $value) {

                                    # code...

                                    $enrolled_course_id = $value['course_id'];
                                    $section = new Section($con, $enrolled_course_id);

                                    $sectionName = $section->GetSectionName();
                                    $enrolled_course_level = $section->GetSectionGradeLevel();
                                    $enrolled_course_capacity = $section->GetSectionCapacity();
                                    $enrolled_course_program_id = $section->GetSectionProgramId($enrolled_course_id);

                                    ?>
                                        <div class="floating">


                                            <em style="margin-bottom: 28px;" >Class section &nbsp;  </em> <span style="font-weight: bold;"><?php
                                                echo "$sectionName <br>";
                                            ?></span>


                                            <table id="" class="a" style="margin-bottom: 0px; margin-top:15px;">
                                            
                                                <thead>

                                                    <tr class="text-center"> 
                                                        <th>Code</th>
                                                        <th>Description</th>
                                                        <th>Days</th>
                                                        <th>Time</th>
                                                        <th>Room</th>
                                                        <th>Instructor</th>
                                                        <th>Capacity</th>
                                                        <th>Total</th>
                                                        <th>Action</th>
                                                    </tr>

                                                </thead>
                                                
                                                <tbody>
                                                    <?php 
                                                    
                                                        
                                                    $sql = $con->prepare("SELECT 
                                                    
                                                        DISTINCT t1.subject_title
                                                        ,t1.subject_program_id
                                                        ,t1.pre_req_subject_title
                                                        ,t1.subject_type
                                                        ,t1.course_level
                                                        ,t1.semester
                                                        ,t1.unit
                                                        ,t1.subject_code
                                                        
                                                        ,t2.program_section, t2.course_id,
                                                        -- t3.subject_code AS student_subject_code,

                                                        t4.subject_code AS schedule_code,

                                                        t4.time_to,
                                                        t4.time_from,
                                                        t4.schedule_time,
                                                        t4.schedule_day,
                                                        t4.room_id,
                                                        t4.subject_schedule_id,
                                                        t4.course_id AS schedule_course_id,

                                                        t5.teacher_id,
                                                        t5.firstname,
                                                        t5.lastname

                                                        ,t6.room_number


                                                        FROM subject_program as t1
                                                        
                                                        INNER JOIN course as t2 ON t2.program_id = t1.program_id

                                                        -- LEFT JOIN student_subject as t3 ON t3.course_id = t2.course_id
                                                        -- AND t3.subject_program_id = t1.subject_program_id



                                                        LEFT JOIN subject_schedule as t4 ON t4.course_id = t2.course_id
                                                        AND t4.subject_program_id = t1.subject_program_id

                                                        LEFT JOIN teacher as t5 ON t5.teacher_id = t4.teacher_id


                                                        LEFT JOIN room as t6 ON t6.room_id = t4.room_id


                                                        WHERE t2.course_id=:course_id
                                                        AND t1.semester=:semester
                                                        AND t1.program_id=:program_id
                                                        AND t1.course_level=:course_level

                                                        ORDER BY t1.subject_title DESC
                                                        
                                                    ");
                                                    
                                                    $sql->bindParam(":program_id", $enrolled_course_program_id);
                                                    $sql->bindParam(":course_level", $enrolled_course_level);
                                                    $sql->bindParam(":semester", $get_period);
                                                    $sql->bindParam(":course_id", $enrolled_course_id);
                                                    
                                                    $sql->execute();

                                                    if($sql->rowCount() > 0){

                                                        // $check  = $sql->fetchAll(PDO::FETCH_ASSOC);

                                                        // var_dump(count($check));
                                                        // return;


                                                        $subject_titles_occurrences = [];
                                                        $subject_code_occurrences = [];
                                                        $room_occurrences = [];
                                                        $teacher_fullname_occurrences = [];
                                                        $days_occurrences = [];

                                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                            $course_id = $row['course_id'];
                                                            $section = new Section($con, $course_id);
                                                            $program_section = $row['program_section'];
                                                            $subject_code = $row['subject_code'];

                                                            $section_subject_code = $section->CreateSectionSubjectCode(
                                                                $program_section, $subject_code
                                                            );

                                                            $subject_title = $row['subject_title'];

                                                            $schedule_day = $row['schedule_day'] ?? "-";
                                                            $room_number = $row['room_number'] ?? "-";

                                                            $teacher_id = $row['teacher_id'];
                                                            $teacherFullName = $row['teacher_id'] != 0 ? ucfirst($row['firstname']) . " " . ucfirst($row['lastname']) : "-";

                                                            $schedule->filterSubsequentOccurrences($subject_titles_occurrences, $subject_title);
                                                            $schedule->filterSubsequentOccurrences($subject_code_occurrences, $subject_code);
                                                            
                                                            $subject_program_id = $row['subject_program_id'];
                                                            $course_level = $row['course_level'];
                                                            $semester = $row['semester'];
                                                            $unit = $row['unit'];
                                                            $pre_requisite = $row['pre_req_subject_title'];
                                                            $subject_type = $row['subject_type'];
                                                            $subject_program_id = $row['subject_program_id'];

                                                            $time_to = $row['time_to'];
                                                            $time_from = $row['time_from'];
                                                            $schedule_time = $row['schedule_time'] ?? "-";

                                                            $schedule_course_id = $row['schedule_course_id'];
                                                            $subject_schedule_id = $row['subject_schedule_id'];

                                                            $haveSchedule = "";

                                                            $statuss = "N/A";

                                                            $add_schedule_url = "add_schedule_code.php?sp_id=$subject_program_id&id=$course_id";
                                                            $edit_schedule_url = "edit_schedule_code.php?s_id=$subject_schedule_id";


                                                            $subject_enrolled_url = "";

                                                            $subject_program = new SubjectProgram($con);

                                                            $student_subject_enrolled = $subject_program->GetSectionSubjectEnrolledStudents($subject_program_id,
                                                                $course_id, $section_subject_code, $selected_school_year_id);

                                                            $student_subject_enrolled = $student_subject_enrolled == 0 ? "" : $student_subject_enrolled;
                                                            

                                                        
                                                            echo "
                                                                <tr class='text-center'>
                                                                    <td>
                                                                        <a style='color: #333' href=''>
                                                                            $subject_code
                                                                        </a>
                                                                    </td>
                                                                    <td>$subject_title</td>
                                                                    <td>$schedule_day</td>
                                                                    <td>$schedule_time</td>
                                                                    <td>$room_number</td>
                                                                    
                                                                    <td>$teacherFullName</td>
                                                                    <td>$enrolled_course_capacity</td>
                                                                    <td>$student_subject_enrolled</td>
                                                                    <td>
                                                                        <a href='student_per_subject_list.php?id=$course_id&sy_id=$selected_school_year_id&code=$subject_code' target='_blank'>
                                                                            <button title='View student enrolled' class='btn btn-sm btn-primary'>
                                                                                <i class='fas fa-eye'></i>
                                                                            </button>
                                                                        </a>
                                                                    </td>
                                                                
                                                                </tr>
                                                            ";

                                                        }
                                                    }
                                                    
                                                    ?>
                                                </tbody>
                                        
                                            </table>

                                        </div>
                                    <?php
                                }
                            }
                        ?>

                    <?php endif;?>

                </main>
            </div>

        </div>

    </div>

    </div>



<script>



// $('#program_id').on('change', function() {

//     var program_id = parseInt($(this).val());
//     var chosen_school_year_id = parseInt($("#school_year_id").val());

//     $.ajax({
//         url: '../../ajax/grade/get_program_section.php',
//         type: 'POST',
//         data: {
//                 program_id,
//             chosen_school_year_id
//         },
//         dataType: 'json',

//         success: function(response) {

//             // response = response.trim();

//             console.log(response);

//             if(response.length > 0){
//                 var options = '<option selected value="">Available Sections</option>';
                
//                 $.each(response, function (index, value) {
//                     options +=
//                     '<option value="' + value.course_id + '">' + value.program_section + '</option>';
//                 });

//                 $('#course_id').html(options);
//                 // $('#student_subject_id').val(options);
                
//             }else{
//                 $('#course_id').html('<option selected value="">No data found(s).</option>');

//             }
//         },
//         'error': function(xhr, status, error) {
//             // Handle error response here
//             console.error('Error:', error);
//             console.log('Status:', status);
//             console.log('Response Text:', xhr.responseText);
//             console.log('Response Code:', xhr.status);
//         }
//     });

// });

let program_id_value = null;

$('#program_id').on('change', function() {

    let program_id = parseInt($(this).val());
    // var chosen_school_year_id = parseInt($("#school_year_id").val());

    program_id_value = program_id;
    
    // console.log("program_id_value "  +  program_id_value)
    // console.log("program_id " + program_id)
    
    $.ajax({
        url: '../../ajax/classlist/populate_academic_level_by_program.php',
        type: 'POST',
        data: {
            program_id
            // chosen_school_year_id
        },
        dataType: 'json',

        success: function(response) {

            var options = '<option value="">Choose Level</option>';

            $.each(response, function(index, value) {

                if(value.level > 5){
                    options += '<option value="' + value.level + '">Grade ' + value.level +'</option>';
                }

                else if(value.level <= 4){
                    var yearLabel;
                    switch (value.level) {
                        case "1":
                            yearLabel = "1st year";
                            break;
                        case "2":
                            yearLabel = "2nd year";
                            break;
                        case "3":
                            yearLabel = "3rd year";
                            break;
                        case "4":
                            yearLabel = "4th year";
                            break;
                        default:
                            yearLabel = value.level + "th year";
                    }
                    options += '<option value="' + value.level + '">' + yearLabel + '</option>';
                }
            });

            $('#course_level').html(options);
        },
        'error': function(xhr, status, error) {
            // Handle error response here
            console.error('Error:', error);
            console.log('Status:', status);
            console.log('Response Text:', xhr.responseText);
            console.log('Response Code:', xhr.status);
        }
    });

});


$('#course_level').on('change', function() {


    let course_level_value = parseInt($(this).val());

    let program_id = program_id_value;

    // console.log("program_id "  +  program_id)
    // console.log("course_level "  +  course_level_value)
    
    // var program_id = parseInt($(this).val());
    var chosen_school_year_id = parseInt($("#school_year_id").val());

    $.ajax({
        url: '../../ajax/grade/get_program_section.php',
        type: 'POST',
        data: {
            program_id,
            chosen_school_year_id,
            course_level_value,
            type: "class_list_per_section"

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
                // $('#student_subject_id').val(options);
                
            }else{
                $('#course_id').html('<option selected value="">No data found(s).</option>');

            }
        },
        'error': function(xhr, status, error) {
            // Handle error response here
            console.error('Error:', error);
            console.log('Status:', status);
            console.log('Response Text:', xhr.responseText);
            console.log('Response Code:', xhr.status);
        }
    });

});

 

</script>


