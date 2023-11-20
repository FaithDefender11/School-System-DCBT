 
<?php 

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/Student.php');

    // echo $_SESSION['adminUserId'];

    
    $school_year = new SchoolYear($con);

    $subjectProgram = new SubjectProgram($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();


    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $enrollment = new Enrollment($con);
    $teacher = new Teacher($con);
    $student = new Student($con);


    $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");
    $fomatTerm = $enrollment->changeYearFormat($current_school_year_term);
    $format = $fomatTerm . "-" .  $period_short;


    $getAllSubjects = $subjectProgram->GetAllSubjectCount();

    $getActiveAllTeachers = $teacher->GetAllTeachersCount("Active");
    $getInactiveAllTeachers = $teacher->GetAllTeachersCount("Inactive");

    $getActiveAllStudents = $student->GetAllStudentCount(1);
    $getInActiveAllStudents = $student->GetAllStudentCount(0);



?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="assets/css/content.css" />
    <link rel="stylesheet" href="assets/css/fonts.css" />
    <link rel="stylesheet" href="assets/css/buttons.css" />
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
    <!-- Font Awesome CSS -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
    />
    <style>
      body {
        background-color: white;
        margin: 0;
      }
    </style>
    <title>Document</title>
  </head>
  <body>
    <div class="content">
      <main>
        <div class="bars">
          <div class="floating">
            <header>
              <div class="title">
                <h3>Active Teachers</h3>
              </div>
            </header>
            <main>
              <h4 class="text-center"><?= $getActiveAllTeachers?></h4>
            </main>
          </div>
          <div class="floating">
            <header>
              <div class="title">
                <h3>In-active teachers</h3>
              </div>
            </header>
            <main>
              <h4 class="text-center"><?= $getInactiveAllTeachers?></h4>
            </main>
          </div>
        </div>

        <div class="bars">
          <div class="floating">
            <header>
              <div class="title">
                <h3>Active Students</h3>
              </div>
            </header>
            <main>
              <h4 class="text-center"><?= $getActiveAllStudents?></h4>
            </main>
          </div>
          <div class="floating">
            <header>
              <div class="title">
                <h3>In-active Students</h3>
              </div>
            </header>
            <main>
              <h4 class="text-center"><?= $getInActiveAllStudents?></h4>
            </main>
          </div>
        </div>

        <div class="bars">
          <div class="floating">
            <header>
              <div class="title">
                <h3>Subjects</h3>
              </div>
            </header>
            <main>
              <h4 class="text-center"><?= $getAllSubjects;?></h4>
            </main>
          </div>
          <div class="floating">
            <header>
              <div class="title">
                <h3>A.Y</h3>
              </div>
            </header>
            <main>
              <h4 class="text-center">SY<?= $format?></h4>
            </main>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
