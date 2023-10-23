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

    echo Helper::RemoveSidebar();
  
    
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
          $school_year_id, $enrolledSubjectList,
          $enrollment_id,
          "second",
          "first",
          "second
        ");
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
          Enrolled
        </button>
        <button
          class="tab"
            onclick="window.location.href='completed_subjects.php'"
        >
          Completed
        </button>
      </div>

      <main>

          <?php if(count($getPreviousEnrolledSubjects) > 0): ?>
              <?php 
              
                  foreach ($getPreviousEnrolledSubjects as $key => $row_inner) {

                      $subject_title = $row_inner['subject_title'];
                      $student_subject_id = $row_inner['student_subject_id'];

                      $teacher_firstname = $row_inner['firstname'];
                      $teacher_lastname = $row_inner['lastname'];

                      $instructor_name = "TBA";

                      if($teacher_firstname != null){
                          $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                      }

                      $courses_url = "../courses/subject_module.php?id=$student_subject_id";

                      ?>

                          <div style="width: 100%" class="floating noOutline">
                              
                              <a href="<?php echo $courses_url; ?>">
                                  <header>
                                      <div class="title">
                                          <h3><?= $subject_title; ?> <em>SY2324-1T</em> &nbsp; &nbsp; <em class="text-primary">70%</em></h3>
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
          <?php endif;?>

      </main>
    </div>
  </body>
 