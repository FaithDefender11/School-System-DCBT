
<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Enrollment.php');

    $room = new Room($con);
    $enrollment = new Enrollment($con);

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $uniqueCourseIds = $enrollment->GetEnrollmentCourseIds($current_school_year_id);


    if(isset($_GET['id'])){

        $school_year_id = $_GET['id'];

        $openedSections = $room->OpenedRoomSectionSemester($current_school_year_period,
            $current_school_year_term, $current_school_year_id);

        $uneReachedThresholdSectionsFirstSem = $room->UnReachedMinimumStudentSectionSemesterv2(
            "First",
            $current_school_year_term,
            $current_school_year_id);

        
        $uneReachedThresholdSectionsSecondSem = $room->UnReachedMinimumStudentSectionSemesterv2(
            "Second",
            $current_school_year_term,
            $current_school_year_id);

            // print_r($uneReachedThresholdSectionsFirstSem);

        ?>
            <div class="content">
                <main>
                    <div class="floating" id="shs-sy">
                        <header>
                            <div class="title">
                                <h3>Unreached Min Threshold Section in <?php echo $current_school_year_term;?> <?php echo "First";?> Semester</h3>
                            </div>

                            <div class="action">
                                    <button type="button"
                                        onclick="window.location.href='opened_section.php?id=<?php echo $current_school_year_id; ?>'"
                                    class="default large">
                                        View Opened Section
                                    </button>
                            </div>
                        </header>
                        <main>

                            <?php 
                                if(count($uneReachedThresholdSectionsFirstSem) > 0){

                                    // print_r($openedSections);
                                    ?>

                                        <table id="room_table" class="a" style="margin: 0">
                                            <thead>
                                                <tr>
                                                    <th>Section</th>
                                                    <th>Student / Capacity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
 
                                                    foreach ($uneReachedThresholdSectionsFirstSem as $key => $value) {
                                                        # code...

                                                        $program_section = $value['program_section'];
                                                        $course_id = $value['course_id'];
                                                        $capacity = $value['capacity'];

                                                        $assign_room_btn = "";

                                                        $first_period_room_id = $value['first_period_room_id'] ?? "-";
                                                        $second_period_room_id = $value['second_period_room_id'] ?? "-";

                                                        $section_show_url = "../section/show.php?id=$course_id";

                                                        if($first_period_room_id == NULL || $first_period_room_id == 0){
                                                            $assign_room_btn = "
                                                                <button 
                                                                    onclick='window.location.href = \"assign_room.php?id=$course_id\"'
                                                                    class='btn btn-sm btn-primary' >
                                                                    <i class='fas fa-plus-circle'></i>
                                                                </button>
                                                            ";
                                                        }

                                                        else{
                                                            // $assign_room_btn = $first_period_room_id;
                                                            $assign_room_btn = "
                                                                <button 
                                                                    onclick='window.location.href = \"assign_room.php?id=$course_id\"'
                                                                    class='btn btn-sm btn-primary' >
                                                                    <i class='fas fa-marker'></i>
                                                                </button>
                                                            ";
                                                        }
                                                        // $first_period_room_id = $value['first_period_room_id'] == NULL ? "-" : $value['first_period_room_id'];


                                                        $students_enrolled = $enrollment->GetStudentEnrolledInSection(
                                                            $course_id, $current_school_year_id);
                                                        // $students_enrolled = 0;


                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    <a style='color: inherit;' href='$section_show_url'>
                                                                        $program_section
                                                                    </a>
                                                                </td>
                                                                <td>$students_enrolled / $capacity</td>
                                                            </tr>
                                                        ";
                                                    }
 

                                                ?>
                                            </tbody>
                                        </table>

                                    <?php
                                }else{

                                    echo "
                                        <div>
                                            <h4 class='text-center text-info'>No Section has reached the threshold count.</h4>
                                        </div>
                                    ";
                                }
                            ?>

                        </main>
                    </div>
                </main>

                <br>
                <main>
                    <div class="floating" id="shs-sy">
                        <header>
                            <div class="title">
                                <h3>Unreached Min Threshold Section in <?php echo $current_school_year_term;?> <?php echo "Second";?> Semester</h3>
                            </div>

                            <div class="action">
                                    <button type="button"
                                        onclick="window.location.href='opened_section.php?id=<?php echo $current_school_year_id; ?>'"
                                    class="default large">
                                        View Opened Section
                                    </button>
                            </div>
                        </header>
                        <main>

                            <?php 
                                if(count($uneReachedThresholdSectionsSecondSem) > 0){

                                    // print_r($openedSections);
                                    ?>

                                        <table id="room_table" class="a" style="margin: 0">
                                            <thead>
                                                <tr>
                                                    <th>Section</th>
                                                    <th>Student / Capacity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
 
                                                    foreach ($uneReachedThresholdSectionsSecondSem as $key => $value) {
                                                        # code...

                                                        $program_section = $value['program_section'];
                                                        $course_id = $value['course_id'];
                                                        $capacity = $value['capacity'];

                                                        $assign_room_btn = "";

                                                        $first_period_room_id = $value['first_period_room_id'] ?? "-";
                                                        $second_period_room_id = $value['second_period_room_id'] ?? "-";

                                                        $section_show_url = "../section/show.php?id=$course_id";

                                                        if($first_period_room_id == NULL || $first_period_room_id == 0){
                                                            $assign_room_btn = "
                                                                <button 
                                                                    onclick='window.location.href = \"assign_room.php?id=$course_id\"'
                                                                    class='btn btn-sm btn-primary' >
                                                                    <i class='fas fa-plus-circle'></i>
                                                                </button>
                                                            ";
                                                        }

                                                        else{
                                                            // $assign_room_btn = $first_period_room_id;
                                                            $assign_room_btn = "
                                                                <button 
                                                                    onclick='window.location.href = \"assign_room.php?id=$course_id\"'
                                                                    class='btn btn-sm btn-primary' >
                                                                    <i class='fas fa-marker'></i>
                                                                </button>
                                                            ";
                                                        }
                                                        // $first_period_room_id = $value['first_period_room_id'] == NULL ? "-" : $value['first_period_room_id'];


                                                        $students_enrolled = $enrollment->GetStudentEnrolledInSection(
                                                            $course_id, $current_school_year_id);
                                                        // $students_enrolled = 0;


                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    <a style='color: inherit;' href='$section_show_url'>
                                                                        $program_section
                                                                    </a>
                                                                </td>
                                                                <td>$students_enrolled / $capacity</td>
                                                            </tr>
                                                        ";
                                                    }
 

                                                ?>
                                            </tbody>
                                        </table>

                                    <?php
                                }else{

                                    echo "
                                        <div>
                                            <h4 class='text-center text-info'>No Section has reached the threshold count.</h4>
                                        </div>
                                    ";
                                }
                            ?>

                        </main>
                    </div>
                </main>
            </div>
        <?php

    }

?>


<script>
   
</script> 