<?php

    require_once('../../includes/config.php');
    require_once('../../includes/navigation/StudentNavigationMenuProvider.php');
    require_once('../../includes/navigation/PendingNavigationMenuProvider.php');
    require_once('../../includes/classes/User.php');
    require_once('../../includes/classes/Student.php');
    require_once('../../includes/classes/Helper.php');
    require_once('../../includes/classes/Constants.php');
    require_once('../../includes/classes/Alert.php');

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

        header("Location: /school-system-dcbt/index.php");
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
       
        <div class="sidebar-nav" style="color: white;">
            <div class="sidebar-profile">
                <h3><?php echo $studentLoggedInObj->getFirstName(); ?> <?php echo $studentLoggedInObj->getLastName(); ?> </h3>
                <p class="user_email"><?php echo $studentLoggedInObj->getUsername(); ?></p>
                <p class="role_name">Student</p>
            </div>

            <?php

                $nav = new StudentNavigationMenuProvider($con, $studentLoggedInObj);

                // $pendingNav = new PendingStudentNavigationMenu($con, $studentLoggedIn);
                // Pending Application Procedure
                // if(isset($_SESSION['status']) 
                //     && $_SESSION['status'] == "pending"){
                //     echo $pendingNav->create($page);
                // }

                // Ongoing Application Procedure
                if(isset($_SESSION['status']) 
                    && $_SESSION['status'] == "enrolled"
                    // && isset($_SESSION['applicaton_status'])
                    // && $_SESSION['applicaton_status'] == "ongoing"
                    ){
                        // echo "qwe";
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
    });
</script>
