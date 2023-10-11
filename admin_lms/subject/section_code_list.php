<?php 

    include_once('../../includes/admin_elms_header.php');
    include_once('../../includes/classes/SubjectProgram.php');


    if(isset($_GET['id'])){


        $subject_program_id = $_GET['id'];

        $subjectProgram = new SubjectProgram($con, $subject_program_id);

        $program_code = $subjectProgram->GetSubjectProgramRawCode();

        $sectionsHaveProgramCode = $subjectProgram->GetSectionsHaveProgramCode($program_code);
        // $sectionsHaveProgramCode = [];

        // echo $program_code;

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
                                <h5>Section has Program Code of: <span style="font-size: 15px;"><?php echo $program_code; ?></span></h5>
                            </div>

                        </header>
                        <main>
                            <?php if(count($sectionsHaveProgramCode) > 0):?>

                                <table id="department_table" class="a" style="margin: 0">
                                    
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Section</th>
                                            <th>Level</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php

                                            foreach ($sectionsHaveProgramCode as $key => $row) {
                                                
                                                $course_id = $row['course_id'];
                                                $program_section = $row['program_section'];
                                                $course_level = $row['course_level'];
                                                $program_code = $row['program_code'];

                                                $subject_schedule_id = $row['subject_schedule_id'];
                                                $teacher_id = $row['teacher_id'] !== NULL ? $row['teacher_id'] : 0;
                                                

                                                // echo $subject_schedule_id;

                                                

                                                echo "
                                                    <tr>
                                                        <td>$course_id</td>
                                                        <td>$program_section</td>
                                                        <td>$course_level</td>
                                                        <td>
                                                            <a href='section_code_topics.php?id=$course_id&c=$program_code&t_id=$teacher_id'>
                                                                <button class='btn btn-primary'>
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

                                <?php else:?>
                                    <div class="text-center">
                                        <h4 class="text-warning">No data found</h4>
                                    </div>
                            <?php endif;?>

                        </main>
                    </div>
                </main>
            </div>
        <?php
    }
?>