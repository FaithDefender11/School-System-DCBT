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
                        <header>
                            <div class="title">
                                <h1><?php echo $program_id; ?></h1>
                            </div>
                        </header>
                        <div class="row">
                            <span>
                                <label for="program_name">Program Name</label>
                                <div>
                                    <input type="text" name="program_name" value="<?php echo $program_name; ?>">
                                </div>
                                <div>
                                    <?php echo $departmentDropdown ?>
                                </div>
                            </span>
                            <span>
                                <label for="dean">Dean</label>
                                <div>
                                    <input type="text" name="dean" value="<?php echo $program_dean; ?>">
                                </div>
                            </span>
                        </div>
                        <div class="row">
                            <span>
                                <label for="track">Track</label>
                                <div>
                                    <input type="text" name="track" value="<?php  echo $program_track; ?>">
                                </div>
                            </span>
                            <span>
                                <label for="acronym">Acronym</label>
                                <div>
                                    <input type="text" name="acronym" value="<?php echo $program_acronym; ?>">
                                </div>
                            </span>
                        </div>
                        <div class="action">
                            <button type="submit" class="clean large" name="program_edit_btn_<?php echo $program_id;?>"></button>
                        </div>
                    </form>
                </main>
            </div>
        </body>
        <?php

    }

?>