 <?php 

    include_once('../../includes/student_lms_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Announcement.php');
    include_once('../../includes/classes/Notification.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');

    // echo Helper::RemoveSidebar();
  
    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

    $subjectPeriodCodeTopic = new SubjectPeriodCodeTopic($con);
  
    $studentSubject = new StudentSubject($con);

    # List of all enrolled previous Enrolled Subjects
    $getPreviousEnrolledSubjects = $studentSubject
      ->GetAllPassedPreviousEnrolledSubjects($studentLoggedInId, $school_year_id);

    // var_dump($getPreviousEnrolledSubjects);


    // $enrollment_id = $studentSubject->GetEnrollmentId();

    $enrollment = new Enrollment($con);

    $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($studentLoggedInId,
      $school_year_id);

    // var_dump($enrollment_id);

    $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
      ($studentLoggedInId, $school_year_id, $enrollment_id);
    
    $enrolledSubjectList = [];

    foreach ($allEnrolledSubjectCode as $key => $value) {
        # code...
        $subject_codeGet = $value['student_subject_code'];
        array_push($enrolledSubjectList, $subject_codeGet);
    }

    $logout_url = 'http://localhost/sistem/lms_logout.php';

    if ($_SERVER['SERVER_NAME'] === 'localhost') {

        $base_url = 'http://localhost/sistem/student/';
    } else {
        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/student/';
    }

    if ($_SERVER['SERVER_NAME'] !== 'localhost') {

      $new_url = str_replace("/student/", "", $base_url);
      $logout_url = "$new_url/lms_logout.php";

    }

