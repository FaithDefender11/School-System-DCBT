<?php 

    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');
 
    require_once __DIR__ . '../../../includes/config.php';
    require_once __DIR__ . '../../../vendor/autoload.php';


    use Dompdf\Dompdf;



   if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['print_student_enrollment_forms'])
        ) {

        $selectedValuesArrJSON = $_POST['selectedValuesArr'] ?? NULL;
        $selected_student_id_input = $_POST['selected_student_id_input'] ?? NULL;

        $selectedValuesArr = json_decode($selectedValuesArrJSON, true);


        $schedule = new Schedule($con);

        // var_dump($selectedValuesArr);

        // echo "selected_student_id_input: $selected_student_id_input";
 
        if(count($selectedValuesArr) > 0){

            $get2 = $con->prepare("SELECT 
                    
                t1.*
                -- t1.enrollment_id

                FROM enrollment as t1

                INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
                AND t2.student_id=:student_id
                AND t2.school_year_id = :school_year_id

                WHERE t1.enrollment_status = 'enrolled'

                GROUP BY t1.enrollment_id

            ");

            $html = '';

            foreach ($selectedValuesArr as $key => $sy_ids) {
                # code...

                $sy_ids = intval($sy_ids);

                // echo $sy_ids;
                // echo "<br>";

                // return;

                $get2->bindValue(":student_id", $selected_student_id_input);
                $get2->bindValue(":school_year_id", $sy_ids);

                $get2->execute();

                $get2All = $get2->fetchAll(PDO::FETCH_ASSOC);

                // var_dump($get2All);

                // echo "count: " . count($get2All);
                // var_dump($get2All);
                // echo "<br>";

                foreach ($get2All as $key => $value) {
                    # code...

                    $enrollment_student_id = $value['student_id'];
                    $enrollment_enrollment_form_id = $value['enrollment_form_id'];
                    $enrollment_school_year_id = $value['school_year_id'];


                    $html .= '

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

                             <div class="title">     
                                <h4>Enrollment Form '.$enrollment_enrollment_form_id.'</h4>
                            </div>
                            
                                <table class="a">

                                    <thead style="font-size:14px">
                                        <tr class="text-center">
                                           <th>Course Description</th>
                                            <th>Code</th>
                                            <th>Unit</th>
                                            <th>Section</th>
                                            <th>Days</th>
                                            <th>Time</th>
                                            <th>Room</th>
                                            <th>Instructor</th>
                                        </tr>
                                    </thead>
                                
                                    <tbody style="font-size: 13px;">';

                                        $sql = $con->prepare("SELECT 
                                            t1.*,
                                            t3.course_id as course_course_id, t3.program_section,

                                            -- t4.student_subject_id AS graded_student_subject_id,
                                            -- t4.first,
                                            -- t4.second,
                                            -- t4.third,
                                            -- t4.fourth,
                                            -- t4.remarks,


                                            t5.subject_title,
                                            t5.unit,
                                            t5.subject_code,
                                            t5.subject_program_id,

                                            t6.schedule_day,
                                            t6.schedule_time,


                                            t7.room_number,

                                            t8.firstname,
                                            t8.lastname

                                    
                                            FROM enrollment as t1

                                            INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id
                                            AND t2.student_id=:t2_student_id
                                            AND t2.school_year_id=:t2_school_year_id

                                            
                                            LEFT JOIN course as t3 ON t3.course_id = t2.course_id

                                            -- LEFT JOIN student_subject_grade as t4 ON t4.student_subject_id = t2.student_subject_id

                                            LEFT JOIN subject_program as t5 ON t5.subject_program_id = t2.subject_program_id

                                            LEFT JOIN subject_schedule as t6 ON t6.subject_code = t2.subject_code
                                            LEFT JOIN room as t7 ON t7.room_id = t6.room_id

                                            LEFT JOIN teacher as t8 ON t8.teacher_id = t6.teacher_id


                                            -- WHERE t1.school_year_id=:school_year_id
                                            -- AND t1.student_id=:student_id

                                            WHERE t1.enrollment_status = 'enrolled'

                                        ");
                                        
                                        $sql->bindValue(":t2_school_year_id", $enrollment_school_year_id);
                                        $sql->bindValue(":t2_student_id", $enrollment_student_id);
                                                    
                                        $sql->execute();

                                        if($sql->rowCount() > 0){


                                            $subject_titles_occurrences = [];
                                            $subject_code_occurrences = [];
                                            $unit_occurrences = [];

                                            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                                                $enrollment_id = $row['enrollment_id'];
                                                
                                                $course_course_id = $row['course_course_id'];

                                                $subject_program_id = $row['subject_program_id'];

                                                

                                                $subject_title = $row['subject_title'];
                                                $subject_code = $row['subject_code'];
                                                $unit = $row['unit'];

                                                $program_section = $row['program_section'];
                                                
                                                $schedule_day = $row['schedule_day'] == NULL ? "-" : $row['schedule_day'];

                                                // var_dump($schedule_day);
                                                $schedule_time = $row['schedule_time'] == NULL ? "-" : $row['schedule_time'];
                                                $room_number = $row['room_number'] == NULL ? "-" : $row['room_number'];

                                                
                                                $firstname = $row['firstname'] == NULL ? "" : $row['firstname'];
                                                $lastname = $row['lastname'] == NULL ? "" : $row['lastname'];
                                                
                                                $teacherFullname = ucwords($firstname) . " " . ucwords($lastname);


                                                $schedule->filterSubsequentOccurrences($subject_titles_occurrences, $subject_title);
                                                $schedule->filterSubsequentOccurrences($subject_code_occurrences, $subject_code);
                                                
                                                # Can occur bug.
                                                $schedule->filterSubsequentOccurrencesSa($unit_occurrences, $unit,
                                                    $course_course_id, $subject_program_id);


                                                    

                                                    // <td>$subject_title</td>
                                                    // <td>$subject_code</td>
                                                    // <td>$unit</td>
                                                    // <td>$program_section</td>
                                                    // <td>$schedule_day</td>
                                                    // <td>$schedule_time</td>
                                                    // <td>$room_number</td>
                                                    // <td>$teacherFullname</td>

                                                $html .= '
                                                    <tr class="text-center">
                                                        <td>'.$subject_title.'</td>
                                                        <td>'.$subject_code.'</td>
                                                        <td>'.$unit.'</td>
                                                        <td>'.$program_section.'</td>
                                                        <td>'.$schedule_day.'</td>
                                                        <td>'.$schedule_time.'</td>
                                                        <td>'.$room_number.'</td>
                                                        <td>'.$teacherFullname.'</td>
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
                }





                // if(true){

                //     try {

                //         // Create a new Dompdf instance
                //         $dompdf = new Dompdf();

                //         // Load the HTML content
                //         $dompdf->loadHtml($html);

                //         // (Optional) Set the paper size and orientation
                //         $dompdf->setPaper('A4', 'portrait');

                //         // Render the PDF
                //         $dompdf->render();

                //         // Get the rendered PDF content
                //         $pdfContent = $dompdf->output();

                //         // Create a temporary file path
                //         $tempFilePath = sys_get_temp_dir() . "/file.pdf";

                //         // Save the PDF content to the temporary file
                //         file_put_contents($tempFilePath, $pdfContent);

                //         // Send headers to trigger the download
                //         header("Content-type: application/pdf");

                //         # 2324S1-HUMSS11-USCP Einstein, Albert

                //         $pdfName = "enrollmentforms.pdf";

                //         // header("Content-Disposition: attachment; filename=\"Class_list_by_teacher.pdf\"");
                //         header("Content-Disposition: attachment; filename=\"$pdfName\"");
                //         header("Content-Length: " . filesize($tempFilePath));

                //         // Output the PDF content to the browser
                //         readfile($tempFilePath);

                //         // Clean up: delete the temporary file

                //         unlink($tempFilePath);

                //     } catch (Exception $e) {

                //         $errorLog = "Email Sending Error: " . $e->getMessage();
                //     }

                // }
            }

            // var_dump($html);
            // return;

            if(true){

                $dompdf = new Dompdf();

                // Load the HTML content for all tables
                $dompdf->loadHtml($html);

                // (Optional) Set the paper size and orientation
                $dompdf->setPaper('A4', 'portrait');

                // Render the PDF
                $dompdf->render();

                // Get the rendered PDF content
                $pdfContent = $dompdf->output();

                // Send headers to trigger the download
                header("Content-type: application/pdf");
                header("Content-Disposition: attachment; filename=\"enrollment_forms.pdf\"");
                header("Content-Length: " . strlen($pdfContent));

                // Output the PDF content to the browser
                echo $pdfContent;

                // End the script execution
                exit;

            }

        }

    }
?>