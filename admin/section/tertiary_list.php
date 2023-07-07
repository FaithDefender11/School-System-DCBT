<?php

include_once('../../includes/admin_header.php');
include_once('../../includes/classes/Section.php');
include_once('../../includes/classes/Enrollment.php');
include_once('../../includes/classes/SchoolYear.php');
include_once('../../includes/classes/Program.php');

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

    $program = new Program($con, $program_id);

}
?>



<div class="content">
        <main>
            <div class="floating" id="shs-sy">
                <div>
                    <h4 class="text-right">S.Y <?php echo $current_school_year_term;?></h4>

                    <a href='tertiary_index.php'>
                        <button class="text-left btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>

                    </a>
                    
                    <h4 class="text-center"><?php echo $program->GetProgramSectionName();?> Sections</h4>
                    
                </div>
                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">1st Year</h3>
                    </div>

                    <div class="action">
                        <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $FIRST_YEAR;?>">
                            <button type="button" class="clean large success">+ Add new</button>
                        </a>
                    </div>
                </header>
                <main>
                    <table id="department_table" class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
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

                <!-- 2nd Year -->
                <hr>

                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">2nd Year</h3>
                    </div>

                    <div class="action">
                        <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $SECOND_YEAR;?>">
                            <button type="button" class="clean large success">+ Add new</button>
                        </a>
                    </div>
                </header>

                <main>
                    <table id="department_table" class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
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

                <!-- 3rd Year -->
                <hr>

                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">3rd Year</h3>
                    </div>

                    <div class="action">
                        <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $THIRD_YEAR;?>">
                            <button type="button" class="clean large success">+ Add new</button>
                        </a>
                    </div>
                </header>

                <main>
                    <table id="department_table" class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
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
                                    $THIRD_YEAR, $enrollment);
                            ?>
                        </tbody>
                         
                    </table>

                </main>

                <!-- 4th Year -->
                <hr>

                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">4th Year</h3>
                    </div>

                    <div class="action">
                        <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $FOURTH_YEAR;?>">
                            <button type="button" class="clean large success">+ Add new</button>
                        </a>
                    </div>
                </header>

                <main>
                    <table id="department_table" class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
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
                                    $FOURTH_YEAR, $enrollment);
                            ?>
                        </tbody>
                         
                    </table>

                </main>


            </div>
        </main>
</div>

 