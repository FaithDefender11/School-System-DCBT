<?php 

    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Department.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/StudentSubjectGrade.php');
    include_once('../../includes/classes/StudentRequirement.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/EnrollmentPayment.php');
    include_once('../../includes/classes/User.php');
    include_once('../../includes/classes/EnrollmentAudit.php');

    require_once("../../includes/classes/PendingParent.php");
    require_once("../../includes/classes/Pending.php");
    require_once("../../includes/classes/Program.php");


    require_once __DIR__ . '../../../includes/config.php';
    require_once __DIR__ . '../../../vendor/autoload.php';


    use Dompdf\Dompdf;

   if ($_SERVER['REQUEST_METHOD'] === 'POST'
        && isset($_POST['print_classlist_by_section'])
        ) {

        $enrollment_form_id_url = $_POST['enrollment_id'] ?? NULL;
        $student_id = $_POST['student_id'] ?? NULL;

        // echo "enrollment_id: $enrollment_form_id_url";
        // echo "<br>";

        $student = new Student($con, $student_id);

        $studentName = ucwords($student->GetFirstName()) . ", " . ucwords($student->GetMiddleName()) . " " . ucwords($student->GetLastName());


        $school_year = new SchoolYear($con, null);

        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];

        $enrollmentPayment = new EnrollmentPayment($con);
        $enrollment = new Enrollment($con);

        $paymentEnrollmentList = $enrollmentPayment->GetPaymentHistoryExceptDownPayment(
            $enrollment_form_id_url);
        // return;

        $now = date("Y-m-d H:i:s");

        $enrollment_form_student_id = $enrollment->GetStudentIdByEnrollmentId(
            $enrollment_form_id_url, $current_school_year_id);

        $enrollment_form_is_tertiary = $enrollment->GetEnrollmentFormIsTertiary(
            $enrollment_form_student_id, $enrollment_form_id_url);

        $enrollmentFormPaymentMethod = $enrollment->GetEnrollmentPaymentMethod(
            $enrollment_form_student_id, $enrollment_form_id_url);

        $enrollmentFormPaymentStatus = $enrollment->GetEnrollmentPaymentStatus(
            $enrollment_form_student_id, $enrollment_form_id_url);

        $enrollmentFormPaymentMethod = $enrollment->GetEnrollmentPaymentMethod(
            $enrollment_form_student_id, $enrollment_form_id_url);

        $enrollmentTotalPayment = $enrollment->GetEnrollmentTotalPayment(
            $enrollment_form_student_id, $enrollment_form_id_url);

        $enrollmentInstallmentCount = $enrollment->GetEnrollmentInstallmentCount(
            $enrollment_form_student_id, $enrollment_form_id_url);

        $doesEnrollmentStudentEnrolled = $enrollment->CheckStudentWasEnrolled(
            $enrollment_form_id_url,
            $current_school_year_id);


        $student_enrollment_course_id = $enrollment->GetEnrollmentFormCourseId($student_id,
            $enrollment_form_id_url);

        // var_dump($student_enrollment_course_id);
        // return;
        
        $section = new Section($con, $student_enrollment_course_id);

        $student_program_id = $section->GetSectionProgramId($student_enrollment_course_id);

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
                </style>

            </head>
        ';

        $html .= '
            <div class="mb-space">

                <h4 class="text-center">Daehan College of Business & Technology - DCBT</h4>
                <h6 style="margin-top: -15px;" class="text-center">Sitio Siwang Westbank Damayan Road 20 , Taytay, Philippines</h6>
                <h6 style="margin-top: -20px;" class="text-center">Contact Address. gerlie.arquiza@yahoo.com</h6>
                <h6 style="font-weight: bold;" class="text-muted text-right">Date Printed: '.$now.'</h6>
                <h4 style="font-weight: bold;" class="text-muted text-center">Payment Record</h4>
              
            </div>
        ';


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

                </head>';

                if ($enrollmentFormPaymentMethod === "Cash" && $enrollmentFormPaymentStatus == NULL) {

                    $html .= '<span style="font-weight: bold;width: 217px; height: 27px" class="text-center bg-success">Payment Method: Cash</span>';
                } elseif ($enrollmentFormPaymentMethod === "Cash" && $enrollmentFormPaymentStatus === "Complete") {

                    $html .= '<span style="font-weight: bold;width: 217px; height: 27px" class="text-center bg-success">Payment Completed via Cash</span>';
                } elseif ($enrollmentFormPaymentMethod === "Partial" && $enrollmentFormPaymentStatus == NULL) {

                    $html .= '<span style="font-weight: bold;width: 228px; height: 27px" class="text-center bg-info">Payment Method: Partial</span>';
                } elseif ($enrollmentFormPaymentMethod === "Partial" && $enrollmentFormPaymentStatus === "Complete") {

                    $html .= '<span style="font-weight: bold;width: 228px; height: 27px" class="text-center bg-success">Payment Completed via Partial</span>';
                } elseif ($enrollmentFormPaymentMethod === "Partial" && $enrollmentFormPaymentStatus === "Incomplete") {

                    $html .= '<span style="font-weight: bold;width: 233px; height: 27px" class="text-center bg-info">Payment Incomplete via Partial</span>';
                }
                $html .= '<br>';

                $html .= '
                <span style="font-weight: bold;" class="mb-2">Total to pay amount: P '.$enrollmentTotalPayment.'</span>
                <table class="a top-space">
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
                    <body>';

                        $student_subject = new StudentSubject($con);
                            $assignSubjects = $student_subject->GetStudentAssignSubjects(
                                $enrollment_form_id_url,
                                $student_id);

                            // var_dump($assignSubjects);
                            
                            foreach ($assignSubjects as $key => $value) {

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

                                $section_exec = new Section($con, $enrolled_course_id);
                                $enrolled_section_name = $section_exec->GetSectionName();

                                $allTime  = "";
                                $allDays  = "";

                                $schedule = new Schedule($con);

                                // echo $section_subject_code;
                                // echo "<br>";

                                $hasSubjectCode = $schedule->GetSameSubjectCode(
                                    $enrolled_course_id,
                                    $ss_subject_code, $current_school_year_id);

                                
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
                        $html .= '
                    </tbody>
                </table>';

                if($enrollmentFormPaymentMethod === "Partial" && count($paymentEnrollmentList)){
                      
                    $downPayment = $enrollmentPayment->GetDownPayment($enrollment_form_id_url);

                    $excessPaymentValue = $enrollmentTotalPayment - $downPayment;

                    $editInstallmentUrl = "";

                    $paymentCanEdit = "";

                    $hasPaidTheSchedulePayment = $enrollmentPayment->HasPaidThePaymentSchedule(
                        $enrollment_id);

                    // var_dump($hasPaidTheSchedulePayment);
                    
                    if($hasPaidTheSchedulePayment == false && $doesEnrollmentStudentEnrolled == false){
                        $paymentCanEdit = "
                            <button type='button' onclick=\"window.location.href = 'edit_installment_selection.php?id=$enrollment_form_id_url'\"
                            class='btn-primary btn btn-sm'><i class='fas fa-pen'></i></button>
                        ";

                    } 


                    $html .= '
                    
                        <h3 style="font-weight: bold;" class="text-center text-primary">Payment Schedule</h3>
                        <span style="font-weight: bold;" class="text-muted">Down Payment: <span class="text-dark">P'.$downPayment.'</span></span>
                    
                        <table class="a">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Amount to pay</th>
                                    <th>To pay Date</th>
                                    <th>Processed by</th>
                                    <th>Processed Date</th>
                                </tr>
                            </thead>

                            <tbody>';
                                $i = 0;
                                foreach ($paymentEnrollmentList as $key => $value) {

                                    $i++;

                                    $amountPaid = $value['amount_paid'];
                                    $enrollment_id_db = $value['enrollment_id'];
                                    $enrollment_payment_id = $value['enrollment_payment_id'];


                                    $total_amount += $value['amount_paid'];

                                    $date_creation = $value['date_creation'];
                                    
                                    $cashierUserIdDB = $value['cashier_id'];

                                    $date_creation = date("M d, Y h:i a", strtotime($date_creation));

                                    $date_to_pay_db = $value['date_to_pay'];
                                    $date_to_pay = date("M d, Y", strtotime($date_to_pay_db));

                                    $process_date = $value['process_date'];

                                    $process_date_db = "-";

                                    if($process_date != NULL){
                                        $process_date_db = date("M d, Y", strtotime($process_date));

                                    }

                                    $cashierName = "-";
                                    if($cashierUserIdDB != NULL){
                                        $user = new User($con, $cashierUserIdDB);
                                        $cashierName = $user->getName();
                                    }

                                    $markAsPaidBtn = "";
                                    $payAmountReflect = "";

                                    // var_dump($doesStudentEnrolled);

                                    
                                    $to_pay_amount = number_format($excessPaymentValue / $enrollmentInstallmentCount, 2);

                                    if($amountPaid == NULL && $doesEnrollmentStudentEnrolled == true){

                                        $markAsPaidBtn = "";
                                        
                                        $payAmountReflect = "
                                                <span style='font-weight:bold;'>P$to_pay_amount</span>
                                        ";

                                    }
                                    if($amountPaid == NULL && $doesEnrollmentStudentEnrolled == false){

                                        $payAmountReflect = "
                                            <span style='font-weight:bold;'>₱$to_pay_amount</span>
                                        ";

                                    }
                                    if($amountPaid != NULL){
                                        $payAmountReflect = "
                                            <span style='color: green;font-weight:bold;'>P$to_pay_amount</span>
                                        ";
                                    }

                                    $html .= '
                                        <tr style="font-size: 13px;">
                                            <td>'.$i.'</td>
                                            <td>'.$payAmountReflect.'</td>
                                            <td>'.$date_to_pay.'</td>
                                            <td>'.$cashierName.'</td>
                                            <td>'.$process_date_db.'</td>
                                        </tr>
                                    ';
                                }
                                $html .= '
                            </tbody>
                        </table>';
                }



                $html .= '
                </body>
            </html>';

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
                header("Content-Disposition: attachment; filename=\"$studentName - $student_program  Payment Record.pdf\"");
                header("Content-Length: " . strlen($pdfContent));

                // Output the PDF content to the browser
                echo $pdfContent;

                // End the script execution
                exit;

            }          
                    
 

    }
?>