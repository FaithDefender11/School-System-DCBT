<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');

    // echo $_SESSION['role'];
    
    $school_year = new SchoolYear($con, null);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year_obj['term'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_id = $school_year_obj['school_year_id'];

    $enrollment = new Enrollment($con);


    $activatedElmsAccount = $enrollment->GetActivatedLMSAccounts($current_school_year_id);
    $nonActivatedElmsAccount = $enrollment->GetNonActivatedLMSAccounts($current_school_year_id);
    $deActivatedElmsAccount = $enrollment->GetDeactivatedActivatedLMSAccounts($current_school_year_id);

    $activatedElmsAccountCount = count($activatedElmsAccount);
    $nonActivatedElmsAccountCount = count($nonActivatedElmsAccount);
    $deActivatedElmsAccountCount = count($deActivatedElmsAccount);


    // print_r($nonActivatedElmsAccount);

    $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");


?>


<div class="content">
     
    <div class="content-header">
      <header>
        <div class="title">
          <h1>User account <em>SHS</em> &nbsp; <em>Tertiary</em></h1>
          <small
            >Note: Numbers on tabs only count current school year and
            semester</small
          >
        </div>
        <h5><?php echo $current_school_year_term; ?> <span><?php echo $period_short; ?></span></h5>

      </header>
    </div>

    <div class="tabs">
      <button
        class="tab"
        id="shsEvaluation"
        style="background-color: var(--them); color: white"
        onclick="window.location.href = 'activated.php';" >
        Activated (<?php echo $activatedElmsAccountCount;?>)
      </button>
        
      <button
        class="tab"
        id="shsPayment"
        style="background-color: var(--mainContentBG); "
        onclick="window.location.href = 'to_activate.php';"
      >
        To activate (<?php echo $nonActivatedElmsAccountCount;?>)
      </button>

      <button
        class="tab"
        id="shsPayment"
        style="background-color: var(--them); color: white"
        onclick="window.location.href = 'de_activated.php';"
      >
        Deactivated (<?php echo $deActivatedElmsAccountCount;?>)
      </button>
     
    </div>

    <main>

      <div class="floating">
        <header>
          <div class="title">
            <h3></h3>
          </div>
          <div class="action">
            <form style="display: flex;" method="POST" id="student_filter_form">
                <div style="margin-right: 15px;" class="form-group">
                    <label for="new">New</label>
                    <input type="checkbox" id="new" name="student_filter[]" value="New" class="form-control" onchange="handleCheckboxChange('new')" <?php if (isset($_POST["student_filter"]) && in_array("New", $_POST["student_filter"])) echo "checked"; ?>>
                </div>
                <div class="form-group">
                    <label for="old">Old</label>
                    <input type="checkbox" id="old" name="student_filter[]" value="Old" class="form-control" onchange="handleCheckboxChange('old')" <?php if (isset($_POST["student_filter"]) && in_array("Old", $_POST["student_filter"])) echo "checked"; ?>>
                </div>
            </form>

            
          </div>
        </header>

        <main>

          <table style="width: 100%" id="account_table" class="a">
           
              <thead>
                  <tr>
                      <th>Student ID</th>  
                      <th>Name</th>
                      <th>Section</th>
                      <th>Type</th>  
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                    <?php

                        foreach ($nonActivatedElmsAccount as $key => $row) {
                            
                            $student_id = $row['student_id'];
                            $enrollment_id = $row['enrollment_id'];
                            $student_unique_id = $row['student_unique_id'];

                            $firstname = $row['firstname'];
                            $lastname = $row['lastname'];

                            $is_tertiary = $row['is_tertiary'];

                            $type= $is_tertiary === 1 ? "Tertiary" : "SHS";

                            $fullname = ucwords($lastname) . " " . ucwords($firstname);

                            $program_section = $row['program_section'];
                            $date_activation = $row['date_activation'];

                            $date_activation = date("M d, Y h:i a", strtotime($date_activation));

                            $activateAccount = "activateAccount($enrollment_id)";

                            $button_url = "
                              <button class='default clean'
                                onclick='$activateAccount'>
                                  Activate
                              </button>
                            ";

                            echo "
                                <tr>
                                    <td>$student_unique_id</td>
                                    <td>$fullname</td>
                                    <td>$program_section</td>
                                    <td>$type</td>
                                    <td>
                                      $button_url
                                    </td>
                                </tr>
                            ";
                        }

                    ?>
              </tbody>
          </table>


        </main>

      </div>
    </main>

         
</div>


<script>
      function activateAccount(enrollment_id){

        var enrollment_id = parseInt(enrollment_id);

        Swal.fire({
            icon: 'question',
            title: `Are you sure you want to activate student LMS account?`,
            text: 'Important! This action cannot be undone.',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "../../ajax/pending/activateAccount.php",
                        type: 'POST',
                        data: {
                            enrollment_id
                        },
                        success: function(response) {

                            response = response.trim();

                            console.log(response);

                            if(response == "success_activate"){
                                Swal.fire({
                                icon: 'success',
                                title: `LMS account activation success`,
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