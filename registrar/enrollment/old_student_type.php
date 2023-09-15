    
<?php 

    $search_word = "";

?>

    <div class="enrollment_old">
        <!-- <header>
            <div class="title">
            <h3>Ongoing Enrollment Process</h3>
            </div>
        </header> -->
        <hr>


        <div class="floating">
            <header class="mb-2">
                <div class="title">
                    <h4 style="font-weight: bold;">Ongoing Enrollment Process</h4>
                </div>

            </header>

            <div class="filters">
                <table>
                    <tr>
                        <th rowspan="2" style="border-right: 2px solid black">
                            Search by
                        </th>
                        <!-- <th><button type="button">Name</button></th> -->
                        <!-- <th><button type="button">School ID</button></th> -->
                    </tr>
                </table>
            </div>
 
            <div class="">
                <?php 
                    $searchText = $search_word !== NULL ? $search_word : "";
                ?>
                
                <input type="text" name="student_unique_id_val" 
                    id="student_unique_id_val" 
                    class="form-control form-control-lg rounded-0 border-info"
                    placeholder="Search Here..." autocomplete="off">

                <!-- <input type="text" id="student_unique_id_val"
                            name="student_unique_id_val" class="form-control"
                            placeholder="Search Ongoing Student.."> -->

            </div>

            <div class="col-md-8 show_search">
                <div class="list-group" id="show_os_student">
                    <!-- <a href="#" class="list-group-item list-group-item-action border-1">First</a>
                    <a href="#" class="list-group-item list-group-item-action border-1">First</a> -->
                </div>
            </div>

        </div>

        <div class="row">
            <span>
                <!-- <div class="form-element courseStrand">
                    <label>Choose Student ID</label>
                    <div>
                        <input type="text" id="student_id"
                            name="student_id" class="form-control"
                            placeholder="Search Ongoing Student..">
                    </div>
                </div> -->

                <div class="form-element courseStrand">
                    <!-- <label>Choose Section</label> -->
                    <label>Previous Section</label>
                    <div>
                        <select style="width: 85%;" class='form-control' 
                            name="course_id" id="course_id">
                        </select>
                    </div>
                </div>
            </span>
        </div>
    </div>



