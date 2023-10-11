<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/StudentRequirement.php');



    if(isset($_GET['id'])){

        $pending_enrollees_id = $_GET['id'];

        $school_year = new SchoolYear($con);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        // echo $pending_enrollees_id;

        $pending = new Pending($con, $pending_enrollees_id);

        $pending_type = $pending->GetPendingType();

        $admission_status = $pending->GetPendingAdmissionStatus();

        // echo $admission_status; 

        $studentRequirement = new StudentRequirement($con);

        $student_requirement_id = $studentRequirement->GetStudentRequirement(
            $pending_enrollees_id,
            $current_school_year_id);

        $universalRequirements = $studentRequirement->GetRequirements("Universal");
        $standardRequirements = $studentRequirement->GetRequirements("Standard");
        $transfereeRequirements = $studentRequirement->GetRequirements("Transferee");


        $back_url = "process_enrollment.php?enrollee_details=true&id=$pending_enrollees_id";

        $contact_url = "contact_enrollee.php?id=$pending_enrollees_id";

        ?>

        <div class="content">
            <nav>
                <a href="<?php echo $back_url;?>">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>

            <main>
                
                <div class="floating noBorder">
                                
                    <form method="POST" enctype="multipart/form-data">
                        <main>
                         
                            <header>
                                <div class="title">
                                    <div class="text-right">
                                        <button type="button" onclick="window.location.href='<?= $contact_url;?>'" class="btn btn-success btn-sm">
                                            <i class="fas fa-phone"></i> &nbsp; Contact
                                        </button>
                                    </div>
                                    <h4 style="font-weight: bold;"><?php echo "$admission_status"; ?> Requirements to submit: </h4>
                                </div>

                                
                            </header>

                            <?php foreach ($universalRequirements as $key => $value): ?>
                                
                                <div class="card-body">

                                    <h5 class="card-title"><?= $value['requirement_name']; ?></h5>
                                    <hr>

                                    <?php 

                                        // $check = $studentRequirement->CheckSubmittedRequirementCount(
                                        //     $value['requirement_id'], $student_requirement_id, 3);

                                        // var_dump($check);
                                    
                                        $submitedRequirementList = $studentRequirement->GetStudentRequirementList(
                                            $student_requirement_id,
                                            $value['requirement_id']);

                                        // var_dump($submitedRequirementList);

                                        $i = 0;
                                        foreach ($submitedRequirementList as $key => $value) {

                                            $uploadFile = $value['file'];
                                            $student_requirement_list_id = $value['student_requirement_list_id'];
                                            $i++;
                                            
                                            $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                            $pos = strpos($uploadFile, "img_");

                                            $original_file_name = "";

                                            // Check if "img_" was found
                                            if ($pos !== false) {
                                                $original_file_name = substr($uploadFile, $pos + strlen("img_"));
                                            }

                                            if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {

                                                ?>

                                                    <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo "$i. $original_file_name"; ?>
                                                    </a>
                                                    <br>
                                                <?php
                                            }

                                            if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                ?>
 

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



                            <?php if ($admission_status === "Standard"): ?>
                                <?php foreach ($standardRequirements as $key => $value): ?>
                                    

                                    <div class="card-body">
                                        <h5 class="card-title"><?= $value['requirement_name']; ?></h5>
                                        <hr>

                                        <?php 
                                    
                                            $submitedRequirementList = $studentRequirement->GetStudentRequirementList(
                                                $student_requirement_id,
                                                $value['requirement_id']);

                                        $i = 0;

                                            foreach ($submitedRequirementList as $key => $value) {

                                                $uploadFile = $value['file'];
                                                $student_requirement_list_id = $value['student_requirement_list_id'];
                                                $i++;
                                                $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                                $pos = strpos($uploadFile, "img_");

                                                $original_file_name = "";

                                                // Check if "img_" was found
                                                if ($pos !== false) {
                                                    $original_file_name = substr($uploadFile, $pos + strlen("img_"));
                                                }

                                                if (in_array(strtolower($extension), ['jpg', 'jpeg', 'png'])) {

                                                    ?>

                                                        <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <?php echo "$i. $original_file_name"; ?>
                                                        </a>
                                                        <br>

                                                    <?php
                                                }

                                                if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                    ?>

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
                            <?php endif; ?>

                            <?php if ($admission_status === "Transferee"): ?>

                                <?php foreach ($transfereeRequirements as $key => $value): ?>
                                    
                                    <?php if ($value['education_type'] == $pending_type 
                                        || $value['education_type'] == "Universal"): ?>
                                   
                                   
                                        <?php  $acronym = strtolower(str_replace(' ', '_', $value['requirement_name'])); ?>
                                    
                                        <div class="card-body">
                                            <h5 class="card-title"><?= $value['requirement_name']; ?></h5>
                                        
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

                                                            <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                <?php echo $original_file_name; ?>
                                                            </a>
                                                            <br>
                                                        <?php
                                                    }

                                                    if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                        
                                                        ?>
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


                    
                            <?php 
                                
                                foreach ($universalRequirements as $key => $value) {
                                    ?>
                                        <!-- <div class="card-body">
                                            <h5 class="card-title"><?php echo $value['requirement_name']; ?> </h5>
                                            <hr>
                                            <input class="form-control" type="file" name="<?php echo $value['requirement_name'] ?>">
                                        </div> -->
                                    <?php
                                }
                            ?>
                        </main>

                         

                    </form>
                </div>
            </main>
        </div>

        <?php
    }

?>

