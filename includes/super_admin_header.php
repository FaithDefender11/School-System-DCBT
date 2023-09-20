
<?php

    require_once('../../includes/config.php');
    require_once('../../includes/navigation/SuperAdminNavigationMenuProvider.php');
    require_once('../../includes/classes/User.php');
    require_once('../../includes/classes/Helper.php');
    require_once('../../includes/classes/Constants.php');
    require_once('../../includes/classes/Alert.php');
    require_once('../../includes/classes/Account.php');

    $superAdminLoggedIn = isset($_SESSION["superAdminLoggedIn"]) 
        ? $_SESSION["superAdminLoggedIn"] : "";

    $superAdminUserId = isset($_SESSION["superAdminUserId"]) 
        ? $_SESSION["superAdminUserId"] : "";

    $superAdminLoggedInObj = new User($con, $superAdminLoggedIn);

    if (!isset($_SESSION['superAdminLoggedIn']) 
        ||  $_SESSION['superAdminLoggedIn'] == ''

        || !isset($_SESSION['superAdminUserId']) 
        || $_SESSION['superAdminUserId'] == '') {

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
        <title><?php echo "Super Administrator " . $document_title; ?></title>

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


           <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>

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
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo" />

        <!-- jQuery (additional instance) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- Custom Logo -->
        <link rel="icon" href="../../assets/images/icons/DCBT-Logo.jpg" type="image/png">

        <!-- SweetAlert2 -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>

        <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </head>

<body>

    <div class="pageContainer">
        <div class="sidebar-nav" style="color: white; display: block;">
            <div class="sidebar-profile">
                <h3><?php echo $superAdminLoggedInObj->getFirstName(); ?> <?php echo $superAdminLoggedInObj->getLastName(); ?> </h3>
                <p class="user_email"><?php echo $superAdminLoggedInObj->getUsername(); ?></p>
                <p class="role_name">Super Admin</p>
            </div>
            <!-- OOP APPROACH ( WE MUST FOLLOW THE INDUSTRY BEST PRACTICES )  -->
            <?php
                $nav = new SuperAdminNavigationMenuProvider($con, $superAdminLoggedInObj);
                echo $nav->create($page);
            ?>
        </div>

       <div class="mainSectionContainer">
            <div class="mainContentContainer"> 
                <!-- CONTENT GOES HERE-->


<script>
    $(document).ready(function() {
        $('.navigationItem').click(function() {
            $('.navigationItem').removeClass('active'); // Remove "active" class from all navigation items
            $(this).addClass('active'); // Add "active" class to the clicked navigation item
        });
    });
</script>