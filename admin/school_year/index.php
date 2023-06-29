<?php 

  include_once('../../includes/admin_header.php');


  include_once('../../includes/classes/Subject.php');
  include_once('../../includes/classes/SchoolYear.php');

  $school_year = new SchoolYear($con, null);
  $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

  $current_school_year_term = $school_year_obj['term'];
  $current_school_year_period = $school_year_obj['period'];

?>
 
<!-- <div class="row col-md-12">
    <div class="content_subject">
        <div class="dashboard">

            <h5>Department</h3>

            <div class="form-box">
                <div class="button-box">
                    <div id="btn"></div>
                    <a href="shs_index.php">
                        <button type="button" class="btn-active toggle-btn" >
                            SHS
                        </button>
                    </a>

                    <a href="tertiary_index.php">
                        <button type="button" class="btn-inactive toggle-btn">
                            Tertiary
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <h3>Menu</h3>
        <div class="container-subjects">

            <div class="subject_container">
                <p>View Subjects</p>

                <a style="all: initial;" href="list.php">
                    <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>

            <div class="subject_container">
                <p>View Strand Subjects</p>
                <p> <?php echo $_SESSION['department_type']; ?></p>
                <a style="all: initial;" href="strand.php">
                    <i class="bi bi-arrow-right-circle"></i>
                </a>
            </div>
        </div>
    </div>

</div> -->

<div class="row col-md-12">
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
