
<?php 


    $standardRequirements = ["Psa", "Form137", "Goodmoral"];


    $studentRequirement = new StudentRequirement($con);

    $student_requirement_id = $studentRequirement->GetStudentRequirement(
        $pending_enrollees_id,
        $school_year_id);
    
 

    $universalRequirements = $studentRequirement->GetRequirements("Universal");
    $standardRequirements = $studentRequirement->GetRequirements("Standard");
    $transfereeRequirements = $studentRequirement->GetRequirements("Transferee");

    $standardRequirementsAcronym = [];
    $universalRequirementsAcronym = [];

    $maxUploadAllowed = 3;


    foreach ($standardRequirements as $key => $value) {
        # code...
        $acronymRequirement = $value['acronym'];
        array_push($standardRequirementsAcronym, $acronymRequirement);
    }

    foreach ($universalRequirements as $key => $value) {
        # code...
        $acronymRequirement = $value['acronym'];
        array_push($universalRequirementsAcronym, $acronymRequirement);
    }

    
    // var_dump($universalRequirementsAcronym);
    // echo "<br>";

    // if($_SERVER["REQUEST_METHOD"] === "POST"
    //     && isset($_POST['student_requirement_btn_' . $pending_enrollees_id])){


    //     $psa = $_FILES['psa'] ?? NULL;
    //     // $good_moral_character = $_FILES['good_moral_character'] ?? NULL;

    //     var_dump($psa);
        
        
    // }

    if ($_SERVER["REQUEST_METHOD"] === "POST"
        && isset($_POST['student_requirement_btn_' . $pending_enrollees_id])) {
        
        if (!is_dir('../../assets')) {
            mkdir('../../assets');
        }
        if (!is_dir('../../assets/images')) {
            mkdir('../../assets/images');
        }
        if (!is_dir('../../assets/images/student_requirements_files')) {
            mkdir('../../assets/images/student_requirements_files');
        }

        // $psa = $_FILES['psa'] ?? NULL;
        // var_dump($psa);

        // foreach ($_POST as $key => $value) {
        //     // Check if the key matches the pattern of file input names
        //     if (in_array($key, $universalRequirementsAcronym)) {
        //         // $key is the name of the file input
        //         echo "File input name: " . $key . "<br>";


        //         $psa = $_FILES[$key] ?? NULL;

        //         // var_dump($psa);
                
        //     }
        // }

        $hasInserted = false;
        $redirectOnly = false;

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


        if($hasInserted == true){
            $redirectOnly = true;
            $url = "process.php?new_student=true&step=enrollee_school_history";

            Alert::successAutoRedirect("Successfully Added", $url);
            exit();
        }

        if($redirectOnly == false){

            $url = "process.php?new_student=true&step=enrollee_school_history";
            // Alert::successAutoRedirect("Successfully Added", $url);
            header("Location: $url");
            exit();
        }
    }
 

    




?>




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
                    <h2 style="color: var(--titleTheme)">New enrollment form</h2>
                    <small>SY <?php echo $current_term; ?> &nbsp; <?php echo $current_semester; ?> Semester </small>
                </div>
            </header>

            <div class="progress">
                <span class="dot active"><p>Preferred Course/Strand</p></span>
                <span class="line active"></span>
                <span class="dot active"> <p>Personal Information</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Validate Details</p></span>
                <span class="line inactive"></span>
                <span class="dot inactive"> <p>Finished</p></span>
            </div>

            <form method="POST" enctype="multipart/form-data">
                <main>
                    <header>
                        <div class="title">
                            <h4 style="font-weight: bold;"><?php echo "$admission_status"; ?> Requirements to submit: </h4>
                        </div>
                    </header>

                    <?php foreach ($universalRequirements as $key => $value): ?>
                        
                        <div class="card-body">
                            <h5 class="card-title">* <?= $value['requirement_name']; ?></h5>
                            <hr>
                            <input multiple class="form-control" type="file" name="<?= $value['acronym']; ?>[]">
                            
                            <?php 

                                // $check = $studentRequirement->CheckSubmittedRequirementCount(
                                //     $value['requirement_id'], $student_requirement_id, 3);

                                // var_dump($check);

                            
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



                    <?php if ($admission_status === "Standard"): ?>
                        <?php foreach ($standardRequirements as $key => $value): ?>
                            

                            <div class="card-body">
                                <h5 class="card-title">* <?= $value['requirement_name']; ?></h5>
                                <hr>
                                <input multiple class="form-control" type="file" name="<?= $value['acronym']; ?>[]">

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

                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if ($admission_status === "Transferee"): ?>
                        <?php foreach ($transfereeRequirements as $key => $value): ?>
                            
                            <?php  $acronym = strtolower(str_replace(' ', '_', $value['requirement_name'])); ?>
                            
                            <div class="card-body">
                                <h5 class="card-title">* <?= $value['requirement_name']; ?></h5>
                                
                                <hr>
                                <input multiple class="form-control" type="file" name="<?= "hello" ?>">
                            
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>


            
                    <?php 
                        
                        foreach ($universalRequirements as $key => $value) {
                            ?>
                                <!-- <div class="card-body">
                                    <h5 class="card-title">* <?php echo $value['requirement_name']; ?> </h5>
                                    <hr>
                                    <input class="form-control" type="file" name="<?php echo $value['requirement_name'] ?>">
                                </div> -->
                            <?php
                        }
                    ?>
                </main>

                <div class="action">
                    <button style="margin-right: 9px;"
                    type="button"
                        class="default large"
                        onclick="window.location.href = 'process.php?new_student=true&step=enrollee_information';"
                        >
                    Return
                    </button>
                    <button
                        class="default success large"
                        name="student_requirement_btn_<?php echo $pending_enrollees_id ?>" 
                        type="submit"
                    >
                    Proceed
                    </button>
                </div>

            </form>
        </div>
    </main>
</div>




<script>

    // $('#datetime').datetimepicker({
    //     format: 'hh:mm A'
    // });

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