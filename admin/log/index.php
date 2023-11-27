

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');

    require_once __DIR__ . '../../../vendor/autoload.php';

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
    $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
    $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');
  
    
    ?>

        <head>

            <style>
                .show_search{
                    position: relative;
                    /* margin-top: -38px;
                    margin-left: 215px; */
                }
                div.dataTables_length {
                    display: none;
                }

                #enrolled_students_table_filter{
                margin-top: 12px;
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: start;
                }

                #enrolled_students_table_filter input{
                width: 250px;
                }
            </style>

            <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        </head>

    <?php
?>
  

<div class="content">
    <main>

        <div class="floating" id="shs-sy">

            <header>
                <div class="title">
                    <h3 style="font-weight: bold;">Users Logs
                         
                    </h3>
                </div>
            </header>

            <main>

                <table id="user_logs_table" class="a" style="margin: 0">

                    <thead>
                        <tr class="text-center"> 
                            <th>Role</th>  
                            <th>Description</th>  
                        </tr>	
                    </thead>

                    <tbody>

                        <?php 

                            // $query = $con->prepare("SELECT t1.* 
                            
                            //     FROM users_log AS t1

                            //     ORDER BY t1.users_log_id DESC
                            // ");

                            // $query->execute();

                            // if($query->rowCount() > 0){
                                
                            //     $enrollment = new Enrollment($con);

                            //     while($row = $query->fetch(PDO::FETCH_ASSOC)){

                            //         $role = $row['role'];
                            //         $users_log_id = $row['users_log_id'];
                            //         $description = $row['description'];

                                  

                            //         echo "
                            //             <tr>
                            //                 <td>$role</td>
                            //                 <td>$description</td>
                            //             </tr>
                            //         ";
                            //     }
                            // }

                        ?>
                    </tbody>
                </table>

            </main>
        </div>
    </main>
</div>



<script>


    $(document).ready(function() {

        var table = $('#user_logs_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': 'userLogsDataList.php',
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },
            'pageLength': 15,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available user logs data."
            },
            'columns': [
                { data: 'role', orderable: false },  
                { data: 'description', orderable: false }
            ],
            'ordering': true
        });
    });
</script>