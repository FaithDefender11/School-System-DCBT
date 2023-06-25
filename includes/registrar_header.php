<?php

    require_once('includes/config.php');
    require_once('includes/navigation/RegistrarNavigationMenuProvider.php');
    require_once('includes/classes/User.php');

    $registrarLoggedIn = isset($_SESSION["registrarLoggedIn"]) 
        ? $_SESSION["registrarLoggedIn"] : "";
    
    $registrarLoggedInObj = new User($con, $registrarLoggedIn);

    if (!isset($_SESSION['registrarLoggedIn']) || $_SESSION['registrarLoggedIn'] == '') {
        header("Location: /school-system-dcbt/enrollment_login.php");
        exit();
    }
    // session_destroy();

?>

<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
        />

        <link rel="stylesheet" type="text/css" href="assets/css/main_style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
    </head>
<body>
    <div class="pageContainer">
       
        <div class="sidebar-nav">
            <div class="sidebar-profile">
                <h3><?php echo $registrarLoggedInObj->getFirstName(); ?> <?php echo $registrarLoggedInObj->getLastName(); ?> </h3>
                <p class="user_email"><?php echo $registrarLoggedInObj->getUsername(); ?></p>
                <p class="role_name">Registrar</p>
            </div>

            <!-- OOP APPROACH ( WE MUST FOLLOW THE INDUSTRY BEST PRACTICES )  -->
            <?php
                $nav = new RegistrarNavigationMenuProvider($con, $registrarLoggedInObj);
                echo $nav->create();
            ?>

            <!-- BAD PRACTICES. -->
            <div style="display: none;" class='navigationItems'>

                <!-- ul & li represent as div (more concise) -->
                <div class='navigationItem'>
                    <a href='dashboard.php'>
                        <i style='color: white;' class='bi bi-clipboard-data icon'></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <div class='navigationItem'>
                    <a href='$link'>
                        <i style='color: white;' class='bi bi-calendar icon'></i>
                        <span>School Year</span>
                    </a>
                </div>

            </div>

        </div>

        <div class="mainSectionContainer">
            <div class="mainContentContainer">

        