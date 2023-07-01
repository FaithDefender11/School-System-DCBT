<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SchoolYear.php');

    $teacher = new Teacher($con);
    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();


    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    
    if(isset($_GET['sp_id']) && isset($_GET['id'])){

        $subject_program_id = $_GET['sp_id'];
        $course_id = $_GET['id'];

        $subject_program = new SubjectProgram($con, $subject_program_id);

        $section = new Section($con, $course_id);

        //  GENERATE CODE.
        $rawCode = $subject_program->GetSubjectProgramRawCode();
        $sp_semester = $subject_program->GetSemester();
        $sp_description = $subject_program->GetDescription();
        $sp_title = $subject_program->GetTitle();
        $sp_level = $subject_program->GetCourseLevel();
        $sp_pre_requisite = $subject_program->GetPreRequisite();
        $sp_unit = $subject_program->GetUnit();
        $sp_program_id = $subject_program->GetProgramId();

        $section_name = $section->GetSectionName();
        $section_level = $section->GetSectionGradeLevel();

        $section_code = $rawCode  . "-" . $section_name ;

        if(isset($_POST['populate_subject_btn'])){

            $subject_code = $_POST['subject_code'];
            $pre_requisite = $_POST['pre_requisite'];
            $subject_type = $_POST['subject_type'];
            $unit = $_POST['unit'];
            $semester = $_POST['semester'];
            $subject_title = $_POST['subject_title'];
            $description = $_POST['description'];
            $pre_requisite = $_POST['pre_requisite'];
            $course_level = $_POST['course_level'];
            $subject_type = $_POST['subject_type'];
            
            $query = "INSERT INTO subject (subject_code, pre_requisite, subject_type, unit,
                    semester, subject_title, description, course_level, program_id, subject_program_id, course_id) 

                    VALUES (:subject_code, :pre_requisite, :subject_type, :unit,
                    :semester, :subject_title, :description, :course_level, :program_id, :subject_program_id, :course_id)";

            $insert = $con->prepare($query);

            $insert->bindParam(':subject_code', $subject_code);
            $insert->bindParam(':pre_requisite', $pre_requisite);
            $insert->bindParam(':subject_type', $subject_type);
            $insert->bindParam(':unit', $unit);
            $insert->bindParam(':semester', $semester);
            $insert->bindParam(':subject_title', $subject_title);
            $insert->bindParam(':description', $description);
            $insert->bindParam(':course_level', $course_level);
            $insert->bindParam(':program_id', $sp_program_id);
            $insert->bindParam(':subject_program_id', $subject_program_id);
            $insert->bindParam(':course_id', $course_id);

            if ($insert->execute() && $insert->rowCount() > 0) {
                Alert::success("Successfully Created", "subject_list.php?id=$course_id");
                exit();
            }
            
        }
        
        ?>
            <div class='col-md-10 row offset-md-1'>
                
                <div class='card'>
                    <hr>
                    <a href="subject_list.php?id=<?php echo $course_id;?>">
                        <button class="btn  btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>
                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Populate Subject</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>

                            <div class='form-group mb-2'>
                                <label for=''>Level</label>
                                <input readonly class='form-control'
                                    type='text' value='<?php echo $section_level;?>' name='course_level'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>*Generated Section Subject Code</label>
                                <input class='form-control' type='text' value='<?php echo $section_code;?>' name='subject_code'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>*Subject Title</label>
                                <input readonly class='form-control' value="<?php echo $sp_title;?>" type='text' placeholder='' name='subject_title'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>*Description</label>
                                <textarea readonly class='form-control' type='text' placeholder='' name='description'><?php echo $sp_description;?></textarea>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>*Unit</label>
                                <input readonly class='form-control' value="<?php echo $sp_unit?>" type='text' placeholder='' name='unit'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>*Semester</label>
                                <input readonly class='form-control' value="<?php echo $sp_semester;?>" type='text' placeholder='' name='semester'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>*Pre Requisite</label>
                                <input readonly class='form-control' value="<?php echo $sp_pre_requisite;?>" type='text' placeholder='' name='pre_requisite'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>*Type</label>
                                <input readonly class='form-control' value="<?php echo $subject_program->GetSubjectType();?>" type='text' placeholder='' name='subject_type'>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='populate_subject_btn'>Save Populate</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        <?php
    }

?>