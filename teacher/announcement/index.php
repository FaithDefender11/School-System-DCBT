<?php 

    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/TaskType.php');
    include_once('../../includes/classes/Enrollment.php');
 
    // echo Helper::RemoveSidebar();
    

    if(
        isset($_GET['c_id'])
        && isset($_GET['c'])
        && isset($_GET['sy_id'])
        
        ){

        $course_id = $_GET['c_id'];
        $subject_code = $_GET['c'];
        $sy_id = $_GET['sy_id'];

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $section = new Section($con);

        $announcement = new Announcement($con);

        $teacher_id = $_SESSION['teacherLoggedInId'];

        $announcementList = $announcement->GetAnnouncementsWithinSubjectCode($subject_code, $teacher_id);
    
        // $back_url = "../class/index.php?c_id=1279&c=$subject_code";
        // $current_school_year_id

        $back_url = "../class/index.php?c=$subject_code&sy_id=$sy_id";

        ?>
            <div class="content">

                <nav>
                    <a href="<?php echo $back_url;?>">
                            <i class="bi bi-arrow-return-left fa-1x"></i>
                            <h3>Back</h3>
                    </a>
                </nav>

                <main style="overflow-x: auto">
                
                    <div class="floating" id="shs-sy">

                        <header>

                            <div class="title">
                                <h4>Announcement</h4>
                            </div>

                            <div class="action">
                                <a href="add_announcement.php?c_id=<?= $course_id?>&c=<?=$subject_code;?>">
                                    <button type="button" class="default success large">+ Add announcement</button>
                                </a>
                            </div>
                        </header>

                        <main>

                            <?php if(count($announcementList ) > 0):?>

                                    <table style="width: 100%" id="admin_announcement_table" class="a" >
                                        
                                        <thead>
                                            <tr>
                                                <th>Subject</th>  
                                                <th>To whom</th>
                                                <th>Date announced</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php 

                                                foreach ($announcementList   as $key => $value) {

                                                    $announcement_id = $value['announcement_id'];

                                                    $title = $value['title'];
                                                    $users_id = $value['users_id'];
                                                    $content = $value['content'];
                                                    $date_creation_db = $value['date_creation'];

                                                    $for_student = $value['for_student'];
                                                    $teachers_id = $value['teachers_id'];

                                                    // var_dump($for_student);
                                                    // echo "<br>";

                                                    $text = "";

                                                    if($for_student != NULL && $teachers_id != ""){
                                                        $text = "Teachers and Students";
                                                    }

                                                    if($for_student != NULL && $teachers_id == ""){
                                                        $text = "Students";
                                                    }
                                                    if($for_student == NULL && $teachers_id != ""){
                                                        $text = "Teachers";
                                                    }

                                                    $date_creation = date("M d, Y h:i a", strtotime($date_creation_db));


                                                    $removeAnnouncement = "removeAnnouncement($announcement_id, $users_id)";
                                                    
                                                    echo "
                                                        <tr>
                                                            <td>$title</td>
                                                            <td>$text</td>
                                                            <td>$date_creation</td>

                                                            <td>

                                                                <a href='edit.php?id=$announcement_id'>
                                                                    <button class='btn-sm btn btn-primary'>
                                                                        <i class='fas fa-marker'></i>
                                                                    </button>
                                                                </a>

                                                                <button onclick='$removeAnnouncement' class='btn-sm btn btn-danger'>
                                                                    <i class='fas fa-trash'></i>
                                                                </button>

                                                            </td>

                                                        </tr>
                                                    ";
                                                }
                                            ?>

                                        </tbody>
                                    </table>

                                <?php else:?>
                                <div class="col-md-12">
                                    <br>
                                    <br>
                                    <br>
                                    <h2 class="text-center text-primary">No announcement posted</h2>
                                </div>
                            <?php endif;?>
                        </main>
                    </div>
                </main>
                
            </div>
        <?php

    }
        
   
?>




<script>

    function removeAnnouncement(announcement_id, users_id){

        var announcement_id = parseInt(announcement_id);
        var users_id = parseInt(users_id);

        Swal.fire({
                icon: 'question',
                title: `Are you sure you want to remove selected announcement?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/announcement/removeAnnouncement.php",
                        type: 'POST',
                        data: {
                            announcement_id, users_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Deleted`,
                                showConfirmButton: false,
                                timer: 1100, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                $('#admin_announcement_table').load(
                                    location.href + ' #admin_announcement_table'
                                );

                                // location.reload();
                            });}

                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
</script>