<?php 


    $selected_course_id = $_GET['selected_course_id'];

    $section = new Section($con, $selected_course_id);

    $section_subject_review_section_name = $section->GetSectionName();

    $promptSectionId = $section->CheckIdExists($selected_course_id);

    ?>
        <!-- Student Table Subject Review-->
        <div class="content">
            <nav>
                <a href="SHS-find-form-evaluation.html"
                ><i class="bi bi-arrow-return-left fa-1x"></i>
                <h3>Back</h3>
                </a>
            </nav>
            <div class="content-header">
                    <?php echo Helper::RevealStudentTypePending($type_status); ?>

                <header>
                <div class="title">
                    <h1>Enrollment form</h1>
                </div>
                <div class="action">
                    <div class="dropdown">
                    <button class="icon">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item" style="color: red"
                        ><i class="bi bi-file-earmark-x"></i>Delete form</a
                        >
                    </div>
                    </div>
                </div>
                </header>

                <?php echo Helper::ProcessStudentCards($student_enrollment_form_id,
                    $student_unique_id, $enrollment_creation, $student_new_enrollee,
                    $enrollment_is_new_enrollee, $enrollment_is_transferee); ?>

            </div>
            <main>
                <div class="progress">
                    <span class="dot active"><p>Check form details</p></span>
                    <span class="line active"></span>
                    <span class="dot active"><p>Find section</p></span>
                    <span class="line active"></span>
                    <span class="dot active"><p>Subject confirmation</p></span>
                </div>

                <?php 
                    if($student_enrollment_is_transferee == 1 
                        && $student_enrollment_is_new == 1){
                        
                        ?>

                            <div class="floating">
                                <!-- New Transferee -->
                                <header>
                                    <div class="title">
                                        <h3>Credited Subjects</h3>
                                    </div>
                                    <div class="action">
                                        <a href="find_credit_subject.php?e_id=<?php echo $student_enrollment_id;?>&st_id=<?php echo $student_id?>">
                                            <button type="button" class="large default">
                                                <i style="font-size: 13px;" class='bi bi-map'></i> Credit Section</button>
                                        </a>
                                    </div>
                                </header>

                                <main>
                                    <table class="a">
                                        <thead>
                                            <tr class="text-center"> 
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">Code</th>
                                                <th rowspan="2">Description</th>
                                                <th rowspan="2">Unit</th>
                                                <th rowspan="2">Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        
                                            <?php

                                                $sql = $con->prepare("SELECT * FROM student_subject as t1

                                                    INNER JOIN subject_program as t2 ON t2.subject_program_id=t1.subject_program_id
                                                    
                                                    WHERE t1.student_id=:student_id
                                                    AND t1.school_year_id=:school_year_id
                                                    AND t1.is_transferee=1
                                                    AND t1.is_final= 0
                                                ");

                                                $sql->bindParam(":student_id", $student_id);
                                                $sql->bindParam(":school_year_id", $current_school_year_id);
                                                // $sql->bindParam(":course_id", $selected_course_id);

                                                $sql->execute();
                                            
                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){
                                                        
                                                        $student_subject_id = $row['student_subject_id'];
                                                        $subject_id = $row['subject_program_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $unit = $row['unit'];
                                                        $subject_type = $row['subject_type'];


                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$subject_type</td>
                                                            </tr>
                                                        ";
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </main>
                            </div>

                            <div class="floating">
                                <!-- New Standard -->
                                <header>

                                    <div class="title">
                                        <h3><?php echo $section_subject_review_section_name;?> Subjects</h3>
                                    </div>

                                </header>

                                <main>
                                    <table class="a">
                                        <thead>
                                            <tr class="text-center"> 
                                                <th rowspan="2">ID</th>
                                                <th rowspan="2">Code</th>
                                                <th rowspan="2">Description</th>
                                                <th rowspan="2">Unit</th>
                                                <th rowspan="2">Type</th>
                                                <th rowspan="2">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        
                                            <?php

                                                $sql = $con->prepare("SELECT 
                                                
                                                    t2.*, t1.student_subject_id, t1.subject_code as output_subject_code,
                                                    t3.program_section
                                                
                                                    FROM student_subject as t1

                                                    INNER JOIN subject_program as t2 ON t2.subject_program_id=t1.subject_program_id
                                                    LEFT JOIN course as t3 ON t3.course_id=t1.course_id

                                                    WHERE t1.student_id=:student_id
                                                    AND t1.school_year_id=:school_year_id
                                                    AND t1.is_transferee = 0
                                                    -- AND t1.course_id=:course_id
                                                    AND t1.is_final= 0

                                                ");

                                                $sql->bindParam(":student_id", $student_id);
                                                $sql->bindParam(":school_year_id", $current_school_year_id);

                                                $sql->execute();
                                            
                                                if($sql->rowCount() > 0){

                                                    while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                        $student_subject_id = $row['student_subject_id'];
                                                        $subject_id = $row['subject_program_id'];
                                                        $subject_code = $row['subject_code'];
                                                        $subject_title = $row['subject_title'];
                                                        $unit = $row['unit'];
                                                        $subject_type = $row['subject_type'];
                                                        $program_section = $row['program_section'];
                                                        $output_subject_code = $row['output_subject_code'];
                                                        

                                                        $section_subject_code = $section->CreateSectionSubjectCode($program_section, $subject_code);

                                                        $change_section_subject_url = "change_subject.php?id=$student_subject_id";

                                                        echo "
                                                            <tr class='text-center'>
                                                                <td>$subject_id</td>
                                                                <td>$output_subject_code</td>
                                                                <td>$subject_title</td>
                                                                <td>$unit</td>
                                                                <td>$subject_type</td>
                                                                <td>
                                                                    <button 
                                                                        class='btn btn-sm btn-primary'
                                                                        onclick=\"window.location.href = '" . $change_section_subject_url . "'\"
                                                                        >
                                                                        <i class='fas fa-pencil'></i>
                                                                    </button>
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


                        <?php
                        
                    }
                ?>


                <?php 
                    if($student_enrollment_is_transferee == 0 
                        && $student_enrollment_is_new == 1){
                        
                        ?>
                        <div class="floating">

                            <!-- New Transferee -->
                            <header>

                                <div class="title">
                                    <h3><?php echo $section_subject_review_section_name;?> Subjects</h3>
                                </div>

                            </header>

                            <main>
                                <table class="a">
                                    <thead>
                                        <tr class="text-center"> 
                                            <th rowspan="2">ID</th>
                                            <th rowspan="2">Code</th>
                                            <th rowspan="2">Description</th>
                                            <th rowspan="2">Unit</th>
                                            <th rowspan="2">Type</th>
                                            <th rowspan="2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    
                                        <?php

                                            $sql = $con->prepare("SELECT 
                                            
                                                t2.*, t1.student_subject_id, t1.subject_code as output_subject_code,
                                                t3.program_section
                                            
                                                FROM student_subject as t1


                                                INNER JOIN subject_program as t2 ON t2.subject_program_id=t1.subject_program_id
                                                LEFT JOIN course as t3 ON t3.course_id=t1.course_id

                                                WHERE t1.student_id=:student_id
                                                AND t1.school_year_id=:school_year_id
                                                -- AND t1.course_id=:course_id
                                                AND t1.is_final=0

                                            ");

                                            $sql->bindParam(":student_id", $student_id);
                                            $sql->bindParam(":school_year_id", $current_school_year_id);
                                            // $sql->bindParam(":course_id", $selected_course_id);

                                            $sql->execute();
                                        
                                            if($sql->rowCount() > 0){

                                                while($row = $sql->fetch(PDO::FETCH_ASSOC)){

                                                    $student_subject_id = $row['student_subject_id'];
                                                    $subject_id = $row['subject_program_id'];
                                                    $subject_code = $row['subject_code'];
                                                    $subject_title = $row['subject_title'];
                                                    $unit = $row['unit'];
                                                    $subject_type = $row['subject_type'];
                                                    $program_section = $row['program_section'];
                                                    $output_subject_code = $row['output_subject_code'];
                                                    

                                                    $section_subject_code = $section->CreateSectionSubjectCode($program_section, $subject_code);

                                                    $change_section_subject_url = "change_subject.php?id=$student_subject_id";

                                                    echo "
                                                        <tr class='text-center'>
                                                            <td>$subject_id</td>
                                                            <td>$output_subject_code</td>
                                                            <td>$subject_title</td>
                                                            <td>$unit</td>
                                                            <td>$subject_type</td>
                                                            <td>
                                                                <button 
                                                                    class='btn btn-sm btn-primary'
                                                                    onclick=\"window.location.href = '" . $change_section_subject_url . "'\"
                                                                    >
                                                                    <i class='fas fa-pencil'></i>
                                                                </button>
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
                        <?php
                        
                    }
                ?>


 

                <div class="action">
                    <button
                        class="default large"
                        onclick="window.location.href = 'process_enrollment.php?find_section=show&st_id=<?php echo $student_id;?>&c_id=<?php echo $student_course_id;?>'">
                        Return
                    </button>

                    <button onclick='EvaluateRequest("<?php echo $student_enrollment_form_id;?>", <?php echo $student_enrollment_course_id;?>, <?php echo $current_school_year_id;?>, <?php echo $student_id;?>)' class="default clean success large">
                        Confirm
                    </button>
                </div>
            </main>
        </div>

        <script>
            function EvaluateRequest(student_enrollment_form_id,
                student_course_id, current_school_year_id, student_id){

                var student_id = parseInt(student_id);
                var student_course_id = parseInt(student_course_id);
                var current_school_year_id = parseInt(current_school_year_id);
                
                Swal.fire({
                    icon: 'question',
                    title: `Confirm Enrollment?`,
                    text: "Note: This action cannot be undone",
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'Cancel'

                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log(student_enrollment_form_id)

                            $.ajax({
                            url: '../../ajax/admission/student_enrollment_confirm.php',
                            type: 'POST',
                            data: {student_enrollment_form_id,
                                student_course_id, current_school_year_id,
                                student_id},

                            // dataType: "json",

                            success: function(response) {

                                response = response.trim();
                                console.log(response)

                                if(response == "update_success"){

                                    Swal.fire({
                                            title: "Registrar Evaluation Success",
                                            icon: "success",
                                            showCancelButton: false,
                                            confirmButtonText: "OK",

                                    }).then((result) => {
                                        if (result.isConfirmed) {


                                            // var url = `../student/record_details.php?id=${student_id}&enrolled_subject=show`;
                                            var url = `./subject_insertion_summary.php?id=${student_id}&enrolled_subject=show`;
                                            window.location.href = url;

                                        } else {
                                            // User clicked Cancel or closed the dialog
                                        }
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // handle any errors here
                            }
                        });
                    }
                });

            }
        </script>
    <?php

?>