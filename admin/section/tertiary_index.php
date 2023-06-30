

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


    <div class="content">
        <div class="content-header"></div>


        <!--SHS-TEACHERS-->
        <main>
            <div class="floating" id="shs-teachers">
                <header>
                    <div class="title">
                        <h3>Course Sections</h3>
                        <span><?php echo $current_school_year_term;?></span>
                    </div>
                </header>
                <main>
                    <table
                        class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
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

</div>


