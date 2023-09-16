<?php 

    include_once('../../includes/admin_header.php');


    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Student.php');

    $section = new Section($con);
    $program = new Program($con);
    $enrollment = new Enrollment($con);
    $student = new Student($con);

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    // $current_school_year_term = $school_year_obj['term'];
    // $current_school_year_period = $school_year_obj['period'];
    // $current_school_year_id = $school_year_obj['school_year_id'];

    $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
    $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
    $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');


  if(isset($_GET['term'])){

    $term = $_GET['term'];
    $period = ucfirst($_GET['period']);

    #Check if Exists.

    $check = $school_year->CheckTermPeriodExists($term, $period);
    if($check == false){
        echo "Term and Period is not exists";
        exit();
    }

    $school_year_id = $school_year->GetSchoolYearIdByTermPeriod($term, $period);

    $school_year_exec = new SchoolYear($con, $school_year_id);

    $term_db = $school_year_exec->GetTerm();
    $period_db = $school_year_exec->GetPeriod();
    $statuses_db = $school_year_exec->GetStatuses();


    $start_enrollment_date = $school_year_exec->GetStartEnrollment();
    $start_enrollment_date = date('Y-m-d\TH:i', strtotime($start_enrollment_date));

    $end_enrollment_date = $school_year_exec->GetEndEnrollment();
    $end_enrollment_date = date('Y-m-d\TH:i', strtotime($end_enrollment_date));

    // echo $start_enrollment_date;

    $prelim_exam_startdate = $school_year_exec->GetprelimStartDate();
    $prelim_exam_startdate = date('Y-m-d\TH:i', strtotime($prelim_exam_startdate));

    $prelim_exam_enddate = $school_year_exec->GetprelimEndDate();
    $prelim_exam_enddate = date('Y-m-d\TH:i', strtotime($prelim_exam_enddate));

    $midterm_exam_startdate = $school_year_exec->GetmidtermStartDate();
    $midterm_exam_startdate = date('Y-m-d\TH:i', strtotime($midterm_exam_startdate));
    
    $midterm_exam_enddate = $school_year_exec->GetmidtermEndDate();
    $midterm_exam_enddate = date('Y-m-d\TH:i', strtotime($midterm_exam_enddate));

    $prefinal_exam_startdate = $school_year_exec->GetprefinalStartDate();
    $prefinal_exam_startdate = date('Y-m-d\TH:i', strtotime($prefinal_exam_startdate));
    
    $prefinal_exam_enddate = $school_year_exec->GetprefinalEndDate();
    $prefinal_exam_enddate = date('Y-m-d\TH:i', strtotime($prefinal_exam_enddate));

    $final_exam_startdate = $school_year_exec->GetfinalStartDate();
    $final_exam_startdate = date('Y-m-d\TH:i', strtotime($final_exam_startdate));
    
    $final_exam_enddate = $school_year_exec->GetfinalEndDate();
    $final_exam_enddate = date('Y-m-d\TH:i', strtotime($final_exam_enddate));

    
    $break_startdate = $school_year_exec->GetbreakStartDate();
    $break_startdate = date('Y-m-d\TH:i', strtotime($break_startdate));
    
    $break_enddate = $school_year_exec->GetbreakEndDate();
    $break_enddate = date('Y-m-d\TH:i', strtotime($break_enddate));

 if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['save_edit_school_year_' . $school_year_id])
        && isset($_POST['term'])
        && isset($_POST['period'])
        && isset($_POST['statuses'])

        && isset($_POST['start_enrollment_date'])
        && isset($_POST['end_enrollment_date'])

        && isset($_POST['prelim_exam_startdate'])
        && isset($_POST['prelim_exam_enddate'])

        && isset($_POST['midterm_exam_startdate'])
        && isset($_POST['midterm_exam_enddate'])

        && isset($_POST['prefinal_exam_startdate'])
        && isset($_POST['prefinal_exam_enddate'])

        && isset($_POST['final_exam_startdate'])
        && isset($_POST['final_exam_enddate'])

        && isset($_POST['break_startdate'])
        && isset($_POST['break_enddate'])
        
        ){


        $term = $_POST['term'];
        $period = $_POST['period'];
        $statuses = $_POST['statuses'];
        $start_enrollment_date = $_POST['start_enrollment_date'];
        $end_enrollment_date = $_POST['end_enrollment_date'];


        # PRELIM
        $prelim_exam_startdate = $_POST['prelim_exam_startdate'];
        $prelim_exam_enddate = $_POST['prelim_exam_enddate'];

        # MIDTERM
        $midterm_exam_startdate = $_POST['midterm_exam_startdate'];
        $midterm_exam_enddate = $_POST['midterm_exam_enddate'];

        # PREFINAL
        $prefinal_exam_startdate = $_POST['prefinal_exam_startdate'];
        $prefinal_exam_enddate = $_POST['prefinal_exam_enddate'];


        # FINAL
        $final_exam_startdate = $_POST['final_exam_startdate'];
        $final_exam_enddate = $_POST['final_exam_enddate'];

        # FINAL
        $break_startdate = $_POST['break_startdate'];
        $break_enddate = $_POST['break_enddate'];

        // echo "term: " . $term . "<br>";
        // echo "period: " . $period . "<br>";

        // echo "start_enrollment_date: " . $start_enrollment_date . "<br>";
        // echo "end_enrollment_date: " . $end_enrollment_date . "<br>";

        // echo "prelim_exam_startdate: " . $prelim_exam_startdate . "<br>";
        // echo "prelim_exam_enddate: " . $prelim_exam_enddate . "<br>";

        // echo "midterm_exam_startdate: " . $midterm_exam_startdate . "<br>";
        // echo "midterm_exam_enddate: " . $midterm_exam_enddate . "<br>";

        // echo "prefinal_exam_startdate: " . $prefinal_exam_startdate . "<br>";
        // echo "prefinal_exam_enddate: " . $prefinal_exam_enddate . "<br>";

        // echo "final_exam_startdate: " . $final_exam_startdate . "<br>";
        // echo "final_exam_enddate: " . $final_exam_enddate . "<br>";

        // echo "break_startdate: " . $break_startdate . "<br>";
        // echo "break_enddate: " . $break_enddate . "<br>";

        $update = $con->prepare("UPDATE school_year
            SET term = :term,
                period = :period,
                statuses = :statuses,
                start_enrollment_date = :start_enrollment_date,
                end_enrollment_date = :end_enrollment_date,
                prelim_exam_startdate = :prelim_exam_startdate,
                prelim_exam_enddate = :prelim_exam_enddate,
                midterm_exam_startdate = :midterm_exam_startdate,
                midterm_exam_enddate = :midterm_exam_enddate,
                prefinal_exam_startdate = :prefinal_exam_startdate,
                prefinal_exam_enddate = :prefinal_exam_enddate,
                final_exam_startdate = :final_exam_startdate,
                final_exam_enddate = :final_exam_enddate,
                break_startdate = :break_startdate,
                break_enddate = :break_enddate

            WHERE school_year_id = :school_year_id");

        $update->bindParam(":term", $term);
        $update->bindParam(":period", $period);
        $update->bindParam(":statuses", $statuses);

        $update->bindParam(":start_enrollment_date", $start_enrollment_date);
        $update->bindParam(":end_enrollment_date", $end_enrollment_date);

        $update->bindParam(":prelim_exam_startdate", $prelim_exam_startdate);
        $update->bindParam(":prelim_exam_enddate", $prelim_exam_enddate);

        $update->bindParam(":midterm_exam_startdate", $midterm_exam_startdate);
        $update->bindParam(":midterm_exam_enddate", $midterm_exam_enddate);

        $update->bindParam(":prefinal_exam_startdate", $prefinal_exam_startdate);
        $update->bindParam(":prefinal_exam_enddate", $prefinal_exam_enddate);

        $update->bindParam(":final_exam_startdate", $final_exam_startdate);
        $update->bindParam(":final_exam_enddate", $final_exam_enddate);

        $update->bindParam(":break_startdate", $break_startdate);
        $update->bindParam(":break_enddate", $break_enddate);

        $update->bindParam(":school_year_id", $school_year_id);

        $update->execute();

        if($update->rowCount() > 0){
            Alert::success("Successfully edited.", "timeframe.php?term=$term");
            exit();
        }

    }


    ?>

    <div class="content">
        <nav>
            <a href="timeframe.php?term=<?php echo $term;?>">
                <i class="bi bi-arrow-return-left fa-1x"></i>
                <h3>Back</h3>
            </a>
        </nav>
        
        <main>
            <div class="floating">
                <header>
                    <div class="title">
                        <h4 class="text-primary text-center">Edit School Year Maintenance</h4>
                    </div>
                </header>
            
                <form method="POST">

                    <main>
                        <header>
                            <div class="title mb-2">
                                <h4 class="">* Term</h4>
                            </div>
                        </header>
                        <div class='form-group mb-2'>
                            <input class='form-control' value="<?php echo $term_db;?>" required type='text' placeholder='ex. 2021-2022' name='term'>
                        </div>

                        <div class='form-group mb-2'>

                            <label for=''>* Semester Period</label>
                            <select class='form-control' required name='period'>
                                <option value='First' <?php echo $period_db == "First" ? "selected" : ""; ?>>1st Semester</option>
                                <option value='Second' <?php echo $period_db == "Second" ? "selected" : ""; ?>>2nd Semester</option>
                            </select>

                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>* Status</label>
                            <br>
                            <div class='form-check-inline'>
                                <input class='form-check-input' type='radio' value='Active' name='statuses' id='activeStatus' <?php echo $statuses_db == "Active" ? "checked" : ""; ?>>
                                <label class='form-check-label' for='activeStatus'>Active</label>
                            </div>
                            <div class='form-check-inline'>
                                <input class='form-check-input' type='radio' value='InActive' name='statuses' id='inactiveStatus' <?php echo $statuses_db == "InActive" ? "checked" : ""; ?>>
                                <label class='form-check-label' for='inactiveStatus'>Inactive</label>
                            </div>
                        </div>

                        <!-- Enrollment Period  -->
                        <header>
                            <div class="title mb-3">
                                <h4 class="">Enrollment Period</h4>
                            </div>
                        </header>
                        <div class='form-group mb-2'>
                            <label for=''>* Start Date</label>
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($start_enrollment_date)); ?>" required type='datetime-local' placeholder='' name='start_enrollment_date'> -->
                            
                            <input class='form-control' value="<?php echo $start_enrollment_date; ?>"
                                required type='datetime-local' placeholder='' 
                                name='start_enrollment_date'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>* End Date</label>

                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($end_enrollment_date)); ?>" required type='date' placeholder='' name='end_enrollment_date'> -->
                            
                            <input class='form-control' value="<?php echo $end_enrollment_date; ?>"
                                required type='datetime-local' placeholder='' 
                                name='end_enrollment_date'>

                        </div>

                        <!-- Pre-Mid Exam  -->
                        <header>
                            <div class="title mb-3">
                                <h4 class="">Pre-Mid Exam</h4>
                            </div>
                        </header>
                        <div class='form-group mb-2'>
                            <label for=''>* Start Date</label>
                          
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($prelim_exam_startdate)); ?>" required type='date' placeholder='' name='prelim_exam_startdate'> -->
                            <input class='form-control' value="<?php echo $prelim_exam_startdate; ?>"
                                required type='datetime-local' placeholder='' 
                                name='prelim_exam_startdate'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>* End Date</label>

                           <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($prelim_exam_enddate)); ?>" required type='date' placeholder='' name='prelim_exam_enddate'> -->
                            <input class='form-control' value="<?php echo $prelim_exam_enddate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='prelim_exam_enddate'>
                        </div>


                        <!-- Midterm Exam  -->
                        <header>
                            <div class="title mb-3">
                                <h4 class="">Midterm Exam</h4>
                            </div>
                        </header>
                        <div class='form-group mb-2'>
                            <label for=''>* Start Date</label>
                         
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($midterm_exam_startdate)); ?>" required type='date' placeholder='' name='midterm_exam_startdate'> -->
                            <input class='form-control' value="<?php echo $midterm_exam_startdate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='midterm_exam_startdate'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>* End Date</label>
                      
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($midterm_exam_enddate)); ?>" required type='date' placeholder='' name='midterm_exam_enddate'> -->
                            <input class='form-control' value="<?php echo $midterm_exam_enddate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='midterm_exam_enddate'>
                        </div>


                        <!-- Pre Final Exam  -->
                        <header>
                            <div class="title mb-3">
                                <h4 class="">Pre-Final Exam</h4>
                            </div>
                        </header>
                        <div class='form-group mb-2'>
                            <label for=''>* Start Date</label>
                         
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($prefinal_exam_startdate)); ?>" required type='date' placeholder='' name='prefinal_exam_startdate'> -->
                            <input class='form-control' value="<?php echo $prefinal_exam_startdate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='prefinal_exam_startdate'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>* End Date</label>
                          

                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($prefinal_exam_enddate)); ?>" required type='date' placeholder='' name='prefinal_exam_enddate'> -->
                            <input class='form-control' value="<?php echo $prefinal_exam_enddate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='prefinal_exam_enddate'>
                        </div>


                        <!-- Final Exam  -->
                        <header>
                            <div class="title mb-3">
                                <h4 class="">Final Exam</h4>
                            </div>
                        </header>
                        <div class='form-group mb-2'>
                            <label for=''>* Start Date</label>
                          
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($final_exam_startdate)); ?>" required type='date' placeholder='' name='final_exam_startdate'> -->
                            <input class='form-control' value="<?php echo $final_exam_startdate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='final_exam_startdate'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>* End Date</label>
                         
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($final_exam_enddate)); ?>" required type='date' placeholder='' name='final_exam_enddate'> -->
                            <input class='form-control' value="<?php echo $final_exam_enddate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='final_exam_enddate'>
                        </div>

                        <!-- Break Period  -->
                        <header>
                            <div class="title mb-3">
                                <h4 class="">Break Period</h4>
                            </div>
                        </header>
                        <div class='form-group mb-2'>
                            <label for=''>* Start Date</label>
                            
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($break_startdate)); ?>" required type='date' placeholder='' name='break_startdate'> -->
                            <input class='form-control' value="<?php echo $break_startdate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='break_startdate'>
                        </div>

                        <div class='form-group mb-2'>
                            <label for=''>* End Date</label>
                            
                            <!-- <input class='form-control' value="<?php echo date('Y-m-d', strtotime($break_enddate)); ?>" required type='date' placeholder='' name='break_enddate'> -->
                            <input class='form-control' value="<?php echo $break_enddate; ?>"
                                    required type='datetime-local' placeholder='' 
                                    name='break_enddate'>
                        </div>

                    </main>
                    <div style="margin-bottom: -20px; margin-top: 20px;" class="action modal-footer">
                        <button name="save_edit_school_year_<?php echo $school_year_id;?>"
                            type="submit" class="default large clean">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <?php

  }


?>