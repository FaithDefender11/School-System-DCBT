<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');
    
    $section = new Section($con);

    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    if(isset($_SESSION['department_type_section'])){
        unset($_SESSION['department_type_section']);
    }

    $_SESSION['department_type_section'] = "Senior High School";
    
    
?>


<div class="content">

    <?php echo Helper::CreateTopDepartmentTab(false);?>


    <main>
        <div class="floating" id="shs-sy">
            <header>

                <div class="title">
                    <h3 style="font-weight: bold;">Strand Section</h3>
                </div>
            </header>
            <main>
                <table id="shs_program_table"
                    class="a" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Program ID</th>
                            <th>Strand</th>
                            <th>Grade 11</th>
                            <th>Grade 12</th>
                            <th>Action</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            $department_name = "Senior High School";

                            $query = $con->prepare("SELECT t1.* FROM program as t1 

                                INNER JOIN department as t2 ON t2.department_id = t1.department_id

                                WHERE t2.department_name=:department_name
                            ");

                            $query->bindParam(":department_name", $department_name);
                            $query->execute();

                            if($query->rowCount() > 0){

                                $GRADE_ELEVEN = 11;
                                $GRADE_TWELVE = 12;

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $acronym = $row['acronym'];
                                    $program_id = $row['program_id'];


                                    $grade_11_sections = $section->GetCreatedStrandSectionPerTerm($program_id,
                                        $current_school_year_term, $GRADE_ELEVEN);

                                    $grade_12_sections = $section->GetCreatedStrandSectionPerTerm($program_id,
                                        $current_school_year_term, $GRADE_TWELVE);

                                    $removeProgramBtn = "removeProgramBtn($program_id)";
                                    echo "
                                        <tr>
                                            
                                            <td>$program_id</td>
                                            <td>$acronym</td>
                                            <td>$grade_11_sections</td>
                                            <td>$grade_12_sections</td>
                                            <td>
                                                <a href='shs_list.php?id=$program_id&term=$current_school_year_term'>
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
                    </tbody>
                </table>
            </main>
        </div>
    </main>

</div>
<?php include_once('../../includes/footer.php') ?>
