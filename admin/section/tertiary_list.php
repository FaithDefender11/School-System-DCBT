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
$current_school_year_id = $school_year_obj['school_year_id'];


$current_school_year_period = $school_year_obj['period'];
// $current_school_year_period = "Second";


if (isset($_GET['id']) && $_GET['term']) {

    // $school_year_id = $_GET['id'];

    $program_id = $_GET['id'];
    $term = $_GET['term'];

    $FIRST_YEAR = 1;
    $SECOND_YEAR = 2;
    $THIRD_YEAR = 3;
    $FOURTH_YEAR = 4;

    $program = new Program($con, $program_id);
    $period_acronym = ($current_school_year_period === "First") ? "S1" : (($current_school_year_period === "Second") ? "S2" : "");
    
    if(isset($_SESSION['section_term'])){
        unset($_SESSION['section_term']);
    }
        
    # Set in section/show.php
    if(isset($_SESSION['session_course_id'])){
        $session_course_id = $_SESSION['session_course_id'];
    }
    
    $_SESSION['section_term'] = $term;

    $recordsPerPageOptions = $school_year->GetAllSchoolYearTerm();

    $selectedTerm = isset($_GET['term']) 
        ? $_GET['term'] : $recordsPerPageOptions[0];

    $recordsPerPageDropdown = '<select class="ml-2 form-control" 
        name="term" onchange="this.form.submit()">';

    foreach ($recordsPerPageOptions as $option) {

        $recordsPerPageDropdown .= "<option value=$option";

        if ($option == $selectedTerm) {
            $recordsPerPageDropdown .= ' selected';
        }

        $recordsPerPageDropdown .= ">S.Y " . $option . "</option>";
    }

    $recordsPerPageDropdown .= '</select>';

    $term = $selectedTerm;

}

?>

