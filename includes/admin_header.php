
<?php

    require_once('../../includes/config.php');
    require_once('../../includes/navigation/AdminNavigationMenuProvider.php');
    require_once('../../includes/classes/User.php');

    $adminLoggedIn = isset($_SESSION["adminLoggedIn"]) 
        ? $_SESSION["adminLoggedIn"] : "";
    
    $adminLoggedInObj = new User($con, $adminLoggedIn);

    // session_destroy();
    if (!isset($_SESSION['adminLoggedIn']) || $_SESSION['adminLoggedIn'] == '') {
        header("Location: /school-system-dcbt/enrollment_login.php");
        exit();
    }

    $directoryURI = $_SERVER['REQUEST_URI'];
    $path = parse_url($directoryURI, PHP_URL_PATH);
    $components = explode('/', $path);

    // var_dump($components);
    $page = $components[3];
    // echo $page;
    
?>

<!DOCTYPE html>

<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />

        <link rel="stylesheet" type="text/css" href="../../assets/css/main_style.css" />
        <link rel="stylesheet" type="text/css" href="../../assets/css/content.css" />
        <link rel="stylesheet" type="text/css" href="../../assets/css/buttons.css" />
        <link rel="stylesheet" type="text/css" href="../../assets/css/fonts.css" />
        <link rel="stylesheet" type="text/css" href="../../assets/css/table.css" />
        <link rel="stylesheet" type="text/css" href="../../assets/css/scheduler.css" />
        <link rel="stylesheet" href="../../assets/css/others/toggle-switch.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo" />
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
     
    </head>
<body>

    <div class="pageContainer">
 
        <div class="sidebar-nav" style="color: white; display: block;">
            <div class="sidebar-profile">
                <h3><?php echo $adminLoggedInObj->getFirstName(); ?> <?php echo $adminLoggedInObj->getLastName(); ?> </h3>
                <p class="user_email"><?php echo $adminLoggedInObj->getUsername(); ?></p>
                <p class="role_name">Admin</p>
            </div>

            <!-- OOP APPROACH ( WE MUST FOLLOW THE INDUSTRY BEST PRACTICES )  -->
            <?php
                // echo $page;
                $nav = new AdminNavigationMenuProvider($con, $adminLoggedInObj);
                echo $nav->create($page);

            ?>

            <!-- BAD PRACTICES. DISPLAY AS NONE-->

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



<script>
//   function toggleActive(event) {
//     event.preventDefault(); // Prevent the default behavior of the anchor tag

//     // Remove "active" class from all navigation items
//     var navigationItems = document.querySelectorAll('.navigationItem');
//     navigationItems.forEach(function(item) {
//       item.classList.remove('active');
//     });

//     // Add "active" class to the clicked navigation item
//     var clickedItem = event.target.closest('.navigationItem');
//     clickedItem.classList.add('active');
//   }

$(document).ready(function() {
  $('.navigationItem').click(function() {
    $('.navigationItem').removeClass('active'); // Remove "active" class from all navigation items
    $(this).addClass('active'); // Add "active" class to the clicked navigation item
  });
});

</script>