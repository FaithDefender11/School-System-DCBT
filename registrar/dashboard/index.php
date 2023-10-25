<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Task.php');

 
    $processEnrolled = true;

    $originalValueIsFalse = false;
    
    ?>
        <script>

            let processEnrolledJs = `
                <?php echo $processEnrolled;?>
            `;

            processEnrolledJs = processEnrolledJs.trim();
            // console.log(processEnrolledJs);

            if(processEnrolledJs == true){
                <?php $originalValueIsFalse = true; ?>
            }

        </script>
    <?php

    // var_dump($originalValueIsFalse);
    
    // if($originalValueIsFalse == true){
    //     echo "nice";
    // }


?>


    <div class="col-md-12 row table-responsive">
        <h4 class="text-center">Registrar Dashboard</h4>
        <table class="table table-bordered ">
            <thead>
                <tr>
                <th>Header 1</th>
                <th>Header 2</th>
                <th>Header 3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>Data 1</td>
                <td>Data 2</td>
                <td>Data 3</td>
                </tr>
                <tr>
                <td>Data 4</td>
                <td>Data 5</td>
                <td>Data 6</td>
                </tr>
                <tr>
                <td>Data 7</td>
                <td>Data 8</td>
                <td>Data 9</td>
                </tr>
            </tbody>
        </table>
    </div>

<?php include_once('../../includes/footer.php') ?>
