<?php 


    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SubjectProgram.php');
        
    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


    if(isset($_GET['id'])
        // && isset($_GET['st_id'])
        ){

        $student_subject_id = $_GET['id'];

        $student_subject = new StudentSubject($con, $student_subject_id);

        $student_subject_course_id = $student_subject->GetStudentSubjectCourseId();
        $student_subject_program_id = $student_subject->GetStudentSubjectProgramId();
        $student_subject_enrollment_id = $student_subject->GetStudentSubjectEnrollmentId();
        $student_subject_student_id = $student_subject->GetStudentSubjectStudentId();
        $student_subject_programCode = $student_subject->GetStudentProgramCode();

        $studentSubjectCode = $student_subject->GetStudentSubjectCode();

        $section = new Section($con, $student_subject_course_id);
        $student = new Student($con, $student_subject_student_id);

        $student_id = $student->GetStudentId();

        $enrollment = new Enrollment($con);

        $student_enrollment_status = $enrollment->CheckEnrollmentEnrolledStatus($student_id, $current_school_year_id,
            $student_subject_enrollment_id);

        $section_name = $section->GetSectionName();

        $student_course_id = $student->GetStudentCurrentCourseId();

        $section_program_id = $section->GetSectionProgramId($student_subject_course_id);

        $sectionDropdown = $section->CreateSectionSubjectDropdownProgramBased(
            $section_program_id, $student_subject_course_id, "Available Subject Code",
            $current_school_year_id, $section, $current_school_year_period,
            $student_subject_program_id, $current_school_year_term, $student_subject_programCode);

           
        // $back_url= "process_enrollment.php?subject_review=show&st_id=$student_subject_student_id&selected_course_id=$student_course_id";
        $back_url= "record_details.php?id=$student_id&enrolled_subject=show";
 
        if(isset($_POST['change_subject_btn' . $student_subject_id])
            // && isset($_POST['course_id'])
            && isset($_POST['selected_subject_code'])
            
            ){

            // $selected_course_id = $_POST['course_id'];
            $selected_course_id = intval($_POST['selected_subject_code']);

            $changesSuccess = $student_subject->ChangingStudentSubjectCourseId(
                $student_subject_enrollment_id,
                $student_subject_course_id,
                $student_subject_student_id,
                $current_school_year_id,
                $selected_course_id,
                $student_subject_id,
                $student_subject_program_id
            );

            if($changesSuccess){
                Alert::success("Changing section subject success", $back_url);
                exit();
            }
        }

        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="floating">
                        <header>
                            <div class="title">
                                <h3 class="text-center text-muted">Changing Section</h3>
                            </div>
                        </header>
                        <form method="POST">

                            <main>
                                <div class='form-group mb-2'>
                                    <label class='mb-2'>* Current Subject Code</label>

                                    <input style="pointer-events: none;" class='form-control' type='text' 
                                        value="<?php echo $studentSubjectCode;?>" placeholder='' name='section_name'>
                                </div>

                                <!-- <?php echo $sectionDropdown;?> -->

                                <hr>
                                <table id="department_table" class="a" style="margin: 0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Section Code</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        
                                            $query = $con->prepare("SELECT 
                                                t2.*
                                                , t3.program_section
                                                , t3.course_id

                                                FROM subject_program AS t2 
                                                
                                                LEFT JOIN course AS t3 ON t3.program_id = t2.program_id
                                                AND t2.course_level = t3.course_level

                                                WHERE t2.semester = :semester
                                                AND t2.subject_code = :subject_code
                                                AND t3.course_id IS NOT NULL

                                            ");

                                            // $query->bindParam(":program_id", $program_id);
                                            $query->bindParam(":semester", $current_school_year_period);
                                            $query->bindParam(":subject_code", $student_subject_programCode);
                                            $query->execute();

                                            if($query->rowCount() > 0){

                                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                    $program_section = $row['program_section'];
                                                    $course_id = $row['course_id'];

                                                    // var_dump($course_id);
                                                    // echo "<br>";

                                                    $program_id = $row['program_id'];
                                                    $subject_code = $row['subject_code'];

                                                    $program = new Program($con, $program_id);
                                            
                                                    // Remove Current Section Subject Code
                                                    if($course_id == $student_subject_course_id) continue;

                                                    $program_acronym = $program->GetProgramAcronym();

                                                    $sec = new Section($con, $course_id);
                                                    $section_code = $sec->CreateSectionSubjectCode($program_section,
                                                        $subject_code);

                                                    $capacity = "";

                                                    echo "
                                                        <tr>
                                                            <td>$course_id</td>
                                                            <td>$section_code</td>
                                                            <td>
                                                                <input 
                                                                    name='selected_subject_code'
                                                                    value='$course_id'
                                                                    class='radio'
                                                                    type='radio' 
                                                                >
                                                            </td>
                                                        </tr>
                                                    ";

                                                }

                                            }
                                        ?>
                                    </tbody>
                                </table>

                                <div class="modal-footer">

                                    <button type='submit' class='default clean'
                                        name='change_subject_btn<?php echo $student_subject_id;?>'>Save Changes</button>

                                </div>
                            </main>
                        </form>
                    </div>
                </main>
            </div>

        <?php
    }
?>  

<!-- onclick="return confirm('Are you sure you want to change section? This can\'t be undone.');" -->