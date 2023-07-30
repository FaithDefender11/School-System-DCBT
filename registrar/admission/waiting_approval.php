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
        style="background-color: var(--them); color: white"
        onclick="window.location.href = 'waiting_payment.php';"
      >
        Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
      </button>
      <button
        class="tab"
        id="shsApproval"
        style="background-color: var(--mainContentBG); color: white"
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
                      // foreach ($waitingApprovalEnrollment as $key => $row) {

                      //     $enrollement_student_id = $row['student_id'];
                      //     $fullname = $row['firstname'] . " " . $row['lastname'];
                      //     $standing = $row['course_level'];
                      //     $student_unique_id = $row['student_unique_id'];
                          
                      //     $course_id = $row['course_id'];
                      //     $username = $row['username'];
                      //     $student_id = $row['t2_student_id'];
                      //     $program_section = $row['program_section'];
                      //     $cashier_evaluated = $row['cashier_evaluated'];
                      //     $registrar_evaluated = $row['registrar_evaluated'];
                      //     $course_level = $row['course_level'];
                      //     $student_status = $row['student_status'];
                      //     $new_enrollee = $row['new_enrollee'];
                      //     $is_tertiary = $row['is_tertiary'];
                      //     $is_transferee = $row['is_transferee'];
                      //     $enrollment_approve = $row['enrollment_approve'];
                          
                      //     $admission_status = $row['admission_status'];
                      //     $student_statusv2 = $row['student_statusv2'];
                      //     $enrollment_is_new_enrollee = $row['enrollment_is_new_enrollee'];
                      //     $enrollment_is_transferee = $row['enrollment_is_transferee'];
                      //     $enrollment_student_status = $row['enrollment_student_status'];

                      //     // $program_section_default = "";
                      //     if($program_section === ""){
                      //         $program_section = "NO SECTION";
                      //     }

                      //     // $course_level_default = "";
                      //     if($course_level == ""){
                      //         $course_level = "NO SECTION";
                      //     }else{
                      //         $course_level = "Grade $course_level";
                      //     }
                          
                      //     $createUrl = "http://localhost/dcbt/admin/student/edit.php?id=$student_id";

                      //     $transferee_insertion_url = "../student/transferee_insertion.php?enrolled_subjects=true&id=$student_id";

                      //     // $regular_insertion_url = "../enrollees/subject_insertion.php?enrolled_subjects=true&id=$student_id";

                      //     $regular_insertion_url = "./subject_insertion_summary.php?id=$student_id&enrolled_subject=show";
                          
                      //     $confirmButton  = "
                      //             <button onclick='confirmValidation(" . $course_id . ", " . $enrollement_student_id . ")' name='confirm_validation_btn' class='btn btn-primary btn-sm'>Confirm</button>
                      //     ";

                      //     $evaluateBtn = "";

                      //     $student_type_status = "";
                          
                      //     if($cashier_evaluated == "yes"
                      //         && $registrar_evaluated == "yes"){

                      //       // if($student_statusv2 == "Regular"){
                      //       //     $evaluateBtn = "
                      //       //         <a href='$regular_insertion_url'>
                      //       //             <button class='button-style-success success'>
                      //       //                 Approve
                      //       //             </button>
                      //       //         </a>
                      //       //     ";

                      //       //     if($new_enrollee == 1 && $is_tertiary == 0){
                      //       //         $student_type_status = "New Regular (SHS)";

                      //       //     }else if($new_enrollee == 0 && $is_tertiary == 0){
                      //       //         $student_type_status = "On Going SHS";

                      //       //     }
                      //       //     else if($new_enrollee == 0 && $is_tertiary == 1){
                      //       //         $student_type_status = "O.S Tertiary (Regular)";
                      //       //     }
                                
                      //       // }else if($student_statusv2 == "Irregular"){

                      //       // if($new_enrollee == 1 && $is_tertiary == 1){
                      //       //     $student_type_status = "New Tertiary (Irregular)";
                      //       // }
                      //       // else if($new_enrollee == 0 && $is_tertiary == 1){
                      //       //     $student_type_status = "O.S Tertiary (Irregular)";
                      //       // }

                      //       $evaluateBtn = "
                      //           <a href='$regular_insertion_url'>
                      //               <button class='button-style-success success'>
                      //                   Evaluate
                      //               </button>
                      //           </a>
                      //       ";



                      //       if($new_enrollee == 0
                      //           && $enrollment_is_new_enrollee == 0 
                      //           && $enrollment_is_transferee == 0
                      //           && $student_statusv2 == "Irregular"
                      //           && $enrollment_student_status == "Irregular"
                      //           ){

                      //           $updated_type = "Old Irregular";

                      //           $button_url = "
                      //               <button class='default'
                      //                   onclick=\"window.location.href = '" . $regular_insertion_url . "'\">
                      //                   Evaluate
                      //               </button>
                      //           ";
                      //       }

                      //       else if($new_enrollee == 0
                      //           && $enrollment_is_new_enrollee == 0 
                      //           && $enrollment_is_transferee == 0
                      //           && $student_statusv2 == "Regular"
                      //           && $enrollment_student_status == "Regular"
                      //           ){

                      //           $updated_type = "Evaluate";

                      //           $button_url = "
                      //               <button class='default success'
                      //                 onclick=\"window.location.href = '" . $regular_insertion_url . "'\">
                      //                   Modified
                      //               </button>
                      //           ";
                      //       }

                      //       else if($new_enrollee == 1
                      //           && $enrollment_is_new_enrollee == 1 
                      //           && $enrollment_is_transferee == 0
                      //           && $student_statusv2 == "Regular"
                      //           && $enrollment_student_status == "Regular"
                      //           ){

                      //           $updated_type = "New Regular";

                      //           $button_url = "
                      //               <button class='default clean'
                      //                 onclick=\"window.location.href = '" . $regular_insertion_url . "'\">
                      //                   Modified
                      //               </button>
                      //           ";

                      //       }

                      //       else if($new_enrollee == 1
                      //           && $enrollment_is_new_enrollee == 1 
                      //           && $enrollment_is_transferee == 1
                      //           && $student_statusv2 == ""
                      //           && $enrollment_student_status == ""
                      //           ){

                      //           $updated_type = "New Transferee";

                      //           $button_url = "
                      //               <button class='default clean'
                      //                 onclick=\"window.location.href = '" . $regular_insertion_url . "'\">
                      //                   Modified
                      //               </button>
                      //           ";

                      //       }
                            
                      //       else if($student_status_pending == "Transferee"
                      //           ){
                      //           $updated_type = "New Transferee Enrollee";


                      //           $button_url = "
                      //               <button class='default'
                      //                   onclick=\"window.location.href = '" . $regular_insertion_url . "'\">
                      //                   Evaluate
                      //               </button>
                      //           ";
                      //       }
                            
                      //       else if($student_status_pending == "Standard"){

                      //         $updated_type = "New Enrollee";

                      //         $button_url = "
                      //             <button class='default'
                      //                 onclick=\"window.location.href = '" . $regular_insertion_url . "'\">
                      //                 Evaluate
                      //             </button>
                      //         ";
                      //       }
                              
                      //   }


                      //     echo "
                      //         <tr class='text-center'>
                      //             <td>$fullname</td>
                      //             <td>$student_unique_id</td>
                      //             <td>$updated_type</td>
                      //             <td>$program_section</td>
                      //             <td>$enrollment_approve </td>
                      //             <td>
                      //                 $evaluateBtn
                      //             </td>
                      //         </tr>
                      //     ";

                      // }


                    foreach ($waitingApprovalEnrollment as $key => $row) {

                      $enrollement_student_id = $row['student_id'];
                      $fullname = ucfirst($row['firstname']) . " " . ucfirst($row['lastname']);
                      $enrollment_date = $row['enrollment_date'];
                      $standing = $row['course_level'];
                      $course_id = $row['course_id'];
                      $enrollment_course_id = $row['enrollment_course_id'];
                      $registrar_confirmation_date = $row['registrar_confirmation_date'];
                      
                      $dateTime = new DateTime($registrar_confirmation_date);
                      // Format the DateTime object as desired
                      $registrar_confirmation_date = $dateTime->format('Y-m-d g:i A');


                      $username = $row['username'];
                      $student_unique_id = $row['student_unique_id'];
                      $student_id = $row['t2_student_id'];
                      $program_section = $row['program_section'];
                      $cashier_evaluated = $row['cashier_evaluated'];
                      $registrar_evaluated = $row['registrar_evaluated'];
                      $course_level = $row['course_level'];

                      $student_status = $row['student_statusv2'];
                      $student_statusv2 = $row['student_statusv2'];
                      // $admission_status = $row['admission_status'];


                      $new_enrollee = $row['new_enrollee'];
                      $enrollment_student_status = $row['enrollment_student_status'];
                      $enrollment_is_new_enrollee = $row['enrollment_is_new_enrollee'];
                      $enrollment_is_transferee = $row['enrollment_is_transferee'];


                      $new_enrollee = $row['new_enrollee'];
                      $is_tertiary = $row['is_tertiary'];
                      // $is_transferee = $row['is_transferee'];


                      $process_url = "";
                      $waiting_payment_url = "subject_insertion_summary.php?id=$enrollement_student_id&enrolled_subject=show";

                      $student_status_pending = "";

                      $updated_type = "";
                      $button_url = "";
                      $strand = "";


                      $section = new Section($con, $enrollment_course_id);

                      $sectionProgramId = $section->GetSectionProgramId($enrollment_course_id);
                      $sectionAcronym = $section->GetAcronymByProgramId($sectionProgramId);

                      if($new_enrollee == 0
                          && $enrollment_is_new_enrollee == 0 
                          && $enrollment_is_transferee == 0
                          && $student_statusv2 == "Irregular"
                          && ($enrollment_student_status == "" || $enrollment_student_status == "Irregular")
                          ){

                          $updated_type = "Old Irregular";

                          $button_url = "
                              <button class='default success'
                                  onclick=\"window.location.href = '" . $waiting_payment_url . "subject_insertion_summary.php?id=560&enrolled_subject=show'\">
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
                              <button class='default success'
                                onclick=\"window.location.href = '" . $waiting_payment_url . "subject_insertion_summary.php?id=560&enrolled_subject=show'\">
                                  Evaluate
                              </button>
                          ";
                      }

                      else if($new_enrollee == 1
                        && $enrollment_is_new_enrollee == 1 
                        && $enrollment_is_transferee == 0
                        // && $student_statusv2 == ""
                        && $enrollment_student_status == "Regular"
                        ){

                        $updated_type = "New Regular";

                        $button_url = "
                            <button class='default clean'
                              onclick=\"window.location.href = '" . $waiting_payment_url . "subject_insertion_summary.php?id=560&enrolled_subject=show'\">
                                Evaluate
                            </button>
                        ";
                      }
                      // 
                      else if($new_enrollee == 1
                        && $enrollment_is_new_enrollee == 1 
                        && $enrollment_is_transferee == 1
                        // && $student_statusv2 == ""
                        && ($enrollment_student_status == "Irregular" || $enrollment_student_status == "Regular")
                        ){

                        $updated_type = "New Transferee";

                        $button_url = "
                            <button class='default clean'
                              onclick=\"window.location.href = '" . $waiting_payment_url . "subject_insertion_summary.php?id=560&enrolled_subject=show'\">
                                Evaluate
                            </button>
                        ";
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


                      echo "
                          <tr class='text-center'>
                              <td>$fullname</td>
                              <td>$student_unique_id</td>
                              <td>$updated_type</td>
                              <td>$sectionAcronym</td>
                              <td>$registrar_confirmation_date </td>
                              <td>
                                  $button_url
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
