<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectProgram.php');




    
    if(
        isset($_GET['id'])
        && isset($_GET['code'])
        
        ){

        $subject_program_id = $_GET['id'];
        $program_code = $_GET['code'];

        $back_url = "code_topics.php?id=$subject_program_id";



        if($_SERVER['REQUEST_METHOD'] === "POST"
            && isset($_POST['create_program_code_btn'])
            && isset($_POST['topic'])
            && isset($_POST['description'])
            && isset($_POST['subject_period_name'])
            
            ){

                $topic = $_POST['topic'];
                $description = $_POST['description'];
                $subject_period_name = $_POST['subject_period_name'];


                $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);

                $wasSuccess = $subjectPeriodCodeTopic->AddTopicTemplate($topic, $description,
                    $subject_period_name, $program_code);
                if($wasSuccess){

                    Alert::success("Program Code: $program_code Template Creation has made.", $back_url);
                    exit();
                }
        }

        ?>
            <div class='content'>

                <nav>
                    <a href="<?php echo $back_url;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                <div class='col-md-10 offset-md-1'>
                    <div class='card'>
                        
                        <div class='card-header'>
                            <h4 class="text-center text-muted">Creating topics for: <?php echo $program_code; ?></h4>
                        </div>

                        <div class="card-body">

                            <form method='POST' enctype="multipart/form-data">

                                <div class='form-group mb-2'>
                                    <label for="topic" class='mb-2'>Topic *</label>
                                    <input required id="topic" class='form-control' type='text' placeholder='' name='topic'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="description" class='mb-2'>Description *</label>
                                    <input required id="description" class='form-control' type='text' placeholder='' name='description'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="subject_period_name" class='mb-2'>Period Name *</label>
                                    
                                    <select required id="subject_period_name" class='form-control' name="subject_period_name">
                                        <option value="Prelim">Prelim</option>
                                        <option value="Midterm">Midterm</option>
                                        <option value="Pre-final">Pre-final</option>
                                        <option value="Final">Final</option>
                                    </select>
                                </div>

                                

                                <div class="modal-footer">
                                    <button type='submit' class='btn btn-success' name='create_program_code_btn'>Save Section</button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <?php
    }
?>

