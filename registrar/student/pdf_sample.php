<?php 

    include_once('../../includes/classes/Student.php');

    require_once __DIR__ . '../../../includes/config.php';
    require_once __DIR__ . '../../../vendor/autoload.php';


    use Dompdf\Dompdf;


    if (isset($_POST['pdf_sample'])) {

        $student = new Student($con);

        // echo "pdf";

        $all = $student->GetAllOngoingActive();


        $html = '
            <!DOCTYPE html>
            <html lang="en">

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
                <h4>Hello PDF</h4>
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Email</th>
                            <th>Username</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($all as $value) {
                $student_id = $value['student_id'];
                $email = $value['email'];
                $username = $value['username'];

                $html .= '
                    <tr>
                        <td>' . $student_id . '</td>
                        <td>' . $email . '</td>
                        <td>' . $username . '</td>
                    </tr>';
            }

            $html .= '
                    </tbody>
                </table>

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
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
        $dompdf->stream('tertiary_course_list.pdf', ['Attachment' => 0]);

        exit; // End the script execution

    }


?>