<?php

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    // include_once('../../assets/images/');

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['e_id']) && isset($_GET['id'])){

        $enrollment_form_id = $_GET['e_id'];
        $student_id = $_GET['id'];
      
        $back_url = "../admission/subject_insertion_summary.php?id=$student_id&enrolled_subject=show";

        $enrollment = new Enrollment($con);
        $student = new Student($con, $student_id);


        $course_id = $enrollment->GetCourseIdByEnrollmentForm($student_id, $enrollment_form_id,
            $current_school_year_id);


        $section = new Section($con, $course_id);

        $section_name = $section->GetSectionName();

        $section_program_id = $section->GetSectionProgramId($course_id);


        $sectionDropdown = $section->CreateSectionDropdownProgramBased($section_program_id,
            $course_id, "Available Section", $current_school_year_id, $section);




        if(isset($_POST['placement_section_' . $enrollment_form_id])
            && isset($_POST['course_id'])){

            $selected_course_id = $_POST['course_id'];

            $updateStudentCourseId = $student->UpdateStudentCourseId($student_id,
                $course_id, $selected_course_id);

            if($updateStudentCourseId == true){

                $updateEnrollmentCourseId = $enrollment->UpdateEnrollmentCourseId($student_id,
                    $enrollment_form_id, $current_school_year_id, $selected_course_id);

                if($updateEnrollmentCourseId){
                    Alert::success("Successfully Change section." ,
                        "$back_url");
                }

            }else{
                Alert::success("Selected Section Id doesnt belong to the student course id" ,
                    "");
            }
        }

        ?>

            <div class='col-md-12 row'>
                <div class='col-md-10 offset-md-1'>
                    <div class='card'>
                        <hr>
                        <a style="margin-left: 10px;" href="<?php echo $back_url;?>">
                            <button class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </a>
                        <div class='card-header'>
                            <h4 Changing Section class='text-center mb-3'></h4>
                        </div>

                        <div class="card-body">
                            <form method="POST">

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Current Section</label>

                                    <input required class='form-control' type='text' 
                                        value="<?php echo $section_name;?>" placeholder='' name='section_name'>
                                </div>

                                <?php echo $sectionDropdown;?>

                                <div class="modal-footer">

                                    <button onclick="return confirm('Are you sure you want to change section? This can\'t be undone.');"
                                    type='submit' class='btn btn-success' name='placement_section_<?php echo $enrollment_form_id;?>'>Save Placement</button>

                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                

            </div>
        <?php

    }
?>
<script>
  function confirmChangeSection(enrollmentFormId) {
    var confirmation = confirm("Are you sure you want to change section? This can't be undone.");

    if (confirmation) {
        
      var form = document.getElementById('changeSectionForm');
      form.submit();

    } else {
      // User canceled, do nothing or handle accordingly
    }
  }
</script>