<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/SchoolYear.php');

    ?>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        </head>
    <?php

    $teacher = new Teacher($con);
    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();


    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    
    if(isset($_GET['id'])){

        $subject_id = $_GET['id'];

        $subject = new Subject($con, $subject_id);

        $section = new Section($con);

        
        $subject_course_id = $subject->GetSubjectCourseId();
        $subject_code = $subject->GetSubjectCode();
        $sp_semester = $subject->GetSemester();
        $sp_description = $subject->GetDescription();
        $sp_title = $subject->GetTitle();
        $sp_level = $subject->GetCourseLevel();
        $sp_pre_requisite = $subject->GetPreRequisite();
        $sp_unit = $subject->GetUnit();
        $sp_program_id = "";
        $subject_type = $subject->GetSubjectType();
        $subject_level = $subject->GetSubjectLevel();

        if(isset($_POST['edit_section_subject_' . $subject_id])){

            // echo "yeye";
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

            $query = "UPDATE subject SET subject_code = :subject_code 
            
                WHERE subject_id = :subject_id";

            $stmt = $con->prepare($query);

            $stmt->bindParam(':subject_code', $subject_code);
            $stmt->bindParam(':subject_id', $subject_id); // Assuming you have the subject_id value

            // Check if the query executed successfully
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                Alert::success("Successfully Edited", "index.php");
                exit();
            }
            
            $query = "INSERT INTO subject (subject_code, pre_requisite, subject_type, unit,
                    semester, subject_title, description, course_level, program_id, subject_program_id, course_id) 

                    VALUES (:subject_code, :pre_requisite, :subject_type, :unit,
                    :semester, :subject_title, :description, :course_level, :program_id, :subject_program_id, :course_id)";

            $insert = $con->prepare($query);

            
            // if ($insert->execute() && $insert->rowCount() > 0) {
            //     Alert::success("Successfully Created", "subject_list.php?id=$course_id");
            //     exit();
            // }
             
        }
        
        ?>
            <div class='col-md-10 row offset-md-1'>
                
                <div class='card'>
                    <hr>
                    <a href="../section/subject_list.php?id=<?php echo $subject_course_id;?>">
                        <button class="btn  btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>

                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Edit Subject</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>

                            <div class='form-group mb-2'>
                                <label for=''>Level</label>
                                <input readonly class='form-control'
                                    type='text' value='<?php echo $subject_level;?>' name='course_level'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>*Subject Code</label>
                                <input class='form-control' type='text' value='<?php echo $subject_code;?>' name='subject_code'>
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
                                <label for=''>*Units</label>
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
                                <input readonly class='form-control' value="<?php echo $subject_type?>" type='text' placeholder='' name='subject_type'>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='edit_section_subject_<?php echo $subject_id?>'>Save Edit</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        <?php
    }

?>