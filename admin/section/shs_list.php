<?php

include_once('../../includes/admin_header.php');
include_once('../../includes/classes/Section.php');
include_once('../../includes/classes/Enrollment.php');
include_once('../../includes/classes/SchoolYear.php');

$school_year = new SchoolYear($con, null);
$section = new Section($con, null);
$enrollment = new Enrollment($con);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];

if (isset($_GET['id']) && $_GET['term']) {

    // $school_year_id = $_GET['id'];

    $program_id = $_GET['id'];
    $term = $_GET['term'];

    $GRADE_ELEVEN = 11;
    $GRADE_TWELVE = 12;
}
?>

<div class="col-md-12 row">
    <div class="content">
        <div class="floating" id="college-teachers">
            <header>
                <div class="title">
                    <h3>Grade 11</h3>
                    <span><?php echo $current_school_year_term;?></span>
                </div>
            </header>
            <main>
                <table class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Section ID</th>
                            <th>Section Name</th>
                            <th>Students</th>
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
            </header>
            <main>
                <table class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                    <thead>
                        <tr>
                            <th>Section ID</th>
                            <th>Section Name</th>
                            <th>Students</th>
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
