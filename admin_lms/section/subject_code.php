<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/SubjectSchedule.php');
    include_once('../../includes/classes/Teacher.php');

    
    ?>

        <head>
            <style>
                .show_search{
                    position: relative;
                    /* margin-top: -38px;
                    margin-left: 215px; */
                }
                div.dataTables_length {
                    display: none;
                }

                #enrolled_students_table_filter{
                margin-top: 12px;
                width: 100%;
                display: flex;
                flex-direction: row;
                justify-content: start;
                }

                #enrolled_students_table_filter input{
                width: 250px;
                }
            </style>

            <link href='https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        </head>

    <?php


    $sy =  41;
    
    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    

    if(isset($_GET['id'])){

        $course_id = $_GET['id'];


        $section = new Section($con, $course_id);

        $sectionProgramId = $section->GetSectionProgramId($course_id);
        $sectionName = $section->GetSectionName();
        $sectionLevel = $section->GetSectionGradeLevel();

        $subjectSchedule = new SubjectSchedule($con);
        
        // $current_school_year_period = "Second";
        // $sectionLevel = 12;

        $sectionSubjectCodes = $section->GetSectionSubjectCodes(
            $sectionProgramId, $current_school_year_period,
            $sectionLevel, "SHS");

            // var_dump($sectionSubjectCodes);

        $back_url = "shs_index.php";
        ?>

        <div class="content">

            <nav>
                <a href="<?php echo $back_url;?>">
                    <i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>

            <main>
            
                <div class="floating" id="shs-sy">

                    <header>

                        <div class="title">
                            <h5 class="mb-3"><?= "<span class='text-primary'>$sectionName</span>"; ?> Subjects &nbsp;<?php echo "<span class='text-right'>A.Y $current_school_year_term $current_school_year_period Semester</span>" ?></h5>
                        </div>
                        
                    </header>

                    <main>

                    <?php if(count($sectionSubjectCodes) > 0):?>

                            <table style="width: 100%" id="enrolled_section_list" class="a" >
                                
                                <thead>
                                    <tr>
                                        <th>Description</th>  
                                        <th>Code</th>
                                        <th>Instructor</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php 

                                        foreach ($sectionSubjectCodes as $key => $value) {

                                            $subject_title = $value['subject_title'];
                                            $rawCode = $value['subject_code'];
                                            $subject_program_id = $value['subject_program_id'];

                                            // $subject_program = new Subjectprogra
                                            // var_dump($rawCode);


                                            $section_subject_code = $section->CreateSectionSubjectCode(
                                                $sectionName, $rawCode);

                                            // $capacity = $value['capacity'];
                                            // $school_year_id = $value['school_year_id'];

                                            // $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);

                                            $teacher_id = $subjectSchedule->GetScheduleTeacherBySectionSubjectCode(
                                                $section_subject_code, $current_school_year_id);

                                            // var_dump($teacher_id);
                                            // var_dump($section_subject_code);
                                            // echo "<br>";

                                            $teacher = new Teacher($con, $teacher_id);

                                            $teacherName = "N/A";

                                            if($teacher_id != 0){
                                                $teacherName = ucwords($teacher->GetTeacherFirstName()) . " " . ucwords($teacher->GetTeacherLastName());
                                            }
                                            
                                            echo "
                                                <tr>
                                                     
                                                    <td>$subject_title</td>
                                                    <td>$section_subject_code</td>
                                                    <td>$teacherName</td>

                                                    <td>
                                                        <a href='subject_topics.php?id=$course_id&c=$rawCode&t_id=$teacher_id'>
                                                            <button class='btn-sm btn btn-primary'>
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
                        <?php endif;?>


                    </main>
                </div>
            </main>
            
        </div>

        <?php

    }
?>




