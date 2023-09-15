

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Program.php');

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
    $selected_student_subject_id = "";

    $hasClicked = false;
    
    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){

        

        $sy_id = $_POST['school_year_id'] ?? NULL;
        $selected_program_id = $_POST['program_id'] ?? NULL;

        $school_year = new SchoolYear($con, $sy_id);

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

        $sy_id = NULL;
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
                                    if($sy_id == $school_year_id){
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
                                        $selected = "selected";
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
                            <h4 id="clickSchedule">Class Module</h4>

                            <?php 
                                if($selected_course_id != "" 
                                    && $sy_id != "" 
                                    && $selected_program_id != ""
                                    && $selected_student_subject_id != ""){
                                    ?>
                                        <form  action='print_schedule.php' method='POST'>

                                            <input type="hidden" name="selected_sy_id" id="selected_sy_id" value="<?php echo $sy_id;?>">
                                            <input type="hidden" name="selected_program_id" id="selected_program_id" value="<?php echo $selected_program_id;?>">
                                            <input type="hidden" name="selected_course_id" id="selected_course_id" value="<?php echo $selected_course_id;?>">
                                        
                                            <button style="cursor: pointer;"
                                                type='submit' 
                                                
                                                href='#' name="print_schedule"
                                                class=' btn btn-primary'>
                                                <i class='bi bi-file-earmark-x'></i>&nbsp Print
                                            </button>
                                        </form>
                                    <?php
                                }
                            ?>

                        </div>
                    </header>

               
                   <table id="great_table_list" class="a" style="margin: 0">
                        <thead>
                            <tr class="text-center"> 
                                <th>ID</th>
                                <th>Section</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>A.Y</th>
                                <th>Period</th>
                                <th>Date Enrolled</th>
                            </tr>
                        </thead>
                        <!-- Your table body content here -->
                    </table>

                </main>
            </div>

        </main>
    </div>

<script>



    let storedProgramId;
    let storedSchoolYearId;
    
    // let hasClicked = false;

    $(document).ready(function() {

        var hasClicked = '<?php echo $hasClicked; ?>';

        
        var selected_sy_id = '<?php echo $sy_id; ?>';
        var selected_program_id = '<?php echo $selected_program_id; ?>';
        var selected_course_id = '<?php echo $selected_course_id; ?>';

        selected_sy_id = selected_sy_id.trim();
        selected_course_id = selected_course_id.trim();
        selected_program_id = selected_program_id.trim();

        var table = $('#great_table_list').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `classDataList.php?sy_id=${selected_sy_id}&p_id=${selected_program_id}&c_id=${selected_course_id}`,
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },
            'pageLength': 10,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available schedule.",
            },
            'columns': [
                { data: 'student_id', orderable: true },  
                { data: 'name', orderable: false },
                { data: 'program_section', orderable: false },
                { data: 'admission_status', orderable: false },  
                { data: 'ay', orderable: false },
                { data: 'period', orderable: false },
                { data: 'date_enrolled', orderable: false }
            ],
            'ordering': true
        });
    });

    $('#program_id').on('change', function() {

        var program_id = parseInt($(this).val());
        var chosen_school_year_id = parseInt($("#school_year_id").val());

        // localStorage.setItem('selected_program_id', program_id);
        // localStorage.setItem('selected_school_year_id', chosen_school_year_id);

        // console.log(storedSchoolYearId)
        // var stored_program_id = program_id;
        // var stored_chosen_school_year_id = chosen_school_year_id;

        // console.log(storedProgramId);
        // console.log(storedSchoolYearId);
        // console.log(chosen_school_year_id);

        $.ajax({
            url: '../../ajax/grade/get_program_section.php',
            type: 'POST',
            data: {
                 program_id,
                chosen_school_year_id
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


