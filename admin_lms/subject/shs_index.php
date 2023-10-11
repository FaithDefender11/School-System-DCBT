<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');


    
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

    <?php echo Helper::CreateTopDepartmentTab(false, "shs_index.php", "tertiary_index.php");?>


    <main>
        
        <!-- <div class="floating" id="shs-sy">
            <main>
                <table id="shs_program_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Program ID</th>
                            <th>Program Name</th>
                            <th>Track</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        
                            $department_name = "Senior High School";

                            $query = $con->prepare("SELECT t1.*, t2.department_name FROM program as t1
                                INNER JOIN department as t2 ON t2.department_id = t1.department_id
                                WHERE department_name = :department_name
                            ");

                            $query->bindParam(":department_name", $department_name);
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
                                                <button class='btn btn-primary dropdown-toggle' type='button' >
                                                    View
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
        </div> -->

        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h4>Program Code List</h4>
                </div>
                
            </header>


            <main>

                <table id="shs_subject_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Strand</th>
                            <th>Subject</th>
                            <th>Number of Section Enrolled</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <!-- <tbody>
                        <?php
                           

                            $query = $con->prepare("SELECT 
                                t1.*, t2.acronym, t2.program_name

                                FROM subject_program as t1

                                INNER JOIN program as t2 ON t2.program_id = t1.program_id
                                WHERE department_type =:department_type

                            ");

                            $query->bindValue(":department_type", "SHS");
                            $query->execute();

                            if($query->rowCount() > 0){

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){
                                    

                                    $subject_program_id = $row['subject_program_id'];
                                    $subject_code = $row['subject_code'];
                                    $subject_title = $row['subject_title'];
                                    $subject_type = $row['subject_type'];

                                    $acronym = $row['acronym'];
                                    $program_name = $row['program_name'];

                                    $program_code = $subject_code;

                                    $sectionHasSameCodeUrl = "section_code_list.php?id=$subject_program_id";
                                    
                                    $subjectProgram = new SubjectProgram($con);

                                    // $program_code = $subjectProgram->GetSubjectProgramRawCode();

                                    $sectionsHaveProgramCode = $subjectProgram->GetSectionsHaveProgramCode($program_code);

                                    $count = count($sectionsHaveProgramCode);

                                    $code_type = $subject_type === "Core" 
                                        ? "Universal" : ($subject_type == "Specialized" || $subject_type == "Applied" ? $acronym : "");

                                    echo "
                                        <tr>
                                            <td>$subject_program_id</td>
                                            <td>$code_type</td>
                                            <td>$subject_title</td>
                                            <td>
                                                <a style='color: inherit;' href='$sectionHasSameCodeUrl'>
                                                    $count
                                                </a>
                                            </td>
                                            <td>
                                                <a href='code_topics.php?id=$subject_program_id'>
                                                    <button class='btn btn-primary'>
                                                        <i class='fas fa-eye'></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    ";
                                }

                            }

                        ?>
                    </tbody> -->

                </table>

            </main>
        </div>
    </main>
    
</div>

<script>

    $(document).ready(function() {

        var table = $('#shs_subject_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': 'shsDataList.php',
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
                { data: 'subject_program_id', orderable: false },  
                { data: 'subject_type', orderable: false },  
                { data: 'subject_title', orderable: false },  
                { data: 'number_enrolled', orderable: false },  
                { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });
    });

</script>