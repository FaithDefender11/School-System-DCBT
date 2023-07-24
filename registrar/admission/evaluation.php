<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
      
    ?>  
        <!-- <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./admission.css">
        </head> -->
    <?php

     if (isset($_SESSION['enrollment_form_id'])) {
            unset($_SESSION['enrollment_form_id']);
    }

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
          style="background-color: var(--mainContentBG)"
          onclick="window.location.href = 'evaluation.php';"
        >
          Evaluation (<?php echo $unionEnrollmentCount;?>)
        </button>
         
        <button
          class="tab"
          id="shsPayment"
          style="background-color: var(--them); color: white"
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

                                if(count($unionEnrollment) > 0){

                                  foreach ($unionEnrollment as $key => $row) {

                                    $submission_creation = $row['submission_creation'];

                                    $student_status_pending = $row['student_status_pending'];
                                    $pending_enrollees_id = $row['pending_enrollees_id'];
                                    $student_course_id = $row['student_course_id'];
                                    $student_id = $row['student_id'];

                                    $student_unique_id = empty($student_unique_id) ? $row['student_unique_id'] : "N/A";

                                    $program_id = $row['program_id'];
                                    $new_enrollee = $row['new_enrollee'];
                                    $enrollment_student_status = $row['enrollment_student_status'];
                                    $enrollment_is_new_enrollee = $row['enrollment_is_new_enrollee'];
                                    $enrollment_is_transferee = $row['enrollment_is_transferee'];


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

                                    // Things to consider.
                                    // 1. New Transferee -> Pending Table.
                                    // 2. Irregular -> Student Table.

                                    // $advicing_os_enrollment_url = "advising_os_process_enrollment.php?details=show&id=$student_id";

                                    $student_table_enrollment_url = "process_enrollment.php?details=show&st_id=$student_id";

                                    $advicing_pending_enrollment_url = "advising_pending_process_enrollment.php?details=show&id=$pending_enrollees_id";
                                    
                                    $fullname = $row['firstname'] . " " . $row['lastname'];

                                    $acronym = $section->GetAcronymByProgramId($program_id);

                                    // Note. SHS & Tertiary is the same
                                    // When it comes to advicing of subjects.

                                    // 1st sem
                                    // 1. New Not Transferee
                                    // 2. New And Transferee
                                    // 3. Ongoing Irregular

                                    // 2nd sem
                                    // 2. New And Transferee
                                    // 3. Ongoing Irregular

                                    $updated_type = "";
                                    
                                    if($new_enrollee == 0
                                        && $enrollment_is_new_enrollee == 0 
                                        && $enrollment_is_transferee == 0
                                        && $student_statusv2 == "Irregular"
                                        && ($enrollment_student_status == "" || $enrollment_student_status == "Irregular")
                                        ){

                                        $updated_type = "Old Irregular";

                                        $button_url = "
                                            <button class='default'
                                                onclick=\"window.location.href = '" . $student_table_enrollment_url . "'\">
                                                Evaluate
                                            </button>
                                        ";
                                    }
                                    else if($new_enrollee == 0
                                        && $enrollment_is_new_enrollee == 0 
                                        && $enrollment_is_transferee == 0
                                        && $student_statusv2 == "Regular"
                                        && $enrollment_student_status == "Regular"
                                        ){

                                        $updated_type = "Old Regular";

                                        $button_url = "
                                            <button class='default'
                                              onclick=\"window.location.href = '" . $student_table_enrollment_url . "'\">
                                                Evaluate
                                            </button>
                                        ";
                                    }

                                    else if($new_enrollee == 1
                                      && $enrollment_is_new_enrollee == 1 
                                      && $enrollment_is_transferee == 0
                                      && $student_statusv2 == "Regular"
                                      && $enrollment_student_status == "Regular"
                                      ){

                                      $updated_type = "New Regular";

                                      $button_url = "
                                          <button class='default clean'
                                            onclick=\"window.location.href = '" . $student_table_enrollment_url . "'\">
                                              Evaluate
                                          </button>
                                      ";
                                    }
                                    // 
                                    else if($new_enrollee == 1
                                      && $enrollment_is_new_enrollee == 1 
                                      && $enrollment_is_transferee == 1
                                      && $student_statusv2 == ""
                                      // && $enrollment_student_status == ""
                                      ){

                                      $updated_type = "New Transferee";

                                      $button_url = "
                                          <button class='default clean'
                                            onclick=\"window.location.href = '" . $student_table_enrollment_url . "'\">
                                              Evaluate
                                          </button>
                                      ";
                                    }
                                    
                                    else if($student_status_pending == "Transferee"
                                        ){
                                        $updated_type = "New Transferee Enrollee";


                                        $button_url = "
                                            <button class='default'
                                                onclick=\"window.location.href = '" . $process_url . "'\">
                                                Evaluate
                                            </button>
                                        ";
                                    }

                                    else if($student_status_pending == "Standard"
                                        ){
                                        $updated_type = "New Enrollee";

                                        $button_url = "
                                            <button class='default'
                                                onclick=\"window.location.href = '" . $process_url . "'\">
                                                Evaluate
                                            </button>
                                        ";
                                    }
                                        
                                    echo "
                                        <tr class='text-center'>
                                            <td>$fullname</td>
                                            <td>$student_unique_id</td>
                                            <td>$updated_type</td>
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
