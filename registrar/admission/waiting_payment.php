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

    $pendingEnrollmentCount = 0;
    $unionEnrollmentCount = 0;
    $waitingApprovalEnrollmentCount = 0;
    $enrolledStudentsEnrollmentCount = 0;


    $unionEnrollmentCount = count($unionEnrollment);
    $waitingPaymentEnrollmentCount = count($waitingPaymentEnrollment);
    $waitingApprovalEnrollmentCount = count($waitingApprovalEnrollment);
    $enrolledStudentsEnrollmentCount = count($enrolledStudentsEnrollment);


?>

    <div class="content">
     
      <div class="content-header">
        <header>
          <div class="title">
            <h1>Enrollment form <em>SHS</em></h1>
            <small
              >Note: Numbers on tabs only count current school year and
              semester</small
            >
          </div>
        </header>
      </div>

      <div class="tabs">
        <button
          class="tab"
          id="shsEvaluation"
          style="background-color: var(--them)"
          onclick="window.location.href = 'evaluation.php';"
        >
          Evaluation (<?php echo $unionEnrollmentCount;?>)
        </button>
         
        <button
          class="tab"
          id="shsPayment"
          style="background-color: var(--mainContentBG); color: white"
          onclick="window.location.href = 'waiting_payment.php';"
        >
          Waiting payment (<?php echo $waitingPaymentEnrollmentCount;?>)
        </button>
        <button
          class="tab"
          id="shsApproval"
          style="background-color: var(--them); color: white"
          onclick="window.location.href = 'waiting_approval.php';"
        >
          Waiting approval (<?php echo $waitingApprovalEnrollmentCount;?>)
        </button>
        <button
          class="tab"
          id="shsEnrolled"
          style="background-color: var(--them); color: white"
          onclick="window.location.href = 'enrolled_students.php';"
        >
          Enrolled (<?php echo $enrolledStudentsEnrollmentCount;?>)
        </button>
      </div>

      <main>

        <div class="floating">
          <header>
            <div class="title">
              <h3>Form details</h3>
            </div>
            <div class="action">
              <button class="default">Select all</button>
              <button class="default">Un-select all</button>
              <div class="dropdown">
                <button class="icon">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item" style="color: red"
                    ><i class="bi bi-file-earmark-x"></i>Delete form</a
                  >
                </div>
              </div>
            </div>
          </header>

          <main>
            <table>
              <tr>
                <th style="border-right: 2px solid black">Search by</th>
                <td><button>Student ID</button></td>
                <td><button>Form ID</button></td>
                <td><button>Name</button></td>
              </tr>
            </table>

            
            <table style="width: 100%" id="waiting_payment_table" class="a">
                <thead>
                    <tr>
                        <th>Student No</th>  
                        <th>Form</th>
                        <th>Name</th>
                        <!-- <th>Email</th> -->
                        <th>Type</th>  
                        <th>Strand</th>
                        <th>Date Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>

          </main>
        </div>
      </main>
    </div>

<script>

    $(document).ready(function() {

        var table = $('#waiting_payment_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': 'waitingPaymentListData.php',
                'error': function(xhr, status, error) {
                    // Handle error response here
                    console.error('Error:', error);
                    console.log('Status:', status);
                    console.log('Response Text:', xhr.responseText);
                    console.log('Response Code:', xhr.status);
                }
            },

            'pageLength': 5,
            'language': {
                'infoFiltered': '',
                'processing': '<i class="fas fa-spinner fa-spin"></i> Processing...',
                'emptyTable': "No available data for waiting payment.",
            
            },
            'columns': [
              { data: 'student_id', orderable: true },  
              { data: 'form_id', orderable: false },  
              { data: 'name', orderable: false },  
              // { data: 'email', orderable: true },
              { data: 'type', orderable: false },
              { data: 'acronym', orderable: false },  
              { data: 'registrar_confirmation_date', orderable: true },
              { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });
    });
</script>



<?php include_once('../../includes/footer.php') ?>
