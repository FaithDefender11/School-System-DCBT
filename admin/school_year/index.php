<?php 

  include_once('../../includes/admin_header.php');


  include_once('../../includes/classes/Section.php');
  include_once('../../includes/classes/Subject.php');
  include_once('../../includes/classes/SchoolYear.php');

  $school_year = new SchoolYear($con);
  $section = new Section($con);
  $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

  // $current_school_year_term = $school_year_obj !== NULL ? $school_year_obj['term'] : "";
  // $current_school_year_period = $school_year_obj !== NULL ? $school_year_obj['period'] : "";
  // $current_school_year_id = $school_year_obj !== NULL ? $school_year_obj['school_year_id'] : "";


  $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
  $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
  $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');


  # VALUE = 2023-08-09 00:00:00
  $startDate = $school_year->GetStartEnrollment();


  $asd = $section->GetAllActiveSectionWithinYear($current_school_year_term);
  // print_r($asd);

?>

<div class="content">
  <main>
    <div class="floating" id="shs-sy">
      <header>
        <div class="title">
          <h3>School Year</h3>
        </div>
        <div class="action">
            <a href="create_term.php">
                <button type="button" class="default large">+ Add new</button>
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

    <div class="floating" id="shs-sy">
      <header>
          <div class="title">
              <h3>School Year</h3>
          </div>
      </header>
      <table id="school_year_table" class="a" style="margin: 0">
          <thead>

              <tr>
                <th><?php echo "
                  <i class='fas fa-wrench'></i>" ?></th>
                <th>School Term</th>
                <th>Academic Period</th>
                <th>Status</th>
              </tr>

          </thead>
          <tbody>
              <?php
              
                $query = $con->prepare("SELECT * FROM school_year ");

                $query->execute();

                if($query->rowCount() > 0){

                  while($row = $query->fetch(PDO::FETCH_ASSOC)){

                      $term = $row['term'];
                      $period = $row['period'];
                      $school_year_id = $row['school_year_id'];
                      $statuses = $row['statuses'];

                      $period = $period === "First" ? "1ST" : ($period === "Second" ? "2ND" : "");

                      // $removeDepartmentBtn = "removeDepartmentBtn($department_id)";
                      $removeDepartmentBtn = "";

                        
                      $output_Status = "";
                      

                      $setting_btn = "";

                     

                      if($statuses === "Active"){

                          $output_Status = "
                            <button class='btn-sm btn btn-info'>
                                $statuses
                            </button>
                          ";

                          $setting_btn = "
                            <button class='btn-sm btn btn-dark'>
                                <i class='fas fa-history'></i>
                            </button>
                          ";

                      }else if($statuses === "InActive"){

                        $changeSchoolYear = "changeSchoolYear($school_year_id, $current_school_year_id)";

                        $setting_btn = "
                           
                          <button onclick='$changeSchoolYear' class='btn-sm btn btn-primary'>
                              <i class='fas fa-pen'></i>
                          </button>
                        ";

                        $output_Status = "
                          <button class='btn-sm btn btn-danger'>
                                $statuses
                          </button>
                        ";

                      }

                      echo "
                        <tr>
                            <td>
                               $setting_btn
                            <td>$term</td>
                            <td>$period</td>
                            <td>
                               $output_Status
                            </td>
                        </tr>
                      ";
                  }

                }

              ?>
          </tbody>
      </table>
    </div>
  </main>  
</div>


<script>
  function changeSchoolYear(selected_school_year_id, current_school_year_id) {

    var selected_school_year_id = parseInt(selected_school_year_id);

    Swal.fire({
        icon: 'question',
        title: `Change School Year?`,
        text: 'Important! Please review your decision',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {

           

            $.ajax({
                url: "../../ajax/school_year/school_year_maintenance.php",
                type: 'POST',
                data: {
                    selected_school_year_id,
                    current_school_year_id
                },
                success: function (response) {
                    response = response.trim();

                    console.log(response);

                    if (response == "success_update") {
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
                error: function (xhr, status, error) {
                    // handle any errors here
                }
            });
        }
    });
}

   


</script>

