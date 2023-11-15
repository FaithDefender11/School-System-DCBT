<?php
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
 
    $school_year = new SchoolYear($con);
    $schedule = new Schedule($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $teacher_id = $_SESSION['teacherLoggedInId'];


    // echo $current_school_year_id;

    $recordsPerPageOptions = $school_year->GetAllSchoolYear();

    $recordsPerPageOptions = $school_year->GetAllSchoolYear();

    $teacher_Schedule = $schedule->GetTeacherScheduleSchoolYear($teacher_id);
    // $teacher_Schedule = [];

    // print_r($teacher_Schedule);
    // 
    // $selectedTermId = isset($_GET['selected_sy_id']) 
    //     ? $_GET['selected_sy_id'] : $teacher_Schedule[0];

    $selectedTermId = isset($_GET['selected_sy_id']) 
        ? $_GET['selected_sy_id'] 
        : ($teacher_Schedule[0]['school_year_id'] ?? null);
    
    // print_r($selectedTermId);

    $recordsPerPageDropdown = '<select class="ml-2 form-control" 
        name="selected_sy_id" onchange="this.form.submit()">';

    $dropdownOptions = "";

    if(count($teacher_Schedule) > 0) {
        foreach ($teacher_Schedule as $option) {

            $chosen_sy_id = $option['school_year_id'];

            $school_year_exec = new SchoolYear($con, $chosen_sy_id);

            // $option =

            $dropdownOptions = $school_year_exec->GetTerm() . " - " . $school_year_exec->GetPeriod();
            // $dropdownOptions = $option['term'] . " - " . $option['period'];

            $recordsPerPageDropdown .= "<option value=$chosen_sy_id";

            if ($chosen_sy_id == $selectedTermId) {
                $recordsPerPageDropdown .= ' selected';
            }

            $recordsPerPageDropdown .= ">S.Y " . $dropdownOptions . " Semester</option>";
        }
        $recordsPerPageDropdown .= '</select>';
    }

    

    // $recordsPerPageDropdown = "";
    // echo $selectedTermId;
?>

        <?php 
            echo Helper::lmsTeacherNotificationHeader(
                $con, $teacherLoggedInId,
                $current_school_year_id,
                $teachingSubjects,
                "second",
                "second",
                "second",
                "second"
            );
        ?>

            <nav>
                <a href="<?= $back_url; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <main>
                <?php if(count($teacher_Schedule) > 0): ?>
                    <div class="floating">
                        <header>
                            <div class="title">
                                <h3>Teaching Subject(s)</h3>
                            </div>
                        </header>
                        <main>
                            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="get">
                                <div class="row">
                                    <span>
                                        <input type="hidden" name="id" value="<?php echo 0 ?>">
                                        <label for="term">Schedule Term:</label>
                                        <?php echo $recordsPerPageDropdown; ?>
                                    </span>
                                </div>
                            </form>
                        </main>
                        <main style='overflow-x: auto'>
                            <table class="a" id="department_table">
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
        
                                        GROUP BY t1.subject_code
                                        ");
            
                                        $query->bindValue(":teacher_id", $teacher_id); 
                                        // $query->bindValue(":school_year_id", $current_school_year_id); 
                                        $query->bindValue(":school_year_id", $selectedTermId); 
                                            
                                        $query->execute(); 
            
                                        if($query->rowCount() > 0){
            
                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){
            
            
                                                $subject_code = $row['subject_code'];
                                                $subject_title = $row['subject_title'];
                                                $course_id = $row['course_id'];
                                                $program_section = $row['program_section'];
            
                                                $grade_show_url = "teaching_code.php?c=$subject_code&id=$current_school_year_id";
                                                    
                                                echo "
                                                    <tr>
                                                        <td>
                                                            <a style='color: inherit' href='$grade_show_url'>
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
                <?php else: ?>
                    <h4 class="text-center">No grade module found.</h4>
                <?php endif; ?>
            </main>
        </div>
    </body>
</html>