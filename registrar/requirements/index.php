<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/StudentRequirement.php');
    include_once('../../includes/classes/Program.php');


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
        </style>
        <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    </head>


<div class="content">

    <main>

        <div class="floating">
            <header>
                <div class="title">
                <h3>Student Requirements for <span class="text-primary">New enrollees</span> | 
                    <a href="enrolled_students.php" class="text-muted">
                        Enrolled students
                    </a>
                </h3>
                </div>
            </header>


            <table id="" class="a" style="margin: 0">

                <thead>
                    <tr class="text-center"> 
                        <th>Enrollee Id</th>  
                        <th>Name</th>  
                        <th>Type</th>  
                        <th>Program</th>  
                        <th>Level</th>  
                        <th>Enrollee Status</th>  
                        <th>Admission Status</th>  
                        <th>Enrollment Status</th>  
                        <th>Action</th>  

                    </tr>	
                </thead>

                <tbody>
                    <?php 

                        $query = $con->prepare("SELECT t1.* 
                        
                            FROM pending_enrollees AS t1
                            
                            INNER JOIN student_requirement as t2 ON t2.pending_enrollees_id = t1.pending_enrollees_id

                            WHERE t1.is_enrolled = 0
                            AND (
                                t1.student_status IS NOT NULL OR 
                                t1.student_status != ''

                            )
                        ");

                        $query->execute();

                        if($query->rowCount() > 0){

                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                $pending_enrollees_id = $row['pending_enrollees_id'];

                                $enrolleeFullName = ucwords($row['firstname']) . " " . ucwords($row['lastname']);
                                $program_id = $row['program_id'];
                                $type = $row['type'];
                                $course_level = $row['course_level'];

                                $student_status =  ($row['student_status']);


                                $enrollment_status = $row['enrollment_status'];
                                $admission_status = $row['admission_status'];

                                $program = new Program($con, $program_id);

                                $programName = $program->GetProgramName();
                                $acronym = $program->GetProgramAcronym();

                                $url = "view_new_enrollee.php?id=$pending_enrollees_id";

                                echo "
                                    <tr>
                                        <td>$pending_enrollees_id</td>
                                        <td>$enrolleeFullName</td>
                                        <td>$type</td>
                                        <td>$acronym</td>
                                        <td>$course_level</td>
                                        <td>$student_status</td>
                                        <td>$enrollment_status</td>
                                        <td>$admission_status</td>
                                        <td>
                                            <a style='text-decoration: none; color:inherit; ' href='$url'>
                                                <button onclick='window.location.href = \"view_new_enrollee.php?id=$pending_enrollees_id\"' class='btn btn-primary btn-sm'>
                                                    <i class='fas fa-eye'></i>
                                                </button>
                                            </a>

                                        </td>
                                    </tr>
                                ";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <br>

        <div style="display: none;" class="floating">
            <header>
                <div class="title">
                <h3>Student Requirements Checklist</h3>
                </div>
            </header>

            <div class="filters">
                <!-- <table>
                    <tr>
                        <th rowspan="2" style="border-right: 2px solid black">
                        Search by
                        </th>
                        <th><button>ID number</button></th>
                        <th><button>Name</button></th>
                        <th><button>Status</button></th>
                        <th><button>Section</button></th>
                    </tr>
                </table> -->
            </div>

            <table id="requirement_table" class="a" style="margin: 0">
                <thead>
                    <tr class="text-center"> 
                        <th >Student Id</th>  
                        <th >Name</th>  
                        <th >Section</th>  
                        <th >Status</th>  
                        <th >Form 137</th>  
                        <th >Good Moral</th>  
                        <th >PSA</th>  
                        <th >Action</th>  
                    </tr>	
                </thead> 	
            </table>
        </div>
    </main>
</div>

<script>

    $(document).ready(function() {

        var table = $('#requirement_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `requirementListData.php`,
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
                'emptyTable': "No available data for enrolled students."
            },
            'columns': [
                { data: 'student_id', orderable: false },
                { data: 'name' , orderable: false },
                { data: 'program_section', orderable: false},
                { data: 'status', orderable: false},
                { data: 'form_137', orderable: false},
                { data: 'good_moral', orderable: false},
                { data: 'psa', orderable: false},
                { data: 'view_button', orderable: false}
            ],
            'ordering': true
        });
     
    });


</script>
<?php include_once('../../includes/footer.php') ?>



