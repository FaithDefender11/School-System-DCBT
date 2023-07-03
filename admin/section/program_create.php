<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Department.php');
    // include_once('../../assets/images/');

    $department = new Department($con);

    $departmentDropdown = $department->CreateDepartmentDropdown();

    if(
        isset($_POST['program_create_btn']) &&
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

        $statement = $con->prepare("INSERT INTO program (program_name, dean,
            department_id, track, acronym) 
            VALUES (:program_name, :dean, :department_id, :track, :acronym)");

        $statement->bindParam(":program_name", $program_name);
        $statement->bindParam(":dean", $dean);
        $statement->bindParam(":department_id", $department_id);
        $statement->bindParam(":track", $track);
        $statement->bindParam(":acronym", $acronym);

        if ($statement->execute()) {
            Alert::success("Program Successfully Created", "shs_index.php");
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
                <a href="index.php">
                    <button class="btn   btn-primary">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </a>
                <div class='card-header'>
                    <h4 class='text-center mb-3'>Create Program</h4>
                </div>
                <div class='card-body'>
                    <form method='POST' enctype='multipart/form-data'>
                        <div class='form-group mb-2'>
                            <label for=''>Program Name</label>
                            <input class='form-control' type='text' placeholder='' name='program_name'>
                        </div>

                        <?php echo $departmentDropdown;?>

                        <div class='form-group mb-2'>
                            <label for=''>Dean</label>
                            <input class='form-control' type='text' placeholder='' name='dean'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>Track</label>
                            <input class='form-control' type='text' placeholder='' name='track'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>Acronym</label>
                            <input class='form-control' type='text' placeholder='' name='acronym'>
                        </div>

                        <div class="modal-footer">
                            <button type='submit' class='btn btn-success' name='program_create_btn'>Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
?>

