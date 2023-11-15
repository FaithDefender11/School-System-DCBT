<?php
    include_once("../../includes/teacher_header.php");
    include_once("../../includes/classes/Teacher.php");
    include_once('../../includes/classes/Enrollment.php');
    include_once("../../includes/classes/SchoolYear.php");
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Notification.php');

    if(isset($GET['id'])) {
        $teacher_id = $_GET['id'];

        $teacher = new Teacher($con, $teacher_id);

        if($teacher) {
            $profilePic = $teacher->GetTeacherProfile();
            $address = $teacher->GetTeacherAddress();
            $contact_number = $teacher->GetTeacherContactNumber();
        } else {
            echo "Teacher not found.";
        }

        $teacher_id = $_SESSION['$teacherLoggedIn'];
    
?>


            <main>
                <div class="floating profile">
                    <div class="img-container">
                        <?php if($profilePic): ?>
                            <img src="<?= "../../" . $profilePic; ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img src="../../assets/images/users/Blank.png" alt="Profile Picture">
                        <?php endif; ?>
                    </div>
                    <div class="user-description">
                        <h4><?= $teacherLoggedInObj->GetTeacherLastName(); ?>, <?= $teacherLoggedInObj->GetTeacherFirstName(); ?></h4>
                        <small>Daehan College Of Business & Technology</small>
                    </div>
                    <div class="action">
                        <button 
                            class="information"
                            onclick="window.location.href='edit_profile.php?id=<?= $teacherLoggedInObj->GetTeacherId(); ?>'"
                        >
                            Edit
                        </button>
                    </div>
                </div>

                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Info</h3>
                        </div>
                    </header>
                    <main>
                        <ul>
                            <li>Role: <span>Teacher</span></li>
                            <li>Department: <span><?= $teacherLoggedInObj->GetDepartmentName(); ?></span></li>
                        </ul>
                    </main>
                    <header>
                        <div class="title">
                            <h3>Contact</h3>
                        </div>
                    </header>
                    <main>
                        <ul>
                            <li>Email: <a href="#"><?= $teacherLoggedInObj->GetTeacherEmail(); ?></a></li>
                            <li>Contact No: <span><?= $teacherLoggedInObj->GetTeacherContactNumber(); ?></span></li>
                        </ul>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    </body>
</html>