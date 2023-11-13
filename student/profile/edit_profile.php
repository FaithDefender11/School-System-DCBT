<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Student.php');

    if(isset($_GET['id'])) {
        $student_id = $_GET['id'];

        $student = new Student($con, $student_id);

        $profilePic = $student->GetStudentProfile();
        $address = $student->GetStudentAddress();
        $contact_number = $student->GetContactNumber();

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_profile_changes_' . $student_id])) {
            $address = $_POST['address'];
            $contact_number = $_POST['contact_number'];
            $image = $_FILES['profilePic'] ?? null;

            $db_image = "../../" . $profilePic;
            $imagePath = '';

            if ($image && $image['tmp_name']) {
                $uploadDirectory = '../../assets/images/users/';
                $originalFilename = $image['name'];
                $uniqueFilename = $originalFilename;
                $targetPath = $uploadDirectory . $uniqueFilename;

                // Delete the existing file
                if (file_exists($db_image) && is_file($db_image)) {
                    // echo $db_image
                    unlink($db_image);
                }

                // Upload the new file
                move_uploaded_file($image['tmp_name'], $targetPath);
                $imagePath = $targetPath;
                $imagePath = str_replace('../../', '', $imagePath);
            }

            $query = "UPDATE student SET
                profilePic = :profilePic,
                contact_number = :contact_number,
                address = :address
                
                WHERE student_id = :student_id";

            $update = $con->prepare($query);

            $update->bindParam(':profilePic', $imagePath);
            $update->bindParam(':contact_number', $contact_number);
            $update->bindParam(':address', $address);
            $update->bindParam(':student_id', $student_id);

            if ($update->execute()) {
                Alert::success("Successfully Edited", "my_profile.php");
                header("Location: my_profile.php?id=$student_id");
                exit();
            }
            
        }

    $back_url = "my_profile.php?id=$student_id";
?>

            <main>
                <div class="floating noBorder">
                    <header>
                        <div class="title">
                            <h3>Edit Profile</h3>
                        </div>
                        <div class="action">
                            <button 
                                class="danger"
                                onclick="window.location.href='<?= $back_url; ?>'"
                            >
                                Cancel
                            </button>
                        </div>
                    </header>
                    <main>
                        <form method="POST" enctype="multipart/form-data">
                            <header>
                                <div class="title">
                                    <h3>User Image</h3>
                                    <small
                                        >We will reject any photo that includes guns, knives, acts
                                        of violence, morbidity, vulgar language, nudity, or any
                                        other kind of content that we think is not suitable for an
                                        education site.</small
                                    >
                                </div>
                            </header>
                            <div class="row">
                                <span>
                                    <div>
                                        <input type="file" name="profilePic" id="profilePic">
                                        <?php if ($profilePic): ?>
                                            <img src="<?= "../../".$profilePic; ?>" alt="Profile Picture" style="width: 200px; height: 200px">
                                        <?php else: ?>
                                            <img src="../../assets/images/users/Blank.png" alt="Profile Picture" style="width: 200px; height: 200px">
                                        <?php endif; ?>
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="address">Address</label>
                                    <div>
                                        <input type="text" name="address" id="address">
                                    </div>
                                </span>
                            </div>
                            <div class="row">
                                <span>
                                    <label for="contact_number">Contact No.</label>
                                    <div>
                                        <input type="text" name="contact_number" id="contact_number">
                                    </div>
                                </span>
                            </div>
                            <div class="action">
                                <button type="submit" class="clean large" name="save_profile_changes_<?= $student_id; ?>">Save Changes</button>
                            </div>
                        </form>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    </body>
</html>