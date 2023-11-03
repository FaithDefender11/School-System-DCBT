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
    <body>
        <div class="content">
            <nav>
                <a href="index.php">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>
            <main>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <span>
                            <label for="name">Name</label>
                            <div>
                                <input type="text" name="firstname" placeholder="">
                                <small>First Name</small>
                            </div>
                            <div>
                                <input type="text" name="middle_name" placeholder="">
                                <small>Middle Name</small>
                            </div>
                            <div>
                                <input type="text" name="lastname" placeholder="">
                                <small>Last Name</small>
                            </div>
                            <div>
                                <input type="text" name="suffix" placeholder="">
                                <small>Suffix</small>
                            </div>
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <label for="gender">Gender</label>
                            <div>
                                <select name="gender" id="gender">
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                        </span>
                        <span>
                            <label for="birthday">Birthday</label>
                            <div>
                                <input type="date" name="birthday" placeholder="">
                            </div>
                        </span>
                        <span>
                            <label for="birthplace">Birthplace</label>
                            <div>
                                <input type="text" name="birthplace" placeholder="">
                            </div>
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <label for="email">Email</label>
                            <div>
                                <input type="email" name="email" placeholder="">
                            </div>
                        </span>
                        <span>
                            <label for="contact">Contact No.</label>
                            <div>
                                <input type="text" name="contact_number" placeholder="">
                            </div>
                        </span>
                        <span>
                            <label for="religion">Religion</label>
                            <div>
                                <input type="text" name="religion" placeholder="">
                            </div>
                        </span>
                        <span>
                            <label for="citizenship">Citizenship</label>
                            <div>
                                <input type="text" name="citizenship" placeholder="">
                            </div>
                        </span>
                    </div>
                    <div class="row">
                        <span>
                            <label for="address">Address</label>
                            <div>
                                <input type="text" name="address" placeholder="">
                            </div>
                        </span>
                    </div>
                    <div class="row">
                        <?php echo $department_selection; ?>
                    </div>
                    <div class="row">
                        <span>
                            <label for="profilePic">Profile Pic</label>
                            <div>
                                <input type="file" name="profilePic" placeholder="" required>
                            </div>
                        </span>
                    </div>
                    <div class="action">
                        <button type="submit" class="clean large" name="create_teacher_btn">Save</button>
                    </div>
                </form>
            </main>
        </div>
    </body>
    <?php
?>