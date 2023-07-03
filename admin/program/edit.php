<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Program.php');
    // include_once('../../assets/images/');

    if(isset($_GET['id'])){

        $program_id = $_GET['id'];

        $program = new Program($con, $program_id);

        $program_name = $program->GetProgramName();
        $program_dean = $program->GetProgramDean();
        $program_department_id = $program->GetProgramDepartmentId();
        $program_acronym = $program->GetProgramAcronym();
        $program_track = $program->GetProgramTrack();

        $promptIfIDNotExists = $program->CheckIdExists($program_id);

        $department = new Department($con);

        $departmentDropdown = $department->CreateDepartmentDropdown($program_department_id);

        $department_type = "";

        if(isset($_SESSION['department_type_program'])){
            $department_type = $_SESSION['department_type_program'];
        } 

        $back_url = "";

        if($department_type === "Senior High School"){
            $back_url = "shs_index.php";
        }else if($department_type === "Tertiary"){
            $back_url = "tertiary_index.php";
        }

        // echo $department_type;
        if(
            isset($_POST['program_edit_btn_' . $program_id]) &&
            isset($_POST['program_name']) &&
            isset($_POST['dean']) &&
            isset($_POST['department_id']) &&
            isset($_POST['track']) &&
            isset($_POST['acronym'])
            ){

            $program_name = $_POST['program_name'];
            $dean = $_POST['dean'];
            $department_id = $_POST['department_id'];
            $track = $_POST['track'];
            $acronym = $_POST['acronym'];

            // Prepare the statement
            $stmt = $con->prepare("UPDATE program 

                SET dean = :dean,
                    department_id = :department_id,
                    track = :track,
                    acronym = :acronym,
                    program_name = :program_name 
                WHERE program_id = :program_id");

            // Bind the parameters
            $stmt->bindParam(':dean', $dean);
            $stmt->bindParam(':department_id', $department_id);
            $stmt->bindParam(':track', $track);
            $stmt->bindParam(':acronym', $acronym);
            $stmt->bindParam(':program_name', $program_name);
            $stmt->bindParam(':program_id', $program_id);

            if ($stmt->execute()) {
                Alert::success("Program Successfully Created", $back_url);
                exit();
            } else {
                Alert::error("Error Occured", "index.php");
                exit();
            }
        }
        
        ?>
            <div class='col-md-10 row offset-md-1'>
                
                <div class='card'>
                    <hr>
                    <a href="<?php echo $back_url;?>">
                        <button class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>
                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Edit Program # <?php echo $program_id;?></h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>
                            <div class='form-group mb-2'>
                                <label for=''>Program Name</label>
                                <input class='form-control' type='text' 
                                    value='<?php echo $program_name;?>'  name='program_name'>
                            </div>

                            <?php echo $departmentDropdown;?>

                            <div class='form-group mb-2'>
                                <label for=''>Dean</label>
                                <input value='<?php echo $program_dean;?>' class='form-control' type='text' placeholder='' name='dean'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Track</label>
                                <input value='<?php echo $program_track;?>' class='form-control' type='text' placeholder='' name='track'>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Acronym</label>
                                <input value='<?php echo $program_acronym;?>' class='form-control' type='text' placeholder='' name='acronym'>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='program_edit_btn_<?php echo $program_id;?>'>Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php

    }

?>