<?php 

    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/StudentRequirement.php');


    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];


    $student_id = $_SESSION['studentLoggedInId'];
    
    // echo "hey requ";

    $studentRequirement = new StudentRequirement($con);
    $student = new Student($con, $student_id);

    $student_requirement_id = $studentRequirement
        ->GetEnrollledStudentRequirement($student_id,
            $school_year_id);

    $studentRequirement = new StudentRequirement($con, $student_requirement_id);

    $universalRequirements = $studentRequirement->GetRequirements("Universal", "Universal");
    $standardRequirements = $studentRequirement->GetRequirements("Standard");
    $transfereeRequirements = $studentRequirement->GetRequirements("Transferee");

    $studentFullname = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());

    $student_type = $studentRequirement->GetStudentType();
    $student_admission_status = $studentRequirement->GetAdmissionStatus();

    
    // var_dump($student_requirement_id);

    if ($_SERVER["REQUEST_METHOD"] === "POST"
        && isset($_POST['student_requirement_btn_' . $student_requirement_id])) {
        
        // if (!is_dir('../../assets')) {
        //     mkdir('../../assets');
        // }
        // if (!is_dir('../../assets/images')) {
        //     mkdir('../../assets/images');
        // }
        // if (!is_dir('../../assets/images/student_requirements_files')) {
        //     mkdir('../../assets/images/student_requirements_files');
        // }


        $hasInserted = false;
        $redirectOnly = false;

        $maxUploadAllowed = 3;

        foreach ($universalRequirements as $key => $value) {

            $acronym = $value['acronym'];
            $requirement_id = $value['requirement_id'];

            if (isset($_FILES[$acronym]) && is_array($_FILES[$acronym]['name'])) {
 
                $fileCount = count($_FILES[$acronym]['name']);

                // echo "fileCount: $fileCount";
                // echo "<br>";

                $uploadDirectory = '../../assets/images/student_requirements_files/';
                
                if ($fileCount <= $maxUploadAllowed) {
                    // Loop through each uploaded file
                    for ($i = 0; $i < $fileCount; $i++) {

                        $originalFilename = $_FILES[$acronym]['name'][$i];

                        // echo "originalFilename: $originalFilename is for $acronym ID: $requirement_id";
                        // echo "<br>";
                        // return;

                        // Generate a unique filename
                        $uniqueFilename = uniqid() . '_' . time() . '_img_' . $originalFilename;
                        $targetPath = $uploadDirectory . $uniqueFilename;

                        // if (move_uploaded_file($originalFilename, $targetPath)) {
                        
                        if (move_uploaded_file($_FILES[$acronym]['tmp_name'][$i], $targetPath)) {

                            $imagePath = $targetPath;

                            // Remove Directory Path in the Database.
                            $imagePath = str_replace('../../', '', $imagePath);

                            $fileUpload = $studentRequirement->InsertStudentRequirement(
                                $student_requirement_id, $requirement_id, $imagePath, $maxUploadAllowed
                            );

                            if($fileUpload){
                                $hasInserted = true;
                                $redirectOnly = true;

                            }

                            // Process $imagePath as needed (e.g., store in a database).
                        } else {
                            // Handle the case where file upload failed.
                            // echo "Error uploading file: " . $originalFilename . "<br>";
                        }

                    }
                }
                
            }

        }

        foreach ($standardRequirements as $key => $value) {

            $acronym = $value['acronym'];
            $requirement_id = $value['requirement_id'];

            if (isset($_FILES[$acronym]) && is_array($_FILES[$acronym]['name'])) {
 
                $fileCount = count($_FILES[$acronym]['name']);

                $uploadDirectory = '../../assets/images/student_requirements_files/';
                
                if ($fileCount <= $maxUploadAllowed) {

                    // Loop through each uploaded file
                    for ($i = 0; $i < $fileCount; $i++) {

                        $originalFilename = $_FILES[$acronym]['name'][$i];

                        // echo "originalFilename: $originalFilename is for $acronym ID: $requirement_id";
                        // echo "<br>";

                        // Generate a unique filename
                        $uniqueFilename = uniqid() . '_' . time() . '_img_' . $originalFilename;
                        $targetPath = $uploadDirectory . $uniqueFilename;

                        // if (move_uploaded_file($originalFilename, $targetPath)) {
                        if (move_uploaded_file($_FILES[$acronym]['tmp_name'][$i], $targetPath)) {

                            $imagePath = $targetPath;

                            // Remove Directory Path in the Database.
                            $imagePath = str_replace('../../', '', $imagePath);

                            $fileUpload = $studentRequirement->InsertStudentRequirement(
                                $student_requirement_id, $requirement_id, $imagePath, $maxUploadAllowed
                            );

                            if($fileUpload){
                                $hasInserted = true;
                                $redirectOnly = true;
                            }

                            // Process $imagePath as needed (e.g., store in a database).
                        } else {
                            // Handle the case where file upload failed.
                            // echo "Error uploading file: " . $originalFilename . "<br>";
                        }

                    }
                }
            }

        }

        foreach ($transfereeRequirements as $key => $value) {

            if($value['status'] === $student_admission_status){

                $requirement_id_true = $value['requirement_id'];
                $acronym = $value['acronym'];

             

                if ($value['education_type'] == $student_type 
                    || $value['education_type'] == "Universal"){


                        // $acronym = $value['acronym'];

                        // echo "acronym: $acronym, requirement_id: $requirement_id_true" ;
                        // echo "<br>";
                   
                        // if (isset($_FILES[$acronym])){

                        //     var_dump($_FILES[$acronym]['name']);
                        //     echo "<br>";

                        // }
                        
                        if (isset($_FILES[$acronym]) && is_array($_FILES[$acronym]['name'])) {
            
                            $fileCount = count($_FILES[$acronym]['name']);

                            // echo $fileCount;
                            // echo "<br>";

                            
                            // echo "Hey $requirement_id_true";
                            // echo "<br>";

                            $uploadDirectory = '../../assets/images/student_requirements_files/';
                            
                            if ($fileCount <= $maxUploadAllowed) {

                                // Loop through each uploaded file
                                for ($i = 0; $i < $fileCount; $i++) {

                                    $originalFilename = $_FILES[$acronym]['name'][$i];

                                    // echo "originalFilename: $originalFilename is for $acronym ID: $requirement_id";
                                    // echo "<br>";

                                    // echo "acronym: $acronym, requirement_id: $requirement_id_true" ;
                                    // echo "<br>";

                                    // Generate a unique filename
                                    $uniqueFilename = uniqid() . '_' . time() . '_img_' . $originalFilename;
                                    $targetPath = $uploadDirectory . $uniqueFilename;

                                    // if (move_uploaded_file($originalFilename, $targetPath)) {
                                    if (move_uploaded_file($_FILES[$acronym]['tmp_name'][$i], $targetPath)) {

                                        $imagePath = $targetPath;

                                        // Remove Directory Path in the Database.
                                        $imagePath = str_replace('../../', '', $imagePath);

                                        
                                        // echo "requirement_id_true: $requirement_id_true" ;
                                        // echo "<br>";

                                        $fileUpload = $studentRequirement->InsertStudentRequirement(
                                            $student_requirement_id, $requirement_id_true, $imagePath, $maxUploadAllowed
                                        );

                                        if($fileUpload){
                                            $hasInserted = true;
                                            $redirectOnly = true;
                                        }

                                        // Process $imagePath as needed (e.g., store in a database).
                                    } else {
                                        // Handle the case where file upload failed.
                                        // echo "Error uploading file: " . $originalFilename . "<br>";
                                    }
                                }
                            }
                        }
                }

            }
        }


        if($hasInserted == true){
            $redirectOnly = true;
            $url = "process.php?new_student=true&step=enrollee_school_history";

            Alert::successFileUpload("Files Successfully added", "Note: Submitted files is subjected for validation.", "index.php");
            exit();
        }

        if($redirectOnly == false){

            $url = "process.php?new_student=true&step=enrollee_school_history";
            // Alert::successAutoRedirect("Successfully Added", $url);
            header("Location: index.php");
            exit();
        }
    }