<script>
    $(document).ready(function() {

        $('#student_unique_id_val').on('keypress', function(event) {


            if (event.which === 13) {

                event.preventDefault();

                var studentId = $(this).val();

                // console.log("emtered");
                // $('.show_search').html('');

                $.ajax({
                    url: '../../ajax/enrollment/populate_section.php',
                    type: 'POST',
                    data: { studentId },
                    dataType: 'json',
                    
                    success: function (response) {
        

                        console.log(response)

                        if(response.length == 0){

                            $("#student_status_attach").text('');
                            $("#lrn").val('');
                            $("#lastName").val('');
                            $("#firstName").val('');
                            $("#middleName").val('');
                            $("#suffixName").val('');
                            $("#civil_status").val('');
                            $("#nationality").val('');
                            $("#sex").val('');
                            $("#birthday").val('');
                            $("#religion").val('');
                            $("#birthplace").val('');
                            $("#address").val('');
                            $("#contact_number").val('');
                            $("#email").val('');
                            $("#course_id").append('<option value=""></option>');
                            
                            // console.log('we');
                            $("#student_status_attach").text('');
                            $("#non_fetch").text('Student ID Doesnt exists.');

                            setTimeout(function() {
                                $("#non_fetch").text(''); // Remove the message
                            }, 2000);
                            return;

                        }else{
                            if (response.sections.length === 0) {
                                // console.log('empty')
                                options += '<option value="">No Section</option>';
                                $('#course_id').html(options);
                                return;
                            } else if (response.sections.length !== 0){

                                // $.each(response.sections, function (index, value) {
                                //     var course_id = value.course_id;
                                //     var program_section = value.program_section;
                                // });

                                // var options = '<option selected value="">Available Sections</option>';

                                // $.each(response.sections, function (index, value) {
                                //     options +=
                                //     '<option value="' +
                                //     value.course_id +
                                //     '">' +
                                //     value.program_section +
                                //     '</option>';
                                // });

                                // $('#course_id').html(options);
                            }

                            if(response.students){

                                var student_object = response.students;

                                $("#student_status_attach").text(student_object['student_status']);
                                $("#lrn").val(student_object['lrn']);
                                $("#lastName").val(student_object['lastname']);
                                $("#firstName").val(student_object['firstname']);
                                $("#middleName").val(student_object['middle_name']);
                                $("#suffixName").val(student_object['suffix']);
                                $("#civil_status").val(student_object['civil_status']);
                                $("#nationality").val(student_object['nationality']);
                                $("#sex").val(student_object['sex']);
                                $("#birthday").val(student_object['birthday']);
                                $("#religion").val(student_object['religion']);
                                $("#birthplace").val(student_object['birthplace']);
                                $("#address").val(student_object['address']);
                                $("#contact_number").val(student_object['contact_number']);
                                $("#email").val(student_object['email']);

                                $("#course_id").append('<option value="' + student_object['student_current_course_id'] + '">' + student_object['student_current_program_section'] + '</option>');
                            }
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });

        $('#student_id').on('keypress', function(event) {

            if (event.which === 13) {

                event.preventDefault();

                var studentId = $(this).val();

                console.log(studentId)

                $.ajax({
                    url: '../../ajax/enrollment/populate_section.php',
                    type: 'POST',
                    data: { studentId },
                    dataType: 'json',
                    
                    success: function (response) {
        
                        var options = '';
                        console.log(response)

                        if(response.length == 0){
                            
                            // console.log('we');
                            $("#student_status_attach").text('');
                            $("#non_fetch").text('Student ID Doesnt exists.');

                            setTimeout(function() {
                                $("#non_fetch").text(''); // Remove the message
                            }, 2000);
                            return;

                        }else{
                            if (response.sections.length === 0) {
                                // console.log('empty')
                                options += '<option value="">No Section</option>';
                                $('#course_id').html(options);
                                return;
                            } else if (response.sections.length !== 0){

                                // $.each(response.sections, function (index, value) {
                                //     var course_id = value.course_id;
                                //     var program_section = value.program_section;
                                // });

                                // var options = '<option selected value="">Available Sections</option>';

                                // $.each(response.sections, function (index, value) {
                                //     options +=
                                //     '<option value="' +
                                //     value.course_id +
                                //     '">' +
                                //     value.program_section +
                                //     '</option>';
                                // });

                                // $('#course_id').html(options);
                            }

                            if(response.students){

                                var student_object = response.students;

                                $("#student_status_attach").text(student_object['student_status']);
                                $("#lrn").val(student_object['lrn']);
                                $("#lastName").val(student_object['lastname']);
                                $("#firstName").val(student_object['firstname']);
                                $("#middleName").val(student_object['middle_name']);
                                $("#suffixName").val(student_object['suffix']);
                                $("#civil_status").val(student_object['civil_status']);
                                $("#nationality").val(student_object['nationality']);
                                $("#sex").val(student_object['sex']);
                                $("#birthday").val(student_object['birthday']);
                                $("#religion").val(student_object['religion']);
                                $("#birthplace").val(student_object['birthplace']);
                                $("#address").val(student_object['address']);
                                $("#contact_number").val(student_object['contact_number']);
                                $("#email").val(student_object['email']);

                                  $("#course_id").append('<option value="' + student_object['student_current_course_id'] + '">' + student_object['student_current_program_section'] + '</option>');

                            }
                        }



                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Get a reference to the input element
        var inputElement = document.getElementById("student_unique_id_val");
        
        // Focus on the input element
        inputElement.focus();
    });
</script>