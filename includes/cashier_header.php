<?php

    require_once('../../includes/config.php');
    require_once('../../includes/navigation/CashierNavigationMenuProvider.php');
    require_once('../../includes/classes/User.php');
    require_once('../../includes/classes/Helper.php');
    require_once('../../includes/classes/Constants.php');
    require_once('../../includes/classes/Alert.php');

    $cashierLoggedIn = isset($_SESSION["cashierLoggedIn"]) 
        ? $_SESSION["cashierLoggedIn"] : "";

    $cashierUserId = isset($_SESSION["cashierUserId"]) 
        ? $_SESSION["cashierUserId"] : "";
    
    $cashierLoggedInObj = new User($con, $cashierLoggedIn);

    if (!isset($_SESSION['cashierLoggedIn']) 
        || $_SESSION['cashierLoggedIn'] == ''
        
        || !isset($_SESSION['cashierUserId']) 
        || $_SESSION['cashierUserId'] == '') {
    
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

    // session_destroy();
    
    
?>

<!DOCTYPE html>

<html>
       <head>
        <title><?php echo "Cashier " . $document_title; ?></title>

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
        <link rel="icon" href="../../assets/images/icons/DCBT-Logo.png" type="image/png">

         <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        
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

        