
<?php

    include_once('../../includes/super_admin_header.php');
    include_once('../../includes/classes/User.php');

    $back_url = "index.php";

    if(isset($_GET['id'])){

        $user_id = $_GET['id'];

        $user = new User($con, $user_id);

        $firstname = $user->getFirstName();
        $lastname = $user->getLastName();
        $email = $user->GetEmail();
        $role = $user->GetRole();
        $photo = $user->GetPhoto();

        if($_SERVER['REQUEST_METHOD'] === "POST" &&
            isset($_POST['user_edit_btn_' . $user_id]) &&
            isset($_POST['firstname']) &&
            isset($_POST['lastname']) && 
            isset($_POST['role']) && 
            isset($_POST['email'])){


            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $email = $_POST['email'];
            $role = $_POST['role'];

            $default_password = 123456;
            
            $image = $_FILES['photo'] ?? null;
        
            $db_image = "../../" . $photo;

            $imagePath = NULL;

            if (!is_dir('../../assets')) {
                mkdir('../../assets');
            }

            if (!is_dir('../../assets/images')) {
                mkdir('../../assets/images');
            }

            if (!is_dir('../../assets/images/users')) {
                mkdir('../../assets/images/users');
            }

            if ($image && $image['tmp_name']) {

                $uploadDirectory = '../../assets/images/users/';
                $originalFilename = $image['name'];
                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                if($photo !== NULL){
                    $db_user_photo = "../../" . $photo;

                    if (file_exists($db_user_photo)) {
                        unlink($db_user_photo);
                    }
                }

                // Upload the new file
                move_uploaded_file($image['tmp_name'], $targetPath);
                // $imagePath = $targetPath;
                $imagePath = str_replace('../../', '', $targetPath);
            }else{
                $imagePath = $photo;
            }
            

            $user_edit = $user->EditUserAccount($firstname, $lastname,
                $email, $role, $user_id, $imagePath);
                
            if($user_edit){

                Alert::success("User edited successfully", "index.php");
                exit();
            }
        }


        ?>
            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <div class='col-md-10 offset-md-1'>
                    <div class='card'>
                        
                        <div class='card-header'>
                            <h4 class="text-center text-primary">Edit User</h4>
                        </div>

                        <div class="card-body">

                            <form method='POST' enctype="multipart/form-data">

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Role *</label>
                                    <select class="form-control" name="role">
                                        <option value="Administrator" <?php echo $role === "Administrator" ? "selected" : "" ?>>Administrator</option>
                                        <option value="Cashier" <?php echo $role === "Cashier" ? "selected" : "" ?>>Cashier</option>
                                        <option value="Registrar" <?php echo $role === "Registrar" ? "selected" : "" ?>>Registrar</option>
                                    </select>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>First Name *</label>
                                    <input value="<?php echo $firstname; ?>" required class='form-control' type='text' placeholder='' name='firstname'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Last Name *</label>
                                    <input value="<?php echo $lastname; ?>" required class='form-control' type='text' placeholder='' name='lastname'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Email *</label>
                                    <input value="<?php echo $email; ?>" required class='form-control' type='text' placeholder='' name='email'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label class='mb-2'>Photo *</label>
                                    <input value="<?php echo $photo; ?>" class='form-control' type='file' placeholder='' name='photo'>

                                    <?php 
                                        if($photo){
                                            ?>
                                                <img style="width: 150px; border-radius: 100%;" 
                                                    src='<?php echo "../../".$photo; ?>' alt='Profile Picture' class='preview-image'>
                                            <?php    
                                        }else{
                                            ?>
                                                <span   span class='text-muted'>No profile picture available</span>
                                            <?php
                                        }

                                    ?>
                                    <!-- <?php if ($photo): ?> -->
                                    <!-- <img style="width: 150px; border-radius: 100%;"  -->
                                        <!-- src='<?php echo "../../".$photo; ?>' alt='Profile Picture' class='preview-image'> -->
                                    <!-- <?php else: ?> -->
                                        <!-- <span class='text-muted'>No profile picture available</span> -->
                                    <!-- <?php endif; ?> -->

                                </div>

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='user_edit_btn_<?php echo $user_id ?>'>Save Section</button>
                                </div>
            
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }

?>





