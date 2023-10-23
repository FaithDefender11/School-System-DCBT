

<?php

use Random\Engine\Secure;

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');

    ?>
        <head>
            <style>
                .show_search{
                    position: relative;
                    /* margin-top: -38px;
                    margin-left: 215px; */
                }
                div.dataTables_length {
                    display: none;
                }

                #evaluation_table_filter{
                margin-top: 15px;
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: start;
                margin-bottom: 7px;
                }

                #evaluation_table_filter input{
                width: 250px;
                }

            </style>

            <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        </head>
    <?php

    $schedule = new Schedule($con);

    $sy_id = "";
    $selected_program_id = "";
    $school_year_search = "";
    $selected_course_id = "";
    $selected_school_year_id = "";
    $selected_student_subject_id = "";

    $hasClicked = false;
    
    $get_period = NULL;
    $get_term = NULL;

    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){

        

        $selected_school_year_id = $_POST['school_year_id'] ?? NULL;
        $selected_program_id = $_POST['program_id'] ?? NULL;

        $school_year = new SchoolYear($con, $selected_school_year_id);

        $get_term = $school_year->GetTerm();
        $get_period = $school_year->GetPeriod();


        $program = new Program($con, $selected_program_id);
        $program_name = $program->GetProgramName();


        $selected_course_id = $_POST['course_id'] ?? NULL;
        $selected_student_subject_id = $_POST['student_subject_id'] ?? NULL;

        $hasClicked = true;
        // $redirectUrl = "enrollmentListData.php?sy_id=$sy_id&p_id=$selected_program_id&c_id=$selected_course_id";
        // header("Location: $redirectUrl");
    }
    // echo $selected_student_subject_id;

    if(isset($_POST['reset_btn'])){

        $selected_school_year_id = NULL;
        $selected_program_id = NULL;
        $selected_student_subject_id = NULL;
    }


