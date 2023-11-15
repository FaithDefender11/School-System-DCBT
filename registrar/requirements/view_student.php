<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/StudentRequirement.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SchoolYear.php');

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];


    if(isset($_GET['id'])){

        $student_id = $_GET['id'];
         
        
        $studentRequirement = new StudentRequirement($con);

        $student_requirement_id = $studentRequirement
            ->GetEnrollledStudentRequirement($student_id,
                $current_school_year_id);

        
        // var_dump($student_requirement_id);

        $student = new Student($con, $student_id);
        

        // $student_type = $student->GetIsTertiary() == 1 ? "Tertiary" : "SHS";


        $studentRequirement = new StudentRequirement($con, $student_requirement_id);

        $universalRequirements = $studentRequirement->GetRequirements("Universal", "Universal");
        $standardRequirements = $studentRequirement->GetRequirements("Standard");
        $transfereeRequirements = $studentRequirement->GetRequirements("Transferee");


        // var_dump($universalRequirements);

        $studentFullname = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());


        // $enrollment_status = $pending->GetPendingEnrollmentStatus();
        // $admission_status = $pending->GetPendingAdmissionStatus();

        $student_type = $studentRequirement->GetStudentType();
        $student_admission_status = $studentRequirement->GetAdmissionStatus();
        // var_dump($student_admission_status);
        // $enrollment_status = "";
        // $admission_status = "";




        // echo $pending_enrollees_id;

        $back_url = "enrolled_students.php";

        $contact_url = "../admission/contact_student.php?id=$student_id";

        ?>
            <div class="content">

                <nav>
                    <a href="<?php echo $back_url; ?>"
                    ><i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="floating">
                
                        <form method="POST" enctype="multipart/form-data">
                            <main>
                                <header>
                                    <div class="title">
                                        <div class="text-right">
                                            <a href="<?= $contact_url; ?>" target="_blank" class="btn btn-success btn-sm">
                                                <i class="fas fa-phone"></i> &nbsp; Contact
                                            </a>
                                        </div>
                                        <h3 style="font-weight: bold;"><span class="text-muted"><?= $studentFullname;?></span> | <span class="text-primary"> <?= $student_type;?></span> <?php echo "$student_admission_status"; ?> Requirements to submit: </h3>
                                    </div>
                                </header>

                                <!-- BOTH SHS AND TERTIARY REQUIREMENTS -->
                                <?php foreach ($universalRequirements as $key => $value): ?>
                                    
                                    <div class="card-body">

                                        <h5 class="card-title"><?= $value['requirement_name']; ?> <span class="red">*</span></h5>
                                        <hr>
                                        <!-- <input multiple class="form-control" type="file" name="<?= $value['acronym']; ?>[]"> -->
                                        
                                        <?php 
                                        
                                            $submitedRequirementList = $studentRequirement->GetStudentRequirementList(
                                                $student_requirement_id,
                                                $value['requirement_id']);

                                            // var_dump($submitedRequirementList);

                                            foreach ($submitedRequirementList as $key => $value) {

                                                $uploadFile = $value['file'];
                                                $student_requirement_list_id = $value['student_requirement_list_id'];
                                                
                                                $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                $pos = strpos($uploadFile, "img_");

                                                $original_file_name = "";

                                                // Check if "img_" was found
                                                if ($pos !== false) {
                                                    $original_file_name = substr($uploadFile, $pos + strlen("img_"));
                                                }

                                                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {

                                                    ?>

                                                    <!-- <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">
                                                        <i style="color: orange;" class="fas fa-times"></i>
                                                    </span> -->

                                                    <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>
                                                    <?php
                                                }

                                                if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                    ?>


                                                    <!-- <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">
                                                        <i style="color: orange;" class="fas fa-times"></i>
                                                    </span> -->

                                                    <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>

                                <?php endforeach; ?>


                                <?php if ($student_admission_status === "Standard"): ?>

                                    <?php foreach ($standardRequirements as $key => $value): ?>
                                        
                                        <?php if ($value['education_type'] == $student_type 
                                            || $value['education_type'] == "Universal"): ?>

                                            <div class="card-body">

                                                <h5 class="card-title"><?= $value['requirement_name']; ?> <span class="red">*</span></h5>
                                                <hr>
                                                <!-- <input  multiple class="form-control" type="file" name="<?= $value['acronym']; ?>[]"> -->

                                                <?php 
                                            
                                                    $submitedRequirementList = $studentRequirement->GetStudentRequirementList(
                                                        $student_requirement_id,
                                                        $value['requirement_id']);


                                                    foreach ($submitedRequirementList as $key => $value) {

                                                        $uploadFile = $value['file'];
                                                        $student_requirement_list_id = $value['student_requirement_list_id'];
                                                        
                                                        $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                        $pos = strpos($uploadFile, "img_");

                                                        $original_file_name = "";

                                                        // Check if "img_" was found
                                                        if ($pos !== false) {
                                                            $original_file_name = substr($uploadFile, $pos + strlen("img_"));
                                                        }

                                                        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {

                                                            ?>

                                                                <!-- <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">
                                                                    <i style="color: orange;" class="fas fa-times"></i>
                                                                </span> -->

                                                                <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <?php echo $original_file_name; ?>
                                                                </a>
                                                                <br>

                                                            <?php
                                                        }

                                                        if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                            ?>
                                                                <!-- <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">
                                                                    <i style="color: orange;" class="fas fa-times"></i>
                                                                </span> -->

                                                                <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <?php echo $original_file_name; ?>
                                                                </a>
                                                                
                                                                <br>
                                                            <?php
                                                        }
                                                    }
                                                    
                                                ?>
                                                
                                            </div>

                                        <?php endif; ?>
                                        

                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <?php if ($student_admission_status === "Transferee"): ?>

                                    <h5 class="text-center"><?php echo $student_admission_status; ?> below: </h5>
                                    <?php foreach ($transfereeRequirements as $key => $value): ?>

                                        <!-- IF student ( SHS OR Tertiary ) is matched to the education_type ( SHS OR Tertiary )  -->
                                        
                                        <?php if ($value['education_type'] == $student_type 
                                            || $value['education_type'] == "Universal"): ?>

                                            <?php
                                                $acronym = strtolower(str_replace(' ', '_', $value['requirement_name'])); 
                                            ?>
                                            
                                            <div class="card-body">

                                            <!-- ID(<?= $value['requirement_id']; ?>) -->

                                                <h5 class="card-title"><?= $value['requirement_name']; ?> <span class="red">*</span> </h5>
                                                <hr>

                                                <!-- <input  multiple class="form-control" type="file" name="<?= $value['acronym']; ?>[]"> -->
                                            
                                                <?php 
                                            
                                                    $submitedRequirementList = $studentRequirement->GetStudentRequirementList(
                                                        $student_requirement_id,
                                                        $value['requirement_id']);


                                                    foreach ($submitedRequirementList as $key => $value) {

                                                        $uploadFile = $value['file'];
                                                        $student_requirement_list_id = $value['student_requirement_list_id'];
                                                        
                                                        $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                        $pos = strpos($uploadFile, "img_");

                                                        $original_file_name = "";

                                                        // Check if "img_" was found
                                                        if ($pos !== false) {
                                                            $original_file_name = substr($uploadFile, $pos + strlen("img_"));
                                                        }

                                                        if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {

                                                            ?>

                                                                <!-- <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">
                                                                    <i style="color: orange;" class="fas fa-times"></i>
                                                                </span> -->

                                                                <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <?php echo $original_file_name; ?>
                                                                </a>
                                                                <br>

                                                            <?php
                                                        }

                                                        if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                            ?>
                                                                <!-- <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">
                                                                    <i style="color: orange;" class="fas fa-times"></i>
                                                                </span> -->

                                                                <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                    <?php echo $original_file_name; ?>
                                                                </a>
                                                                
                                                                <br>
                                                            <?php
                                                        }
                                                    }
                                                    
                                                ?>
                                            </div>

                                        <?php endif; ?>
                                        
                                    <?php endforeach; ?>

                                <?php endif; ?>
                                
                            </main>

                          

                        </form>
                    </div>
                </main>

            </div>
        <?php
    }
?>