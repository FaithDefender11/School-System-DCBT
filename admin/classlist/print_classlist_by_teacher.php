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

    ?>
        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- SweetAlert -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.24/sweetalert2.all.min.js"></script>

        <!-- Bootstrap 4 JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <?php

    use Dompdf\Dompdf;

    $sy_id = null;

   if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['print_classlist_by_teacher'])
        ) {



        $selected_school_year_id = $_POST['selected_school_year_id'] ?? NULL;
        $selected_subject_schedule_id = $_POST['selected_subject_schedule_id'] ?? NULL;
        $selected_teacher_id = $_POST['selected_teacher_id'] ?? NULL;
 

        $school_year = new SchoolYear($con, $selected_school_year_id);

        $enrollment = new Enrollment($con);
            
        
        $term = $school_year->GetTerm();
        $period = $school_year->GetPeriod();

        $termFormat = $enrollment->changeYearFormat($term);
        $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");

        // echo "selected_school_year_id: $selected_school_year_id";
        // echo "<br>";

        // echo "selected_subject_schedule_id: $selected_subject_schedule_id";
        // echo "<br>";

        // echo "selected_teacher_id: $selected_teacher_id";
        // echo "<br>";


        $getAll = [];

        if($selected_subject_schedule_id !== NULL){

            $schedule = new Schedule($con, $selected_subject_schedule_id);
            
            $time_from = $schedule->GetTimeFrom();
            $time_to = $schedule->GetTimeTo();
            $schedule_time = $schedule->GetScheduleTime();

            $schedule_day = $schedule->GetScheduleDay();

            $subject_program_id = $schedule->GetSubjectProgramId();
            $schedule_course_id = $schedule->GetScheduleCourseId();
            $schedule_subject_code = $schedule->GetSubjectCode();

            $schedule_room_id = $schedule->GetRoomId();
            $room = "";

            // var_dump($schedule_room_id);

            if($schedule_room_id == NULL){
                $room = "TBA";
            }else if($schedule_room_id != NULL){
                $room = new Room($con, $schedule_room_id);
                $room = $room->GetRoomNumber();
            }

            $firstname = "";
            $lastname = "";
            $rawCode = "";

            $section = new Section($con, $schedule_course_id);

            $programName = $section->GetSectionName();

            $subjectProgram = new SubjectProgram($con, $subject_program_id);

            $rawCode = $subjectProgram->GetSubjectProgramRawCode();

            $teacher = new Teacher($con, $selected_teacher_id);

            $fullname = ucwords(trim($teacher->GetTeacherFirstName())) . " " . ucwords(trim($teacher->GetTeacherLastName()));
             
        }


        $tableTop = '
            <div class="container">
                <table style="max-width:100%" cellpadding="4" cellspacing="7" class="table">
                    <thead>
                        <tr>
                            <th><label>Instructor :</label></th><th><label>'.$fullname.'</label></th> 
                            <th></th>
                            <th>Day(s)/Time:</th><th>';

                            $query  = $con->prepare("SELECT * 

                                FROM subject_schedule as t1
                                
                                WHERE t1.subject_code=:subject_code
                                AND t1.teacher_id=:teacher_id
                            ");

                            $query->bindValue(":subject_code", $schedule_subject_code);
                            $query->bindValue(":teacher_id", $selected_teacher_id);
                            $query->execute();

                            if($query->rowCount() > 0){

                                $getAll = $query->fetchAll(PDO::FETCH_ASSOC);

                                // var_dump($getAll);

                                foreach ($getAll as $key => $value) {

                                    $schedule_time = $value['schedule_time'];
                                    $schedule_day = $value['schedule_day'];

                                    $schedule_day = $schedule->convertToDays($schedule_day);

                                    $tableTop .= '
                                        '.$schedule_time.' / '.$schedule_day.' <br>
                                    ';
                                }
                            }


                            $tableTop .= '
                            </th>
                        </tr>
                    </thead>
                    ';

                    $tableTop .='
                    <thead> 
                        <tr>
                            <th><label>Subject :</label></th><th><label>'.$rawCode.'</label></th> 
                            
                            <th></th>

                            <th>
                                <label>Program-Section : '.$programName.'</label>
                            </th>

                            <th>
                                <label>Room '.$room.'</label>
                            </th>
                        </tr>
                    </thead>
                </table>
            </div>
        ';

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
 
                    '.$tableTop.'

                    <table class="a">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Contact No</th>
                                <th>Civil Status</th>
                                <th>Program</th>
                                <th>Level</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>';

                            $query = $con->prepare("SELECT 
                            
                                t3.firstname,
                                t3.lastname,
                                t3.student_unique_id,
                                t3.admission_status,
                                t3.sex,
                                t3.contact_number,
                                t3.course_level,
                                
                                t3.civil_status,

                                t4.program_section,

                                t5.acronym
                            
                                FROM subject_schedule as t1

                                INNER JOIN student_subject as t2 ON t2.subject_code = t1.subject_code

                                INNER JOIN student as t3  ON t3.student_id = t2.student_id
                                
                                LEFT JOIN course as t4  ON t4.course_id = t3.course_id
                                LEFT JOIN program as t5  ON t5.program_id = t4.program_id
                                
                                WHERE t1.subject_schedule_id = :subject_schedule_id
                                AND t2.is_final = :is_final
                                
                            ");

                            $query->bindValue(":subject_schedule_id", $selected_subject_schedule_id);
                            $query->bindValue(":is_final", 1);

                            $query->execute();

                            if($query->rowCount() > 0){

                                while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                    $firstname = trim($row['firstname']);
                                    $lastname = trim($row['lastname']);

                                    $student_unique_id = trim($row['student_unique_id']);
                                    $contact_number = trim($row['contact_number']);
                                    $sex = trim($row['sex']);
                                    $program_section = trim($row['program_section']);
                                    $course_level = trim($row['course_level']);
                                    
                                    $civil_status = trim($row['civil_status']);
                                    $admission_status = trim($row['admission_status']);
                                    
                                    $acronym = trim($row['acronym']);


                                    $fullname = ucwords($firstname) . " " . ucwords($lastname);
    
                                    $html .= '
                                        <tr style="font-size: 13px;">
                                            <td>'.$student_unique_id.'</td>
                                            <td>'.$fullname.'</td>
                                            <td>'.$sex.'</td>
                                            <td>'.$contact_number.'</td>
                                            <td>'.$civil_status.'</td>
                                            <td>'.$acronym.'</td>
                                            <td>'.$course_level.'</td>
                                            <td>'.$admission_status.'</td>
                                        
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

                // Create a new Dompdf instance
                $dompdf = new Dompdf();

                // Load the HTML content
                $dompdf->loadHtml($html);

                // (Optional) Set the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');

                // Render the PDF
                $dompdf->render();

                // Get the rendered PDF content
                $pdfContent = $dompdf->output();

                // Create a temporary file path
                $tempFilePath = sys_get_temp_dir() . "/file.pdf";

                // Save the PDF content to the temporary file
                file_put_contents($tempFilePath, $pdfContent);

                // Send headers to trigger the download
                header("Content-type: application/pdf");

                # 2324S1-HUMSS11-USCP Einstein, Albert

                $pdfName = "$termFormat$period_short-$programName-$rawCode $lastname, $firstname.pdf";

                // header("Content-Disposition: attachment; filename=\"Class_list_by_teacher.pdf\"");
                header("Content-Disposition: attachment; filename=\"$pdfName\"");
                header("Content-Length: " . filesize($tempFilePath));

                // Output the PDF content to the browser
                readfile($tempFilePath);

                // Clean up: delete the temporary file

                unlink($tempFilePath);

            } catch (Exception $e) {

                $errorLog = "Email Sending Error: " . $e->getMessage();
            }


            // try {
            //     // Create a new Dompdf instance
            //     $dompdf = new Dompdf();

            //     // Load the HTML content
            //     $dompdf->loadHtml($html);

            //     // (Optional) Set the paper size and orientation
            //     $dompdf->setPaper('A4', 'portrait');

            //     // Render the PDF
            //     $dompdf->render();

            //     // Get the rendered PDF content
            //     $pdfContent = $dompdf->output();

            //     # PDF NAME.
                
            //     $pdfName = "$termFormat$period_short-$programName-$rawCode $lastname, $firstname.pdf";

            //     $email = new Email();

            //     $isEmailSent = $email->SendEnrolledSubjectListViaPdf(
            //         "justinesirios15@gmail.com",
            //         $pdfContent, // Send the PDF content directly
            //         $pdfName
            //     );

            //     if ($isEmailSent) {
            //         echo "
            //             <script>
            //             $(document).ready(function() {
            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'Email Sent!',
            //                     text: 'Pdf was sent to the provided email',
            //                     backdrop: false,
            //                 }).then((result) => {
            //                     if (result.isConfirmed) {
            //                         window.location.href = 'index.php';
            //                     }
            //                 });
            //             });
            //             </script>";
            //         exit(); 


            //     } else {
            //         echo "
            //             <script>
            //             $(document).ready(function() {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'Email could not Sent',
            //                     text: 'Sending email failed, Please contact administrator.',
            //                     backdrop: false,
            //                 }).then((result) => {
            //                     if (result.isConfirmed) {
            //                         window.location.href = 'index.php';
            //                     }
            //                 });
            //             });
            //             </script>";
            //         exit();
            //     }

            //     // You don't need to unlink a temporary file since you're not saving it in this version.

            // } catch (Exception $e) {
            //     $errorLog = "Email Sending Error: " . $e->getMessage();
            // }

            // End the script execution
            exit;
        }

    }

?>