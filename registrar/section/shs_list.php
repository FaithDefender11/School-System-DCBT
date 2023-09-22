<?php

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

    ?>
        <style>
            .button-row {
                display: flex;
                justify-content: space-between;
                width: 165px;
            }
        </style>
    <?php

    $school_year = new SchoolYear($con, null);
    $section = new Section($con, null);
    $enrollment = new Enrollment($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $GRADE_ELEVEN = 11;
    $GRADE_TWELVE = 12;

    // $period_acronym = $current_school_year_period === "First" ? "S1" : $current_school_year_period === "Second" ? "S2" : "";
    $period_acronym = ($current_school_year_period === "First") ? "S1" : (($current_school_year_period === "Second") ? "S2" : "");

    if (isset($_GET['id']) && $_GET['term']) {

        // $school_year_id = $_GET['id'];
        
        $program_id = $_GET['id'];
        $term = $_GET['term'];

        $program = new Program($con, $program_id);

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

        // echo $term;


        ?>

            <div class="content">
                
                <nav>
                    <a href="shs_index.php">
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
                            
                            <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
                            <label for="term">Choose Term:</label>
                            <?php echo $recordsPerPageDropdown; ?>
                        </form>
                    </div>

                    <div class="floating" id="shs-sy">
                        

                        <header>
                            <div class="title">
                                <h3 style="font-weight: bold;">Grade 11</h3>
                            </div>

                            <div class="action">
                                <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $GRADE_ELEVEN;?>&term=<?php echo $term;?>">
                                    <button type="button" class="clean large success">+ Add new</button>
                                </a>

                            </div>
                        </header>
                        <main>
                            <table id="section_table_11"  class="a"
                                style="margin: 0">
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

                                        if($current_school_year_period == "First"){

                                            echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                                $term, "first_period_room_id",
                                                $GRADE_ELEVEN, $enrollment, $current_school_year_period, $current_school_year_term, $current_school_year_id);
                                        }

                                        else if($current_school_year_period == "Second"){

                                            echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                                $term, "second_period_room_id",
                                                $GRADE_ELEVEN, $enrollment,$current_school_year_period, $current_school_year_term, $current_school_year_id);
                                        }

                                        // if($current_school_year_period == "Second"){

                                        //     echo $section->CreateSHSSectionLevelSecondSemesterContent($program_id, $term,
                                        //         $GRADE_ELEVEN, $enrollment,);
                                        // }
                                    
                                        // echo $section->CreateSectionLevelContent($program_id, $term,
                                        //     $GRADE_ELEVEN, $enrollment, $current_school_year_id);
                                    ?>
                                </tbody>
                                    
                            </table>

                        </main>

                    </div>

                    <div class="floating" >
                        <header>
                            <div class="title">
                                <h3 style="font-weight: bold;">Grade 12</h3>
                            </div>

                            <div class="action">
                                <a href="add_section.php?id=<?php echo $program_id;?>&level=<?php echo $GRADE_TWELVE;?>&term=<?php echo $term;?>">
                                    <button type="button" class="clean large success">+ Add new</button>
                                </a>
                            </div>
                        </header>

                        <main>
                            <table id="section_table_12" 
                                class="a"
                                style="margin: 0">
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

                                        if($current_school_year_period == "First"){
                                            echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                                $term, "first_period_room_id",
                                                $GRADE_TWELVE, $enrollment,$current_school_year_period, $current_school_year_term);
                                        }
                                        else if($current_school_year_period == "Second"){
                                            echo $section->CreateSHSSectionLevelSemesterContent($program_id,
                                                $term, "second_period_room_id",
                                                $GRADE_TWELVE, $enrollment,$current_school_year_period, $current_school_year_term, $current_school_year_id);
                                        }

                                        // echo $section->CreateSectionLevelContent($program_id, $term,
                                        //     $GRADE_TWELVE, $enrollment, $current_school_year_id);
                                    ?>
                                    
                                </tbody>
                            </table>
                        </main>
                    </div>

                </main>
            </div>

        <?php
    }

?>



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
