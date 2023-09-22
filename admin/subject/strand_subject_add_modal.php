<?php 

    ?>
    <div class="modal fade" id="subjectAddModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class='modal-title text-center mb-3'>Attach Subject on <?php echo $strand_name;?> Subject</h4>
                    <!-- <h5 class="modal-title" id="exampleModalLabel">Add Course</h5> -->
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="saveStudent">
                    <div class="modal-body">

                        <?php echo $selectSubjectTitle;?>

                        <div id="errorMessage" class="alert alert-warning d-none"></div>

                        <?php echo $dynamicCourseLevelDropdown;?>
                        
                        <div class='form-group mb-2'>
                            <label for="" class="mb-2">Semester</label>
                            <select class='form-control' id="semester" name='semester'>
                                <option value='First'>First</option>
                                <option value='Second'>Second</option>
                            </select>
                        </div>

                        <div class='form-group mb-2'>
                            <label for="" class="mb-2">Pre Requisite</label>
                            <!-- <input type="text" id="pre_req_subject_title" 
                                 
                                name="pre_req_subject_title" 
                                class="form-control"> -->

                            <select name="pre_req_subject_title" id="pre_req_subject_title"
                                class="form-control"></select>
                            
                        </div>

                    </div>

                    <div class="modal-footer">
                        <input type="hidden" id="program_id" value="<?php echo $program_id?>">
                        <input type="hidden" id="department_name" value="<?php echo $department_name?>">
                       
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

    $('#subject_template_id').on('change', function() {

        var subject_template_id = parseInt($(this).val());


        $.ajax({
            url: "../../ajax/subject/get_pre_requisite.php",
            type: "POST",
            data: {
                subject_template_id
            },
            dataType: 'json',

            // processData: false,
            // contentType: false,
            success: function (response) {

                // var response = response.trim();
                // console.log(response);
                
                if(response.length > 0){

                    var options = '';
                    
                    $.each(response, function (index, value) {
                        options +=
                        '<option value="' + value.subject_template_id + '">' + value.pre_requisite_title + '</option>';
                    });

                    $('#pre_req_subject_title').html(options);
                }
                
            }
        });
    });

    $(document).on('submit', '#saveStudent', function (e) {

        e.preventDefault();

        var subject_code = $("#subject_code").val();
        var semester = $("#semester").val();
        var course_level = $("#course_level").val();
        var subject_template_id = $("#subject_template_id").val();
        var program_id = $("#program_id").val();
        var pre_req_subject_title = $("#pre_req_subject_title").val();
        var department_name = $("#department_name").val();
        

        // console.log(subject_template_id)
        // Result would be the form of #saveStudent
        // console.log(this);

        // console.log(program_id)

        $.ajax({
            url: "../../ajax/subject/strand_subject_add_modal.php",
            type: "POST",
            data: {
                // subject_code: subject_code,
                semester: semester,
                course_level: course_level,
                subject_template_id: subject_template_id,
                program_id,
                pre_req_subject_title,department_name
            },
            // dataType: 'json',

            // processData: false,
            // contentType: false,
            success: function (response) {

                var output = response.trim();
                console.log(output);
                
                // $('#strand_subject_view_table').load(location.href + " #strand_subject_view_table");
                // $('#subjectAddModal').modal('hide');
                // $('#saveStudent')[0].reset();

                if(output == "success"){

                    $('#strand_subject_view_table').load(location.href + " #strand_subject_view_table");
                    $('#subjectAddModal').modal('hide');
                    $('#saveStudent')[0].reset();

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Subject added successfully!',
                    });
                }

                if(output == "already_registered"){

                    Swal.fire({
                        icon: 'error',
                        title: 'Oh no!',
                        text: `Selected Template has already registered!`,
                    });
                }
            }
        });

    });
</script>