<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/StudentRequirement.php');

    // include_once('../../assets/images/');

    $teacher = new Teacher($con);
    $studentRequirement = new StudentRequirement($con);

    $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    

    if($_SERVER["REQUEST_METHOD"] === "POST" &&
        isset($_POST['create_requirement_btn']) &&
        isset($_POST['requirement_name']) &&
        isset($_POST['status']) &&
        isset($_POST['is_enabled'])
    ){

        $requirement_name = $_POST['requirement_name'];
        $status = $_POST['status'];
        $is_enabled = $_POST['is_enabled'];

        $createdSuccess = $studentRequirement->InsertRequirement(
            $requirement_name, $status, $is_enabled);
 
        if ($createdSuccess == true) {

            Alert::success("Requirement Successfully Created", "index.php");
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
                <form method="POST">
                    <div class="row">
                        <span>
                            <div class="form-group">
                                <label for="requirement_name">* File Name</label>
                                <input required class="form-control" type="text" name="requirement_name" id="requirement_name" placeholder="e.g Psa, Form137">
                            </div>
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <div class="form-group">
                            <label for="status">* Status</label>

                                <select required class="form-control" id="status" name="status">
                                    <option value="universal">Universal</option>
                                    <option value="standard">Standard</option>
                                    <option value="transferee">Transferee</option>
                                </select>
                            </div>
                        </span>
                    </div>


                    <div class="row">
                        <span>
                            <label>Enabled:</label>
                            <input type="radio" id="enabled-yes" name="is_enabled" value="1">
                            <label for="enabled-yes">Yes</label>
                            <input type="radio" checked id="enabled-no" name="is_enabled" value="0">
                            <label for="enabled-no">No</label>
                        </span>
                    </div>


                    <div class="action">
                        <button type="submit" class="clean" name="create_requirement_btn">Save</button>
                    </div>
                </form>
            </main>
        </div>
    </body>
    <?php
?>

