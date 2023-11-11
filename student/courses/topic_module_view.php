<?php
    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeHandout.php');
    include_once('../../includes/classes/SubjectCodeHandoutStudent.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectModuleAudit.php');

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

        $handoutTopic = $subjectPeriodCodeTopic->GetTopic();

        $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        $school_year = new SchoolYear($con);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_id = $school_year_obj['school_year_id'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_term = $school_year_obj['term'];


        # Adding Audit Trail.

        $subjectModuleAudit = new SubjectModuleAudit($con);

        $handout_audit_name = "Viewed $handout_name under $handoutTopic";

        $doesAuditSuccess = $subjectModuleAudit->InsertAuditOfSubjectModule(
            $student_subject_id, $current_school_year_id,
            $handout_audit_name);


        

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

            <?php
                echo Helper::lmsStudentNotificationHeader(
                    $con, $studentLoggedInId,
                    $school_year_id, $enrolledSubjectList,
                    $enrollment_id,
                    "second",
                    "second",
                    "second"
                );
            ?>

            <div class="content-header">
                <header>
                    <div class="title">
                        <h1><?= $handoutTopic;?> <em>Handout</em></h1>
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
                                    </div>
                                </span>
                            </div>
                        </form>
                    </main>
                </div>
            </main>
        </div>
    <?php
    }
    ?>
    </body>
</html>