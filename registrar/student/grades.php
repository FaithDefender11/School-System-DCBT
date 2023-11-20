<?php 
    
    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Program.php');
 

    $selected_school_year_id = "";
    $selected_student_id = "";

    $schedule = new Schedule($con);

    $student_program_id = "";
    $student_program = "";


    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['grade_record_btn'])){

        
        // $selected_school_year_id = $_POST['school_year_id'] ?? NULL;
        $selected_student_id = $_POST['student_id'] ?? NULL;
        
        $student = new Student($con, $selected_student_id);

        $student_course_id = $student->GetStudentCurrentCourseId();

        $section = new Section($con, $student_course_id);

        $student_program_id = $section->GetSectionProgramId($student_course_id);

        $program = new Program($con, $student_program_id);

        $student_program = $program->GetProgramAcronym();

    }
    echo "student_program_id: $student_program_id";
    echo "<br>";

    echo "selected_student_id: $selected_student_id";
    echo "<br>";



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
             
                <div class="col-sm-0 invoice-col"> 
                    <br>
                    <div class="form-group"> 

                        <!-- <input type="hidden" id="student_subject_grade_id"> -->

                        <button type="submit" name="grade_record_btn" class="btn btn-primary">
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
                <h4 class="text-primary text-center">Search Student for Grade Records</h4>

                <span>Course: <?php echo $student_program; ?> Curriculum </span>
                
                <?php 

                    $school_year_id_query = "";

                    if($selected_school_year_id != ""){
                        $school_year_id_query = "AND t2.school_year_id = :school_year_id";
                    }

                    $get = $con->prepare("SELECT 
                        
                        t1.*
                
                        FROM subject_program as t1

                        -- INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
                        -- AND t2.student_id=:student_id

                        WHERE t1.program_id = :program_id

                        GROUP BY t1.course_level, t1.semester 

                    ");
                        
                    $get->bindValue(":program_id", $student_program_id);
                                
                    // if($selected_school_year_id != ""){
                    //     $get->bindValue(":school_year_id", $selected_school_year_id);
                    // }

                    $get->execute();

                    if($get->rowCount() > 0){
                        
                        $getAll = $get->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($getAll as $key => $value) {
                            # code...

                            // $enrollment_student_id = $value['student_id'];
                            // $enrollment_enrollment_form_id = $value['enrollment_form_id'];
                            // $enrollment_school_year_id = $value['school_year_id'];

                            $program_course_level = $value['course_level'];
                            $program_semester = $value['semester'];

                            $outputLevel = "";
                            if($program_course_level == 11){
                                $outputLevel = "Grade 11,";
                            }
                            if($program_course_level == 12){
                                $outputLevel = "Grade 12,";
                            }
                            if($program_course_level == 1){
                                $outputLevel = "First Year,";
                            }
                            if($program_course_level == 2){
                                $outputLevel = "Second Year,";
                            }
                            if($program_course_level == 3){
                                $outputLevel = "Third Year,";
                            }
                            if($program_course_level == 4){
                                $outputLevel = "Fourth Year,";
                            }


                            ?>

                                <div class="floating" style="margin-top:2%;"> 

                                    <header>

                                        <div class="title">     
                                            <h3><?= $student_program;?> <?= "$outputLevel";?> <?= "$program_semester Term";?></h3>
                                        </div>
                                    </header>

                                    <main>
                                        <table class="a"  style="font-size:15px" > 

                                            <thead>
                                                <tr>
                                                    <th style="min-width: 150px;">Course Code</th>
                                                    <th style="min-width: 200px;">Course Description</th>
                                                    <th>Req <br>Units</th>
                                                    <th>Grade</th>
                                                    <th>Earned Units</th>
                                                    <th style="min-width: 300px;">Pre - Requisite</th>
                                                    <th>Term</th>
                                                </tr>
                                            </thead>

                                            <!-- TBODY -->

                                            <tbody>

                                                <?php
                                                    $earnedUnits = 0;
                                                    $requiredUnits = 0;

                                                    $sql = $con->prepare("SELECT 
                            
                                                        t1.*,

                                                        t2.school_year_id,

                                                        t4.first,
                                                        t4.second,
                                                        t4.third,
                                                        t4.fourth,
                                                        t4.remarks
                                                
                                                        FROM subject_program as t1

                                                        LEFT JOIN student_subject as t2 ON t2.program_code = t1.subject_code
                                                        AND t2.student_id=:student_id
                                                        
                                                        LEFT JOIN student_subject_grade as t4 ON t4.student_subject_id = t2.student_subject_id
                                                        

                                                        WHERE t1.course_level = :course_level
                                                        AND t1.semester = :semester
                                                        AND t1.program_id = :program_id


                                                    ");
                                                        
                                                    $sql->bindValue(":student_id", $selected_student_id);
                                                    $sql->bindValue(":course_level", $program_course_level);
                                                    $sql->bindValue(":semester", $program_semester);
                                                    $sql->bindValue(":program_id", $student_program_id);
                                                                
                                                    // if($selected_school_year_id != ""){
                                                    //     $sql->bindValue(":school_year_id", $selected_school_year_id);
                                                    // }

                                                    $sql->execute();

                                                    if($sql->rowCount() > 0){
        

                                                        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                                                            $subject_title = $row['subject_title'];
                                                            $subject_code = $row['subject_code'];
                                                            $unit = $row['unit'];
                                                            $pre_req_subject_title = $row['pre_req_subject_title'];
                                                            
                                                            $remarks = $row['remarks'];

                                                            $earned = "";

                                                            $first = $row['first'];
                                                            $second = $row['second'];
                                                            $third = $row['third'];
                                                            $fourth = $row['fourth'];

                                                            $taken_school_year_id = $row['school_year_id'];

                                                            // var_dump($taken_school_year_id);

                                                            $requiredUnits += $unit;

                                                            $sc = new SchoolYear($con, $taken_school_year_id);
                                                            
                                                            $term = $sc->GetTerm();
                                                            $period = $sc->GetPeriod();

                                                            $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");

                                                            $enroll = new Enrollment($con);

                                                            $term = $enroll->changeYearFormat($term);

                                                            $average = "";

                                                            if($remarks != NULL && $remarks == "Passed"){
                                                                $average = (($first+$second+$third+$fourth) / 4);
                                                                $earned = $unit;
                                                                $earnedUnits += $earned;

                                                            }
                                                            if($remarks != NULL && $remarks == "Failed"){
                                                                $average = "Failed";

                                                            }
                                                            
                                                            // $term = "Term taken";
                                                            $grade = "5";

                                                            $format = "";

                                                            if($taken_school_year_id != NULL){
                                                                $format = $term . $period_short;

                                                            }

                                                            echo "
                                                                <tr class='text-center'>
                                                                    <td>$subject_code</td>
                                                                    <td>$subject_title</td>
                                                                    <td>$unit</td>
                                                                    <td>$average</td>
                                                                    <td>$earned</td>
                                                                    <td>$pre_req_subject_title</td>
                                                                    <td>$format</td>
                                                                </tr>
                                                            ";

                                                        }


                                                    }else{
                                                        echo "nothing";
                                                    }
                                                ?>
                                            </tbody>
                                            <?php if($earnedUnits != NULL): ?>
                                                <tr class="text-right">
                                                    <td colspan="5" style="padding-right: 34px;"><?php echo $earnedUnits;?></td>
                                                </tr>
                                            <?php endif; ?>

                                        </table>

                                    </main>
                                    
                                </div>
                            <?php
                        }
                    }

                ?>

            </main>

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
