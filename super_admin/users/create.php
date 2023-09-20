
<?php

    include_once('../../includes/super_admin_header.php');
    include_once('../../includes/classes/User.php');



    $back_url = "index.php";

    $user = new User($con);

    if($_SERVER['REQUEST_METHOD'] === "POST" &&
        isset($_POST['user_create_btn']) &&
        isset($_POST['firstname']) &&
        isset($_POST['lastname']) && 
        isset($_POST['role']) && 
        isset($_POST['email'])

        // && isset($_FILES['photo'])
        ){


        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $default_password = 123456;
        

        $image = $_FILES['photo'] ?? null;
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

            move_uploaded_file($image['tmp_name'], $targetPath);

            $imagePath = $targetPath;

            // Remove Directory Path in the Database.
            $imagePath = str_replace('../../', '', $imagePath);

        }


        $user_create = $user->CreateUserAccount($firstname, $lastname,
            $email, $role, $default_password, $imagePath);
            
        if($user_create){

            Alert::success("User created successfully", "index.php");
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
                        <h4 class="text-center text-muted">User Create</h4>
                    </div>

                    <div class="card-body">

                        <form method='POST' enctype="multipart/form-data">

                            <div class='form-group mb-2'>
                                <label class='mb-2'>Role *</label>

                                <select class="form-control" name="role">
                                    <option value="Administrator">Administrator</option>
                                    <option value="Cashier">Cashier</option>
                                    <option value="Registrarr">Registrar</option>
                                </select>
                            </div>
                            <div class='form-group mb-2'>
                                <label class='mb-2'>First Name *</label>
                                <input required class='form-control' type='text' placeholder='' name='firstname'>
                            </div>

                            <div class='form-group mb-2'>
                                <label class='mb-2'>Last Name *</label>
                                <input required class='form-control' type='text' placeholder='' name='lastname'>
                            </div>

                            <div class='form-group mb-2'>
                                <label class='mb-2'>Email *</label>
                                <input required class='form-control' type='text' placeholder='' name='email'>
                            </div>

                            <div class='form-group mb-2'>
                                <label class='mb-2'>Photo *</label>
                                <input class='form-control' type='file' placeholder='' name='photo'>
                            </div>


                            <div class="modal-footer">
                                <button type='submit' class='btn btn-success' name='user_create_btn'>Save Section</button>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>
    <?php
?>





