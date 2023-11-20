 <?php 

    include_once('../../includes/cashier_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
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

            #waiting_payment_table_filter{
              margin-top: 12px;
              width: 100%;
              display: flex;
              flex-direction: row;
              justify-content: start;
            }

            #waiting_payment_table_filter input{
              width: 250px;
            }
        </style>

        <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

      </head>

    <?php

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];
 
 
    $selected_student_filter = "";
    $selected_payment_status_filter = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" 
        && isset($_POST["student_filter"])) {

        $selected_student_filters = $_POST["student_filter"];

        foreach ($selected_student_filters as $selected_filter) {
            // echo $selected_filter . "<br>";

            $selected_student_filter = $selected_filter;
        }
    }

    // echo "selected_student_filter: $selected_student_filter";
    // echo "<br>";

    if ($_SERVER["REQUEST_METHOD"] === "POST" 
        && isset($_POST["payment_status"])) {

        $selected_payment_statuss = $_POST["payment_status"];

        foreach ($selected_payment_statuss as $selected_filter) {
            // echo $selected_filter . "<br>";

            $selected_payment_status_filter = $selected_filter;
        }
    }
    // echo "selected_payment_status: $selected_payment_status_filter";
    // echo "<br>";




    $selected_program_id = "";
    $school_year_search = "";
    $selected_course_id = "";
    $selected_school_year_id = "";
    $selected_student_subject_id = "";
    $selected_course_level = "";

    $hasClicked = false;
    
    $get_period = NULL;
    $get_term = NULL;

    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['payment_history_btn'])){

        
        $selected_school_year_id = $_POST['school_year_id'] ?? NULL;
        $selected_program_id = $_POST['program_id'] ?? NULL;
        $selected_course_level = $_POST['course_level'] ?? NULL;

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
    
    // echo "selected_school_year_id: $selected_school_year_id";
    // echo "<br>";

    // echo "selected_course_level: $selected_course_level";
    // echo "<br>";

    // echo "selected_program_id: $selected_program_id";
    // echo "<br>";

    // echo "selected_course_id: $selected_course_id";
    // echo "<br>";

    if(isset($_POST['reset_btn'])){

        $selected_school_year_id = NULL;
        $selected_program_id = NULL;
        $selected_student_subject_id = NULL;
        $selected_course_level = NULL;
        
    }


?>



    <div class="content">

        <main>
            <div class="content-header">
                <header>
                    <div class="title">
                        <h2>Confirmed Payment History</em></h1>
                        
                    </div>

                   
                </header>
            </div>
    
            <main>


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
                                Academic Level

                                <select name="course_level" id="course_level" class="form-control">
                                </select>
                                <input type="hidden" id="course_level_value" name="course_level_value">
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

                                    <button type="submit" name="payment_history_btn" class="btn btn-primary">
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

                <div class="floating">

                    <table class="a">

                        <div class="action">
                            <form style="display: flex;" method="POST" id="student_filter_form">
                                <div style="margin-right: 15px;" class="form-group">
                                    <label for="new">Cash</label>
                                    <input type="checkbox" id="cash" name="student_filter[]"
                                        value="Cash" class="form-control" 
                                        onchange="handleCheckboxChange('cash')" 
                                        <?php if (isset($_POST["student_filter"]) && in_array("Cash", $_POST["student_filter"])) echo "checked"; ?>>
                                
                                </div>
                                <div class="form-group">
                                    <label for="old">Partial</label>
                                    <input type="checkbox" id="partial" name="student_filter[]"
                                        value="Partial" class="form-control" 
                                        onchange="handleCheckboxChange('partial')" 
                                        <?php if (isset($_POST["student_filter"]) && in_array("Partial", $_POST["student_filter"])) echo "checked"; ?>>
                                
                                </div>

                                <div style="margin-right: 15px;" class="form-group">
                                    <label for="new">Complete</label>
                                    <input type="checkbox" id="complete" name="payment_status[]"
                                        value="Complete" class="form-control" 
                                        onchange="handleCheckboxChange('complete')" 
                                        <?php if (isset($_POST["payment_status"]) && in_array("Complete", $_POST["payment_status"])) echo "checked"; ?>>
                                
                                </div>
                                <div class="form-group">
                                    <label for="old">Incomplete</label>
                                    <input type="checkbox" id="incomplete" name="payment_status[]"
                                        value="Incomplete" class="form-control" 
                                        onchange="handleCheckboxChange('incomplete')" 
                                        <?php if (isset($_POST["payment_status"]) && in_array("Incomplete", $_POST["payment_status"])) echo "checked"; ?>>
                                
                                </div>

                            </form>
                        </div>

                        <tr>
                            <th style="border-right: 2px solid black">Search by</th>
                            <td><button>Name</button></td>
                            <td><button>Email</button></td>
                            <td><button>Student ID</button></td>
                        </tr>
                    </table>
                    
                    <table style="width: 100%" id="cashier_history_table" class="a">
                        <thead>
                            <tr>
                                <th>Form ID</th>  
                                <th>Student No</th>  
                                <th>Name</th>
                                <th>Term - Semester</th>
                                <th>Section</th>
                                <th>Status</th>
                                <th>Method</th>
                                <th>Date Confirmed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>

                </div>

            </main>
        </main>
    </div>

<?php include_once('../../includes/footer.php') ?>

<script>

    function submitForm() {
        document.getElementById("student_filter_form").submit();
    }

    function handleCheckboxChange(checkboxId) {

        if (checkboxId == "cash") {
            if (document.getElementById("partial").checked) {
                document.getElementById("partial").checked = false;
            }
        } else if (checkboxId == "partial") {
            if (document.getElementById("cash").checked) {
                document.getElementById("cash").checked = false;
            }
        }

        if (checkboxId == "complete") {
            if (document.getElementById("incomplete").checked) {
                document.getElementById("incomplete").checked = false;
            }
        } else if (checkboxId == "incomplete") {
            if (document.getElementById("complete").checked) {
                document.getElementById("complete").checked = false;
            }
        }

        document.getElementById("student_filter_form").submit();
    }

    let program_id_value = null;

    $('#program_id').on('change', function() {

        let program_id = parseInt($(this).val());
        // var chosen_school_year_id = parseInt($("#school_year_id").val());

        program_id_value = program_id;
        
        // console.log("program_id_value "  +  program_id_value)
        // console.log("program_id " + program_id)
        
        $.ajax({
            url: '../../ajax/classlist/populate_academic_level_by_program.php',
            type: 'POST',
            data: {
                program_id
                // chosen_school_year_id
            },
            dataType: 'json',

            success: function(response) {

                var options = '<option value="">Choose Level</option>';

                $.each(response, function(index, value) {

                    if(value.level > 5){
                        options += '<option value="' + value.level + '">Grade ' + value.level +'</option>';
                    }

                    else if(value.level <= 4){
                        var yearLabel;
                        switch (value.level) {
                            case "1":
                                yearLabel = "1st year";
                                break;
                            case "2":
                                yearLabel = "2nd year";
                                break;
                            case "3":
                                yearLabel = "3rd year";
                                break;
                            case "4":
                                yearLabel = "4th year";
                                break;
                            default:
                                yearLabel = value.level + "th year";
                        }
                        options += '<option value="' + value.level + '">' + yearLabel + '</option>';
                    }
                });

                $('#course_level').html(options);
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

    $('#course_level').on('change', function() {


        let course_level_value = parseInt($(this).val());

        let program_id = program_id_value;

        // console.log("program_id "  +  program_id)
        // console.log("course_level "  +  course_level_value)
        
        // var program_id = parseInt($(this).val());
        var chosen_school_year_id = parseInt($("#school_year_id").val());

        $.ajax({
            url: '../../ajax/grade/get_program_section.php',
            type: 'POST',
            data: {
                program_id,
                chosen_school_year_id,
                course_level_value,
                type: "class_list_per_section"

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


    $(document).ready(function() {

        var selected_student_filter = `
            <?php echo $selected_student_filter; ?>
        `;

        selected_student_filter = selected_student_filter.trim();

        var selected_payment_status_filter = `
            <?php echo $selected_payment_status_filter; ?>
        `;

        selected_payment_status_filter = selected_payment_status_filter.trim();


        var school_year_id_filter = `
            <?php echo $selected_school_year_id; ?>
        `;

        school_year_id_filter = school_year_id_filter.trim();

        var program_id_filter = `
            <?php echo $selected_program_id; ?>
        `;

        program_id_filter = program_id_filter.trim();

        var course_level_filter = `
            <?php echo $selected_course_level; ?>
        `;

        course_level_filter = course_level_filter.trim();


        var course_id_filter = `
            <?php echo $selected_course_id; ?>
        `;

        course_id_filter = course_id_filter.trim();


        var table = $('#cashier_history_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `historyPaymentListData.php?payment_method_filter=${selected_student_filter}&payment_status_filter=${selected_payment_status_filter}&school_year_id_filter=${school_year_id_filter}&program_id_filter=${program_id_filter}&course_level_filter=${course_level_filter}&course_id_filter=${course_id_filter}`,
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },

            'pageLength': 5,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available data for payment history.",
            },
            'columns': [
                { data: 'enrollment_form_id', orderable: false },  
                { data: 'student_no', orderable: false },  
                { data: 'name', orderable: false },
                { data: 'term_semester', orderable: false },
                { data: 'section', orderable: false },  
                { data: 'status', orderable: false },  
                { data: 'method', orderable: false },  
                { data: 'cashier_confirmation_date', orderable: true },
                { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });
    });

</script>
