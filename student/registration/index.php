<?php
    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];


    $student_id = $_SESSION['studentLoggedInId'];
?>

            <?php
                echo Helper::enrollmentStudentHeader($con, $studentLoggedInId);
            ?>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Registration Forms</h3>
                        </div>
                    </header>
                    <main>
                        <table class="a" id="department_table">
                            <thead>
                                <tr>
                                    <th>Enrollment ID</th>
                                    <th>Section</th>
                                    <th>A.Y</th>
                                    <th>Period</th>
                                    <th>Enrolled Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = $con->prepare("SELECT 

                                        t1.enrollment_form_id
                                        ,t1.enrollment_id 
                                        ,t1.enrollment_approve 

                                        ,t2.firstname 
                                        ,t2.lastname 

                                        ,t3.program_section 
                                        ,t4.term 
                                        ,t4.period 

                                    
                                        FROM enrollment as t1

                                        INNER JOIN student as t2 ON t2.student_id = t1.student_id
                                        
                                        AND t2.student_id=:student_id

                                        INNER JOIN school_year as t4 ON t4.school_year_id = t1.school_year_id
                                        LEFT JOIN course as t3 ON t3.course_id = t1.course_id

                                    ");

                                    $query->bindParam(":student_id", $student_id);
                                    $query->execute();

                                    if($query->rowCount() > 0){

                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                            $enrollment_form_id = $row['enrollment_form_id'];
                                            $enrollment_id = $row['enrollment_id'];

                                            $term = $row['term'];
                                            $period = $row['period'];

                                            $program_section = $row['program_section'];
                                            
                                            $enrollment_approve = $row['enrollment_approve'];
                                            $url = "";
                                            if($enrollment_approve !== NULL){

                                                $enrollment_approve = date("F d, Y h:i a", strtotime($enrollment_approve));
                                                $url = "
                                                    <a href='form_subjects.php?id=$enrollment_id'>
                                                        $enrollment_form_id
                                                    </a>
                                                ";
                                            }
                                            else{
                                                $enrollment_approve = "-";
                                                $url = "
                                                    $enrollment_form_id
                                                ";
                                            }

                                            $name = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);

                                            $removeDepartmentBtn = "";


                                            // <td>
                                            //         <a href='edit.php?id='>
                                            //             <button class='btn btn-primary'>
                                            //                 <i class='fas fa-pen'></i>
                                            //             </button>
                                            //         </a>
                                            //         <button onclick='$removeDepartmentBtn' class='btn btn-danger'>
                                            //                 <i class='fas fa-trash'></i>
                                            //         </button>
                                            //     </td>

                                            echo "
                                            <tr>
                                                <td>
                                                    $url
                                                </td>
                                                <td>$program_section</td>
                                                <td>$term</td>
                                                <td>$period</td>
                                                <td>$enrollment_approve</td>

                                                
                                            </tr>
                                            ";
                                        }
                                    }

                                ?>
                            </tbody>
                        </table>
                    </main>
                </div>  
            </main>
        </div>
    </body>
</html>