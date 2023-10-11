
<?php 

    include_once('../../includes/admin_header.php');
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
    

    $department_type = "";
    $type = "";
    $back_url = "";

    if(isset($_SESSION['department_type'])){
        $department_type = $_SESSION['department_type'];

        if($department_type == "Senior High School"){
            $type = "shs";
            $back_url = "shs_index.php";

        }else if($department_type == "Tertiary"){
            $type = "tertiary";
            $back_url = "tertiary_index.php";
        }
    }

    $templateUrl = directoryPath . "create_template.php?type=$type";

?>

<div class="content">

    <main>

        <div class="floating" id="shs-sy">
            <div>
                <a href='shs_index.php'>
                    <button class="text-left btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </a>
            </div>
            <header>
                <div class="title">
                    <h4 style="font-weight: 400;"><?php echo $department_type;?> Template Subjects</h4>
                </div>

                <div class="action">
                    <a href="<?php echo $templateUrl;?>">
                        <button type="button" class="clean large success">+ Add new</button>
                    </a>
                </div>
            </header>
            <main>
                <table id="template_table" 
                    class="a" style="margin: 0"> 
                    <thead>
                        <tr class="text-center"> 
                            <th>Template Id</th>
                            <th>Strand</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Pre Requisite</th>
                            <th>Type</th>  
                            <th>Unit</th>
                            <th>Action</th>
                        </tr>	
                    </thead> 	
                    <tbody>

                        <?php 

                            // $query = $con->query("SELECT * FROM subject_template
                            //     -- WHERE course_level=0
                            //     ");

                            // if($department_type == "Senior High School"){
                            //     $query = $con->query("SELECT * FROM subject_template
                            //         WHERE program_type=0
                            //         ");
                            // }else if($department_type == "Tertiary"){
                            //     $query = $con->query("SELECT * FROM subject_template
                            //         WHERE program_type=1
                            //         ");
                            // }

                            // $query->execute();

                            // if($query->rowCount() > 0){
                            
                            //     while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    
                            //         $subject_template_id = $row['subject_template_id'];
                            //         $program_id = $row['program_id'];
                            //         $subject_type = $row['subject_type'];

                            //         $program = new Program($con, $program_id);

                            //         $program_name = $program->GetProgramSectionName();

                            //         $url = "template_edit.php?id=$subject_template_id";

                            //         if($program_name === "" && $subject_type == "Core"){
                            //             // Applicable to all strands. not specfically distributed.
                            //             $program_name = "Universal";
                            //         }

                            //         $removeTemplateBtn = "removeTemplateBtn($subject_template_id)";

                            //         echo "
                            //             <tr class='text-center'>
                            //                 <td>".$row['subject_template_id']."</td>
                            //                 <td>$program_name</td>
                            //                 <td>".$row['subject_code']."</td>
                            //                 <td>".$row['subject_title']."</td>
                            //                 <td>".$row['pre_requisite_title']."</td>
                            //                 <td>".$row['subject_type']."</td>
                            //                 <td>".$row['unit']."</td>
                            //                 <td>
                            //                     <a href='$url'>
                            //                         <button class='btn btn-sm btn-primary'>
                            //                             <i class='fas fa-edit'></i>
                            //                         </button> 
                            //                     </a>
                            //                     <button type='button' onclick='$removeTemplateBtn' 
                            //                         class='btn btn-sm btn-danger'>
                            //                         <i class='fas fa-trash'></i>
                            //                     </button> 
                            //                 </td>
                            //             </tr>
                            //         ";
                            //     }
                            // }
                        ?>
                    </tbody>
                </table>
            </main>
        </div>

        <br>
        <div class="floating" id="shs-sy">
            <div>
                <a href='shs_index.php'>
                    <button class="text-left btn btn-primary">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                </a>
            </div>
            <header>
                <div class="title">
                    <h4 style="font-weight: 400;"><?php echo $department_type;?> Template Subjects</h4>
                </div>

                <div class="action">
                    <a href="<?php echo $templateUrl;?>">
                        <button type="button" class="clean large success">+ Add new</button>
                    </a>
                </div>
            </header>
            <main>
                <table id="template_tablex" 
                    class="a" style="margin: 0"> 
                    <thead>
                        <tr class="text-center"> 
                            <th rowspan="2">Template Id</th>
                            <th rowspan="2">Strand</th>
                            <th rowspan="2">Code</th>
                            <th rowspan="2">Description</th>
                            <th rowspan="2">Pre Requisite</th>
                            <th rowspan="2">Type</th>  
                            <th rowspan="2">Unit</th>
                            <th rowspan="2">Action</th>
                        </tr>	
                    </thead> 	
                    <tbody>

                        <?php

                            $query = NULL;

                            if($department_type == "Senior High School"){

                                $query = $con->query("SELECT * FROM subject_template
                                    WHERE program_type = 0
                                ");

                            }else if($department_type == "Tertiary"){

                                $query = $con->query("SELECT * FROM subject_template
                                    WHERE program_type = 1
                                ");
                            }

                            $query->execute();

                            if($query->rowCount() > 0){
                            
                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                    
                                    $subject_template_id = $row['subject_template_id'];
                                    $program_id = $row['program_id'];
                                    $subject_type = $row['subject_type'];

                                    $program = new Program($con, $program_id);

                                    $program_name = $program->GetProgramSectionName();

                                    $url = "template_edit.php?id=$subject_template_id";

                                    if($program_name === "" && $subject_type == "Core"){
                                        // Applicable to all strands. not specfically distributed.
                                        $program_name = "Universal";
                                    }

                                    $removeTemplateBtn = "removeTemplateBtn($subject_template_id)";

                                    echo "
                                        <tr class='text-center'>
                                            <td>".$row['subject_template_id']."</td>
                                            <td>$program_name</td>
                                            <td>".$row['subject_code']."</td>
                                            <td>".$row['subject_title']."</td>
                                            <td>".$row['pre_requisite_title']."</td>
                                            <td>".$row['subject_type']."</td>
                                            <td>".$row['unit']."</td>
                                            <td>
                                                <a href='$url'>
                                                    <button class='btn btn-sm btn-primary'>
                                                        <i class='fas fa-edit'></i>
                                                    </button> 
                                                </a>
                                                <button type='button' onclick='$removeTemplateBtn' 
                                                    class='btn btn-sm btn-danger'>
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
    </main>
</div>

<script>


    $(document).ready(function() {

        var department_type = `
            <?php echo $department_type; ?>
        `;

        department_type = department_type.trim();

        console.log(department_type)

        var table = $('#template_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `templateListData.php?type=${department_type}`,

                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },
            'pageLength': 10,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available data."
            },
            'columns': [
                { data: 'subject_template_id', orderable: false },  
                { data: 'strand', orderable: false },  
                { data: 'code', orderable: false },  
                { data: 'description', orderable: false },  
                { data: 'pre_requisite', orderable: false },  
                { data: 'type', orderable: false },  
                { data: 'unit', orderable: false },  
                { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });
    });

    function removeTemplateBtn(subject_template_id){
        Swal.fire({

                icon: 'question',
                title: `I agreed to removed Template ID: ${subject_template_id}`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/subject/remove_subject_template.php",
                        type: 'POST',
                        data: {
                            subject_template_id
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

                                $('#template_table').load(
                                    location.href + ' #template_table'
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


