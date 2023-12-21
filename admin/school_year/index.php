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
    <div class="floating">
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
                            <button type='button' class='redirect-btn'>
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

    <div class="floating">
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

                  $now = date("Y-m-d H:i:s");
                  $now = new DateTime(); // This defaults to the current date and time

                  while($row = $query->fetch(PDO::FETCH_ASSOC)){

                      $term = $row['term'];
                      $period = $row['period'];
                      $school_year_id = $row['school_year_id'];
                      $statuses = $row['statuses'];

                      $final_exam_enddate = $row['final_exam_enddate'];

                      // var_dump($final_exam_enddate);

                      $period = $period === "First" ? "1ST" : ($period === "Second" ? "2ND" : "");

                      // $removeDepartmentBtn = "removeDepartmentBtn($department_id)";
                      $removeDepartmentBtn = "";

                        
                      $output_Status = "";

                      $setting_btn = "";

                     
                      $statusResult = "";

                      if($statuses === "Active"){

                          $output_Status = "
                            <button class='btn-sm btn btn-info'>
                                $statuses
                            </button>
                          ";

                          $statusResult = "Active";

                          

                          $setting_btn = "
                            <button class='btn-sm btn btn-dark'>
                                <i class='fas fa-history'></i>
                            </button>
                          ";

                      }else if($statuses === "InActive"){

                        $changeSchoolYear = "changeSchoolYear($school_year_id, $current_school_year_id)";

                        $selected_year_end_term_date = new DateTime($final_exam_enddate);




                        if($final_exam_enddate == NULL 
                          || $selected_year_end_term_date > $now
                          || $current_school_year_id > $school_year_id){
                            $setting_btn = "
                            <button disabled onclick='' class='btn-sm btn btn-primary'>
                                <i class='fas fa-pen'></i>
                            </button>
                          ";
                        }else{
                          $setting_btn = "
                            <button onclick='$changeSchoolYear' class='btn-sm btn btn-primary'>
                                <i class='fas fa-pen'></i>
                            </button>
                          ";
                        }


                        $statusResult = "In-active";
 
                        $output_Status = "
                          <button class='btn-sm btn btn-danger'>
                                $statusResult
                          </button>
                        ";

                      }

                      echo "
                        <tr>
                            <td>
                               $setting_btn
                            <td>$term</td>
                            <td>
                              <a style='color: inherit' href='timeframe.php?term=$term'>
                                $period
                              </a>
                            </td>
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

                    if (response == "school_year_changing_invalid") {
                        Swal.fire({
                            icon: 'error',
                            title: `Selected School Year End term should be higher than todays date.`,
                            showConfirmButton: false,
                            timer: 4900, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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

                    if (response == "success_update") {
                        Swal.fire({
                            icon: 'success',
                            title: `School year has been successfully modified.`,
                            showConfirmButton: false,
                            timer: 2500, // Adjust the duration of the toast message in milliseconds (e.g., 3000 = 3 seconds)
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

