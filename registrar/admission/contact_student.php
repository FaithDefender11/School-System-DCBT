<?php 

  include_once('../../includes/registrar_header.php');
  include_once('../../includes/classes/Pending.php');
  include_once('../../includes/classes/Section.php');
  include_once('../../includes/classes/SchoolYear.php');
  include_once('../../includes/classes/Email.php');


    // Helper::RemoveSidebar();

    ?>
        <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        </head>
    <?php

    require "../../vendor/autoload.php";

    use PHPMailer\PHPMailer\PHPMailer;

    $mail = new PHPMailer(true);

    if(isset($_GET['id'])){

        $student_id = $_GET['id'];


        $student = new Student($con, $student_id);
        $fullname =  ucwords($student->GetLastName()) . ", " . ucwords($student->GetFirstName()) . " " . ucfirst($student->GetMiddleName());


        $email_address = $student->GetEmail();

        // var_dump($email_address);

        $back_url = "../requirements/view_student.php?id=$student_id";


        if($_SERVER["REQUEST_METHOD"] === "POST" &&
            isset($_POST['enrollee_message_' . $student_id])
            && isset($_POST['subject'])
            && isset($_POST['input_message'])
            ){


            // echo "hey";

            $subject = trim(ucfirst($_POST['subject']));
            $message = trim(ucfirst($_POST['input_message']));

            // var_dump($subject);
            // return;

            try {

                // $email = new Email();
                $email = new Email();

                $isEmailSent = $email->SendMessageViaEmail($email_address,
                    $message, $subject);
                
                if ($isEmailSent) {

                    Alert::success("Successfully Message Sent", $back_url);
                    exit();

                }

             } catch (Exception $e) {

                $errorLog = "Email Sending Error: " . $e->getMessage();

                echo "<script>
                    $(document).ready(function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oh no!',
                            text: 'Sending email is not working. Please contact the school administrator. {$mail->ErrorInfo} {$errorLog}',
                            backdrop: false,
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '$back_url'`;
                            }
                        });
                    });
                </script>";
                exit();
            }

        }


        // echo $student_id;

        ?>
        <div class='col-md-10 row offset-md-1'>

            <div style="max-width: 100%;" class='card'>

                <div class='card-header'>

                    <a href="<?php echo $back_url;?>">
                        <button class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i>
                        </button>
                    </a>

                    <h4 class='text-center mb-3'>Contact Enrollee: <span><?php echo $fullname; ?></span> &nbsp; <span class="text-primary"><?= $email_address;?></span> </h4>

                </div>

                <div class='card-body'>
                    <form method='POST'>

                        <div class='form-group mb-2'>
                            <label for='subject'>* Subject</label>
                            <input class="form-control" type="text" name="subject" id="subject">
                        </div>

                        <div class='form-group mb-2'>
                            <label for='input_message'>* Message</label>
                            <textarea class="form-control" name="input_message" id="input_message" cols="10" rows="5"></textarea>
                        </div>

                        <button type='submit' class='btn btn-primary'
                            name='enrollee_message_<?php echo $student_id; ?>'>
                            Save
                        </button>
                    </form>
                </div>
            </div>
        </div>
           
        <?php


    }
?>