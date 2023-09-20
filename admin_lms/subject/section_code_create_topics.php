<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Section.php');


    if(isset($_GET['id'])
        && isset($_GET['ct_id'])){


        $subject_program_id = $_GET['id'];
        $subject_period_code_topic_id = $_GET['ct_id'];

        $subjectProgram = new SubjectProgram($con, $subject_program_id);

        $program_code = $subjectProgram->GetSubjectProgramRawCode();

        ?>
            <div class="content">
                <main>
                    <div class="floating" id="shs-sy">
                        <header>
                            <div class="title">
                                <h5>Default Topics for: <span style="font-size: 15px;"><?php echo $program_code; ?></span></h5>
                            </div>
                        </header>

                        <main>
                            <table id="department_table" class="a" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Topics</th>
                                        <th>Description</th>
                                        <th>Period</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    
                                        $query = $con->prepare("SELECT * FROM subject_period_code_topic_template

                                            WHERE program_code =:program_code
                                        ");

                                        $query->bindValue(":program_code", $program_code);
                                        $query->execute();

                                        if($query->rowCount() > 0){
                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $topic = $row['topic'];
                                                $description = $row['description'];
                                                $subject_period_name = $row['subject_period_name'];
                                                $subject_period_code_topic_template_id = $row['subject_period_code_topic_template_id'];
                                                
                                                echo "
                                                    <tr>
                                                        <td>$subject_period_code_topic_template_id</td>
                                                        <td>$topic</td>
                                                        <td>$description</td>
                                                        <td>$subject_period_name</td>
                                                        <td>
                                                            <button onclick='' class='btn btn-success'>
                                                                <i class='fas fa-plus-circle'></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                ";
                                            }
                                        }

                                    ?>
                                </tbody>
                            </table>

                        </main>
                    </div>
                </main>
            </div>
        <?php

    }
?>



<script>

    function addTopicTemplate(user_id){

        var user_id = parseInt(user_id);

        Swal.fire({
                icon: 'question',
                title: `Are you sure you want to remove user?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/super_admin/removeUser.php",
                        type: 'POST',
                        data: {
                            user_id
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

                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );

                                location.reload();
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