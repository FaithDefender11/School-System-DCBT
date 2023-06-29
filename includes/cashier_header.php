<?php

    require_once('../../includes/config.php');
    require_once('../../includes/navigation/CashierNavigationMenuProvider.php');
    require_once('../../includes/classes/User.php');
    require_once('../../includes/classes/Helper.php');
    require_once('../../includes/classes/Constants.php');
    require_once('../../includes/classes/Alert.php');

    $cashierLoggedIn = isset($_SESSION["cashierLoggedIn"]) 
        ? $_SESSION["cashierLoggedIn"] : "";
    
    $cashierLoggedInObj = new User($con, $cashierLoggedIn);

    if (!isset($_SESSION['cashierLoggedIn']) || $_SESSION['cashierLoggedIn'] == '') {
        header("Location: /school-system-dcbt/enrollment_login.php");
        exit();
    }


    $page = Helper::GetUrlPath();
    $document_title = Helper::DocumentTitlePage($page);

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

        <link rel="stylesheet" type="text/css" href="../../assets/css/main_style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> 
        <title><?php echo "Cashier " . $document_title; ?></title>

        <!-- Mododify the Logo of DCBT Here and Please apply some styling. -->
        <link rel="icon" href="../../assets/images/icons/DCBT-Logo.jpg" type="image/png">

    </head>
<body>
    <div class="pageContainer">

        <div class="sidebar-nav">
            <div class="sidebar-profile">
                <h3><?php echo $cashierLoggedInObj->getFirstName(); ?> <?php echo $cashierLoggedInObj->getLastName(); ?> </h3>
                <p class="user_email"><?php echo $cashierLoggedInObj->getUsername(); ?></p>
                <p class="role_name">Cashier</p>
            </div>

            <!-- OOP APPROACH ( WE MUST FOLLOW THE INDUSTRY BEST PRACTICES )  -->
            <?php
                $nav = new CashierNavigationMenuProvider($con, $cashierLoggedInObj);
                echo $nav->create($page);
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

        