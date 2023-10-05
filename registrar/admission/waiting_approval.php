<?php 

  include_once('../../includes/registrar_header.php');
  include_once('../../includes/classes/Enrollment.php');
  include_once('../../includes/classes/Section.php');
  include_once('../../includes/classes/SchoolYear.php');

  ?>  
    <head>

      <style>
          .show_search{
              position: relative;
              /* margin-top: -38px;
              margin-left: 215px; */
          }
          div.dataTables_length {
              display: none;
          }

          #waiting_payment_table_filter{
            margin-top: 12px;
            width: 100%;
            display: flex;
            flex-direction: row;
            justify-content: start;
          }

          #waiting_payment_table_filter input{
            width: 250px;
          }
      </style>

      <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
      <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    </head>

  <?php

  $school_year = new SchoolYear($con, null);
  $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

  $current_school_year_term = $school_year_obj['term'];
  $current_school_year_period = $school_year_obj['period'];
  $current_school_year_id = $school_year_obj['school_year_id'];

  $enrollment = new Enrollment($con, null);
  $section = new Section($con, null);

  // O.S Irregular, Pending New Standard, New Transferee
  $unionEnrollment = $enrollment->UnionEnrollment($current_school_year_id);
  $waitingPaymentEnrollment = $enrollment->WaitingPaymentEnrollment($current_school_year_id);
  $waitingApprovalEnrollment = $enrollment->WaitingApprovalEnrollment($current_school_year_id);
  $enrolledStudentsEnrollment = $enrollment->EnrolledStudentsWithinSYSemester($current_school_year_id);
  $sectionEnrolledStudentList = $section->GetCurrentSectionWithEnrolledStudent($current_school_year_id);


  $pendingEnrollmentCount = 0;
  $unionEnrollmentCount = 0;
  $waitingApprovalEnrollmentCount = 0;
  $enrolledStudentsEnrollmentCount = 0;


  $unionEnrollmentCount = count($unionEnrollment);
  $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
  $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
  $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);
  $sectionEnrolledStudentListCount = count($sectionEnrolledStudentList);


  $selected_student_filter = "";

  if ($_SERVER["REQUEST_METHOD"] === "POST" 
      && isset($_POST["student_filter"])) {

      $selected_student_filters = $_POST["student_filter"];

      foreach ($selected_student_filters as $selected_filter) {
          // echo $selected_filter . "<br>";

        $selected_student_filter = $selected_filter;
      }
  }

  // echo "selected_student_filter: $selected_student_filter";

  $period_short = $current_school_year_period === "First" ? "S1" : ($current_school_year_period === "Second" ? "S2" : "");

?>

  <div class="content">
<<<<<<< HEAD
    
=======
>>>>>>> ab36c1169e9eadbb944d7c8c6d0b4ebe6a6bd812
    <div class="content-header">
      <header>
        <div class="title">
          <h1>Enrollment form <em>SHS</em></h1>
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
        style="background-color: var(--theme); color: white"
        onclick="window.location.href = 'evaluation.php';"
      >
        Evaluation (<?php echo $unionEnrollmentCount;?>)
      </button>
        
      <button
        class="tab"
        id="shsPayment"
        style="background-color: var(--them); color: white"
        onclick="window.location.href = 'waiting_payment.php';"
      >
        Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
      </button>
      <button
        class="tab"
        id="shsApproval"
<<<<<<< HEAD
        style="background-color: var(--mainContentBG); color: white"
        onclick="window.location.href = 'waiting_approval.php';">

=======
        style="background-color: var(--mainContentBG); color: black"
        onclick="window.location.href = 'waiting_approval.php';"
      >
>>>>>>> ab36c1169e9eadbb944d7c8c6d0b4ebe6a6bd812
        Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
      </button>

      <button
          class="tab"
          id="shsEnrolled"
          style="background-color: var(--them); color: white"
          onclick="window.location.href = 'enrolled_sections.php';"
        >
          Enrolled (<?php echo $sectionEnrolledStudentListCount;?>)
        </button>
    </div>

    <main>

      <div class="floating">
          <header>
            <div class="title">
              <h3>Form details</h3>
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
            <table>
              <tr>
                <th style="border-right: 2px solid black">Search by</th>
                <td><button>Name</button></td>
                <td><button>Form</button></td>
                <td><button>Student ID</button></td>
              </tr>
            </table>

            <table style="width: 100%" id="waiting_payment_table" class="a">
                <thead>
                    <tr>
                        <th>Student No</th>  
                        <th>Form</th>
                        <th>Name</th>
                        <th>Type</th>  
                        <th>Strand</th>
                        <th>Processed Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
          </main>
        </div>


    </main>
    
  </div>

<script>

  function submitForm() {
      document.getElementById("student_filter_form").submit();
  }

  function handleCheckboxChange(checkboxId) {
      if (checkboxId === "new") {
          if (document.getElementById("old").checked) {
              document.getElementById("old").checked = false;
          }
      } else if (checkboxId === "old") {
          if (document.getElementById("new").checked) {
              document.getElementById("new").checked = false;
          }
      }
      document.getElementById("student_filter_form").submit();
  }

  $(document).ready(function() {

      var selected_student_filter = `
        <?php echo $selected_student_filter; ?>
      `;

      selected_student_filter = selected_student_filter.trim();

      var table = $('#waiting_payment_table').DataTable({
          'processing': true,
          'serverSide': true,
          'serverMethod': 'POST',
          'ajax': {
              'url': `waitingApprovalListData.php?admission_type_filter=${selected_student_filter}`,
              'error': function(xhr, status, error) {
                  // Handle error response here
                  console.error('Error:', error);
                  console.log('Status:', status);
                  console.log('Response Text:', xhr.responseText);
                  console.log('Response Code:', xhr.status);
              }
          },
          'pageLength': 10,
          'language': {
              'infoFiltered': '',
              'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
              'emptyTable': "No available data for waiting approval."
            },
          'columns': [
            { data: 'student_id', orderable: false },  
            { data: 'form_id', orderable: false },
            { data: 'name', orderable: false },  
            { data: 'type', orderable: false },
            { data: 'section_name', orderable: false },  
            { data: 'cashier_confirmation_date', orderable: false },
            { data: 'button_url', orderable: false }
          ],
          // 'ordering': true
      });
  });



</script>






<?php include_once('../../includes/footer.php') ?>
