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
        $update->bindParam(':teacher_status', $status);
        $update->bindParam(':teacher_id', $teacher_id);

        if ($update->execute()) {
            Alert::success("Successfully Edited", "index.php");
            exit();
        }
    }

    $department_selection = $teacher->CreateTeacherDepartmentSelection($department_id);

    ?>
        <div class="col-md-12 row">
            <div class='col-md-10 offset-md-1'>

                <div class='card'>
                    <hr>
                    <a href="index.php">
                        <button class="btn   btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>
                    <div class='card-header'>
                        <h4 class='text-center mb-3'>Edit Teacher</h4>
                    </div>

                    <div class='card-body'>
                        <form method='POST' enctype='multipart/form-data'>
                            <div class='form-group mb-2'>
                                <label for=''>First Name</label>
                                <input class='form-control' type='text' value='<?php echo $firstname; ?>' name='firstname'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Middle Name</label>
                                <input class='form-control' value='<?php echo $middle_name; ?>' name='middle_name'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Last Name</label>
                                <input class='form-control' type='text' value='<?php echo $lastname; ?>' name='lastname'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Suffix</label>
                                <input class='form-control' type='text' value='<?php echo $suffix; ?>' name='suffix'>
                            </div>
                            <div class='form-group mb-2'>
                                <?php echo $department_selection; ?>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Profile Pic</label>
                                <input class='form-control' type='file' name='profilePic'>
                                <?php if ($profilePic): ?>
                                    <img style="width: 150px; border-radius: 100%;" 
                                    src='<?php echo "../../".$profilePic; ?>' alt='Profile Picture' class='preview-image'>
                                <?php else: ?>
                                    <span class='text-muted'>No profile picture available</span>
                                <?php endif; ?>
                            </div>

                            <div class='form-group mb-2'>
                                <label for=''>Gender</label>
                                <select class='form-control' required name='gender' id='gender'>
                                    <option value='Male'<?php echo ($gender === 'Male' ? ' selected' : ''); ?>>Male</option>
                                    <option value='Female'<?php echo ($gender === 'Female' ? ' selected' : ''); ?>>Female</option>
                                </select>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Email</label>
                                <input class='form-control' type='text' value='<?php echo $email; ?>' name='email'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Contact No.</label>
                                <input class='form-control' type='text' value='<?php echo $contact_number; ?>' name='contact_number'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Address</label>
                                <input class='form-control' type='text' value='<?php echo $address; ?>' name='address'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Citizenship</label>
                                <input class='form-control' type='text' value='<?php echo $citizenship; ?>' name='citizenship'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Birth Place</label>
                                <input class='form-control' type='text' value='<?php echo $birthplace; ?>' name='birthplace'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Birth Date</label>
                                <input class='form-control' type='date' value='<?php echo $birthday; ?>' name='birthday'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Religion</label>
                                <input class='form-control' type='text' value='<?php echo $religion; ?>' name='religion'>
                            </div>
                            <div class='form-group mb-2'>
                                <label for=''>Status</label>
                                <div>
                                    <label>
                                        <input type='radio' name='status' value='active'<?php echo ($status === 'Active' ? ' checked' : ''); ?>> Active
                                    </label>
                                    <label>
                                        <input type='radio' name='status' value='non-active'<?php echo ($status === 'Inactive' ? ' checked' : ''); ?>> Inactive
                                    </label>
                                </div>
                            </div>
                            <button type='submit' class='btn btn-success' name='edit_teacher_btn_<?php echo $teacher_id?>'>Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php
}
?>

