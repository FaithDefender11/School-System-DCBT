
<?php 

    include_once('../../includes/admin_header.php');

    ?>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="./subject.css">
        </head>
    <?php

    if(isset($_SESSION['department_type'])){
        unset($_SESSION['department_type']);
    }
    $_SESSION['department_type'] = "Senior High School";

?>

 
<div class="content">

 
    <?php echo Helper::CreateTopDepartmentTab(false);?>

    <main>
        <div class="floating" id="tertiary-sy">
            <header>
                <div class="title">
                    <h4 style="font-weight: bold;">Menu</h4>
                </div>
            </header>

            <main>
                <div class="menu">
                <div class="item">
                    <span>View Template Subjects</span>
                    <span>
                        <a href="template_list.php">
                            <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </span>
                </div>
                <div class="item">
                    <span>View Strand</span>
                    <span>
                        <a  href="strand.php">
                            <i class="bi bi-arrow-right-circle"></i>
                        </a>
                    </span>
                </div>
                </div>
            </main>

        </div>
    </main>

    <!-- <div class="container">
        <h3>Menu</h3>
        <div class="container-subjects">

            <div class="subject_container">
                <p>View Template Subjects</p>

                <a style="all: initial;" href="template_list.php">
                    <i class="bi bi-arrow-right-circle"></i>
                </a>

            </div>

            <div class="subject_container">
                <p>View Strand</p>
                <p> <?php echo $_SESSION['department_type']; ?></p>
                <a style="all: initial;" href="strand.php">
                    <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div> -->

</div>




