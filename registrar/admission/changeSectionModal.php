<?php 
    ?>
    <div class="modal fade" id="changeSectionModalBtn" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class='modal-title text-center'>Selection Section</h4>
                  
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="saveChangeSection">
                    <div class="modal-body">

                        <div id="errorMessage" class="alert alert-warning d-none"></div>

                        
                        <div class='form-group mb-2'>

                            <?php 
                            
                                // echo $student_enrollment_program_id;
                                // echo "<br>";
                                
                                // echo $student_enrollment_course_id;
                                // echo "<br>";

                                // echo $enrollment_course_section_level;
                                // echo "<br>";
 
                           
                            ?>
                            <label for="" class="mb-2">Available Section</label>
                            <select class='form-control' id="course_id" name='course_id'>
                                <?php 

                                    $query = $con->prepare("SELECT * FROM course
                                        WHERE program_id=:program_id
                                        AND course_id != :course_id
                                        AND program_section != :program_section
                                        AND course_level = :course_level
                                        AND active = 'yes'
                                        AND is_full = 'no'
                                        AND school_year_term = :school_year_term

                                    "); 
                                    $query->bindParam(":program_id", $student_enrollment_program_id);
                                    $query->bindParam(":course_id", $student_enrollment_course_id);
                                    $query->bindParam(":program_section", $student_program_section);
                                    $query->bindParam(":course_level", $enrollment_course_section_level);
                                    $query->bindParam(":school_year_term", $current_school_year_term);
                                    $query->execute();

                                    $section = new Section($con, $student_enrollment_course_id);

                                    $sectonName = $section->GetSectionName();

                                    echo "<option value='' disabled selected>$sectonName - Current section</option>";

                                    if ($query->rowCount() > 0) {
                                        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                            $selected = ""; // Reset the variable for each option
                                            // if ($row['course_id'] == $course_id) {
                                            //     $selected = "selected";
                                            // }
                                            echo "<option value='".$row['course_id']."' $selected>".$row['program_section']."</option>";
                                        }
                                    }else{
                                        
                                    }
                                ?>

                                <input type="hidden" id="enrollment_id" name="enrollment_id" value="<?php echo $enrollment_id; ?>">
                                <input type="hidden" id="student_id" name="student_id" value="<?php echo $student_id; ?>">
                                <input type="hidden" id="current_school_year_id" name="current_school_year_id" value="<?php echo $current_school_year_id; ?>">
                                <input type="hidden" id="student_enrollment_course_id" name="student_enrollment_course_id" value="<?php echo $student_enrollment_course_id; ?>">
                                <input type="hidden" id="current_school_year_period" name="current_school_year_period" value="<?php echo $current_school_year_period; ?>">
                            
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
    $(document).on('submit', '#saveChangeSection', function (e) {

        e.preventDefault();

        var course_id = parseInt($("#course_id").val());
        var current_school_year_id = parseInt($("#current_school_year_id").val());
        var enrollment_id = parseInt($("#enrollment_id").val());
        var student_id = parseInt($("#student_id").val());
        var student_enrollment_course_id = parseInt($("#student_enrollment_course_id").val());

        var current_school_year_period = $("#current_school_year_period").val();
 
        $.ajax({
            // url: "../../ajax/admission/changeSection.php",
            url: "../../ajax/admission/changeSectioWaitinglist.php",
            type: "POST",
            data: {
                course_id,
                current_school_year_id,
                enrollment_id,
                student_id,
                student_enrollment_course_id,
                current_school_year_period
            },
            // dataType: 'json',

            // processData: false,
            // contentType: false,
            success: function (response) {

                var output = response.trim();

                console.log(output);

                // if(output == "update_success"){
                if(output == "update_success_form_enrolled"){

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Section successfully changed.',
                        }).then((result) => {
                      
                        if (result.isConfirmed) {
                            // window.location.href = 'another-page.php';
                            $('#changeSectionModalBtn').modal('hide');
                            $('#saveChangeSection')[0].reset();
                            location.reload();
                        }
                    });
                    // $('#subjectLoadTablex').load(location.href + " #subjectLoadTablex");
                }

                
                // if(output == "already_registered"){

                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Oh no!',
                //         text: `Selected Template has already registered!`,
                //     });
                // }
            }
        });

    });
</script>
