<?php 

  include_once('../../includes/admin_header.php');


  include_once('../../includes/classes/Subject.php');
  include_once('../../includes/classes/SchoolYear.php');

  $school_year = new SchoolYear($con, null);
  $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

  $current_school_year_term = $school_year_obj['term'];
  $current_school_year_period = $school_year_obj['period'];

?>
 

<div style="display: none;" class="row col-md-12">
  <div class="content">
      <nav>Department</nav>
      <main>
        <div class="floating">
          <header>
            <div class="title">
              <h3>School Year</h3>
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
                          <td style='font-weight: 700'>$term</td>
                          <td style='text-align: right'>$text</td>
                          <td>
                              <a href='timeframe.php?term=$term'>
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
</div>


    <div class="content">

      <!-- <nav>
        <h3>Department</h3>
        <div class="form-box">
          <div class="button-box">
            <div id="btn"></div>
            <button type="button" class="toggle-btn">
              SHS
            </button>
            <button type="button" class="toggle-btn">
              College
            </button>
          </div>
        </div>
      </nav> -->

      <main>
        <!--SHS SY-->
        <div class="floating" id="shs-sy">
          <header>
            <div class="title">
              <h3>School Year</h3>
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
                          <td style='font-weight: 700'>$term</td>
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

        <!--COLLEGE SY-->
        <div class="floating" id="college-sy" style="display: none">
          <header>
            <div class="title">
              <h3>School Year <em>College</em></h3>
            </div>
          </header>
          <main>
            <table>
              <tr>
                <td style="font-weight: 700">2022-2023</td>
                <td style="text-align: right">Current</td>
                <td>
                  <a href="#"><i class="bi bi-arrow-right-circle"></i></a>
                </td>
              </tr>
            </table>
          </main>
        </div>
      </main>
    </div>
