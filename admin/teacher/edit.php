<?php 

  include_once('../../includes/admin_header.php');
  include_once('../../includes/classes/Teacher.php');
      include_once('../../includes/classes/SchoolYear.php');
 
  if(isset($_GET['id'])){

    $teacher_id = $_GET['id'];

    $teacher = new Teacher($con, $teacher_id);

    $firstname = $teacher->GetTeacherFirstName();
    $middle_name = $teacher->GetTeacherMiddleName();
    $lastname = $teacher->GetTeacherLastName();
    $suffix = $teacher->GetTeacherSuffix();
    $department_id = $teacher->GetDepartmentId();
    $profilePic = $teacher->GetTeacherProfile();
    $gender = $teacher->GetTeacherGender();
    $email = $teacher->GetTeacherEmail();
    $contact_number = $teacher->GetTeacherContactNumber();
    $address = $teacher->GetTeacherAddress();
    $citizenship = $teacher->GetTeacherCitizenship();
    $birthplace = $teacher->GetTeacherBirthplace();
    $birthday = $teacher->GetTeacherBirthday();
    $religion = $teacher->GetTeacherReligion();
    $status = $teacher->GetStatus();

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    if(isset($_POST['edit_teacher_btn_' . $teacher_id])){

        $firstname = $_POST['firstname'];
        $middle_name = $_POST['middle_name'];
        $lastname = $_POST['lastname'];
        $suffix = $_POST['suffix'];
        $department_id = $_POST['department_id'];

        // $profilePic = $_POST['profilePic'];

        // $profilePic = "";
        $gender = $_POST['gender'];
        $email = $_POST['email'];
        $contact_number = $_POST['contact_number'];
        $address = $_POST['address'];
        $citizenship = $_POST['citizenship'];
        $birthplace = $_POST['birthplace'];
        $birthday = $_POST['birthday'];
        $religion = $_POST['religion'];

        $teacher_status = $_POST['teacher_status'];

        $hasError = false;

        $chech = $teacher->CheckTeacherExistsBasedOnFirstLastMiddleEmail(
            $firstname, $lastname, $middle_name, $email);

        if($chech == true){
            $hasError = true;

            Alert::error("Teacher already exists with the provided credentials", "");
            // exit();
        }

        // var_dump($teacher_status);
        // return;
        // echo $status;



        // if (!is_dir('../../assets')) {
        //     mkdir('../../assets');
        // }

        // if (!is_dir('../../assets/images')) {
        //     mkdir('../../assets/images');
        // }

        // if (!is_dir('../../assets/images/teacher_profiles')) {
        //     mkdir('../../assets/images/teacher_profiles');
        // }

        if($hasError == false){

            $image = $_FILES['profilePic'] ?? null;
            
            // var_dump($image);

            $db_image = "../../" . $profilePic;

            $imagePath = '';

            if ($image && $image['tmp_name']) {

                $uploadDirectory = '../../assets/images/teacher_profiles/';
                $originalFilename = $image['name'];
                $uniqueFilename = uniqid() . '_' . time() . '_' . $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                // Delete the existing file
                if (file_exists($db_image)) {

                    // echo $db_image;
                    unlink($db_image);
                }

                // Upload the new file
                move_uploaded_file($image['tmp_name'], $targetPath);
                $imagePath = $targetPath;
                $imagePath = str_replace('../../', '', $imagePath);
            }

            $query = "UPDATE teacher SET 
                firstname = :firstname,
                middle_name = :middle_name,
                lastname = :lastname,
                suffix = :suffix,
                department_id = :department_id,
                profilePic = :profilePic,
                gender = :gender,
                email = :email,
                contact_number = :contact_number,
                address = :address,
                citizenship = :citizenship,
                birthplace = :birthplace,
                birthday = :birthday,
                religion = :religion,
                teacher_status = :teacher_status

            WHERE teacher_id = :teacher_id";

            $update = $con->prepare($query);

            $update->bindParam(':firstname', $firstname);
            $update->bindParam(':middle_name', $middle_name);
            $update->bindParam(':lastname', $lastname);
            $update->bindParam(':suffix', $suffix);
            $update->bindParam(':department_id', $department_id);
            $update->bindParam(':profilePic', $imagePath);
            $update->bindParam(':gender', $gender);
            $update->bindParam(':email', $email);
            $update->bindParam(':contact_number', $contact_number);
            $update->bindParam(':address', $address);
            $update->bindParam(':citizenship', $citizenship);
            $update->bindParam(':birthplace', $birthplace);
            $update->bindParam(':birthday', $birthday);
            $update->bindParam(':religion', $religion);
            $update->bindParam(':teacher_status', $teacher_status);
            $update->bindParam(':teacher_id', $teacher_id);

            if ($update->execute()) {
                Alert::success("Teacher successfully modified", "index.php");
                exit();
            }
        }

    }

    $department_selection = $teacher->CreateTeacherDepartmentSelection($department_id);

    ?>
    <body>
        <div class="content">
            <nav>
                <a href="index.php">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>
            <div class="floating noBorder">
                <main>
                    <form method="post" enctype="multipart/form-data">
                        <div class="row">
                            <span>
                                <label for="name">Name</label>
                                <div>
                                    <input class="form-control" type="text" name="firstname" value="<?php echo $firstname; ?>">
                                    <small>First Name</small>
                                </div>
                                <div>
                                    <input class="form-control" type="text" name="middle_name" value="<?php echo $middle_name; ?>">
                                    <small>Middle Name</small>
                                </div>
                                <div>
                                    <input class="form-control" type="text" name="lastname" value="<?php echo $lastname; ?>">
                                    <small>Last Name</small>
                                </div>
                                <div>
                                    <input class="form-control" type="text" name="suffix" value="<?php echo $suffix; ?>">
                                    <small>Suffix</small>
                                </div>
                            </span>
                        </div>
                        <div class="row">
                            <span>
                                <label for="profilePic">Profile Pic</label>
                                <div>
                                <input class="form-control" type="file" name="profilePic">
                                    <?php if ($profilePic): ?>
                                        <img style="width: 150px; border-radius: 100%;"src="<?php echo "../../".$profilePic; ?>" alt="Profile Picture">
                                    <?php else: ?>
                                        <small>No profile picture available</small>
                                    <?php endif; ?>
                                </div>
                            </span>
                            <span>
                                <?php echo $department_selection; ?>
                            </span>
                        </div>
                        <div class="row">
                            <span>
                                <label for="gender">Gender</label>
                                <div>
                                    <select class="form-control" name="gender" id="gender" required>
                                        <option value="Male"<?php echo ($gender === 'Male' ? ' selected' : ''); ?>>Male</option>
                                        <option value="Female"<?php echo ($gender === 'Female' ? ' selected' : ''); ?>>Female</option>
                                    </select>
                                </div>
                            </span>
                            <span>
                                <label for="email">Email</label>
                                <div>
                                    <input class="form-control" type="text" name="email" value="<?php echo $email; ?>">
                                </div>
                            </span>
                            <span>
                                <label for="contact_number">Contact Number</label>
                                <div>
                                    <input class="form-control" type="text" name="contact_number" value="<?php echo $contact_number; ?>">
                                </div>
                            </span>
                        </div>
                        <div class="row">
                            <span>
                                <label for="address">Address</label>
                                <div>
                                    <input class="form-control" type="text" name="address" value="<?php echo $address; ?>">
                                </div>
                            </span>
                        </div>
                        <div class="row">
                            <span>
                                <label for="citizenship">Citizenship</label>
                                <div>
                                    <input class="form-control" type="text" name="citizenship" value="<?php echo $citizenship; ?>">
                                </div>
                            </span>
                            <span>
                                <label for="birthplace">Birthplace</label>
                                <div>
                                    <input class="form-control" type="text" name="birthplace" value="<?php echo $birthplace; ?>">
                                </div>
                            </span>
                            <span>
                                <label for="birthday">Birthday</label>
                                <div>
                                    <input class="form-control" type="date" name="birthday" value="<?php echo $birthday; ?>">
                                </div>
                            </span>
                            <span>
                                <label for="religion">Religion</label>
                                <div>
                                    <input class="form-control" type="text" name="religion" value="<?php echo $religion; ?>">
                                </div>
                            </span>
                        </div>
                        <header>
                            <div class="title">
                                <h3 style="color: black;">Status</h3>
                            </div>
                        </header>
                        <div class="row">
                            <span>
                                <div class="form-element">
                                    <label for="active">Active</label>
                                    <div>
                                        <input class="form-control" id="active" type="radio" name="teacher_status" value="Active"<?php echo ($status === 'Active' ? ' checked' : ''); ?>>
                                    </div>
                                </div>
                                <div class="form-element">
                                    <label for="inactive">Inactive</label>
                                    <div>
                                        <input class="form-control" id="inactive" type="radio" name="teacher_status" value="Inactive"<?php echo ($status === 'Inactive' ? ' checked' : ''); ?>>
                                    </div>
                                </div>
                            </span>
                        </div>
                        <div class="action">
                            <button type="submit" class="clean large" name="edit_teacher_btn_<?php echo $teacher_id?>">Save</button>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </body>
    <?php
    }
    ?>

