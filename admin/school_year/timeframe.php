<?php 

  include_once('../../includes/admin_header.php');


  include_once('../../includes/classes/Subject.php');
  include_once('../../includes/classes/SchoolYear.php');

  $school_year = new SchoolYear($con, null);
  $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

  $current_school_year_term = $school_year_obj['term'];
  $current_school_year_period = $school_year_obj['period'];

  if(isset($_GET['term'])){

    // $school_year_id = $_GET['id'];

    $term = $_GET['term'];
    $FIRST_SEMESTER = "First";
    $SECOND_SEMESTER = "Second";


    ?>
        <div class="col-md-12 row">
            <div style="width: 100%;" class="content">
                <div class="floating" id="college-teachers">
                    <header>
                        <div class="title">
                            <h3><?php echo $term;?> 1st Semester</h3>
                        </div>
                        
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




