<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');


    if(
        isset($_GET['code'])
        && isset($_GET['t_id'])
        && isset($_GET['c_id'])
        ){

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $program_code = $_GET['code'];
        $course_id = $_GET['c_id'];

        $teacher_id = $_GET['t_id'];

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con); 

        $templateList = $subjectPeriodCodeTopic->GetDefaultTopicTemplate(
            $program_code);

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
                            <?php 
                                if(count($templateList) > 0){
                                    ?>
                                        
                                    <table id="default_template" class="a" style="margin: 0">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Topics</th>
                                                <th>Description</th>
                                                <th>Period</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            <?php
                                                $i = 0;
                                                foreach ($templateList as $key => $row) {
                                                   
                                                    $topic = $row['topic'];
                                                    $description = $row['description'];
                                                    $subject_period_name = $row['subject_period_name'];
                                                    $subject_period_code_topic_template_id = $row['subject_period_code_topic_template_id'];
                                                    $i++;

                                                    echo "
                                                        <tr>
                                                            <td>$i</td>
                                                            <td>$topic</td>
                                                            <td>$description</td>
                                                            <td>$subject_period_name</td>
                                                        </tr>
                                                    ";
                                                }

                                                
                                            ?>
                                        </tbody>
                                    </table>
                                    <?php

                                }else{
                                    echo "
                                        <h3 class='text-center'>No default topics</h3>
                                    ";
                                }
                            ?>
                            

                        </main>

                    </div>

                    <div class="action">
                        <button style="margin-right: 9px;"
                            type="button"
                                class="default large"
                                onclick="window.location.href = '';"
                                >
                            Return
                            </button>
                            
                            <!-- course_id, program_code -->

                        <button onclick="PopulateDefaultTopics('<?php echo $program_code; ?>', <?php echo $course_id?>, <?php echo $teacher_id?>, <?php echo $current_school_year_id; ?>)"
                            class="default success large"
                            type="submit"
                        >
                        Populate
                    </button>
                </div>

                </main>
                
            </div>
        <?php

    }
?>

<script>

    function PopulateDefaultTopics(program_code, course_id, teacher_id, school_year_id){
        Swal.fire({
                icon: 'question',
                title: `Do you want to populate here the ${program_code} topics?`,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/class/populateTopic.php",
                        type: 'POST',
                        data: {
                            program_code,
                            course_id,
                            teacher_id,
                            school_year_id
                        },
                        success: function(response) {
                            response = response.trim();

                            console.log(response);

                            if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Populated`,
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

                                location.reload();
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
