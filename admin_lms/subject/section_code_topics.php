<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');


    if(
        isset($_GET['id'])
        && isset($_GET['c'])
        && isset($_GET['t_id'])
    ){

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        $course_id = $_GET['id'];
        $program_code = $_GET['c'];
        $teacher_id = $_GET['t_id'];

        $section = new Section($con, $course_id);
        $sectionName = $section->GetSectionName();

        $subjectProgram = new SubjectProgram($con);

        $subject_code = $section->CreateSectionSubjectCode($sectionName, $program_code);

        $subjectProgramId = $subjectProgram->GetSubjectProgramIdByProgramCode($program_code);

        // $subjectProgram = new SubjectProgram($con, $subjectProgramId);


        $subject_period_code_topic = NULL;

        // $teacher_id = "";

        $back_url = "section_code_list.php?id=$subjectProgramId";
        ?>
            <div class="content">
                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <main>
                    <div class="floating" id="shs-sy">
                        <header>
                            <div class="title">
                                <h4>Subject Code Topics : <span style="font-size: 15px;"><?php echo $subject_code; ?></span></h4>
                            </div>
                        </header>
                        <main>

                            <table id="topic_table" class="a" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Topics</th>
                                        <th>Description</th>
                                        <th>Period</th>
                                        <th>Given</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    

                                        $query = $con->prepare("SELECT 

                                            t1.*
                                            FROM subject_period_code_topic_template as t1
                                            WHERE t1.program_code =:program_code
                                        ");

                                        $query->bindValue(":program_code", $program_code);
                                        
                                        $query->execute();

                                        if($query->rowCount() > 0){

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                // $subject_period_code_topic_id = $row['subject_period_code_topic_id'];
                                                // $course_id = $row['course_id'];

                                                $subject_period_code_topic_template_id = $row['subject_period_code_topic_template_id'];
                                                $topic = $row['topic'];
                                                $description = $row['description'];
                                                $subject_period_name = $row['subject_period_name'];
                                                $period_order = $row['period_order'];

                                                $subject_period_code_topic_id = 0;

                                                $status = "";


                                                $subject_period_code_topic_id = NULL;

                                                $query2 = $con->prepare("SELECT 

                                                    t1.*
                                                    FROM subject_period_code_topic as t1
                                                    
                                                    WHERE topic=:topic
                                                    AND school_year_id=:school_year_id
                                                    AND course_id=:course_id
                                                    LIMIT 1
                                                ");

                                                $query2->bindValue(":topic", $topic);
                                                $query2->bindValue(":school_year_id", $current_school_year_id);
                                                $query2->bindValue(":course_id", $course_id);
                                                $query2->execute();

                                                if($query2->rowCount() > 0){

                                                    while($row2 = $query2->fetch(PDO::FETCH_ASSOC)){

                                                        $subject_period_code_topic_id = $row2['subject_period_code_topic_id'];
                                                    }
                                                }

                                                // $course_id,$teacher_id, $school_year_id,
                                                // $topic, $description,$subject_period_name,
                                                // $subject_code, $program_code

                                                $addTopic = "addTopic($subject_period_code_topic_template_id, $course_id,
                                                    $teacher_id, $current_school_year_id, \"$program_code\", \"$topic\", \"$description\", \"$subject_period_name\", \"$period_order\")";

                                                
                                                if($subject_period_code_topic_id !== NULL){
                                                    $removePopulateTopic = "removePopulateTopic($subject_period_code_topic_id, $current_school_year_id)";

                                                    $status = "

                                                        <i style='cursor: pointer;' onclick='$removePopulateTopic' class='fas fa-check'></i>
                                                    ";
                                                }else{

                                                    $status = "
                                                        <button onclick='$addTopic' class='btn btn-success'>
                                                            <i class='fas fa-add'></i>
                                                        </button>
                                                    ";
                                                }

                                                echo "
                                                    <tr>
                                                        <td>$subject_period_code_topic_id</td>
                                                        <td>$topic</td>
                                                        <td>$description</td>
                                                        <td>$subject_period_name</td>
                                                        <td>$status</td>
                                                    </tr>
                                                ";

                                            }
                                        }

                                    ?>
                                </tbody>
                            </table>

                             <!-- <div class="action">
                                <a href="section_code_create_topics.php?id=<?php echo $subjectProgramId;?>">
                                    <button type="button" class="clean large success">+ Add new</button>
                                </a>
                            </div> -->
                        </main>
                    </div>
                </main>

                <br>

                <main>
                    <div class="floating" id="shs-sy">
                        <header>
                            <div class="title">
                                <h4>Subject Code Topics : <span style="font-size: 15px;"><?php echo $subject_code; ?></span></h4>
                            </div>

                            <div class="action">
                                <a href="test_create.php?code=<?php echo $program_code;?>&t_id=<?php echo $teacher_id;?>&c_id=<?php echo $course_id ?>">
                                    <button type="button" class="clean large success">+ Populate</button>
                                </a>
                            </div>
                        </header>
                        <main>

                            <table id="topic_table_v2" class="a" style="margin: 0">
                                <thead>
                                    <tr>
                                        <th>Period</th>
                                        <th>Topics</th>
                                        <th>Description</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    

                                        $query = $con->prepare("SELECT 

                                            t1.*
                                            FROM subject_period_code_topic as t1
                                            WHERE t1.program_code =:program_code
                                            AND t1.course_id =:course_id
                                        ");

                                        $query->bindValue(":program_code", $program_code);
                                        $query->bindValue(":course_id", $course_id);
                                        $query->execute();

                                        if($query->rowCount() > 0){

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $subject_period_code_topic_id = $row['subject_period_code_topic_id'];
                                                // $course_id = $row['course_id'];

                                                // $subject_period_code_topic_template_id = $row['subject_period_code_topic_template_id'];
                                                $topic = $row['topic'];
                                                $description = $row['description'];
                                                $subject_period_name = $row['subject_period_name'];

                                                // $subject_period_code_topic_id = 0;

                                                $status = "";
                                                $subjectProgramId = 0;

                                                // $subject_period_code_topic_id = NULL;
    
                                                echo "
                                                    <tr>
                                                        <td>$subject_period_name</td>
                                                        <td>$topic</td>
                                                        <td>$description</td>
                                                        <td>
                                                            <a href='section_code_create_topics.php?id=$subjectProgramId&ct_id=$subject_period_code_topic_id'>
                                                                <button class='btn btn-success'>
                                                                    <i class='fas fa-add'></i>
                                                                </button>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                ";

                                            }
                                        }

                                    ?>
                                </tbody>
                            </table>

                             <!-- <div class="action">
                                <a href="section_code_create_topics.php?id=<?php echo $subjectProgramId;?>">
                                    <button type="button" class="clean large success">+ Add new</button>
                                </a>
                            </div> -->
                        </main>
                    </div>
                </main>
            </div>


            

        <?php
    }
?>

<script>
    function addTopic(subject_period_code_topic_template_id,
        course_id, teacher_id, current_school_year_id, program_code,
        topic, description, subject_period_name, period_order){


        // var subject_period_code_topic_template_id = parseInt(subject_period_code_topic_template_id);
        // var course_id = parseInt(course_id);

        // console.log(subject_period_code_topic_template_id);

        Swal.fire({
            icon: 'question',
            title: `Are you sure you want to populate?`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {
                    $.ajax({
                    url: "../../ajax/class/addTopic.php",
                        type: 'POST',
                        data: {
                            subject_period_code_topic_template_id,
                            course_id, teacher_id,
                            current_school_year_id,
                            program_code,
                            topic, description,
                            subject_period_name,
                            period_order
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Added`,
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

                                $('#topic_table').load(
                                    location.href + ' #topic_table'
                                );

                                // location.reload();
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

    function removePopulateTopic(subject_period_code_topic_id,
        school_year_id){

        
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
                        url: "../../ajax/class/removeTopic.php",
                        type: 'POST',
                        data: {
                            subject_period_code_topic_id,
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

