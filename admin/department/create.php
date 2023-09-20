<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con);

    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    if(isset($_POST['create_department_btn'])){

        $department_name = $_POST['department_name'];


        $statement = $con->prepare("INSERT INTO department (department_name) 
            VALUES (:department_name)");

        $statement->bindParam(":department_name", $department_name);

        if ($statement->execute()) {
            Alert::success("Department Successfully Created", "index.php");
            exit();
        } else {
            Alert::error("Error Occured", "index.php");
            exit();
        }
    }
    
    ?>
        <div class='col-md-12 row '>
            <div class='col-md-10 offset-md-1'>
                <div class='card'>
                    <hr>
                    <a href="index.php">
                        <button class="btn   btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>
                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Create Department</h4>
                    </div>
                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>
                            <div class='form-group mb-2'>
                                <label for=''>Department Name</label>
                                <input class='form-control' type='text' placeholder='' name='department_name'>
                            </div>

                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='create_department_btn'>Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php
?>

