
<script src="./preferred_course.js"></script>

<div class="content">

    <nav>
        <a href="<?php echo $logout_url;?>">
            <i class="fas fa-sign-out-alt"></i>
            <h3>Logout</h3>
        </a>
    </nav>

    <main>
        <div class="floating noBorder">
            <header>
                <div class="title">
                    <h2 style="color: var(--titleTheme)">New Student Form</h2>
                    <small>SY <?php echo $current_term; ?> &nbsp; <?php echo $current_semester;?> Semester</small>
                </div>
            </header>
            <div class="progress">
                <span class="dot active"><p>Preferred Course/Strand</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Personal Information</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Validate Details</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Finished</p></span>
            </div>
            <main>
                <form action="">
                <header>
                    <div class="title">
                        <h3>Admission Type
                            &nbsp;<span class="errorMessage admission_error"></span>
                        </h3>

                    </div>
                </header>
                <div class="row mt-3">
                    <span>
                        <div class="form-element">
                            <label for="new">New Student</label>
                            <div>
                            <input
                                type="radio"
                                name="admission_type"
                                id="new"
                                value="New"
                                <?php echo $admission_status === "Standard" ? "checked" : ""; ?>
                            />
                            </div>
                        </div>
                        <div class="form-element">
                            <label for="transferee">Transferee</label>
                            <div>
                                <input
                                    type="radio"
                                    name="admission_type"
                                    id="transferee"
                                    value="Transferee"
                                    <?php echo $admission_status === "Transferee" ? "checked" : ""; ?>
                                />
                            </div>
                        </div>
                    </span>
                </div>
                
                <header>
                    <div class="title">
                        <h3>Department
                            &nbsp;<span class="errorMessage department_error"></span>
                        </h3>

                    </div>
                </header>
                <div class="row">
                    <span>


                        <div class="form-element">
                            <label for="shs">Senior High</label>
                            <div>
                            <input
                                type="radio"
                                name="department_type"
                                id="shs"
                                value="Senior High School"

                                <?php 
                                    echo $pending_type == "SHS" ? "checked" : "";
                                ?>


                            />
                            </div>
                        </div>

                        <div class="form-element">
                            <label for="tertiary">College</label>
                            <div>
                            <input
                                type="radio"
                                name="department_type"
                                id="tertiary"
                                value="Tertiary"
                                <?php 
                                    echo $pending_type == "Tertiary" ? "checked" : "";
                                ?>
                            />
                            </div>
                        </div>
                    </span>
                </div>

            
                
                    <!-- <header>
                        <div class="title">
                        <h3>Course/Strand</h3>
                        </div>
                    </header> -->
                    <div class="row">

                        <span>

                            <div>
                                <label for="">Course/Strand</label>
                                <select style="width: 450px" class="form-control" name="program_id" id="program_id">
                                    <?php 

                                        $type = $pending_type == "SHS" ? "Senior High School" 
                                            : ($pending_type == "Tertiary" ? "Tertiary" : "");

                                            // echo $type;
                                        
                                        $department_id = $department->GetDepartmentIdByName($type);

                                        // echo $deparment_id;
                                    
                                        $query = $con->prepare("SELECT * FROM program
                                            WHERE department_id=:department_id");

                                        $query->bindParam(":department_id", $department_id);

                                        $query->execute();
                                        if($query->rowCount() > 0){


                                            $output = "";


                                                $output .= "
                                                    <option value='' selected >Select Program</option>
                                                ";
                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $db_program_id = $row['program_id'];
                                                $acronym = $row['acronym'];
                                                $program_name = $row['program_name'];

                                                $selected = "";

                                                if($db_program_id == $program_id){
                                                    $selected = "selected";
                                                }

                                                $output .= "
                                                    <option value='$db_program_id' $selected>$program_name</option>
                                                ";
                                            }
                                            echo $output;
                                        }
                                    
                                    ?>
                                </select>
                            </div>

                            <div>
                                <label for="">Level &nbsp;<span class="errorMessage course_level_error" 
                                    ></span>
                                </label>
                                    

                                <?php 

                                    echo $pending->PendingCourseLevelDropdown($pending_type, 
                                        $course_level);
                                ?>
                            </div>
                        </span>

                    </div>
                </form>
            </main>

            <div class="action">
                <button name="preferred_btn"
                    class="default large"
                    onclick="<?php echo "PreferredBtn($pending_enrollees_id)"; ?>"
                >
                Proceed
                </button>
            </div>
        </div>
    </main>
</div>


