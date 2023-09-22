
<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Room.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/PendingParent.php');

    $room = new Room($con);
    $pending = new Pending($con);
    $section = new Section($con);
    $enrollment = new Enrollment($con);

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id'])){

        if(isset($_SESSION['section_term'])){
            unset($_SESSION['section_term']);
        }


        $school_year_id = $_GET['id'];

        // $current_school_year_term = "2021-2022";



        // $asd = $section->GetAllActiveSectionRoomIn2ndSem($current_school_year_term);
        
        // $movingUpSection = $section->MovingUpCurrentActiveSections(
        //     $current_school_year_term);
        if($current_school_year_period == "First"){

            // $first = $section->RemoveUnEnrolledSectionInFirstSemester(
            //     $current_school_year_term,
            //     $current_school_year_id);
        }
        if($current_school_year_period == "Second"){

            // $activeSectionIn2ndSem = $section->GetAllActiveSectionRoomIn2ndSem(
            //     $current_school_year_term);

            // $sec = $section->RemoveUnEnrolledSectionInSecondSemester(
            //     $current_school_year_term,
            //     "Second", $current_school_year_id);
        }

        // $dd = $section->GetAllActiveSectionWithinYear($current_school_year_term);
        // print_r($dd);

        // $removedNewEnrollmentList = $enrollment->RemovingTentativeNewEnrollmentForm(
            // $current_school_year_id);

        $newEnrollmentList = $enrollment->GetNewEnrollmentTentativeIDs(
            $current_school_year_id);

        // $allEnrolleeInSemester = $pending->GetNewEnrolleeWithinSemester(
        //     $school_year_id);

        // $removedAllEnrolleeInSemester = $pending->RemoveAllPendingEnrolleeWithinSemester(
        //     $school_year_id);
        
            
        $oldEnrollmentList = $section->RemoveUnEnrolledSectionWithinSemester(
            $current_school_year_term, $current_school_year_period,
            $current_school_year_id);

        // print_r($oldEnrollmentList);
        // print_r($oldEnrollmentList);

        $recordsPerPageOptions = $school_year->GetAllSchoolYearTerm();

        // echo $recordsPerPageOptions[0];

        // $recordsPerPageOptions = ["2021-2022", "2022-2023"]; 

        $selectedTerm = isset($_GET['per_term']) 
            ? $_GET['per_term'] : $current_school_year_term;

        $recordsPerPageDropdown = '<select class="ml-2 form-control" 
            name="per_term" onchange="this.form.submit()">';


        foreach ($recordsPerPageOptions as $option) {

            $recordsPerPageDropdown .= "<option value=$option";

            if ($option == $selectedTerm) {
                $recordsPerPageDropdown .= ' selected';
            }

            $recordsPerPageDropdown .= ">S.Y " . $option . "</option>";
        }

        $recordsPerPageDropdown .= '</select>';

        // echo $selectedTerm;
        // echo "<br>";

        // echo $current_school_year_term;
        // echo "<br>";

        $current_school_year_term = $selectedTerm;

        $section_term = "";

        if(!isset($_SESSION['section_term'])){
            $_SESSION['section_term'] = $current_school_year_term;
        }

        // echo $_SESSION['section_term'];

        $openedSectionsFirstSemester = $room->OpenedRoomSectionSemester(
            "First",
            $current_school_year_term,
            $current_school_year_id);

        $openedSectionsSecondSemester = $room->OpenedRoomSectionSemester(
            "Second",
            $current_school_year_term,
            $current_school_year_id);

        // print_r($openedSectionsFirstSemester);

        $semesterSectionHasRoomIds = $section->GetSectionIdHasRoomSemester($current_school_year_period,
            $current_school_year_term);

        // print_r($semesterSectionHasRoomIds);

        $availableRoom = $room->AvailableSectionSYSemesterList(
            $current_school_year_period,
            $current_school_year_term, $semesterSectionHasRoomIds);

        $uniqueCourseIdsWithinSemester = $enrollment->GetEnrollmentCourseIds(
            $current_school_year_id);


        ?>
            <div class="content">

                <nav>
                    <a href="index.php">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>

                 <main>
                    <div class="floating" id="shs-sy">

                        <header>
                            <div class="title">
                                <h4>Available sections for <?php echo $current_school_year_term;?> <?php echo $current_school_year_period;?> Semester</h4>
                            </div>
                        </header>

                        <main>

                            <?php 
                                if(count($availableRoom) > 0){
                                    ?>

                                        <table id="room_table" class="a" style="margin: 0">
                                            <thead>
                                                <tr>
                                                    <th>Room No.</th>
                                                    <th>Room Name</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                    foreach ($availableRoom as $key => $value) {

                                                        $room_id = $value['room_id'];
                                                        $room_number = $value['room_number'];
                                                        $room_name = $value['room_name'];

                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    $room_number
                                                                </td>
                                                                <td>$room_name</td>
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
                                            <h4 class='text-center text-info'>No available Room for this semester.</h4>
                                        </div>
                                    ";
                                }
                            ?>

                        </main>
                    </div>
                </main>
                <hr>

                <main>

                    <div style="display: flex;justify-content: center;" class="text-center mb-3">
                        <form method="GET" class="form-inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                            <label for="per_term">Choose Semester:</label>
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">

                            <?php echo $recordsPerPageDropdown; ?>
                        </form>
                    </div>

                    <div class="floating" id="shs-sy">
 
                        <header>
                            <div class="title">
                                <h3>Open Section in <?php echo $current_school_year_term;?> <?php echo "First";?> Semester</h3>
                            </div>

                            <div class="action">
                                    <button type="button"
                                        onclick="window.location.href='unopen_section.php?id=<?php echo $current_school_year_id; ?>'"
                                    class="default large">
                                        View Unopened Section
                                    </button>
                            </div>
                        </header>
                        <main>

                            <?php 
                                if(count($openedSectionsFirstSemester) > 0){

                                    // print_r($openedSectionsFirstSemester);
                                    ?>

                                        <table id="room_table" class="a" style="margin: 0">
                                            <thead>
                                                <tr>
                                                    <th>Section</th>
                                                    <th>Student / Capacity</th>
                                                    <th>Room No.</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                    foreach ($openedSectionsFirstSemester as $key => $value) {

                                                        $program_section = $value['program_section'];
                                                        $course_id = $value['course_id'];
                                                        $capacity = $value['capacity'];

                                                        $assign_room_btn = "";

                                                        # Requirement
                                                        # If First semester the room should be displayed was
                                                        # first_period_room_id

                                                        # If Second semester the room should be displayed was
                                                        # second_period_room_id

                                                        # Please aligned the button accordingly with remove functions and update.

                                                        $first_period_room_id = $value['first_period_room_id'] ?? "~";

                                                        $room_number = "~";
                                                        if($first_period_room_id != 0 || $first_period_room_id != NULL){
                                                            $room_exec = new Room($con, $first_period_room_id);

                                                            $room_number = $room_exec->GetRoomNumber();

                                                        }

                                                        if($current_school_year_period == "First"){

                                                            if(($first_period_room_id == NULL || $first_period_room_id == 0)){
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

                                                        }
                                                        
                                                        // $first_period_room_id = $value['first_period_room_id'] == NULL ? "-" : $value['first_period_room_id'];

                                                        // $students_enrolled = 0;
                                                        $students_enrolled = $enrollment->GetStudentEnrolledInSection(
                                                            $course_id, $current_school_year_id,
                                                            $current_school_year_term, "First");


                                                        // $section_show_url = "../section/show.php?id=$course_id";
                                                        $section_show_url = "../section/show.php?id=$course_id&per_semester=First&term=$selectedTerm";

                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    <a style='color: inherit;' href='$section_show_url'>
                                                                        $program_section
                                                                    </a>
                                                                </td>
                                                                <td>$students_enrolled / $capacity</td>
                                                                <td>$room_number</td>
                                                                <td>
                                                                    $assign_room_btn
                                                                </td>
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

                <main>
                    <div class="floating" id="shs-sy">
                        <header>
                            <div class="title">
                                <h3>Open Section in <?php echo $current_school_year_term;?> <?php echo "Second";?> Semester</h3>
                            </div>

                            <div class="action">
                            </div>
                        </header>
                        <main>

                            <?php 
                                if(count($openedSectionsSecondSemester) > 0){

                                    ?>

                                        <table id="room_table" class="a" style="margin: 0">
                                            <thead>
                                                <tr>
                                                    <th>Section</th>
                                                    <th>Enrolled / Capacity</th>
                                                    <th>Tentative</th>
                                                    <th>Room No.</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                    foreach ($openedSectionsSecondSemester as $key => $value) {

                                                        $program_section = $value['program_section'];
                                                        $course_id = $value['course_id'];
                                                        $capacity = $value['capacity'];

                                                        $assign_room_btn = "";

                                                        # Requirement
                                                        # If First semester the room should be displayed was
                                                        # first_period_room_id

                                                        # If Second semester the room should be displayed was
                                                        # second_period_room_id

                                                        # Please aligned the button accordingly with remove functions and update.

                                                        $second_period_room_id = $value['second_period_room_id'] ?? "-";

                                                        $room_number = "~";
                                                        
                                                        if($second_period_room_id != 0 || $second_period_room_id != NULL){
                                                            $room_exec = new Room($con, $second_period_room_id);
                                                            $room_number = $room_exec->GetRoomNumber();
                                                        }

                                                        if($current_school_year_period == "Second"){

                                                            $assign_room_btn = "
                                                                <button 
                                                                    onclick='window.location.href = \"assign_room.php?id=$course_id\"'
                                                                    class='btn btn-sm btn-primary' >
                                                                    <i class='fas fa-marker'></i>
                                                                </button>
                                                            ";
                                                        }

                                                        // echo $current_school_year_term;

                                                        $students_enrolled = $enrollment->GetStudentEnrollmentStatusTypeInSection(
                                                            $course_id, $current_school_year_term, "Second", "enrolled");

                                                        $students_tentative = $enrollment->GetStudentEnrollmentStatusTypeInSection(
                                                            $course_id, $current_school_year_term,
                                                            "Second", "tentative"
                                                        );


                                                        $section_show_url = "../section/show.php?id=$course_id&per_semester=second&term=$current_school_year_term";

                                                        $period = strtolower($current_school_year_period);
                                                        
                                                        $show_student_url = "../section/show_students.php?course_id=$course_id&sy_id=$current_school_year_id";
                                                        echo "
                                                            <tr>
                                                                <td>
                                                                    <a style='color: inherit;' href='$section_show_url'>
                                                                        $program_section
                                                                    </a>
                                                                </td>
                                                                <td>$students_enrolled / $capacity</td>
                                                                <td>
                                                                    $students_tentative

                                                                </td>
                                                                <td>
                                                                    <a style='color: inherit' href='show_section.php?id=$second_period_room_id&term=$current_school_year_term&period=$period'>
                                                                        $room_number
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    $assign_room_btn
                                                                </td>
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