?>

    <div class="col-lg-12">

        <form method="POST">
            <div class="row invoice-info">
               
                <div class="col-sm-3 invoice-col">
                    Academic Year
                    <select name="school_year_id" id="school_year_id" class="form-control">
                        <?php 
                            $query = $con->prepare("SELECT t1.*
                                FROM school_year AS t1
                            ");

                            // $query->bindParam(":condition2", $Tertiary);
                            $query->execute();
                            if($query->rowCount() > 0){

                                echo "
                                    <option value='' selected>Select Term</option>
                                ";

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $term = $row['term'];
                                    $period = $row['period'];
                                    $school_year_id = $row['school_year_id'];

                                    $selected = "";
                                    if($selected_school_year_id == $school_year_id){
                                        $selected = "selected";
                                    }
                                    echo "
                                        <option $selected value='$school_year_id'>$term $period Semester</option>
                                    ";
                                }
                            }
                        ?>
                    </select>

                </div>

                <div class="col-sm-3 invoice-col">
                    Program(s)

                    <select name="program_id" id="program_id" class="form-control">
                        <?php 
                            $query = $con->prepare("SELECT t1.*

                                FROM program AS t1
                            ");

                            // $query->bindParam(":condition2", $Tertiary);
                            $query->execute();
                            if($query->rowCount() > 0){

                                echo "
                                    <option value='' selected>Select</option>
                                ";

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $program_name = $row['program_name'];
                                    $acronym = $row['acronym'];
                                    $program_id = $row['program_id'];

                                    $selected = "";
                                    if($selected_program_id == $program_id){
                                        // $selected = "selected";
                                    }
                                    echo "
                                        <option $selected value='$program_id'>$acronym</option>
                                    ";
                                }
                            }
                        ?>
                    </select>
                </div>

                <div class="col-sm-3 invoice-col">
                    Program - Section
                        <select name="course_id" id="course_id"  class="form-control">
                            <?php 

                                if($selected_course_id != "") {
                                    $query = $con->prepare("SELECT t1.*

                                    FROM course AS t1
                                    WHERE t1.course_id=:course_id
                                    ");

                                    $query->bindParam(":course_id", $selected_course_id);
                                    $query->execute();

                                    if($query->rowCount() > 0){

                                        $row = $query->fetch(PDO::FETCH_ASSOC);

                                        $program_section = $row['program_section'];
                                        // $acronym = $row['acronym'];
                                        $course_id = $row['course_id'];

                                        $selected = "";
                                        if($selected_course_id == $course_id){
                                            $selected = "selected";
                                        }
                                        echo "
                                            <option $selected value='$course_id'>$program_section</option>
                                        ";
                                    }   
                                }
                                
                            ?>
                        </select>
                </div>
 

                <div class="col-sm-0 invoice-col"> 
                    <br>
                    <div class="form-group"> 

                        <!-- <input type="hidden" id="student_subject_grade_id"> -->

                        <button type="submit" name="schedule_btn2" class="btn btn-primary">
                            <i class="fas fa-search fa-1x"></i>
                        </button>
                    </div>
                </div>

                <div class="col-sm-0 invoice-col"> 
                    <br>
                    <div class="form-group"> 
                        <button type="submit" name="reset_btn" class="btn btn-outline-primary">
                            <i class="fas fa-undo"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="content">

            <div class="floating">
                <main>
                    <header>

                        <div class="title">

                            <div class="row col-md-12">
                                
                                <div class="col-md-6">
                                    <h4 style="margin-bottom: 13px;" id="clickSchedule">Student list by Section</h4>
                                </div>

                                <div class="text-right col-md-6">
                                
                                    <?php 
                                        if($selected_school_year_id != "" 
                                            && ($selected_program_id != "" 
                                                || $selected_course_id != "")){

                                            ?>
                                                <form action='print_studentlist_by_section.php' 
                                                    method='POST'>

                                                    <input type="hidden" name="selected_school_year_id" id="selected_school_year_id" value="<?php echo $selected_school_year_id;?>">
                                                    <input type="hidden" name="selected_program_id" id="selected_program_id" value="<?php echo $selected_program_id;?>">
                                                    <input type="hidden" name="selected_course_id" id="selected_course_id" value="<?php echo $selected_course_id;?>">
                                                
                                                    <button title="Export as pdf" style="cursor: pointer;"
                                                        type='submit' 
                                                        
                                                        href='#' name="print_studentlist_by_section"
                                                        class='btn-sm btn btn-primary'>
                                                        <i class='bi bi-file-earmark-x'></i>&nbsp Print
                                                    </button>

                                                    <button style="cursor: pointer;"
                                                        type='submit' 
                                                        title="Export as excel"
                                                        href='#' name="print_excel"
                                                        class='btn-sm btn btn-success'>
                                                        <i class='bi bi-file-earmark-x'></i>&nbsp Excel
                                                    </button>

                                                </form>
                                            <?php
                                        }
                                    ?>

                                </div>

                            </div>
                            
                            

                        </div>
                        
                    </header>

                    <?php if($selected_program_id !== NULL):?>

                        <?php 

                            // var_dump($selected_course_id); 

                            $course_query = "";
                            
                            if($selected_course_id != ""){
                                $course_query = "AND t1.course_id = :course_id";
                            }

                            $get = $con->prepare("SELECT 

                                t1.student_subject_id,
                                t1.course_id,
                                t3.program_section
                                
                                FROM student_subject as t1

                                -- INNER JOIN student_subject as t2 ON t2.student_subject_id = t1.student_subject_id
                                INNER JOIN course as t3 ON t3.course_id = t1.course_id

                                AND t3.program_id=:program_id
                                AND t1.is_final = 1
                                AND t1.school_year_id=:school_year_id
                                $course_query

                                GROUP BY t1.course_id
                            ");

                            $get->bindValue(":program_id", $selected_program_id);
                            $get->bindValue(":school_year_id", $selected_school_year_id);
                            
                            if($selected_course_id != ""){
                                $get->bindValue(":course_id", $selected_course_id);
                            }
                            
                            $get->execute();

                            if($get->rowCount() > 0){

                                $sectionsByProgramList = $get->fetchAll(PDO::FETCH_ASSOC);

                              

                                foreach ($sectionsByProgramList as $key => $value) {

                                    # code...

                                    $enrolled_course_id = $value['course_id'];
                                    $section = new Section($con, $enrolled_course_id);

                                    $sectionName = $section->GetSectionName();
                                    $enrolled_course_level = $section->GetSectionGradeLevel();
                                    $enrolled_course_capacity = $section->GetSectionCapacity();
                                    $enrolled_course_program_id = $section->GetSectionProgramId($enrolled_course_id);

                                    ?>

                                        <em style="margin-bottom: 28px;" >Class section &nbsp; &nbsp; &nbsp; </em> <span style="font-weight: bold;"><?php
                                            echo "$sectionName <br>";
                                        ?></span>


                                        <table id="" class="a" style="margin-bottom: 0px; margin-top:15px;">
                                        
                                            <thead>

                                                <tr class="text-center"> 
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Gender</th>
                                                    <th>Contact No</th>
                                                    <th>Civil Status</th>
                                                    <th>Program</th>
                                                    <th>Level</th>
                                                    <th>Status</th>
                                                </tr>

                                            </thead>
                                            
                                            <tbody>
                                                <?php 
                                                
                                                    $query = $con->prepare("SELECT 
                                                    
                                                        t3.firstname,
                                                        t3.lastname,
                                                        t3.student_unique_id,
                                                        t3.admission_status,
                                                        t3.sex,
                                                        t3.contact_number,
                                                        t3.course_level,
                                                        
                                                        t3.civil_status,

                                                        t4.program_section,

                                                        t5.acronym
                                                    
                                                        
                                                        FROM student as t3 
                                                    
                                                        LEFT JOIN course as t4  ON t4.course_id = t3.course_id
                                                        LEFT JOIN program as t5  ON t5.program_id = t4.program_id
                                                        
                                                        WHERE t3.course_id = :course_id
                                                        -- AND t2.is_final = :is_final
                                                        
                                                    ");

                                                    # SPECIFIC Course ID selection.

                                                    $query->bindValue(":course_id", $enrolled_course_id);

                                                    $query->execute();

                                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                        $firstname = trim($row['firstname']);
                                                        $lastname = trim($row['lastname']);

                                                        $student_unique_id = trim($row['student_unique_id']);
                                                        $contact_number = trim($row['contact_number']);
                                                        $sex = trim($row['sex']);
                                                        $program_section = trim($row['program_section']);
                                                        $course_level = trim($row['course_level']);
                                                    
                                                        $civil_status = trim($row['civil_status']);
                                                        $admission_status = trim($row['admission_status']);
                                                        
                                                        $acronym = trim($row['acronym']);

                                                        $fullname = ucwords($firstname) . " " . ucwords($lastname);

                                                        echo "
                                                            <tr>
                                                                <td>$student_unique_id</td>
                                                                <td>$fullname</td>
                                                                <td>$sex</td>
                                                                <td>$contact_number</td>
                                                                <td>$civil_status</td>
                                                                <td>$acronym</td>
                                                                <td>$course_level</td>
                                                                <td>$admission_status</td>
                                                                
                                                            </tr>
                                                        ";
                                                    }

                                                ?>
                                            </tbody>
                                    
                                        </table>

                                        <br>
                                        <br>
                                    <?php
                                }

                            }
                        ?>


                        

                    <?php endif;?>
                </main>
            </div>

        </main>
    </div>

<script>



$('#program_id').on('change', function() {

    var program_id = parseInt($(this).val());
    var chosen_school_year_id = parseInt($("#school_year_id").val());

    $.ajax({
        url: '../../ajax/grade/get_program_section.php',
        type: 'POST',
        data: {
            program_id,
            chosen_school_year_id,
            type: "student_per_section"
        },
        dataType: 'json',

        success: function(response) {

            // response = response.trim();

            console.log(response);

            if(response.length > 0){
                var options = '<option selected value="">Available Sections</option>';
                
                $.each(response, function (index, value) {
                    options +=
                    '<option value="' + value.course_id + '">' + value.program_section + '</option>';
                });

                $('#course_id').html(options);
                // $('#student_subject_id').val(options);
                
            }else{
                $('#course_id').html('<option selected value="">No data found(s).</option>');

            }
        },
        'error': function(xhr, status, error) {
            // Handle error response here
            console.error('Error:', error);
            console.log('Status:', status);
            console.log('Response Text:', xhr.responseText);
            console.log('Response Code:', xhr.status);
        }
    });

});
 

</script>


