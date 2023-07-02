
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
    $_SESSION['department_type'] = "Tertiary";

?>

<!-- Your STYLE GOES HERE -->

<div class="row col-md-12">
    <div class="content_subject">
        <div class="dashboard">

            <h5>Department</h3>

            <div class="form-box">
                <div class="button-box">
                    <div id="btn"></div>

                    <a href="shs_index.php">
                        <button type="button" class="btn-inactive toggle-btn" >
                            SHS
                        </button>
                    </a>

                    <a href="tertiary_index.php">
                        <button type="button" class="btn-active toggle-btn">
                            Tertiary
                        </button>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h3>Menu</h3>
        <div class="container-subjects">
            <div class="subject_container">
                <p>View Template Subjects</p>

                <a style="all: initial;" href="template_list.php">
                    <i class="bi bi-arrow-right-circle"></i>
                </a>

            </div>
            <div class="subject_container">
                <p>View Courses</p>

                <p> <?php echo $_SESSION['department_type']; ?></p>

                <a style="all: initial;" href="strand.php">
                    <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>

</div>





