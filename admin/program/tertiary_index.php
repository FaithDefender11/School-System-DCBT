<?php 

    include_once('../../includes/admin_header.php');
    ?>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../subject/subject.css">
        </head>
    <?php

    if(isset($_SESSION['department_type_program'])){
        unset($_SESSION['department_type_program']);
    }

    $_SESSION['department_type_program'] = "Tertiary";

    // echo $_SESSION['department_type_program'];


?>


<div class="col-md-12 row">
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
    <div class="content_subject">
        <header>
            <div class="title">
                <h3>Tertiary Programs</h3>
            </div>
            <div class="action">
                <a href="create.php">
                    <button type="button" class="btn btn-success">+ Add new</button>
                </a>
            </div>
        </header>
        
        <main>
            <table id="tertiary_program_table"
                class="ws-table-all cw3-striped cw3-bordered"
                style="margin: 0"
            >
                <thead>
                <tr>
                    <th>Program ID</th>
                    <th>Program Name</th>
                    <th>Track</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                
                    $department_name = "Senior High School";

                    $query = $con->prepare("SELECT t1.*, t2.department_name FROM program as t1
                        INNER JOIN department as t2 ON t2.department_id = t1.department_id
                        WHERE department_name = :department_name
                    ");

                    $query->bindParam(":department_name", $_SESSION['department_type']);
                    $query->execute();

                    if($query->rowCount() > 0){

                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                        $program_id = $row['program_id'];
                        $program_name = $row['program_name'];
                        $department_name = $row['department_name'];
                        $track = $row['track'];

                        $removeProgramBtn = "removeProgramBtn($program_id)";

                        echo "
                        <tr>
                            <td>$program_id</td>
                            <td>$program_name</td>
                            <td>$track</td>
                            <td>
                                <a href='edit.php?id=$program_id'>
                                    <button class='btn btn-primary'>
                                        <i class='fas fa-pen'></i>
                                    </button>
                                </a>
                                <button onclick='$removeProgramBtn' class='btn btn-danger'>
                                    <i class='fas fa-trash'></i>
                                </button>
                            </td>
                        </tr>
                        ";
                    }
                    }

                ?>
                </tbody>
            </table>
        </main>
    </div>
</div>

<script>
    function removeProgramBtn(program_id){
        Swal.fire({

                icon: 'question',
                title: `I agreed to removed Program ID: ${program_id}`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/program/remove_program.php",
                        type: 'POST',
                        data: {
                            program_id
                        },
                        success: function(response) {
                            response = response.trim();

                            // console.log(response);
                            if(response == "success_delete"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Removed`,
                                showConfirmButton: false,
                                timer: 1000, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
                                toast: true,
                                position: 'top-end',
                                showClass: {
                                popup: 'swal2-noanimation',
                                backdrop: 'swal2-noanimation'
                                },
                                hideClass: {
                                popup: '',
                                backdrop: ''
                                }
                            }).then((result) => {

                                $('#tertiary_program_table').load(
                                    location.href + ' #tertiary_program_table'
                                );
                            });
                            }
                        },
                        error: function(xhr, status, error) {
                            // handle any errors here
                        }
                    });
                } else {
                    // User clicked "No," perform alternative action or do nothing
                }
        });
    }
</script>