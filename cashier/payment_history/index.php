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
                        <h2>Confirmed Payment History</em></h1>
                        
                    </div>
                </header>
            </div>
    
            <main>


                <div class="col-md-12">
                    <form method="POST">
                        <div class="row invoice-info">
                            
                            <div class="col-sm-3 invoice-col">
                                Academic Year
                                <select name="school_year_id" id="school_year_id" class="form-control">
                                    <?php 
                                        $query = $con->prepare("SELECT t1.*
                                            FROM school_year AS t1
                                        ");

                                        // $query->bindParam(":condition2", $Tertiary);
                                        $query->execute();
                                        if($query->rowCount() > 0){

                                            echo "
                                                <option value='' selected>Select Term</option>
                                            ";

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $term = $row['term'];
                                                $period = $row['period'];
                                                $school_year_id = $row['school_year_id'];

                                                $selected = "";
                                                if($sy_id == $school_year_id){
                                                    $selected = "selected";
                                                }
                                                echo "
                                                    <option $selected value='$school_year_id'>$term $period Semester</option>
                                                ";
                                            }
                                        }
                                    ?>
                                </select>

                            </div>

                            <div class="col-sm-3 invoice-col">
                                Offered Program

                                <select name="program_id" id="program_id" class="form-control">
                                    <?php 
                                        $query = $con->prepare("SELECT t1.*

                                            FROM program AS t1
                                        ");

                                        // $query->bindParam(":condition2", $Tertiary);
                                        $query->execute();
                                        if($query->rowCount() > 0){

                                            echo "
                                                <option value='' selected>Choose Program</option>
                                            ";

                                            while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                $program_name = $row['program_name'];
                                                $acronym = $row['acronym'];
                                                $program_id = $row['program_id'];

                                                $selected = "";
                                                if($selected_program_id == $program_id){
                                                    $selected = "selected";
                                                }
                                                echo "
                                                    <option $selected value='$program_id'>$acronym</option>
                                                ";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="col-sm-3 invoice-col">
                                Program - Section
                                    <select name="course_id" id="course_id"  class="form-control">
                                        <?php 

                                            if($selected_course_id != "") {
                                                $query = $con->prepare("SELECT t1.*

                                                FROM course AS t1
                                                WHERE t1.course_id=:course_id
                                                ");

                                                $query->bindParam(":course_id", $selected_course_id);
                                                $query->execute();

                                                if($query->rowCount() > 0){

                                                    $row = $query->fetch(PDO::FETCH_ASSOC);

                                                    $program_section = $row['program_section'];
                                                    // $acronym = $row['acronym'];
                                                    $course_id = $row['course_id'];

                                                    $selected = "";
                                                    if($selected_course_id == $course_id){
                                                        $selected = "selected";
                                                    }
                                                    echo "
                                                        <option $selected value='$course_id'>$program_section</option>
                                                    ";
                                                }   
                                            }
                                            
                                        ?>
                                    </select>
                            </div>


                            <div class="col-sm-0 invoice-col"> 
                                <br>
                                <div class="form-group"> 
                                    <button type="submit" name="schedule_btn2" class="btn btn-primary">
                                        <i class="fas fa-search fa-1x"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-sm-0 invoice-col"> 
                                <br>
                                <div class="form-group"> 
                                    <button type="submit" name="reset_btn" class="btn btn-outline-primary">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>


                <div class="floating">

                    <table class="a">
                    <tr>
                        <th style="border-right: 2px solid black">Search by</th>
                        <td><button>Name</button></td>
                        <td><button>Email</button></td>
                        <td><button>Student ID</button></td>
                    </tr>
                    </table>
                    
                    <table style="width: 100%" id="cashier_history_table" class="a">
                        <thead>
                            <tr>
                                <th>Form ID</th>  
                                <th>Student No</th>  
                                <th>Name</th>
                                <th>Section</th>
                                <th>Date Confirmed</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </main>
        </main>
    </div>

<?php include_once('../../includes/footer.php') ?>

<script>

    $(document).ready(function() {

        var table = $('#cashier_history_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': 'historyPaymentListData.php',
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
                'emptyTable': "No available data for payment history.",
            
            },
            'columns': [
              { data: 'enrollment_form_id', orderable: true },  
              { data: 'student_no', orderable: false },  
              { data: 'name', orderable: false },
              { data: 'section', orderable: false },  
              { data: 'cashier_confirmation_date', orderable: true },
              { data: 'button_url', orderable: false }
            ],
            'ordering': true
        });
    });
</script>
