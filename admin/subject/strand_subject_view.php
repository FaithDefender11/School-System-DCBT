<?php 

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/Section.php');


    $templateUrl = directoryPath . "template.php";

    if(isset($_GET['id'])){

        $program_id = $_GET['id'];

        $department_name = "";
        $department_type = "";
        $back_url = "";

        if(isset($_SESSION['department_type'])){

            $department_type = $_SESSION['department_type'];

            if($department_type == "Senior High School"){
                $department_name = "Senior High School";
                $back_url = "strand.php";

            }else{
                $department_name = "Tertiary";
                $back_url = "strand.php";

            }
        }

        $section = new Section($con, null);

        $strand_name = $section->GetAcronymByProgramId($program_id);

        $subject = new Subject($con, null);

        $selectSubjectTitle = $subject->SelectSubjectTitle();

        $selectSubjectEdit = $subject->SelectSubjectTitleEdit();

        require_once('./strand_subject_add_modal.php');
        require_once('./strand_subject_edit_modal.php');

        ?>
            <div class="row col-md-12">
                <div>
                    <h3 class="text-center"><?php echo $strand_name;?> Subjects</h3>

                    <a class="mb-2" href="<?php echo $back_url;?>">
                        <button class="btn btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a> 
                    <div class="row justify-content-end">

                        <button style="width: 150px; margin-bottom: 15px;" type="button" 
                            data-bs-target="#subjectAddModal" 
                            data-bs-toggle="modal"
                            data-program-id="<?php echo intval($program_id); ?>"
                            class="btn btn-sm btn-success attach-subject-button">Attach Subject
                        </button>
                    </div>
                 
                    <table id="strand_subject_view_table" class="table table-striped table-bordered table-hover "  style="font-size:13px" cellspacing="0"  > 
                        <thead>
                            <tr class="text-center"> 
                                <th rowspan="2">Subject</th>
                                <th rowspan="2">Code</th>
                                <th rowspan="2">Grade Level</th>
                                <th rowspan="2">Semester</th>
                                <th rowspan="2">Action</th>
                            </tr>	
                        </thead> 	
                        <tbody>
                            <?php 

                                $query = $con->prepare("SELECT * FROM subject_program

                                    WHERE program_id=:program_id
                                    ORDER BY course_level,
                                    semester");

                                $query->bindValue("program_id", $program_id);
                                $query->execute();

                                if($query->rowCount() > 0){
                                
                                    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                        $subject_program_id = $row['subject_program_id'];
                                        $subject_title = $row['subject_title'];
                                        $course_level = $row['course_level'];
                                        $semester = $row['semester'];
                                        $subject_code = $row['subject_code'];


                                        $removeTemplateBtn = "removeTemplateBtn($subject_program_id)";

                                        echo "
                                            <tr class='text-center'>
                                                <td>$subject_title</td>
                                                <td>$subject_code</td>
                                                <td>$course_level</td>
                                                <td>$semester</td>
                                                <td>
                                                    <button type='button' value='$subject_program_id'
                                                        class='editSubjectStrandBtn btn btn-primary btn-sm'>

                                                        <i class='fas fa-edit'></i>
                                                    </button>
                                                    <button onclick='$removeTemplateBtn' type='button' value='$subject_program_id'
                                                        class='btn btn-danger btn-sm'>
                                                        <i class='fas fa-trash'></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php
    }
?>
<script>
    function removeTemplateBtn(subject_program_id){
        Swal.fire({
                icon: 'question',
                title: `I agreed to removed ${subject_program_id}.`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/subject/remove_subject_program.php",
                        type: 'POST',
                        data: {
                            subject_program_id
                        },
                        success: function(response) {
                            response = response.trim();

                            // console.log(response);
                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Removed`,
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

                                $('#strand_subject_view_table').load(
                                    location.href + ' #strand_subject_view_table'
                                );
                            });

                            }
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
<!-- <script>
    $(document).ready(function() {
        // Handler for the button click event
        $(".attach-subject-button").click(function() {
            // Retrieve the data-program-id attribute value
            var programId = $(this).data('program-id');
            
            // alert(programId);

            // Make an Ajax request to add.php

            $.ajax({
                url: 'add.php',
                method: 'POST',
                data: { program_id: programId },
                success: function(response) {
                    // Populate the form in the modal with the response
                    // $("#modal-form").html(response);
                    // console.log(response)
                },
                error: function() {
                    // Handle error case
                    alert("Error occurred. Please try again.");
                }
            });
        });
    });
</script> -->
