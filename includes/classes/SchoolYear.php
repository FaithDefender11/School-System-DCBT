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

            $query->bindValue(":school_year_id", $input);
            $query->execute();
            $this->sqlData = $query->fetch(PDO::FETCH_ASSOC);
        }
    }

    public function GetActiveSchoolYearAndSemester(){

        $query = $this->con->prepare("SELECT school_year_id,
            term, period

            FROM school_year
            WHERE statuses='Active'
            -- ORDER BY school_year_id DESC
            LIMIT 1");

        $query->execute();
        
        return $query->fetch(PDO::FETCH_ASSOC);
    }


    public function GetTermTimeFrame($term, $semester) : string{

        $result = "";

        $query = $this->con->prepare("SELECT * 
                                    FROM school_year
                                    WHERE term=:term
                                    AND period=:period
                                    LIMIT 1
                                    ");

        $query->bindParam(":term", $term);
        $query->bindParam(":period", $semester);
        $query->execute();

        if($query->rowCount() > 0){
            $row = $query->fetch(PDO::FETCH_ASSOC);

            $school_year_id = $row['school_year_id'];

            // echo $school_year_id;

            $start_enrollment_date_db = $row['start_enrollment_date'];
            $start_enrollment_date = $start_enrollment_date_db !== NULL ? date('Y-m-d', strtotime($start_enrollment_date_db)) : 'Not Set';

            $start_enrollment_date_status = $this->CheckIfEnded($start_enrollment_date_db);
            
            $end_enrollment_date_db = $row['end_enrollment_date'];
            $end_enrollment_date = $end_enrollment_date_db !== NULL ? date('Y-m-d', strtotime($end_enrollment_date_db)) : 'Not Set';
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

            $final_exam_enddate_db = $row['final_exam_enddate'];
            $final_exam_enddate = $final_exam_enddate_db !== NULL ? date('Y-m-d', strtotime($final_exam_enddate_db)) : 'Not Set';
            $final_exam_enddate_status = $this->CheckIfEnded($final_exam_enddate_db);

            $break_startdate = $row['break_startdate'];
            $break_startdate = $break_startdate !== NULL ? date('Y-m-d', strtotime($break_startdate)) : 'Not Set';

            $break_enddate_db = $row['break_enddate'];
            $break_enddate = $break_enddate_db !== NULL ? date('Y-m-d', strtotime($break_enddate_db)) : 'Not Set';
            $break_enddate_status = $this->CheckIfEnded($break_enddate_db);

            $result .= "
                <tr>
                    <td>Enrollment Period</td>
                    <td>$start_enrollment_date</td>
                    <td>$end_enrollment_date</td>
                    <td>Ended</td>
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

            $result .= "
                <tr>
                    <td>Final Exam</td>
                    <td>$final_exam_startdate</td>
                    <td>$final_exam_enddate</td>
                    <td>$final_exam_enddate_status</td>
                </tr>
            ";

            $result .= "
                <tr>
                    <td>Break</td>
                    <td>$break_startdate</td>
                    <td>$break_enddate</td>
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