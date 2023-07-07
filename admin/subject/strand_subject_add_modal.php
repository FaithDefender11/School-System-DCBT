<?php 

    // $subject = new Subject($con);
    // $form = $subject->createForm();
    // $selectSubjectTitle = $subject->SelectSubjectTitle();

    // $strand_name = "";
    // if(isset($_POST['program_id'])){
        
    //     $program_id = $_POST['program_id'];
    //     $section = new Section($con, null);
    //     $strand_name = $section->GetAcronymByProgramId($program_id);
    //     // ...

    //     echo $strand_name;
    // }

    // Modal HTML (outside the if block)
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

                    </div>

                    <div class="modal-footer">
                        <input type="hidden" id="program_id" value="<?php echo $program_id?>">
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
    $(document).on('submit', '#saveStudent', function (e) {

        e.preventDefault();

        var subject_code = $("#subject_code").val();
        var semester = $("#semester").val();
        var course_level = $("#course_level").val();
        var subject_template_id = $("#subject_template_id").val();
        var program_id = $("#program_id").val();
        

        // console.log(subject_template_id)
        // Result would be the form of #saveStudent
        // console.log(this);

        // console.log(subject_template_id)
        $.ajax({
            url: "../../ajax/subject/strand_subject_add_modal.php",
            type: "POST",
            data: {
                // subject_code: subject_code,
                semester: semester,
                course_level: course_level,
                subject_template_id: subject_template_id,
                program_id
            },
            // dataType: 'json',

            // processData: false,
            // contentType: false,
            success: function (response) {

                var output = response.trim();

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