?>
  <head>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ELMS - Daehan College of Business and Technology</title>

    <!--Link JavaScript-->
    <script src="../../assets/js/elms-sidebar.js" defer></script>
    <script src="../../assets/js/elms-dropdown.js" defer></script>
    <script src="../../assets/js/table-dropdown.js" defer></script>
    <!--Link styleshets-->
    <link rel="stylesheet" href="../../assets/css/fonts.css" />
    <link rel="stylesheet" href="../../assets/css/content.css" />
    <link rel="stylesheet" href="../../assets/css/buttons.css" />
    <link rel="stylesheet" href="../../assets/css/table.css" />
    <!--Custom CSS-->
    <link
      rel="stylesheet"
      href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
      integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    />
    <!--Link Fonts-->
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Lato"
    />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Arimo"
    />

    <style>
      body {
        background-color: white;
        margin: 0;
      }
    </style>

  </head>

  <body>


    <div class="content">


      <?php 
        echo Helper::lmsStudentNotificationHeader(
          $con, $studentLoggedInId,
          $school_year_id,
          $enrolledSubjectList,
          $enrollment_id,

          "second",
          "second",
          "second",
          $logout_url,
          "first"
        );
      ?>
 

      <div class="content-header">

        <header>
          <div class="title">
            <h1>Dashboard</h1>
          </div>
        </header>

      </div>

      <div class="tabs">
        <button
          class="tab"
          style="background-color: var(--theme); color: white"

          onclick="window.location.href='student_dashboard.php'"
        >
          Enrolled (<?= count($allEnrolledSubjectCode)?>)
        </button>
        <button
          class="tab"
            onclick="window.location.href='completed_subjects.php'"
        >
          Completed (<?= count($getPreviousEnrolledSubjects)?>)
        </button>
      </div>

      <main>

          <?php if(count($getPreviousEnrolledSubjects) > 0): ?>

              
              <?php 
                // var_dump($getPreviousEnrolledSubjects);
              
                  $now = date("Y-m-d H:i:s");
                  $totalOver = 0;
                  $totalScore = 0;
                  
                  $final_equivalent = 0;
                  $final_total_over = 0;
                  $equivalent = "";

                  $db_subject_code_assignment_id = NULL;

                  foreach ($getPreviousEnrolledSubjects as $key => $row_inner) {

                      $subject_title = $row_inner['subject_title'];
                      $student_subject_id = $row_inner['student_subject_id'];
                      $student_subject_code = $row_inner['student_subject_code'];

                      $teacher_firstname = $row_inner['firstname'];
                      $teacher_lastname = $row_inner['lastname'];

                      $school_year_id = $row_inner['school_year_id'];
                      $school_year_idx = $row_inner['school_year_id'];

                      $sy = new SchoolYear($con, $school_year_id);

                      $term = $sy->GetTerm();
                      $period = $sy->GetPeriod();

                      $fomatTerm = $enrollment->changeYearFormat($term);
                      $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");
                      
                      $instructor_name = "TBA";

                      if($teacher_firstname != null){
                          $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                      }

                      $courses_url = "";

                      if($instructor_name != "TBA"){
                        $courses_url = "../courses/subject_module.php?id=$student_subject_id";
                      }

                      $subjectCodeAssignment = new SubjectCodeAssignment($con, $student_subject_id);

                      $allGivenAssignments = $subjectCodeAssignment->GetSubjectCodeAssignments(
                        $student_subject_code, $school_year_id);

                      // var_dump($allGivenAssignments);

                      $equivalent = NULL;

                      

                      if(count($allGivenAssignments) > 0){

                        foreach ($allGivenAssignments as $key => $row) {
                          
                          $subject_code_assignment_id = $row['subject_code_assignment_id'];
                          $assignment_name = $row['assignment_name'];

                          $max_score = $row['max_score'];

                          $date_creation = $row['date_creation'];
                          $start_date = date("M d, h:i a", strtotime($date_creation));
                          
                          $due_date_db = $row['due_date'];
                          
                          $due_date = date("M d, h:i a", strtotime($due_date_db));

                          $submitted_status = "";


                          $submitted_grade_status = "";

                          $score = 0;

                          $total = "";

                          $subjectAssignmentSubmission = new SubjectAssignmentSubmission($con);

                          $statusSubmission = $subjectAssignmentSubmission
                              ->CheckStatusSubmission(
                              $subject_code_assignment_id,
                              $studentLoggedInId, $school_year_id);
                          
                          // var_dump($statusSubmission);

                          // echo "max_score: $max_score";
                          echo "<br>";

                          if($statusSubmission !== NULL){

                              // echo "NOT NULL";
                              // echo "<br>";

                              $db_subject_code_assignment_id =  $statusSubmission['subject_code_assignment_id'];

                              $submitted_grade =  $statusSubmission['subject_grade'];
                              // $graded_over_score =  $statusSubmission['max_score'];
                              $date_graded =  $statusSubmission['date_graded'];


                              $score = $submitted_grade;

                              // echo "score: $score";
                              // echo "<br>";

                              if($submitted_grade != NULL){
                                  
                                $submitted_grade_status = "
                                    <i style='color: green;' class='fas fa-check'></i>
                                ";

                                $totalScore += $submitted_grade;
                                $totalOver += $max_score;
                                
                                $final_total_over = $totalOver;

                                $pecentage_equivalent = ($submitted_grade / $max_score) * 100;


                                $equivalent = round($pecentage_equivalent, 0, PHP_ROUND_HALF_UP);
                                $final_equivalent += $equivalent;

                                $equivalent = $equivalent . "%";

                                // echo "equivalent: $equivalent";
                                // echo "<br>";
                                  
                              }

                              if($submitted_grade == NULL && $date_graded == NULL){
                                  $submitted_grade_status = "
                                      <i style='color: orange;' class='fas fa-times'></i>
                                  ";
                                  $score = "??";
                              }

                              $submitted_status = "
                                  <i style='color: green;' class='fas fa-check'></i>
                              ";

                          }else if($statusSubmission == NULL){

                              $submitted_status = "
                                  <i style='color: red;' class='fas fa-times'></i>
                              ";
                              $submitted_grade_status = "
                                      <i style='color: red;' class='fas fa-times'></i>
                              ";

                              $nowTimestamp = strtotime($now);

                              if(strtotime($due_date_db) <=  $nowTimestamp){
                                  $submitted_status = "
                                      <i style='color: orange;' class='fas fa-flag'></i>
                                  ";

                                  $submitted_grade_status = "
                                      <i style='color: orange;' class='fas fa-flag'></i>
                                  ";
                                  $equivalent = "";

                                  $totalOver += $max_score;
                                  // $totalScore += $max_score;

                              }else if(strtotime($due_date_db) >  $nowTimestamp){
                                $submitted_status = "-";
                                $score = "?";
                                $submitted_grade_status = "";
                                $equivalent = "";
                              }
                          }

                        }

                      }

                      // echo "allGivenAssignments: "; var_dump(count($allGivenAssignments));
                      // echo "<br>";
                      
                      ?>
                        <div style="width: 100%" class="floating noOutline">

                            <?php 

                              // echo "subject_code_assignment_id: $subject_code_assignment_id";
                              // echo "<br>";

                              $ff_equivalent = 0;

                              if($final_total_over > 0){


                                $final_pecentage_equivalent = ($final_equivalent / $final_total_over) * 100;

                                $ff_equivalent = round($final_pecentage_equivalent, 0, PHP_ROUND_HALF_UP);

                                // var_dump($final_pecentage_equivalent);
                                // echo "<br>";

                                $ff_equivalent = $ff_equivalent . "%";
                              
                                // echo "final_equivalent: $final_equivalent";
                                // echo "<br>";
                                // echo "final_total_over: $final_total_over";
                                // echo "<br>";

                                // echo "final_pecentage_equivalent: $ff_equivalent";
                                // echo "<br>";

                              }


                            ?>
                            
                            <a href="<?php echo $courses_url; ?>">
                                <header>
                                    <div class="title">
                                        <h3><?= $subject_title; ?> <em><?= "SY$fomatTerm-$period_short";?></em> &nbsp; &nbsp; <em class="text-primary"><span> </span><?= $equivalent != NULL ? "<a href='../courses/grade_progress.php?id=$student_subject_id'><span class='text-info' style='font-size: 20px'>Grade $ff_equivalent</span></a>" : "";?></em></h3>
                                        <small><?= $instructor_name?></small>
                                        <br>
                                        <small style="color: orange;">Archived</small>
                                    </div>
                                </header>
                            </a>

                            <main>

                                <div class="progress" style="height: 20px">
                                    <div class="progress-bar" style="width: 25%">25%</div>
                                </div>

                                <div class="action">
                                    <button
                                    class="task"
                                    data-toggle="tooltip"
                                    data-placement="bottom"
                                    title="No Assignments Due"
                                    >
                                    <i class="bi bi-file-earmark">0</i>
                                    </button>
                                </div>
                            </main>

                        </div>
                        <?php

                  }

              ?>

            <?php else:?>
                <div class="col-md-12">
                    <br>
                    <br>
                    <br>
                    <h2 class="text-center text-primary">No completed subjects</h2>
                </div>
          <?php endif;?>

      </main>
    </div>


  </body>
 