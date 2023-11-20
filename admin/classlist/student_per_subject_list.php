<?php 
    
    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Program.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Enrollment.php');

    ?>
        <style>
            /* Hide the default select box */
            select#school_year_id {
                display: block;
            }

            /* Style for custom checkboxes */
            .custom-checkbox {
                display: flex;
                align-items: center;
            }

            .custom-checkbox input[type="checkbox"] {
                margin-right: 5px;
            }
        </style>

    <?php
 
    if(isset($_GET['id'])
        && isset($_GET['sy_id'])
        && isset($_GET['code'])){



        $course_id = $_GET['id'];
        $school_year_id = $_GET['sy_id'];
        $subject_code = $_GET['code'];

        $school_year = new SchoolYear($con, $school_year_id);
        $enrollment = new Enrollment($con);

        $term = $school_year->GetTerm();
        $period = $school_year->GetPeriod();

        $termFormat = $enrollment->changeYearFormat($term);

        $period_short = $period === "First" ? "S1" : ($period === "Second" ? "S2" : "");
        
        $termSemesterFoarmat = $termFormat . $period_short;

        ?>
            <div class="content">
                <main>
                    <?php 

                        $get = $con->prepare("SELECT 

                            t1.student_subject_id,
                            t1.course_id,
                            t3.program_section
                            
                            FROM student_subject as t1

                            -- INNER JOIN student_subject as t2 ON t2.student_subject_id = t1.student_subject_id
                            INNER JOIN course as t3 ON t3.course_id = t1.course_id

                            AND t1.is_final = 1
                            AND t1.school_year_id=:school_year_id
                            AND t1.course_id=:course_id
                            AND t1.program_code=:program_code

                            -- GROUP BY t1.course_id
                        ");

                        $get->bindValue(":school_year_id", $school_year_id);
                        $get->bindValue(":course_id", $course_id);
                        $get->bindValue(":program_code", $subject_code);
                        
                        $get->execute();

                        if($get->rowCount() > 0){

                            $sectionsByProgramList = $get->fetchAll(PDO::FETCH_ASSOC);

                            foreach ($sectionsByProgramList as $key => $value) {

                                # code...

                                $section = new Section($con, $course_id);
                                $sectionName = $section->GetSectionName();
                                $enrolled_course_level = $section->GetSectionGradeLevel();
                                $enrolled_course_capacity = $section->GetSectionCapacity();

                                $section_subject_code = $section->CreateSectionSubjectCode($sectionName, $subject_code);

                                ?>

                                    <div class="floating">
                                        <header>
                                            <div class="title">   
                                                <h4 style="margin-bottom: 13px;" id="clickSchedule">
                                                    Student enrolled list on subject code <?= "$section_subject_code, SY$termSemesterFoarmat";?>
                                                </h4>

                                            </div>
                                        </header>

                                        <main>
                                            <table id="" class="a" style="margin-bottom: 0px; margin-top:15px;">
                                            
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
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                
                                                <tbody>
                                                    <?php 
                                                    
                                                        $query = $con->prepare("SELECT 
                                                        
                                                            t3.student_id,
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

                                                            INNER JOIN student_subject as t6 ON t6.student_id = t3.student_id
                                                            AND t6.is_final = 1
                                                            AND t6.subject_code = :subject_code

                                                        
                                                            LEFT JOIN course as t4  ON t4.course_id = t3.course_id
                                                            LEFT JOIN program as t5  ON t5.program_id = t4.program_id
                                                            
                                                            -- WHERE t3.course_id = :course_id
                                                            -- AND t2.is_final = :is_final
                                                            
                                                        ");

                                                        $query->bindValue(":subject_code", $section_subject_code);

                                                        $query->execute();

                                                        while($row = $query->fetch(PDO::FETCH_ASSOC)){

                                                            
                                                            $student_id = trim($row['student_id']);

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

                                                            // $removeDepartmentBtn = "removeDepartmentBtn($department_id)";
                                                            echo "
                                                                <tr>
                                                                    <td>$student_unique_id</td>
                                                                    <td>$fullname</td>
                                                                    <td>$sex</td>
                                                                    <td>$contact_number</td>
                                                                    <td>$civil_status</td>
                                                                    <td>$acronym</td>
                                                                    <td>$course_level</td>
                                                                    <td>$admission_status</td>
                                                                    <td>
                                                                        <a href='student_enrolled_list.php?id=$student_id'>
                                                                            <button title='View enrolled list' class='btn btn-sm btn-info'>
                                                                                <i class='fas fa-clock'></i>
                                                                            </button>
                                                                        </a>
                                                                        <a href='student_per_grades.php?id=$student_id'>
                                                                            
                                                                            <button title='View grade records' class='btn btn-sm btn-primary'>
                                                                                <i class='fas fa-eye'></i>
                                                                            </button>
                                                                        </a>

                                                                    </td>
                                                                </tr>
                                                            ";
                                                        }

                                                    ?>
                                                </tbody>
                                            </table>

                                        </main>
                                    </div>
                                <?php
                            }
                        }
                    ?>
                </main>
            </div>
        <?php

    }

?>