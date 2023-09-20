<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/StudentRequirement.php');


    if(isset($_GET['id'])){

        $student_requirement_id = $_GET['id'];
         

        $student_requirement = new StudentRequirement($con,
            $student_requirement_id);

        $student_id = $student_requirement->GetStudentId();

        $student_goodmoral = $student_requirement->GetGoodMoral();
        $student_form137 = $student_requirement->GetForm137();
        $student_psa = $student_requirement->GetPSA();


        if($_SERVER["REQUEST_METHOD"] === "POST"
            && isset($_POST['student_requirements_btn_' . $student_requirement_id])

        ){

            $good_moral = $_FILES['good_moral'] ?? null;
            $form_137 = $_FILES['form_137'] ?? null;
            $psa = $_FILES['psa'] ?? null;

            
            // echo "good_moral: ";
            // var_dump($good_moral);
            // echo "<br>";

            // echo "form_137:";
            // var_dump($form_137);
            // echo "<br>";

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
 
            $uploadDirectory = '../../assets/images/student_requirements_files/';

            $editedGoodMoral = null;
            if ($good_moral && $good_moral['tmp_name']) {

                $originalFilename = $good_moral['name'];
                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                # Get the stored file in the student_requirements_files folder

                if($student_goodmoral !== NULL){

                    $db_student_goodmoral = "../../" . $student_goodmoral;
                    if (file_exists($db_student_goodmoral)) {
                        unlink($db_student_goodmoral);
                    }
                }
           
                move_uploaded_file($good_moral['tmp_name'], $targetPath);
                $editedGoodMoral = str_replace('../../', '', $targetPath);
            }else{
                $editedGoodMoral = $student_goodmoral;
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
                        unlink($db_form137);
                    }
                }
                // Upload the new file as the old file was being removed.
                move_uploaded_file($form_137['tmp_name'], $targetPath);
                $editedForm137 = str_replace('../../', '', $targetPath);

            }else {
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
                        unlink($db_psa);
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

                WHERE student_requirement_id = :student_requirement_id");

            $update->bindParam(':good_moral', $editedGoodMoral);
            $update->bindParam(':form_137', $editedForm137);
            $update->bindParam(':psa', $editedPSA);
            $update->bindParam(':student_requirement_id', $student_requirement_id);

            // Execute the query
            $update->execute();
            if ($update->rowCount() > 0) {
                echo "success";

                $redirectToParentPage = true;

                $url = "process.php?new_student=true&step=enrollee_parent_information";

                Alert::success("Edited Success", "");
                exit();
            }else{
                echo "not success";
            }
        }
            // if($redirectToParentPage == false){

            //     $url = "process.php?new_student=true&step=enrollee_parent_information";
            //     header("Location: index.php");
            //     exit();
            // }

        ?>

        
        <div class="content">

            <main>
                <div class="floating noBorder">
                     
                    <form method="POST" enctype="multipart/form-data">

                        <main id="requirement_list">
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

                                <div class="carousel-item active">
                                    <div  class="container col-md-12 row">
                                        <div class="row">
                                            <div class="col-lg-8 col-md-8 mb-4 mb-lg-0">
                                                <div class="card">
                                                    <!-- <img src="https://mdbcdn.b-cdn.net/img/new/standard/nature/187.webp" class="card-img-top" alt="Peaks Against the Starry Sky"> -->

                                                    <?php
                                                        if($student_goodmoral){
                                                            ?>
                                                            <button type="button" 
                                                                onclick="imageRemoval(<?php echo $student_requirement_id; ?>, <?php echo $student_id; ?>, 'Good Moral')" 
                                                                class="btn-danger btn btn-sm">
                                                                <i class="fas fa-times"></i>
                                                            </button>
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
                                                                <button type="button" 
                                                                    onclick="imageRemoval(<?php echo $student_requirement_id; ?>, <?php echo $student_id; ?>, 'Form 137')" 
                                                                    class="btn-danger btn btn-sm">
                                                                    <i class="fas fa-times"></i>
                                                                </button>

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
                                                                <button type="button" 
                                                                    onclick="imageRemoval(<?php echo $student_requirement_id; ?>, <?php echo $student_id; ?>, 'PSA')" 
                                                                    class="btn-danger btn btn-sm">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
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
                                    onclick="window.location.href = 'index.php';"
                                    >
                                Back
                            </button>
                            <button
                                class="default success large"
                                name="student_requirements_btn_<?php echo $student_requirement_id ?>" 
                                type="submit"
                                >
                                Save Changes
                            </button>
                        </div>

                    </form>

                </div>
            </main>
        </div>
 


        <?php
    }
?>


<script>

    function imageRemoval(student_requirement_id, student_id, type){
       
        Swal.fire({
                icon: 'question',
                title: `Are you sure you want to remove ${type}?`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        url: "../../ajax/requirements/removingRequirement.php",
                        type: 'POST',
                        data: {
                            student_requirement_id, student_id, type
                        },
                        success: function(response) {
                            response = response.trim();

                            console.log(response);

                            if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Removed`,
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

                                $(`#requirement_list`).load(
                                    location.href + ` #requirement_list`
                                );
                            });
                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });

                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
</script>
