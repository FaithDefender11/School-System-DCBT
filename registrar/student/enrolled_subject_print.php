<?php 

    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Schedule.php');

    require_once __DIR__ . '../../../includes/config.php';
    require_once __DIR__ . '../../../vendor/autoload.php';


    use Dompdf\Dompdf;

    // isset($_POST['enrollment_form_id']) && 

    $enrollment_form_id = null;
    $enrollment_form_id = null;

   if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['student_id'])
        && isset($_POST['enrollment_id'])
        
        ) {
        foreach ($_POST as $key => $value) {
            if (strpos($key, 'enrolled_subject_print_') === 0) {
                $enrollment_form_id = substr($key, strlen('enrolled_subject_print_'));
            }
        }
        
        $student_id = $_POST['student_id'];
        $enrollment_id = $_POST['enrollment_id'];

        // echo $student_id;
        $student = new Student($con, $student_id);

        $firstname = ucfirst($student->GetFirstName());
        $lastname = ucfirst($student->GetLastName());

        $student_subject = new StudentSubject($con);


        $enrolledSubject = $student_subject->GetAEnrolledSubjectByEnrollmentId($student_id, $enrollment_id);

        $html = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
            </head>

                <div class="container">
                    <div class="row justify-content-center mt-5">
                        <div class="col-md-6">
                            <h3 class="text-center">STUDENT GENERAL INFORMATION</h3>
                        </div>
                    </div>
                    <div class="row mt-4">
                        
                        <div class="row col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-5">STUDENT NO:</div>
                                <div class="col-md-7">12345</div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">STUDENT NAME:</div>
                                <div class="col-md-7">John Doe</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row mb-2">
                                <div class="col-md-5">School Year:</div>
                                <div class="col-md-7">2023-2024</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-5">Gender:</div>
                                <div class="col-md-7">2023-2024</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-5">Age:</div>
                                <div class="col-md-7">2023-2024</div>
                            </div>

                            <div class="row mb-2">
                                <div class="col-md-5">Grade / Section:</div>
                                <div class="col-md-7">2023-2024</div>
                            </div>
                            
                        </div>
                    </div>
                
                </div>

                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Tertiary Course List</title>
                    <!-- Bootstrap CSS -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
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
                        }
                    </style>
                </head>

                <body>
                <table class="a">
                    <thead>
                        <tr>
                            <th>CODE</th>
                            <th>SUBJECT</th>  
                            <th>UNIT</th>
                            <th>SECTION</th>  
                            <th>DAYS</th>  
                            <th>TIME</th>  
                            <th>ROOM</th>  
                            <th>INSTRUCTOR</th>  
                        </tr>
                    </thead>
                    <tbody>';

                    $subject_titles_occurrences = [];
                    $subject_code_occurrences = [];

                    foreach ($enrolledSubject as $value) {
                        $subject_title = $value['subject_title'];
                        $subject_type = $value['subject_type'];
                        $unit = $value['unit'];
                        $sp_subjectCode = $value['sp_subjectCode'];
                        $program_section = $value['program_section'];

                        $time_from = $value['time_from'];
                        $time_to = $value['time_to'];
                        $schedule_day = $value['schedule_day'] ?? "TBA";
                        $schedule_time = $value['schedule_time'] ?? "TBA";
                        $subject_schedule_course_id = $value['subject_schedule_course_id'];
                        $subject_subject_program_id = $value['subject_subject_program_id'];

                        $instructor_name = "N/A";

                        $room = $value['room'] ?? "TBA";
                        $room = $value['room'] == 0 ? "TBA" : $value['room'];

                        $teacher_firstname = $value['firstname'];
                        $teacher_lastname = $value['lastname'];

                        if($teacher_firstname != null){
                            $instructor_name = $teacher_firstname . " " . $teacher_lastname;
                        }

                        $schedule = new Schedule($con);

                        $schedule->filterSubsequentOccurrencesSa($subject_titles_occurrences,
                            $subject_title, $subject_schedule_course_id, $subject_subject_program_id);

                        $schedule->filterSubsequentOccurrencesSa($subject_code_occurrences,
                            $sp_subjectCode, $subject_schedule_course_id, $subject_subject_program_id);

                        $html .= '
                            <tr style="font-size: 13px;">
                                <td>' . $sp_subjectCode . '</td>
                                <td>' . $subject_title . '</td>
                                <td>' . $unit . '</td>
                                <td>' . $program_section . '</td>
                                <td>' . $schedule_day . '</td>
                                <td>' . $schedule_time . '</td>
                                <td>' . $room . '</td>
                                <td>' . $instructor_name . '</td>
                            </tr>';
                    }
            
                    $html .= '
                    </tbody>
                </table>
            </body>
        </html>';

        // Create a new Dompdf instance
        $dompdf = new Dompdf();

        // Load the HTML content
        $dompdf->loadHtml($html);

        // (Optional) Set the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Output the generated PDF to the browser
        // $dompdf->stream("Enrollment Form #$enrollment_form_id - $firstname $lastname.pdf", ['Attachment' => 0]);
        // $dompdf->stream("tertiary_course_list.pdf", ['Attachment' => 0]);

        // Get the rendered PDF content
        $pdfContent = $dompdf->output();

        // Create a temporary file path
        $tempFilePath = sys_get_temp_dir() . "/enrollment_form_$enrollment_form_id.pdf";

        // Save the PDF content to the temporary file
        file_put_contents($tempFilePath, $pdfContent);

        // Send headers to trigger the download
        header("Content-type: application/pdf");
        header("Content-Disposition: attachment; filename=\"Enrollment_Form_#$enrollment_form_id - $firstname $lastname.pdf\"");
        header("Content-Length: " . filesize($tempFilePath));

        // Output the PDF content to the browser
        readfile($tempFilePath);

        // Clean up: delete the temporary file
        unlink($tempFilePath);

        // End the script execution
        exit;

        
    }
?>