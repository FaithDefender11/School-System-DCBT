<?php
    require_once('../../includes/config.php');

    require_once('../../includes/classes/User.php');
    require_once('../../includes/classes/Teacher.php');
    require_once('../../includes/classes/Helper.php');
    require_once('../../includes/classes/Constants.php');
    require_once('../../includes/classes/Alert.php');
    require_once('../../includes/navigation/TeacherElmsNavigationProvider.php');

    $teacherLoggedIn = isset($_SESSION["teacherLoggedIn"]) 
        ? $_SESSION["teacherLoggedIn"] : "";

    $teacherLoggedInId = isset($_SESSION["teacherLoggedInId"]) 
        ? $_SESSION["teacherLoggedInId"] : "";

    $teacherLoggedInObj = new Teacher($con, $teacherLoggedInId);

    if ((!isset($_SESSION['teacherLoggedIn']) 
        || $_SESSION['teacherLoggedIn'] == '')
        
        && (!isset($_SESSION['teacherLoggedInId']) 
        || $_SESSION['teacherLoggedInId'] == '')
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
        <title>ELMS - Daehan College of Business and Technology</title>
        <!--Link JavaScript-->
        <script src="../../assets/js/elms-sidebar.js" defer></script>
        <script src="../../assets/js/elms-dropdown.js" defer></script>
        <script src="../../assets/js/table-dropdown.js" defer></script>
        <script src="../../assets/js/calendar.js" defer></script>
        <script src="../../assets/js/dropdownMenu.js" defer></script>
        <!-- Popper.js and Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
        <!-- Modify the Logo of DCBT Here and Please apply some styling -->
        <link rel="icon" href="../../assets/images/icons/DCBT-Logo.jpg" type="image/png">
        <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- SUMMER NOTE SCRIPT -->
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
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
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        <style>
        body {
            background-color: white;
            margin: 0;
        }
        </style>
    </head>
    <body>
        <div class="sidebar-nav">
            <?php
                $nav = new TeacherElmsNavigationProvider($con, $teacherLoggedInObj);

                if(isset($_SESSION['role']) 
                    && $_SESSION['role'] == "teacher"  ){
                        // echo "qwe";
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