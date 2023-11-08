<?php 

 
    ?>

    <header>
        <style>
            .modal-content{
                width: 550px;
            }
        </style>
    </header>

    <div class="modal fade" id="addGradeBtn" tabindex="1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class='modal-title text-center'>Add Grade</h4>
                  
                    <span>Max Score: <?php echo $max_score ?></span>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="saveAddGradeBtn">
                    <div class="modal-body">

                        <div id="errorMessage" class="alert alert-warning d-none"></div>

                        <div class='form-group mb-2'>
                            <label for="grade_input" class="mb-2">Grade</label>
                            
                            <input maxlength="3" value="" class='form-control'
                                name="grade_input" id="grade_input" type="text">
                           
                        </div>
                    </div>

                    <div class="modal-footer">
            
                        <input type="hidden" id="subject_assignment_submission_id" name="subject_assignment_submission_id" value="<?php echo $subject_assignment_submission_id; ?>">
                        <input type="hidden" id="max_score" name="max_score" value="<?php echo $max_score; ?>">
                        <input type="hidden" id="subject_code_topic_id" name="subject_code_topic_id" value="<?php echo $subject_code_topic_id; ?>">
                        <input type="hidden" id="current_school_year_id" name="current_school_year_id" value="<?php echo $current_school_year_id; ?>">
                        <input type="hidden" id="subject_code_assignment_id" name="subject_code_assignment_id" value="<?php echo $subject_code_assignment_id; ?>">
                        <input type="hidden" id="subject_code" name="subject_code" value="<?php echo $subject_code; ?>">

                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
?>

<script>

    $(document).on('submit', '#saveAddGradeBtn', function (e) {

        e.preventDefault();


        var grade_input = parseInt($("#grade_input").val());
        var subject_assignment_submission_id = parseInt($("#subject_assignment_submission_id").val());
        var max_score = parseInt($("#max_score").val());
        var subject_code_topic_id = parseInt($("#subject_code_topic_id").val());
        var current_school_year_id = parseInt($("#current_school_year_id").val());
        var subject_code_assignment_id = parseInt($("#subject_code_assignment_id").val());
        var subject_code = $("#subject_code").val();
        
        
        // console.log(grade_input);
 
        $.ajax({
            url: "../../ajax/class/addGrade.php",
            type: "POST",
            data: {
                grade_input,
                subject_assignment_submission_id,
                max_score,
                current_school_year_id,
                subject_code_assignment_id,
                subject_code
            },
            // dataType: 'json',
            // processData: false,
            // contentType: false,
            success: function (response) {

                var output = response.trim();

                console.log(output);

            // Alert::error("Given grade has reached the established max score.", "");
            // exit();

                if(output == "invalid"){
                    // Alert::error("Given grade has reached the established max score.", "");
                    // exit();

                    Swal.fire({
                        icon: 'error',
                        title: 'Oh no!',
                        text: 'Invalid Grade Input',
                        backdrop: false,
                        allowEscapeKey: false,
                        }).then((result) => {
                      
                        if (result.isConfirmed) {
                            // window.location.href = 'another-page.php';

                            // $('#addGradeBtn').modal('hide');
                            // $('#saveAddGradeBtn')[0].reset();
                            // location.reload();
                            // window.location.href = `process_enrollment.php?subject_review=show&st_id=${student_id}&selected_course_id=${chosen_course_id}`;
                        }
                    });
                }
                if(output == "success"){

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Grade remarks success.',
                        backdrop: false,
                        allowEscapeKey: false,

                        }).then((result) => {
                      
                        if (result.isConfirmed) {
                            // window.location.href = 'another-page.php';

                            // $('#addGradeBtn').modal('hide');
                            // $('#saveAddGradeBtn')[0].reset();
                            // location.reload();

                            var url = `task_summary.php?ct_id=${subject_code_topic_id}`;
                            window.location.href = `${url}`;

                        }
                    });
                }

            }
        });

    });
</script>
