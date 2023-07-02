<?php

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    $school_year = new SchoolYear($con, null);
    $section = new Section($con, null);
    $enrollment = new Enrollment($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];

    $GRADE_ELEVEN = 11;
    $GRADE_TWELVE = 12;

    if (isset($_GET['id']) && $_GET['term']) {

        // $school_year_id = $_GET['id'];
        
        $program_id = $_GET['id'];
        $term = $_GET['term'];

        $program = new Program($con, $program_id);

    }
?>

<div class="col-md-12 row">
    <div class="content">
        <a href="shs_index.php">
            <button class="btn  btn-primary">
                <i class="fas fa-arrow-left"></i>
            </button>
        </a>
        <header>
            <div class="title">

                <h3><?php echo $program->GetProgramSectionName();?> Sections</h3>
                <span><?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester </span>

            </div>
            
        </header>
        <div class="floating" id="college-teachers">
            <header>
                <div class="title">
                    <h3>Grade 11</h3>
                    <span><?php echo $current_school_year_term;?></span>
                </div>
                <div>
                    <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $GRADE_ELEVEN;?>">
                        <button class="btn btn-success">
                            <i class="fas fa-plus"></i>
                        </button>
                    </a>
                </div>
            </header>
            <main>
                <table class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Section ID</th>
                            <th>Section Name</th>
                            <th>Students / Capacity</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                            // $query = $con->prepare("SELECT t1.* 

                            //     FROM course as t1 

                            //     WHERE t1.program_id=:program_id
                            //     AND t1.school_year_term=:school_year_term
                            //     AND t1.course_level=:course_level
                            // ");

                            // $query->bindParam(":program_id", $program_id);
                            // $query->bindParam(":school_year_term", $term);
                            // $query->bindParam(":course_level", $GRADE_ELEVEN);
                            // $query->execute();

                            // if($query->rowCount() > 0){

                            //     while($row = $query->fetch(PDO::FETCH_ASSOC)){

                            //         $program_section = $row['program_section'];
                            //         $course_id = $row['course_id'];

                            //         // echo $course_id;

                            //         $students_enrolled = $enrollment->GetStudentEnrolled($course_id);

                            //         echo "
                                    
                            //             <tr>
                            //                 <td>$course_id</td>
                            //                 <td>$program_section</td>
                            //                 <td>$students_enrolled</td>
                            //             </tr>
                            //         ";
                            //     }
                            // }

                            echo $section->CreateSectionLevelContent($program_id, $term,
                                $GRADE_ELEVEN, $enrollment);
                        ?>
                    </tbody>
                </table>
            </main>
        </div>
            <!--  -->
        <hr>
        <div class="floating" id="college-teachers">
            <header>
                <div class="title">
                    <h3>Grade 12</h3>
                    <span><?php echo $current_school_year_term;?></span>
                </div>
                <div>
                    <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $GRADE_TWELVE;?>">
                        <button class="btn btn-success">
                            <i class="fas fa-plus"></i>
                        </button>
                    </a>
                </div>
            </header>
            <main>
                <table class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Section ID</th>
                            <th>Section Name</th>
                            <th>Students / Capacity</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            echo $section->CreateSectionLevelContent($program_id, $term,
                                $GRADE_TWELVE, $enrollment);
                        ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>


</div>
