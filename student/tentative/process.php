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

    echo Helper::RemoveSidebar();
 
    ?>
         <style>
            .read_only{
                pointer-events: none;
            }
         </style>
    <?php

    // echo "Student Tentative Page";
    // echo "<br>";
    // echo $_SESSION['studentLoggedIn'];
    // echo "<br>";
    // echo $_SESSION['status'];

    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

    // echo $_SESSION['username'];
    // echo $_SESSION['status'];
    // echo $_SESSION['enrollee_id'];


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
            AND is_finished = 0
            AND activated = 1
            AND student_status != 'APPROVED'
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
            
            $pending = new Pending($con, $pending_enrollees_id);

            $department = new Department($con);

            $admission_status = $pending->GetPendingAdmissionStatus();
            $pending_type = $pending->GetPendingType();
            $course_level = $pending->GetCourseLevel();
            $program_id = $pending->GetPendingProgramId();

            // echo $pending_type;

            // Student Info -> Parent -> Review -> Student Requiremnts Upload

            if(isset($_GET['new_student']) && $_GET['new_student'] == "true"){


                if(isset($_GET['step']) && $_GET['step'] == "preferred_course"){
                   include_once('./preferred_course.php');
                }

                if(isset($_GET['step']) && $_GET['step'] == "enrollee_information"){
                  
                    include_once('./enrollee_information.php');
                }

                // if(isset($_GET['step']) && $_GET['step'] == "student_requirements"){
                  
                //     include_once('./student_requirements.php');
                // }


                if(isset($_GET['step']) && $_GET['step'] == "enrollee_parent_information"){

                    include_once('./enrollee_parent_information.php');
                }


                if(isset($_GET['step']) && $_GET['step'] == "enrollee_summary_details"){
                    include_once('./enrollee_summary_details.php');
                }
            }
        }

        $isFinished = $pending->GetPendingIsFinished();

        if($isFinished != null && $isFinished == 1){

            if(isset($_GET['step']) && $_GET['step'] == 4){
                ?>
                    <div class="content">
                        
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
                                            <p>
                                            Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                            Laudantium, molestias culpa dicta earum cupiditate non a ipsam
                                            repellat nulla, quisquam cum cumque, iste omnis ab error. Debitis
                                            rem asperiores cumque?
                                            </p>
                                            <ul>
                                            <li>Please kindly walk in to registrar for completion your requirements</li>
                                            </ul>
                                        </div>
                                    </header>
                                    
                            </div>
                        </main>

                        <div style="margin-top: 10px; text-align:right;" class="col-md-11">
                            <a href="profile.php?fill_up_state=finished">
                                <button class="default large">Return to Home</button>
                            </a>
                        </div>
                    </div> 
                        
                <?php
            }

        }
       

    } 
?>