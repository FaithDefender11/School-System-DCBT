

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Teacher.php');
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

    $selected_teacher_id = "";
    $selected_school_year_id = "";
    $selected_course_id  = "";
    $selected_subject_schedule_id  = "";

    // $selected_program_id = "";
    // $selected_student_subject_id = "";

    $hasClicked = false;
    
    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){
        

        $selected_teacher_id = $_POST['teacher_id'] ?? NULL;
        $selected_school_year_id = $_POST['school_year_id'] ?? NULL;
        $selected_subject_schedule_id = $_POST['subject_schedule_id'] ?? NULL;
            
        $hasClicked = true;
            
    }

    echo "selected_subject_schedule_id: $selected_subject_schedule_id";
    echo "<br>";

    if(isset($_POST['reset_btn'])){

        $selected_teacher_id = NULL;
        $selected_school_year_id = NULL;
        $selected_subject_schedule_id = NULL;
        
    }

?>

    <div class="col-lg-12">

        <form method="POST">
            <div class="row invoice-info">
               
                <div class="col-sm-3 invoice-col">

                    Select Instructor

                    <select name="teacher_id" 
                        id="teacher_id" class="form-control">

                        <?php 
                            $query = $con->prepare("SELECT t1.*
                                FROM teacher AS t1
                            ");

                            $query->execute();

                            if($query->rowCount() > 0){

                                echo "
                                    <option value='' selected>Choose from</option>
                                ";

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $teacher_id = $row['teacher_id'];
                                    $firstname = $row['firstname'];
                                    $lastname = $row['lastname'];

                                    $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                    $selected = "";

                                    if($selected_teacher_id == $teacher_id){
                                        $selected = "selected";
                                    }

                                    echo "
                                        <option $selected value='$teacher_id'>$fullname</option>
                                    ";

                                }
                            }
                        ?>
                    </select>

                </div>

                <div class="col-sm-3 invoice-col">
                    Academic Year

                    <select name="school_year_id" id="school_year_id" class="form-control">
                        <?php 
                            // $query = $con->prepare("SELECT t1.*
                            //     FROM school_year AS t1
                            // ");

                            // // $query->bindParam(":condition2", $Tertiary);
                            // $query->execute();
                            // if($query->rowCount() > 0){

                            //     echo "
                            //         <option value='' selected>Select Term</option>
                            //     ";

                            //     while($row = $query->fetch(PDO::FETCH_ASSOC)){

                            //         $term = $row['term'];
                            //         $period = $row['period'];
                            //         $school_year_id = $row['school_year_id'];

                            //         $selected = "";
                            //         if($selected_school_year_id == $school_year_id){
                            //             $selected = "selected";
                            //         }
                            //         echo "
                            //             <option $selected value='$school_year_id'>$term $period Semester</option>
                            //         ";
                            //     }
                            // }
                        ?>
                    </select>
                   
                </div>

                <div class="col-sm-3 invoice-col">
                    Subjects handled
 
                    <select name="subject_schedule_id"
                        id="subject_schedule_id" class="form-control">

                        <?php 
                            // $query = $con->prepare("SELECT 
                            
                            //     t1.subject_code,
                            //     t1.subject_schedule_id,

                            //     t2.teacher_id,

                            //     t3.term,
                            //     t3.period,

                            //     t4.room_number

                            //     FROM subject_schedule AS t1

                            //     INNER JOIN teacher as t2 ON t2.teacher_id = t1.teacher_id

                            //     LEFT JOIN school_year as t3 ON t3.school_year_id = t1.school_year_id
                            //     LEFT JOIN room as t4 ON t4.room_id = t1.room_id

                            // ");

                            // $query->execute();
                            // if($query->rowCount() > 0){

                            //     echo "
                            //         <option value='' selected>Select</option>
                            //     ";

                            //     while($row = $query->fetch(PDO::FETCH_ASSOC)){

                            //         $term = $row['term'];
                            //         $period = $row['period'];
                            //         $subject_code = $row['subject_code'];

                            //         $subject_schedule_id = $row['subject_schedule_id'];

                            //         $teacher_id = $row['teacher_id'];

                            //         $selected = "";

                            //         if($selected_teacher_id == $teacher_id){
                            //             $selected = "selected";
                            //         }

                            //         echo "
                            //             <option $selected value='$subject_schedule_id'>$subject_code</option>
                            //         ";
                            //     }
                            // }

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
    </div>

    <div class="content">

            <div class="floating">
                <main>
                    <header>
                        <div class="title">
                            <h4 class="mb-3" id="clickSchedule">Students list filter by Instructor </h4>
                            
                            <?php 

                                if($selected_subject_schedule_id !== NULL){



                                    $schedule = new Schedule($con, $selected_subject_schedule_id);


                                    
                                    $time_from = $schedule->GetTimeFrom();
                                    $time_to = $schedule->GetTimeTo();
                                    $schedule_time = $schedule->GetScheduleTime();
                                    // $schedule_school_year_id = $schedule->GetTeacherScheduleSchoolYear();


                                    $schedule_day = $schedule->GetScheduleDay();

                                    $subject_program_id = $schedule->GetSubjectProgramId();
                                    $schedule_course_id = $schedule->GetScheduleCourseId();
                                    $schedule_subject_code = $schedule->GetSubjectCode();


                                    $section = new Section($con, $schedule_course_id);

                                    $programName = $section->GetSectionName();

                                    $subjectProgram = new SubjectProgram($con, $subject_program_id);


                                    $rawCode = $subjectProgram->GetSubjectProgramRawCode();

                                    $teacher = new Teacher($con, $selected_teacher_id);

                                    $fullname = ucwords(trim($teacher->GetTeacherFirstName())) . " " . ucwords(trim($teacher->GetTeacherLastName()));
                                
                                    ?>

                                        <div class="container">
                                            <table style="max-width:100%" cellpadding="4" cellspacing="7" class="table">
                                                <thead>
                                                    <tr>
                                                        <th><label>Instructor :</label></th><th><label><?= $fullname; ?></label></th> 
                                                        <th></th>
                                                        <!-- <th>Day(s)/Time:</th><th><?= "$schedule_time / $schedule_day ($)";?></th> -->
                                                        <th>Day(s)/Time:</th><th>

                                                            <?php 
                                                            
                                                                $query  = $con->prepare("SELECT * 

                                                                    FROM subject_schedule as t1
                                                                    
                                                                    WHERE t1.subject_code=:subject_code
                                                                    AND t1.teacher_id=:teacher_id
                                                                ");

                                                                $query->bindValue(":subject_code", $schedule_subject_code);
                                                                $query->bindValue(":teacher_id", $selected_teacher_id);
                                                                $query->execute();

                                                                if($query->rowCount() > 0){

                                                                    $getAll = $query->fetchAll(PDO::FETCH_ASSOC);

                                                                    // var_dump($getAll);

                                                                    foreach ($getAll as $key => $value) {

                                                                        $schedule_time = $value['schedule_time'];
                                                                        $schedule_day = $value['schedule_day'];

                                                                        $schedule_day = $schedule->convertToDays($schedule_day);

                                                                        $schedule_school_year_id = $value['school_year_id'];

                                                                        $schedule_day = $schedule->convertToDays($schedule_day);

                                                                        # code...
                                                                        echo "$schedule_time / $schedule_day ($schedule_school_year_id)<br>";
                                                                    }

                                                                }
                                                            ?>

                                                        </th>
                                                       
                                                        
                                                        <!-- <th><label>Course/Year :</label></th><th colspan="2"><label></label></th>  -->
                                                    </tr>
                                                </thead>
                                                <thead> 
                                                    <tr><th><label>Subject :</label></th><th><label><?= $rawCode;?></label></th> 
                                                    <th></th>
                                                    <th><label>Program-Section :</label></th><th><label><?= $programName;?></label></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    <?php

                                }
                            ?>


                            <?php 
                                if($selected_course_id != "" 
                                    && $sy_id != "" 
                                    && $selected_program_id != ""
                                    && $selected_student_subject_id != ""){
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
                    </header>

               
                   <table id="student_per_instructor_table_list" class="a" style="margin: 0">
                        <thead>
                            <tr class="text-center"> 
                                <th>ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Contact No</th>
                                <th>Civil Status</th>
                                <th>Program</th>
                                <th>Level</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                            
                                $query = $con->prepare("SELECT 
                                
                                    t3.firstname,
                                    t3.lastname,
                                    t3.student_unique_id,
                                    t3.admission_status,
                                    t3.sex,
                                    t3.contact_number,
                                    t3.course_level,
                                    
                                    t3.civil_status,

                                    t4.program_section,

                                    t5.acronym
                                 
                                    FROM subject_schedule as t1

                                    INNER JOIN student_subject as t2 ON t2.subject_code = t1.subject_code

                                    INNER JOIN student as t3  ON t3.student_id = t2.student_id
                                   
                                    LEFT JOIN course as t4  ON t4.course_id = t3.course_id
                                    LEFT JOIN program as t5  ON t5.program_id = t4.program_id
                                    
                                    WHERE t1.subject_schedule_id = :subject_schedule_id
                                    AND t2.is_final = :is_final
                                    
                                ");

                                $query->bindValue(":subject_schedule_id", $selected_subject_schedule_id);
                                $query->bindValue(":is_final", 1);

                                $query->execute();

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $firstname = trim($row['firstname']);
                                    $lastname = trim($row['lastname']);

                                    $student_unique_id = trim($row['student_unique_id']);
                                    $contact_number = trim($row['contact_number']);
                                    $sex = trim($row['sex']);
                                    $program_section = trim($row['program_section']);
                                    $course_level = trim($row['course_level']);
                                   
                                    $civil_status = trim($row['civil_status']);
                                    $admission_status = trim($row['admission_status']);
                                    
                                    $acronym = trim($row['acronym']);


                                    $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                    // $removeDepartmentBtn = "removeDepartmentBtn($department_id)";
                                    echo "
                                        <tr>
                                            <td>$student_unique_id</td>
                                            <td>$fullname</td>
                                            <td>$sex</td>
                                            <td>$contact_number</td>
                                            <td>$civil_status</td>
                                            <td>$acronym</td>
                                            <td>$course_level</td>
                                            <td>$admission_status</td>
                                            
                                        </tr>
                                    ";
                                }

                            ?>
                        </tbody>
                        <!-- Your table body content here -->
                    </table>

                </main>
            </div>

        </main>
    </div>

<script>
 
    $('#teacher_id').on('change', function() {

        var teacher_id = parseInt($(this).val());
        
        $.ajax({
            url: '../../ajax/classlist/get_student_per_instructor_by_teacher.php',
            type: 'POST',
            data: {
                teacher_id
            },
            dataType: 'json',

            success: function(response) {

                // response = response.trim();

                console.log(response);

                if(response.length > 0){
                    var options = '<option selected value="">Subject list</option>';
                    
                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.subject_schedule_id + '">' + value.subject_code + ' ' + value.term + ' ' + value.period + ' Semester</option>';
                    });

                    $('#subject_schedule_id').html(options);
                    
                }else{
                    $('#subject_schedule_id').html('<option selected value="">No data found(s).</option>');

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

    $('#teacher_id').on('change', function() {

        var teacher_id = parseInt($(this).val());
        
        $.ajax({
            url: '../../ajax/classlist/populate_sy_by_teacher.php',
            type: 'POST',
            data: {
                teacher_id
            },
            dataType: 'json',

            success: function(response) {

                // response = response.trim();

                console.log(response);

                if(response.length > 0){
                    var options = '<option selected value="">Choose term</option>';
                    
                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.school_year_id + '"> '+ value.term + ' ' + value.period + ' Semester</option>';
                    });

                    $('#school_year_id').html(options);
                    
                }else{
                    $('#school_year_id').html('<option selected value="">No data found(s).</option>');

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

    $('#school_year_id').on('change', function() {

        var school_year_id = parseInt($(this).val());

        var teacher_id = parseInt($("#teacher_id").val());

        
        $.ajax({
            url: '../../ajax/classlist/get_student_per_instructor_by_sy.php',
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
                    var options = '<option selected value="">Subject list</option>';
                    
                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.subject_schedule_id + '">' + value.subject_code + ' ' + value.term + ' ' + value.period + ' Semester</option>';
                    });

                    $('#subject_schedule_id').html(options);
                    
                }else{
                    $('#subject_schedule_id').html('<option selected value="">No data found(s).</option>');

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


