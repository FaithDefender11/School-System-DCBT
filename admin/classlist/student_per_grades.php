<?php 
    
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');

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
 
    if(isset($_GET['id'])){

        $selected_student_id = $_GET['id'];

        $schedule = new Schedule($con);

        $student_program_id = "";
        $student_program = "";

        $student = new Student($con, $selected_student_id);

        $studentName = ucwords($student->GetFirstName()) . ", " . ucwords($student->GetMiddleName()) . " " . ucwords($student->GetLastName());

        $student_unique_id = $student->GetStudentUniqueId();
        $student_address = $student->GetStudentAddress();

        // var_dump($student_address);

        $student_course_id = $student->GetStudentCurrentCourseId();
        
        $section = new Section($con, $student_course_id);

        $student_program_id = $section->GetSectionProgramId($student_course_id);

        $program = new Program($con, $student_program_id);

        $student_program = $program->GetProgramAcronym();

        // var_dump($student_program);

        ?>
            <div class="col-lg-12">

                <div class="text-left col-md-6">
                    <form action='print_student_per_grades.php' 
                        method='POST'>

                        <input type="hidden" name="student_program_id" id="student_program_id" value="<?php echo $student_program_id;?>">
                        <input type="hidden" name="selected_student_id" id="selected_student_id" value="<?php echo $selected_student_id;?>">
                    
                        <button title="Export as pdf" style="cursor: pointer;"
                            type='submit' 
                            
                            href='#' name="print_classlist_by_section"
                            class='btn-sm btn btn-primary'>
                            <i class='bi bi-file-earmark-x'></i>&nbsp Print
                        </button>

                    </form>
                </div>

                <div class="content">

                    <main>

                        <h4 class="text-center">Daehan College of Business & Technology - DCBT</h4>
                        <h6 style="margin-top: -15px;" class="text-center">Sitio Siwang Westbank Damayan Road 20 , Taytay, Philippines</h6>
                        <span style="margin-top: -20px;"  class="text-center">Contact address. gerlie.arquiza@yahoo.com</span>
                        <h4 style="font-weight: bold;" class="text-muted text-center">Student Checklist</h4>

                        <!-- <h3 style="font-weight: bold;">Course: <?php echo $student_program; ?> Curriculum </h3> -->

                        <div style="margin-bottom: -40px;" class="container">
                            <table style="max-width:100%"   class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            <label>Student ID :</label>
                                        </th>
                                        <th>
                                            <label><?= "$student_unique_id";?></label></th> 
                                        <th></th>
                                        <th>
                                            <label>Address: <?= "$student_address";?></label>
                                        </th>
                                    </tr>
                                </thead>

                                <thead> 
                                    <tr>
                                        <th>
                                            <label>Student Name :</label>
                                        </th>
                                        <th>
                                            <label><?= "$studentName";?></label></th> 
                                        <th></th>
                                        <th>
                                            <label>Course: <?= "$student_program Curriculum";?></label>
                                        </th>
                                    </tr>
                                </thead>
                            </table>
                        </div>

                       

                        <?php 


                            $get = $con->prepare("SELECT 
                                
                                t1.*
                        
                                FROM subject_program as t1

                                -- INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
                                -- AND t2.student_id=:student_id

                                WHERE t1.program_id = :program_id

                                GROUP BY t1.course_level, t1.semester 

                            ");
                                
                            $get->bindValue(":program_id", $student_program_id);
                                        
                            $get->execute();

                            if($get->rowCount() > 0){
                                
                                $getAll = $get->fetchAll(PDO::FETCH_ASSOC);


                                foreach ($getAll as $key => $value) {
                                    # code...
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

                                                                    $format = "-";

                                                                    if($taken_school_year_id != NULL){
                                                                        $format = "SY" . $term . $period_short;

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
                                                            <td colspan="5" style="padding-right: 32px;"><?php echo $earnedUnits;?></td>
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

        <?php
    }
?>
 