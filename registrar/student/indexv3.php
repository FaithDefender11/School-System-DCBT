<?php 
    
    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Schedule.php');
 
    
    ?>
        <style>
            /* Hide the default select box */
            select#school_year_id {
                display: block;
            }

            /* Style for custom checkboxes */
            .custom-checkbox {
                display: flex;
                align-items: center;
            }

            .custom-checkbox input[type="checkbox"] {
                margin-right: 5px;
            }
            th a {
                text-decoration: underline;
                color: inherit; /* To maintain the link color */
                white-space: nowrap; /* Prevent text from wrapping */
            }
        </style>
    <?php

    $selected_school_year_id = "";
    $selected_student_id = "";
    $selected_student_id_input = "";

    $text_output = "Search Student Enrolled Subjects";
    $no_data_found = "No data found";

    $schedule = new Schedule($con);

    $selectedValuesArr = [];
    $storedValuesArr = [];

    $selectAllDisplay = "none";

    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){

        
        $selected_school_year_id = $_POST['school_year_id'] ?? NULL;
        $selected_student_id = $_POST['student_id'] ?? NULL;
        $selected_student_id_input = $_POST['student_id_input'] ?? NULL;


        $selectedValues = $_POST['school_year_id2'] ?? [];

       
        // $selectedValues is now an array containing the selected values
        if(count($selectedValues) > 0){

            $selectedValuesArr = $selectedValues;

            foreach ($selectedValues as $value) {
                // echo "Selected: $value<br>";
            }
        }

    }


    // echo "<br>";
    // var_dump($selectedValuesArr);
    // echo "<br>";


    // echo "selected_school_year_id: $selected_school_year_id";
    // echo "<br>";

    // echo "<br>";
    // echo "selected_student_id: $selected_student_id";
    // echo "<br>";

    

    echo "<br>";
    echo "selected_student_id_input: $selected_student_id_input";
    echo "<br>";


