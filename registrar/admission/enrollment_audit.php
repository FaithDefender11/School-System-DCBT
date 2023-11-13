<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Schedule.php');

    echo Helper::RemoveSidebar();

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

                #evaluation_table_filter{
                margin-top: 15px;
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: start;
                margin-bottom: 7px;
                }

                #evaluation_table_filter input{
                width: 250px;
                }

            </style>

            <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        </head>
    <?php
  
    $school_year = new SchoolYear($con);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $pre_requisite_subject_code = "";

    if(isset($_GET['id'])){

        // echo "audit";

        $enrollment_id = $_GET['id'];

        ?>

        <div class="content">

            <main>
                <div class="floating" id="shs-sy">
                    <header>
                        <div class="title">
                            <h3>Enrollment Audit trail</h3>
                        </div>
                    </header>
                    
                    <main>

                        <table id="department_table" class="a" style="margin: 0">
                            <thead>
                                <tr>
                                    <th>Process by</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                    // $SHS = "Senior High School";
                                    // $Tertiary = "Tertiary";

                                    $query = $con->prepare("SELECT 

                                        t1.*,
                                        t2.firstName,
                                        t2.lastName,
                                        t2.role 
                                        
                                        FROM enrollment_audit as t1

                                        LEFT JOIN users as t2 ON t2.user_id = t1.registrar_id
                                        
                                        WHERE t1.enrollment_id =:enrollment_id
                                        
                                        ORDER BY t1.date_creation DESC
                                    ");

                                    $query->bindValue(":enrollment_id", $enrollment_id);
                                    $query->execute();

                                    if($query->rowCount() > 0){

                                        $i = 0;

                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                            $i++;

                                            $enrollment_audit_id = $row['enrollment_audit_id'];
                                            $description = $row['description'];

                                            $role = $row['role'];
                                            $date_creation_db = $row['date_creation'];
                                            $date_creation = date("M d, Y h:i a", strtotime($date_creation_db));

                                            $fullname = ucwords($row['firstName']) . " " . ucwords($row['lastName']);

                                            // $removeDepartmentBtn = "removeDepartmentBtn($enrollment_audit_id)";
                                            
                                            echo "
                                                <tr>
                                                    <td>$i.) $fullname ($role)</td>
                                                    <td>$description</td>
                                                    <td>$date_creation</td>
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

        <?php
    }
    
?>