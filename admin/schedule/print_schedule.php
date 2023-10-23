<?php 

    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Schedule.php');

    require_once __DIR__ . '../../../includes/config.php';
    require_once __DIR__ . '../../../vendor/autoload.php';


    use Dompdf\Dompdf;

    $sy_id = null;

   if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['print_schedule'])
        // && isset($_POST['selected_sy_id'])
        // && isset($_POST['selected_program_id'])
        ) {

        // foreach ($_POST as $key => $value) {
        //     if (strpos($key, 'print_schedule_') === 0) {
        //         $sy_id = substr($key, strlen('print_schedule_'));
        //     }
        // }

        $selected_sy_id = $_POST['selected_sy_id'] ?? NULL;
        $selected_program_id = $_POST['selected_program_id'] ?? NULL;
        $selected_course_id = $_POST['selected_course_id'] ?? NULL;
 
 
        // var_dump($selected_sy_id);
        // var_dump($selected_program_id);

    
        $school_year_condition = "";

        if($selected_sy_id !== ""){
            $school_year_condition = "AND t1.school_year_id=:school_year_id";
        }

        $program_condition = "";
        $program_join_condition = "";

        if($selected_program_id !== ""){
            $program_join_condition = "INNER JOIN program AS t4 ON t4.program_id = t2.program_id";
            $program_condition = "AND t4.program_id=:program_id";
        }

        $course_condition = "";

        if($course_id !== ""){
            $course_condition = "AND t2.course_id=:course_id";
        }


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
                        }
                    </style>
                </head>


                <body>
                    <table class="a">
                        <thead>
                            <tr>
                                <th>Program - Section</th>
                                <th>Subject Code</th>
                                <th>Term</th>
                                <th>Period</th>
                                <th>Day</th>
                                <th>Time</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>';

                        $empQuery = "SELECT 
                                t1.*, t2.program_section, t2.school_year_term,
                                t3.term, t3.period

                                FROM subject_schedule AS t1

                                INNER JOIN course AS t2 ON t2.course_id = t1.course_id
                                INNER JOIN school_year AS t3 ON t3.school_year_id = t1.school_year_id
                                $program_join_condition

                                WHERE t1.school_year_id=:school_year_id
                                AND t1.school_year_id=:school_year_id
                                AND t4.program_id=:program_id
                                AND t2.course_id=:course_id
                                -- $school_year_condition
                                -- $program_condition
                                -- $course_condition

                                
                            ";

                        // var_dump($empQuery);
    
                        $stmt = $con->prepare($empQuery);
 
                        $stmt->bindValue(":school_year_id", $selected_sy_id);
                        $stmt->bindValue(":program_id", $selected_program_id);
                        $stmt->bindValue(":course_id", $selected_course_id);
                        
                        $stmt->execute();

                        if($stmt->rowCount() > 0){

                            while($row = $stmt->fetch(PDO::FETCH_ASSOC)){

                                $subject_code = $row['subject_code'];
                                $schedule_day = $row['schedule_day'];
                                $schedule_time = $row['schedule_time'];
                                $program_section = $row['program_section'];
                                $school_year_term = $row['school_year_term'];
                                $term = $row['term'];
                                $period = $row['period'];

                                $html .= '
                                    <tr style="font-size: 13px;">
                                        <td>'.$program_section.'</td>
                                        <td>'.$subject_code.'</td>
                                        <td>'.$term.'</td>
                                        <td>'.$period.'</td>
                                        <td>'.$schedule_day.'</td>
                                        <td>'.$schedule_time.'</td>
                                    
                                    </tr>';
                            }
                        }
                        else{
                            echo "nothing";
                        }

                        $html .= '
                        </tbody>
                    </table>
                </body>
        </html>';

        if(true){

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
            $tempFilePath = sys_get_temp_dir() . "/file.pdf";

            // Save the PDF content to the temporary file
            file_put_contents($tempFilePath, $pdfContent);

            // Send headers to trigger the download
            header("Content-type: application/pdf");
            header("Content-Disposition: attachment; filename=\"Schedule#.pdf\"");
            header("Content-Length: " . filesize($tempFilePath));

            // Output the PDF content to the browser
            readfile($tempFilePath);

            // Clean up: delete the temporary file
            unlink($tempFilePath);

            // End the script execution
            exit;
        }

    }
?>