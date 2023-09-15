<?php 

    $program = new Program($con, $student_enrollment_course_id === 0 
            ? $student_course_id : $student_enrollment_course_id);

    $proper_program_id = $student_enrollment_course_id === 0 ?
        $student_current_program_id : $student_program_id;
    
    ?>
    <header>
        <style>
            .modal-content{
                width: 650px;
            }
        </style>
    </header>
    <div class="modal fade" id="changeStudentProgram" tabindex="1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class='modal-title text-center'>Form Adjustment</h4>
                  
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="saveChangeProgramForm">
                    <div class="modal-body">

                        <div id="errorMessage" class="alert alert-warning d-none"></div>

                        <div class='form-group mb-2'>
                            <label for="" class="mb-2">Previous Enrolled Section</label>
                            
                            <input  value="<?php echo $student_current_program_section; ?>" class='form-control'
                                name="current_program_section" id="current_program_section" style="pointer-events: none;" type="text">
                           
                        </div>

                        <div class='form-group mb-2'>

                            <!-- Should be placed on other program starting level point -->
                            <!-- Because it needs to finish all of its major subjects of the program. -->


                            <!-- If Enrollment Course Id = 0, filter = current enrolled course id -->
                            <!-- If Enrollment Course Id != 0, filter = current enrollment course id -->

                            <label for="chosen_program_id" class="mb-2">Choocse Program</label>

                            <select class="form-control" 
                                id="chosen_program_id" name="chosen_program_id">

                                <?php

                                    $query = null;


                                    if($proper_program_id != 0){

                                        $query = $con->prepare("SELECT * FROM program as t1
                                            -- WHERE t1.department_id=:student_current_department_id
                                            -- AND t1.program_id !=:program_id
                                        ");

                                        // $query->bindParam(":student_current_department_id", $student_current_department_id);
                                        // $query->bindParam(":program_id", $proper_program_id);
                                    }
                                    if($proper_program_id == 0){
                                        $query = $con->prepare("SELECT * FROM program");
                                    }

                                    $query->execute();

                                    echo "
                                        <option value='' selected disabled>Choose Program</option>
                                    ";

                                    if ($query->rowCount() > 0) {
                                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ""; // Reset the variable for each option
                                            // if ($row['course_id'] == $course_id) {
                                            //     $selected = "selected";
                                            // }
                                            echo "<option value='".$row['program_id']."' $selected>".$row['program_name']."</option>";
                                        }
                                    }else{
                                        echo "no choose program generated.";
                                    }
                                ?>
                            </select>


                        </div>
                        
                        <div class='form-group mb-2'>

                            <!-- Should be placed on other program starting level point -->
                            <!-- Because it needs to finish all of its major subjects of the program. -->

                            <!-- If Enrollment Course Id = 0, filter = current enrolled course id -->
                            <!-- If Enrollment Course Id != 0, filter = current enrollment course id -->

                            <label for="chosen_program_course_id" class="mb-2">Available Section</label>

                            <!-- <select class='form-control' id="chosen_program_course_id" name='chosen_program_course_id'>
                                <?php

                                    $query = $con->prepare("SELECT * FROM course as t1
                                        -- WHERE program_id=:program_id

                                        INNER JOIN program AS t2 ON t2.program_id = t1.program_id
                                        AND t2.department_id=:student_current_department_id

                                        WHERE t1.program_id !=:program_id

                                        AND t1.course_id != :course_id
                                        -- AND course_level = :course_level
                                        AND t1.active = 'yes'
                                        AND t1.is_full = 'no'
                                        AND t1.is_remove = 0

                                    ");

                                    $query->bindParam(":student_current_department_id", $student_current_department_id);
                                    $query->bindParam(":program_id", $student_current_program_id);
                                    // $query->bindParam(":course_level", $student_enrollment_course_level);
                                    $query->bindParam(":course_id", $student_current_course_id);
                                    $query->execute();

                                    // $section = new Section($con, $student_enrollment_course_id);
                                    // $sectonName = $section->GetSectionName();
                                    // echo "<option value='' disabled selected>$sectonName - Current section</option>";

                                    if ($query->rowCount() > 0) {

                                        
                                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ""; // Reset the variable for each option
                                            // if ($row['course_id'] == $course_id) {
                                            //     $selected = "selected";
                                            // }
                                            echo "<option value='".$row['course_id']."' $selected>".$row['program_section']."</option>";
                                        }
                                    }else{
                                        echo "not";
                                    }
                                ?>
                            </select> -->

                            <select class='form-control' id="chosen_program_course_id" 
                                name='chosen_program_course_id'>
                            </select>

                        </div>

                    </div>

                    <div class="modal-footer">

                        <input type="hidden" id="student_enrollment_id" name="student_enrollment_id" value="<?php echo $student_enrollment_id; ?>">
                        <input type="hidden" id="current_school_year_id" name="current_school_year_id" value="<?php echo $current_school_year_id; ?>">
                        <input type="hidden" id="student_current_course_id" name="student_current_course_id" value="<?php echo $student_current_course_id; ?>">
                        <input type="hidden" id="student_id" name="student_id" value="<?php echo $student_id; ?>">
                        <input type="hidden" id="current_school_year_term" name="current_school_year_term" value="<?php echo $current_school_year_term; ?>">
                            
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <!-- It will reset into enrollment course id = 0 -->
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
?>

<script>

    $("#chosen_program_id").on("change", function() {

        // Get the selected option's value (in this case, it will be the ID)
        var program_id = parseInt($(this).val());

        var current_school_year_term = $("#current_school_year_term").val();

        // console.log(program_id)

        $.ajax({
            url: "../../ajax/admission/populateProgramSection.php",
            type: "POST",
            data: {
                program_id,
                current_school_year_term,
            },
            dataType: 'json',

            // processData: false,
            // contentType: false,
            success: function (response) {
                // var output = response.trim();
                // console.log(output)

                var options = '<option selected value="">Available Sections</option>';

                $.each(response, function (index, value) {
                    options +=
                    '<option value="' +
                    value.course_id +
                    '">' +
                    value.program_section +
                    '</option>';
                });

                $('#chosen_program_course_id').html(options);
            },
            error: function(xhr, status, error) {
                // Handle error response here
                console.error('Error:', error);
                console.log('Status:', status);
                console.log('Response Text:', xhr.responseText);
                console.log('Response Code:', xhr.status);
            }
        });
    });

    $(document).on('submit', '#saveChangeProgramForm', function (e) {

        e.preventDefault();

        var current_school_year_id = parseInt($("#current_school_year_id").val());
        var student_enrollment_id = parseInt($("#student_enrollment_id").val());
        var student_id = parseInt($("#student_id").val());
        var chosen_course_id = parseInt($("#chosen_program_course_id").val());

 
        // console.log(enrollment_id)

        $.ajax({
            url: "../../ajax/admission/changeProgram.php",
            type: "POST",
            data: {
                current_school_year_id,
                student_enrollment_id,
                student_id,
                chosen_course_id
            },
            // dataType: 'json',

            // processData: false,
            // contentType: false,
            success: function (response) {

                var output = response.trim();

                console.log(output)
 
                if(output == "success_change_program"){

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Section successfully changed.',
                        }).then((result) => {
                      
                        if (result.isConfirmed) {
                            // window.location.href = 'another-page.php';

                            $('#changeStudentProgram').modal('hide');
                            $('#saveChangeProgramForm')[0].reset();
                            location.reload();

                            window.location.href = `process_enrollment.php?subject_review=show&st_id=${student_id}&selected_course_id=${chosen_course_id}`;
                        }
                    });
                }

            }
        });

    });
</script>