?>

<div class="content">


    <main>
        <div class="floating">
    

            <form method="POST" enctype="multipart/form-data">
                <main>
                    <header>
                        <div class="title">
                            <!-- <div class="text-right">
                                <a href="<?= $contact_url; ?>" target="_blank" class="btn btn-success btn-sm">
                                    <i class="fas fa-phone"></i> &nbsp; Contact
                                </a>
                            </div> -->

                            <h3 style="font-weight: bold;"><span class="text-muted"><?= $studentFullname;?></span> | <span class="text-primary"> <?= $student_type;?></span> <?php echo "$student_admission_status"; ?> Requirements list </h3>
                        </div>
                    </header>

                    <!-- BOTH SHS AND TERTIARY REQUIREMENTS -->
                    <?php foreach ($universalRequirements as $key => $value): ?>
                        
                        <div class="card-body">

                            <h5 class="card-title"><?= $value['requirement_name']; ?> <span class="red">*</span></h5>
                            <hr>
                            <input multiple class="form-control" type="file" name="<?= $value['acronym']; ?>[]">
                            
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

                                        <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">

                                            <i style="color: orange;" class="fas fa-times"></i>
                                        </span>

                                        <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                            <?php echo $original_file_name; ?>
                                        </a>
                                        <br>
                                        <?php
                                    }

                                    if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                        ?>


                                        <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">

                                            <i style="color: orange;" class="fas fa-times"></i>
                                        </span>

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
                                    <input  multiple class="form-control" type="file" name="<?= $value['acronym']; ?>[]">

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

                                                    <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">

                                                        <i style="color: orange;" class="fas fa-times"></i>
                                                    </span>

                                                    <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>

                                                <?php
                                            }

                                            if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                ?>
                                                    <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">

                                                        <i style="color: orange;" class="fas fa-times"></i>
                                                    </span>

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

                                    <input  multiple class="form-control" type="file" name="<?= $value['acronym']; ?>[]">
                                
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

                                                    <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">

                                                        <i style="color: orange;" class="fas fa-times"></i>
                                                    </span>

                                                    <a title="View File" href='<?php echo "../../".  $value['file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>

                                                <?php
                                            }

                                            if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                ?>
                                                    <span onclick="requirementRemoval(<?php echo $student_requirement_list_id; ?>, <?php echo $student_requirement_id; ?>)" style="cursor: pointer;">

                                                        <i style="color: orange;" class="fas fa-times"></i>
                                                    </span>

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

                <div class="action">
                    
                    <button
                        class="default success large"
                        name="student_requirement_btn_<?php echo $student_requirement_id ?>" 
                        type="submit"
                    >
                    Save
                    </button>
                </div>

            </form>
        </div>
    </main>

</div>


<script>
 
    function requirementRemoval(
        student_requirement_list_id, student_requirement_id){

        var student_requirement_list_id = parseInt(student_requirement_list_id);
        var student_requirement_id = parseInt(student_requirement_id);

        Swal.fire({
                icon: 'question',
                title: `Are you sure you want remove the selected file?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/requirements/removeSelectedFile.php",
                        type: 'POST',
                        data: {
                            student_requirement_list_id,
                            student_requirement_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Deleted`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );

                                location.reload();
                            });}

                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                            console.error('Error:', error);
                            console.log('Status:', status);
                            console.log('Response Text:', xhr.responseText);
                            console.log('Response Code:', xhr.status);
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
 
</script>

