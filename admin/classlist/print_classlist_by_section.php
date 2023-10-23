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
        && isset($_POST['print_classlist_by_section'])
        ) {



        $selected_school_year_id = $_POST['selected_school_year_id'] ?? NULL;
        $selected_program_id = $_POST['selected_program_id'] ?? NULL;
        $selected_course_id = $_POST['selected_course_id'] ?? NULL;
        $selected_course_level = $_POST['selected_course_level'] ?? NULL;

        // echo "selected_program_id: " . var_dump($selected_program_id);
        // echo "<br>";

        // echo "selected_course_id: " . var_dump($selected_course_id);
        // echo "<br>";

        // return;


        if($selected_program_id !== NULL){

            $course_query = "";
            $course_level_query = "";

            $school_year = new SchoolYear($con, $selected_school_year_id);

            $schedule = new Schedule($con);

            // $get_period = NULL;
            // $get_term = NULL;

            $get_term = $school_year->GetTerm();
            $get_period = $school_year->GetPeriod();


            if($selected_course_id != ""){
                $course_query = "AND t1.course_id = :course_id";
            }

            if($selected_course_level != ""){
                $course_level_query = "AND t3.course_level = :course_level";
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
                $course_level_query

                GROUP BY t1.course_id
            ");

            $get->bindValue(":program_id", $selected_program_id);
            $get->bindValue(":school_year_id", $selected_school_year_id);
            
            if($selected_course_id != ""){
                $get->bindValue(":course_id", $selected_course_id);
            }
            if($selected_course_level != ""){
                $get->bindValue(":course_level", $selected_course_level);
            }

            
            $get->execute();

            if($get->rowCount() > 0){

                // echo "Count: " . $get->rowCount();
                // echo "<br>";


                $sectionsByProgramList = $get->fetchAll(PDO::FETCH_ASSOC);

                $html = '';

                // echo "Count: " . $get->rowCount();
                // echo "<br>";
                // return;

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
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th>Days</th>
                                            <th>Time</th>
                                            <th>Room</th>
                                            <th>Instructor</th>
                                            <th>Capacity</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                
                                    <tbody>';   

                                    $sql = $con->prepare("SELECT 
                                        DISTINCT t1.subject_title
                                        ,t1.subject_program_id
                                        ,t1.pre_req_subject_title
                                        ,t1.subject_type
                                        ,t1.course_level
                                        ,t1.semester
                                        ,t1.unit
                                        ,t1.subject_code
                                        
                                        ,t2.program_section, t2.course_id,
                                        -- t3.subject_code AS student_subject_code,

                                        t4.subject_code AS schedule_code,

                                        t4.time_to,
                                        t4.time_from,
                                        t4.schedule_time,
                                        t4.schedule_day,
                                        t4.room_id,
                                        t4.subject_schedule_id,
                                        t4.course_id AS schedule_course_id,

                                        t5.teacher_id,
                                        t5.firstname,
                                        t5.lastname

                                        ,t6.room_number


                                        FROM subject_program as t1
                                        
                                        INNER JOIN course as t2 ON t2.program_id = t1.program_id

                                        -- LEFT JOIN student_subject as t3 ON t3.course_id = t2.course_id
                                        -- AND t3.subject_program_id = t1.subject_program_id



                                        LEFT JOIN subject_schedule as t4 ON t4.course_id = t2.course_id
                                        AND t4.subject_program_id = t1.subject_program_id

                                        LEFT JOIN teacher as t5 ON t5.teacher_id = t4.teacher_id


                                        LEFT JOIN room as t6 ON t6.room_id = t4.room_id


                                        WHERE t2.course_id=:course_id
                                        AND t1.semester=:semester
                                        AND t1.program_id=:program_id
                                        AND t1.course_level=:course_level

                                        ORDER BY t1.subject_title DESC
                                        
                                    ");
                                    
                                    $sql->bindParam(":program_id", $enrolled_course_program_id);
                                    $sql->bindParam(":course_level", $enrolled_course_level);
                                    $sql->bindParam(":semester", $get_period);
                                    $sql->bindParam(":course_id", $enrolled_course_id);
                                    
                                    $sql->execute();

                                    if($sql->rowCount() > 0){

                                        // $check  = $sql->fetchAll(PDO::FETCH_ASSOC);

                                        // var_dump(count($check));
                                        // return;

                                        $subject_titles_occurrences = [];
                                        $subject_code_occurrences = [];
                                        $room_occurrences = [];
                                        $teacher_fullname_occurrences = [];
                                        $days_occurrences = [];

                                        while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                            $course_id = $row['course_id'];
                                            $section = new Section($con, $course_id);
                                            $program_section = $row['program_section'];
                                            $subject_code = $row['subject_code'];

                                            $section_subject_code = $section->CreateSectionSubjectCode(
                                                $program_section, $subject_code
                                            );

                                            $subject_title = $row['subject_title'];

                                            $schedule_day = $row['schedule_day'] ?? "-";
                                            $room_number = $row['room_number'] ?? "-";

                                            $teacher_id = $row['teacher_id'];
                                            $teacherFullName = $row['teacher_id'] != 0 ? ucfirst($row['firstname']) . " " . ucfirst($row['lastname']) : "-";

                                            $schedule->filterSubsequentOccurrences($subject_titles_occurrences, $subject_title);
                                            $schedule->filterSubsequentOccurrences($subject_code_occurrences, $subject_code);
                                            
                                            $subject_program_id = $row['subject_program_id'];
                                            $course_level = $row['course_level'];
                                            $semester = $row['semester'];
                                            $unit = $row['unit'];
                                            $pre_requisite = $row['pre_req_subject_title'];
                                            $subject_type = $row['subject_type'];
                                            $subject_program_id = $row['subject_program_id'];

                                            $time_to = $row['time_to'];
                                            $time_from = $row['time_from'];
                                            $schedule_time = $row['schedule_time'] ?? "-";

                                            $schedule_course_id = $row['schedule_course_id'];
                                            $subject_schedule_id = $row['subject_schedule_id'];

                                            $haveSchedule = "";

                                            $statuss = "N/A";

                                            $add_schedule_url = "add_schedule_code.php?sp_id=$subject_program_id&id=$course_id";
                                            $edit_schedule_url = "edit_schedule_code.php?s_id=$subject_schedule_id";


                                            $subject_enrolled_url = "";

                                            $subject_program = new SubjectProgram($con);

                                            $student_subject_enrolled = $subject_program->GetSectionSubjectEnrolledStudents($subject_program_id,
                                                $course_id, $section_subject_code);

                                            $student_subject_enrolled = $student_subject_enrolled == 0 ? "" : $student_subject_enrolled;
                                        
                                            $html .= '
                                                <tr style="font-size: 13px;">
                                                    <td>
                                                        <a style="color: #333" href="">
                                                            '.$subject_code.'
                                                        </a>
                                                    </td>
                                                    <td>'.$subject_title.'</td>
                                                    <td>'.$schedule_day.'</td>
                                                    <td>'.$schedule_time.'</td>
                                                    <td>'.$room_number.'</td>

                                                    <td>'.$teacherFullName.'</td>
                                                    <td>'.$enrolled_course_capacity.'</td>
                                                    <td>'.$student_subject_enrolled.'</td>
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
                    header("Content-Disposition: attachment; filename=\"Class_list_by_section.pdf\"");
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