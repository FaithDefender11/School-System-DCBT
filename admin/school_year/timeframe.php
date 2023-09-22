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

    // $asd = $section->SectionHasRoomTransfer("2021-2022", "First");

    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    // $current_school_year_term = $school_year_obj['term'];
    // $current_school_year_period = $school_year_obj['period'];
    // $current_school_year_id = $school_year_obj['school_year_id'];

    $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
    $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
    $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');

    // $changingEnrollmentPeriod = $school_year->ChangingEnrollmentPeriod($current_school_year_id);
    // $changingFinalEndDate = $school_year->ChangingFinalEndDate($current_school_year_id);
    // $changingBreakPeriod = $school_year->ChangingBreakPeriod($current_school_year_id);

    if(isset($_GET['term'])){

        $term = $_GET['term'];
        $FIRST_SEMESTER = "First";
        $SECOND_SEMESTER = "Second";
        $current = "";


        //     if(isset($_POST['school_year_id'])
        // && isset($_POST['school_year_period'])
        // && isset($_POST['name_period'])
        // && $_POST['name_period'] == "endEnrollmentDate"
        // && isset($_POST['school_year_term'])){

        $endEnrollmentAction = "endEnrollmentAction($current_school_year_id)";

    ?>
        <div class="content">

            <nav>
                <a href="index.php">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>

            <main>

                <div class="floating" id="first_semester">

                    <header>
                        <div class="title">
                            <h3>
                                <a style="all:unset;" href="edit.php?term=<?php echo $term; ?>&period=first">
                                    <?php echo $term;?> 1st Semester
                                </a>
                            </h3>
                        </div>

                        <?php if($current_school_year_period == "First" && $current_school_year_term == $term): ?>

                            <button onclick="<?php echo $endEnrollmentAction; ?>" class="mr-2 btn btn-sm btn-info">Clean</button>
                            <span>Current</span>
                        <?php endif;?>
                        
                    </header>

                    <main>
                        <table class="a"style="margin: 0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    echo $school_year->GetTermTimeFrame($term,
                                        $FIRST_SEMESTER, $current_school_year_period);
                                ?>
                                
                            </tbody>
                        </table>
                    </main>
                </div>

                <div class="floating" id="second_semester">

                    <header>
                        <div class="title">
                            <h3> 
                                <a style="all:unset;" href="edit.php?term=<?php echo $term; ?>&period=second">
                                        <?php echo $term;?> 2nd Semester
                                </a>
                            </h3>
                            
                        </div>
                        <span><?php echo $current_school_year_period == "Second" 
                            && $current_school_year_term == $term
                            ? "Current" : "";?></span>
                        
                    </header>

                    <main>
                        <table class="a"style="margin: 0">
                            <thead>
                                <tr>
                                    <th>Event</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    echo $school_year->GetTermTimeFrame($term,
                                        $SECOND_SEMESTER, $current_school_year_period);
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

    function endingDate(school_year_id, school_year_period, name_period, school_year_term){

        var school_year_id = parseInt(school_year_id);

            Swal.fire({
                icon: 'question',
                title: `End the ${name_period} period?`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/school_year/ending_timeframe.php",
                        type: 'POST',
                        data: {
                            school_year_id,
                            school_year_period,
                            name_period,
                            school_year_term
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_update"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Updated`,
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
                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );
                                location.reload();
                            })}

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

    function endBreak(school_year_id, school_year_period){

        var school_year_id = parseInt(school_year_id);

            Swal.fire({
                icon: 'question',
                title: `End the Break period?`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/school_year/ending_timeframe.php",
                        type: 'POST',
                        data: {
                            school_year_id,school_year_period
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_update"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Updated`,
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

                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );

                                location.reload();
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

    function startEnrollmentDate(school_year_id, school_year_period,
        name_period, school_year_term){

        var school_year_id = parseInt(school_year_id);

            Swal.fire({
                icon: 'question',
                title: `Start the Enrollment Date?`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/school_year/startEnrollmentDate.php",
                        type: 'POST',
                        data: {
                            school_year_id,
                            school_year_period,
                            name_period,
                            school_year_term
                        },
                        success: function(response) {

                            response = response.trim();

                            // console.log(response);

                            if(response == "success_update"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Updated`,
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

                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );

                                location.reload();
                            });}
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

    function endEnrollmentDate(school_year_id, school_year_period,
        name_period, school_year_term){

        var school_year_id = parseInt(school_year_id);

            Swal.fire({
                icon: 'question',
                title: `End the Enrollment Date?`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/school_year/endEnrollmentDate.php",
                        type: 'POST',
                        data: {
                            school_year_id,
                            school_year_period,
                            name_period,
                            school_year_term
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_update"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Updated`,
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

                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );

                                location.reload();
                            });}
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

    function breakEnded(school_year_id, school_year_period,
        name_period, school_year_term){

        var school_year_id = parseInt(school_year_id);

            Swal.fire({
                icon: 'question',
                title: `End the Break Date?`,
                text: 'Please note that this action cannot be undone',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/school_year/breakEnded.php",
                        type: 'POST',
                        data: {
                            school_year_id,
                            school_year_period,
                            name_period,
                            school_year_term
                        },
                        success: function(response) {

                            response = response.trim();

                            // console.log(response);

                            if(response == "success_update"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Updated`,
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

                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );

                                location.reload();
                            });}
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

    function endEnrollmentAction(current_school_year_id){

        var current_school_year_id = parseInt(current_school_year_id);

        // # 1. Remove All new Enrollee Table.
        // # 2. Remove all form from new student tentative and its created student table
        // # 3. Remove all form from old student tentative and will de-activate the student account.

            Swal.fire({
                icon: 'question',
                title: `This action will do the following:`,
                html: `
                    <p>1. Remove all new enrollees data.</p>
                    <p>2. Remove all form from new student tentative and its created student data.</p>
                    <p>3. Remove all form from old student tentative and will de-activate the student account.</p>
                    <h5>Important! Please note that this action cannot be undone.</h5>
                `,
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'Cancel'

            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/school_year/endEnrollmentAction.php",
                        type: 'POST',
                        data: {
                            current_school_year_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_update"){
                                Swal.fire({
                                icon: 'success',
                                title: `Successfully Updated`,
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

                                // $('#shs_program_table').load(
                                //     location.href + ' #shs_program_table'
                                // );

                                location.reload();
                            });}

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
