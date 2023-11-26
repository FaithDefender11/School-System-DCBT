

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');

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
            </style>

            <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
            
            <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

        </head>
    <?php

    // require "../../vendor/autoload.php";
    require_once __DIR__ . '../../../vendor/autoload.php';

    $student_unique_id_val = NULL;
    $search_word = NULL;

    $selected_student_filter = "";

    if ($_SERVER["REQUEST_METHOD"] === "POST" 
        && isset($_POST["student_filter"])) {

        $selected_student_filters = $_POST["student_filter"];

        foreach ($selected_student_filters as $selected_filter) {
            // echo $selected_filter . "<br>";

        $selected_student_filter = $selected_filter;

        }
    }


    // if(isset($_POST['reset_student_password'])  && isset($_POST['student_username'])){

    //     try {

    //         $student_username = $_POST['student_username'];

    //         // var_dump($student_username);
    //         // return;

    //         // Assuming you have a Student class with a GetEmail and ResetPassword method
    //         $student = new Student($con, $student_username);

    //         $email = new Email();

    //         $student_email = $student->GetEmail();

    //         // var_dump($student_email);
    //         // return;

    //         $temporaryPassword = $student->ResetPassword($student_username);

    //         if (!empty($student_email) && filter_var($student_email, FILTER_VALIDATE_EMAIL) &&
    //             count($temporaryPassword) > 0 && $temporaryPassword[1] == true) {

    //             $isEmailSent = $email->SendTemporaryPassword($student_email, $temporaryPassword[0]);

    //             if ($isEmailSent) {
    //                 Alert::success("Email reset password has been sent to: $student_email", "");
    //             } else {
    //                 echo "Sending reset password via email went wrong";
    //             }

    //         } 
    //         else {
    //             echo "Invalid email address or password reset failed";
    //         }

    //     } catch (Exception $e) {
    //         // Handle PHPMailer exceptions
    //         echo 'Message could not be sent. PHPMailer Error: ' . $e->getMessage();
    //         // Handle other exceptions as needed
    //     }
    // }


  
?>
  

<div class="content">
    
    <main>

        <!-- <div class="floating">
          <header>
            <div class="title">
              <h3>Find Account</h3>
            </div>
          </header>
          <div class="filters">
            <table class="a">
              <tr>
                <th rowspan="2" style="border-right: 2px solid black">
                  Search by
                </th>
                <th><button>ID number</button></th>
                <th><button>Account type</button></th>
                <th><button>Email</button></th>
                <th><button>Name</button></th>
              </tr>

              
            </table>
          </div>
          <main>
            <input type="text" />
            <button type="submit"><i class="bi bi-search"></i>Search</button>
          </main>

        </div> -->

                <div class="floating" id="shs-sy">
                    <header>
                        <div class="title">
                            <h3 style="font-weight: bold;">Account details for <span class="text-primary">Enrolled students</span> | 
                                <a href="un_enrolled.php" class="text-muted">
                                    Pending enrollees
                                </a> | 
                                    <span class="text-info"><a href="users.php" class="text-muted">
                                        Authorized Users
                                    </a>
                                </span>
                            </h3>
                        </div>
                    
                        <div class="action">
                            <form style="display: flex;" method="POST" id="student_filter_form">
                                <div style="margin-right: 15px;" class="form-group">
                                    <label for="active">Active</label>
                                    <input type="checkbox" id="active" name="student_filter[]" value="1" 
                                        class="form-control" 
                                        onchange="handleCheckboxChange('active')" <?php if (isset($_POST["student_filter"]) && in_array("1", $_POST["student_filter"])) echo "checked"; ?>>

                                </div>
                                <div class="form-group">
                                    <label for="inactive">Inactive</label>
                                    <input type="checkbox" id="inactive" name="student_filter[]" value="0" 
                                        class="form-control" onchange="handleCheckboxChange('inactive')" <?php if (isset($_POST["student_filter"]) && in_array("0", $_POST["student_filter"])) echo "checked"; ?>>

                                </div>

                                
                            </form>
                        </div>

                    </header>

                    <main>
                        <table style="width: 100%" id="student_table" class="a">
                            <thead>
                                <tr>
                                    <th>ID</th>  
                                    <th>Username</th>
                                    <th>Email</th>  
                                    <th>Name</th>  
                                    <th>Status</th>  
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
        if (checkboxId === "active") {
            if (document.getElementById("active").checked) {
                document.getElementById("inactive").checked = false;
            }
        } else if (checkboxId === "inactive") {
            if (document.getElementById("inactive").checked) {
                document.getElementById("active").checked = false;
            }
        }
        document.getElementById("student_filter_form").submit();
    }

    $(document).ready(function() {

        var selected_student_filter = `
            <?php echo $selected_student_filter; ?>
        `;

        selected_student_filter = selected_student_filter.trim();

        var table = $('#student_table').DataTable({
            'processing': true,
            'serverSide': true,
            'serverMethod': 'POST',
            'ajax': {
                'url': `studentListData.php?status=${selected_student_filter}`,
                // 'success': function(data) {
                //   // Handle success response here
                //   console.log('Success:', data);
                // },
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
                'emptyTable': "No available data to be evaluated.",
            },
            'columns': [
                { data: 'student_id', orderable: true },
                { data: 'username' , orderable: false },
                { data: 'email', orderable: false},
                { data: 'name', orderable: false},
                { data: 'status', orderable: false},
                { data: 'button', orderable: false}
            ],
            'ordering': true

        });

        var ad = table.context;

        // console.log(ad.json)
        var sec = ad[0];

        // console.log(sec['']);
        // sec -> json -> data = all data in the server placed in that array.
        // console.log(sec);
    });


    function resetEnrolledStudent(student_id){

        var student_id = parseInt(student_id);

        Swal.fire({
            icon: 'question',
            title: `Do you want to reset the selected student password? <br> Note: This action cannot be undone.`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/student/resetStudentPassword.php',
                    type: 'POST',
                    data: {
                        student_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "student_reset_success"){

                            Swal.fire({
                                icon: 'success',
                                title: `Selected student password has been reset.`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                // location.reload();
                                window.location.href = "index.php";
                            }, 1900);
 
                        }

                        // if (response === "success_update") {

                        //     Swal.fire({
                        //         icon: 'success',
                        //         title: `Selected form has been rejected.`,
                        //     });
                        // } 

                        // else {
                        //     console.log('Update failed');
                        // }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }

    function deactivateStudent(student_id, status){

        var student_id = parseInt(student_id);

        Swal.fire({
            icon: 'question',
            title: `Do you want to change users into ${status} `,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'

        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/student/changeStudentStatus.php',
                    type: 'POST',
                    data: {
                        student_id,
                        userStatus: status
                    },
                    success: function(response) {

                        response = response.trim();

                        // console.log(response);

                        if(response == "success_update"){

                            Swal.fire({
                                icon: 'success',
                                title: `Selected student status has been changed`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                // location.reload();
                                window.location.href = "index.php";
                            }, 1900);
 
                        }

                        // if (response === "success_update") {

                        //     Swal.fire({
                        //         icon: 'success',
                        //         title: `Selected form has been rejected.`,
                        //     });
                        // } 

                        // else {
                        //     console.log('Update failed');
                        // }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }


</script>