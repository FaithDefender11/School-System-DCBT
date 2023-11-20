

<?php 
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');

    // require "../../vendor/autoload.php";
    require_once __DIR__ . '../../../vendor/autoload.php';

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


    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_term = $school_year->getSchoolYearValue($school_year_obj, 'term');
    $current_school_year_period = $school_year->getSchoolYearValue($school_year_obj, 'period');
    $current_school_year_id = $school_year->getSchoolYearValue($school_year_obj, 'school_year_id');


  
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
                    <h3 style="font-weight: bold;">Account details for <span class="text-primary">Pending Enrollees </span> | 
                        <a href="index.php" class="text-muted">
                            Enrolled students
                        </a>
                    </h3>
                </div>
            </header>

            <main>

                <table id="" class="a" style="margin: 0">

                    <thead>
                        <tr class="text-center"> 
                            <th>Enrollee Id</th>  
                            <th>Name</th>  
                            <th>Email</th>  
                            <th>Type</th>  
                            <th>Program</th>  
                            <th>Level</th>  
                            <th style="min-width: 100px;">Term</th>  
                            <!-- <th>Enrollee Status</th>  
                            <th>Admission Status</th>  
                            <th>Enrollment Status</th>   -->
                            <th>Action</th>  

                        </tr>	
                    </thead>

                    <tbody>
                        <?php 

                            $query = $con->prepare("SELECT t1.* 
                            
                                FROM pending_enrollees AS t1
                                
                                INNER JOIN student_requirement as t2 ON t2.pending_enrollees_id = t1.pending_enrollees_id

                                WHERE t1.is_enrolled = 0
                                AND (
                                    t1.student_status IS NOT NULL AND 
                                    t1.student_status != '' 
                                    -- AND
                                    -- t1.student_status != 'APPROVED'
                                )
                            ");

                            $query->execute();

                            if($query->rowCount() > 0){
                                
                                $enrollment = new Enrollment($con);

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $pending_enrollees_id = $row['pending_enrollees_id'];

                                    $enrolleeFullName = ucwords($row['firstname']) . " " . ucwords($row['middle_name']) . " " . ucwords($row['lastname']);
                                    $program_id = $row['program_id'];
                                    $type = $row['type'];
                                    $email = $row['email'];
                                    $course_level = $row['course_level'];
                                    $school_year_id = $row['school_year_id'];

                                    $sy = new SchoolYear($con, $school_year_id);
                                    $period = $sy->GetPeriod();
                                    $term = $sy->GetTerm();


                                    $student_status =  ($row['student_status']);


                                    $enrollment_status = $row['enrollment_status'];
                                    $admission_status = $row['admission_status'];

                                    $program = new Program($con, $program_id);

                                    $programName = $program->GetProgramName();
                                    $acronym = $program->GetProgramAcronym();

                                    $url = "view_new_enrollee.php?id=$pending_enrollees_id";

                                    $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");
                                    
                                    $term = $enrollment->changeYearFormat($term);
                                    $format = $term . "-" .  $period_short;


                                    //  <td>$student_status</td>
                                    //         <td>$enrollment_status</td>
                                    //         <td>$admission_status</td>

                                    $resetPasswordBtn = "";
                                    if($school_year_id == $current_school_year_id){

                                        $resetPasswordBtn = "
                                            <button type='button' onclick='resetUnEnrolledEnrollee($pending_enrollees_id)'
                                                class='btn btn-sm danger'>Reset password
                                            </button>
                                        ";
                                    }

                                    echo "
                                        <tr>
                                            <td>$pending_enrollees_id</td>
                                            <td>$enrolleeFullName</td>
                                            <td>$email</td>
                                            
                                            <td>$type</td>
                                            <td>$acronym</td>
                                            <td>$course_level</td>
                                            <td>SY$format</td>
                                           
                                            <td>
                                                $resetPasswordBtn
                                            </td>
                                        </tr>
                                    ";
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


    function resetUnEnrolledEnrollee(pending_enrollees_id){

        var pending_enrollees_id = parseInt(pending_enrollees_id);

        Swal.fire({

            icon: 'question',
            title: `Do you want to reset the selected enrollee password? <br> Note: This action cannot be undone.`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'

        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/student/resetEnrolleePassword.php',
                    type: 'POST',
                    data: {
                        pending_enrollees_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                        if(response == "enrollee_reset_success"){

                            Swal.fire({
                                icon: 'success',
                                title: `Selected enrollee password has been reset.`,
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