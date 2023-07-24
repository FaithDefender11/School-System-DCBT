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

  $current_school_year_term = $school_year_obj['term'];
  $current_school_year_period = $school_year_obj['period'];
  $current_school_year_id = $school_year_obj['school_year_id'];

  if(isset($_GET['term'])){

    // $school_year_id = $_GET['id'];

    $term = $_GET['term'];
    $FIRST_SEMESTER = "First";
    $SECOND_SEMESTER = "Second";

    $current = "";

    // if($current_school_year_period == $FIRST_SEMESTER){
    //     $current = "Current";
    // }else if($current_school_year_period == $SECOND_SEMESTER){
    //     $current = "Current";
    // }


    // Create new Moving Up Section based on deactivated section.
    // $movingUpSection = $section->MovingUpCurrentActiveSections($term);
 
    // if($movingUpSection){

    //     // Deactivate other all active section course/strand.
    //     $deactiveCurrentSection = $section->DeactiveCurrentActiveSections($term);

    //     if($deactiveCurrentSection){
    //         $createEachNewSection = $section->CreateEachSectionStrandCourse($term);
    //     }
    // }

    // $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($current_school_year_id);

    // print_r($currentNewEnrolled);

    // foreach ($currentNewEnrolled as $key => $student_ids) {

    //     $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);

    // }

    ?>
        <div class="col-md-12 row">
            <div style="width: 100%;" class="content">
                <div class="floating" id="college-teachers">
                    <header>
                        <div class="title">
                            <h3><?php echo $term;?> 1st Semester</h3>
                        </div>
                        <span><?php echo $current_school_year_period == "First" ? "Current" : "";?></span>
                        
                    </header>
                    <main>
                        <table class="ws-table-all cw3-striped cw3-bordered"style="margin: 0">
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
                            
                                echo $school_year->GetTermTimeFrame($term, $FIRST_SEMESTER);

                                // $query = $con->prepare("SELECT * 
                                //     FROM school_year
                                //     WHERE term=:term
                                //     AND period=:period
                                //     LIMIT 1
                                //     ");


                                // $query->bindParam(":term", $term);
                                // $query->bindParam(":period", $FIRST_SEMESTER);
                                // $query->execute();

                                // if($query->rowCount() > 0){
                                //     $row = $query->fetch(PDO::FETCH_ASSOC);

                                //     echo "
                                //         <tr>
                                //             <td>Enrollment Period</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //     echo "
                                //         <tr>
                                //             <td>Class Start</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";
                                //     echo "
                                //         <tr>
                                //             <td>Pre-Mid Exam</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //     echo "
                                //         <tr>
                                //             <td>Midterm Exam</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //      echo "
                                //         <tr>
                                //             <td>Pre-Final Exam</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //     echo "
                                //         <tr>
                                //             <td>Final Exam</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //     echo "
                                //         <tr>
                                //             <td>Break</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";
                                // }



                            ?>
                            <tr>
                                <td></td>
                            </tr>
                        </tbody>
                        </table>
                    </main>
                </div>



                <div class="floating" id="college-teachers">
                    <header>
                        <div class="title">
                            <h3>2nd Semester</h3>
                        </div>
                        <span><?php echo $current_school_year_period == "Second" ? "Current" : "";?></span>
                    </header>
                    <main>
                        <table class="ws-table-all cw3-striped cw3-bordered"style="margin: 0">
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
                                echo $school_year->GetTermTimeFrame($term, $SECOND_SEMESTER);

                                // $query = $con->prepare("SELECT * 
                                //     FROM school_year
                                //     WHERE term=:term
                                //     AND period=:period
                                //     LIMIT 1
                                //     ");


                                // $query->bindParam(":term", $term);
                                // $query->bindParam(":period", $FIRST_SEMESTER);
                                // $query->execute();

                                // if($query->rowCount() > 0){
                                //     $row = $query->fetch(PDO::FETCH_ASSOC);

                                //     echo "
                                //         <tr>
                                //             <td>Enrollment Period</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //     echo "
                                //         <tr>
                                //             <td>Class Start</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";
                                //     echo "
                                //         <tr>
                                //             <td>Pre-Mid Exam</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //     echo "
                                //         <tr>
                                //             <td>Midterm Exam</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //      echo "
                                //         <tr>
                                //             <td>Pre-Final Exam</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //     echo "
                                //         <tr>
                                //             <td>Final Exam</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";

                                //     echo "
                                //         <tr>
                                //             <td>Break</td>
                                //             <td></td>
                                //             <td></td>
                                //             <td>Ended</td>
                                //         </tr>
                                //     ";
                                // }



                            ?>
                            <tr>
                                <td></td>
                            </tr>
                        </tbody>
                        </table>
                    </main>
                </div>


            </div>
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
                            school_year_id,school_year_period, name_period, school_year_term
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

</script>
