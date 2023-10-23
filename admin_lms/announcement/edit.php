
<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Announcement.php');

    // echo Helper::RemoveSidebar();


    $teacher = new Teacher($con);

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $allActiveTeacher = $teacher->GetAllActiveTeacher();

    // var_dump($allActiveTeacher);

    $back_url = "index.php";

    if(isset($_GET['id'])){

        $announcement_id = $_GET['id'];

        $announcement = new Announcement($con, $announcement_id);

        $title = $announcement->GetTitle();
        $content = $announcement->GetContent();
        $for_students = $announcement->GetForStudents();
        $teachers_id = $announcement->GetTeachersIds();

        // var_dump($teachers_id);

        if(isset($_SERVER['REQUEST_METHOD']) == "POST"
            && (isset($_POST['add_announcement_' . $adminUserId]))
            && isset($_POST['title'])
            && isset($_POST['content'])){


            $title = $_POST['title'];
            $content = $_POST['content'];

            $student_selected = isset($_POST['student_selected']) ? $_POST['student_selected'] : NULL ;

            
            $teachers_id = NULL;


            if (isset($_POST['selectedTeachers']) && is_array($_POST['selectedTeachers'])) {

                // Loop through the selected teacher IDs
                foreach ($_POST['selectedTeachers'] as $selectedTeacherId) {

                    $teachers_id = implode(',', $_POST['selectedTeachers']);

                }

              

          

                

            } 

            

            else {
                // No teachers selected
                // Handle this case if necessary
            }

            $wasSuccess = $announcement->EditAdminAnnouncement(
                $announcement_id,
                $adminUserId,  $teachers_id,
                $current_school_year_id, $title, $content,
                $student_selected
            );
            if($wasSuccess){
                Alert::successAutoRedirect("Announcement has been modified.", $back_url);
                exit();
            }

            // var_dump($student_selected);
            // return;

        }


        ?>
            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>


                <div class='col-md-10 offset-md-1'>
                    <div class='card'>

                        <div class='card-header'>
                            <h3 class='text-start mb-3'>Edit announcement: <span style="font-weight: bold; font-size: 20px;"><?= $title;?></span> </h3>
                        </div>

                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">

                                <div class='form-group mb-2'>
                                    <label for="title" class='mb-2'>* Subject</label>

                                    <input class='form-control' type='text' 
                                        placeholder='Add Assignment' id="title" value="<?= $title; ?>" name='title'>
                                </div>


                                <div class='form-group mb-2'>
                                    <label for="content" class='mb-2'>* Content</label>
                                    <textarea class="form-control"  name="content" id="content"><?= $content; ?></textarea>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="title" class='mb-2'>* To Whom</label>
                                    <?php


                                        $selectedTeacherIdsArray = explode(',', $teachers_id);

                                        $for_students_checked = $for_students == 1 ? "checked" : "";

                                        echo "<br>";
                                        echo "
                                            <input type='checkbox' id='select-all' class='select-all-checkbox'>
                                            <span>Teachers: </span> &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp;  &nbsp; 
                                            <input $for_students_checked name='student_selected' type='checkbox'>&nbsp; <span>Student: </span>
                                        ";
                                        echo "<br>";

                                        foreach ($allActiveTeacher as $teacher) {

                                            $teacherName = ucwords(htmlspecialchars($teacher['firstname'])) . " " . ucwords(htmlspecialchars($teacher['lastname']));
                                            $teacher_id = htmlspecialchars($teacher['teacher_id']);
                                        
                                            $checked = in_array($teacher_id, $selectedTeacherIdsArray) ? 'checked' : '';

                                            echo '<input type="checkbox" name="selectedTeachers[]" value="' . $teacher_id . '" ' . $checked . '> ' . $teacherName . '<br>';

                                        }
                                    ?>
                                </div>

                            
                                
                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-primary' name='add_announcement_<?php echo $adminUserId; ?>'>Save announcement</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                
            </div>
        <?php

    }

    

?>



<script>

    // JavaScript to handle "Select All" checkbox
    document.querySelector('.select-all-checkbox').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="selectedTeachers[]"]');
        for (const checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>