<div class="content">
        <nav>
            <a href="tertiary_index.php">
                <i class="bi bi-arrow-return-left fa-1x"></i>
                <h3>Back</h3>
            </a>
        </nav>
        <main>



            <div>
                <h6 class="text-right"><?php echo $current_school_year_term;?> <?php echo $period_acronym;?></h6>
                
                <h4 class="text-center"><?php echo $program->GetProgramName();?></h4>
            </div>

            <div style="display: flex;justify-content: center;" class="text-center mb-3">
                <form method="GET" class="form-inline" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                    <!-- Hidden input field to preserve the 'id' parameter -->
                    <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                    <label for="term">Choose Term:</label>
                    <?php echo $recordsPerPageDropdown; ?>
                </form>
            </div>

            <div class="floating" id="shs-sy">

                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">1st Year</h3>
                    </div>

                    <div class="action">
                        <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $FIRST_YEAR;?>&term=<?php echo $term;?>">
                            <button type="button" class="default large">+ Add new</button>
                        </a>
                    </div>
                </header>

                <main>
                    <table id="section_table_1" class="a" style="margin: 0">
                        <thead>
                            <tr>
                                <th>Section ID</th>
                                <th>Section Name</th>
                                <!-- <th>Room</th> -->
                                <th>Students / Capacity</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                // echo $section->CreateSectionLevelContent($program_id, $term,
                                //     $FIRST_YEAR, $enrollment);

                                if($current_school_year_period == "First"){

                                    echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                        $term, "first_period_room_id",
                                        $FIRST_YEAR, $enrollment, $current_school_year_period, $current_school_year_term, $current_school_year_id, "admin");
                                }

                                else if($current_school_year_period == "Second"){

                                    echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                        $term, "second_period_room_id",
                                        $FIRST_YEAR, $enrollment, $current_school_year_period, $current_school_year_term, $current_school_year_id, "admin");
                                }
                            ?>
                        </tbody>
                         
                    </table>

                </main>
            </div>

            <div class="floating" id="shs-sy">

                <!-- 2nd Year -->
                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">2nd Year</h3>
                    </div>

                    <div class="action">
                        <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $SECOND_YEAR;?>&term=<?php echo $term;?>">
                            <button type="button" class="default large">+ Add new</button>
                        </a>
                    </div>
                </header>

                <main>
                    <table id="section_table_2" class="a" style="margin: 0">
                        <thead>
                            <tr>
                                <th>Section ID</th>
                                <th>Section Name</th>
                                <!-- <th>Room</th> -->
                                <th>Students / Capacity</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // echo $section->CreateSectionLevelContent($program_id, $term,
                                //     $SECOND_YEAR, $enrollment);

                                if($current_school_year_period == "First"){

                                    echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                        $term, "first_period_room_id",
                                        $SECOND_YEAR, $enrollment,$current_school_year_period, $current_school_year_term, $current_school_year_id, "admin");
                                }

                                else if($current_school_year_period == "Second"){

                                    echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                        $term, "second_period_room_id",
                                        $SECOND_YEAR, $enrollment,$current_school_year_period, $current_school_year_term, $current_school_year_id, "admin");
                                }
                            ?>
                        </tbody>
                         
                    </table>

                </main>
            </div>

              

            <div class="floating" id="shs-sy">
                <!-- 3rd Year -->
                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">3rd Year</h3>
                    </div>

                    <div class="action">
                        <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $THIRD_YEAR;?>&term=<?php echo $term;?>">
                            <button type="button" class="default large">+ Add new</button>
                        </a>
                    </div>
                </header>

                <main>
                    <table id="section_table_3" class="a" style="margin: 0">
                        <thead>
                            <tr>
                                <th>Section ID</th>
                                <th>Section Name</th>
                                <!-- <th>Room</th> -->
                                <th>Students / Capacity</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // echo $section->CreateSectionLevelContent($program_id, $term,
                                //     $THIRD_YEAR, $enrollment);

                                if($current_school_year_period == "First"){

                                    echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                        $term, "first_period_room_id",
                                        $THIRD_YEAR, $enrollment,$current_school_year_period, $current_school_year_term, $current_school_year_id, "admin");
                                }

                                else if($current_school_year_period == "Second"){

                                    echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                        $term, "second_period_room_id",
                                        $THIRD_YEAR, $enrollment,$current_school_year_period, $current_school_year_term, $current_school_year_id, "admin");
                                }


                            ?>
                        </tbody>
                         
                    </table>
                </main>

            </div>

            <div class="floating" id="shs-sy">

                <!-- 4th Year -->

                <header>
                    <div class="title">
                        <h3 style="font-weight: bold;">4th Year</h3>
                    </div>

                    <div class="action">
                        <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $FOURTH_YEAR;?>&term=<?php echo $term;?>">
                            <button type="button" class="default large">+ Add new</button>
                        </a>
                    </div>
                </header>

                <main>
                    <table id="section_table_4" class="a" style="margin: 0">
                        <thead>
                            <tr>
                                <th>Section ID</th>
                                <th>Section Name</th>
                                <!-- <th>Room</th> -->
                                <th>Students / Capacity</th>
                                <th>Active</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // echo $section->CreateSectionLevelContent($program_id, $term,
                                //     $FOURTH_YEAR, $enrollment);

                                if($current_school_year_period == "First"){

                                    echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                        $term, "first_period_room_id",
                                        $FOURTH_YEAR, $enrollment,$current_school_year_period, $current_school_year_term, $current_school_year_id, "admin");
                                }

                                else if($current_school_year_period == "Second"){

                                    echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                        $term, "second_period_room_id",
                                        $FOURTH_YEAR, $enrollment,$current_school_year_period, $current_school_year_term, $current_school_year_id, "admin");
                                }
                            ?>
                        </tbody>
                         
                    </table>

                </main>

            </div>


        </main>
</div>

 <script>

    function removeSection(course_id, course_level){
        Swal.fire({
                icon: 'question',
                title: `I agreed to removed Section ID: ${course_id}`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/section/remove_section.php",
                        type: 'POST',
                        data: {
                            course_id
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

                                $(`#section_table_${course_level}`).load(
                                    location.href + ` #section_table_${course_level}`
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

