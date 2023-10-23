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

    $sy_id = null;

   if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['print_studentlist_by_section'])
        ) {



        $selected_school_year_id = $_POST['selected_school_year_id'] ?? NULL;
        $selected_program_id = $_POST['selected_program_id'] ?? NULL;
        $selected_course_id = $_POST['selected_course_id'] ?? NULL;
 

        // echo "selected_school_year_id: $selected_school_year_id";
        // echo "<br>";

        // echo "selected_program_id: $selected_program_id";
        // echo "<br>";

        // echo "selected_course_id: $selected_course_id";
        // echo "<br>";

       
       
        if($selected_program_id !== NULL){

            $course_query = "";

                            
            if($selected_course_id != ""){
                $course_query = "AND t1.course_id = :course_id";
            }

            $get = $con->prepare("SELECT 

                t1.student_subject_id,
                t1.course_id,
                t3.program_section
                
                FROM student_subject as t1

                -- INNER JOIN student_subject as t2 ON t2.student_subject_id = t1.student_subject_id
                INNER JOIN course as t3 ON t3.course_id = t1.course_id

                AND t3.program_id=:program_id
                AND t1.is_final = 1
                AND t1.school_year_id=:school_year_id
                $course_query

                GROUP BY t1.course_id
            ");

            $get->bindValue(":program_id", $selected_program_id);
            $get->bindValue(":school_year_id", $selected_school_year_id);
            
            if($selected_course_id != ""){
                $get->bindValue(":course_id", $selected_course_id);
            }
            
            $get->execute();

            
            // echo "count: " . count($sectionsByProgramList);

            // var_dump($sectionsByProgramList);
            // echo "<br>";
            // return;

            if($get->rowCount() > 0){

                $sectionsByProgramList = $get->fetchAll(PDO::FETCH_ASSOC);

                $html = '';

                foreach ($sectionsByProgramList as $key => $value) {

                    # code...

                    $enrolled_course_id = $value['course_id'];
                    $section = new Section($con, $enrolled_course_id);

                    $sectionName = $section->GetSectionName();
                    $enrolled_course_level = $section->GetSectionGradeLevel();
                    $enrolled_course_capacity = $section->GetSectionCapacity();
                    $enrolled_course_program_id = $section->GetSectionProgramId($enrolled_course_id);

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

                                <em style="margin-bottom: 28px;">Class section &nbsp; &nbsp; &nbsp; </em> <span style="font-weight: bold;">'.$sectionName.'</span><br><br>
                                
                                <table class="a">
                                    <thead>
                                        <tr class="text-center">
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
                                        
                                            
                                            FROM student as t3 
                                        
                                            LEFT JOIN course as t4  ON t4.course_id = t3.course_id
                                            LEFT JOIN program as t5  ON t5.program_id = t4.program_id
                                            
                                            WHERE t3.course_id = :course_id
                                            -- AND t2.is_final = :is_final
                                            
                                        ");

                                        # SPECIFIC Course ID selection.
                                        $query->bindValue(":course_id", $enrolled_course_id);

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
                }

                if(true){

                    // // Create a new Dompdf instance
                    // $dompdf = new Dompdf();

                    // // Load the HTML content
                    // $dompdf->loadHtml($html);

                    // // (Optional) Set the paper size and orientation
                    // $dompdf->setPaper('A4', 'portrait');

                    // // Render the PDF
                    // $dompdf->render();

                    // // Output the generated PDF to the browser
                    // // $dompdf->stream("Enrollment Form #$enrollment_form_id - $firstname $lastname.pdf", ['Attachment' => 0]);
                    // // $dompdf->stream("tertiary_course_list.pdf", ['Attachment' => 0]);

                    // // Get the rendered PDF content
                    // $pdfContent = $dompdf->output();

                    // // Create a temporary file path
                    // $tempFilePath = sys_get_temp_dir() . "/file.pdf";

                    // // Save the PDF content to the temporary file
                    // file_put_contents($tempFilePath, $pdfContent);

                    // // Send headers to trigger the download
                    // header("Content-type: application/pdf");

                    // # 2324S1-HUMSS11-USCP Einstein, Albert

                    // #REF
                    // // header("Content-Disposition: attachment; filename=\"Class_list_by_teacher.pdf\"");
                    // header("Content-Disposition: attachment; filename=\"Class_list_by_section.pdf\"");
                    // header("Content-Length: " . filesize($tempFilePath));

                    // // Output the PDF content to the browser
                    // readfile($tempFilePath);

                    // // Clean up: delete the temporary file
                    // unlink($tempFilePath);

                    // // End the script execution
                    // exit;


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
                    header("Content-Disposition: attachment; filename=\"Student_list_by_section.pdf\"");
                    header("Content-Length: " . strlen($pdfContent));

                    // Output the PDF content to the browser
                    echo $pdfContent;

                    // End the script execution
                    exit;

                }

            }


           
        }

    }
?>