<?php

    require_once('../../includes/config.php');

    require_once('../../includes/classes/User.php');
    require_once('../../includes/classes/Student.php');
    require_once('../../includes/classes/Helper.php');
    require_once('../../includes/classes/Constants.php');
    require_once('../../includes/classes/Alert.php');

    require_once('../../includes/classes/StudentSubject.php');
    require_once('../../includes/classes/Enrollment.php');
    require_once('../../includes/classes/SchoolYear.php');
    require_once('../../includes/classes/SubjectCodeAssignment.php');
    require_once('../../includes/classes/SubjectAssignmentSubmission.php');
    
    require_once('../../includes/navigation/StudentElmsNavigationProvider.php');

    $studentLoggedIn = isset($_SESSION["studentLoggedIn"]) 
        ? $_SESSION["studentLoggedIn"] : "";

    $studentLoggedInId = isset($_SESSION["studentLoggedInId"]) 
        ? $_SESSION["studentLoggedInId"] : "";

    
    $studentLoggedInObj = new Student($con, $studentLoggedInId);

    if ((!isset($_SESSION['studentLoggedIn']) 
        || $_SESSION['studentLoggedIn'] == '')
        
        && (!isset($_SESSION['studentLoggedInId']) 
        || $_SESSION['studentLoggedInId'] == '')
        ) {

        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            header("Location: /school-system-dcbt/enrollment_login.php");
            session_destroy();
            exit();
        }
        # If Online,
        header("Location: /enrollment_login.php");
        session_destroy();
        exit();

    }

    $page = Helper::GetUrlPath();
    $document_title = Helper::DocumentTitlePage($page);


?>

<!DOCTYPE html>

<html>
    <head>
        
        <title><?php echo "Student " . $document_title; ?></title>

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <!-- Bootstrap Icons CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Popper.js and Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="../../assets/css/main_style.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/content.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/forms.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/buttons.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/fonts.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/table.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/scheduler.css">
        <link rel="stylesheet" href="../../assets/css/others/toggle-switch.css">

        <!-- Google Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo">

        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

        <!-- Modify the Logo of DCBT Here and Please apply some styling -->
        <link rel="icon" href="../../assets/images/icons/DCBT-Logo.jpg" type="image/png">

         <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        
    </head>

<body>

    <div class="pageContainer">

        <?php 
        
            $enrollment = new Enrollment($con);

            $school_year = new SchoolYear($con);
            $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

            $school_year_id = $school_year_obj['school_year_id'];

            $enrollment_id = $enrollment->GetEnrollmentIdNonDependent($studentLoggedInId,
                $school_year_id);

            $studentSubject = new StudentSubject($con);

            $allEnrolledSubjectCode = $studentSubject->GetAllEnrolledSubjectCodeELMS
                ($studentLoggedInId, $school_year_id, $enrollment_id);

            $enrolledSubjectList = [];

            foreach ($allEnrolledSubjectCode as $key => $value) {
                # code...
                $subject_code = $value['student_subject_code'];
                array_push($enrolledSubjectList, $subject_code);
            }
        
            // print_r($enrolledSubjectList);
            // echo "<br>";

            $subjectCodeAssignment = new SubjectCodeAssignment($con);


            $getAllIncomingDueAssignmentsIds = $subjectCodeAssignment->GetAllIncomingDueAssignmentsIds(
                $enrolledSubjectList, $school_year_id, $studentLoggedInId
            );

            // echo "<br>";
            // echo "getAllIncomingDueAssignmentsIds: ";
            // var_dump($getAllIncomingDueAssignmentsIds);
            // echo "<br>";
            // echo "<br>";

            // $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/registrar/';
            $base_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';

            $dirname = dirname($_SERVER['PHP_SELF']);
            $after_registrar = substr($dirname, strpos($dirname, 'registrar/') + strlen('registrar/'));

            // var_dump($base_url);

            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'];
            $current_url = $protocol . '://' . $host . $_SERVER['REQUEST_URI'];

            // Remove the query parameters
            $parts = parse_url($current_url);
            $current_url_without_query = $parts['scheme'] . '://' . $parts['host'] . $parts['path'];
            
            $file_name = basename($current_url_without_query);


            $assignmentCount = count($getAllIncomingDueAssignmentsIds);
            // var_dump($assignmentCount);

            if($file_name === "task_submission.php" 
                // || $file_name === "subject_insertion_summary.php" 
                
            ){
                // echo "Im at the task_submission page";
            }
        
        ?>
       
        <div class="sidebar-nav" style="color: white;">

            <div class="sidebar-profile">
                <h3><?php echo $studentLoggedInObj->getFirstName(); ?> <?php echo $studentLoggedInObj->getLastName(); ?> </h3>
                <p class="user_email"><?php echo $studentLoggedInObj->getUsername(); ?></p>
                <p class="role_name">Student</p>
            </div>

            <?php

                $nav = new StudentElmsNavigationProvider($con, $studentLoggedInObj);
                
                // Ongoing Application Procedure
                if(isset($_SESSION['status']) 
                    && $_SESSION['status'] == "enrolled"
                    ){
                    echo $nav->create($page);
                }
            ?>
        </div>

        <div class="mainSectionContainer">
            <div class="mainContentContainer">

                


<script>
    $(document).ready(function() {
        $('.navigationItem').click(function() {
            $('.navigationItem').removeClass('active'); // Remove "active" class from all navigation items
            $(this).addClass('active'); // Add "active" class to the clicked navigation item
        });


        function checkForUpdates(lastCount, studentLoggedInId, enrollment_id) {

            $.ajax({

                url: '../../includes/due_date_updates.php', // PHP file to check updates
                type: 'GET',

                data: { 
                    last_count: lastCount,
                    studentLoggedInId,
                    enrollment_id
                }, // Send the client's last count

                success: function (data) {

                    data = data.trim();

                    console.log(data)
                    
                    // if (data == 'update_available') {
                    if (data == 'update_available' && window.location.href.indexOf('task_submission.php') === -1) {
                        // Reload the page if an update is available
                        location.reload(true);
                    }

                },
                complete: function () {
                    // Schedule the next check after a certain interval (e.g., every 5 seconds)
                    setTimeout(function() {
                        checkForUpdates(<?php echo $assignmentCount; ?>, <?php echo $studentLoggedInId; ?>, <?php echo $enrollment_id; ?>); // Corrected PHP echo
                    }, 10000);
                    // }, 3000);
                }
            });
        }

        // Initial check when the page loads
        checkForUpdates(<?php echo $assignmentCount; ?>, <?php echo $studentLoggedInId; ?>, <?php echo $enrollment_id; ?>);

    });
</script>
