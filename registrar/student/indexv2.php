<?php 
    
    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Schedule.php');
 

    $selected_school_year_id = "";
    $selected_student_id = "";

    $schedule = new Schedule($con);


    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){

        
        $selected_school_year_id = $_POST['school_year_id'] ?? NULL;
        $selected_student_id = $_POST['student_id'] ?? NULL;


        $selectedValues = $_POST['school_year_id2'];

        // $selectedValues is now an array containing the selected values
        
        foreach ($selectedValues as $value) {
            echo "Selected: $value<br>";
        }

    }




    // echo "selected_school_year_id: $selected_school_year_id";
    // echo "<br>";

    // echo "selected_student_id: $selected_student_id";
    // echo "<br>";

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
        </style>
    <?php
?>
 
    <div class="col-lg-12">

        <form method="POST">
            <div class="row invoice-info">


                <div class="col-sm-3 invoice-col">
                    Student
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
                                        <option value='$school_year_id' >$term $period Semester</option>
                                    ";
                                   
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="col-sm-3 invoice-col">

                    Academic Year

                    <select name="school_year_id2[]" id="school_year_id2" multiple>
                        <?php 
                        $query = $con->prepare("SELECT t1.*
                            FROM school_year AS t1
                        ");

                        $query->execute();

                        if ($query->rowCount() > 0) {

                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $term = $row['term'];
                                $period = $row['period'];
                                $school_year_id = $row['school_year_id'];
                                echo "<option value='$school_year_id'>$term $period Semester</option>";
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

                <h4 class="text-primary text-center">Search Student Enrolled Subjects</h4>
                <?php 

                    $school_year_id_query = "";

                    if($selected_school_year_id != ""){
                        $school_year_id_query = "AND t2.school_year_id = :school_year_id";
                    }
 
                    $get = $con->prepare("SELECT 
                        
                        t1.*

                        -- ,t3.course_id, t3.program_section,

                        -- t4.student_subject_id AS graded_student_subject_id,
                        -- t4.first,
                        -- t4.second,
                        -- t4.third,
                        -- t4.fourth,
                        -- t4.remarks,


                        -- t5.subject_title,
                        -- t5.unit
                
                        FROM enrollment as t1

                        INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
                        AND t2.student_id=:student_id
                        $school_year_id_query

                        
                        -- LEFT JOIN course as t3 ON t3.course_id = t2.course_id
                        -- LEFT JOIN student_subject_grade as t4 ON t4.student_subject_id = t2.student_subject_id
                        -- LEFT JOIN subject_program as t5 ON t5.subject_program_id = t2.subject_program_id

                        WHERE t1.enrollment_status = 'enrolled'

                        GROUP BY t1.enrollment_id

                    ");
                        
                    $get->bindValue(":student_id", $selected_student_id);
                                
                    if($selected_school_year_id != ""){
                        $get->bindValue(":school_year_id", $selected_school_year_id);
                    }

                    $get->execute();

                    if($get->rowCount() > 0){
                        
                        $getAll = $get->fetchAll(PDO::FETCH_ASSOC);


                        foreach ($getAll as $key => $value) {
                            # code...

                            $enrollment_student_id = $value['student_id'];
                            $enrollment_enrollment_form_id = $value['enrollment_form_id'];
                            $enrollment_school_year_id = $value['school_year_id'];


                            ?>

                                <div class="floating" style="margin-top:2%;"> 
                                <header>

                                    <div class="title">     
                                        <h4>Enrollment Form <?= "$enrollment_enrollment_form_id";?></h4>
                                    </div>
                                </header>

                                    <table id="" class="a"  style="font-size:15px" > 
                                        <thead>
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

                                        <tbody>
                                            
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

                                                        // $first = $row['first'];
                                                        // $second = $row['second'];
                                                        // $third = $row['third'];
                                                        // $fourth = $row['fourth'];
                                                        // $remarks = $row['remarks'];
                                                        
                                                        // echo "enrollment_id: $enrollment_id";

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
                                    <br>

                                </div>
                            <?php

                        }
                    }
                    


                ?>



        </div>


    </div>
 

<script>

    // Add this JavaScript code to handle the checkboxes and selected options
    document.getElementById('school_year_id').addEventListener('change', function() {
        let select = this;
        let selectedOptions = Array.from(select.selectedOptions);
        
        // You can now use the 'selectedOptions' array to get the selected values
        console.log(selectedOptions.map(option => option.value));
    });

</script>
