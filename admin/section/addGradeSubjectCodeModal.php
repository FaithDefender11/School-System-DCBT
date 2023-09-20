<?php 
    ?>
    <div class="modal fade" id="addGradeSubjectCodeBtn" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class='modal-title text-center'>Add Grades to: <span id="modalStudentName"></span></h4>
                     
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="saveSubjectCodeForm">
                    <div class="modal-body">

                        <div id="errorMessage" class="alert alert-warning d-none"></div>

                        <div class="form-group">
                            <div class="col-md-12 row">
                                <label class="col-md-4 control-label" for="first_quarter_input">First Grading:</label>

                                <div class="col-md-6">
                                    <input class="form-control input-sm" id="first_quarter_input" name="first_quarter_input" placeholder="First Grading" type="text" value="0" autocomplete="off" required="">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-12 row">
                                <label class="col-md-4 control-label" for="second_quarter_input">Second Grading:</label>

                                <div class="col-md-6">
                                    <input class="form-control input-sm" id="second_quarter_input" name="second_quarter_input" placeholder="First Grading" type="text" value="0" autocomplete="off" required="">
                                </div>
                            </div>
                        </div>



                        
                        <div class="form-group">
                            <div class="col-md-12 row">
                                <label class="col-md-4 control-label" for="third_quarter_input">Third Grading:</label>

                                <div class="col-md-6">
                                    <input class="form-control input-sm" id="third_quarter_input" name="third_quarter_input" placeholder="First Grading" type="text" value="0" autocomplete="off" required="">
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-md-12 row">
                                <label class="col-md-4 control-label" for="fourth   _quarter_input">Fourth Grading:</label>

                                <div class="col-md-6">
                                    <input class="form-control input-sm" id="fourth _quarter_input" name="fourth _quarter_input" placeholder="First Grading" type="text" value="0" autocomplete="off" required="">
                                </div>
                            </div>
                        </div>


                    </div>

                    <div class="modal-footer">
                        <input type="hidden" id="student_subject_id_modal">
                        <input type="hidden" id="student_id_modal">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
?>

<script>
    $(document).on('submit', '#saveSubjectCodeForm', function (e) {

        e.preventDefault();

        var student_subject_id_modal = $("#student_subject_id_modal").val();
        var student_id_modal = $("#student_id_modal").val();



        var first_quarter_input = $("#first_quarter_input").val();
        var second_quarter_input = $("#second_quarter_input").val();
        var third_quarter_input = $("#third_quarter_input").val();
        var fourth_quarter_input = $("#fourth_quarter_input").val();
    
        // console.log(student_subject_id_modal)
        // console.log(student_id_modal)
         
 
        $.ajax({
            url: "../../ajax/section/addingGradeOnSubjectCode.php",
            type: "POST",
            data: {
                student_id_modal,
                student_subject_id_modal,
                first_quarter_input,
                second_quarter_input,
                third_quarter_input,
                fourth_quarter_input
            },
            // dataType: 'json',

            // processData: false,
            // contentType: false,
            success: function (response) {

                var output = response.trim();

                console.log(output);

                // if(output == "update_success"){

                if(output == "add_grade_success"){

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Grade successfully inserted.',
                        }).then((result) => {
                      
                        if (result.isConfirmed) {
                            // window.location.href = 'another-page.php';
                            $('#addGradeSubjectCodeBtn').modal('hide');
                            $('#saveSubjectCodeForm')[0].reset();
                            location.reload();
                        }
                    });
                    // $('#subjectLoadTablex').load(location.href + " #subjectLoadTablex");
                }
             
            }
        });

    });
</script>
