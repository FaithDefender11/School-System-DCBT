<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SchoolYear.php');

    ?>
        <style>

            tr td {
                text-decoration: none;
                color: inherit; /* To maintain the link color */
                white-space: nowrap; /* Prevent text from wrapping */
            }
        </style>
    <?php

    if(isset($_GET['id'])){


        $subject_program_id = $_GET['id'];

        $subjectProgram = new SubjectProgram($con, $subject_program_id);
        $program_code = $subjectProgram->GetSubjectProgramRawCode();

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $back_url= "shs_index.php";

        ?>
            <div class="content">
                
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="action">
                        <a href="task_type_index.php">
                            <button type="button" class="btn btn-sm btn-info">+ Task</button>
                        </a>
                    </div>
                    <div class="floating">
                        <header>
                            
                            <div class="title">
                                <h5>Default Topics for: <span style="font-size: 15px;"><?php echo $program_code; ?></span></h5>
                            </div>

                            <div class="action">
                                <a href="create.php?id=<?php echo $subject_program_id; ?>&code=<?php echo $program_code;?>">
                                    <button type="button" class="clean large success">+ Add new</button>
                                </a>
                            </div>
                            
                        </header>

                        <main>
                            <table id="topic_template_table" class="a" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>Topics</th>
                                        <th>Description</th>
                                        <th>Period</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    

                                       $query = $con->prepare("SELECT   * 

                                            FROM subject_period_code_topic_template
                                            WHERE program_code = :program_code
                                            ORDER BY
                                            CASE subject_period_name
                                                WHEN 'Prelim' THEN 1
                                                WHEN 'Midterm' THEN 2
                                                WHEN 'Pre-final' THEN 3
                                                WHEN 'Final' THEN 4
                                                ELSE 5  
                                            END
                                        ");

                                        $query->bindValue(":program_code", $program_code);
                                        $query->execute();

                                        if($query->rowCount() > 0){

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $subject_period_code_topic_template_id = $row['subject_period_code_topic_template_id'];
                                                $topic = $row['topic'];
                                                $description = $row['description'];
                                                $subject_period_name = $row['subject_period_name'];
                                                $subject_period_code_topic_template_id = $row['subject_period_code_topic_template_id'];
                                                
                                                $removeTemplate =  "removeTemplate($subject_period_code_topic_template_id, $current_school_year_id)";

                                                $template_topic_assignment_list_url =  "template_topic_assignment_list.php?id=$subject_program_id&ct_id=$subject_period_code_topic_template_id";
                                                $template_topic_handout_list_url =  "template_topic_handout_list.php?id=$subject_program_id&ct_id=$subject_period_code_topic_template_id";

                                                echo "
                                                    <tr>
                                                        <td>
                                                            $topic
                                                        </td>
                                                        <td>$description</td>
                                                        <td>$subject_period_name</td>
                                                        <td>
                                                            <a style='color: inherit' href='$template_topic_handout_list_url'>
                                                                <button title='handout' class='btn btn-sm btn'>
                                                                    <i class='fas fa-book'></i>
                                                                </button>
                                                            </a>

                                                            <a href='$template_topic_assignment_list_url'>
                                                                <button title='assignment' class='btn btn-sm btn-info'>
                                                                    <i class='fas fa-file'></i>
                                                                </button>
                                                            </a>
                                                            
                                                            <a href='edit.php?id=$subject_period_code_topic_template_id&sp_id=$subject_program_id'>
                                                                <button class='btn btn-sm btn-primary'>
                                                                    <i class='fas fa-pencil'></i>
                                                                </button>
                                                            </a>


                                                            <button onclick='$removeTemplate' class='btn btn-sm btn-danger'>
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
    function removeTemplate(subject_period_code_topic_template_id,
        school_year_id){

        
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
                        url: "../../ajax/class/removeTemplateTopic.php",
                        type: 'POST',
                        data: {
                            subject_period_code_topic_template_id,
                            school_year_id
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

                                $('#topic_template_table').load(
                                    location.href + ' #topic_template_table'
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