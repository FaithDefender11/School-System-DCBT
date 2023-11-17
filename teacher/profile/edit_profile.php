<?php
    include_once("../../includes/teacher_header.php");
    include_once("../../includes/classes/Teacher.php");
    include_once("../../includes/classes/SchoolYear.php");

    if(isset($_GET['id'])) {
        $teacher_id = $_GET['id'];

        $teacher = new Teacher($con, $teacher_id);

        $profilePic = $teacher->GetTeacherProfile();
        $address = $teacher->GetTeacherAddress();
        $contact_number = $teacher->GetTeacherContactNumber();

        if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile_changes' . $teacher_id])) {
            $image = $_FILES['profilePic'] ?? null;
            $address = $_POST['address'];
            $contact_number = $_POST['contact_number'];

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

            $query = "UPDATE teacher SET
                profilePic = :profilePic,
                contact_number = :contact_number,
                address = :address 
                WHERE teacher_id = :teacher_id";
            $update = $con->prepare($query);

            $update->bindParam('profilePic', $imagePath);
            $update->bindParam(':contact_number', $contact_number);
            $update->bindParam(':address', $address);
            $update->bindParam(':teacher_id', $teacher_id);

            if ($update->execute()) {
                Alert::success("Successfully Edited", "my_profile.php");
                header("Location: my_profile.php?id=$teacher_id");
                exit();
            }
        }

        $back_url = "my_profile.php?id=$teacher_id";
    
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
                                <button type="submit" class="clean large" name="save_profile_changes_<?= $teacher_id; ?>">Save Changes</button>
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
