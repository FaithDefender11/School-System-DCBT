<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    // include_once('../../assets/images/');

    $teacher = new Teacher($con);

    // $form = $teacher->createTeacherForm();
    $department_selection = $teacher->CreateTeacherDepartmentSelection();

    if(isset($_POST['create_teacher_btn'])){

        $firstname = $_POST['firstname'];
        $middle_name = $_POST['middle_name'];
        $lastname = $_POST['lastname'];
        $suffix = $_POST['suffix'];
        $department_id = $_POST['department_id'];
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $contact_number = $_POST['contact_number'];
        $address = $_POST['address'];
        $citizenship = $_POST['citizenship'];
        $birthplace = $_POST['birthplace'];
        $birthday = $_POST['birthday'];
        $religion = $_POST['religion'];

        $password = "123456";

        $status = "active";

        $hash_password = password_hash($password, PASSWORD_BCRYPT);


        $image = $_FILES['profilePic'] ?? null;
        $imagePath = '';

        if (!is_dir('../../assets')) {
            mkdir('../../assets');
        }

        if (!is_dir('../../assets/images')) {
            mkdir('../../assets/images');
        }

        if (!is_dir('../../assets/images/teacher_profiles')) {
            mkdir('../../assets/images/teacher_profiles');
        }

        if ($image && $image['tmp_name']) {

            $uploadDirectory = '../../assets/images/teacher_profiles/';
            $originalFilename = $image['name'];

            $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
            $targetPath = $uploadDirectory . $uniqueFilename;

            move_uploaded_file($image['tmp_name'], $targetPath);

            $imagePath = $targetPath;

            // Remove Directory Path in the Database.
            $imagePath = str_replace('../../', '', $imagePath);

        }

        $query = "INSERT INTO teacher (password, firstname, middle_name, lastname, suffix, department_id, profilePic, gender, email, contact_number,
                address, citizenship, birthplace, birthday, religion, teacher_status) 

            VALUES (:password, :firstname, :middle_name, :lastname, :suffix, :department_id, :profilePic, :gender, :email, :contact_number,
                :address, :citizenship, :birthplace, :birthday, :religion, :teacher_status)";

        $statement = $con->prepare($query);

        $statement->bindParam(':password', $hash_password);
        $statement->bindParam(':firstname', $firstname);
        $statement->bindParam(':middle_name', $middle_name);
        $statement->bindParam(':lastname', $lastname);
        $statement->bindParam(':suffix', $suffix);
        $statement->bindParam(':department_id', $department_id);
        $statement->bindParam(':profilePic', $imagePath);
        $statement->bindParam(':gender', $gender);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':contact_number', $contact_number);
        $statement->bindParam(':address', $address);
        $statement->bindParam(':citizenship', $citizenship);
        $statement->bindParam(':birthplace', $birthplace);
        $statement->bindParam(':birthday', $birthday);
        $statement->bindParam(':religion', $religion);
        $statement->bindParam(':teacher_status', $status);

        if ($statement->execute()) {
            Alert::success("Successfully Created", "index.php");
            exit();
        }
        //  else {
        //     Alert::error("Error Occured", "index.php");
        //     exit();
        // }
    }
    
    ?>
        <div class='col-md-12 row'>
            <div class="col-md-10 offset-md-1">
            <div class='card'>
                <hr>
                <a href="index.php">
                    <button class="btn   btn-primary">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </a>
                <div class='card-header'>
                    <h4 class='text-center mb-3'>Create Teacher</h4>
                </div>
                <div class='card-body'>
                    <form method='POST' enctype='multipart/form-data'>
                        <div class='form-group mb-2'>
                            <label for=''>First Name</label>
                            <input class='form-control' type='text' placeholder='' name='firstname'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Middle Name</label>
                            <input class='form-control' placeholder='' name='middle_name'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Last Name</label>
                            <input class='form-control' type='text' placeholder='' name='lastname'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Suffix</label>
                            <input class='form-control' type='text' placeholder='' name='suffix'>
                        </div>
                        <div class='form-group mb-2'>
                            <?php echo $department_selection; ?>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Profile Pic</label>
                            <input required class='form-control' type='file' placeholder='' name='profilePic'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Gender</label>
                            <select class='form-control' required name='gender' id='gender'>
                                <option value='Male'>Male</option>
                                <option value='Female'>Female</option>
                            </select>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Email</label>
                            <input class='form-control' type='text' placeholder='' name='email'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Contact No.</label>
                            <input class='form-control' type='text' placeholder='' name='contact_number'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Address</label>
                            <input class='form-control' type='text' placeholder='' name='address'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Citizenship</label>
                            <input class='form-control' type='text' placeholder='' name='citizenship'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Birth Place</label>
                            <input class='form-control' type='text' placeholder='' name='birthplace'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Birth Date</label>
                            <input class='form-control' type='date' placeholder='' name='birthday'>
                        </div>
                        <div class='form-group mb-2'>
                            <label for=''>Religion</label>
                            <input class='form-control' type='text' placeholder='' name='religion'>
                        </div>
                        <button type='submit' class='btn btn-success' name='create_teacher_btn'>Save</button>
                    </form>
                </div>
            </div>
            </div>

        </div>
    <?php
?>