<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/TaskType.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Teacher.php');

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];


    $enrollment = new Enrollment($con);
    $announcement = new Announcement($con);


    $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($studentLoggedInId,
        $current_school_year_id);

    $studentSubject = new StudentSubject($con);

    $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
        ($studentLoggedInId, $current_school_year_id, $enrollment_id);

    $enrolledSubjectList = [];

    foreach ($allEnrolledSubjectCode as $key => $value) {
        # code...
        $subject_code = $value['student_subject_code'];
        array_push($enrolledSubjectList, $subject_code);
    }

    $getAllAnnouncementOnMyEnrolledSubjects = $announcement->GetAllTeacherAnnouncementUnderEnrolledSubjects(
        $current_school_year_id, $enrolledSubjectList);

    // var_dump($getAllAnnouncementOnMyEnrolledSubjects);


    $getAllAnnouncementFromAdmin = $announcement->GetAllAnnouncementFromAdmin(
        $current_school_year_id);

    $mergeAnnouncement = array_merge($getAllAnnouncementFromAdmin,
        $getAllAnnouncementOnMyEnrolledSubjects);

    // var_dump($mergeAnnouncement);

    $stmt = $con->prepare("SELECT COUNT(*) as announcement_count FROM announcement");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $announcementCount = $result['announcement_count'];
    // var_dump($announcementCount);

    $back_url = "student_dashboard.php";
?>

            <?php
                echo Helper::lmsStudentNotificationHeader(
                    $con, $studentLoggedInId,
                    $current_school_year_id,
                    $enrolledSubjectList,
                    $enrollment_id,
                    "second",
                    "second",
                    "second",
                    "first"
                );
            ?>

            <nav>
                <a href="<?= $back_url; ?>">
                    <i class="bi bi-arrow-return-left"></i>
                    Back
                </a>
            </nav>

            <main>
                <div class="floating">
                    <header>
                        <div class="title">
                            <h3>Announcement for <em><?= "$current_school_year_term $current_school_year_period Semester"?></em></h3>
                        </div>
                    </header>
                    <main style="overflow-x: auto">
                        <?php if(count($getAllAnnouncementOnMyEnrolledSubjects) > 0):?>
                            <table class="a" id="teacher_global_announcement_table">
                                <thead>
                                    <tr>
                                        <th>Subject</th>  
                                        <th>By whom</th>
                                        <th>To whom</th>
                                        <th>Date announced</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        foreach ($mergeAnnouncement as $key => $value) {

                                            $announcement_id = $value['announcement_id'];

                                            $title = $value['title'];
                                            $teacher_id = $value['teacher_id'];
                                            $content = $value['content'];
                                            $subject_code = $value['subject_code'];
                                            $role = $value['role'];
                                            $for_student = $value['for_student'];
                                            $users_id = $value['users_id'];

                                            // var_dump($subject_code);

                                            $toWhomName = "";
                                            $byWhomName = "";

                                            $announcement_creation_db = $value['date_creation'];

                                            $text = "";
                                            $announcement_creation = date("M d, Y h:i a", strtotime($announcement_creation_db));
                                            
                                            if($role == "teacher" 
                                                && $teacher_id != NULL
                                                && $for_student == NULL){

                                                $teacher = new Teacher($con, $teacher_id);
                                                $toWhomName = $value['subject_code'];

                                                $byWhomName = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());

                                            }
                                            if($role == "admin"
                                                && $teacher_id == NULL
                                                && $for_student != NULL
                                                && $users_id != NULL){

                                                // echo "qwe";
                                                $toWhomName = "All Students";

                                                $user = new User($con, $users_id);
                                                $byWhomName = ucwords($user->getFirstName()) . " " . ucwords($user->getLastName());
                                            }

                                            echo "
                                                <tr>
                                                    <td>$title</td>
                                                    <td>$byWhomName</td>
                                                    <td>$toWhomName</td>
                                                    <td>$announcement_creation</td>
                                                    <td>
                                                        <a href='../dashboard/announcement.php?id=$announcement_id'>
                                                            <button class='btn-sm btn btn-primary'>
                                                                <i class='fas fa-eye'></i>
                                                            </button>
                                                        </a>
                                                    </td>
                                                </tr>
                                            ";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <h4 style="text-align: center">No Announcement</h4>
                        <?php endif; ?>
                    </main>
                </div>
            </main>
        </div>
        <script>
            $(document).ready(function () {

                // Function to check for updates
                // function checkForUpdates(lastCount) {
                //     $.ajax({
                //         url: 'check_updates.php', // PHP file to check updates
                //         type: 'GET',
                //         data: { last_count: lastCount }, // Send the client's last count
                //         success: function (data) {
                //             data = data.trim();

                //             console.log(data)
                            
                //             if (data == 'update_available') {
                //                 // Reload the page if an update is available
                //                 location.reload(true);
                //             }

                //         },
                //         complete: function () {
                //             // Schedule the next check after a certain interval (e.g., every 5 seconds)
                //             setTimeout(function() {
                //                 checkForUpdates(<?php echo $announcementCount; ?>); // Corrected PHP echo
                //             }, 5000);
                //         }
                //     });
                // }

                // Initial check when the page loads
                // checkForUpdates(<?php echo $announcementCount; ?>);
            });
        </script>
    </body>
</html>