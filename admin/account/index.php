

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
            </header>

            <main>
                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr class="text-center"> 
                            <th >ID</th>  
                            <th >Username</th>  
                            <th >Email</th>  
                            <th style="min-width: 130px" >Name</th>
                            <th>Grade Level</th>  
                            <th>Section</th>  
                            <!-- <th">Status</th>   -->
                            <th></th>  
                        </tr>	
                    </thead> 	
                    <tbody>
                        <?php 
                            $active = 1;

                            $query = $con->prepare("SELECT t1.*,
                                t2.program_section 
                                
                                FROM student as t1

                                LEFT JOIN course as t2 ON t1.course_id = t2.course_id
                                WHERE t1.active=:active
                                AND t1.username IS NOT NULL
                                AND t1.student_unique_id IS NOT NULL
                            
                            ");

                            $query->bindValue(":active", $active);
                            $query->execute();

                            if($query->rowCount() > 0){

                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                    $name = $row['firstname'] . " " .  $row['middle_name']. " " . $row['lastname'];
                                    $program_section = $row['program_section'] == 0 ? "NE" : $row['program_section'];
                                    $student_id = $row['student_id'];
                                    $student_unique_id = $row['student_unique_id'];
                                    $email = $row['email'];

                                    $student_username = $row['username'];
                                    $email = $row['email'];

                                    $course_level = $row['course_level'] == 0 ? "NE" : $row['course_level'];

                                    $status = "";

                                    $username_output = "";

                                    if($student_username == ""){
                                        $status = "Not enrolled";
                                        $username_output = $email;
                                    }else{
                                        $status = "Enrolled";
                                        $username_output = $student_username;
                                    }
                                    

                                    // <td>
                                    //             <form method='POST'>
                                    //                 <input name='student_username' type='hidden' value='$username_output'>
                                    //                 <button type='submit' name='reset_student_password' class='danger'>Reset Password</button>
                                    //             </form>
                                    //         </td>

                                    $resetPasswordBtn = "
                                        <button type='button' onclick='resetEnrolledStudent($student_id)'
                                            class='btn btn-sm danger'>Reset password
                                        </button>
                                    ";

                                    $deactivateAccount = "
                                        <button type='button' onclick='deactivateStudent($student_id)'
                                            class='btn btn-sm btn-outline-warning'>Deactivate
                                        </button>
                                    ";
                                            // <td>$status</td>


                                    echo "<tr class='text-center'>";
                                        echo "
                                            <td>$student_unique_id</td>
                                            <td>$username_output</td>
                                            <td>$email</td>
                                            <td>$name</td>
                                            <td>$course_level</td>
                                            <td>$program_section</td>
                                            <td>
                                               $resetPasswordBtn
                                               $deactivateAccount
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

</script>