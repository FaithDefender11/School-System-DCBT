<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');

    if(isset($_GET['id'])
         
        ){

        $subject_code_handout_id = $_GET['id'];

        $subjectCodeHandout = new SubjectCodeHandout($con, $subject_code_handout_id);
        
        $subject_period_code_topic_id = $subjectCodeHandout->GetSubject_period_code_topic_id();
        $handout_file = $subjectCodeHandout->GetFile();
        $handout_name = $subjectCodeHandout->GetHandoutName();
        $handout_template_id = $subjectCodeHandout->GetSubjectCodeHandoutTemplateId();

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);


        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        // $teacher_id = $_SESSION['teacherLoggedInId'];

        $topic_name = $subjectPeriodCodeTopic->GetTopic();
        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $topic_course_id = $subjectPeriodCodeTopic->GetCourseId();

        // $back_url = "";

        $back_url = "index.php?c_id=$topic_course_id&c=$topic_subject_code";

     

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
                            <h4 class='text-center mb-3'><?php echo ucwords($topic_name); ?> <span style="font-size: 17px;" class="text-muted">Handout</span> </h4>
                        </div> 

                        <div class="card-body">
                            <form method='POST' enctype="multipart/form-data">

                                 <div class='form-group mb-2'>
                                    <label for="handout_name" class='mb-2'>* Handout Name</label>

                                    <input value="<?php echo $handout_name ?>" required class='form-control' type='text' 
                                        placeholder='Add Handout' id="handout_name" name='handout_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="assignment_image" class='mb-2'>File</label>
                                    
                                    <?php if ($handout_template_id == NULL): ?>
                                        <input value="<?php echo $handout_file; ?>" id="assignment_image" class='form-control' type='file' placeholder='' name='assignment_image'>

                                    <?php endif; ?>
                                    <p>
                                        <?php 

                                            // $uploadFile = $photo['image'];

                                            $extension = pathinfo($handout_file, PATHINFO_EXTENSION);
                                            
                                            $parts = explode('_', $handout_file);

                                            $original_file_name = end($parts);
                                            if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                ?>
                                                    
                                                    <a title="View File" href='<?php echo "../../".  $handout_file ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>
                                                <?php
                                            }
                                        ?>
                                    </p>
                                </div>
<!--                          
                                
                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='edit_handout_<?php echo $subject_code_handout_id; ?>'>Save Changes</button>
                                </div> -->

                            </form>
                        </div>

                    </div>
                </div>
                

            </div>
        <?php
    }
?>