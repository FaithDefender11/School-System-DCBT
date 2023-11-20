<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Student.php');


    $sy_id = "";
    $selected_program_id = "";
    $school_year_search = "";
    $selected_course_id = "";

    $student = new Student($con);

    $birthday = '1999-07-26';
    $lastName = 'Sirios';

    // Generate the password using the function
    $password = $student->GenerateEnrolleePassword($birthday, $lastName);

    // // Output the password
    // echo $password;

    
    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['enrollment_btn_search'])
        
        ){

        $sy_id = $_POST['school_year_id'] ?? NULL;
        $selected_program_id = $_POST['program_id'] ?? NULL;
        $selected_course_id = $_POST['course_id'] ?? NULL;
    }

    // echo $sy_id;
    // echo $selected_program_id;
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
            .dropdown-menu.show{
            margin-left: -170px;
        }
        </style>
        <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    </head>

    <div class="col-md-12">

        <form method="POST">
            <div class="row invoice-info">
                <div class="col-sm-3 invoice-col">
                    Academic Year - Period
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
                    Offered Program

                    <select name="program_id" id="program_id" class="form-control">
                        <?php 
                            $query = $con->prepare("SELECT t1.*

                                FROM program AS t1
                            ");

                            // $query->bindParam(":condition2", $Tertiary);
                            $query->execute();
                            if($query->rowCount() > 0){

                                echo "
                                    <option value='' selected>Choose Program</option>
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
                        <button type="submit" name="enrollment_btn_search" class="btn btn-primary">
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

        <main>

            <div class="floating">

                <div class="action">

                        <div class='dropdown'>

                            <button class='icon'>
                                <i class='bi bi-three-dots-vertical'></i>
                            </button>

                            <div class='dropdown-menu'>
 
                                <a href='manual_create.php' class='dropdown-item' style='color: black'>
                                    <i class='fas fa-plus'></i>
                                    Process form
                                </a>

                                <a href='rejected_enrollees.php' class='dropdown-item' style='color: yellowgreen'>
                                    <i class='fas fa-eye'></i>
                                    Previous enrollees
                                </a>

                            </div>

                        </div>
    
                  

                </div>

                <!-- <div class="title">
                    <button onclick="window.location.href = 'manual_create.php' " class="btn btn-success">Enroll Here</button>
                    </div>
                -->
                
                <!-- <div class="title">
                    <button onclick="window.location.href = 'rejected_enrollees.php' " class="btn btn-info">Rejected Enrollee</button>
                </div>  -->

                <div class="filters">
                    <table>
                        <tr>
                            <th rowspan="2" style="border-right: 2px solid black">
                                Search by
                            </th>
                            <th><button>Enrollment ID</button></th>
                            <th><button>Name</button></th>
                            <th><button>Section</button></th>
                            <th><button>A.Y</button></th>
                        </tr>
                    </table>
                </div>

                <header>
                    <!-- <div class="title">
                        <h3 style="font-weight: bold;">Enrollment List</h3>
                    </div> -->

                    
                
                </header>

                <main>
                    <table style="width: 100%" id="enrollment_table" class="a">
                        <thead>
                            <tr>
                                <th>Enrollment ID</th>  
                                <th>Name</th>
                                <th>Section</th>  
                                <th>A.Y</th>  
                                <th>Period</th>  
                                <th>Status</th>  
                                <th>Action</th>  
                            </tr>
                        </thead>
                    </table>
                </main>

                
        </main>
    </div>

<script>

    $('#program_id').on('change', function() {

        var program_id = parseInt($(this).val());
        var chosen_school_year_id = parseInt($("#school_year_id").val());

        $.ajax({
            url: '../../ajax/enrollment/get_enrollment_program_section.php',
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
                }else{
                    $('#course_id').html('<option selected value="">No data found(s).</option>');

                }
            }
        });

    });

    $(document).ready(function() {

        var selected_sy_id = `
            <?php echo $sy_id; ?>
        `;

        var selected_program_id = `
            <?php echo $selected_program_id; ?>
        `;

        var selected_course_id = `
            <?php echo $selected_course_id; ?>
        `;

        selected_sy_id = selected_sy_id.trim();
        selected_program_id = selected_program_id.trim();


        var table = $('#enrollment_table').DataTable({
            //
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `enrollmentListData.php?sy_id=${selected_sy_id}&p_id=${selected_program_id}&c_id=${selected_course_id}`,
                // 'success': function(data) {
                //   // Handle success response here
                //   console.log('Success:', data);
                // },
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },

            'pageLength': 15,

            'columns': [
                { data: 'enrollment_form_id', orderable: true },
                { data: 'name' , orderable: false },
                { data: 'program_section', orderable: false},
                { data: 'term', orderable: false},
                { data: 'period', orderable: false},
                { data: 'enrollment_status', orderable: false},
                { data: 'view_button', orderable: false}
            ],
            'ordering': true
        });
     
    });

var dropBtns = document.querySelectorAll(".icon");

    dropBtns.forEach(btn => {
        btn.addEventListener("click", (e) => {
            const dropMenu = e.currentTarget.nextElementSibling;
            if (dropMenu.classList.contains("show")) {
                dropMenu.classList.toggle("show");
            } else {
                document.querySelectorAll(".dropdown-menu").forEach(item => item.classList.remove("show"));
                dropMenu.classList.add("show");
            }
        });
    });

</script>



<?php include_once('../../includes/footer.php') ?>
