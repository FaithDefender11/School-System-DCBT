<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');

    ?>  
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./admission.css">
        </head>
    <?php

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $enrollment = new Enrollment($con, null);
    $section = new Section($con, null);

    // O.S Irregular, Pending New Standard, New Transferee
    $unionEnrollment = $enrollment->UnionEnrollment();
    $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
    $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
    $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);


    $pendingEnrollmentCount = 0;
    $unionEnrollmentCount = 0;
    $waitingApprovalEnrollmentCount = 0;
    $enrolledStudentsEnrollmentCount = 0;


    $unionEnrollmentCount = count($unionEnrollment);
    $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
    $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
    $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);


?>

    <div class="content">
      <nav>
        <a href="registrar_admission.html"
          ><i class="bi bi-arrow-return-left fa-1x"></i>
          <span>Back</span>
        </a>
      </nav>
      <div class="content-header">
        <header>
          <div class="title">
            <h1>Enrollment form <em>SHS</em></h1>
            <small
              >Note: Numbers on tabs only count current school year and
              semester</small
            >
          </div>
        </header>
      </div>

      <div class="tabs">
        <button
          class="tab"
          id="shsEvaluation"
          style="background-color: var(--them)"
          onclick="window.location.href = 'evaluation.php';"
        >
          Evaluation (<?php echo $unionEnrollmentCount;?>)
        </button>
         
        <button
          class="tab"
          id="shsPayment"
          style="background-color: var(--mainContentBG); color: white"
          onclick="window.location.href = 'waiting_payment.php';"
        >
          Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
        </button>
        <button
          class="tab"
          id="shsApproval"
          style="background-color: var(--them); color: white"
          onclick="window.location.href = 'waiting_approval.php';"
        >
          Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
        </button>
        <button
          class="tab"
          id="shsEnrolled"
          style="background-color: var(--them); color: white"
          onclick="window.location.href = 'enrolled_students.php';"
        >
          Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
        </button>
      </div>

      <main>

        <div class="floating">
          <header>
            <div class="title">
              <h3>Form details</h3>
            </div>
            <div class="action">
              <button class="default">Select all</button>
              <button class="default">Un-select all</button>
              <div class="dropdown">
                <button class="icon">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item" style="color: red"
                    ><i class="bi bi-file-earmark-x"></i>Delete form</a
                  >
                </div>
              </div>
            </div>
          </header>
          <main>
            <table>
              <tr>
                <th style="border-right: 2px solid black">Search by</th>
                <td><button>Name</button></td>
                <td><button>Email</button></td>
                <td><button>Student ID</button></td>
              </tr>
            </table>
            <input type="search" name="search" id="search" />
            <button><i class="bi bi-search icon"></i>Search</button>

            <table class="a">
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
                        // Generate a random alphanumeric string as the enrollment form ID

                        $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
                    
                        $transResult = "";
                        $createUrl = "";

                        foreach ($waitingPaymentEnrollment as $key => $row) {

                                $enrollement_student_id = $row['student_id'];
                                $fullname = $row['firstname'] . " " . $row['lastname'];
                                $enrollment_date = $row['enrollment_date'];
                                $standing = $row['course_level'];
                                $course_id = $row['course_id'];
                                $username = $row['username'];
                                $student_unique_id = $row['student_unique_id'];
                                $student_id = $row['t2_student_id'];
                                $program_section = $row['program_section'];
                                $cashier_evaluated = $row['cashier_evaluated'];
                                $registrar_evaluated = $row['registrar_evaluated'];
                                $course_level = $row['course_level'];

                                $student_status = $row['student_statusv2'];
                                $admission_status = $row['admission_status'];

                                $new_enrollee = $row['new_enrollee'];
                                $is_tertiary = $row['is_tertiary'];
                                // $is_transferee = $row['is_transferee'];

                                // $program_section_default = "";
                                if($program_section === ""){
                                    $program_section = "NO SECTION";
                                }

                                // $course_level_default = "";
                                if($course_level == ""){
                                    $course_level = "NO SECTION";
                                }else{
                                    $course_level = "Grade $course_level";
                                }
                            
                                $createUrl = "http://localhost/dcbt/admin/student/edit.php?id=$student_id";

                                // $transferee_insertion_url = "http://localhost/dcbt/admin/student/transferee_insertion.php?id=$student_id";
                                $transferee_insertion_url = "../student/transferee_insertion.php?enrolled_subjects=true&id=$student_id";



                                $regular_insertion_url = "./subject_insertion_summary.php?id=$student_id&student_details=show";

                                $confirmButton  = "
                                    <button onclick='confirmValidation(" . $course_id . ", " . $enrollement_student_id . ")' name='confirm_validation_btn' class='btn btn-primary btn-sm'>Confirm</button>
                                ";

                                $evaluateBtn = "";

                                $student_type_status = "";

                                if($cashier_evaluated == "no"
                                    && $registrar_evaluated == "yes"){

                                    if($admission_status == "Standard"){

                                        $evaluateBtn = "
                                            <a href='$regular_insertion_url'>
                                                <button class='button-style-secondary secondary'>
                                                    Check
                                                </button>
                                            </a>
                                        ";

                                        if($new_enrollee == 1 && $is_tertiary == 0){
                                            $student_type_status = "New Regular (SHS)";

                                        }else if($new_enrollee == 0 && $is_tertiary == 0){
                                            $student_type_status = "On Going Standard (SHS)";
                                        }
                                        else if($new_enrollee == 0 && $is_tertiary == 1){
                                            $student_type_status = "On Going Standard (Tertiary)";
                                        }
                                    }

                                    # if Transferee
                                    if($admission_status == "Transferee"){

                                        // echo $admission_status;

                                            // if($new_enrollee == 0 || $new_enrollee == 1){
                                        if($new_enrollee == 1 && $is_tertiary == 0 
                                            ){

                                            $student_type_status = "New Transferee (SHS)";

                                            $evaluateBtn = "
                                                <a href='$transferee_insertion_url'>
                                                    <button class='btn btn-outline-success btn-sm'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                            ";

                                        }else if($new_enrollee == 0 && $is_tertiary == 0 

                                            #
                                            // && $student_status == "Irregular"
                                            ){

                                            $student_type_status = "On Going Transferee (SHS)";

                                            // $evaluateBtn = "
                                            //     <a href='cashier_process_enrollment.php?id=$student_id'>

                                            //         <button class='btn btn-outline-primary btn-sm'>
                                            //             Evaluate
                                            //         </button>
                                            //     </a>
                                            // ";

                                            // $asd = $course_id;

                                            // $trans_url = "transferee_process_enrollment.php?step3=true&id=$student_id&selected_course_id=$course_id";

                                            # PREVIOUS URL
                                            $trans_url = "transferee_process_enrollment.php?step3=true&st_id=$student_id&selected_course_id=$course_id";

                                            $evaluateBtn = "
                                                <a href='$transferee_insertion_url'>
                                                    <button class='btn btn-outline-success btn-sm'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                            ";
                                        }
                                        else if($new_enrollee == 0 && $is_tertiary == 1
                                            ){
                                            $student_type_status = "On Going Transferee (Tertiary)";

                                            $evaluateBtn = "
                                                <a href='$transferee_insertion_url'>
                                                    <button class='btn btn-outline-success btn-sm'>
                                                        Evaluate
                                                    </button>
                                                </a>
                                            ";
                                        }
                                    }
                                }


                                echo "
                                    <tr class='text-center'>
                                        <td>$fullname</td>
                                        <td>$student_unique_id</td>
                                        <td>$student_type_status</td>
                                        <td>$program_section</td>
                                        <td>$enrollment_date </td>
                                        
                                        <td>
                                            $evaluateBtn
                                        </td>
                                    </tr>
                                ";
                        }
                    ?>
                </tbody>

            </table>
          </main>
        </div>

      </main>
      
    </div>



<?php include_once('../../includes/footer.php') ?>
