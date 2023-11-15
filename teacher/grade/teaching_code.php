<?php

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/StudentSubject.php');

    $school_year = new SchoolYear($con, null);
    $enrollment = new Enrollment($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


    if(isset($_GET['c']) 
        && isset($_GET['id'])
        ){

        $section_subject_code = $_GET['c'];
        $school_year_id = $_GET['id'];

        $school_year = new SchoolYear($con, $school_year_id);
        $studentSubject = new StudentSubject($con);


        $course_id = $studentSubject->GetStudentSubjectCourseIdByYearAndCode($section_subject_code,
            $school_year_id);

        $code_term = $school_year->GetTerm();
        $code_period = $school_year->GetPeriod();



        // echo "qweqw";

        $back_url = "index.php";


        // include_once('./addGradeSubjectCodeModal.php');

        ?>

            <div class="content">

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left"></i>
                        Back
                    </a>
                </nav>

                <main>
                    <div class="floating" id="shs-sy">
                        <header>

                            <div class="title">
                                <h3 style="font-weight: bold;"><?php echo $section_subject_code; ?> Enrolled Students</h3>
                            </div>
                            <h6 style="font-weight: bold;" class="text-right"><?php echo "$code_term $code_period "?> Semester</h6>

                        </header>

                        <header>

                            <div style="
                                display: flex;
                                flex-direction: row;
                                align-items: center;
                            " class="title row">
                                <span>Instructor: </span>
                                <h6 style="margin-left: 5px; font-weight: bold;"> Albert Einstein</h6>

                            </div>
                            
                        </header>

                        <main style="overflow-x: auto">
                            <table id="shs_program_table"
                                class="a" style="margin: 0">
                                <thead>
                                   <tr class="text-center"> 
                                        <!-- <th rowspan="2">ID</th> -->
                                        <th rowspan="2">Name</th>
                                        <th rowspan="2">Code</th>
                                        <th rowspan="2">Units</th>
                                        <th rowspan="2">Prelim</th>
                                        <th rowspan="2">Midterm</th>
                                        <th rowspan="2">Pre-Final</th>
                                        <th rowspan="2">Final</th>
                                        <th rowspan="2">Average</th>
                                        <th rowspan="2">Remarks</th>
                                        <th rowspan="2">Action</th>
                                    </tr>	
                                </thead>
                                <tbody>

                                    <?php
                                        $enrollment_status = "enrolled";
                                        $sql = $con->prepare("SELECT 
                                            t1.*,
                                            t2.active, t2.firstname, t2.lastname , t2.admission_status,
                                            t2.course_id as student_course_id
                                            ,t4.enrollment_approve

                                            ,t3.program_section

                                            ,t5.first
                                            ,t5.second
                                            ,t5.third
                                            ,t5.fourth
                                            
                                            ,t5.remarks
                                            ,t5.student_subject_grade_id

                                            ,t6.unit


                                            FROM student_subject AS t1  
                                            INNER JOIN enrollment AS t4 ON t4.enrollment_id = t1.enrollment_id

                                            LEFT JOIN student AS t2 ON t2.student_id = t1.student_id
                                            LEFT JOIN course AS t3 ON t3.course_id = t2.course_id
                                            LEFT JOIN student_subject_grade AS t5 ON t5.student_subject_id = t1.student_subject_id 
                                            LEFT JOIN subject_program AS t6 ON t6.subject_program_id = t1.subject_program_id 

                                            -- WHERE t1.school_year_id=:school_year_id
                                            WHERE t1.subject_code=:subject_code
                                            AND t4.enrollment_status = 'enrolled'

                                        ");

                                        // $sql->bindValue(":school_year_id", $school_year_id);
                                        $sql->bindValue(":subject_code", $section_subject_code);
                                       
                                        $sql->execute();
                                        if($sql->rowCount() > 0){

                                        
                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $fullName = ucfirst($row['firstname'])." ". ucfirst($row['lastname']); 
                                                $student_id = $row['student_id'];
                                                $course_id = $row['student_course_id'];
                                                $program_code = $row['program_code'];
                                                $student_subject_grade_id = $row['student_subject_grade_id'];

                                                $student_subject_id = $row['student_subject_id'];

                                                $unit = $row['unit'];


                                                $remarks = $row['remarks'];

                                                $first = $row['first'];
                                                $second = $row['second'];
                                                $third = $row['third'];
                                                $fourth = $row['fourth'];

                                                $add_grades_onclick = "addGrade($student_subject_id, $student_id)";
    
                                                // <input type="hidden" id="student_name_modal" value="'.$fullName.'" >

                                                // echo $student_subject_grade_id;

                                                $grade_url = "";
                                                if($student_subject_grade_id != NULL){
                                                    $grade_url = '

                                                        <a href="edit_grade.php?st_id='.$student_id.'&sg_id='.$student_subject_grade_id.'" 
                                                            title="Edit" style="color: blue; cursor: pointer;">
                                                            <span class="fa fa-plus fw-fa"></span> Edit Grade
                                                        </a>
                                                    ';
                                                }else if($student_subject_grade_id == NULL){
                                                   

                                                    $grade_url = '

                                                        <a href="add_grade.php?st_id='.$student_id.'&ss_id='.$student_subject_id.'" 
                                                            title="Edit" style="color: blue; cursor: pointer;">
                                                            <span class="fa fa-plus fw-fa"></span> Add Grade
                                                        </a>
                                                    ';

                                                }

                                                

                                               




                                                
                                                echo '<tr class="text-center">'; 

                                                        // echo '<td>'.$student_id.'</td>';
                                                        echo '<td>'.$fullName.'</td>';
                                                        echo '<td>'.$program_code.'</td>';
                                                       
                                                        echo '<td>'.$unit.'</td>';
                                                        echo '<td>'.$first.'</td>';
                                                        echo '<td>'.$second.'</td>';
                                                        echo '<td>'.$third.'</td>';
                                                        echo '<td>'.$fourth.'</td>';
                                                        echo '<td></td>';
                                                        echo '<td>'.$remarks.'</td>';
                                                        echo '<td>


                                                            '.$grade_url.'
                                                        </td>';

                                                       
                                                echo '</tr>';
                                            }
                                        }else{
                                            // echo "none";
                                        }

                                    ?>
                                </tbody>
                            </table>
                        </main>
                    </div>
                </main>

            </div>

        <?php

    }

?>

<script>

    function openModal(link) {

        var studentSubjectId = link.previousElementSibling.value; // Get the student_subject_id
        var studentId = link.previousElementSibling.previousElementSibling.value; // Get the student_id

        // var studentName = link.previousElementSibling.previousElementSibling.text; // Get the student_id

        // console.log(studentSubjectId)
        // console.log(studentId)

        // Set the values in the modal
        document.getElementById('student_subject_id_modal').value = studentSubjectId;
        document.getElementById('student_id_modal').value = studentId;
        // document.getElementById('student_name_modal').value = studentName;
        // document.getElementById('modalStudentName').textContent = studentName;
    }

    $(document).ready(function() {

    
        $('#first_quarter_input').on('keypress', function(event) {
            
            if (event.which === 13) {

                event.preventDefault();

                var firstQuarterInputValue = $(this).val();

                console.log(firstQuarterInputValue)
            }
        });

    });
</script>