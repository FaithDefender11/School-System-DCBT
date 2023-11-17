
<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Enrollment.php');

    // echo Helper::RemoveSidebar();


    $teacher = new Teacher($con);
    $enrollment = new Enrollment($con);

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $allActiveTeacher = $teacher->GetAllActiveTeacher();

    // var_dump($allActiveTeacher);

    $back_url = "index.php";


    # Can select teachers (All or selected)
    # or All Students.

    $getAllStudents = $enrollment->GetEnrolledStudentIdsWithinSemester($current_school_year_id);



    
    if(isset($_SERVER['REQUEST_METHOD']) == "POST"
        && (isset($_POST['add_announcement_' . $adminUserId]))
        && isset($_POST['title'])
        && isset($_POST['content'])
        ){


            $title = $_POST['title'];
            $content = $_POST['content'];

            $student_selected = isset($_POST['student_selected']) ? $_POST['student_selected'] : NULL ;

            // var_dump($student_selected);


            $announcement = new Announcement($con);

            if (isset($_POST['student_selected'])) {

                $student_selected = "";
                // echo "hey";

                if(count($getAllStudents) > 0){

                    
                    
                }
                
            }
            if (isset($_POST['selectedTeachers']) && is_array($_POST['selectedTeachers'])) {

                $teachers_id = "";
                // Loop through the selected teacher IDs
                foreach ($_POST['selectedTeachers'] as $selectedTeacherId) {

                    $teachers_id = implode(',', $_POST['selectedTeachers']);

                }

                $wasSuccess = $announcement->InsertAdminAnnouncement(
                    $adminUserId,  $teachers_id,
                    $current_school_year_id, $title, $content,
                    $student_selected
                );

                if($wasSuccess){
                    Alert::successAutoRedirect("Announcement has been posted.", $back_url);
                    exit();
                }

            } else {
                // No teachers selected
                // Handle this case if necessary
            }
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
                    <h4 class='text-center mb-3'>Add announcement</h4>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">

                        <div class='form-group mb-2'>
                            <label for="title" class='mb-2'>* Subject</label>

                            <input class='form-control' type='text' 
                                placeholder='Add Assignment' id="title" name='title'>
                        </div>


                        <div class='form-group mb-2'>
                            <label for="content" class='mb-2'>* Content</label>
                            <textarea class="form-control" name="content" id="content"></textarea>
                        </div>

                        <div class='form-group mb-2'>
                            <label for="title" class='mb-2'>* To Whom</label>
                            <?php

                                echo "<br>";
                                echo "
                                    <input type='checkbox' id='select-all' class='select-all-checkbox'>
                                    <span>Teachers: </span> &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp;  &nbsp; 
                                    <input name='student_selected' type='checkbox'>&nbsp; <span>Student: </span>
                                ";
                                echo "<br>";

                                foreach ($allActiveTeacher as $teacher) {

                                    $teacherName = ucwords(htmlspecialchars($teacher['firstname'])) . " " . ucwords(htmlspecialchars($teacher['lastname']));
                                    $teacher_id = htmlspecialchars($teacher['teacher_id']);
                                
                                    echo '<input type="checkbox" name="selectedTeachers[]" value="' . $teacher_id . '"> ' . $teacherName . '<br>';
                                
                                }

                               

                            ?>
                        </div>

                    
                        
                        <div class="modal-footer">
                            <button type='submit' class='btn btn-success' name='add_announcement_<?php echo $adminUserId; ?>'>Add announcement</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
        
    </div>

<script>

    // JavaScript to handle "Select All" checkbox
    document.querySelector('.select-all-checkbox').addEventListener('change', function () {
        const checkboxes = document.querySelectorAll('input[name="selectedTeachers[]"]');
        for (const checkbox of checkboxes) {
            checkbox.checked = this.checked;
        }
    });
</script>
