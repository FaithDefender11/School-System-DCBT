<?php

include_once('../../includes/admin_header.php');
include_once('../../includes/classes/Section.php');
include_once('../../includes/classes/Enrollment.php');
include_once('../../includes/classes/SchoolYear.php');

$school_year = new SchoolYear($con, null);
$section = new Section($con, null);
$enrollment = new Enrollment($con);
$school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

$current_school_year_term = $school_year_obj['term'];
$current_school_year_period = $school_year_obj['period'];

if (isset($_GET['id'])) {

    $course_id = $_GET['id'];

    $section = new Section($con, $course_id);

    $sectionName = $section->GetSectionName();

    $section_program_id = $section->GetSectionProgramId($course_id);
    $course_level = $section->GetSectionGradeLevel();
}
?>

<div class="col-md-12 row">
    <div class="content">
        <div class="floating" id="college-teachers">

            <header>
                <div class="title">
                    <h3><?php echo $sectionName;?> Subjects</h3>
                    <span><?php echo $current_school_year_term;?></span>
                </div>
            </header>

            <main>
                <table class="ws-table-all cw3-striped cw3-bordered" style="margin: 0">
                    <thead>
                        <tr>
                            <th>S.P ID</th>
                            <th>Subject ID</th>
                            <th>Title</th>
                            <th>Code</th>
                            <th>Type</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th>Day</th>
                            <th>Time</th>
                            <th>Instructor</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php 

                            $sql = $con->prepare("SELECT 
                            
                                t1.*

                                FROM subject_program as t1
                                WHERE t1.program_id=:program_id

                                AND t1.course_level=:course_level
                                AND (t1.semester='First'
                                    OR
                                    t1.semester='Second'
                                )

                                ORDER BY t1.course_level DESC,
                                t1.semester
                                ");
                            
                            // $sql->bindValue(":course_id", $course_id);
                            $sql->bindValue(":program_id", $section_program_id);
                            $sql->bindValue(":course_level", $course_level);
                            // $sql->bindValue(":semester", $current_school_year_period);
                            
                            $sql->execute();

                            if($sql->rowCount() > 0){

                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                    $subject_program_id = $row['subject_program_id'];
                                    $subject_code = $row['subject_code'];
                                    $subject_title = $row['subject_title'];
                                    $course_level = $row['course_level'];
                                    $semester = $row['semester'];
                                    // $pre_requisite = $row['pre_requisite'];
                                    $pre_requisite = $row['pre_req_subject_title'];
                                    $subject_type = $row['subject_type'];
                                    // $subject_subject_program_id = $row['subject_subject_program_id'];
                                    // $subject_subject_title = $row['subject_subject_title'];

                                    $statuss = "N/A";

                                    $subject_real = $con->prepare("SELECT 
                                            
                                        t1.subject_title as t1_subject_title,
                                        t1.subject_code as t1_subject_code,
                                        t1.subject_id as t1_subject_id,
                                        t1.subject_program_id as t1_subject_program_id,

                                        t3.firstname,
                                        t3.lastname,
                                        t2.time_from,
                                        t2.time_to,
                                        t2.schedule_time,
                                        t2.schedule_day,
                                        t2.subject_schedule_id

                                        FROM subject as t1 

                                        LEFT JOIN subject_schedule as t2 ON t2.subject_id = t1.subject_id
                                        LEFT JOIN teacher as t3 ON t3.teacher_id = t2.teacher_id

                                        WHERE t1.subject_program_id=:subject_program_id
                                        AND t1.course_id=:course_id
                                        LIMIT 1");
                                                    
                                    $subject_real->bindValue(":subject_program_id", $subject_program_id);
                                    $subject_real->bindValue(":course_id", $course_id);
                                    $subject_real->execute();

                                    $t1_subject_program_id = null;
                                    $t1_subject_code = null;
                                    $t1_subject_id = null;
                                    $firstname = null;
                                    $lastname = null;
                                    $time_from = null;
                                    $time_to = null;
                                    $schedule_time = null;
                                    $schedule_day = null;
                                    $subject_schedule_id = null;

                                    if($subject_real->rowCount() > 0){

                                        // $asd = $subject_real->fetchAll(PDO::FETCH_ASSOC);

                                        // print_r($asd);

                                        $row = $subject_real->fetch(PDO::FETCH_ASSOC);

                                        $t1_subject_title = $row['t1_subject_title'];
                                        $t1_subject_code = $row['t1_subject_code'];
                                        $t1_subject_id = $row['t1_subject_id'];
                                        
                                        $t1_subject_program_id = $row['t1_subject_program_id'];
                                        $firstname = $row['firstname'];
                                        $time_from = $row['time_from'];
                                        $time_to = $row['time_to'];
                                        $lastname = $row['lastname'];
                                        $schedule_time = $row['schedule_time'];
                                        $schedule_day = $row['schedule_day'];
                                        $subject_schedule_id = $row['subject_schedule_id'];

                                    }
                                   
                                    if($t1_subject_program_id != null 
                                        && $t1_subject_program_id == $subject_program_id){
                                            
                                        $statuss = "
                                            <a href=''>
                                                <i style='color: green;'class='fas fa-check'></i>
                                            </a>
                                        ";
                                    }

                                    else if($subject_program_id != null 
                                        && $t1_subject_program_id == null
                                        ){
                                        $populateBtn = "populateBtn($subject_program_id, $course_id)";

                                        $statuss = "
                                            <a href='populate_subject.php?sp_id=$subject_program_id&id=$course_id'>
                                                <button class='btn btn-success'>
                                                    Populate
                                                </button>
                                            </a>

                                        ";
                                    }

                                    $add_schedule_url=  "";

                                    if($t1_subject_id !== null){
                                        $add_schedule_url = "../schedule/subject_assign.php?id=$t1_subject_id";
                                    }
                                    
                                    $haveSchedule = "";
                                    $schedule = "";
                                    $day = "";

                                    if($schedule_day != null){
                                        $schedule = $schedule_time;
                                    }else{
                                        $schedule = "N/A";
                                    }


                                    if($firstname != null && $lastname != null && $t1_subject_id !== null){
                                        $haveSchedule = "
                                            <a href='../schedule/subject_edit.php?id=$subject_schedule_id'>
                                                $firstname $lastname
                                            </a>
                                        ";
                                    }else if($firstname == null 
                                        && $lastname == null
                                        && $t1_subject_id !== null
                                        ){
                                        $haveSchedule = "
                                            <a href='$add_schedule_url'>
                                                <button class='btn  btn-primary'>
                                                    <i class='bi bi-calendar'></i>
                                                </button>
                                            </a>
                                        "; 
                                    }

                                    echo "
                                        <tr class='text-center'>
                                            <td>$subject_program_id</td>
                                            <td>
                                                <a href='../subject/edit.php?id=$t1_subject_id'>
                                                    $t1_subject_id
                                                </a>
                                            </td>
                                            <td>$subject_title</td>
                                            <td>$t1_subject_code</td>
                                            <td>$subject_type</td>
                                            <td>$semester</td>
                                            <td>$statuss</td>
                                            <td>$schedule</td>
                                            <td>$schedule_day</td>
                                            <td>
                                                $haveSchedule
                                            </td>
                                        </tr>
                                    ";

                                }
                            }
                        ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</div>


<script>
    function populateBtn($subject_program_id, $course_id){


    }
</script>