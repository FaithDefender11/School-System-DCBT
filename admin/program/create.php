<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Department.php');
    // include_once('../../assets/images/');


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

    $department = new Department($con);
    $department_id = $department->GetDepartmentIdByName($department_type);
    $departmentDropdown = $department->CreateDepartmentDropdown($department_id);

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
            Alert::success("Program Successfully Created", $back_url);
            exit();
        } else {
            Alert::error("Error Occured. Please Contact the Administrator", "");
            exit();
        }
    }
    
    ?>
    <body>
        <div class="content">
            <nav>
                <a href="<?php echo $back_url; ?>">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>
            <main>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <span>
                            <label for="program_name">Program Name</label>
                            <div>
                                <input type="text" name="program_name">
                            </div>
                            <div>
                                <?php echo $departmentDropdown;?>
                            </div>
                        </span>
                        <span>
                            <label for="dean">Dean</label>
                            <div>
                                <input type="text" name="dean">
                            </div>
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <label for="track">Track</label>
                            <div>
                                <input type="text" name="track">
                            </div>
                        </span>
                        <span>
                            <label for="acronym">Acronym</label>
                            <div>
                                <input type="text" name="acronym">
                            </div>
                        </span>
                    </div>
                    <div class="action">
                        <button type="submit" class="clean large" name="program_create_btn">Save</button>
                    </div>
                </form>
            </main>
        </div>
    </body>
    <?php
?>