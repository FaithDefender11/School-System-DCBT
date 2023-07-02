<?php

include_once('../../includes/admin_header.php');
include_once('../../includes/classes/Section.php');
include_once('../../includes/classes/Enrollment.php');
include_once('../../includes/classes/SchoolYear.php');

$section = new Section($con, null);
$school_year = new SchoolYear($con, null);
$enrollment = new Enrollment($con);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];

if (isset($_GET['id']) && $_GET['term']) {

    // $school_year_id = $_GET['id'];

    $program_id = $_GET['id'];
    $term = $_GET['term'];

    $FIRST_YEAR = 1;
    $SECOND_YEAR = 2;
    $THIRD_YEAR = 3;
    $FOURTH_YEAR = 4;
}
?>

<div class="col-md-12 row">
    <div class="content">
        <div class="floating" id="college-teachers">
            <header>
                <div class="title">
                    <h3>1st Year</h3>
                    <span><?php echo $current_school_year_term;?></span>
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
                                $FIRST_YEAR, $enrollment);
                        ?>
                    </tbody>
                </table>
            </main>
        </div>
        <hr>
                <div class="floating" id="college-teachers">
            <header>
                <div class="title">
                    <h3>2nd Year</h3>
                    <span><?php echo $current_school_year_term;?></span>
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
                                $SECOND_YEAR, $enrollment);
                        ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>
