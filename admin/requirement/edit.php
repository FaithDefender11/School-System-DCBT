<?php
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/StudentRequirement.php');
    include_once('../../includes/classes/Requirement.php');


    if(isset($_GET['id'])){

        $requirement_id = $_GET['id'];

        
        $requirement = new Requirement($con, $requirement_id);
        $teacher = new Teacher($con);
        $studentRequirement = new StudentRequirement($con);

        $requirement_name = $requirement->GetRequirementName();
        $status = $requirement->GetStatus();
        $education_type = $requirement->GetEducationType();
        $acronym = $requirement->GetAcronym();
        $acronym = $requirement->GetAcronym();
        $is_enabled = $requirement->GetIs_enabled();

        // var_dump($is_enabled);

        $form = $teacher->createTeacherForm();
        $department_selection = $teacher->CreateTeacherDepartmentSelection();

        if($_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST['create_requirement_btn']) &&
            isset($_POST['requirement_name']) &&
            isset($_POST['status']) &&
            isset($_POST['education_type']) 
            // &&isset($_POST['is_enabled'])
            
            ){

            $requirement_name = $_POST['requirement_name'];
            $status = $_POST['status'];
            // $is_enabled = $_POST['is_enabled'];
            $education_type = $_POST['education_type'];

            $is_enabled = 1;

            // var_dump($is_enabled);
            // return;

            $updateSuccess = $studentRequirement->UpdateRequirement(
                $requirement_id, $requirement_name,
                $status, $education_type, $is_enabled);
    
            if ($updateSuccess == true) {

                Alert::success("Requirement Successfully Modified.", "index.php");
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
                                        <input required class="form-control" value="<?= $requirement_name; ?>" type="text" name="requirement_name" id="requirement_name" placeholder="e.g. Psa, Form137">
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <div class="form-group">
                                    <label for="status">* Student Type</label>

                                        <select required class="form-control" id="status" name="status">
                                            <option <?php echo $status == "Universal" ? "selected" : "" ?> value="Universal">Universal</option>
                                            <option <?php echo $status == "Standard" ? "selected" : "" ?> value="Standard">Standard</option>
                                            <option <?php echo $status == "Transferee" ? "selected" : "" ?> value="Transferee">Transferee</option>
                                        </select>
                                    </div>
                                </span>
                            </div>

                            <div class="row">
                                <span>
                                    <div class="form-group">
                                    <label for="education_type">* Education Type</label>

                                        <select required class="form-control" id="education_type" name="education_type">
                                            <option  <?php echo $education_type == "Universal" ? "selected" : "" ?> value="universal">Universal</option>
                                            <option  <?php echo $education_type == "Tertiary" ? "selected" : "" ?> value="Tertiary">Tertiary</option>
                                            <option  <?php echo $education_type == "SHS" ? "selected" : "" ?> value="SHS">SHS</option>
                                        </select>
                                    </div>
                                </span>
                            </div>

<!-- 
                            <div class="row">
                                <span>
                                    <label>Enabled:</label>
                                    <input <?= $is_enabled == 1 ? "checked" : ""; ?> type="radio" id="enabled-yes" name="is_enabled" value="1">
                                    <label for="enabled-yes">Yes</label>
                                    <input <?= $is_enabled == 0 ? "checked" : ""; ?> type="radio" id="enabled-no" name="is_enabled" value="0">
                                    <label for="enabled-no">No</label>
                                </span>
                            </div> -->


                            <div class="action">
                                <button type="submit" class="clean" name="create_requirement_btn">Save</button>
                            </div>
                        </form>
                    </main>
                </div>
            </body>
        <?php

    }

?>

