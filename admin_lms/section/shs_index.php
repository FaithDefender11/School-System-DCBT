<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');

    
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
    
    $school_year = new SchoolYear($con);
    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $current_school_year_id = $school_year_obj['school_year_id'];
    $current_school_year_period = $school_year_obj['period'];
    $current_school_year_term = $school_year_obj['term'];

    $section = new Section($con);

    // $enrolledSection = $section->GetAllEnrolledStudentSections($current_school_year_id);
    $sectionEnrolledStudentList = $section->GetCurrentSectionWithEnrolledStudent($current_school_year_id);

    // var_dump($enrolledSection);
    
?>



<div class="content">

    <?php echo Helper::CreateTopDepartmentTab(false, "shs_index.php", "tertiary_index.php");?>


    <main>
     
        <div class="floating" id="shs-sy">

            <header>

                <div class="title">
                    <h4>Enrolled Section</h4>
                </div>
                
            </header>

            <main>

               <?php if(count($sectionEnrolledStudentList) > 0):?>

                    <table style="width: 100%" id="enrolled_section_list" class="a" >
                        
                        <thead>
                            <tr>
                                <th>Section</th>  
                                <th>Student</th>
                                <th>Capacity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php 

                                foreach ($sectionEnrolledStudentList as $key => $value) {

                                    $program_section = $value['program_section'];
                                    $course_id = $value['course_id'];
                                    $capacity = $value['capacity'];
                                    $school_year_id = $value['school_year_id'];

                                    $totalStudent = $section->GetTotalNumberOfStudentInSection($course_id, $current_school_year_id);
                                    
                                    echo "
                                        <tr>
                                            <td>
                                                <a href='enrolled_students.php?id=$course_id&sy_id=$school_year_id' style='color: inherit'>
                                                    $program_section
                                                </a>
                                            </td>
                                            <td>$totalStudent</td>
                                            <td>$capacity</td>

                                            <td>
                                                <a href='subject_code.php?id=$course_id'>
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


