
<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Program.php');

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

?>

<div class="content">
    <main>
        <div class="floating" id="shs-sy">
            <header>
                <div class="title">
                    <h3>Waiting List Summary for <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h3>
                </div>

                <div class="action">
                        <button type="button"
                            onclick="window.location.href='create.php?id=<?php echo ''; ?>'"
                        class="information default large">
                            Add Waitlist
                        </button>
                </div>
            </header>
            <main>

                <table id="room_table" class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Program</th>
                            <th>Course Level</th>
                            <th>Type</th>
                            <th>Registrar</th>
                            <th>Cashier</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            // $query = $con->prepare("SELECT 

                            //     t1.*,
                            //     t2.admission_status,
                            //     t2.firstname,
                            //     t2.lastname

                            //     FROM waiting_list as t1

                            //     LEFT JOIN student as t2 on t2.student_id = t1.student_id

                            //     ");

                            // $query->execute();

                            // if($query->rowCount() > 0){

                            //     while($row = $query->fetch(PDO::FETCH_ASSOC)){

                            //         $firstname = ucfirst($row['firstname']);
                            //         $lastname = ucfirst($row['lastname']);

                            //         $student_id = $row['student_id'];

                            //         $course_level = $row['course_level'];
                            //         $registrar_evaluated = $row['registrar_evaluated'];
                            //         $cashier_evaluated = $row['cashier_evaluated'];

                            //         $status = $row['status'];
                            //         $program_id = $row['program_id'];
                            //         $admission_status = $row['admission_status'];

                            //         $program = new Program($con, $program_id);

                            //         $program_name = $program->GetProgramName();


                            //         # Check if Tentative/Enrolled that has given form
                            //         $url = directoryPath . "../admission/adjustment.php?id=$student_id&enrolled_subject=show";
                                    
                            //         $has_form_enrollment_url = "
                            //             <button
                            //                 onclick='window.location.href= \"$url\"'
                            //                 class='btn btn-primary'>
                            //                 <i class='fas fa-search'></i>
                            //             </button>
                            //         ";

                            //         $registrar_evaluted_output = "~";
                            //         $cashier_evaluted_output = "~";

                            //         if($registrar_evaluated == "yes"){
                            //             $registrar_evaluted_output = "
                            //                 <i style='color: green;' class='fas fa-check'></i>
                            //             ";
                            //         }

                            //         if($cashier_evaluated == "yes"){
                            //             $cashier_evaluted_output = "
                            //                 <i style='color: green;' class='fas fa-check'></i>
                            //             ";
                            //         }
                                     
                            //         echo "
                            //             <tr>
                            //                 <td>$program_name</td>
                            //                 <td>$firstname $lastname</td>
                            //                 <td>$course_level</td>
                            //                 <td>$admission_status</td>
                            //                 <td>$registrar_evaluted_output</td>
                            //                 <td>$cashier_evaluted_output</td>
                            //                 <td>$status</td>
                            //                 <td>$has_form_enrollment_url</td>
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
   
</script>