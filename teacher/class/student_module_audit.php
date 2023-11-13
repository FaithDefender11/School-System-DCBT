<?php 

    // include_once('../../includes/config.php');
    include_once('../../includes/teacher_header.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectCodeAssignmentTemplate.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopic.php');
    include_once('../../includes/classes/SubjectPeriodCodeTopicTemplate.php');
    include_once('../../includes/classes/SubjectAssignmentSubmission.php');
    include_once('../../includes/classes/SubjectCodeAssignment.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/SubjectProgram.php');
 
    echo Helper::RemoveSidebar();
    
    ?>
        <style>
            th a {
                text-decoration: underline;
                color: inherit; /* To maintain the link color */
                white-space: nowrap; /* Prevent text from wrapping */
            }
        </style>
    <?php

    if(isset($_GET['id'])
        && isset($_GET['code'])
        && isset($_GET['sy_id'])
    ){



        $student_id = $_GET['id'];
        $subject_code = $_GET['code'];
        $school_year_id = $_GET['sy_id'];

        $student = new Student($con, $student_id);

        $studentName = ucwords($student->GetFirstName()) . " " . ucwords($student->GetLastName());

        $studentSubject = new StudentSubject($con);
        $school_year = new SchoolYear($con, $school_year_id);

        $term = $school_year->GetTerm();
        $period = $school_year->GetPeriod();

        $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");

        $studentSubjectId = $studentSubject->GetStudentSubjectProgramIdBy(
            $student_id, $subject_code, $school_year_id);

        var_dump($studentSubjectId);
        
        $studentSubject = new StudentSubject($con, $studentSubjectId);
        
        $subjectProgramId = $studentSubject->GetStudentSubjectProgramId();

        var_dump($subjectProgramId);

        $subjectProgram = new SubjectProgram($con, $subjectProgramId);

        $title = $subjectProgram->GetTitle();

        // echo $subject_period_code_topic_id;
        // $subject_period_code_topic = new SubjectPeriodCodeTopic($con, $subject_period_code_topic_id);

        // $subject_period_code_topic->gettop

        ?>

        
        <div class="content">

            <main>
                <div class="floating" id="shs-sy">
                    <header>
                        <div class="title">
                            <h3>Module: <?= $title?> Trail of <?= $studentName; ?> <?= "$term $period_short" ?></h3>
                        </div>
                    </header>
                    
                    <main>

                        <table id="department_table" class="a" style="margin: 0">

                            <thead>

                                <tr>
                                    <th>No.</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                </tr>

                            </thead>

                            <tbody>
                                <?php
                                
                                    // $SHS = "Senior High School";
                                    // $Tertiary = "Tertiary";

                                    $query = $con->prepare("SELECT 

                                        t1.*,
                                        t2.firstName,
                                        t2.lastName
                                        -- t2.role 
                                        
                                        FROM subject_module_audit as t1


                                        INNER JOIN student as t2 ON t2.student_id = t1.student_id
                                        
                                        WHERE t1.school_year_id =:school_year_id
                                        AND t1.subject_code =:subject_code
                                        AND t1.student_id =:student_id
                                        
                                        ORDER BY t1.date_creation DESC
                                    ");

                                    $query->bindValue(":school_year_id", $school_year_id);
                                    $query->bindValue(":subject_code", $subject_code);
                                    $query->bindValue(":student_id", $student_id);
                                    $query->execute();

                                    if($query->rowCount() > 0){

                                        $i = 0;

                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                            $i++;

                                            $subject_module_audit_id = $row['subject_module_audit_id'];
                                            $description = $row['description'];

                                            // $role = $row['role'];
                                            $date_creation_db = $row['date_creation'];
                                            $date_creation = date("M d, Y h:i a", strtotime($date_creation_db));

                                            $fullname = ucwords($row['firstName']) . " " . ucwords($row['lastName']);

                                            // $removeDepartmentBtn = "removeDepartmentBtn($enrollment_audit_id)";
                                            
                                            echo "
                                                <tr>
                                                    <td>$i</td>
                                                    <td>$description</td>
                                                    <td>$date_creation</td>
                                                </tr>
                                            ";
                                        }
                                    }

                                ?>
                            </tbody>
                        </table>

                    </main>
                    
                </div>
            </main>

        </div>

        <?php
    }
?>