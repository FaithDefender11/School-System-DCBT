

<?php

use Random\Engine\Secure;

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
    $selected_academic_level = "";
    $selected_subject_id = "";
    $selected_school_year_id = "";
    $selected_student_subject_id = "";

    $hasClicked = false;
    
    $get_period = NULL;
    $get_term = NULL;

    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){

        

        $selected_school_year_id = $_POST['school_year_id'] ?? NULL;
        $selected_program_id = $_POST['program_id'] ?? NULL;
        $selected_subject_id = $_POST['subject_id'] ?? NULL;
        $selected_academic_level = $_POST['course_level'] ?? NULL;

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

    // echo "selected_subject_id: $selected_subject_id";
    // echo "<br>";

    if(isset($_POST['reset_btn'])){

        $selected_school_year_id = NULL;
        $selected_program_id = NULL;
        $selected_student_subject_id = NULL;
        $selected_academic_level = NULL;
        
    }


?>

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

                    <input type="hidden" name="program_id_value" id="program_id_value">
                </div>

                <div class="col-sm-3 invoice-col">
                    Academic Level

                    <select name="course_level" id="course_level" class="form-control">
                    </select>
                    <input type="hidden" id="course_level_value" name="course_level_value">
                </div>

                <!-- <div class="col-sm-3 invoice-col">
                    Program - Section
                        <select name="course_id" id="course_id"  class="form-control">
                            <?php 
                                // if($selected_course_id != "") {
                                //     $query = $con->prepare("SELECT t1.*
                                //         FROM course AS t1
                                //         WHERE t1.course_id=:course_id
                                //     ");

                                //     $query->bindParam(":course_id", $selected_course_id);
                                //     $query->execute();

                                //     if($query->rowCount() > 0){

                                //         $row = $query->fetch(PDO::FETCH_ASSOC);

                                //         $program_section = $row['program_section'];
                                //         // $acronym = $row['acronym'];
                                //         $course_id = $row['course_id'];

                                //         $selected = "";
                                //         if($selected_course_id == $course_id){
                                //             $selected = "selected";
                                //         }
                                //         echo "
                                //             <option $selected value='$course_id'>$program_section</option>
                                //         ";
                                //     }   
                                // }
                            ?>
                        </select>
                </div> -->

                <div class="col-sm-3 invoice-col">
                    Subject
                        <select name="subject_id" id="subject_id"  class="form-control">
                        
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

                            <h4 style="margin-bottom: 13px;" id="clickSchedule">Student list by Subject
                                <?php 
                                
                                    if($selected_subject_id != ""){

                                        // var_dump($selected_subject_id);

                                        $subjectProgram = new SubjectProgram($con, $selected_subject_id);
                                        $subject_title = $subjectProgram->GetTitle();
                                        echo "<span style='font-size: 18px;font-weight:bold;'>($subject_title)</span>";
                                    }
                                    
                                ?>
                            </h4>

                            <?php 
                                $schedule_time = "08:00 AM - 10:00 AM";
                                $schedule_day = "Monday";

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

                    <?php if($selected_program_id !== NULL):?>

                        <?php 

                            // var_dump($selected_course_id); 

                            $course_query = "";
                            $academic_level_query = "";
                            
                            if($selected_course_id != ""){
                                $course_query = "AND t1.course_id = :course_id";
                            }
                            if($selected_academic_level != ""){
                                $academic_level_query = "AND t3.course_level = :course_level";
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
                                $academic_level_query

                                GROUP BY t1.course_id
                            ");

                            $get->bindValue(":program_id", $selected_program_id);
                            $get->bindValue(":school_year_id", $selected_school_year_id);
                            
                            if($selected_course_id != ""){
                                $get->bindValue(":course_id", $selected_course_id);
                            }
                            if($selected_academic_level != ""){
                                $get->bindValue(":course_level", $selected_academic_level);
                            }
                            
                            $get->execute();

                            if($get->rowCount() > 0){

                                // echo "Count: " . $get->rowCount();
                                // echo "<br>";


                                $sectionsByProgramList = $get->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($sectionsByProgramList as $key => $value) {

                                    # code...

                                    $enrolled_course_id = $value['course_id'];
                                    $section = new Section($con, $enrolled_course_id);

                                    $sectionName = $section->GetSectionName();
                                    $enrolled_course_level = $section->GetSectionGradeLevel();
                                    $enrolled_course_capacity = $section->GetSectionCapacity();
                                    $enrolled_course_program_id = $section->GetSectionProgramId($enrolled_course_id);

                                    $subjectProgram = new SubjectProgram($con, $selected_subject_id);

                                    $rawCode = $subjectProgram->GetSubjectProgramRawCode();

                                    $section_subject_code = $section->CreateSectionSubjectCode($sectionName, $rawCode);

                                    ?>

                                    <em style="margin-bottom: 28px;" >Code &nbsp; &nbsp; &nbsp; </em> <span style="font-weight: bold;"><?php
                                        echo "$section_subject_code <br>";
                                    ?></span>


                                    <table id="" class="a" style="margin-bottom: 0px; margin-top:15px;">
                                    
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
                                                

                                                     
                                                    FROM student as t3 

                                                    INNER JOIN student_subject as t6 ON t6.student_id = t3.student_id
                                                    AND t6.is_final = 1
                                                    AND t6.subject_code = :subject_code

                                                
                                                    LEFT JOIN course as t4  ON t4.course_id = t3.course_id
                                                    LEFT JOIN program as t5  ON t5.program_id = t4.program_id
                                                    
                                                    -- WHERE t3.course_id = :course_id
                                                    -- AND t2.is_final = :is_final
                                                    
                                                ");

                                                // $query->bindValue(":course_id", $enrolled_course_id);
                                                $query->bindValue(":subject_code", $section_subject_code);
                                                // $query->bindValue(":is_final", 1);

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
                                
                                    </table>

                                    <br>
                                    <br>
                                    <?php
                                }

                            }

                        
                            $query  = $con->prepare("SELECT 

                                t1.enrollment_approve,

                                t3.program_id, t2.student_id,
                                t3.program_section,

                                t2.active,
                                t2.firstname,
                                t2.lastname,

                                t2.student_unique_id,
                                t2.admission_status,
                                t2.student_statusv2,
                                t5.term,
                                t5.period
                                
                                FROM enrollment as t1

                                INNER JOIN student as t2 ON t2.student_id=t1.student_id
                                INNER JOIN course as t3 ON t3.course_id=t1.course_id
                                INNER JOIN program as t4 ON t4.program_id=t3.program_id
                                INNER JOIN school_year as t5 ON t5.school_year_id=t1.school_year_id
                        
                                WHERE t1.enrollment_status = 'enrolled'
                                AND t1.course_id = :course_id
                                AND t1.school_year_id = :school_year_id
                                AND t4.program_id = :program_id

                            ");

                            $query->bindValue(":course_id", $selected_course_id);
                            $query->bindValue(":school_year_id", $selected_school_year_id);
                            $query->bindValue(":program_id", $selected_program_id);
                            $query->execute();


                            if($query->rowCount() > 0){


                                $getAll = $query->fetchAll(PDO::FETCH_ASSOC);


                                foreach ($getAll as $key => $value) {
                                    
                                }
                            }
                        ?>


                        

                    <?php endif;?>
                </main>
            </div>

        </main>
    </div>

    
<script>

    $('#program_id').on('change', function() {

        var program_id = parseInt($(this).val());
        var chosen_school_year_id = parseInt($("#school_year_id").val());

        $.ajax({
            url: '../../ajax/grade/get_program_section.php',
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

    // GET LEVEL.
    let program_id_value = null;

    $('#program_id').on('change', function() {

        var program_id = parseInt($(this).val());
        // var chosen_school_year_id = parseInt($("#school_year_id").val());

        program_id_value = program_id;
        
        
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
    
    let selectedValue = null;

    // $('#course_level').on('change', function() {
    //     var selectedValue = $(this).val();

    //     $("#course_level_value").val(selectedValue);

    //     // console.log('Selected value:', selectedValue);
    // });


    $('#course_level').on('change', function() {


        var course_level = parseInt($(this).val());
        // var program_id = parseInt($(this).val());


        var chosen_school_year_id = parseInt($("#school_year_id").val());

        var program_id = program_id_value;

        // console.log('Selected value:', course_level);

        $.ajax({

            // classlist/populate_sy_by_teacher.php
            url: '../../ajax/classlist/populate_subject_by_program.php',

            type: 'POST',
            data: {
                program_id,
                chosen_school_year_id,
                course_level
            },
            dataType: 'json',

            success: function(response) {

                // response = response.trim();

                console.log(response);

                if(response.length > 0){
                    var options = '<option selected value="">Pick Subject</option>';
                    
                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.subject_program_id + '">' + value.subject_title + '</option>';
                    });

                    $('#subject_id').html(options);
                    // $('#student_subject_id').val(options);
                    
                }else{
                    $('#subject_id').html('<option selected value="">No data found(s).</option>');

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


    // $('#program_id').on('change', function() {

    //     var program_id = parseInt($(this).val());
    //     var chosen_school_year_id = parseInt($("#school_year_id").val());

    //     var selectedValue = parseInt($("#course_level_value").val());

    //     console.log('Selected value:', selectedValue);

    //     $.ajax({

    //         // classlist/populate_sy_by_teacher.php
    //         url: '../../ajax/classlist/populate_subject_by_program.php',

    //         type: 'POST',
    //         data: {
    //             program_id,
    //             chosen_school_year_id
    //         },
    //         dataType: 'json',

    //         success: function(response) {

    //             // response = response.trim();

    //             console.log(response);

    //             if(response.length > 0){
    //                 var options = '<option selected value="">Pick Subject</option>';
                    
    //                 $.each(response, function (index, value) {
    //                     options +=
    //                     '<option value="' + value.subject_program_id + '">' + value.subject_title + '</option>';
    //                 });

    //                 $('#subject_id').html(options);
    //                 // $('#student_subject_id').val(options);
                    
    //             }else{
    //                 $('#subject_id').html('<option selected value="">No data found(s).</option>');

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


    $('#course_id').on('change', function() {

        var course_id = parseInt($(this).val());
        var chosen_school_year_id = parseInt($("#school_year_id").val());

        // console.log(chosen_school_year_id)
        $.ajax({

            // classlist/populate_sy_by_teacher.php
            url: '../../ajax/classlist/populate_subject.php',

            type: 'POST',
            data: {
                course_id,
                chosen_school_year_id
            },
            dataType: 'json',

            success: function(response) {

                // response = response.trim();

                console.log(response);

                if(response.length > 0){
                    var options = '<option selected value="">Pick Subject</option>';
                    
                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.subject_program_id + '">' + value.subject_title + '</option>';
                    });

                    $('#subject_id').html(options);
                    // $('#student_subject_id').val(options);
                    
                }else{
                    $('#subject_id').html('<option selected value="">No data found(s).</option>');

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


