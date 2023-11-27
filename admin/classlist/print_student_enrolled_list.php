<?php 

    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');

    require_once __DIR__ . '../../../includes/config.php';
    require_once __DIR__ . '../../../vendor/autoload.php';




    use Dompdf\Dompdf;

   if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['print_classlist_by_section'])
        ) {

        $student_id = $_POST['student_id'] ?? NULL;
        $selected_student_id = $_POST['selected_student_id'] ?? NULL;

        // echo "student_id: $student_id";
        // echo "<br>";

        // return;

        $student = new Student($con, $student_id);
        $student_unique_id = $student->GetStudentUniqueId();
        $student_address = $student->GetStudentAddress();

        $studentName = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());


        $student_course_id = $student->GetStudentCurrentCourseId();
        $section = new Section($con, $student_course_id);

        $student_program_id = $section->GetSectionProgramId($student_course_id);
        $student_course_level = $section->GetSectionGradeLevel();

        $program = new Program($con, $student_program_id);

        $student_program = $program->GetProgramAcronym();


        $html = '';
        
        $html = '
            <head>
            
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
                
                <style>
                    .text-center{
                        text-align: center;
                    }
                    label{
                        font-size: 14px; !important
                    }
                    .mb-space{
                        margin-bottom : 30px; !important
                    }
                    .text-right{
                        text-align: right;
                    }
                </style>

            </head>
        ';

        $now = date("Y-m-d H:i:s");

        $html .= '
            <div class="mb-space">

                <h4 class="text-center">Daehan College of Business & Technology - DCBT</h4>
                <h6 style="margin-top: -15px;" class="text-center">Sitio Siwang Westbank Damayan Road 20 , Taytay, Philippines</h6>
                <h6 style="margin-top: -20px;" class="text-center">Contact Address. gerlie.arquiza@yahoo.com</h6>
                <h4 style="font-weight: bold;" class="text-muted text-center">Student Checklist</h4>
                <h6 style="font-weight: bold;" class="text-muted text-right">Date Printed: '.$now.'</h6>
            
                <div style="margin-bottom: -40px;" class="container">
                    <table style="max-width:100%" class="table">
                        <thead>
                            <tr>
                                <th>
                                    <label>Student ID :</label>
                                </th>
                                <th>
                                    <label>'.$student_unique_id.'</label></th> 
                                <th></th>
                                <th>
                                    <label>Program: '.$student_program.'</label>
                                </th>
                            </tr>
                        </thead>

                        <thead> 
                            <tr>
                                <th>
                                    <label>Student Name :</label>
                                </th>
                                <th>
                                    <label>'.$studentName.'</label></th> 
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        ';

        $get2 = $con->prepare("SELECT 
                                        
            t1.*

            FROM enrollment as t1

            INNER JOIN student_subject as t2 ON t2.enrollment_id = t1.enrollment_id

            AND t2.student_id=:student_id

            WHERE t1.enrollment_status = 'enrolled'

            GROUP BY t1.enrollment_id

        ");

        $get2->bindValue(":student_id", $student_id);

        $get2->execute();

        if($get2->rowCount() > 0){
            
            $get2All = $get2->fetchAll(PDO::FETCH_ASSOC);

            // var_dump($get2All);

            foreach ($get2All as $key => $value) {

                $enrollment_student_id = $value['student_id'];
                $enrollment_enrollment_form_id = $value['enrollment_form_id'];
                $enrollment_school_year_id = $value['school_year_id'];
                
                $enrollment_course_id = $value['course_id'];


                $sectionExec = new Section($con, $enrollment_course_id);

                $enrolled_section = $sectionExec->GetSectionName();


                $html .= '
                    <!DOCTYPE html>
                    <html lang="en">
                    
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            
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
                                    font-size: 14px; !important
                                }
                                .top-space{
                                    margin-top: 15px;
                                }
                            </style>

                        </head>

                        <header>
                            <div class="title">     
                                <h5>Enrollment Form - '. $enrollment_enrollment_form_id .' </h5>
                                <h5>Section - '. $enrolled_section .' </h5>
                            </div>
                        </header>

                        <table class="a top-space">
                            <thead>
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

                            <body>';

                            $schedule = new Schedule($con);
                        
                            $sql = $con->prepare("SELECT 
                            
                                t1.*,
                                t3.course_id as course_course_id, t3.program_section,

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

                                LEFT JOIN subject_program as t5 ON t5.subject_program_id = t2.subject_program_id

                                LEFT JOIN subject_schedule as t6 ON t6.subject_code = t2.subject_code
                                LEFT JOIN room as t7 ON t7.room_id = t6.room_id

                                LEFT JOIN teacher as t8 ON t8.teacher_id = t6.teacher_id
 
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
                                    $html .= '
                                        <tr style="font-size: 13px;">
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
                header("Content-Disposition: attachment; filename=\"$studentName - $student_program Enrollment List.pdf\"");
                header("Content-Length: " . strlen($pdfContent));

                // Output the PDF content to the browser
                echo $pdfContent;

                // End the script execution
                exit;

            }
        }



    }
?>