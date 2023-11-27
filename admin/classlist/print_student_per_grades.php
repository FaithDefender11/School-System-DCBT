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

        $student_program_id = $_POST['student_program_id'] ?? NULL;
        $selected_student_id = $_POST['selected_student_id'] ?? NULL;

        // echo "selected_student_id: $selected_student_id";
        // echo "<br>";

        // echo "student_program_id: $student_program_id";
        // echo "<br>";


        // return;


        $schedule = new Schedule($con);

        $student_program_id = "";
        $student_program = "";

        $student = new Student($con, $selected_student_id);

        $studentName = ucwords($student->GetFirstName()) . ", " . ucwords($student->GetMiddleName()) . " " . ucwords($student->GetLastName());

        $student_unique_id = $student->GetStudentUniqueId();
        $student_address = $student->GetStudentAddress();

        // var_dump($student_address);

        $student_course_id = $student->GetStudentCurrentCourseId();
        
        $section = new Section($con, $student_course_id);

        $student_program_id = $section->GetSectionProgramId($student_course_id);

        $program = new Program($con, $student_program_id);

        $student_program = $program->GetProgramAcronym();


        
        $html = '';
        
        $now = date("Y-m-d H:i:s");
        
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
                </style>

            </head>
        ';

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
                                    <label>Address: '.$student_address.'</label>
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
                                <th>
                                    <label>Course: '.$student_program.' Curriculum</label>
                                </th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        ';
        
        // echo $tableTop;

        $get = $con->prepare("SELECT 
                                
            t1.*
    
            FROM subject_program as t1
 
            WHERE t1.program_id = :program_id

            GROUP BY t1.course_level, t1.semester 
        ");
            
        $get->bindValue(":program_id", $student_program_id);
                    
        $get->execute();

        if($get->rowCount() > 0){
            
            $getAll = $get->fetchAll(PDO::FETCH_ASSOC);


            foreach ($getAll as $key => $value) {

                $program_course_level = $value['course_level'];
                $program_semester = $value['semester'];

                $outputLevel = "";


                $program_course_level = $value['course_level'];
                $program_semester = $value['semester'];

                $outputLevel = "";

                if($program_course_level == 11){
                    $outputLevel = "Grade 11,";
                }
                if($program_course_level == 12){
                    $outputLevel = "Grade 12,";
                }
                if($program_course_level == 1){
                    $outputLevel = "First Year,";
                }
                if($program_course_level == 2){
                    $outputLevel = "Second Year,";
                }
                if($program_course_level == 3){
                    $outputLevel = "Third Year,";
                }
                if($program_course_level == 4){
                    $outputLevel = "Fourth Year,";
                }


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
                                <h5>'. $student_program.' '.$outputLevel.' '.$program_semester.' Term</h5>
                            </div>
                        </header>

                        <table class="a top-space">
                            <thead>
                                <tr class="text-center">
                                    <th>Course Code</th>
                                    <th >Course Description</th>
                                    <th>Req <br>Units</th>
                                    <th>Grade</th>
                                    <th>Earned Units</th>
                                    <th >Pre - Requisite</th>
                                    <th>Term</th>
                                </tr>
                            </thead>

                        <body>';

                        $earnedUnits = 0;
                        $requiredUnits = 0;

                        $sql = $con->prepare("SELECT 

                            t1.*,

                            t2.school_year_id,

                            t4.first,
                            t4.second,
                            t4.third,
                            t4.fourth,
                            t4.remarks
                    
                            FROM subject_program as t1

                            LEFT JOIN student_subject as t2 ON t2.program_code = t1.subject_code
                            AND t2.student_id=:student_id
                            
                            LEFT JOIN student_subject_grade as t4 ON t4.student_subject_id = t2.student_subject_id
                            

                            WHERE t1.course_level = :course_level
                            AND t1.semester = :semester
                            AND t1.program_id = :program_id
                        ");
                            
                        $sql->bindValue(":student_id", $selected_student_id);
                        $sql->bindValue(":course_level", $program_course_level);
                        $sql->bindValue(":semester", $program_semester);
                        $sql->bindValue(":program_id", $student_program_id);

                        $sql->execute();

                        if($sql->rowCount() > 0){


                            while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

                                $subject_title = $row['subject_title'];
                                $subject_code = $row['subject_code'];
                                $unit = $row['unit'];
                                $pre_req_subject_title = $row['pre_req_subject_title'];
                                
                                $remarks = $row['remarks'];

                                $earned = "";

                                $first = $row['first'];
                                $second = $row['second'];
                                $third = $row['third'];
                                $fourth = $row['fourth'];

                                $taken_school_year_id = $row['school_year_id'];

                                // var_dump($taken_school_year_id);

                                $requiredUnits += $unit;

                                $sc = new SchoolYear($con, $taken_school_year_id);
                                
                                $term = $sc->GetTerm();
                                $period = $sc->GetPeriod();

                                $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");

                                $enroll = new Enrollment($con);

                                $term = $enroll->changeYearFormat($term);

                                $average = "";

                                if($remarks != NULL && $remarks == "Passed"){
                                    $average = (($first+$second+$third+$fourth) / 4);
                                    $earned = $unit;
                                    $earnedUnits += $earned;

                                }
                                if($remarks != NULL && $remarks == "Failed"){
                                    $average = "Failed";

                                }
                                
                                // $term = "Term taken";
                                $grade = "5";

                                $format = "-";

                                if($taken_school_year_id != NULL){
                                    $format = "SY" . $term . $period_short;
                                }

                                $html .= '
                                    <tr style="font-size: 13px;">
                                        <td>'.$subject_code.'</td>
                                        <td>'.$subject_title.'</td>
                                        <td>'.$unit.'</td>
                                        <td>'.$average.'</td>
                                        <td>'.$earned.'</td>
                                        <td>'.$pre_req_subject_title.'</td>
                                        <td>'.$format.'</td>
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
                header("Content-Disposition: attachment; filename=\"$studentName - $student_program Grade Records.pdf\"");
                header("Content-Length: " . strlen($pdfContent));

                // Output the PDF content to the browser
                echo $pdfContent;

                // End the script execution
                exit;

            }

            // echo $html;

        }

    }
?>