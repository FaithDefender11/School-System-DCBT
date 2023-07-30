<?php 

    require_once("../../includes/config.php");
    require_once("../../includes/classes/Department.php");
    require_once("../../includes/classes/Program.php");
    require_once("../../includes/classes/Pending.php");
    
    if (
        $_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['selected_program_id'])
        && isset($_POST['selected_admission_type'])
        && isset($_POST['selected_department_type'])
        && isset($_POST['selected_choose_level'])
        && isset($_POST['pending_enrollees_id'])
    ) {

        // // Int
        // $pending_enrollees_id = $_POST['pending_enrollees_id'];
        // // Int
        // $selected_program_id = $_POST['selected_program_id'];
        // // String
        // $selected_admission_type = $_POST['selected_admission_type'];
        // // String
        // $selected_department_type = $_POST['selected_department_type'];
        // // Int
        // $selected_choose_level = $_POST['selected_choose_level'];
               
        
        $pending_enrollees_id = intval($_POST['pending_enrollees_id']);
        // Int
        $selected_program_id = intval($_POST['selected_program_id']);
        // String (sanitize using filter_var or htmlspecialchars, choose based on requirements)
        $selected_admission_type = isset($_POST['selected_admission_type']) ? htmlspecialchars($_POST['selected_admission_type'], ENT_QUOTES, 'UTF-8') : '';
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

        $pending = new Pending($con);

        $wasSuccess = $pending->PreferredCourseUpdate($selected_admission_type,
            $selected_department_type, $selected_program_id,
            $selected_choose_level, $pending_enrollees_id);

        if($wasSuccess){
            echo "preferred_update_success";
            return; 
        }

    }

?>