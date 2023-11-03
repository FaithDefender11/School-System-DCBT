<?php 

    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Email.php');
    include_once('../../includes/classes/Alert.php');

    require_once __DIR__ . '../../../includes/config.php';
    require_once __DIR__ . '../../../vendor/autoload.php';
    use Dompdf\Dompdf;

    ?>
        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.min.js"></script>

        <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <?php


    $sy_id = null;

   if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['print_enrolled_subject'])
        ) {

            $enrollment_id = $_POST['enrollment_id'] ?? NULL;
            $student_id = $_POST['student_id'] ?? NULL;
            $school_year_id = $_POST['school_year_id'] ?? NULL;


            $student = new Student($con, $student_id);

            $studentEmail = $student->GetEmail();
            $firstname = $student->GetFirstName();
            $lastname = $student->GetLastName();
            $middle = $student->GetMiddleName();

            $enrollment = new Enrollment($con);
            
            $enrollment_course_id = $enrollment->GetEnrollmentFormCourseId(
                $student_id, $enrollment_id);

            $enrollment_form_id = $enrollment->GetEnrollmentFormByFormId(
                $enrollment_id, $enrollment_course_id, $school_year_id);

            $section = new Section($con, $enrollment_course_id);

            $programName = $section->GetSectionName();

            // $termFormat = $enrollment->changeYearFormat($term);
            // $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");

            // echo "student_id: $student_id";
            // echo "<br>";

            // echo "school_year_id: $school_year_id";
            // echo "<br>";
            // return;

            $student_subject = new StudentSubject($con);


            $assignedSubjects = $student_subject->GetStudentAssignSubjects(
                $enrollment_id, 
                $student_id,
                $school_year_id);


            $now = date("Y-m-d H:i:s");
            

            $html = '
            <!DOCTYPE html>
                <html lang="en">
                
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        
                        <title>Tertiary Course List</title>
                        
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
                        
                        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                                margin-bottom: 20px;
                            }
                            th, td {
                                border: 1px solid #dddddd;
                                text-align: center;
                                padding: 8px;
                            }
                            th {
                                background-color: #f2f2f2;
                                border: none;

                            }
                        </style>
                    </head>

                    <body>

                        <span style="margin-bottom: 5px">Date printed: '.$now.'</span>

                        <table class="a">
                            <thead>
                                <tr>
                                    <th>Course Description</th>
                                    <th>Unit</th>
                                    <th>Section</th>
                                    <th>Type</th>
                                    <th>Time</th>
                                    <th>Room</th>
                                </tr>
                            </thead>
                            <tbody>';


                            if(count($assignedSubjects) > 0){

                                foreach ($assignedSubjects as $key => $value) {

                                    $enrollment_id = $value['enrollment_id'];
                                    $is_transferee = $value['is_transferee'];
                                    
                                    $enrolled_course_id = $value['enrolled_course_id'];

                                    $subject_id = $value['subject_program_id'];
                                    $pre_requisite = $value['pre_req_subject_title'];
                                    $subject_type = $value['subject_type'];
                                    $subject_code = $value['subject_code'];
                                    $ss_subject_code = $value['ss_subject_code'];
                                    $program_section = $value['program_section'];
                                    $subject_title = $value['subject_title'];
                                    $course_id = $value['course_id'];
                                    $unit = $value['unit'];

                                    $section = new Section($con, $course_id);
                                    $sectionName = $section->GetSectionName();

                                    // $subject_code = $program_section . "-" . $value['subject_code'];

                                    $student_subject_code = "";

                                    $subject_status = "";

                                    if($course_id != null && $enrollment_id != NULL){

                                        $student_subject_code = $section->CreateSectionSubjectCode($subject_code, 
                                            $sectionName);
                                        
                                        $subject_status = "
                                            <i style='color: green;' class='fas fa-check-circle'></i>
                                        ";
                                    }
                                    
                                    else if($course_id === null && $enrollment_id === NULL){
                                        $student_subject_code = "-";
                                        $ss_subject_code = "Credited";
                                        $subject_status = "
                                            <i style='color: orange;' class='fas fa-credit-card'></i>
                                        ";
                                    }

                                    $section_exec = new Section($con, $enrolled_course_id);
                                    $enrolled_section_name = $section_exec->GetSectionName();

                                    $allTime  = "";
                                    $allDays  = "";

                                    $schedule = new Schedule($con);

                                    // echo $section_subject_code;
                                    // echo "<br>";

                                    $hasSubjectCode = $schedule->GetSameSubjectCode(
                                        $enrolled_course_id,
                                        $ss_subject_code, $school_year_id);
                                    
                                    $scheduleOutput = "";
                                    $roomOutput = "";

                                    if($hasSubjectCode !== []){

                                        foreach ($hasSubjectCode as $key => $value) {

                                            // $schedule_subject_code = $value['subject_code'];
                                            
                                            $schedule_day = $value['schedule_day'];
                                            $schedule_time = $value['schedule_time'];

                                            $allDays .= $schedule_day;
                                            $allTime .= $schedule_time;

                                            $scheduleOutput .= "$schedule_day - $schedule_time <br>";
                                            // echo "<br>";

                                            $room = $value['room_number'];

                                            if($value['room_number'] != NULL){
                                                $roomOutput .= "$room <br>";
                                            }else{
                                                $roomOutput .= "TBA<br>";
                                            }
                                        }
                                    }else{
                                        $scheduleOutput = "TBA";
                                        $roomOutput = "TBA";
                                    }

                                    $html .= '
                                        <tr style="font-size: 13px;">
                                            <td>'.$subject_title.'</td>
                                            <td>'.$unit.'</td>
                                            <td>'.$enrolled_section_name.'</td>
                                            <td>'.$subject_type.'</td>
                                            <td>'.$scheduleOutput.'</td>
                                            <td>'.$roomOutput.'</td>
                                        </tr>
                                    ';
                                }

                            }
                            $html .= '
                            </tbody>
                        </table>
                    </body>
                </html>
            ';

                  
            if(true){

                try {

                    $dompdf = new Dompdf();

                    // Load the HTML content
                    $dompdf->loadHtml($html);

                    // (Optional) Set the paper size and orientation
                    $dompdf->setPaper('A4', 'portrait');

                    // Render the PDF
                    $dompdf->render();

                    // Get the rendered PDF content
                    $pdfContent = $dompdf->output();

                    // $pdfName = "enrolled_subjects.pdf";

                    $studentFullname = ucwords($lastname) . ", " . ucwords($firstname) . " " . ucfirst($middle);

                    // $message = "Yo";
                    $pdfName = "$enrollment_form_id $studentFullname, Section: $programName.pdf";

                    $email = new Email();

                    $isEmailSent = $email->SendEnrolledSubjectListViaPdf(
                        $studentEmail,
                        $pdfContent, // Send the PDF content directly
                        $pdfName
                    );

                    $url = "../student/record_details.php?id=$student_id&enrolled_subject=show";

                    if ($isEmailSent) {

                        // $_SESSION['enrollment_printed_success'] = true;
                        // text: 'Student has been enrolled and enrollment confirmation has been sent.',

                        # should return as true or false

                        echo "
                            <script>

                            $(document).ready(function() {
                                Swal.fire({

                                    icon: 'success',
                                    title: 'Email Sent!',
                                    text: 'Enrollment confirmation has been sent to verified email.',
                                    backdrop: false,
                                    allowEscapeKey: false,

                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '$url';
                                    }
                                });
                            });

                            </script>";

                        exit(); 

                    }else{

                        $errorUrl = "subject_insertion_summary.php?id=$enrollment_id&enrolled_subject=show";

                        echo "
                            <script>
                            $(document).ready(function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oh no!',
                                    text: 'Email is not delivered. Kindly manually send.',
                                    backdrop: false,
                                    allowEscapeKey: false,
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.href = '$url';
                                    }
                                });
                            });
                            </script>";
                        exit(); 
                    } 

                } catch (Exception $e) {

                    $errorLog = "Email Sending Error: " . $e->getMessage();
                }
            }
            


        }
    ?>