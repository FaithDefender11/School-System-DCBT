<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Department.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/Helper.php");
    require_once("../../includes/classes/Constants.php");
    require_once("../../includes/classes/StudentRequirement.php");
    require_once("../../includes/classes/SchoolYear.php");
    

    // selected_admission_type,
    //     selected_department_type,
    //     selected_choose_level,
    //     selected_program_id,
    //     pending_enrollees_id,

    if (
        // $_SERVER['REQUEST_METHOD'] == 'POST'
        isset($_POST['selected_admission_type'])
        && isset($_POST['selected_department_type'])
        && isset($_POST['selected_choose_level'])
        && isset($_POST['selected_program_id'])
        && isset($_POST['pending_enrollees_id'])
    ) {

        // echo "hey";
        // return;
        $school_year = new SchoolYear($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $school_year_id = $school_year_obj['school_year_id'];
        $current_semester = $school_year_obj['period'];
        $current_term = $school_year_obj['term'];
        
        $pending_enrollees_id = intval($_POST['pending_enrollees_id']);
        // Int
        $selected_program_id = intval($_POST['selected_program_id']);
        // String (sanitize using filter_var or htmlspecialchars, choose based on requirements)
        $selected_admission_type = isset($_POST['selected_admission_type']) 
            ? htmlspecialchars($_POST['selected_admission_type'], ENT_QUOTES, 'UTF-8') : '';
        // String (sanitize using filter_var or htmlspecialchars, choose based on requirements)
        $selected_department_type = isset($_POST['selected_department_type']) ? htmlspecialchars($_POST['selected_department_type'], ENT_QUOTES, 'UTF-8') : '';
        // Int
        $selected_choose_level = intval($_POST['selected_choose_level']);

        // echo $pending_enrollees_id;
        // echo "<br>";
        // echo $selected_program_id;
        // echo "<br>";
        // echo $selected_admission_type;
        // echo "<br>";
        // echo $selected_department_type;
        // echo "<br>";
        // echo $selected_choose_level;
        // echo "<br>";

        
        $selected_admission_type = Helper::ValidateAdmissionType(
            $_POST['selected_admission_type']);

        $selected_department_type = Helper::ValidateDepartment(
            $_POST['selected_department_type']);

        $selected_choose_level = Helper::ValidateCourseLevel(
            $_POST['selected_choose_level']);


        // $data[] = array();

        if(empty(Helper::$errorArray)){

            // echo "no error";

            $pending = new Pending($con);

            $wasSuccess = $pending->PreferredCourseUpdate($selected_admission_type,
                $selected_department_type, $selected_program_id,
                $selected_choose_level, $pending_enrollees_id);

                $admission_status = $selected_admission_type == "New" ? "Standard" 
                    : ($selected_admission_type == "Transferee" ? "Transferee" : "");
                
                $type = $selected_department_type == "Senior High School" ? "SHS" 
                    : ($selected_department_type == "Tertiary" ? "Tertiary" : "");


                $studentRequirement = new StudentRequirement($con);

                $student_requirement_id = $studentRequirement->GetStudentRequirement(
                    $pending_enrollees_id,
                    $school_year_id);

                // if($student_requirement_id == NULL){

                //     # Create.
                //     $initNewEnrolleeStudentRequirement = $studentRequirement
                //         ->InitializedPendingEnrolleeRequirement(
                //         $pending_enrollees_id, $school_year_id);
                    
                // }

                $updateRequirements = $studentRequirement
                    ->UpdateStudentRequirementAdmission(
                    $pending_enrollees_id, $student_requirement_id,
                    $type, $admission_status);
    
            
            if($wasSuccess){

                $data[] = array(
                    "output" => "preferred_update_success"
                );
                echo json_encode($data);
                return; 
            }else{
                $data[] = array(
                    "output" => ""
                );
                echo json_encode($data);
                return; 
            }
          
            // echo $selected_department_type;

        }else{
            // echo "hasError";
            $departmentRequiredError = Helper::getError2(Constants::$requiredDepartment);
            $departmentInvalidError = Helper::getError2(Constants::$invalidDepartment);
            
            $admissionTypeRequiredError = Helper::getError2(Constants::$requiredAdmissionType);
            $admissionTypeInvalidError = Helper::getError2(Constants::$invalidAdmissionType);
            
            $courseLevelRequiredError = Helper::getError2(Constants::$requiredGradeLevel);
            $courseLevelInvalidError = Helper::getError2(Constants::$invalidGradeLevel);
            
            $data[] = array(

                "departmentRequiredError" => "$departmentRequiredError",
                "departmentInvalidError" => "$departmentInvalidError",

                "admissionTypeRequiredError" => "$admissionTypeRequiredError",
                "admissionTypeInvalidError" => "$admissionTypeInvalidError",

                "courseLevelRequiredError" => "$courseLevelRequiredError",
                "courseLevelInvalidError" => "$courseLevelInvalidError",
            );

            if(!empty($data)){
                echo json_encode($data);
                return;
            }
 
            
        }

    }

?>