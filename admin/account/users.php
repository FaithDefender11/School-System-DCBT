

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');

    // require "../../vendor/autoload.php";
    require_once __DIR__ . '../../../vendor/autoload.php';

    if(isset($_POST['reset_student_password'])  && isset($_POST['student_username'])){

        try {

            $student_username = $_POST['student_username'];

            // var_dump($student_username);
            // return;

            // Assuming you have a Student class with a GetEmail and ResetPassword method
            $student = new Student($con, $student_username);

            $email = new Email();

            $student_email = $student->GetEmail();

            // var_dump($student_email);
            // return;

            $temporaryPassword = $student->ResetPassword($student_username);

            if (!empty($student_email) && filter_var($student_email, FILTER_VALIDATE_EMAIL) &&
                count($temporaryPassword) > 0 && $temporaryPassword[1] == true) {

                $isEmailSent = $email->SendTemporaryPassword($student_email, $temporaryPassword[0]);

                if ($isEmailSent) {
                    Alert::success("Email reset password has been sent to: $student_email", "");
                } else {
                    echo "Sending reset password via email went wrong";
                }

            } 
            else {
                echo "Invalid email address or password reset failed";
            }

        } catch (Exception $e) {
            // Handle PHPMailer exceptions
            echo 'Message could not be sent. PHPMailer Error: ' . $e->getMessage();
            // Handle other exceptions as needed
        }
    }


  
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
                    <h3 style="font-weight: bold;">Account details for <span class="text-primary">Authorized Users </span> | 
                        <a href="un_enrolled.php" class="text-muted">
                            Pending enrollees
                        </a> | 
                        <span class="text-info">
                            <a href="index.php" class="text-muted">
                                Enrolled students
                            </a>
                        </span>
                    </h3>

                </div>
            </header>

            <main>
                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr class="text-center"> 
                            <th>Role</th>  
                            <th style="min-width: 130px" >Name</th>
                            <th>Username</th>  
                            <th>Email</th>  
                            <!-- <th">Status</th>   -->
                            <th></th>  
                        </tr>	
                    </thead> 	
                    <tbody>
                        <?php 
                            $active = 1;
                            $query = $con->prepare("SELECT t1.*
                                
                                FROM users as t1

                            
                            ");

                            // $query->bindValue(":active", $active);
                            $query->execute();

                            if($query->rowCount() > 0){

                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                    $name = $row['firstName'] . " " . $row['lastName'];

                                    $user_id = $row['user_id'];

                                    $unique_id = $row['unique_id'];
                                    $email = $row['email'];
                                    $username = $row['username'];
                                    $role = $row['role'];

                                    $resetUsers = "
                                        <button type='button' onclick='resetUsers($user_id)'
                                            class='btn btn-sm danger'>Reset password
                                        </button>
                                    ";

                                    // $deactivateAccount = "
                                    //     <button type='button' onclick='deactivateStudent($student_id)'
                                    //         class='btn btn-sm btn-outline-warning'>Deactivate
                                    //     </button>
                                    // ";


                                    echo "<tr class='text-center'>";
                                        echo "
                                            <td>$role</td>
                                            <td>$name</td>
                                            <td>$username</td>
                                            <td>$email</td>
                                            <td>
                                               $resetUsers
                                            </td>
                                        ";
                                    echo "</tr>";

                                }
                            }
                        ?>
                    </tbody>
                </table>

            </main>
        </div>
    </main>
</div>

<script>


    function resetUsers(user_id){

        var user_id = parseInt(user_id);

        Swal.fire({
            icon: 'question',
            title: `Do you want to reset the selected users password? <br> Note: This action cannot be undone.`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/student/resetUsersPassword.php',
                    type: 'POST',
                    data: {
                        user_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "user_reset_success"){

                            Swal.fire({
                                icon: 'success',
                                title: `Selected user password has been reset.`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                // location.reload();
                                window.location.href = "users.php";
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