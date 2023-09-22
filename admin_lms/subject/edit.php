<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');

    ?>
        <style>

            tr td {
                text-decoration: none;
                color: inherit; /* To maintain the link color */
                white-space: nowrap; /* Prevent text from wrapping */
            }
        </style>
    <?php

    if(isset($_GET['id'])
        && isset($_GET['sp_id'])){


        $subject_period_code_topic_template_id = $_GET['id'];
        $subject_program_id = $_GET['sp_id'];

        $subjectProgram = new SubjectProgram($con, $subject_program_id);
        $program_code = $subjectProgram->GetSubjectProgramRawCode();

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con, $subject_period_code_topic_template_id);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $back_url= "code_topics.php?id=$subject_program_id";

        $topic = $subjectPeriodCodeTopicTemplate->GetTopic();
        $description = $subjectPeriodCodeTopicTemplate->GetDescription();
        $subject_period = $subjectPeriodCodeTopicTemplate->GetSubjectPeriodName();
        
        // $subject_period = $subjectPeriodCodeTopicTemplate->GetSubjectPeriodName()();
        // $subject_period ="";

        // echo $subject_period;

        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['edit_program_code_btn_' . $subject_period_code_topic_template_id])
            && isset($_POST['topic'])
            && isset($_POST['description'])
            && isset($_POST['subject_period_name'])
            
            ){

                $topic = $_POST['topic'];
                $description = $_POST['description'];
                $subject_period_name = $_POST['subject_period_name'];

                // echo "Hey";

                // $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

                $wasSuccess = $subjectPeriodCodeTopicTemplate->UpdateTopicTemplate(
                    $subject_period_code_topic_template_id,
                    $topic, $description,
                    $subject_period_name,
                    $program_code);

                if($wasSuccess){

                    Alert::success("Successfully Updated", $back_url);
                    exit();
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
                            <h4 class="text-center text-muted">Edit topics for: <?php echo $program_code; ?></h4>
                        </div>

                        <div class="card-body">

                            <form method='POST' enctype="multipart/form-data">

                                <div class='form-group mb-2'>
                                    <label for="topic" class='mb-2'>Topic *</label>
                                    <input required value="<?php echo $topic; ?>" id="topic" class='form-control' type='text' placeholder='' name='topic'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="description" class='mb-2'>Description *</label>
                                    <input value="<?php echo $description; ?>" required id="description" class='form-control' type='text' placeholder='' name='description'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="subject_period_name" class='mb-2'>Period Name *</label>
                                    
                                    <select required id="subject_period_name" class='form-control' name="subject_period_name">
                                        <option value="Prelim" <?php echo $subject_period === "Prelim" ? "selected" : "" ?>>Prelim</option>
                                        <option value="Midterm" <?php echo $subject_period === "Midterm" ? "selected" : "" ?>>Midterm</option>
                                        <option value="Pre-final" <?php echo $subject_period === "Pre-final" ? "selected" : "" ?>>Pre-final</option>
                                        <option value="Final" <?php echo $subject_period === "Final" ? "selected" : "" ?>>Final</option>
                                    </select>
                                </div>

                                

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='edit_program_code_btn_<?php echo $subject_period_code_topic_template_id; ?>'>Save Section</button>
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
    function removeTemplate(subject_period_code_topic_template_id,
        school_year_id){

        
        Swal.fire({
                icon: 'question',
                title: `Are you sure you want to remove?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/class/removeTemplateTopic.php",
                        type: 'POST',
                        data: {
                            subject_period_code_topic_template_id,
                            school_year_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Deleted`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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

                                $('#topic_template_table').load(
                                    location.href + ' #topic_template_table'
                                );

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