?>
 
    <div class="col-lg-12">

        <form method="POST">
            <div class="row invoice-info">

                <div class="col-sm-3 invoice-col">

                    <label for="student_id_input">Input Student Id</label> 

                    <input  value="<?= $selected_student_id_input;?>" name="student_id_input" id="student_id_input" class="form-control" type="text">

                </div>

                <div class="col-sm-3 invoice-col">
                    <label for="student_id">Student ID</label>
                    <select name="student_id" id="student_id" class="form-control">
                        <?php 
                            $query = $con->prepare("SELECT t1.*
                                FROM student AS t1

                                WHERE t1.course_id != 0

                            ");

                            // $query->bindParam(":condition2", $Tertiary);
                            $query->execute();

                            if($query->rowCount() > 0){

                                echo "
                                    <option value='' selected>Select Student</option>
                                ";

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $student_id = $row['student_id'];
                                    $firstname = $row['firstname'];
                                    $lastname = $row['lastname'];

                                    $course_id = $row['course_id'];

                                    $course = new Section($con, $course_id);
                                    $courseName = $course->GetSectionName();



                                    $fullName = ucwords($firstname) . " " . ucwords($lastname);

                                    $school_year_id = $row['school_year_id'];

                                    $selected = "";
                                    if($selected_student_id == $student_id){
                                        $selected = "selected";
                                    }
                                    echo "
                                        <option $selected value='$student_id'>$fullName ($courseName) </option>
                                    ";
                                }
                            }
                        ?>
                    </select>

                </div>
                
                <div class="col-sm-3 invoice-col">

                    <label for="school_year_id2">Academic Year</label> 

                    <select class="form-control" name="school_year_id2[]"
                        id="school_year_id2" multiple>

                        <?php 

                            # To get the values after the first form submit.

                            if(count($selectedValuesArr) > 0){

                                $query = $con->prepare("SELECT 
                                
                                    -- t1.*
                                    -- FROM school_year AS t1
                                    t1.school_year_id,
                                    t3.period,
                                    t3.term
                                    -- t1.enrollment_id

                                    FROM enrollment as t1

                                    INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
                                    AND t2.student_id=:student_id

                                    LEFT JOIN school_year as t3 ON t3.school_year_id = t1.school_year_id

                                    WHERE t1.enrollment_status = 'enrolled'

                                    GROUP BY t1.enrollment_id

                                ");

                                // $query->bindValue(":student_id", $selected_student_id);
                                $query->bindValue(":student_id", $selected_student_id_input);
                                $query->execute();

                                if ($query->rowCount() > 0) {

                                    $selectAllDisplay = "block";

                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                        $term = $row['term'];
                                        $period = $row['period'];
                                        $school_year_id = $row['school_year_id'];

                                        $period_shortcut = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");

                                            $selected = in_array($school_year_id, $selectedValuesArr) ? 'selected' : '';

                                        echo "<option $selected value='$school_year_id'>$term $period_shortcut</option>";
                                    
                                    }

                                } 
                            }else if(count($selectedValuesArr)  == 0 
                                && $selected_student_id_input != ""){
                                $text_output = $no_data_found;
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

                <button style="display: <?= $selectAllDisplay;?>;" class="btn btn-sm btn-primary" type="button" id="selectAllBtn">Select All</button>

            </div>

        </form>
        
        <div class="content">

                <h4 class="text-primary text-center"><?= $text_output;?></h4>

                <?php if($text_output != $no_data_found && $selected_student_id_input != ""): ?>

                    <form action='print_student_enrollment_forms.php' 
                        method='POST'>

 
                        <input type="hidden" name="selected_student_id_input" id="selected_student_id_input" value="<?php echo $selected_student_id_input;?>">
                        
                        <?php 
                        
                            $selectedValuesArrJSON = json_encode($selectedValuesArr);

                        ?>
                        <!-- <input type="hidden" name="selectedValuesArr" id="selectedValuesArr" value="<?php echo $selectedValuesArrJSON;?>"> -->
                        <input type="hidden" name="selectedValuesArr" id="selectedValuesArr" value="<?php echo htmlspecialchars(json_encode($selectedValuesArr), ENT_QUOTES, 'UTF-8'); ?>">

                        <!-- <input type="hidden" name="selectedValuesArr[]" id="selectedValuesArr" value="<?php echo "";?>"> -->

                        <button title="Export as pdf" style="cursor: pointer;"
                            type='submit' 
                            
                            href='#' name="print_student_enrollment_forms"
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
                <?php endif;?>


                <main>

                    <?php 

                        $school_year_id_query = "";

                        if($selected_school_year_id != ""){
                            $school_year_id_query = "AND t2.school_year_id = :school_year_id";
                        }

                        if(count($selectedValuesArr) > 0){


                            $get2 = $con->prepare("SELECT 
                                    
                                t1.*
                                -- t1.enrollment_id
        
                                FROM enrollment as t1

                                INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
                                AND t2.student_id=:student_id
                                AND t2.school_year_id = :school_year_id
        
                                WHERE t1.enrollment_status = 'enrolled'

                                GROUP BY t1.enrollment_id

                            ");

                            foreach ($selectedValuesArr as $key => $sy_ids) {
                                # code...

                                $sy_ids = intval($sy_ids);

                                $get2->bindValue(":student_id", $selected_student_id_input);
                                $get2->bindValue(":school_year_id", $sy_ids);

                                $get2->execute();

                                if($get2->rowCount() > 0){
                                    
                                    $get2All = $get2->fetchAll(PDO::FETCH_ASSOC);

                                    // var_dump($get2All);

                                    foreach ($get2All as $key => $value) {
                                        # code...

                                        $enrollment_student_id = $value['student_id'];
                                        $enrollment_enrollment_form_id = $value['enrollment_form_id'];
                                        $enrollment_school_year_id = $value['school_year_id'];

                                        ?>

                                            <div class="floating"> 

                                                <header>

                                                    <div class="title">     
                                                        <h4>Enrollment Form <?= "$enrollment_enrollment_form_id";?></h4>
                                                    </div>

                                                </header>

                                                <main>
                                                    <table class="a"  > 
                                                        <thead style="font-size:14px" >
                                                            <tr>
                                                                <th>Course Description</th>
                                                                <th>Code</th>
                                                                <th>Unit</th>
                                                                <th>Section</th>
                                                                <th>Days</th>
                                                                <th>Time</th>
                                                                <th>Room</th>
                                                                <th>Instructor</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody style="font-size: 13px;">
                                                            
                                                            <?php 
                                                            
                                                                $sql = $con->prepare("SELECT 
                                                                
                                                                    t1.*,
                                                                    t3.course_id as course_course_id, t3.program_section,

                                                                    -- t4.student_subject_id AS graded_student_subject_id,
                                                                    -- t4.first,
                                                                    -- t4.second,
                                                                    -- t4.third,
                                                                    -- t4.fourth,
                                                                    -- t4.remarks,


                                                                    t5.subject_title,
                                                                    t5.unit,
                                                                    t5.subject_code,
                                                                    t5.subject_program_id,

                                                                    t6.schedule_day,
                                                                    t6.schedule_time,


                                                                    t7.room_number,

                                                                    t8.firstname,
                                                                    t8.lastname

                                                            
                                                                    FROM enrollment as t1

                                                                    INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
                                                                    AND t2.student_id=:t2_student_id
                                                                    AND t2.school_year_id=:t2_school_year_id

                                                                    
                                                                    LEFT JOIN course as t3 ON t3.course_id = t2.course_id

                                                                    -- LEFT JOIN student_subject_grade as t4 ON t4.student_subject_id = t2.student_subject_id

                                                                    LEFT JOIN subject_program as t5 ON t5.subject_program_id = t2.subject_program_id

                                                                    LEFT JOIN subject_schedule as t6 ON t6.subject_code = t2.subject_code
                                                                    LEFT JOIN room as t7 ON t7.room_id = t6.room_id

                                                                    LEFT JOIN teacher as t8 ON t8.teacher_id = t6.teacher_id


                                                                    -- WHERE t1.school_year_id=:school_year_id
                                                                    -- AND t1.student_id=:student_id

                                                                    WHERE t1.enrollment_status = 'enrolled'

                                                                ");
                                                                
                                                                $sql->bindValue(":t2_school_year_id", $enrollment_school_year_id);
                                                                $sql->bindValue(":t2_student_id", $enrollment_student_id);
                                                                            
                                                                $sql->execute();

                                                                if($sql->rowCount() > 0){

                                                                    // echo "hey";

                                                                    // $results = $sql->fetchAll(PDO::FETCH_ASSOC);

                                                                    // echo "enrollment_id: $enrollment_id";
                                                                    // echo "<br>";

                                                                    $subject_titles_occurrences = [];
                                                                    $subject_code_occurrences = [];
                                                                    $unit_occurrences = [];

                                                                    while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                                                                        $enrollment_id = $row['enrollment_id'];
                                                                        
                                                                        $course_course_id = $row['course_course_id'];

                                                                        $subject_program_id = $row['subject_program_id'];

                                                                        

                                                                        $subject_title = $row['subject_title'];
                                                                        $subject_code = $row['subject_code'];
                                                                        $unit = $row['unit'];

                                                                        $program_section = $row['program_section'];
                                                                        
                                                                        $schedule_day = $row['schedule_day'] == NULL ? "-" : $row['schedule_day'];

                                                                        // var_dump($schedule_day);
                                                                        $schedule_time = $row['schedule_time'] == NULL ? "-" : $row['schedule_time'];
                                                                        $room_number = $row['room_number'] == NULL ? "-" : $row['room_number'];

                                                                        
                                                                        $firstname = $row['firstname'] == NULL ? "" : $row['firstname'];
                                                                        $lastname = $row['lastname'] == NULL ? "" : $row['lastname'];
                                                                        
                                                                        $teacherFullname = ucwords($firstname) . " " . ucwords($lastname);
                

                                                                        $schedule->filterSubsequentOccurrences($subject_titles_occurrences, $subject_title);
                                                                        $schedule->filterSubsequentOccurrences($subject_code_occurrences, $subject_code);
                                                                        
                                                                        # Can occur bug.
                                                                        $schedule->filterSubsequentOccurrencesSa($unit_occurrences, $unit,
                                                                            $course_course_id, $subject_program_id);

                                                                        echo "

                                                                            <tr class='text-center'>
                                                                            
                                                                                <td>$subject_title</td>
                                                                                <td>$subject_code</td>
                                                                                <td>$unit</td>
                                                                                <td>$program_section</td>
                                                                                <td>$schedule_day</td>
                                                                                <td>$schedule_time</td>
                                                                                <td>$room_number</td>
                                                                                <td>$teacherFullname</td>
                                                                            
                                                                            </tr>

                                                                        ";

                                                                    }
                                                                }else{
                                                                    echo "nothing";
                                                                }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </main>

                                            </div>

                                        <?php

                                    }

                                }else{
                                    // echo "nothibg";
                                    $text_output = $no_data_found;
                                }
                            }

                        }
    
                    ?>

                </main>


        </div>

    </div>

<script>


    document.addEventListener("DOMContentLoaded", function() {
        
        const selectAllBtn = document.getElementById("selectAllBtn");
        const schoolYearSelect = document.getElementById("school_year_id2");

        selectAllBtn.addEventListener("click", function() {
            // Loop through all options and set them as selected
            for (let i = 0; i < schoolYearSelect.options.length; i++) {
                schoolYearSelect.options[i].selected = true;
            }
        });
    });



    $('#student_id_input').on('input', function() {

        let student_id = parseInt($(this).val());

        // let student_id = `
        //     <?php echo $selected_student_id_input; ?>
        // `;

        // console.log(student_id)

        $.ajax({
            url: '../../ajax/student/getAllEnrollmentSchoolYearViaInput.php',
            type: 'POST',
            data: {
                student_id
            },
            dataType: 'json',

            // response = response.trim();
            // console.log(response);

            success: function(response) {

                if(response.length > 0){
                    var options = '<option  value="" disabled >Available School Year</option>';
                    
                    // Storing the values from client side into my php array
                    // $storedValuesArr = response;

                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.school_year_id + '">' + value.term + ' ' + value.period + '</option>';
                    });

                    let buttonTopopulate = `
                        <button class="btn btn-sm btn-primary" type="button" id="selectAllBtn">Select All</button>
                    `;


                    $('#school_year_id2').html(options);
                    $('#selectAllBtn').html(buttonTopopulate).css('display', 'block');

                    
                    // $('#student_subject_id').val(options);
                    
                }else{
                    $('#school_year_id2').html('<option disabled >No data found(s).</option>');

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


    $('#student_id').on('change', function() {


        let student_id = parseInt($(this).val());

        $.ajax({
            url: '../../ajax/student/getAllEnrollmentSchoolYear.php',
            type: 'POST',
            data: {
                student_id
            },
            dataType: 'json',

            // response = response.trim();
            // console.log(response);

            success: function(response) {

                if(response.length > 0){
                    var options = '<option  value="" disabled >Available School Year</option>';
                    
                    // Storing the values from client side into my php array
                    // $storedValuesArr = response;

                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.school_year_id + '">' + value.term + ' ' + value.period + '</option>';
                    });

                    let buttonTopopulate = `
                        <button class="btn btn-sm btn-primary" type="button" id="selectAllBtn">Select All</button>
                    `;


                    $('#school_year_id2').html(options);
                    $('#selectAllBtn').html(buttonTopopulate).css('display', 'block');

                    
                    // $('#student_subject_id').val(options);
                    
                }else{
                    $('#school_year_id2').html('<option disabled selected >No data found(s).</option>');

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

