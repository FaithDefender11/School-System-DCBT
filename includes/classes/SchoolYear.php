<?php

    class SchoolYear{


    private $con, $sqlData;

    public function __construct($con, $input = null)
    {
        $this->con = $con;
        $this->sqlData = $input;
        
        if(!is_array($input)){
            $query = $this->con->prepare("SELECT * FROM school_year
                WHERE school_year_id=:school_year_id");

            $query->bindParam(":school_year_id", $input);
            $query->execute();
            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetSchoolYearPeriod() {
        return isset($this->sqlData['period']) ? $this->sqlData["period"] : 0; 
    }

    public function GetEnrollmentStatus() {
        return isset($this->sqlData['enrollment_status']) 
            ? $this->sqlData["enrollment_status"] : 0; 
    }

    public function GetBreakEnded() {
        return isset($this->sqlData['break_ended']) 
            ? $this->sqlData["break_ended"] : 0; 
    }

    public function GetFinalExamEnded() {
        return isset($this->sqlData['final_exam_ended']) 
            ? $this->sqlData["final_exam_ended"] : null; 
    }

    public function GetStartEnrollment() {
        return isset($this->sqlData['start_enrollment_date']) 
            ? $this->sqlData["start_enrollment_date"] : null; 
    }
    public function GetEndEnrollment() {
        return isset($this->sqlData['end_enrollment_date']) 
            ? $this->sqlData["end_enrollment_date"] : null; 
    }

    public function GetSYEnrollmentStatus() {
        return isset($this->sqlData['end_enrollment_date']) 
            ? $this->sqlData["end_enrollment_date"] : null; 
    }


    public function GetTerm() {
        return isset($this->sqlData['term']) 
            ? $this->sqlData["term"] : null; 
    }
    public function GetPeriod() {
        return isset($this->sqlData['period']) 
            ? $this->sqlData["period"] : null; 
    }

    public function GetStatuses() {
        return isset($this->sqlData['statuses']) 
            ? $this->sqlData["statuses"] : null; 
    }


    public function GetprelimStartDate() {
        return isset($this->sqlData['prelim_exam_startdate']) 
            ? $this->sqlData["prelim_exam_startdate"] : null; 
    }
    public function GetprelimEndDate() {
        return isset($this->sqlData['prelim_exam_enddate']) 
            ? $this->sqlData["prelim_exam_enddate"] : null; 
    }

    public function GetmidtermStartDate() {
        return isset($this->sqlData['midterm_exam_startdate']) 
            ? $this->sqlData["midterm_exam_startdate"] : null; 
    }
    public function GetmidtermEndDate() {
        return isset($this->sqlData['midterm_exam_enddate']) 
            ? $this->sqlData["midterm_exam_enddate"] : null; 
    }

    public function GetprefinalStartDate() {
        return isset($this->sqlData['prefinal_exam_startdate']) 
            ? $this->sqlData["prefinal_exam_startdate"] : null; 
    }
    public function GetprefinalEndDate() {
        return isset($this->sqlData['prefinal_exam_enddate']) 
            ? $this->sqlData["prefinal_exam_enddate"] : null; 
    }

    public function GetfinalStartDate() {
        return isset($this->sqlData['final_exam_startdate']) 
            ? $this->sqlData["final_exam_startdate"] : null; 
    }
    public function GetfinalEndDate() {
        return isset($this->sqlData['final_exam_enddate']) 
            ? $this->sqlData["final_exam_enddate"] : null; 
    }

    public function GetbreakStartDate() {
        return isset($this->sqlData['break_startdate']) 
            ? $this->sqlData["break_startdate"] : null; 
    }
    public function GetbreakEndDate() {
        return isset($this->sqlData['break_enddate']) 
            ? $this->sqlData["break_enddate"] : null; 
    }



    public function CheckTermPeriodExists($term, $period){

        $query = $this->con->prepare("SELECT 
        
            school_year_id

            FROM school_year

            WHERE term=:term
            AND period=:period

            LIMIT 1");

        $query->bindParam(":term", $term);
        $query->bindParam(":period", $period);
        $query->execute();

        return $query->rowCount() > 0;
            
    }

    public function GetSchoolYearIdByTermPeriod($term, $period){

        $query = $this->con->prepare("SELECT 
        
            school_year_id

            FROM school_year

            WHERE term=:term
            AND period=:period

            LIMIT 1");

        $query->bindParam(":term", $term);
        $query->bindParam(":period", $period);
        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchColumn();
        }
        return null;
            
    }


    public function GetAllSchoolYearTerm(){

        $query = $this->con->prepare("SELECT term

            FROM school_year
            GROUP BY term
        ");

        $query->execute();
        
        if($query->rowCount() > 0){
            return $query->fetchAll(PDO::FETCH_COLUMN);
        }

        return null;
    }

    public function GetActiveSchoolYearAndSemester(){

        $query = $this->con->prepare("SELECT school_year_id,
            term, period

            FROM school_year
            WHERE statuses='Active'
            -- ORDER BY school_year_id DESC
            LIMIT 1");

        $query->execute();
        if($query->rowCount() > 0){
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    function getSchoolYearValue($school_year_obj, $key) {

        return $school_year_obj !== null 
            && isset($school_year_obj[$key]) ? $school_year_obj[$key] : "";

    }


    public function GetTermTimeFrame($term,
        $semester, $activeSemester = null) : string{

        $result = "";

        $query = $this->con->prepare("SELECT * 
            FROM school_year

            WHERE term=:term
            AND period=:period
            LIMIT 1");

        $query->bindParam(":term", $term);
        $query->bindParam(":period", $semester);
        $query->execute();

        if($query->rowCount() > 0){
            
            $row = $query->fetch(PDO::FETCH_ASSOC);

            $school_year_id = $row['school_year_id'];
            $school_year_period = $row['period'];
            $school_year_term = $row['term'];
            $enrollment_status = $row['enrollment_status'];
            $is_finished = $row['is_finished'];
            $final_exam_ended = $row['final_exam_ended'];
            $break_ended = $row['break_ended'];

            // echo $school_year_id;

            $start_enrollment_date_db = $row['start_enrollment_date'];
            $start_enrollment_date = $start_enrollment_date_db !== NULL ? date('Y-m-d', strtotime($start_enrollment_date_db)) : 'Not Set';

            $start_enrollment_date_status = $this->CheckIfEnded($start_enrollment_date_db);
            
            $endingEnrollmentDate = "endingDate($school_year_id, \"$school_year_period\",
                \"end_enrollment\", \"$school_year_term\")";

            $endEnrollmentEnded = "
                <span style='cursor:pointer; color: green;'  onclick='$endingEnrollmentDate'>Not Set</span>
            ";

            $end_enrollment_date_db = $row['end_enrollment_date'];
            $end_enrollment_date = $end_enrollment_date_db !== NULL ? date('Y-m-d', strtotime($end_enrollment_date_db)) : $endEnrollmentEnded;
            // $final_exam_enddate = $final_exam_enddate_db !== NULL ? date('Y-m-d', strtotime($final_exam_enddate_db)) : $finalExamEnded;

            $start_enrollment_date_status = $this->CheckIfEnded($end_enrollment_date_db);

            $start_period = $row['start_period'];
            $end_period = $row['end_period'];

            $class_startdate = $row['class_startdate'];
            $class_startdate = $class_startdate !== NULL ? date('Y-m-d', strtotime($class_startdate)) : 'Not Set';


            $class_enddate_db = $row['class_enddate'];
            $class_enddate = $class_enddate_db !== NULL ? date('Y-m-d', strtotime($class_enddate_db)) : 'Not Set';
            $class_enddate_status = $this->CheckIfEnded($class_enddate_db);


            $prelim_exam_startdate = $row['prelim_exam_startdate'];
            $prelim_exam_startdate = $prelim_exam_startdate !== NULL ? date('Y-m-d', strtotime($prelim_exam_startdate)) : 'Not Set';

            $prelim_exam_enddate_db = $row['prelim_exam_enddate'];
            $prelim_exam_enddate = $prelim_exam_enddate_db !== NULL ? date('Y-m-d', strtotime($prelim_exam_enddate_db)) : 'Not Set';
            $prelim_exam_enddate_status = $this->CheckIfEnded($prelim_exam_enddate_db);


            $midterm_exam_startdate = $row['midterm_exam_startdate'];
            $midterm_exam_startdate = $midterm_exam_startdate !== NULL ? date('Y-m-d', strtotime($midterm_exam_startdate)) : 'Not Set';

            $midterm_exam_enddate_db = $row['midterm_exam_enddate'];
            $midterm_exam_enddate = $midterm_exam_enddate_db !== NULL ? date('Y-m-d', strtotime($midterm_exam_enddate_db)) : 'Not Set';
            $midterm_exam_enddate_status = $this->CheckIfEnded($midterm_exam_enddate_db);

            $prefinal_exam_startdate = $row['prefinal_exam_startdate'];
            $prefinal_exam_startdate = $prefinal_exam_startdate !== NULL ? date('Y-m-d', strtotime($prefinal_exam_startdate)) : 'Not Set';

            $prefinal_exam_enddate_db = $row['prefinal_exam_enddate'];
            $prefinal_exam_enddate = $prefinal_exam_enddate_db !== NULL ? date('Y-m-d', strtotime($prefinal_exam_enddate_db)) : 'Not Set';
            $prefinal_exam_enddate_status = $this->CheckIfEnded($prefinal_exam_enddate_db);

            $final_exam_startdate = $row['final_exam_startdate'];
            $final_exam_startdate = $final_exam_startdate !== NULL ? date('Y-m-d', strtotime($final_exam_startdate)) : 'Not Set';


            $endingFinal = "endingDate($school_year_id, \"$school_year_period\",
                \"finals\", \"$school_year_term\")";
                

            $finals_e_d_color = $final_exam_ended === 1 && $school_year_period === $activeSemester ? "green" : "";

            $final_exam_enddate_db = $row['final_exam_enddate'];
            $final_exam_enddate_db = date('Y-m-d', strtotime($final_exam_enddate_db));

            $finalExamEnded = "
                <span style='cursor:pointer; color: $finals_e_d_color;' 
                    onclick='$endingFinal'>$final_exam_enddate_db</span>
            ";

            // $finalExamEnded = "
            //     <span style='cursor:pointer; color: $finals_e_d_color;' 
            //         onclick='$endingFinal'>Not Set</span>
            // ";

            // $final_exam_enddate = $final_exam_enddate_db !== NULL 
            //     ? date('Y-m-d', strtotime($final_exam_enddate_db)) 
            //     : $finalExamEnded;

            $final_exam_enddate_status = $this->CheckIfEnded($final_exam_enddate_db);

            $break_startdate = $row['break_startdate'];
            $break_startdate = $break_startdate !== NULL ? date('Y-m-d', strtotime($break_startdate)) : 'Not Set';

            $break_enddate_db = $row['break_enddate'];
            $break_enddate = $break_enddate_db !== NULL ? date('Y-m-d', strtotime($break_enddate_db)) : 'Not Set';
            $break_enddate_status = $this->CheckIfEnded($break_enddate_db);


            $startEnrollmentDate = "startEnrollmentDate($school_year_id, \"$school_year_period\",
                \"startEnrollmentDate\", \"$school_year_term\")";


            $endEnrollmentDate = "endEnrollmentDate($school_year_id, \"$school_year_period\",
                \"endEnrollmentDate\", \"$school_year_term\")";


            $start_e_d_color = $enrollment_status === 1 && $school_year_period == $activeSemester ?  "green" : "";
            $end_e_d_color = $is_finished === 1 && $school_year_period == $activeSemester ?  "green" : "";



            $result .= "
                <tr>
                    <td>Enrollment Period</td>
                    <td>
                        <span style='cursor:pointer; color: $start_e_d_color' 
                            onclick='$startEnrollmentDate'>

                            $start_enrollment_date
                        </span>
                    </td>
                    <td>
                        <span style='cursor:pointer; color: $end_e_d_color'
                            onclick='$endEnrollmentDate'>
                            $end_enrollment_date
                        </span>

                    </td>
                    <td>$start_enrollment_date_status</td>
                </tr>
            ";

            $result .= "
                <tr>
                    <td>Class Start</td>
                    <td>$class_startdate</td>
                    <td>$class_enddate</td>
                    <td>$class_enddate_status</td>
                </tr>
            ";

            $result .= "
                <tr>
                    <td>Prelim Exam</td>
                    <td>$prelim_exam_startdate</td>
                    <td>$prelim_exam_enddate</td>
                    <td>$prelim_exam_enddate_status</td>
                </tr>
            ";

            $result .= "
                <tr>
                    <td>Midterm Exam</td>
                    <td>$midterm_exam_startdate</td>
                    <td>$midterm_exam_enddate</td>
                    <td>$midterm_exam_enddate_status</td>
                </tr>
            ";

            $result .= "
                <tr>
                    <td>Pre-Final Exam</td>
                    <td>$prefinal_exam_startdate</td>
                    <td>$prefinal_exam_enddate</td>
                    <td>$prefinal_exam_enddate_status</td>
                </tr>
            ";


            $endFinalExamData = "endFinalExamData($school_year_id, \"$school_year_period\",
                \"endFinalExamData\", \"$school_year_term\")";

                
            // <td>$final_exam_enddate</td>
            $result .= "
                <tr>
                    <td>Final Exam</td>
                    <td>$final_exam_startdate</td>
                    <td>$finalExamEnded</td>
                    
                    <td>$final_exam_enddate_status</td>
                </tr>
            ";

            $endBreak = "endBreak($school_year_id, \"$school_year_period\")";

            $breakEnded = "breakEnded($school_year_id, \"$school_year_period\",
                \"breakEnded\", \"$school_year_term\")";

            $break_e_d_color = $break_ended === 1 && $school_year_period == $activeSemester ? "green" : "";

            $result .= "
                <tr>
                    <td style='cursor: pointer;' onclick='$endBreak'>Break</td>
                    <td>$break_startdate</td>
                    <td>
                        <span style='cursor:pointer; color: $break_e_d_color' 
                            onclick='$breakEnded'>
                            $break_enddate
                        </span>
                    </td>
                    <td>$break_enddate_status</td>
                </tr>
            ";

            
        }

        return $result;

    }

    public function CheckIfEnded($date){

        $currentDateTime = new DateTime();
        $current_time = $currentDateTime->format('Y-m-d H:i:s');

        if($current_time >= $date){
            return "Ended";
        }

        return "Ongoing";
    }


    public function ChangingEnrollmentPeriod($school_year_id){

        $currentDateTime = new DateTime();
        $current_time = $currentDateTime->format('Y-m-d H:i:s');

        $section = new Section($this->con);
        $school_year_exec = new SchoolYear($this->con, $school_year_id);

        $current_term_exec = $school_year_exec->GetTerm();

        $enrollmentStartDate = $school_year_exec->GetStartEnrollment();
        $enrollmentEndDate = $school_year_exec->GetEndEnrollment();
        $enrollmentStatus = $school_year_exec->GetEnrollmentStatus();

        $enrollment_status_active = 1;
        $enrollment_status_inactive = 0;
        # Enrollment Start Date August 10 7:00
        # Current Date August 10 7:01

        // echo $current_time;
        // echo "<br>";
        // echo $enrollmentStartDate;
        // echo "<br>";
        // echo "<br>";
        // echo $enrollmentEndDate;

        if($current_time >= $enrollmentStartDate 
            && $current_time < $enrollmentEndDate
            && $enrollmentStartDate < $enrollmentEndDate
            && $enrollmentStatus === 0){

            # Enrollment Status into 1
            $wasSuccess = $this->SetEnrollmentOngoingStatus(
                $school_year_id, $enrollment_status_active);

            if($wasSuccess){
                // echo "Success 1";
                Alert::success("Enrollment Date is now started", "");
            }

        }else if($current_time >= $enrollmentEndDate 
            && $current_time > $enrollmentStartDate
            && $enrollmentStatus === 1){

            # Enrollment Status into 0, Ended Status

            $wasSuccess = $this->SetEnrollmentOngoingStatus(
                $school_year_id, $enrollment_status_inactive);


            if($wasSuccess){

                // echo "Success 2";
                # Creation of this is coming from end finals trigger.
                $removeUnEnrolledSections = $section->RemoveUnEnrolledCreatedSectionWithinSemester(
                    $current_term_exec, $school_year_id);
                Alert::success("Enrollment Date is now ended", "");
            }
        }
        
    }

    public function ChangingFinalEndDate($school_year_id){

        $currentDateTime = new DateTime();
        $current_time = $currentDateTime->format('Y-m-d H:i:s');

        $enrollment = new Enrollment($this->con);

        $student = new Student($this->con);
        $section = new Section($this->con);
        $school_year_exec = new SchoolYear($this->con, $school_year_id);

      
        $current_term_exec = $school_year_exec->GetTerm();
        $current_period_exec = $school_year_exec->GetPeriod();

        $finalEndDate = $school_year_exec->GetfinalEndDate();

        $final_exam_ended = $school_year_exec->GetFinalExamEnded();

        // echo $current_time;
        // echo "<br>";

        // echo $finalEndDate;
        // echo "<br>";


        if($current_time >= $finalEndDate && $final_exam_ended == NULL){

            // echo "finalEndedDate";

            if(true){

                if($current_period_exec == "First"){

                    # All IsFull In First Semester Sections should be reset.
                    $resetSection = $section->ResetCurrentActiveSections($current_term_exec);
                    
                    $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($school_year_id);
                                
                    foreach ($currentNewEnrolled as $key => $student_ids) {
                        // All new enrolled student in the enrollment form will update as OLD
                        $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);
                    }

                    $finalEndedSuccess = $this->SetFinalExamEnded($school_year_id);
                    
                    Alert::success("First Semester Finals Date has ended", "");
                }

                if($current_period_exec == "Second"){

                    # Create new Moving Up Section based on deactivated section.
                    # AND SHS -> Only Grade 12 maximum, if current section level is grade 12
                    # All that grade 12 should be deactivated and should not be moved-up
                    
                    $movingUpSection = $section->MovingUpCurrentActiveSections($current_term_exec);
                    # if($movingUpSection) echo "update_success";

                    # Section useless should be removed at the end of enrollment date.

                    #  Deactive the section that are being created but doesnt have any student enrolled in it.
                    $deactiveCurrentSection = $section->DeactiveCurrentActiveSections($current_term_exec);
                
                    # Create each one section to be used for new students section.
                    $createEachNewSection = $section->CreateEachSectionStrandCourse($current_term_exec);

                    if($createEachNewSection){

                        $currentNewEnrolled = $enrollment->GetEnrolledNewStudentWithinSemester($school_year_id);
                        
                        foreach ($currentNewEnrolled as $key => $student_ids) {
                            // All new enrolled student in the enrollment form will update as OLD
                            $toOld = $student->UpdateStudentAdmissionStatusToOld($student_ids);
                        }

                    }
                    $finalEndedSuccess = $this->SetFinalExamEnded($school_year_id);

                    Alert::success("Second Semester Finals Date has ended", "");
                }
            }
 
        }

    }

    public function ChangingBreakPeriod($school_year_id){

        $currentDateTime = new DateTime();
        $current_time = $currentDateTime->format('Y-m-d H:i:s');

        $section = new Section($this->con);
        $school_year_exec = new SchoolYear($this->con, $school_year_id);

        $current_term_exec = $school_year_exec->GetTerm();

        $breakStartDate = $school_year_exec->GetbreakStartDate();
        $breakEndDate = $school_year_exec->GetbreakEndDate();

        $break_ended = $school_year_exec->GetBreakEnded();

        $ended = 1;
        # Enrollment Start Date August 10 7:00
        # Current Date August 10 7:01

        // echo $current_time;
        // echo "<br>";
        // echo $breakStartDate;
        // echo "<br>";
        // echo $breakEndDate;
        // echo "<br>";

        if($current_time >= $breakEndDate
            && $break_ended === 0){

            $wasSuccess = $this->SetBreakEnded($school_year_id, $ended);

            if($wasSuccess){

                # Changing into another S.Y School_Year BY ID DESC

                $hasChangeIntoNextSY = $this->SetAnotherRowOfSchoolYear($school_year_id);
                if($hasChangeIntoNextSY){
                    // Current Year Period Should be InActive.
                    $currentYearPeriodInActive = $this->SetCurrentYearPeriodInActive($school_year_id);
                    if($currentYearPeriodInActive){
                        Alert::success("Successfully Set to another School Year Row", "");
                    }
                }
            }

        } 
        
    }


    public function SetEnrollmentOngoingStatus($school_year_id,
        $enrollment_status, $type_enrollment_date = null){
       
        $now = date("Y-m-d H:i:s");

        $update = $this->con->prepare("UPDATE school_year
            SET enrollment_status=:enrollment_status,
                $type_enrollment_date=:type_enrollment_date

            WHERE school_year_id=:school_year_id
            -- AND enrollment_status = 1
            AND statuses='Active'");


        $update->bindParam(":enrollment_status", $enrollment_status);
        $update->bindParam(":type_enrollment_date", $now);
        $update->bindParam(":school_year_id", $school_year_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function SetEndEnrollmentDate($school_year_id,
        $enrollment_status, $type_enrollment_date = null){
       
        $now = date("Y-m-d H:i:s");
        $is_finished = 1;

        $update = $this->con->prepare("UPDATE school_year
            SET enrollment_status=:enrollment_status,
                $type_enrollment_date=:type_enrollment_date,
                is_finished=:is_finished

            WHERE school_year_id=:school_year_id
            -- AND enrollment_status = 1
            AND statuses='Active'");


        $update->bindParam(":enrollment_status", $enrollment_status);
        $update->bindParam(":type_enrollment_date", $now);
        $update->bindParam(":is_finished", $is_finished);
        $update->bindParam(":school_year_id", $school_year_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function SetCurrentYearPeriodInActive($school_year_id){
       
        // echo ">>";
        $statuses = "InActive";

        $update = $this->con->prepare("UPDATE school_year
            SET statuses=:statuses
            WHERE school_year_id=:school_year_id
            AND statuses='Active'");


        $update->bindParam(":statuses", $statuses);
        $update->bindParam(":school_year_id", $school_year_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function SetAnotherRowOfSchoolYear($school_year_id){
       
        // echo $school_year_id;

        $now = date("Y-m-d H:i:s");

        $gettingNextRow = $this->con->prepare("SELECT * FROM school_year 
                WHERE school_year_id > (
                    SELECT school_year_id FROM school_year WHERE school_year_id = :school_year_id
                )
                ORDER BY school_year_id ASC
                LIMIT 1
            ");

        $gettingNextRow->bindParam(":school_year_id", $school_year_id);
        $gettingNextRow->execute();

        if($gettingNextRow->rowCount() > 0){

            $nextRow = $gettingNextRow->fetch(PDO::FETCH_ASSOC);

            $next_school_year_id =  $nextRow['school_year_id'];

            // echo "next_school_year_id: $next_school_year_id";
            // echo "<br>";

            // Once hit the Break.
            // Next S_Y Id statuses = Acttive & 
            // enrollment_Status = 1 and start_enrollment_date = NOW

            $update = $this->con->prepare("UPDATE school_year
                SET statuses=:statuses,
                    -- enrollment_status=:enrollment_status,
                    start_enrollment_date=:start_enrollment_date
                WHERE school_year_id=:school_year_id");
            
            $update->bindValue(":statuses", "Active");
            // $update->bindValue(":enrollment_status", 1, PDO::PARAM_INT);
            $update->bindValue(":start_enrollment_date", $now);
            $update->bindParam(":school_year_id", $next_school_year_id);
            $update->execute();

            if($update->rowCount() > 0){
                return true;
            }

        }

        return false;
    }

    public function SetBreakEnded($school_year_id,
        $break_ended){
       
        // echo ">>";
        // $break_ended = 1 ;

        $update = $this->con->prepare("UPDATE school_year
            SET break_ended=:break_ended
            WHERE school_year_id=:school_year_id
            AND break_ended = 0
            AND statuses='Active'");


        $update->bindParam(":break_ended", $break_ended);
        $update->bindParam(":school_year_id", $school_year_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }

    public function SetFinalExamEnded($school_year_id){
       
       
        $final_exam_ended = 1;

        $update = $this->con->prepare("UPDATE school_year
            SET final_exam_ended=:final_exam_ended
            WHERE school_year_id=:school_year_id
            AND final_exam_ended IS NULL
            AND statuses='Active'");


        $update->bindParam(":final_exam_ended", $final_exam_ended);
        $update->bindParam(":school_year_id", $school_year_id);
        $update->execute();

        if($update->rowCount() > 0){
            return true;
        }
        return false;
    }
    public function GetSchoolYearIdBySyID($period, $term){

        $db_school_year_id = null;

        $query = $this->con->prepare("SELECT school_year_id FROM school_year

            WHERE period=:period
            AND term=:term
            LIMIT 1
        ");

        $query->bindParam(":period", $period);
        $query->bindParam(":term", $term);
        $query->execute();

        if($query->rowCount() > 0){
            $get_row = $query->fetch(PDO::FETCH_ASSOC);
            $db_school_year_id = $get_row['school_year_id'];
        }

        return $db_school_year_id;
    }
}
?>