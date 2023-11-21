<?php
    // include_once('../../includes/student_header.php');
    include_once('../../includes/pending_enrollee_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Alert.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/StudentRequirement.php');
    include_once('../../includes/classes/Program.php');

    $init = false;

    if($init == false){
        
        echo Helper::RemoveSidebar();
        $init = true;
    }


 
    ?>
        
        <style>
            .read_only{
                pointer-events: none;
            }
            .red{
                color: red;
            }
         </style>
    <?php

 
    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

    // echo $_SESSION['username'];
    // echo $_SESSION['status'];
    // echo $_SESSION['enrollee_id'];


    $base_url = "";

    // $logout_url = 'http://localhost/school-system-dcbt/logout.php';

    if ($_SERVER['SERVER_NAME'] === 'localhost')
        $base_url = 'http://localhost/school-system-dcbt/student/';
    else 
        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';
    

    $logout_url = 'http://localhost/school-system-dcbt/enrollee_logout.php';

    if ($_SERVER['SERVER_NAME'] !== 'localhost') {
        $new_url = str_replace("/student/", "", $base_url);
        $logout_url = "$new_url/enrollee_logout.php";
    }

// var_dump($init);

    if($init == true){

        if(isset($_SESSION['username'])
            && isset($_SESSION['enrollee_id'])
            && isset($_SESSION['status']) 

            && $_SESSION['status'] == 'pending'
            // && $_SESSION['status'] != 'enrolled'
            ){

            $username = $_SESSION['username'];
            $enrollee_id = $_SESSION['enrollee_id'];

            $pending_enrollees_id  = $enrollee_id;

            // echo $enrollee_id;
            $pending = new Pending($con, $pending_enrollees_id);
            $check = $pending->CheckIdExists($pending_enrollees_id);

            // $autoRedirectIfFinishedForm = $pending->FinishedFormAutoRedirect();

            $sql = $con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id
                -- AND is_finished = 0
                AND activated = 1
                AND is_enrolled = 0
                -- AND student_status != 'APPROVED'
                ");
            
            $sql->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $sql->execute();

            if($sql->rowCount() > 0){

                $row = $sql->fetch(PDO::FETCH_ASSOC);

                $pending_enrollees_id = $row['pending_enrollees_id'];

                # STEP 1

                $check = $pending->CheckInitialStatus($pending_enrollees_id);

                if($check == false){
                    // echo "ERROR 401.";
                    // exit();
                }

                // echo "qwe";
                $pending = new Pending($con, $pending_enrollees_id);
                $does_enrollee_form_manual_enrollment = $pending->GetPendingManual();
                // $doesManuallyOperated = $does_enrollee_form_manual_enrollment == 1 ? "
                //     style='pointer-events: none;'
                // " : "";

                $department = new Department($con);

                $admission_status = $pending->GetPendingAdmissionStatus();
                $pending_type = $pending->GetPendingType();
                $course_level = $pending->GetCourseLevel();
                $program_id = $pending->GetPendingProgramId();
                $acceptance_condition = $pending->GetAcceptanceCondition();
                $does_enrollee_finished_input = $pending->GetIsFinished();
                $enrollee_status = $pending->GetEnrolleeStatus();

                $doesManuallyOperated = $enrollee_status == "APPROVED" ? "
                    style='pointer-events: none;'
                " : "";

                // echo $enrollee_status;

                // Student Info -> Parent -> Review -> Student Requiremnts Upload

                if(isset($_GET['new_student']) && $_GET['new_student'] == "true"){

                    if(isset($_GET['step']) && $_GET['step'] == "preferred_course"){
                        include_once('./preferred_course.php');
                    }

                    if(isset($_GET['step']) && $_GET['step'] == "enrollee_requirements"){
                    include_once('./enrollee_requirements.php');
                    }

                    if(isset($_GET['step']) && $_GET['step'] == "enrollee_school_history"){
                    include_once('./enrollee_school_history.php');
                    }

                    if(isset($_GET['step']) && $_GET['step'] == "enrollee_information"){
                    
                        include_once('./enrollee_information.php');
                    }

                    if(isset($_GET['step']) && $_GET['step'] == "enrollee_parent_information"){

                        include_once('./enrollee_parent_information.php');
                    }


                    if(isset($_GET['step']) && $_GET['step'] == "enrollee_summary_details"){
                        include_once('./enrollee_summary_details.php');
                    }

                }
            }

            $isFinished = $pending->GetPendingIsFinished();

            // var_dump($isFinished);

            if($isFinished != null && $isFinished == 1){

                // echo "hey";

                if(isset($_GET['step']) && $_GET['step'] == 4){
?>

                <main>
                    <div class="floating noBorder">
                        <header>
                            <div class="title">
                                <h2 style="color: var(--titleTheme)">New Student Form</h2>
                                <small>SY <?php echo $current_term;?></small>
                            </div>
                        </header>
                        <div class="progress">
                            <span class="dot active"><p>Preferred Course/Strand</p></span>
                            <span class="line active"></span>
                            <span class="dot active"> <p>Personal Information</p></span>
                            <span class="line active"></span>
                            <span class="dot active"> <p>Validate Details</p></span>
                            <span class="line active"></span>
                            <span class="dot active"> <p>Finished</p></span>
                        </div>
                        <div class="floating noBorder">
                            <header>
                                <div class="title">
                                    <h2>You've successfully completed your form!</h2>
                                </div>
                            </header>
                            <header>
                                <div class="title">
                                    <h3 style="color: black">What's next?</h3>
                                    <ul>
                                        <li>Please proceed to registrar</li>
                                        <!-- <li>Check your email after you are officially enrolled.</li> -->
                                    </ul>
                                </div>
                            </header>
                        </div>
                        <div class="action">
                            <button 
                                class="default large"
                                onclick="window.location.href='profile.php?fill_up_state=finished'"
                            >
                                Return to Home
                            </button>
                        </div>
                    </div>
                </main>
                <?php
                }
            }
        }
    }
        ?>
        </div>
        <script>
            $(document).ready(function () {

                var hasAcceptedTerms = localStorage.getItem("acceptedTerms");

                // If the user has not accepted the terms, show the modal
                if (!hasAcceptedTerms) {
                $("#termsModal").modal({
                    backdrop: "static", // Prevent closing by clicking outside the modal
                    keyboard: false // Prevent closing by pressing ESC key
                });
                }

                // Handle the accept button click
                $("#acceptTerms").click(function () {
                // Store in local storage that the user has accepted the terms
                localStorage.setItem("acceptedTerms", "true");
                // Close the modal
                $("#termsModal").modal("hide");
                });

            });

        </script>
    </body>
</html>