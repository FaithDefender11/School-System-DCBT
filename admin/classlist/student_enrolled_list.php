<?php 
    
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');

 
    if(isset($_GET['id'])){


        $student_id = $_GET['id'];

        $student = new Student($con, $student_id);

        $studentName = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());

        ?>
 
            <div class="col-lg-12">

                
                <div class="content">
                        <main>

                            <h4 class="text-primary text-center"><?php echo "$studentName"; ?> Enrolled List</h4>

                            <?php 

                                $get2 = $con->prepare("SELECT 
                                        
                                    t1.*
            
                                    FROM enrollment as t1

                                    INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id

                                    AND t2.student_id=:student_id
            
                                    WHERE t1.enrollment_status = 'enrolled'

                                    GROUP BY t1.enrollment_id

                                ");

                                $get2->bindValue(":student_id", $student_id);

                                $get2->execute();

                                if($get2->rowCount() > 0){
                                    
                                    $get2All = $get2->fetchAll(PDO::FETCH_ASSOC);

                                    // var_dump($get2All);

                                    foreach ($get2All as $key => $value) {

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

                                                                $schedule = new Schedule($con);
                                                            
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

                                }

                            ?>

                        </main>


                </div>

            </div>

        <?php


    }
?>