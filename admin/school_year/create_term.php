<?php 

    include_once('../../includes/admin_header.php');


    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/SchoolYear.php');

    $school_year = new SchoolYear($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

//   $current_school_year_term = $school_year_obj['term'];
//   $current_school_year_period = $school_year_obj['period'];
    
    $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
    $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
    $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');


  if($_SERVER['REQUEST_METHOD'] === "POST" 
        && isset($_POST['save_school_year'])
        && isset($_POST['term'])
        && isset($_POST['period'])
        && isset($_POST['statuses'])

        && isset($_POST['start_enrollment_date'])
        && isset($_POST['end_enrollment_date'])

        // && isset($_POST['prelim_exam_startdate'])
        // && isset($_POST['prelim_exam_enddate'])

        // && isset($_POST['midterm_exam_startdate'])
        // && isset($_POST['midterm_exam_enddate'])

        // && isset($_POST['prefinal_exam_startdate'])
        // && isset($_POST['prefinal_exam_enddate'])

        // && isset($_POST['final_exam_startdate'])
        && isset($_POST['final_exam_enddate'])

        // && isset($_POST['break_startdate'])
        // && isset($_POST['break_enddate'])
        
        ){


        $term = $_POST['term'];
        $period = $_POST['period'];
        $statuses = $_POST['statuses'];
        $start_enrollment_date = $_POST['start_enrollment_date'];
        $end_enrollment_date = $_POST['end_enrollment_date'];

        $final_exam_enddate = $_POST['final_exam_enddate'];


        // # PRELIM
        // $prelim_exam_startdate = $_POST['prelim_exam_startdate'];
        // $prelim_exam_enddate = $_POST['prelim_exam_enddate'];

        // # MIDTERM
        // $midterm_exam_startdate = $_POST['midterm_exam_startdate'];
        // $midterm_exam_enddate = $_POST['midterm_exam_enddate'];

        // # PREFINAL
        // $prefinal_exam_startdate = $_POST['prefinal_exam_startdate'];
        // $prefinal_exam_enddate = $_POST['prefinal_exam_enddate'];


        // # FINAL
        // $final_exam_startdate = $_POST['final_exam_startdate'];
        // $final_exam_enddate = $_POST['final_exam_enddate'];

        // # FINAL
        // $break_startdate = $_POST['break_startdate'];
        // $break_enddate = $_POST['break_enddate'];
        

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

        $add = $con->prepare("INSERT INTO school_year
            (term, period, statuses, start_enrollment_date, end_enrollment_date,
                final_exam_enddate
                -- prelim_exam_startdate, prelim_exam_enddate,
                -- midterm_exam_startdate, midterm_exam_enddate,
                -- prefinal_exam_startdate, prefinal_exam_enddate,
                -- final_exam_startdate, final_exam_enddate,
                -- break_startdate, break_enddate
            )

            VALUES (:term, :period, :statuses, :start_enrollment_date, :end_enrollment_date, :final_exam_enddate

                -- :prelim_exam_startdate, :prelim_exam_enddate,
                -- :midterm_exam_startdate, :midterm_exam_enddate,
                -- :prefinal_exam_startdate, :prefinal_exam_enddate,
                -- :final_exam_startdate, :final_exam_enddate,
                -- :break_startdate, :break_enddate
            )");

        $add->bindParam(":term", $term);
        $add->bindParam(":period", $period);
        $add->bindParam(":statuses", $statuses);

        $add->bindParam(":start_enrollment_date", $start_enrollment_date);
        $add->bindParam(":end_enrollment_date", $end_enrollment_date);

        $add->bindParam(":final_exam_enddate", $final_exam_enddate);

        // $add->bindParam(":prelim_exam_startdate", $prelim_exam_startdate);
        // $add->bindParam(":prelim_exam_enddate", $prelim_exam_enddate);

        // $add->bindParam(":midterm_exam_startdate", $midterm_exam_startdate);
        // $add->bindParam(":midterm_exam_enddate", $midterm_exam_enddate);

        // $add->bindParam(":prefinal_exam_startdate", $prefinal_exam_startdate);
        // $add->bindParam(":prefinal_exam_enddate", $prefinal_exam_enddate);

        // $add->bindParam(":final_exam_startdate", $final_exam_startdate);
        // $add->bindParam(":final_exam_enddate", $final_exam_enddate);

        // $add->bindParam(":break_startdate", $break_startdate);
        // $add->bindParam(":break_enddate", $break_enddate);

        $add->execute();

        if($add->rowCount() > 0){
            Alert::success("School Year Term: $term $period semester has been created", "index.php");
            exit();
        }

    }

?>

<div class="content">
    <nav>
        <a href="index.php">
            <i class="bi bi-arrow-return-left fa-1x"></i>
            <h3>Back</h3>
        </a>
    </nav>
    
    <main>
        <div class="floating">
            <header>
                <div class="title">
                    <h4 class="text-primary text-center">School Year Maintenance</h4>
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
                        <input class='form-control' required type='text' placeholder='ex. 2021-2022' name='term'>
                    </div>

                    <div class='form-group mb-2'>

                        <label for=''>* Semester Period</label>
                        <select class='form-control' required name='period'>
                            <option value='First'>1st Semester</option>
                            <option value='Second'>2nd Semester</option>
                        </select>

                    </div>

                    <div class='form-group mb-2'>
                        <label for=''>* Status</label>
                        <br>
                        <div class='form-check-inline'>
                            <input class='form-check-input' type='radio' value='Active' name='statuses' id='activeStatus'>
                            <label class='form-check-label' for='activeStatus'>Active</label>
                        </div>
                        <div class='form-check-inline'>
                            <input class='form-check-input' type='radio' value='InActive' name='statuses' id='inactiveStatus'>
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
                        <input class='form-control' required type='date' placeholder='' name='start_enrollment_date'>
                    </div>

                    <div class='form-group mb-2'>
                        <label for=''>* End Date</label>
                        <input class='form-control' required type='date' placeholder='' name='end_enrollment_date'>
                    </div>

                    <header>
                        <div class="title mb-3">
                            <h4 class="">* Finals End Term</h4>
                        </div>
                    </header>

                    <div class='form-group mb-2'>
                        <input class='form-control' required type='date' placeholder='' name='final_exam_enddate'>
                    </div>

                </main>
                <div style="margin-bottom: -20px; margin-top: 20px;" class="action modal-footer">
                    <button name="save_school_year"
                        type="submit" class="default large clean">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
    
