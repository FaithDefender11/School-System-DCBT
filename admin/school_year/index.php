<?php 

    include_once('../../includes/admin_header.php');


    include_once('../../includes/classes/Subject.php');
    include_once('../../includes/classes/SchoolYear.php');

    $school_year = new SchoolYear($con, 30);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    // $current_school_year_term = $school_year_obj !== NULL ? $school_year_obj['term'] : "";
    // $current_school_year_period = $school_year_obj !== NULL ? $school_year_obj['period'] : "";
    // $current_school_year_id = $school_year_obj !== NULL ? $school_year_obj['school_year_id'] : "";


    $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
    $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
    $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');

    // echo $current_school_year_term;

    # VALUE = 2023-08-09 00:00:00
    $startDate = $school_year->GetStartEnrollment();

    // echo $startDate;

?>

 


    <div class="content">

      <main>
        <div class="floating" id="shs-sy">
          <header>
            <div class="title">
              <h4>School Year</h4>

            </div>

            <div class="action">
                <a href="create_term.php">
                    <button type="button" class="clean large information">+ Add new</button>
                </a>
            </div>
          </header>
          <main>
            <table>
              <?php
              
                $query = $con->prepare("SELECT 
                    MIN(school_year_id) AS school_year_id,
                    term
                    FROM school_year
                    GROUP BY term");
                $query->execute();

                if($query->rowCount() > 0){

                  while($row = $query->fetch(PDO::FETCH_ASSOC)){

                      $term = $row['term'];
                      $school_year_id = $row['school_year_id'];

                      $text = "";


                      if($term == $current_school_year_term){
                        $text = "Current";
                      }

                      echo "
                        <tr style='font-weight: 700'>
                          <td style='font-weight: 700'>
                              <a href='create.php?term=$term' style='all: unset;'>
                                $term
                              </a>
                            </td>
                          <td style='text-align: right'>$text</td>
                          <td>
                              <a  href='timeframe.php?term=$term'>
                                <button type='button' class='redirect-btn' id='shs-calendar'>
                                  <i class='bi bi-arrow-right-circle'></i>
                                </button>
                              </a>
                            </td>
                        </tr>
                      ";
                  }
                }
              ?>
            </table>
          </main>
        </div>

      </main>
    </div>
