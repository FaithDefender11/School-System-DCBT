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
                <h3>Student Requirements for <span class="text-primary">Enrolled students</span> | 
                    <a href="index.php" class="text-muted">
                        New enrollees
                    </a>
                </h3>
                </div>
            </header>


            <table id="" class="a" style="margin: 0">

                <thead>
                    <tr class="text-center"> 
                        <th>Student Id</th>  
                        <th>Name</th>  
                        <th>Type</th>  
                        <th>Section</th>  
                        <th>Level</th>  
                        <th>Enrollment Status</th>  
                        <th>Student Status</th>  
                        <th>Action</th>  

                    </tr>	
                </thead>

                <tbody>
                    <?php 

                        $query = $con->prepare("SELECT 
                        
                            t1.*, t3.program_section, t3.course_level as section_level
                        
                            FROM student AS t1
                            
                            INNER JOIN student_requirement as t2 ON t2.student_id = t1.student_id

                            LEFT JOIN course as t3 ON t3.course_id = t1.course_id

                            WHERE t1.student_unique_id IS NOT NULL
                        ");

                        $query->execute();

                        if($query->rowCount() > 0){

                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                $student_id = $row['student_id'];
                                $student_unique_id = $row['student_unique_id'];
                                $studentFullName = ucwords($row['firstname']) . " " . ucwords($row['lastname']);
                                // $program_id = $row['program_id'];

                                $type = $row['is_tertiary'] == 1 ? "Tertiary" : "SHS";
                                $course_level = $row['course_level'];
                                $program_section = $row['program_section'];
                                $section_level = $row['section_level'];

                                $student_statusv2 = $row['student_statusv2'];
                                $admission_status = $row['admission_status'];

                                // $student_status =  ($row['student_status']);


                                // $enrollment_status = $row['enrollment_status'];
                                // $admission_status = $row['admission_status'];

                                // $program = new Program($con, $program_id);

                                // $programName = $program->GetProgramName();
                                // $acronym = $program->GetProgramAcronym();

                                $url = "view_student.php?id=$student_id";

                                echo "
                                    <tr>
                                        <td>$student_unique_id</td>
                                        <td>$studentFullName</td>
                                        <td>$type</td>
                                        <td>$program_section</td>
                                        <td>$section_level</td>
                                        <td>$student_statusv2</td>
                                        <td>$admission_status</td>
                                        <td>
                                            <a style='text-decoration: none; color:inherit; ' href='$url'>
                                                <button onclick='window.location.href = \"view_student.php?id=$student_id\"' class='btn btn-primary btn-sm'>
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

    </main>
</div>

<script>

    // $(document).ready(function() {

    //     var table = $('#requirement_table').DataTable({
    //         'processing': true,
    //         'serverSide': true,
    //         'serverMethod': 'POST',
    //         'ajax': {
    //             'url': `requirementListData.php`,
    //             'error': function(xhr, status, error) {
    //                 // Handle error response here
    //                 console.error('Error:', error);
    //                 console.log('Status:', status);
    //                 console.log('Response Text:', xhr.responseText);
    //                 console.log('Response Code:', xhr.status);
    //             }
    //         },

    //         'pageLength': 15,
    //         'language': {
    //             'infoFiltered': '',
    //             'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
    //             'emptyTable': "No available data for enrolled students."
    //         },
    //         'columns': [
    //             { data: 'student_id', orderable: false },
    //             { data: 'name' , orderable: false },
    //             { data: 'program_section', orderable: false},
    //             { data: 'status', orderable: false},
    //             { data: 'form_137', orderable: false},
    //             { data: 'good_moral', orderable: false},
    //             { data: 'psa', orderable: false},
    //             { data: 'view_button', orderable: false}
    //         ],
    //         'ordering': true
    //     });
     
    // });
</script>

<?php include_once('../../includes/footer.php') ?>



