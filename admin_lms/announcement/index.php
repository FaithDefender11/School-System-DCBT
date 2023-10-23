<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Announcement.php');

    // echo Helper::RemoveSidebar();

    
    ?>

        <head>

            <style>
                .show_search{
                    position: relative;
                    /* margin-top: -38px;
                    margin-left: 215px; */
                }
                div.dataTables_length {
                    display: none;
                }

                #enrolled_students_table_filter{
                margin-top: 12px;
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: start;
                }

                #enrolled_students_table_filter input{
                width: 250px;
                }
            </style>

            <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        </head>

    <?php
    
    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $section = new Section($con);
    $announcement = new Announcement($con);

    // $enrolledSection = $section->GetAllEnrolledStudentSections($current_school_year_id);
    $sectionEnrolledStudentList = $section->GetCurrentSectionWithEnrolledStudent($current_school_year_id);


    $adminAnnouncement = $announcement->GetAdminAnnouncementList(null,
        $adminUserId);

    // var_dump($adminAnnouncement);
    
?>



<div class="content">


    <main>
     
        <div class="floating" id="shs-sy">

            <header>

                <div class="title">
                    <h4>Announcement</h4>
                </div>

                <div class="action">
                    <a href="create.php">
                        <button type="button" class="default success large">+ Add new</button>
                    </a>
                </div>
                
            </header>

            <main>

               <?php if(count($adminAnnouncement) > 0):?>

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

                                foreach ($adminAnnouncement as $key => $value) {

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


                                    // <a href=''>
                                    //     <button class='btn-sm btn btn-info'>
                                    //         <i class='fas fa-eye'></i>
                                    //     </button>
                                    // </a>

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
                <?php endif;?>


            </main>
        </div>
    </main>
    
</div>


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