<?php

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    $enrollment = new Enrollment($con);



    if(isset($_GET['course_id']) && isset($_GET['sy_id'])){

        $course_id = $_GET['course_id'];
        $school_year_id = $_GET['sy_id'];

        // echo $school_year_id;
        
        $section = new Section($con, $course_id);
        $section_name = $section->GetSectionName();

        $school_year = new SchoolYear($con, $_GET['sy_id']);

        $current_school_year_term = $school_year->GetTerm();
        $current_school_year_period = $school_year->GetPeriod();
        

        ?>
            <div class="content">

                <main>
                    <div class="floating" id="shs-sy">
                        <header>

                            <div class="title">
                                <h5 style="font-weight: bold;"><?php echo $section_name; ?> Enrolled Students in A.Y <?php echo "$current_school_year_term $current_school_year_period" ?> Semester</h5>
                            </div>
                        </header>
                        <main>
                            <table id="shs_program_table"
                                class="a" style="margin: 0">
                                <thead>
                                   <tr class="text-center"> 
                                        <th rowspan="2">ID</th>
                                        <th rowspan="2">Form ID</th>
                                        <th rowspan="2">Name</th>
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
                                                t1.enrollment_approve,
                                                t1.enrollment_status,
                                                t1.enrollment_form_id, t1.enrollment_id,
                                                t3.program_id, t2.student_id, t2.student_unique_id,
                                                t2.active,t2.firstname, t2.lastname , t2.admission_status
                                                
                                                FROM enrollment as t1
                                        
                                                INNER JOIN school_year as t4 ON t4.school_year_id=t1.school_year_id

                                                LEFT JOIN student as t2 ON t2.student_id=t1.student_id
                                                LEFT JOIN course as t3 ON t3.course_id=t1.course_id

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
                                                $student_unique_id = $row['student_unique_id'];
                                                $enrollment_form_id = $row['enrollment_form_id'];
                                                $enrollment_id = $row['enrollment_id'];
                                                

                                                $enrollment_status = $row['enrollment_status'];

                                                $enrollment_status_output = $enrollment_status == "enrolled" ? "Enrolled" : ($enrollment_status == "tentative" ? "Tentative" : "");

                                                $admission_status = $row['admission_status'];
                                                $status = $status == 1 ? "Active" : "Inactive";
                                                $program_id = $row['program_id'];

                                                $enrollment_approve = $row['enrollment_approve'];
                                                $enrollment_approve_output = date("F d, Y g:i a",
                                                    strtotime($enrollment_approve));

                                                if($enrollment_approve == null){
                                                    $enrollment_approve_output = "~";
                                                }

                                                // $url = directoryPath . "section_shifting.php?id=$student_id";

                                                $url = directoryPath . "../student/record_details.php?id=$student_id&details=show";
                                                $waiting_list_url = directoryPath . "../waiting_list/create.php?id=$student_id";

                                                // Registrar Route.
                                                // $url = "../students/students_enrolled.php?course_id=$course_id&sy_id=$current_school_year_id";

                                                $subject_shift_url = directoryPath . "subject_shifting.php?id=$student_id";
                                                $form_id_url = "";
                                                $enrollment_url  = "../admission/subject_insertion_summary.php?id=$enrollment_id&enrolled_subject=show";

                                                echo '<tr class="text-center">'; 
                                                        echo '<td>'.$student_unique_id.'</td>';
                                                        echo '<td>
                                                        <a style="color: inherit" href="'.$enrollment_url.'">
                                                            '.$enrollment_form_id.'
                                                        </a>

                                                        </td>';
                                                        echo '<td>'.$fullName.'</td>';
                                                        echo '<td>'.$admission_status.'</td>';
                                                        echo '<td>'.$enrollment_status_output.'</td>';
                                                        echo '<td>'.$enrollment_approve_output.'</td>';

                                                        $waitingList = '
                                                            <button 
                                                                onclick="window.location.href=\''.$waiting_list_url.'\'"
                                                                class=" default clean">Waiting List</button>
                                                        ';

                                                        echo '
                                                            <td>
                                                                <button 
                                                                    onclick="window.location.href=\''.$url.'\'"
                                                                class="  default">View</button>

                                                                
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