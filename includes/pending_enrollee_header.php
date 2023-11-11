<?php
    require_once('../../includes/config.php');
    require_once('../../includes/navigation/StudentNavigationMenuProvider.php');
    require_once('../../includes/navigation/PendingNavigationMenuProvider.php');
    require_once('../../includes/classes/User.php');
    require_once('../../includes/classes/Student.php');
    require_once('../../includes/classes/Pending.php');
    require_once('../../includes/classes/Helper.php');
    require_once('../../includes/classes/Constants.php');
    require_once('../../includes/classes/Alert.php');

    $enrolleeLoggedIn = isset($_SESSION["enrollee_id"]) 
        ? $_SESSION["enrollee_id"] : "";

    
    $enrolleeLoggedInObj = new Pending($con, $enrolleeLoggedIn);

    if (!isset($_SESSION['enrollee_id']) 
        || $_SESSION['enrollee_id'] == '') {

        header("Location: /school-system-dcbt/index.php");
        exit();
    }

    $page = Helper::GetUrlPath();
    $document_title = Helper::DocumentTitlePage($page);
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, inital-scale=1" />
        <title><?php echo "Enrollee " . $document_title; ?></title>
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.js"></script>
        <!-- Modify the Logo of DCBT Here and Please apply some styling -->
        <link rel="icon" href="../../assets/images/icons/DCBT-Logo.jpg" type="image/png">
        <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!--Link JavaScript-->
        <script src="../../assets/js/elms-sidebar.js" defer></script>
        <!--Link stylesheets-->
        <link rel="stylesheet" href="../../assets/css/fonts.css" />
        <link rel="stylesheet" href="../../assets/css/sidebar.css" />
        <link rel="stylesheet" href="../../assets/css/buttons.css" />
        <link rel="stylesheet" href="../../assets/css/content.css" />
        <link rel="stylesheet" href="../../assets/css/forms.css" />
        <link rel="stylesheet" href="../../assets/css/student-form-responsive.css" />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
        />
        <link
            rel="stylesheet"
            href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous"
        />
        <!--Link fonts-->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=IM+Fell+Double+Pica&display=swap"
            rel="stylesheet"
        />
        <link
            href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400&display=swap"
            rel="stylesheet"
        />
        <style>
        body {
            background-color: #efefef;
            margin: 0;
        }
        </style>
    </head>
    <body>
        <div class="sidebar-nav">
            <?php
                // var_dump($enrolleeLoggedIn);

                $pendingNav = new PendingStudentNavigationMenu($con, $enrolleeLoggedIn);

                // Pending Application Procedure
                if(isset($_SESSION['status']) 
                    && $_SESSION['status'] == "pending"){
                    echo $pendingNav->create($page);
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