

<?php 

    $student_requirement = new StudentRequirement($con,
        $pending_enrollees_id);

    $student_goodmoral = $student_requirement->GetGoodMoral();
    $student_form137 = $student_requirement->GetForm137();
    $student_psa = $student_requirement->GetPSA();

    if($_SERVER["REQUEST_METHOD"] === "POST"
        && isset($_POST['student_requirements_btn_' . $pending_enrollees_id])
        // && isset($_FILES['good_moral'])
        // && isset($_FILES['form_137'])
        // && isset($_FILES['psa'])
        ){


        $good_moral = $_FILES['good_moral'] ?? null;
        $form_137 = $_FILES['form_137'] ?? null;
        $psa = $_FILES['psa'] ?? null;
 

        if (!is_dir('../../assets')) {
            mkdir('../../assets');
        }

        if (!is_dir('../../assets/images')) {
            mkdir('../../assets/images');
        }

        if (!is_dir('../../assets/images/student_requirements_files')) {
            mkdir('../../assets/images/student_requirements_files');
        }
 
        $redirectToParentPage = false;

        # EDIT
        if($student_requirement->CheckEnrolleeHasRequirementData(
                $pending_enrollees_id) == true){

            $uploadDirectory = '../../assets/images/student_requirements_files/';

         
            if ($good_moral && $good_moral['tmp_name']) {

                $originalFilename = $good_moral['name'];
                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                # Get the stored file in the student_requirements_files folder

                if($student_goodmoral !== NULL){

                    $db_student_goodmoral = "../../" . $student_goodmoral;
                    if (file_exists($db_student_goodmoral)) {
                        // Remove the existing requirement
                        unlink($db_student_goodmoral);
                    }
                }
               
                // Upload the new file as the old file was being removed.
                move_uploaded_file($good_moral['tmp_name'], $targetPath);
                $editedGoodMoralPath = $targetPath;
                $editedGoodMoralPath = str_replace('../../', '', $editedGoodMoralPath);

            }


  
            // Handle form_137 file
            $editedForm137 = null;

            if ($form_137 && $form_137['tmp_name']) {

                $originalFilename = $form_137['name'];
                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                // Get the stored file in the student_requirements_files folder
                
                if($student_form137 !== NULL){
                    $db_form137 = "../../" . $student_form137;
                    if (file_exists($db_form137)) {
                        // Remove the existing requirement
                        // fclose(fopen($db_form137, 'r'));
                        unlink($db_form137);
                    }
                }
                // Upload the new file as the old file was being removed.
                move_uploaded_file($form_137['tmp_name'], $targetPath);
                $editedForm137 = str_replace('../../', '', $targetPath);

            }else {
                // echo "qweqwe";
                // If $psa is not being updated, retain the existing path
                $editedForm137 = $student_form137;
            }

            // Handle psa file
            $editedPSA = null;
            if ($psa && $psa['tmp_name']) {
                $originalFilename = $psa['name'];
                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                // Get the stored file in the student_requirements_files folder
                if($student_psa !== NULL){
                    $db_psa = "../../" . $student_psa;

                    if (file_exists($db_psa)) {
                        // Remove the existing requirement
                        // fclose(fopen($db_psa, 'r'));
                        unlink($db_psa);
                        // echo "unlink";
                    }
                }
                // Upload the new file as the old file was being removed.
                move_uploaded_file($psa['tmp_name'], $targetPath);
                $editedPSA = str_replace('../../', '', $targetPath);
            } else {
                // If $psa is not being updated, retain the existing path
                $editedPSA = $student_psa;
            }
           
            $update = $con->prepare("UPDATE student_requirement
                SET good_moral = :good_moral,
                    form_137 = :form_137,
                    psa = :psa

                WHERE pending_enrollees_id = :pending_enrollees_id");

            $update->bindParam(':good_moral', $editedGoodMoralPath);
            $update->bindParam(':form_137', $editedForm137);
            $update->bindParam(':psa', $editedPSA);
            $update->bindParam(':pending_enrollees_id', $pending_enrollees_id);

            // Execute the query
            $update->execute();
            if ($update->rowCount() > 0) {

                $redirectToParentPage = true;

                $url = "process.php?new_student=true&step=enrollee_parent_information";

                Alert::success("Edited Success", $url);
                exit();
            }
        }
        # Create
        else if($student_requirement->CheckEnrolleeHasRequirementData(
                $pending_enrollees_id) == false){

            if ($good_moral && $good_moral['tmp_name']) {

                $uploadDirectory = '../../assets/images/student_requirements_files/';
                $originalFilename = $good_moral['name'];

                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                move_uploaded_file($good_moral['tmp_name'], $targetPath);

                $goodMoralImagePath = $targetPath;

                // Remove Directory Path in the Database.
                $goodMoralImagePath = str_replace('../../', '', $goodMoralImagePath);

            }

            if ($form_137 && $form_137['tmp_name']) {

                $uploadDirectory = '../../assets/images/student_requirements_files/';
                $originalFilename = $form_137['name'];

                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                move_uploaded_file($form_137['tmp_name'], $targetPath);

                $form137ImagePath = $targetPath;

                // Remove Directory Path in the Database.
                $form137ImagePath = str_replace('../../', '', $form137ImagePath);
            }

            if ($psa && $psa['tmp_name']) {

                $uploadDirectory = '../../assets/images/student_requirements_files/';
                $originalFilename = $psa['name'];

                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                move_uploaded_file($psa['tmp_name'], $targetPath);

                $psaImagePath = $targetPath;

                // Remove Directory Path in the Database.
                $psaImagePath = str_replace('../../', '', $psaImagePath);
            }

            // var_dump($form_137);

            $now = date("Y-m-d H:i:s");

            $create = $con->prepare("INSERT INTO student_requirement
                (pending_enrollees_id, good_moral, form_137, psa, date_upload)
                VALUES (:pending_enrollees_id, :good_moral, :form_137, :psa, :date_upload)");

            $create->bindParam(':pending_enrollees_id', $pending_enrollees_id);
            $create->bindParam(':good_moral', $goodMoralImagePath);
            $create->bindParam(':form_137', $form137ImagePath);
            $create->bindParam(':psa', $psaImagePath);
            $create->bindParam(':date_upload', $now);
            $create->execute();

            if ($create->rowCount() > 0) {
                $redirectToParentPage = true;

                $url = "process.php?new_student=true&step=enrollee_parent_information";
                Alert::success("Successfully Created", $url);
                exit();
            }
        }

        if($redirectToParentPage == false){

            $url = "process.php?new_student=true&step=enrollee_parent_information";
            header("Location: $url");
            exit();
        }

    }
?>

<div class="content">

    <main>
        <div class="floating noBorder">
            <header>
                <div class="title">
                    <h2 style="color: var(--titleTheme)">New Enrollment Form</h2>
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
                            <h4 style="font-weight: bold;">Student Requirements</h4>
                        </div>
                    </header>
                    <hr>
 
                    <div class="carousel-inner py-4 col-md-12">

                        <?php 
                            // include_once('./uploadRequirementModal.php');
                        ?>

                        <!-- Single item -->
                        <div class="carousel-item active">
                            <div  class="container col-md-12 row">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 mb-4 mb-lg-0">
                                        <div class="card">
                                            <!-- <img src="https://mdbcdn.b-cdn.net/img/new/standard/nature/187.webp" class="card-img-top" alt="Peaks Against the Starry Sky"> -->

                                            <?php
                                                if($student_goodmoral){
                                                    ?>
                                                        <img style="width: 350px; border-radius: 5%;" 
                                                            src='<?php echo "../../".$student_goodmoral; ?>'
                                                            alt='Good Moral' class='preview-image'>
                                                    <?php
                                                }
                                            ?>
                                           
                                            <div class="card-body">
                                                <h5 class="card-title">* Good Moral</h5>
                                              
                                                <hr>

                                                <!-- <button style="cursor: pointer;"
                                                    type='button' 
                                                    data-bs-target='#uploadRequirementnModalBtn' 
                                                    data-bs-toggle='modal'
                                                    class='btn btn-primary'>
                                                    <i class='bi bi-file-earmark-x'></i>&nbsp Upload
                                                </button> -->

                                                <input class="form-control" type="file" name="good_moral">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-8 col-md-8 mb-4 mb-lg-0">
                                        <div class="card">
                                            <!-- <img src="https://mdbcdn.b-cdn.net/img/new/standard/nature/187.webp" class="card-img-top" alt="Peaks Against the Starry Sky"> -->
                                            
                                            <?php
                                                if($student_form137){
                                                    ?>
                                                        <img style="width: 350px; border-radius: 5%;" 
                                                            src='<?php echo "../../".$student_form137; ?>'
                                                            alt='Form 137' class='preview-image'>
                                                    <?php
                                                }
                                            ?>

                                            <div class="card-body">
                                                <h5 class="card-title">* Form 137</h5>
                                                <hr>

                                                <input class="form-control" type="file" name="form_137">

                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-8 col-md-8 mb-4 mb-lg-0">
                                        <div class="card">
                                                <!-- <img src="https://mdbcdn.b-cdn.net/img/new/standard/nature/187.webp" class="card-img-top" alt="Peaks Against the Starry Sky"> -->
                                                
                                            <?php
                                                if($student_psa){
                                                    ?>
                                                        <img style="width: 350px; border-radius: 5%;" 
                                                            src='<?php echo "../../".$student_psa; ?>'
                                                            alt='PSA' class='preview-image'>
                                                    <?php
                                                }
                                            ?>
                                            <div class="card-body">
                                                <h5 class="card-title">* PSA</h5>

                                                <hr>
                                                <input class="form-control" type="file" name="psa">

                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <div class="action">
                    <button style="margin-right: 9px;"
                        type="button" class="default large"
                            onclick="window.location.href = 'process.php?new_student=true&step=enrollee_information';"
                            >
                        Return
                    </button>
                    <button
                        class="default success large"
                        name="student_requirements_btn_<?php echo $pending_enrollees_id ?>" 
                        type="submit"
                        >
                        Proceed
                    </button>
                </div>

            </form>

        </div>
    </main>
</div>
 