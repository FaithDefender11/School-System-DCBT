<?php

    include_once('../../includes/admin_header.php');
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


    if(isset($_GET['course_id']) && isset($_GET['sy_id'])){
        $course_id = $_GET['course_id'];
        $school_year_id = $_GET['sy_id'];

        $section = new Section($con, $course_id);
        $section_name = $section->GetSectionName();

        ?>
            <div class="content">

                <main>
                    <div class="floating" id="shs-sy">
                        <header>

                            <div class="title">
                                <h3 style="font-weight: bold;"><?php echo $section_name; ?> Enrolled Students</h3>
                            </div>
                        </header>
                        <main>
                            <table id="shs_program_table"
                                class="a" style="margin: 0">
                                <thead>
                                   <tr class="text-center"> 
                                        <th rowspan="2">Id</th>
                                        <th rowspan="2">Name</th>
                                        <th rowspan="2">Status</th>
                                        <th rowspan="2">Date Enrolled</th>
                                        <th colsp="2">Action</th>
                                    </tr>	
                                </thead>
                                <tbody>
                                    <?php

                                        $enrollment_status = "enrolled";
                                        
                                        $sql = $con->prepare("SELECT 

                                            t1.enrollment_approve,
                                            t3.program_id, t2.student_id,
                                            t2.active,t2.firstname, t2.lastname 
                                            
                                            FROM enrollment as t1
                                    
                                            INNER JOIN student as t2 ON t2.student_id=t1.student_id
                                            INNER JOIN course as t3 ON t3.course_id=t1.course_id


                                            WHERE t1.course_id=:course_id
                                            AND t1.school_year_id=:school_year_id
                                            AND t1.enrollment_status=:enrollment_status
                                        ");

                                        $sql->bindValue(":course_id", $course_id);
                                        $sql->bindValue(":school_year_id", $school_year_id);
                                        $sql->bindValue(":enrollment_status", $enrollment_status);
                                        $sql->execute();
                                        
                                        if($sql->rowCount() > 0){

                                            while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                $fullName = $row['firstname']." ". $row['lastname']; 
                                                $student_id = $row['student_id'];
                                                $status = $row['active'];
                                                $status = $status == 1 ? "Active" : "Inactive";
                                                $program_id = $row['program_id'];

                                                $enrollment_approve = $row['enrollment_approve'];
                                                $enrollment_approve = date("F d, Y g:i a",
                                                    strtotime($enrollment_approve));
        
                                                $url = directoryPath . "section_shifting.php?id=$student_id";

                                                // Registrar Route.
                                                // $url = "../students/students_enrolled.php?course_id=$course_id&sy_id=$current_school_year_id";

                                                $subject_shift_url = directoryPath . "subject_shifting.php?id=$student_id";

                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$student_id.'</td>';
                                                        echo '<td>'.$fullName.'</td>';
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