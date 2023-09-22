<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');


    if(
        isset($_GET['id'])
        && isset($_GET['ct_id'])
        ){


        $subject_program_id = $_GET['id'];
        $subject_period_code_topic_template_id = $_GET['ct_id'];

        $subjectPeriodCodeTopicTemplate = new SubjectPeriodCodeTopicTemplate($con,
            $subject_period_code_topic_template_id);

        $topic_name = $subjectPeriodCodeTopicTemplate->GetTopic();
      
        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $back_url= "code_topics.php?id=$subject_program_id";


        ?>
            <div class="content">
                
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="floating">
                        <header>
                            <div class="title">
                                <h5>Default Handout for topic: <?php echo $topic_name;?></h5>
                            </div>

                            <div class="action">
                                <a href="create_template_handout.php?id=<?php echo $subject_program_id; ?>&ct_id=<?php echo $subject_period_code_topic_template_id;?>">
                                    <button type="button" class="clean large success">+ Add new</button>
                                </a>
                            </div>
                            
                        </header>

                        <main>
                            <table id="handout_template_table" class="a" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Handout Name</th>
                                        <th>File</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    

                                       $query = $con->prepare("SELECT   * 

                                            FROM subject_code_handout_template
                                            WHERE subject_period_code_topic_template_id = :subject_period_code_topic_template_id
                                             
                                        ");

                                        $query->bindValue(":subject_period_code_topic_template_id", $subject_period_code_topic_template_id);
                                        $query->execute();

                                        if($query->rowCount() > 0){

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $subject_code_handout_template_id = $row['subject_code_handout_template_id'];
                                                $handout_name = $row['handout_name'];
                                                $file = $row['file'];
                                                
                                                
                                                $removeHandoutTemplate= "removeHandoutTemplate($subject_code_handout_template_id, $subject_period_code_topic_template_id)";
                                                
                                                $extension = pathinfo($file, PATHINFO_EXTENSION);

                                                $parts = explode('_', $file);

                                                $original_file_name = end($parts);

                                                $pos = strpos($file, "img_");

                                                // Check if "img_" was found
                                                if ($pos !== false) {
                                                    // Extract the filename portion
                                                    $original_file_name = substr($file, $pos + strlen("img_"));
                                                    // echo $filename; // Output: 01_Laboratory_Exercise_12.pdf
                                                }

                                                $file_output = "";

                                                $filePath = "../../$file";

                                                if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                    $file_output = "
                                                        <a title='View File' href='$filePath' target='__blank' rel='noopener noreferrer'>
                                                            $original_file_name
                                                        </a>
                                                        <br>
                                                    ";
                                                }

                                                echo "
                                                    <tr>
                                                        <td>$subject_code_handout_template_id</td>
                                                        <td>$handout_name</td>
                                                        <td>$file_output</td>

                                                        <td>

                                                            <a href='edit_template_handout.php?id=$subject_program_id&ht_id=$subject_code_handout_template_id'>
                                                                <button class='btn btn-sm btn-primary'>
                                                                    <i class='fas fa-pencil'></i>
                                                                </button>
                                                            </a>

                                                            <button onclick='$removeHandoutTemplate' class='btn btn-sm btn-danger'>
                                                                <i class='fas fa-times'></i>
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

    function removeHandoutTemplate(subject_code_handout_template_id, subject_period_code_topic_template_id){

        Swal.fire({
                icon: 'question',
                title: `Are you sure you want to remove?`,
                text: 'Important! This action cannot be undone.',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {
                    
                    $.ajax({
                        url: "../../ajax/template/removeTopicHandout.php",
                        type: 'POST',
                        data: {
                            subject_code_handout_template_id,
                            subject_period_code_topic_template_id
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

                                $('#handout_template_table').load(
                                    location.href + ' #handout_template_table'
                                );

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