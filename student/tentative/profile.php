<?php
    // include_once('../../includes/student_header.php');
    include_once('../../includes/pending_enrollee_header.php');
    include_once('../../includes/classes/Helper.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');

        if(isset($_SESSION['username'])
        && isset($_SESSION['enrollee_id'])
        && isset($_SESSION['status']) 
        // && isset($_SESSION['email']) 

        && $_SESSION['status'] == 'pending'
        // && $_SESSION['status'] != 'enrolled'
        )
        
        {
        # Pending firstname;

        $username = $_SESSION['username'];
        $enrollee_id = $_SESSION['enrollee_id'];
        $enrollee_id = $_SESSION['enrollee_id'];

        $school_year = new SchoolYear($con);
        $section = new Section($con, null);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $school_year_id = $school_year_obj['school_year_id'];
        $current_semester = $school_year_obj['period'];
        $current_term = $school_year_obj['term'];

        $pending = new Pending($con, $enrollee_id);
        $does_enrollee_form_manual_enrollment = $pending->GetPendingManual();

        // echo $does_enrollee_form_manual_enrollment;

        $sql = $con->prepare("SELECT * FROM pending_enrollees
            -- 
            WHERE firstname=:firstname
            AND is_enrolled=:is_enrolled
            AND is_finished=:is_finished
            ");

        $sql->bindValue(":firstname", $username);
        $sql->bindValue(":is_enrolled", 0);
        $sql->bindValue(":is_finished", 1);
        
        $sql->execute();

        if($sql->rowCount() > 0){

            $isFinished = $pending->GetPendingIsFinished();
            $pending_enrollees_id = $pending->GetPendingID();

            // if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $enrollee_id = $_SESSION['enrollee_id'];

            if(isset($_GET['fill_up_state']) && $_GET['fill_up_state'] == "finished"){

                $isFinishedForm = $pending->CheckStudentFinishedForm($pending_enrollees_id);
                // include_once('./enrollee_summary_details.php');
?>

                <?php
                    echo Helper::pendingStudentHeader($con, $enrolleeLoggedInObj);
                ?>
                <main>
                    <div class="floating noBorder">
                        <header>
                            <?php
                                if($isFinishedForm == false){

                                    echo "
                                        <div class='action'>
                                            <button 
                                                class='default'
                                                onclick='window.location.href='process.php?new_student=true&step=1''
                                            >
                                                New Student Process
                                            </button>
                                        </div>
                                    ";

                                }
                            ?>
                            <?php
                                // $url = "./enrollee_summary_details_test.php?id=$pending_enrollees_id&details=show";

                                $url = "./process.php?new_student=true&step=preferred_course";
                                if($isFinishedForm == true){
                                    echo "
                                        <div class='action'>
                                            <a href='$url'>
                                                <button 
                                                    class='information'
                                                >
                                                    View Form
                                                </button>  
                                            </a>
                                        </div>
                                        ";
                                    }
                            ?>
                        </header>
                        <header>
                            <div class="title">
                                <?php 
                                    if($does_enrollee_form_manual_enrollment == 0 && $isFinished == 1){
                                        echo "
                                            <h3>You have successfully filled-up the form</h3>
                                            <p>Please go to registrar.</p>

                                        ";
                                    }
                                    if($does_enrollee_form_manual_enrollment == 1 && $isFinished == 1){
                                        echo "
                                            <h3>Registrar have successfully filled-up your enrollment form</h3>
                                        ";
                                    } 
                                ?>
                                
                                <span>Please note that after your enrollment form has approved from evaluation, certain fields will no longer editable.</span>
                                
                                <!-- Logic that if student has been given a section (Enrollment form) -->
                                <!-- Then, it should have a notice. -->
                            </div>
                        </header>
                    </div>
                </main>
            <?php
            }
        }
    }
        ?>
        </div>
    </body>
</html>