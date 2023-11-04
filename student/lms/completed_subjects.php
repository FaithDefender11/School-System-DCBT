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
      <?php
        echo Helper::lmsStudentNotificationHeader(
          $con, $studentLoggedInId,
          $school_year_id, $enrolledSubjectList,
          $enrollment_id,
          "second",
          "first",
          "second"
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
          <div class="floating noOutline">
            <a href="<?php echo $course_url ?>">
              <header>
                <div class="title">
                  <h3><?= $subject_title; ?> <em>SY2324-1T</em></h3>
                  <small><?= $instructor_name; ?></small>
                  <small style="color: orange">Archived</small>
                </div>
              </header>
            </a>
            <main>
              <div class="progress" style="height: 20px">
                <div class="progress-bar" style="width: 100%">100%</div>
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
        <?php endif; ?>
      </main>
    </div>
  </body>
</html>