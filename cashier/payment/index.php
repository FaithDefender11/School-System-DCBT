<?php 

    include_once('../../includes/cashier_header.php');
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
 

?>



    <div class="content">

      <main>
        
                  <div class="content-header">
        <header>
          <div class="title">
            <h1>Enrollment payment</h1>
            <small
              >Note: Numbers on tabs only count current school year and
              semester</small
            >
          </div>
        </header>
      </div>
 

      <main>

        <div class="floating">

            <table class="a">
              <tr>
                <th style="border-right: 2px solid black">Search by</th>
                <td><button>Name</button></td>
                <td><button>Email</button></td>
                <td><button>Student ID</button></td>
              </tr>
            </table>
            
            <table style="width: 100%" id="waiting_payment_table" class="a">
                <thead>
                    <tr>
                        <th>Student No</th>  
                        <th>Name</th>
                        <th>Email</th>
                        <!-- <th>Type</th>   -->
                        <th>Strand</th>
                        <!-- <th>Waitlist</th> -->
                        <th>Date Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>

        </div>

      </main>

      </main>

    </div>

<script>

    $(document).ready(function() {

        var table = $('#waiting_payment_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': 'waitingCashierPaymentListData.php',
                // 'success': function(data) {
                //     // Handle success response here
                //     console.log('Success:', data);
                // },
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
              { data: 'student_id', orderable: false },  
              { data: 'name', orderable: false },  
              // { data: 'email', orderable: true },
              { data: 'email', orderable: false },
              { data: 'acronym', orderable: false },  
              // { data: 'waiting_result', orderable: false },  
              { data: 'registrar_confirmation_date', orderable: false },
              { data: 'button_url', orderable: false }
            ],
            'ordering': false
        });
    });
</script>

<?php include_once('../../includes/footer.php') ?>
