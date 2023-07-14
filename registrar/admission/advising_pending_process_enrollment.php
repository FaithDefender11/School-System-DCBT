<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Department.php');


    $department = new Department($con);
    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    if(isset($_GET['id'])){

        
        // Things to consider.
        // 1. New Transferee -> Pending Table.
        // 1. Irregular O.S -> Pending Table.

        $pending_enrollees_id = $_GET['id'];
        


        $enrollment = new Enrollment($con);
        $section = new Section($con);
        
        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

        if (!isset($_SESSION['enrollment_form_id'])) {
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;
            
        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];
        }

        $pending_query = $con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id
            ");

        $pending_query->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $pending_query->execute();

        $row = null;

        $course_id = 0;

        if($pending_query->rowCount() > 0){

            $row = $pending_query->fetch(PDO::FETCH_ASSOC);
            $pending_enrollees_id = $row['pending_enrollees_id'];

            $get_parent = $con->prepare("SELECT * FROM parent
                WHERE pending_enrollees_id=:pending_enrollees_id");
        
            $get_parent->bindValue(":pending_enrollees_id", $pending_enrollees_id);
            $get_parent->execute();

            $parent_firstname = "";
            $parent_lastname = "";
            $parent_middle_name = "";
            $parent_contact_number = "";
            $parent_email = "";
            $parent_occupation = "";
            $parent_suffix = "";

            if($get_parent->rowCount() > 0){

                $rowParnet = $get_parent->fetch(PDO::FETCH_ASSOC);

                $parent_id = $rowParnet['parent_id'];
                $parent_firstname = $rowParnet['firstname'];
                $parent_lastname = $rowParnet['lastname'];
                $parent_middle_name = $rowParnet['middle_name'];
                $parent_contact_number = $rowParnet['contact_number'];
                $parent_occupation = $rowParnet['occupation'];
                $parent_suffix = $rowParnet['suffix'];
                $parent_email = $rowParnet['email'];
            }

            $program_id = $row['program_id'];

            $firstname = $row['firstname'];
            $middle_name = $row['middle_name'];
            $suffix = $row['suffix'];
            $lastname = $row['lastname'];
            $birthday = $row['birthday'];
            $address = $row['address'];
            $sex = $row['sex'];
            $contact_number = $row['contact_number'];
            $date_creation = $row['date_creation'];
            $student_status = $row['student_status'];
            $email = $row['email'];
            $pending_enrollees_id = $row['pending_enrollees_id'];
            $password = $row['password'];
            $civil_status = $row['civil_status'];
            $nationality = $row['nationality'];
            $age = $row['age'];
            $guardian_name = $row['guardian_name'];
            $guardian_contact_number = $row['guardian_contact_number'];
            $lrn = $row['lrn'];
            $birthplace = $row['birthplace'];
            $religion = $row['religion'];
            $email = $row['email'];
            $type = $row['type'];
            $admission_status = $row['admission_status'];

            $program = $con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id
                LIMIT 1
            ");

            $program->bindValue(":program_id", $program_id);
            $program->execute();

            $program_acronym = $program->fetchColumn();

            $student_fullname = $firstname . " " . $lastname;

            $section = new Section($con, null);

            $strand_name = $section->GetAcronymByProgramId($program_id);
            $track_name = $section->GetTrackByProgramId($program_id);
    
            if(isset($_GET['details']) && $_GET['details'] == "show"){
                include("./advicing_pending_details.php");
            }

            else if(isset($_GET['finding_section']) 
                && $_GET['finding_section'] == "show"){

                ?>
                    <div class="content">
                        <nav>
                            <a href="#"
                            ><i class="bi bi-arrow-return-left fa-1x"></i>
                            <h3>Back</h3>
                            </a>
                        </nav>
                        <div class="content-header">
                                
                            <?php echo Helper::RevealStudentType($type); ?>

                            <header>
                                <div class="title">
                                    <h1>Enrollment form</h1> 
                                </div>
                                <div class="action">
                                    <div class="dropdown">

                                        <button class="icon">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                            <a href="#" class="dropdown-item" style="color: red"
                                            ><i class="bi bi-file-earmark-x"></i>Delete form</a
                                            >
                                        </div>
                                        
                                    </div>
                                </div>

                            </header>

                            <div class="cards">
                                <div class="card">
                                    <p class="text-center mb-0">Form ID</p>
                                    <p class="text-center"><?php echo $enrollment_form_id;?></p>
                                </div>
                                <div class="card">
                                    <p class="text-center mb-0">Admission type</p>
                                    <p class="text-center">N/A</p>
                                </div>
                                <div class="card">
                                    <p class="text-center mb-0">Student no.</p>
                                    <p class="text-center">N/A</p>
                                </div>
                                <div class="card">
                                    <p class="text-center mb-0">Status</p>
                                    <p class="text-center">Evaluation</p>
                                </div>
                                <div class="card">
                                    <p class="text-center mb-0">Submitted on</p>
                                    <p class="text-center">
                                        <?php
                                            $date = new DateTime($date_creation);
                                            $formattedDate = $date->format('m/d/Y H:i');
                                            echo $formattedDate;
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <main>
                            <div class="progress">
                                <span class="dot active"><p>Check form details</p></span>
                                <span class="line active"></span>
                                <span class="dot active"><p>Find section</p></span>
                                <span class="line inactive"></span>
                                <span class="dot inactive"><p>Subject confirmation</p></span>
                                
                            </div>

                            <div class="floating">
                                <header>
                                    <div class="title">
                                        <h3>Enrollment details</h3>
                                    </div>
                                </header>

                                <main>
                                    
                                    <form method="POST">
                                        <div class="row">

                                            <span>
                                                <label for="sy">S.Y.</label>
                                                <div>
                                                    <input class="text-center" type="text" name="sy" id="sy" value="<?php echo $current_school_year_term; ?>" />
                                                </div>
                                            </span>

                                            <?php
                                            
                                                if($type == "Tertiary"){
                                                    ?>
                                                        <span>
                                                            <label label for="track">Track</label>

                                                            <div>
                                                                <select id="inputTrack" class="form-select">
                                                                    <?php 

                                                                        // $SHS_DEPARTMENT = 4;


                                                                    
                                                                        $track_sql = $con->prepare("SELECT 
                                                                            program_id, track, acronym 
                                                                            
                                                                            FROM program 

                                                                            WHERE department_id !=:department_id
                                                                            GROUP BY track
                                                                        ");

                                                                        $track_sql->bindValue(":department_id", $department_id);
                                                                        $track_sql->execute();
                                                                        
                                                                        while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                            $row_program_id = $row['program_id'];

                                                                            $track = $row['track'];

                                                                            $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                            echo "<option class='text-center' value='$row_program_id' $selected>$track</option>";
                                                                        }
                                                                    ?>
                                                                
                                                                </select>
                                                            </div>
                                                        </span>

                                                        <span>
                                                            <label for="strand">Strand</label>

                                                            <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                                name="strand" id="strand" class="form-select">
                                                                <?php 

                                                                    $SHS_DEPARTMENT = 4;
                                                                
                                                                    $track_sql = $con->prepare("SELECT 
                                                                        program_id, track, acronym 
                                                                        
                                                                        FROM program 
                                                                        WHERE department_id !=:department_id
                                                                        GROUP BY acronym
                                                                    ");

                                                                    $track_sql->bindValue(":department_id", $department_id);
                                                                    $track_sql->execute();

                                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                        $row_program_id = $row['program_id'];

                                                                        $acronym = $row['acronym'];

                                                                        $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                        echo "<option class='text-center' value='$row_program_id' $selected>$acronym</option>";
                                                                    }
                                                                ?>

                                                            </select>
                                                        </span>
                                                    <?php
                                                }
                                                else if($type == "SHS"){
                                                    
                                                    ?>
                                                        <span>
                                                            <label label for="track">Track</label>
                                                            <div>
                                                                <select id="inputTrack" class="form-select">
                                                                    <?php 
                                                                        $SHS_DEPARTMENT = 4;

                                                                        echo $department_id;
                                                                    
                                                                        $track_sql = $con->prepare("SELECT 
                                                                            program_id, track, acronym 
                                                                            
                                                                            FROM program 

                                                                            WHERE department_id =:department_id
                                                                            GROUP BY track
                                                                        ");

                                                                        $track_sql->bindValue(":department_id", $department_id);
                                                                        $track_sql->execute();

                                                                        while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                            $row_program_id = $row['program_id'];

                                                                            $track = $row['track'];

                                                                            $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                            echo "<option value='$row_program_id' $selected>$track</option>";
                                                                        }
                                                                    ?>
                                                                    
                                                                </select>
                                                            </div>
                                                        </span>

                                                        <span>
                                                            <label for="strand">Strand</label>
                                                            <select onchange="chooseStrand(this, <?php echo $pending_enrollees_id;?>)" 
                                                                name="strand" id="strand" class="form-select">
                                                                <?php 
                                                                
                                                                    $track_sql = $con->prepare("SELECT 
                                                                        program_id, track, acronym 
                                                                        
                                                                        FROM program 
                                                                        WHERE department_id =:department_id
                                                                        GROUP BY acronym
                                                                    ");

                                                                    $track_sql->bindValue(":department_id", $department_id);
                                                                    $track_sql->execute();

                                                                    while($row = $track_sql->fetch(PDO::FETCH_ASSOC)){

                                                                        $row_program_id = $row['program_id'];

                                                                        $acronym = $row['acronym'];

                                                                        $selected = ($row_program_id == $program_id) ? "selected" : "";

                                                                        echo "<option value='$row_program_id' $selected>$acronym</option>";
                                                                    }
                                                                ?>

                                                            </select>
                                                        </span>
                                                    <?php
                                                }
                                            ?>

                                        </div>

                                        <div class="row">

                                            <span>
                                                <label for="grade">Level</label>
                                                <div>
                                                    <select name="grade" id="grade" disabled>
                                                        <option class="text-center" value="11"<?php echo ($admission_status == "Standard" && $type == "SHS") ? " selected" : ""; ?>>11</option>
                                                        <option class="text-center" value="1"<?php echo ($admission_status == "Standard" && $type == "Tertiary") ? " selected" : ""; ?>>1</option>
                                                        <!-- <option class="text-center" value="">12</option> -->
                                                    </select>
                                                </div>
                                            </span>

                                            <span>
                                                <label for="semester">Semester</label>
                                                <div>
                                                    <select name="semester" id="semester" disabled>
                                                        <option class="text-center" value=""<?php echo ($current_school_year_period == "First") ? " selected" : ""; ?>>1st</option>
                                                        <option class="text-center" value=""<?php echo ($current_school_year_period == "Second") ? " selected" : ""; ?>>2nd</option>
                                                    </select>
                                                </div>
                                            </span>
                                        </div>
                                    </form>

                                </main>

                            </div>

                            <script>
                                function chooseStrand(entity, pending_enrollees_id){

                                    var strand = document.getElementById("strand").value;

                                    // console.log("Selected value: " + strand);

                                    Swal.fire({
                                        icon: 'question',
                                        title: `Update Strand?`,
                                        showCancelButton: true,
                                        confirmButtonText: 'Yes',
                                        cancelButtonText: 'Cancel'
                                    }).then((result) => {

                                        if (result.isConfirmed) {
                                            // REFX
                                            $.ajax({
                                                url: '../ajax/pending/update_student_strand.php',
                                                type: 'POST',
                                                data: {
                                                    strand, pending_enrollees_id
                                                },
                                                success: function(response) {

                                                    console.log(response);

                                                    // enrollment-details
                                                    if(response == "success"){
                                                        $('#enrollment-details').load(
                                                            location.href + ' #enrollment-details'
                                                        );
                                                        $('#regular_available_section').load(
                                                            location.href + ' #regular_available_section'
                                                        );
                                                    }


                                                }
                                            });
                                        }

                                    });
                                }
                            </script>

                            <div class="floating">

                                <header>
                                    <div class="title">
                                    <h3>Available sections</h3>
                                    </div>
                                </header>

                                <form method="post">

                                    <main>
                                        <table class="a">
                                            <thead>
                                                <tr class="text-center"> 
                                                    <th rowspan="2">Section Id</th>
                                                    <th rowspan="2">Section Name</th>
                                                    <th rowspan="2">Student</th>
                                                    <th rowspan="2">Capacity</th>
                                                    <th rowspan="2">Term</th>
                                                    <th rowspan="2"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php

                                                    $course_level = $type === "SHS" ? 11 : ($type === "Tertiary" ? 1 : 0);

                                                    $active = "yes";

                                                    # Only Available now.
                                                    $sql = $con->prepare("SELECT * FROM course

                                                        WHERE program_id=:program_id
                                                        AND active=:active
                                                        AND school_year_term=:school_year_term
                                                        AND course_level=:course_level
                                                        ");

                                                    $sql->bindParam(":program_id", $program_id);
                                                    $sql->bindParam(":active", $active);
                                                    $sql->bindParam(":school_year_term", $current_school_year_term);
                                                    $sql->bindParam(":course_level", $course_level);

                                                    $sql->execute();
                                                
                                                    if($sql->rowCount() > 0){

                                                        while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                                            $course_id = $get_course['course_id'];

                                                            $program_section = $get_course['program_section'];
                                                            $capacity = $get_course['capacity'];
                                                            $school_year_term = $get_course['school_year_term'];

                                                            $section = new Section($con, $course_id);

                                                            $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);

                                                            $capacity = $section->GetSectionCapacity();

                                                            $program_id = $section->GetSectionProgramId($course_id);
                                                            $course_level = $section->GetSectionGradeLevel();

                                                            if($totalStudent == $capacity){

                                                            }
                                                            echo "
                                                            <tr class='text-center'>
                                                                <td>$course_id</td>
                                                                <td>$program_section</td>
                                                                <td>$totalStudent</td>
                                                                <td>$capacity</td>
                                                                <td>$school_year_term</td>
                                                                <td>
                                                                    <input name='selected_course_id' class='radio' value='$course_id' type='radio' " . (($totalStudent == $capacity) ? "disabled" : "") . ">
                                                                </td>
                                                            </tr>
                                                        ";
                                                        }
                                                        
                                                    }else{
                                                        echo "
                                                            <div class='col-md-12'>
                                                                <h4 class='text-center text-muted'>No currently available section for $program_acronym</h4>
                                                            </div>
                                                        ";
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                    </main>
                                    <div style="margin-top: 20px;" class="action">
                                        <button
                                        type="button"
                                            class="default large"
                                            onclick="window.location.href = 'process_enrollment.php?step1=true&id=<?php echo $pending_enrollees_id; ?>'">
                                            Return
                                        </button>
                                        <button
                                            class="default large success"
                                            name="pending_choose_section"
                                            type="submit">
                                            Proceed
                                        </button>
                                    </div>
                                </form>

                            </div>

                            
                        </main>
                    </div>
                <?php
                
            }
            else if(isset($_GET['subject_evaluation']) && $_GET['subject_evaluation'] == "show"){

                echo "subject_evaluation";
            }

        }
    }
?>