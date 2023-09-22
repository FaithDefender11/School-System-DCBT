<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Task.php');

    // $task = new Task($con);
    // $task->MarkStudentAsApplicable();

    // $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    // $current_school_year_term = $school_year_obj['term'];
    // $current_school_year_period = $school_year_obj['period'];
    // $current_school_year_id = $school_year_obj['school_year_id'];

    // $studentQuery = $con->prepare("SELECT *
        
    // echo web_root;
    // echo "Qweqwe";

    // echo currentURL;

    
    // $currentURL = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    // echo web_root;

    $url = "https://sub.dcbt.online/registrar/dashboard/index.php";

    // Parse the URL
    $parsedUrl = parse_url($url);

    // Reconstruct the base URL
    $base_url = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/';

    // echo $base_url;

    // echo "base_url: $base_url";
    // echo "<br>";
    // echo "domainName: $domainName";
    // echo "<br>";


    //     FROM student as t1

    //     INNER JOIN enrollment as t2 ON t2.student_id = t1.student_id
    //     AND school_year_id=:school_year_id
    //     AND enrollment_status=:enrollment_status


    //     WHERE t1.active = 1
    //     AND t1.nsy_applicable = 0
        
    // ");
    // $studentQuery->bindParam(":school_year_id", $current_school_year_id);
    // $studentQuery->bindValue(":enrollment_status", "enrolled");
    // $studentQuery->execute();

    // if($studentQuery->rowCount() > 0){

    //     $enrollment = new Enrollment($con);
    //     $student_subject = new StudentSubject($con);

    //     while($row = $studentQuery->fetch(PDO::FETCH_ASSOC)){

    //         $student_name = $row['firstname'];
    //         $student_id = $row['student_id'];

    //         // Get student enrollment form id within current semester & S.Y
    //         $student_enrollment_id = $enrollment->GetEnrollmentIdNonDependent($student_id,
    //             $current_school_year_id);

    //         // echo $student_enrollment_id;
    //         // echo "<br>";

    //         $applicableStudentId = $student_subject->CheckCurrentSemesterSubjectAllPassed($student_enrollment_id,
    //             $student_id, $current_school_year_id);

    //         if($applicableStudentId != 0){

    //             $student = new Student($con, $applicableStudentId);

    //             $applicable = $student->DoesApplicableToApplyNextYear();

    //             if($applicable == 0){

    //                 if($student->UpdateStudentApplicableApplyNextSY($applicableStudentId) == true){

    //                     // Student id that has qualified requirements.
    //                     // Enrollment form based.
    //                     echo $applicableStudentId . "has been eligible to apply next s_y";

    //                 }
    //             }
    //         }
    //         else{
    //             // echo "nothing eligible";
    //         }
    //     }
    // }


?>


    <div class="col-md-12 row table-responsive">
        <h4 class="text-center">Registrar Dashboard</h4>
        <table class="table table-bordered ">
            <thead>
                <tr>
                <th>Header 1</th>
                <th>Header 2</th>
                <th>Header 3</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>Data 1</td>
                <td>Data 2</td>
                <td>Data 3</td>
                </tr>
                <tr>
                <td>Data 4</td>
                <td>Data 5</td>
                <td>Data 6</td>
                </tr>
                <tr>
                <td>Data 7</td>
                <td>Data 8</td>
                <td>Data 9</td>
                </tr>
            </tbody>
        </table>
    </div>

<?php include_once('../../includes/footer.php') ?>
