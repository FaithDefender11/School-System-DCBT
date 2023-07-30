    
    <div class="enrollment_old">
        <header>
            <div class="title">
            <h3>Ongoing Enrollment Process</h3>
            </div>
        </header>
        <hr>

        <div class="row">
            <span>
                <div class="form-element courseStrand">
                    <label>Choose Student ID</label>
                    <div>
                         <input type="text" id="student_id" name="student_id" class="form-control">
                    </div>
                </div>

                <div class="form-element courseStrand">
                    <label>Choose Section</label>
                    <div>
                        <select style="width: 85%;" class='form-control' name="course_id" id="course_id">
                        </select>
                    </div>
                </div>
            </span>
        </div>
    </div>



<script>
    $(document).ready(function() {

        // var typingTimer;
        // var doneTypingInterval = 500; // Time in milliseconds (0.5 seconds)

        // $(document).ready(function() {
        //     // Attach a keyup event handler to the text input field
        //     $('#student_id').on('keyup', function() {
        //         // Clear the previous typing timer
        //         clearTimeout(typingTimer);

        //         // Set a new typing timer after the user stops typing
        //         typingTimer = setTimeout(doneTyping, doneTypingInterval);
        //     });
        // });

        // function doneTyping() {
        //     // Get the input value from the text input field
        //     var studentId = $('#student_id').val();

        //     // Make an AJAX request to send the input value to the server

        //     $.ajax({
        //         url: '../../ajax/enrollment/populate_section.php',
        //         type: 'POST',
        //         data: {
        //             studentId,
        //         },
        //         success: function (response) {
        //             // response = response.trim();
        //             console.log(response);

        //         },
        //     });}

        $('#student_id').on('keypress', function(event) {

            if (event.which === 13) {

                event.preventDefault();

                var studentId = $(this).val();

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
                            } else {
                                // $.each(response.sections, function (index, value) {
                                //     var course_id = value.course_id;
                                //     var program_section = value.program_section;
                                // });

                                var options = '<option selected value="">Available Sections</option>';

                                $.each(response.sections, function (index, value) {
                                    options +=
                                    '<option value="' +
                                    value.course_id +
                                    '">' +
                                    value.program_section +
                                    '</option>';
                                });

                                $('#course_id').html(options);
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
</script>