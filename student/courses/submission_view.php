<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');

    ?>
        <head>
            <!-- SUMMER NOTE LINK -->
            <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">

            <!-- SUMMER NOTE SCRIPT -->
            <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
        </head>
    <?php

    if(isset($_GET['id'])
        && isset($_GET['s_id'])){

        $subject_code_assignment_id = $_GET['id'];

        $subject_assignment_submission_id = $_GET['s_id'];

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

        $subjectCodeAssignment = new SubjectCodeAssignment($con, $subject_code_assignment_id);
        
        $subject_period_code_topic_id = $subjectCodeAssignment->GetSubjectPeriodCodeTopicId();
        $assignment_type = $subjectCodeAssignment->GetType();

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $topic_name = $subjectPeriodCodeTopic->GetTopic();
        $instructions = $subjectPeriodCodeTopic->GetDescription();
        $subject_code = $subjectPeriodCodeTopic->GetSubjectCode();

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];


        $assignment_name = $subjectCodeAssignment->GetAssignmentName();

        // $subject_assignment_submission_id = NULL;

        // $subject_assignment_submission_id = $subjectAssignmentSubmission->GetSubjectAssignmentSubmissionIdNonGraded(
        //     $subject_code_assignment_id, $current_school_year_id);

        // $graded_subject_assignment_submission_id = $subjectAssignmentSubmission->GetStudentGradedAssignmentSubmissionId(
        //     $subject_code_assignment_id, $current_school_year_id);

        $getAnsweredAssignmentList = $subjectAssignmentSubmission->GetAssignmentList(
            $subject_assignment_submission_id);
        
        $doesLatestAssigntmentGraded = $subjectAssignmentSubmission->DoesStudentGradedSubmissionAssignment(
            $subject_assignment_submission_id, $current_school_year_id, $studentLoggedInId);

        # subject_assignment_submission_id reflected accordingly.

        # If graded = graded subject_assignment_submission_id
        # If not graded = latest subject_assignment_submission_id

        $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con,
            $subject_assignment_submission_id);

        $get_graded_remarks = $subjectAssignmentSubmission->GetSubjectGrade();


        
        $back_url = "index.php?c=$subject_code";

        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="floating" id="shs-sy">
                        <button style="width: 135px;" onclick="window.location.href = 'task_submission.php?sc_id=<?php echo $subject_code_assignment_id; ?>' "
                                    class="btn btn-sm btn-outline-primary" >Instructions</button>

                        <button style="width: 135px;"
                            class="btn btn-sm btn-primary" >View Submission</button>
                        
                        <header>
                            <div class="title">
                                <p class="text-info text-right">Assignment type: <?php echo $assignment_type; ?></p>
                                <h3>Submissions</h3>

                                <?php
                                    if($doesLatestAssigntmentGraded && $get_graded_remarks !== NULL){
                                        ?>
                                            <p>Grade Remarks: <?php echo $get_graded_remarks; ?></p>
                                        <?php
                                    }
                                ?>

                                
                                
                                    <?php 
                                        if(count($getAnsweredAssignmentList) > 1){
                                            ?>
                                                <button onclick="window.location.href = 'student_submission_list.php?id=<?php echo $subject_code_assignment_id; ?>' "
                                                    style="width: 120px;"
                                                    class="btn btn-sm btn-dark" >History</button>
                                            <?php
                                        }
                                    ?>
                                
                                
                            </div>

                        </header>

                        <main>
                            <?php 
                            
                                if(count($getAnsweredAssignmentList) > 0){

                                    if($assignment_type == "text"){


                                        foreach ($getAnsweredAssignmentList as $key => $value) {

                                            ?>
                                            <div class="card" style="width: 18rem;">
                                                
                                                <div style="min-width: 750px;" class="card-body">
                                                        
                                                        <div style="max-width: 650px;" class='form-group mb-2'>
                                                        
                                                            <label style="font-size: 20px;" for="output_text" class='text-center mb-2'>Answer</label>
                                                            <textarea class="form-control summernote" type='text' 
                                                                id="output_text" name='output_text'><?php echo $value['output_text'] ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php
                                        }
                                    }

                                    if($assignment_type == "upload"){

                                        $image_extensions = ['jpg', 'jpeg', 'png'];

                                        foreach ($getAnsweredAssignmentList as $key => $value) {

                                            $uploadFile = $value['output_file'];

                                            $extension = pathinfo($uploadFile, PATHINFO_EXTENSION);

                                            if (strtolower($extension) === 'pdf') {

                                                $parts = explode('_', $uploadFile);

                                                $original_file_name = end($parts);

                                                ?>
                                                    <a title="View File" href='<?php echo "../../".  $value['output_file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>
                                                <?php
                                            }

                                            if (strtolower($extension) === 'docx' ||
                                                strtolower($extension) === 'doc') {

                                                $parts = explode('_', $uploadFile);
                                                $original_file_name = end($parts);

                                                ?>
                                                    <a title="View File" href='<?php echo "../../".  $value['output_file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>
                                                <?php
                                            }
                                            if (in_array(strtolower($extension), $image_extensions)) {
                                                ?>
                                                    <div class="card" style="width: 18rem;">
                                                        <div style="min-width: 750px;" class="card-body">

                                                            <a title="View File" href='<?php echo "../../". $value['output_file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                                <img style="margin-left:8px; width: 520px;" 
                                                                src='<?php echo "../../".$value['output_file']; ?>' alt='Given Photo' class='preview-image'>
                                                        
                                                            </a>
                                                        </div>
                                                    </div>
                                                <?php
                                            }
                                        }

                                        foreach ($getAnsweredAssignmentList as $key => $value) {

                                            ?>
                                                <!-- <div class="card" style="width: 18rem;">
                                                    <div style="min-width: 750px;" class="card-body">

                                                        <a title="Download File" download href='<?php echo "../../". $value['output_file'] ?>' target='__blank' rel='noopener noreferrer'>
                                                            <?php echo $value['output_file']; ?>
                                                        </a>

                                                    </div>
                                                </div> -->
                                            <?php
                                        }
                                    }
                                    

                                }
                            ?>
                            

                        </main>
                    </div>
                </main>
            </div>

        <?php
    }
?>

<script>
    $(document).ready(function () {
    // Initialize Summernote with readOnly: true

    $('.summernote').summernote({
        height: 250,
       
    });

    $('.summernote').next().find(".note-editable").attr("contenteditable", false);

});
</script>


