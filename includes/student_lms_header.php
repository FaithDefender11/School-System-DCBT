<?php
     require_once('../../includes/config.php');

     require_once('../../includes/classes/User.php');
     require_once('../../includes/classes/Student.php');
     require_once('../../includes/classes/Helper.php');
     require_once('../../includes/classes/Constants.php');
     require_once('../../includes/classes/Alert.php');
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
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php echo "Student " . $document_title; ?></title>
        <!--Link JavaScript-->
        <script src="../../assets/js/elms-sidebar.js" defer></script>
        <script src="../../assets/js/elms-dropdown.js" defer></script>
        <script src="../../assets/js/table-dropdown.js" defer></script>
        <script src="../../assets/js/student-calendar.js" defer></script>
        <!--Link styleshets-->
        <link rel="stylesheet" href="../../assets/css/sidebar.css" />
        <link rel="stylesheet" href="../../assets/css/fonts.css" />
        <link rel="stylesheet" href="../../assets/css/content.css" />
        <link rel="stylesheet" href="../../assets/css/buttons.css" />
        <link rel="stylesheet" href="../../assets/css/table.css" />
        <link rel="stylesheet" href="../../assets/css/elms.css" />
        <link rel="stylesheet" href="../../assets/css/calendar.css" />
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
        <!-- Modify the Logo of DCBT Here and Please apply some styling -->
        <link rel="icon" href="../../assets/images/icons/DCBT-Logo.jpg" type="image/png">
        <!-- Bootstrap 4 JavaScript -->
        <script 
        src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" 
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" 
        crossorigin="anonymous"
        >
        </script>
        <!--Link for jQuery-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <!-- JS for full calender -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
        <!-- *Note: You must have internet connection on your laptop or pc other wise below code is not working -->
        <!-- CSS for full calender -->
        <link
          href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css"
          rel="stylesheet"
        />
        <style>
        body {
            background-color: white;
            margin: 0;
        }

        .fc-content {
            width: auto !important;
            white-space: normal !important;
            overflow: visible !important;
        }
        </style>
    </head>
    <body>
        <div class="sidebar-nav">
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
        <div class="content" id="elms-content">
            
    <script>
        $(document).ready(function() {
            $('.navigationItem').click(function() {
                $('.navigationItem').removeClass('active'); // Remove "active" class from all navigation items
                $(this).addClass('active'); // Add "active" class to the clicked navigation item
            });
        });
    </script>