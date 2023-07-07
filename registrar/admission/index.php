<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
        
    ?>  
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <!-- <link rel="stylesheet" href="./admission/evaluation.css"> -->
            <link rel="stylesheet" href="./admission.css">
        </head>
    <?php

    $enrollment = new Enrollment($con, null);
    $section = new Section($con, null);

    // O.S Irregular, Pending New Standard, New Transferee
    $unionEnrollment = $enrollment->UnionEnrollment();

    $pendingEnrollmentCount = 0;
    $unionEnrollmentCount = 0;
    $waitingPaymentEnrollmentCount = 0;
    $waitingApprovalEnrollmentCount = 0;
    $enrolledStudentsEnrollmentCount = 0;


?>

    <div class="content">

        
        <div class="content">
            <div class="back-menu">
                <button type="button" class="admission-btn" onclick="admission()">
                    <i class="fas fa-arrow-left"></i> Admission
                </button>
            </div>
            
            <div style="color: #fff;" class="head">
                <h3 class="mt-2">Enrollment form finder (SHS)</h3>
                <p>Note: Numbers on tabs only count current school year and semester</p>
                
                <div class="button-container">
                    <div class="evaluation">
                        <a href="evaluation.php">
                            <button type="button" class="selection-btn" id="evaluation" onclick="evaluation_btn()" style="background: rgb(239, 239, 239); color: black;">
                                Evaluation (<?php echo $unionEnrollmentCount;?>)
                            </button>
                        </a>

                    </div>
                    <div class="waiting-payment">
                        <a href="waiting_payment.php">
                            <button type="button" class="selection-btn" id="waiting-payment" onclick="waiting_payment_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
                            </button>
                        </a>

                    </div>
                    <div class="waiting-approval">
                        <a href="waiting_approval.php">
                            <button type="button" class="selection-btn" id="waiting-approval" onclick="waiting_approval_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
                            </button>
                        </a>
                    </div>
                    <div class="enrolled">
                        <a href="enrolled.php">
                            <button type="button" class="selection-btn" id="enrolled" onclick="enrolled_btn()" style="background: rgb(2, 0, 28); color: white;">
                                Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <main>
            <div class="floating" id="shs-sy">
                <header>
                    <div class="title">
                        <h3>Evaluation List</h3>
                    </div>

                    <div class="action">
                        <a href="create.php">
                            <button type="button" class="clean large success">+ Search</button>
                        </a>
                    </div>
                </header>
                <main>

                    <table id="department_table" class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                        <thead>
                            <tr class="text-center"> 
                                <th rowspan="2">Name</th>
                                <th rowspan="2">Student No.</th>
                                <th rowspan="2">Type</th>
                                <th rowspan="2">Strand</th>
                                <th rowspan="2">Date Submitted</th>
                                <th rowspan="2">Action</th>
                            </tr>	
                        </thead>
                        <tbody>
                            <?php 
                                if(count($unionEnrollment) > 0){
                                    foreach ($unionEnrollment as $key => $row) {

                                        $submission_creation = $row['submission_creation'];

                                        $student_status_pending = $row['student_status_pending'];
                                        $pending_enrollees_id = $row['pending_enrollees_id'];
                                        $student_course_id = $row['student_course_id'];
                                        $student_id = $row['student_id'];
                                        // $student_unique_id = $row['student_unique_id'];

                                        $student_unique_id = empty($student_unique_id) ? $row['student_unique_id'] : "N/A";

                                        $program_id = $row['program_id'];

                                        $student_statusv2 = $row['student_statusv2'];
                                        $admission_status = $row['admission_status'];
                                        $student_classification = $row['student_classification'];

                                        $identity = "";
                                        $type = "";

                                        $button_url = "";

                                        if($student_classification != NULL){
                                            # 1 -> Tertiary, 0 -> SHS
                                            $identity = $student_classification == 1 ? "Tertiary" 
                                                : ($student_classification == 0 ? "SHS" : "Pending");
                                        }

                                        $process_url = "process_enrollment.php?step1=true&id=$pending_enrollees_id";
                                        $url_trans = "transferee_process_enrollment.php?step1=true&id=$pending_enrollees_id";
                                        
                                        $fullname = $row['firstname'] . " " . $row['lastname'];

                                        $acronym = $section->GetAcronymByProgramId($program_id);

                                        $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$student_course_id";

                                        if(empty($identity) && $student_status_pending == "Standard"){
                                            # Comes from Pending Table.
                                            $type = "New Regular";
                                            $button_url = "
                                                <a href='$process_url'>
                                                    <button class='button-style-primary primary'>Evaluate</button>
                                                </a>
                                            ";
                                        }
                                        else if(empty($identity) && $student_status_pending == "Transferee"){
                                            # Comes from Pending Table.
                                            $type = "New Transferee";
                                            $button_url = "
                                                <a href='$url_trans'>
                                                    <button class='button-style-primary primary'>Evaluate</button>
                                                </a>
                                            ";
                                        }
                                        else if($identity == "Tertiary"){
                                            // $type = "O.S $admission_status (Tertiary Irregular)";
                                            // $type = "O.S $admission_status (Tertiary)";
                                            # TRANSFEREE Irregular
                                            if($student_statusv2 == "Regular"){
                                                $type = "O.S $admission_status (Tertiary) Regular";

                                            }else if($student_statusv2 == "Irregular"){
                                                $type = "O.S $admission_status (Tertiary) Irregular";

                                                $button_url = "
                                                <a href='$trans_url'>
                                                    <button class='button-style-primary primary'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                                ";
                                            }
                                        }
                                        else if($identity == "SHS"){
                                            // $type = "O.S $admission_status (Tertiary Irregular)";
                                            // $type = "O.S $admission_status (Tertiary)";
                                            # TRANSFEREE Irregular
                                            if($student_statusv2 == "Regular"){
                                                $type = "O.S $admission_status (SHS) Regular";

                                            }else if($student_statusv2 == "Irregular"){
                                                $type = "O.S $admission_status (SHS) Irregular";

                                                $button_url = "
                                                <a href='$trans_url'>
                                                    <button class='button-style-primary primary'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                                ";
                                            }
                                        }
                                        else if($identity == "SHS"){
                                            $type = "O.S $admission_status (Senior High)";
                                        }
                                        $image = "<img src='images/Zinzu Chan Lee.jpg' alt=''>";

                                        $btnn = "
                                            <button class='button-style-primary primary'>Click</button>
                                        ";
                                        echo "
                                            <tr class='text-center'>
                                                <td>$fullname</td>
                                                <td>$student_unique_id</td>
                                                <td>$type</td>
                                                <td>$acronym</td>
                                                <td>$submission_creation</td>
                                                <td>$button_url</td>
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



<?php include_once('../../includes/footer.php') ?>
