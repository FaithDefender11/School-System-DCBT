<?php

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Department.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con);

    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    // $current_school_year_period = "Second";

    if(isset($_GET['id'])
    ){

        $pending_enrollees_id = NULL;
        $manual_create = NULL;

        if(isset($_GET['p_id'])){
            $pending_enrollees_id = $_GET['p_id'];
        }
        if(isset($_GET['manual_create'])){
           $manual_create = "manual_create";
        }

        $program_id = $_GET['id'];
        // $selected_term = $_GET['term'];

        $program = new Program($con, $program_id);

        $department_id = $program->GetProgramDepartmentId();

        $department = new Department($con, $department_id);
        $departmentName = $department->GetDepartmentName();

        $is_tertiary = $departmentName == "Senior High School" ? 0 : 1;

        // var_dump($departmentName);

        $section = new Section($con, null);

        $trackDropdown = $section->createProgramSelection($program_id);


                
        $courseLevelDropdown = $section->
            CreateCourseLevelDropdownDepartmentBased($departmentName);

        $section_term = "";
        
        if(isset($_SESSION['section_term'])){
            $section_term = $_SESSION['section_term'];
        }


        // $back_url = "../admission/process_enrollment.php?enrollee_find_section=true&id=$pending_enrollees_id";
        
        $back_url = "";
        
        // var_dump($manual_create);
        
        if($pending_enrollees_id != NULL){
            $back_url = "../admission/process_enrollment.php?enrollee_find_section=true&id=$pending_enrollees_id";
        }
        if($manual_create != NULL){
            $back_url = "../enrollment/manual_create.php";
        }
        // $s = "admission/subject_insertion_summary.php?id=1437&enrolled_subject=show&page=waiting_approval";

        // if($url_info == "find_section"){
        //     $back_url = "../admission/process_enrollment.php?find_section=show&st_id=$st_id&c_id=$c_id";
        // }
        // else if($url_info == "subject_insertion"){
        //     $back_url = "../admission/subject_insertion_summary.php?id=$e_id&enrolled_subject=show&page=waiting_approval";
        // }

        if($_SERVER['REQUEST_METHOD'] === "POST" &&
            isset($_POST['create_section_btn']) &&
            isset($_POST['program_section']) && 
            isset($_POST['capacity']) &&
            isset($_POST['course_level']) &&
            isset($_POST['min_student'])){

            $program_section = $_POST['program_section'];
            $capacity = $_POST['capacity'];
            $min_student = $_POST['min_student'];
            $course_level = $_POST['course_level'];
 
            // echo "program_section: $program_section <br>";
            // echo "capacity: $capacity <br>";
            // echo "course_level: $course_level <br>";
            // echo "min_student: $min_student <br>";

            if($section->CheckSetionExistsWithinCurrentSY($program_section,
                $current_school_year_term) == true){
                Alert::error("$program_section already exists within $current_school_year_term term", "");
                exit();
            }

            // return;

            $is_active = "yes";

 

            $insert = "";

            $insert = $con->prepare("INSERT INTO course
                (program_section, program_id, capacity,
                school_year_term, active, is_full, course_level, is_tertiary, min_student)

                VALUES(:program_section, :program_id, :capacity, 
                :school_year_term, :active, :is_full, :course_level, :is_tertiary, :min_student)");
            
            $insert->bindValue(":program_section", $program_section);
            $insert->bindValue(":program_id", $program_id);
            $insert->bindValue(":capacity", $capacity);
            $insert->bindValue(":school_year_term", $current_school_year_term);
            $insert->bindValue(":active", "yes");
            $insert->bindValue(":is_full", "no");
            $insert->bindValue(":course_level", $course_level, PDO::PARAM_INT);
            $insert->bindValue(":is_tertiary", $is_tertiary, PDO::PARAM_INT);
            $insert->bindValue(":min_student", $min_student, PDO::PARAM_INT);
            
            $insert->execute();

            if($insert->rowCount() > 0){

                Alert::success("Successfully added section", $back_url);
                exit();
            }
            
        }



        ?>
            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <div class='col-md-10 offset-md-1'>
                    <div class='card'>
                        
                        <div class='card-header'>
                            <h4 class='text-center mb-3'><?php echo $program->GetProgramSectionName();?> Section creation</h4>
                        </div>

                        <div class="card-body">

                            <form method='POST'>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>* Section Name</label>

                                    <input required class='form-control' type='text' 
                                        value="<?php echo $program->GetProgramSectionName(); ?>" placeholder='e.g: STEM11-A, ABM11-A' name='program_section'>
                                </div>

                                <?php echo $courseLevelDropdown;  ?>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>* Minimum Capacity</label>
                                    <input required class='form-control' value="<?php echo $db_min_capacity; ?>" type='number' placeholder='Minimum Capacity' name='min_student'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>* Maximum Capacity</label>
                                    <input required class='form-control' value="30" type='number' placeholder='Room Capacity' name='capacity'>
                                </div>

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='create_section_btn'>Save Section</button>
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
        $('#program_id').on('change', function() {

            var program_id = parseInt($(this).val());
            console.log(program_id)


            $.ajax({
                url: '../../ajax/section/get_level_from_program.php',
                type: 'POST',
                data: {
                    program_id
                },
                dataType: 'json',

                success: function(response) {
                    var options = '<option value="">Choose Level</option>';

                    $.each(response, function(index, value) {

                        if(value.level > 5){
                            options += '<option value="' + value.level + '">Grade ' + value.level +'</option>';

                        }
                        else if(value.level <= 4){
                            var yearLabel;
                            switch (value.level) {
                                case "1":
                                    yearLabel = "1st year";
                                    break;
                                case "2":
                                    yearLabel = "2nd year";
                                    break;
                                case "3":
                                    yearLabel = "3rd year";
                                    break;
                                case "4":
                                    yearLabel = "4th year";
                                    break;
                                default:
                                    yearLabel = value.level + "th year";
                            }
                            options += '<option value="' + value.level + '">' + yearLabel + '</option>';
                        }
                    });

                    $('#course_level').html(options);
                }
            });
        });
    </script>