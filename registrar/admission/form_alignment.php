<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    

    if (isset($_GET['id'])) {

        $pending_enrollees_id = $_GET['id'];

        $pending = new Pending($con, $pending_enrollees_id);

        $pending_level = $pending->GetCourseLevel();
        $pending_type = $pending->GetPendingType();

        $pending_type = $pending_type == "SHS" ? "Senior High School" : ($pending_type == "Tertiary" ? "Tertiary" : "");
        $pending_program_id = $pending->GetPendingProgramId();

        $program = new Program($con, $pending_program_id);

        $pending_program_acronym = $program->GetProgramAcronym();

        $backUrl = "process_enrollment.php?step2=true&id=$pending_enrollees_id";
        if(isset($_POST['edit_pending_enrollment_btn_' . $pending_enrollees_id])){


            $course_level = $_POST['course_level'];
            $department_type = $_POST['department_type'];
            $program_id = $_POST['program_id'];

            $updateSuccess = $pending->UpdateEnrollmentDetails($department_type, $course_level, $program_id,
                $pending_enrollees_id);
            if($updateSuccess){

                header("Location: $backUrl");
                exit();
            }
        }


        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $backUrl;?>">
                        <i class="bi bi-arrow-return-left"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="floating">
                        <header>
                            <div class="title">
                                <h3>Enrollment Details</h3>
                                <small>Please fill-up all (*) inputs</small>
                            </div>
                        </header>
                        <main>
                            <form method="POST">

                                <div class="row">
                                    <span>
                                        <label for="Year">* Department Type</label>
                                        <div>
                                            <select class="form-control text-center" name="department_type" id="department_type">
                                                <option <?php if ($pending_type === "Senior High School") echo "selected"; ?> value="SHS">Senior High School</option>
                                                <option <?php if ($pending_type === "Tertiary") echo "selected"; ?> value="Tertiary">Tertiary</option>
                                            </select>
                                        </div>
                                    </span>
                                    <span>
                                        <label for="semester">* Program</label>
                                        <div>
                                            <select class="form-control text-center" name="program_id" id="program_id">
                                            <?php
                                            
                                                    $query = $con->prepare("SELECT * FROM program
                                                        ");
                                                    
                                                    $query->execute();

                                                    if($query->rowCount() > 0){

                                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                            $acronym = $row['acronym'];
                                                            $program_id = $row['program_id'];

                                                            $selected = "";

                                                            if($acronym == $pending_program_acronym){
                                                                $selected = "selected";
                                                            }

                                                            echo "<option $selected value='$program_id'>$acronym</option>";
                                                        }

                                                    }
                                            
                                            ?>
                                            </select>
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>  
                                        <label>* Grade Level</label>
                                        <div>
                                            <input
                                                class="text-center form-control "
                                                style="width: 300px;"
                                                type="number"
                                                name="course_level"
                                                id="course_level"
                                                value="<?php  echo $pending_level; ?>"
                                                required
                                            />
                                        </div>
                                    </span>
                                    
                                </div>

                                <div class="modal-footer">
                                    <button name="edit_pending_enrollment_btn_<?php echo $pending_enrollees_id?>" class="default">Save Changes</button>
                                </div>
                            </form>

                        </main>
                    </div>
                </main>

            </div>
        <?php
    }


?>
    