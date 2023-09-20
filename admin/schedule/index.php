

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
        <style>
            table{
                min-width: 100%;
                overflow-y: auto;
            }
        </style>

    <?php

    $schedule = new Schedule($con);

    $sy_id = "";
    $selected_program_id = "";
    $school_year_search = "";
    $selected_course_id = "";
    
    if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['schedule_btn2'])){

        $sy_id = $_POST['school_year_id'] ?? NULL;
        $selected_program_id = $_POST['program_id'] ?? NULL;

        $school_year = new SchoolYear($con, $sy_id);

        $get_term = $school_year->GetTerm();
        $get_period = $school_year->GetPeriod();

                <div class="schedule-table" id="table-ict-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-ict-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-ict-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-ict-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

        $program = new Program($con, $selected_program_id);
        $program_name = $program->GetProgramName();


        $selected_course_id = $_POST['course_id'] ?? NULL;

        // $redirectUrl = "enrollmentListData.php?sy_id=$sy_id&p_id=$selected_program_id&c_id=$selected_course_id";
        // header("Location: $redirectUrl");
    }

    if(isset($_POST['reset_btn'])){
        $sy_id = NULL;
        $selected_program_id = NULL;
    }
    
?>

    

    <div class="col-md-12">

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
        <main>
           
            <div style="display: none;" class="floating">
                <header>
                    <div class="title">
                        <h4 id="clickSchedule">Schedule</h4>
                    </div>
                
                   
                </header>
                <main >

                    <table class="a" style="margin: 0">
                        <thead>
                            <tr>
                                <th>Program - Section</th>
                                <th>Subject Code</th>
                                <th>Term</th>
                                <th>Period</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            
                                $query = $con->prepare("SELECT 
                                    t1.*, t2.program_section, t2.school_year_term,
                                    t3.term, t3.period

                                    FROM subject_schedule AS t1

                                    INNER JOIN course AS t2 ON t2.course_id = t1.course_id
                                    INNER JOIN school_year AS t3 ON t3.school_year_id = t1.school_year_id


                                    ORDER BY t2.program_section,
                                    t3.term DESC,
                                    t3.period ASC
                                ");

                                // $query->bindParam(":condition2", $Tertiary);
                                $query->execute();

                                if($query->rowCount() > 0){

                                    while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                        $subject_code = $row['subject_code'];
                                        $schedule_day = $row['schedule_day'];
                                        $schedule_time = $row['schedule_time'];
                                        $program_section = $row['program_section'];
                                        $school_year_term = $row['school_year_term'];
                                        $term = $row['term'];
                                        $period = $row['period'];

                                        $removeDepartmentBtn= "";

                                        $days = $schedule->convertToDays($schedule_day);

                                        echo "
                                        <tr>
                                            <td>$program_section</td>
                                            <td>$subject_code</td>
                                            <td>$school_year_term</td>
                                            <td>$period</td>
                                            <td>$schedule_day</td>
                                            <td>$schedule_time</td>
                                            <td></td>
                                        </tr>
                                        ";
                                    }
                                }

                            ?>
                        </tbody>
                    </table>
                </main>

            </div>

            <div class="floating">
                <main>
                    <header>
                        <div class="title">
                            <h4 id="clickSchedule">Schedule</h4>

                            <p>
                                <?php 
                                    if($school_year_search !== ""){
                                        // echo "Results: $school_year_search";
                                    }
                                ?>
                            </p>

                            <?php 
                                if($selected_course_id != "" && $sy_id != "" && $selected_program_id != ""){
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

                         <div class="action">
                        <button onclick="window.location.href= 'create.php' " class="default clean">
                            <i class="fas fa-plus"></i>
                        SCHEDULE</button>
                    </div>
                    </header>
                        

                    <table id="schedule_table_list" class="a" style="margin: 0">
                        <thead>
                            <tr >
                                <th>Program - Section</th>
                                <th>Subject Code</th>
                                <th>A.Y</th>
                                <th>Period</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                                <th>Type</th>
                                <th>Instructor</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </main>
            </div>

        </main>
    </div>

<script>


    $('#program_id').on('change', function() {

        var program_id = parseInt($(this).val());
        var chosen_school_year_id = parseInt($("#school_year_id").val());

        $.ajax({
            url: '../../ajax/schedule/get_schedule_program_section.php',
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
        console.log('Document is ready'); 

        $("#clickSectionRedirect").click(function(e) {
                e.preventDefault(); // Prevent the default link behavior
                console.log('Link clicked'); // Check if the click event is captured
        });

        $("#clickSchedule").on('click', function(){
            e.preventDefault(); // Prevent the default link behavior
                console.log('Link clicked');
        });
  
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

        var table = $('#schedule_table_list').DataTable({

            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `scheduleDataList.php?sy_id=${selected_sy_id}&p_id=${selected_program_id}&c_id=${selected_course_id}`,
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },

            'pageLength': 15,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available schedule.",
            },
            'columns': [
                { data: 'program_section', orderable: false },  
                { data: 'subject_code', orderable: true },  
                { data: 'term', orderable: false },  
                { data: 'period', orderable: false },  
                { data: 'day', orderable: false }, 
                { data: 'time', orderable: false },  
                { data: 'room', orderable: false },
                { data: 'type', orderable: false },
                { data: 'instructor', orderable: false },
                { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });

    });

</script>


                <div class="schedule-table" id="table-abm-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-abm-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-abm-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-abm-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-ia-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-ia-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-ia-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-ia-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-he-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-he-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-he-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-he-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </main>
        </div>

        <div class="floating" id="shs-schedule-overview">
          <header>
            <div class="title">
              <h3>Schedule Overview <em>SHS</em></h3>
            </div>
            <div class="action">
              <div class="dropdown">
                <button class="icon">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item"
                    ><i class="bi bi-printer"></i>Print Schedule</a
                  >
                </div>
              </div>
            </div>
          </header>
          <div class="tabs">
            <button class="tab" id="shs-room">
              <i class="bi bi-clipboard-check"></i>
              Room
            </button>
            <button class="tab" id="shs-section">
              <i class="bi bi-journal-arrow-down"></i>
              Section
            </button>
          </div>

          <div class="action" id="shs-room-tab">
            <div class="input" id="shs-room-select">
              <p>Room</p>
              <select name="Room">
                <option value="">*insert room*</option>
              </select>
            </div>
          </div>

          <div class="action" id="shs-section-tab">
            <div class="input" id="shs-section-select">
              <p>Section</p>
              <select name="Room">
                <option value="">*insert section*</option>
              </select>
            </div>
          </div>

          <main id="shs-room-table">
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>Monday</th>
                  <th>Tuesday</th>
                  <th>Wednesday</th>
                  <th>Thursday</th>
                  <th>Friday</th>
                  <th>Saturday</th>
                </tr>
              </thead>
              <tbody class="timestamp">
                <tr>
                  <td>7:00am</td>
                  <td>mmw</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </main>

          <main id="shs-section-table">
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>Monday</th>
                  <th>Tuesday</th>
                  <th>Wednesday</th>
                  <th>Thursday</th>
                  <th>Friday</th>
                  <th>Saturday</th>
                </tr>
              </thead>
              <tbody class="timestamp">
                <tr>
                  <td>7:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>

        <!--COLLEGE SCHEDULE-->
        <div class="floating" id="college-scheduler">
          <header>
            <div class="title">
              <h3>Scheduler <em>College *insert school year*</em></h3>
            </div>
          </header>
          <main>
            <div class="scheduler">
              <nav>
                <a class="drop bcm" id="BCM">
                  <span class="span-toggle" id="bcm-span">BCM</span>
                  <label for="1st Year">1st Year</label>
                  <input
                    type="button"
                    id="section-bcm-1"
                    value="Section1"
                    onclick="toggleTable('table-bcm-1', 'section-bcm-1', 'bcm-span')"
                  />
                  <input
                    type="button"
                    id="section-bcm-2"
                    value="Section2"
                    onclick="toggleTable('table-bcm-2', 'section-bcm-2', 'bcm-span')"
                  />
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-bcm-3"
                    value="Section1"
                    onclick="toggleTable('table-bcm-3', 'section-bcm-3', 'bcm-span')"
                  />
                  <input
                    type="button"
                    id="section-bcm-4"
                    value="Section2"
                    onclick="toggleTable('table-bcm-4', 'section-bcm-4', 'bcm-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-bcm-5"
                    value="Section1"
                    onclick="toggleTable('table-bcm-5', 'section-bcm-5', 'bcm-span')"
                  />
                  <input
                    type="button"
                    id="section-bcm-6"
                    value="Section2"
                    onclick="toggleTable('table-bcm-6', 'section-bcm-6', 'bcm-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-bcm-7"
                    value="Section1"
                    onclick="toggleTable('table-bcm-7', 'section-bcm-7', 'bcm-span')"
                  />
                  <input
                    type="button"
                    id="section-bcm-8"
                    value="Section2"
                    onclick="toggleTable('table-bcm-8', 'section-bcm-8', 'bcm-span')"
                  />
                </a>
                <a class="drop bpe" id="BPE">
                  <span class="span-toggle" id="bpe-span">BPE</span>
                  <label for="1st Year">1st Year</label>
                  <input
                    type="button"
                    id="section-bpe-1"
                    value="Section1"
                    onclick="toggleTable('table-bpe-1', 'section-bpe-1', 'bpe-span')"
                  />
                  <input
                    type="button"
                    id="section-bpe-2"
                    value="Section2"
                    onclick="toggleTable('table-bpe-2', 'section-bpe-2', 'bpe-span')"
                  />
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-bpe-3"
                    value="Section1"
                    onclick="toggleTable('table-bpe-3', 'section-bpe-3', 'bpe-span')"
                  />
                  <input
                    type="button"
                    id="section-bpe-4"
                    value="Section2"
                    onclick="toggleTable('table-bpe-4', 'section-bpe-4', 'bpe-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-bpe-5"
                    value="Section1"
                    onclick="toggleTable('table-bpe-5', 'section-bpe-5', 'bpe-span')"
                  />
                  <input
                    type="button"
                    id="section-bpe-6"
                    value="Section2"
                    onclick="toggleTable('table-bpe-6', 'section-bpe-6', 'bpe-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-bpe-7"
                    value="Section1"
                    onclick="toggleTable('table-bpe-7', 'section-bpe-7', 'bpe-span')"
                  />
                  <input
                    type="button"
                    id="section-bpe-8"
                    value="Section2"
                    onclick="toggleTable('table-bpe-8', 'section-bpe-8', 'bpe-span')"
                  />
                </a>
                <a class="drop bae" id="BAE">
                  <span class="span-toggle" id="bae-span">BAE</span>
                  <label for="1st Year">1st Year</label>
                  <input
                    type="button"
                    id="section-bae-1"
                    value="Section1"
                    onclick="toggleTable('table-bae-1', 'section-bae-1', 'bae-span')"
                  />
                  <input
                    type="button"
                    id="section-bae-2"
                    value="Section2"
                    onclick="toggleTable('table-bae-2', 'section-bae-2', 'bae-span')"
                  />
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-bae-3"
                    value="Section1"
                    onclick="toggleTable('table-bae-3', 'section-bae-3', 'bae-span')"
                  />
                  <input
                    type="button"
                    id="section-bae-4"
                    value="Section2"
                    onclick="toggleTable('table-bae-4', 'section-bae-4', 'bae-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-bae-5"
                    value="Section1"
                    onclick="toggleTable('table-bae-5', 'section-bae-5', 'bae-span')"
                  />
                  <input
                    type="button"
                    id="section-bae-6"
                    value="Section2"
                    onclick="toggleTable('table-bae-6', 'section-bae-6', 'bae-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-bae-7"
                    value="Section1"
                    onclick="toggleTable('table-bae-7', 'section-bae-7', 'bae-span')"
                  />
                  <input
                    type="button"
                    id="section-bae-8"
                    value="Section2"
                    onclick="toggleTable('table-bae-8', 'section-bae-8', 'bae-span')"
                  />
                </a>
                <a class="drop bse" id="BSE">
                  <span class="span-toggle" id="bse-span">BSE</span>
                  <label for="1st Year">1st Year</label>
                  <input
                    type="button"
                    id="section-bse-1"
                    value="Section1"
                    onclick="toggleTable('table-bse-1', 'section-bse-1', 'bse-span')"
                  />
                  <input
                    type="button"
                    id="section-bse-2"
                    value="Section2"
                    onclick="toggleTable('table-bse-2', 'section-bse-2', 'bse-span')"
                  />
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-bse-3"
                    value="Section1"
                    onclick="toggleTable('table-bse-3', 'section-bse-3', 'bse-span')"
                  />
                  <input
                    type="button"
                    id="section-bse-4"
                    value="Section2"
                    onclick="toggleTable('table-bse-4', 'section-bse-4', 'bse-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-bse-5"
                    value="Section1"
                    onclick="toggleTable('table-bse-5', 'section-bse-5', 'bse-span')"
                  />
                  <input
                    type="button"
                    id="section-bse-6"
                    value="Section2"
                    onclick="toggleTable('table-bse-6', 'section-bse-6', 'bse-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-bse-7"
                    value="Section1"
                    onclick="toggleTable('table-bse-7', 'section-bse-7', 'bse-span')"
                  />
                  <input
                    type="button"
                    id="section-bse-8"
                    value="Section2"
                    onclick="toggleTable('table-bse-8', 'section-bse-8', 'bse-span')"
                  />
                </a>
                <a class="drop btte" id="BTTE">
                  <span class="span-toggle" id="btte-span">BTTE</span>
                  <label for="1st Year">1st Year</label>
                  <input
                    type="button"
                    id="section-btte-1"
                    value="Section1"
                    onclick="toggleTable('table-btte-1', 'section-btte-1', 'btte-span')"
                  />
                  <input
                    type="button"
                    id="section-btte-2"
                    value="Section2"
                    onclick="toggleTable('table-btte-2', 'section-btte-2', 'btte-span')"
                  />
                  <label for="2nd Year">2nd Year</label>
                  <input
                    type="button"
                    id="section-btte-3"
                    value="Section1"
                    onclick="toggleTable('table-btte-3', 'section-btte-3', 'btte-span')"
                  />
                  <input
                    type="button"
                    id="section-btte-4"
                    value="Section2"
                    onclick="toggleTable('table-btte-4', 'section-btte-4', 'btte-span')"
                  />
                  <label for="3rd Year">3rd Year</label>
                  <input
                    type="button"
                    id="section-btte-5"
                    value="Section1"
                    onclick="toggleTable('table-btte-5', 'section-btte-5', 'btte-span')"
                  />
                  <input
                    type="button"
                    id="section-btte-6"
                    value="Section2"
                    onclick="toggleTable('table-btte-6', 'section-btte-6', 'btte-span')"
                  />
                  <label for="4th Year">4th Year</label>
                  <input
                    type="button"
                    id="section-btte-7"
                    value="Section1"
                    onclick="toggleTable('table-btte-7', 'section-btte-7', 'btte-span')"
                  />
                  <input
                    type="button"
                    id="section-btte-8"
                    value="Section2"
                    onclick="toggleTable('table-btte-8', 'section-btte-8', 'btte-span')"
                  />
                </a>
              </nav>
              <div class="schedule-editor">
                <div class="schedule-table" id="table-bcm-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td class="important">Subject 1</td>
                        <td>
                          <select name="room">
                            <option value="r101">R101</option>
                          </select>
                        </td>
                        <td>
                          <select name="day">
                            <option value="monday">Monday</option>
                          </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
                        <td>
                          <select name="room">
                            <option value="r101">R101</option>
                          </select>
                        </td>
                        <td>
                          <select name="day">
                            <option value="monday">Monday</option>
                          </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
                        <td>
                          <select name="room">
                            <option value="r101">R101</option>
                          </select>
                        </td>
                        <td>
                          <select name="day">
                            <option value="monday">Monday</option>
                          </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                      <tr>
                        <td class="important">Subject 1</td>
                        <td>
                          <select name="room">
                            <option value="r101">R101</option>
                          </select>
                        </td>
                        <td>
                          <select name="day">
                            <option value="monday">Monday</option>
                          </select>
                        </td>
                        <td></td>
                        <td></td>
                        <td>
                          <button class="icon">
                            <i class="bi bi-plus"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bcm-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bcm-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bcm-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bcm-5">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bcm-6">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bcm-7">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bcm-8">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-bpe-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bpe-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bpe-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bpe-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bpe-5">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bpe-6">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bpe-7">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bpe-8">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-bae-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bae-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bae-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bae-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bae-5">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bae-6">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bae-7">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bae-8">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-bse-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bse-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bse-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bse-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bse-5">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bse-6">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bse-7">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-bse-8">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>

                <div class="schedule-table" id="table-btte-1">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-btte-2">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-btte-3">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-btte-4">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-btte-5">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-btte-6">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-btte-7">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
                <div class="schedule-table" id="table-btte-8">
                  <table class="a">
                    <thead>
                      <tr>
                        <th>Subject name</th>
                        <th>Room</th>
                        <th>Day</th>
                        <th>End time</th>
                        <th>Start time</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <!--ADD TABLE ROW-->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </main>
        </div>

        <div class="floating" id="college-schedule-overview">
          <header>
            <div class="title">
              <h3>Schedule Overview <em>College</em></h3>
            </div>
            <div class="action">
              <div class="dropdown">
                <button class="icon">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item"
                    ><i class="bi bi-printer"></i>Print Schedule</a
                  >
                </div>
              </div>
            </div>
          </header>
          <div class="tabs">
            <button class="tab" id="college-room">
              <i class="bi bi-clipboard-check"></i>
              Room
            </button>
            <button class="tab" id="college-section">
              <i class="bi bi-journal-arrow-down"></i>
              Section
            </button>
          </div>

          <div class="action" id="college-room-tab">
            <div class="input" id="college-room-select">
              <p>Room</p>
              <select name="Room">
                <option value="">*insert room*</option>
              </select>
            </div>
          </div>

          <div class="action" id="college-section-tab">
            <div class="input" id="college-section-select">
              <p>Section</p>
              <select name="Room">
                <option value="">*insert section*</option>
              </select>
            </div>
          </div>

          <main id="college-room-table">
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>Monday</th>
                  <th>Tuesday</th>
                  <th>Wednesday</th>
                  <th>Thursday</th>
                  <th>Friday</th>
                  <th>Saturday</th>
                </tr>
              </thead>
              <tbody class="timestamp">
                <tr>
                  <td>7:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </main>

          <main id="college-section-table">
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>Monday</th>
                  <th>Tuesday</th>
                  <th>Wednesday</th>
                  <th>Thursday</th>
                  <th>Friday</th>
                  <th>Saturday</th>
                </tr>
              </thead>
              <tbody class="timestamp">
                <tr>
                  <td>7:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:00am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>11:30am</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>12:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>1:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>2:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>3:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>4:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>5:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>6:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>7:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>8:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>9:30pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                <tr>
                  <td>10:00pm</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </main>
        </div>
      </main>
    </div>
    <script src="../../assets/js/schedule.js"></script>
    <script src="../../assets/js/dropdownMenu.js"></script>