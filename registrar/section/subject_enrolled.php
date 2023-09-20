<?php

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    $school_year = new SchoolYear($con, null);
    $enrollment = new Enrollment($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


    if(isset($_GET['cd']) 
        && isset($_GET['id'])
        ){

        $section_subject_code = $_GET['cd'];
        // $course_id = $_GET['c'];
        $school_year_id = $_GET['id'];

        $section = new Section($con);
        $section_name = $section->GetSectionName();

        // echo "qweqw";

        ?>
            <div class="content">
                <main>
                    <div class="floating" id="shs-sy">
                        <header>

                            <div class="title">
                                <!-- <h3 style="font-weight: bold;"><?php echo $section_name; ?> Enrolled Students</h3> -->
                            </div>
                        </header>
                        <main>
                            <table id="shs_program_table"
                                class="a" style="margin: 0">
                                <thead>
                                   <tr class="text-center"> 
                                        <th rowspan="2">Id</th>
                                        <th rowspan="2">Name</th>
                                        <th rowspan="2">Section</th>
                                        <th rowspan="2">Type</th>
                                        <th rowspan="2">Status</th>
                                        <th rowspan="2">Date Enrolled</th>
                                        <th colsp="2">Action</th>
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

                                            FROM student_subject AS t1  
                                            INNER JOIN enrollment AS t4 ON t4.enrollment_id = t1.enrollment_id

                                            LEFT JOIN student AS t2 ON t2.student_id = t1.student_id
                                            LEFT JOIN course AS t3 ON t3.course_id = t2.course_id

                                            -- WHERE t1.school_year_id=:school_year_id
                                            WHERE t1.subject_code=:subject_code
                                            AND t4.enrollment_status = 'enrolled'

                                        ");

                                        // $sql->bindValue(":school_year_id", $school_year_id);
                                        $sql->bindValue(":subject_code", $section_subject_code);
                                       
                                        $sql->execute();
                                        if($sql->rowCount() > 0){

                                        
                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $fullName = $row['firstname']." ". $row['lastname']; 
                                                $student_id = $row['student_id'];
                                                $course_id = $row['student_course_id'];
                                                $status = $row['active'];
                                                $program_section = $row['program_section'];
                                                $admission_status = $row['admission_status'];
                                                $status = $status == 1 ? "Active" : "Inactive";

                                                $enrollment_approve = $row['enrollment_approve'];
                                                $enrollment_approve = date("F d, Y g:i a",
                                                    strtotime($enrollment_approve));

                                                // $url = directoryPath . "section_shifting.php?id=$student_id";

                                                $url = directoryPath . "../student/record_details.php?id=$student_id&details=show";

                                                // Registrar Route.
                                                // $url = "../students/students_enrolled.php?course_id=$course_id&sy_id=$current_school_year_id";

                                                $subject_shift_url = directoryPath . "subject_shifting.php?id=$student_id";
    
                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$student_id.'</td>';
                                                        echo '<td>'.$fullName.'</td>';
                                                        echo '<td>
                                                            <a style="color: inherit" href="show.php?id='.$course_id.'">
                                                        '.$program_section.'

                                                            </a>
                                                        </td>';
                                                       
                                                        echo '<td>'.$admission_status.'</td>';
                                                        echo '<td>'.$status.'</td>';
                                                        echo '<td>'.$enrollment_approve.'</td>';

                                                        echo '
                                                            <td>
                                                                <button 
                                                                 onclick="window.location.href=\''.$url.'\'"
                                                                class="default">View</button>
                                                            </td>
                                                        ';
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