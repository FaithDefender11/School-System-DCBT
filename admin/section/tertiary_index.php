

<?php 

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');
    
    ?>
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../subject/subject.css">
        </head>
    <?php

    $section = new Section($con);

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    
    if(isset($_SESSION['department_type_section'])){
        unset($_SESSION['department_type_section']);
    }
    # Set in section/show.php
    if(isset($_SESSION['session_course_id'])){
        $session_course_id = $_SESSION['session_course_id'];
    }
    $_SESSION['department_type_section'] = "Tertiary";

?>



<div class="content">

    <?php echo Helper::CreateTopDepartmentTab(true);?>
 
    <main>
        <div class="floating" id="shs-sy">
            <header>

                <div class="title">
                    <h3 style="font-weight: bold;">Course Section</h3>
                </div>
            </header>
            <main>
                <table id="shs_program_table"
                    class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Program ID</th>
                            <th>Course</th>
                            <th>1st Year</th>
                            <th>2nd Year</th>
                            <th>3rd Year</th>
                            <th>4th Year</th>
                            <th></th>
                        </tr>
                    </thead>
                        <tbody>
                            <?php

                                $department_name = "Tertiary";

                                $query = $con->prepare("SELECT t1.* FROM program as t1 

                                    INNER JOIN department as t2 ON t2.department_id = t1.department_id

                                    WHERE t2.department_name=:department_name
                                ");

                                $query->bindParam(":department_name", $department_name);
                                $query->execute();

                                if($query->rowCount() > 0){

                                    $FIRST_YEAR = 1;
                                    $SECOND_YEAR = 2;
                                    $THIRD_YEAR = 3;
                                    $FOURTH_YEAR = 4;

                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                        $acronym = $row['acronym'];
                                        $program_id = $row['program_id'];

                                        $first_year = $section->GetCreatedStrandSectionPerTerm($program_id,
                                            $current_school_year_term, $FIRST_YEAR);

                                        $second_year = $section->GetCreatedStrandSectionPerTerm($program_id,
                                            $current_school_year_term, $SECOND_YEAR);
                                        
                                        $third_year = $section->GetCreatedStrandSectionPerTerm($program_id,
                                            $current_school_year_term, $THIRD_YEAR);

                                        $fourth_year = $section->GetCreatedStrandSectionPerTerm($program_id,
                                            $current_school_year_term, $FOURTH_YEAR);

                                        // $grade_12_sections = $section->GetCreatedStrandSectionPerTerm($program_id,
                                        //     $current_school_year_term, $GRADE_TWELVE);

                                        $removeProgramBtn = "removeProgramBtn($program_id)";

                                        echo "
                                            <tr>
                                                <td>$program_id</td>
                                                <td>$acronym</td>
                                                <td>$first_year</td>
                                                <td>$second_year</td>
                                                <td>$third_year</td>
                                                <td>$fourth_year</td>
                                                <td>
                                                    <a href='tertiary_list.php?id=$program_id&term=$current_school_year_term'>
                                                        <button class='btn btn-primary'>
                                                            <i class='fas fa-eye'></i>
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
    </main>
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

                                $('#shs_program_table').load(
                                    location.href + ' #shs_program_table'
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


