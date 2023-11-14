
<?php

    include_once('../../includes/super_admin_header.php');
    include_once('../../includes/classes/User.php');
    include_once('../../includes/classes/Email.php');

    require_once __DIR__ . '../../../vendor/autoload.php';

    $back_url = "index.php";


    $user = new User($con, $superAdminUserId);
    // var_dump($superAdminUserId);
    $adminName = ucwords($user->getFirstName());

    $usersNextUniqueId = $user->generateNextUniqueUserId();

    $userRandomPassword = $user->generateRandomPassword();

    // var_dump($userRandomPassword);


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
        $userEmail = $_POST['email'];
        $role = $_POST['role'];

        // $default_password = 123456;

        $username = "";

        # H = Super Admin
        # A = Admin
        # R = Registrar
        # C = Cashier

        if($role == "Administrator"){
            $username = trim(strtolower($lastname)).".".$usersNextUniqueId."A@dcbt.edu.ph";

        }

        if($role == "Cashier"){
            $username = trim(strtolower($lastname)).".".$usersNextUniqueId."C@dcbt.edu.ph";
            
        }

        if($role == "Registrar"){
            $username = trim(strtolower($lastname)).".".$usersNextUniqueId."R@dcbt.edu.ph";
        }
         

        // $default_password = password_hash($userRandomPassword, PASSWORD_BCRYPT);

        $default_password = $userRandomPassword;
        

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
            $userEmail, $role, $default_password,
            $imagePath, $userRandomPassword, $username, $usersNextUniqueId);
            
        if($user_create){

            try {

                $email = new Email();

                if (!empty($userEmail) && filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {

                    $isEmailSent = $email->SendUserCredentialsAfterCreation(
                            $userEmail, $username, $default_password, $adminName);

                    if ($isEmailSent) {

                        Alert::success("User credentials has been delivered to provided email: $userEmail", "index.php");
                        exit();

                    } else {
                        echo "Sending credentials via email went wrong";
                    }
                } 
                else {
                    echo "Invalid provided email address";
                }

            } catch (Exception $e) {
                // Handle PHPMailer exceptions
                echo 'Message could not be sent. PHPMailer Error: ' . $e->getMessage();
                // Handle other exceptions as needed
            }

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
                                <label class='mb-2'>Photo</label>
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





