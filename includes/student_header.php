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
    
    $studentLoggedInObj = new Student($con, $studentLoggedIn);

    if (!isset($_SESSION['studentLoggedIn']) 
            || $_SESSION['studentLoggedIn'] == '') {
        header("Location: /school-system-dcbt/enrollment_login.php");
        exit();
    }

    $page = Helper::GetUrlPath();
    $document_title = Helper::DocumentTitlePage($page);

?>

<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
        />

        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="../../assets/css/main_style.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/content.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/forms.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/buttons.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/fonts.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/table.css">
        <link rel="stylesheet" type="text/css" href="../../assets/css/scheduler.css">
        <link rel="stylesheet" href="../../assets/css/others/toggle-switch.css">

        <link
        href="https://fonts.googleapis.com/css2?family=IM+Fell+Double+Pica&display=swap"
        rel="stylesheet"
        />
        <link
        href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap"
        rel="stylesheet"
        />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>  -->
        <title><?php echo "Student " . $document_title; ?></title>

        <!-- Mododify the Logo of DCBT Here and Please apply some styling. -->
        <link rel="icon" href="../../assets/images/icons/DCBT-Logo.jpg" type="image/png">

        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

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

                $pendingNav = new PendingStudentNavigationMenu($con, $studentLoggedIn);

                // Pending Application Procedure
                if(isset($_SESSION['status']) 
                    && $_SESSION['status'] == "pending"){

                    echo $pendingNav->create($page);

                }
                // Ongoing Application Procedure
                else if(isset($_SESSION['status']) 
                    && $_SESSION['status'] == "enrolled"
                    && isset($_SESSION['applicaton_status'])
                    && $_SESSION['applicaton_status'] == "ongoing"
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
