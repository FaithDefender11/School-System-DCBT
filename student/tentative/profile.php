<?php 

    // include_once('../../includes/student_header.php');
    include_once('../../includes/pending_enrollee_header.php');
    
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');

    ?>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        </head>
    <?php

    // echo Helper::RemoveSidebar();

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
        // echo $username;

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
                    <div class="row col-md-12">
                        <div class="row">

                            <?php
                                if($isFinishedForm == false){

                                    echo "
                                        <div class='col-md-6'>
                                            <a href='process.php?new_student=true&step=1'>
                                                <button class='btn btn-outline-primary'>New Student Process</button>
                                            </a>
                                        </div>
                                    ";

                                }
                            ?>

                            <?php

                                // $url = "./enrollee_summary_details_test.php?id=$pending_enrollees_id&details=show";

                                $url = "./process.php?new_student=true&step=preferred_course";

                                if($isFinishedForm == true){
                                    echo "
                                        <div class='col-md-6'>
                                            <a href='$url'>
                                                <button class='btn btn-outline-info'>View Form</button>
                                            </a>
                                        </div>
                                        ";
                                    }
                            ?>

                        </div>

                        <div class="card">
                            <div class="card-header">
                                <div class="text-center container">
                                        <h3>Successfully filled-up the form</h3>
                                        <p>Please walk-in for registrar accomodation.</p>
                                </div>
                            
                            </div>
                            <div class="card-body"></div>
                        </div>
                    </div>
                <?php
            }

        }
        // else{
        //     echo "enrolled";
        // }

     
    }

?>
