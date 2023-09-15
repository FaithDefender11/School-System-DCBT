<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
 
    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $teacher_id = $_SESSION['teacherLoggedInId'];
?>


<div class="content">
    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h4 style="font-weight: bold;" class="text-primary">Teaching Subject(s)</h4>
                </div>
            </header>
            <main>

                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Subject</th>  
                            <th>Code</th>
                            <th>Section</th>  
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $query = $con->prepare("SELECT 

                                t1.*,
                                t2.subject_title,
                                t3.program_section

                                FROM subject_schedule AS t1  

                                LEFT JOIN subject_program as t2 ON t2.subject_program_id = t1.subject_program_id
                                LEFT JOIN course as t3 ON t3.course_id = t1.course_id


                                WHERE t1.teacher_id=:teacher_id
                                AND t1.school_year_id=:school_year_id
                            ");

                            $query->bindValue(":teacher_id", $teacher_id); 
                            $query->bindValue(":school_year_id", $current_school_year_id); 
                            $query->execute(); 

                            if($query->rowCount() > 0){

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){


                                    $subject_code = $row['subject_code'];
                                    $subject_title = $row['subject_title'];
                                    $course_id = $row['course_id'];
                                    $program_section = $row['program_section'];

                                    $class_url = "../class/index.php?c_id=$course_id&c=$subject_code";
                                    
                                    echo "
                                        <tr class='text-center'>
                                            <td>
                                                <a style='color: inherit' href='$class_url'>
                                                    $subject_title
                                                </a>
                                            </td>
                                            <td>
                                                $subject_code
                                            </td>
                                            <td>
                                                <a style='all:unset; cursor: pointer' href=''>
                                                    $program_section
                                                </a>
                                            </td>
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
