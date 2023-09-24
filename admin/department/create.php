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
    <body>
        <div class="content">
            <nav>
                <a href="index.php">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>
            <main>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <span>
                            <label for="department_name">Department Name</label>
                            <div>
                                <input type="text" name="department_name" placeholder="">
                            </div>
                        </span>
                    </div>
                    <div class="action">
                        <button type="submit" class="clean large" name="create_department_btn">Save</button>
                    </div>
                </form>
            </main>
        </div>
    </body>
    <?php
?>

