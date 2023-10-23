<?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/SubjectCodeHandoutStudent.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');

    echo Helper::RemoveSidebar();


    if(
        
        isset($_GET['id']) &&
        isset($_GET['ss_id'])
        ){

        $subject_code_handout_id = $_GET['id'];
        $student_subject_id = $_GET['ss_id'];

        $subjectCodeHandout = new SubjectCodeHandout($con, $subject_code_handout_id);
        $subjectCodeHandoutStudent = new SubjectCodeHandoutStudent($con);
        
        $subject_period_code_topic_id = $subjectCodeHandout->GetSubject_period_code_topic_id();
        $handout_file = $subjectCodeHandout->GetFile();
        $handout_name = $subjectCodeHandout->GetHandoutName();
        $handout_template_id = $subjectCodeHandout->GetSubjectCodeHandoutTemplateId();

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];

        // $teacher_id = $_SESSION['teacherLoggedInId'];

        $topic_name = $subjectPeriodCodeTopic->GetTopic();
        $topic_subject_code = $subjectPeriodCodeTopic->GetSubjectCode();
        $topic_course_id = $subjectPeriodCodeTopic->GetCourseId();

        // $back_url = "";

        // $back_url = "index.php?c_id=$topic_course_id&c=$topic_subject_code";

        // $back_url = "index.php?id=$student_subject_id";
        $back_url = "subject_module.php?id=$student_subject_id";
     
        # Check If student had goes in to this page.
        $pushToHandoutView = $subjectCodeHandoutStudent->MarkStudentViewedHandout($subject_code_handout_id,
            $studentLoggedInId, $current_school_year_id);
        

        ?>

            <div class="content">
                <!-- <div class="sidebar-nav">
                    <div class="navigationContainer">
                    <div class="navigationItem">
                        <a href="#">
                        <span class="badge">5</span>
                        <i class="bi bi-clipboard-data">
                            <span>Dashboard</span>
                        </i>
                        </a>
                    </div>
                    <div class="navigationItem">
                        <a href="#">
                        <span class="badge">5</span>
                        <i class="bi bi-bell-fill">
                            <span>Notification</span>
                        </i>
                        </a>
                    </div>
                    <div class="navigationItem">
                        <a href="#">
                        <i class="bi bi-person-circle">
                            <span>User</span>
                        </i>
                        </a>
                    </div>
                    <div class="navigationItem">
                        <a href="#">
                        <i class="bi bi-box-arrow-in-left">
                            <span>Log-out</span>
                        </i>
                        </a>
                    </div>
                    </div>
                </div> -->

            <div class="icons">
                <button class="sidebar">
                    <i class="bi bi-list"></i>
                </button>
                <div class="notif">

                    <button
                        class="icon"
                        data-toggle="tooltip"
                        data-placement="bottom"
                        title="Notification">
                        <i class="bi bi-bell-fill"></i>
                        <span class="badge-1">3</span>
                    </button>
 
                </div>

                <div class="username">
                    <a href="#" title="Profile">Cultura, Dhan Exeq...</a>
                </div>
            </div>

            <div class="content-header">
                <header>
                <div class="title">
                    <h1>UCSP Topic 1 <em>Handout</em></h1>
                </div>
                </header>
            </div>

            <nav>
                <a href="<?=$back_url;?>"
                ><i class="bi bi-arrow-return-left"></i>Back</a
                >
            </nav>

            <main>
                <div class="floating noBorder">
                <header>
                    <div class="title">
                    <h3><?= $handout_name;?></h3>
                    </div>
                </header>
                <main>
                    <form action="">
                    <div class="row">
                        <span>
                            <label for="file">File</label>
                            <div>
                                <p>
                                    <?php 

                                        $extension = pathinfo($handout_file, PATHINFO_EXTENSION);

                                        $pos = strpos($handout_file, "img_");

                                        $original_file_name = "";

                                        // Check if "img_" was found
                                        if ($pos !== false) {
                                            $original_file_name = substr($handout_file, $pos + strlen("img_"));
                                        }

                                        if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                            ?>
                                                
                                                <a title="View File" href='<?php echo "../../".  $handout_file ?>' target='__blank' rel='noopener noreferrer'>
                                                    <?php echo $original_file_name; ?>
                                                </a>
                                                <br>
                                            <?php
                                        }
                                    ?>
                                </p>
                            </div>
                        </span>
                    </div>
                    </form>
                </main>
                </div>
            </main>
            </div>

            <br>
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
                            <h4 class='text-center mb-3'><?php echo ucwords($topic_name); ?> <span style="font-size: 17px;" class="text-muted">Handout</span> </h4>
                        </div> 

                        <div class="card-body">
                            <form method='POST' enctype="multipart/form-data">

                                 <div class='form-group mb-2'>
                                    <label for="handout_name" class='mb-2'>Handout Name</label>

                                    <input disabled value="<?php echo $handout_name ?>" required class='form-control' type='text' 
                                        placeholder='Add Handout' id="handout_name" name='handout_name'>
                                </div>

                                <div class='form-group mb-2'>
                                    <label for="assignment_image" class='mb-2'>File</label>
                                    
                                    <?php if ($handout_template_id == NULL): ?>
                                        <input value="<?php echo $handout_file; ?>" id="assignment_image" class='form-control' type='file' placeholder='' name='assignment_image'>
                                    <?php endif; ?>

                                    <p>
                                        <?php 

                                            $extension = pathinfo($handout_file, PATHINFO_EXTENSION);

                                            $pos = strpos($handout_file, "img_");

                                            $original_file_name = "";

                                            // Check if "img_" was found
                                            if ($pos !== false) {
                                                $original_file_name = substr($handout_file, $pos + strlen("img_"));
                                            }

                                            if (in_array(strtolower($extension), ['pdf', 'docx', 'doc'])) {
                                                ?>
                                                    
                                                    <a title="View File" href='<?php echo "../../".  $handout_file ?>' target='__blank' rel='noopener noreferrer'>
                                                        <?php echo $original_file_name; ?>
                                                    </a>
                                                    <br>
                                                <?php
                                            }
                                        ?>
                                    </p>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        <?php
    }
?>