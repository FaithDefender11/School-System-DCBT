

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');

    require "../../vendor/autoload.php";

    if(isset($_POST['reset_student_password'])  && isset($_POST['student_username'])){

        try {

            $student_username = $_POST['student_username'];

            // $enroll = new StudentEnroll($con);
            $student = new Student($con, $student_username);

            $email = new Email();

            $student_email = $student->GetEmail();
            $b = $student->GenerateBirthdayAsPassword();

            // echo $b;
            $temporaryPassword = $student->ResetPassword($student_username);

            if(count($temporaryPassword) > 0 && $temporaryPassword[1] == true){

                $isEmailSent = $email->SendTemporaryPassword($student_email,
                    $temporaryPassword[0]);

                if($isEmailSent == true){
                // if(true){
                    Alert::success("Email reset password has been sent to: $student_email", "");
                }else{
                    echo "Sending reset password via email went wrong";
                }

            }else{
                echo "Password did not reset";
            }

            //code...
        } catch (Exception $e) {

            // Handle PHPMailer exceptions
            echo 'Message could not be sent. PHPMailer Error: ' . $e->getMessage();
            //  echo 'SMTP Error Code: ' . $mail->smtp->getError()['error'];
            // Check for specific errors and provide alternative messages
            if (strpos($e->getMessage(), 'Maximum execution time') !== false) {
                echo 'Email sending timed out. Please try again later or contact support.';
            }
        }

        

        

    }


  
?>
  

<div class="content">
    <main>

        <div class="floating">
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

        </div>

        <div class="floating" id="shs-sy">

            <header>
                <div class="title">
                    <h3 style="font-weight: bold;">Account Details</h3>
                </div>
            </header>

            <main>
                <table id="department_table" class="a" style="margin: 0">
                    <thead>
                        <tr class="text-center"> 
                            <th rowspan="2" >Student Id</th>  
                            <th rowspan="2" >Username</th>  
                            <th rowspan="2" >Name</th>
                            <th rowspan="2"">Grade Level</th>  
                            <th rowspan="2"">Section</th>  
                            <th rowspan="2"">Status</th>  
                            <th rowspan="2"></th>  
                        </tr>	
                    </thead> 	
                    <tbody>
                        <?php 
                            $active = 1;

                            $query = $con->prepare("SELECT t1.*,
                                t2.program_section 
                                
                                FROM student as t1

                                LEFT JOIN course as t2 ON t1.course_id = t2.course_id
                                WHERE t1.active=:active");

                            $query->bindValue(":active", $active);
                            $query->execute();

                            if($query->rowCount() > 0){

                                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {

                                    $name = $row['firstname'] . " " . $row['lastname'];
                                    $program_section = $row['program_section'] == 0 ? "NE" : $row['program_section'];
                                    $student_id = $row['student_id'];

                                    $student_username = $row['username'];
                                    $email = $row['email'];

                                    $course_level = $row['course_level'] == 0 ? "NE" : $row['course_level'];

                                    $status = "";

                                    $username_output = "";

                                    if($student_username == ""){
                                        $status = "Not enrolled";
                                        $username_output = $email;
                                    }else{
                                        $status = "enrolled";
                                        $username_output = $student_username;

                                    }

                                    echo "<tr class='text-center'>";
                                        echo "
                                            <td>$student_id</td>
                                            <td>$username_output</td>
                                            <td>$name</td>
                                            <td>$course_level</td>
                                            <td>$program_section</td>
                                            <td>$status</td>
                                            <td>
                                                <form method='POST'>
                                                    <input name='student_username' type='hidden' value='$student_username'>
                                                    <button type='submit' name='reset_student_password' class='danger'>Reset Password</button>
                                                </form>
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