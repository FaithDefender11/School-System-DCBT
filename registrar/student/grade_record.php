<?php 

    ?>

        <div class="content">

            <nav>
                <a href="index.php"><i class="bi bi-arrow-return-left fa-1x"></i>
                    <h3>Back</h3>
                </a>
            </nav>

            <div class="content-header">
                <?php echo Helper::RevealStudentTypePending($type); ?>

                <header>
                    
                    <div class="title">
                        <h2><?php echo $student->GetLastName();?>, <?php echo $student->GetFirstName();?> <?php echo $student->GetMiddleName();?> <?php echo $student->GetSuffix();?></h2>
                    </div>

                    <div class="action">
                        <div class="dropdown">

                            <button class="icon">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a href="#" class="dropdown-item" style="color: red">
                                    <i class="bi bi-file-earmark-x"></i>Delete form
                                </a>
                            </div>
                            
                        </div>
                    </div>
                </header>

                
                <?php 
                    echo Helper::CreateStudentTabs($student_unique_id, $student_level,
                        $type, $section_acronym, $student_active_status,
                        $enrollment_date);
                ?>

            </div>

            <div class="tabs">

                <?php
                    echo "
                        <button class='tab' 
                            style='background-color: var(--them); color: white'
                            onclick=\"window.location.href = 'record_details.php?id=$student_id&details=show';\">
                            
                            <i class='bi bi-clipboard-check'></i>
                            Student Details
                        </button>
                    ";

                    echo "
                        <button class='tab' 
                            id='shsPayment'
                            style='background-color: var(--mainContentBG); color: black'
                            onclick=\"window.location.href = 'record_details.php?id=$student_id&grade_records=show';\">
                            <i class='bi bi-book'></i>
                            Grade Records
                        </button>
                    ";

                    echo "
                        <button class='tab' 
                            id='shsPayment'
                            style='background-color: var(--them); color: white'
                            onclick=\"window.location.href = 'record_details.php?id=$student_id&enrolled_subject=show';\">
                            <i class='bi bi-collection icon'></i>
                            Enrolled Subjects
                        </button>
                    ";
                ?>
            </div>


            <?php 
                // If student is a SHS
                if($student_type == 0){

                    include_once('./grade_record_shs.php');

                }
                
                if($student_type == 1){
                    include_once('./grade_record_tertiary.php');
                }
            ?>
 
        </div>
    <?php

?>

<script>
    var dropBtns = document.querySelectorAll(".icon");

    dropBtns.forEach(btn => {
        btn.addEventListener("click", (e) => {
            const dropMenu = e.currentTarget.nextElementSibling;
            if (dropMenu.classList.contains("show")) {
                dropMenu.classList.toggle("show");
            } else {
                document.querySelectorAll(".dropdown-menu").forEach(item => item.classList.remove("show"));
                dropMenu.classList.add("show");
            }
        });
    });
</script>