

<?php 
    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');

    require "../../vendor/autoload.php";


    if(isset($_POST['reset_student_password']) && isset($_POST['student_username'])){

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
                echo "Email reset password has been sent to: $student_email";
            }else{
                echo "Sending reset password via email went wrong";
            }

        }else{;
            echo "Password did not reset";
        }

    }


  
?>
  

<div class="content">
    <main>

        <div class="floating">
          <header>
            <div class="title">
              <h3>Find Student</h3>
            </div>
          </header>
          <div class="filters">
            <table>
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
                    <h3 style="font-weight: bold;">Student Details</h3>
                </div>

               
            </header>
            <main>
                <table id="department_table" class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                    <thead>
                        <tr class="text-center"> 
                            <th rowspan="2" >Student Id</th>  
                            <th rowspan="2" >Name</th>
                            <th rowspan="2"">Level</th>  
                            <th rowspan="2"">Section</th>  
                            <th rowspan="2"">Status</th>  
                            <th rowspan="2"">Type</th>  
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
                                    $program_section = $row['program_section'];
                                    $student_id = $row['student_id'];
                                    $student_username = $row['username'];
                                    $course_level = $row['course_level'];
                                    $active = $row['active'];
                                    $new_enrollee = $row['new_enrollee'];

                                    $status = $active == 1 ? "Active" : ($active == 0 ? "Inactive" : "");
                                    $type = $new_enrollee == 1 ? "New" : ($new_enrollee == 0 ? "Old" : "");

                                    echo "<tr class='text-center'>";

                                        echo "
                                            <td>$student_id</td>
                                            <td>$name</td>
                                            <td>$course_level</td>
                                            <td>$program_section</td>
                                            <td>$status</td>
                                            <td>$type</td>
                                            <td>
                                                <button type='submit' 
                                                name='reset_student_password' 
                                                class='default'
                                                onclick=\"window.location.href = 'record_details.php?id=$student_id&details=show';\"
                                                >
                                                View
                                                </button>
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