<?php 

    include_once('../../includes/admin_header.php');
    include_once('../../includes/classes/Teacher.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Schedule.php');
    include_once('../../includes/classes/Section.php');

    if(isset($_GET['id'])){

        $teacher_id = $_GET['id'];

        $teacher = new Teacher($con, $teacher_id);

        $department_name = $teacher->GetDepartmentName();
        $teacher_school_id = $teacher->GetSchoolTeacherId();
        $teacher_status = $teacher->GetStatus();
        $teacher_creation = $teacher->GetCreation();

        $firstname = $teacher->GetTeacherFirstName();
        $lastname = $teacher->GetTeacherLastName();
        $middle_name = $teacher->GetTeacherMiddleName();
        $suffix = $teacher->GetTeacherSuffix();


        $civil_status = $teacher->GetCivilStatus();
        $sex = $teacher->GetTeacherGender();
        $nationality  = $teacher->GetTeacherCitizenship();
        $religion  = $teacher->GetTeacherReligion();
        $birthplace  = $teacher->GetTeacherBirthplace();
        $address  = $teacher->GetTeacherAddress();
        $contact_number  = $teacher->GetTeacherContactNumber();
        $email  = $teacher->GetTeacherEmail();

        $school_year = new SchoolYear($con, null);
        $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

        $current_school_year_term = $school_year_obj['term'];
        $current_school_year_period = $school_year_obj['period'];
        $current_school_year_id = $school_year_obj['school_year_id'];


        ?>
        <body>
            <div class="content">
                <nav>
                    <a href="index.php">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                <div class="content-header">
                    <header>
                        <div class="title">
                            <h1><?php echo $teacher->GetTeacherFullName(); ?></h1>
                        </div>
                        <div class="action">
                            <div class="dropdown">
                                <button class="icon">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a onclick="<?php echo "removeTeacher($teacher_id) " ?>" class="dropdown-item" style="color: red;">
                                        <i class="bi bi-file-earmark-x"></i>Delete form</a>
                                </div>
                            </div>
                        </div>
                    </header>
                    <?php echo Helper::CreateTeacherTabs($teacher_school_id, $department_name, $teacher_status, $teacher_creation); ?>
                </div>
                <?php
                    if(isset($_GET['details']) && $_GET['details'] == "show"){
                        include_once('./details.php');
                    }
                    if(isset($_GET['subject_load']) && $_GET['subject_load'] == "show"){
                        include_once('./subject_load.php');
                    }
                ?>
            </div>
        </body>
        <?php

    }
        
    
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

    function removeTeacher(teacher_id){

        var teacher_id = parseInt(teacher_id);

        Swal.fire({
            icon: 'question',
            title: `Do you want to remove selected teacher`,
            text: `Important! This action is cannot be undone`,
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // REFX
                $.ajax({
                    url: '../../ajax/teacher/removeTeacher.php',
                    type: 'POST',
                    data: {
                        teacher_id
                    },
                    success: function(response) {

                        response = response.trim();

                        console.log(response);

                            Swal.fire({
                                icon: 'success',
                                title: `Selected teacher has been removed.`,
                            });

                            setTimeout(() => {
                                Swal.close();
                                // location.reload();
                                window.location.href = "index.php";
                            }, 1000);


 

                        // if (response === "success_update") {

                        //     Swal.fire({
                        //         icon: 'success',
                        //         title: `Selected form has been rejected.`,
                        //     });
                        // } 

                        // else {
                        //     console.log('Update failed');
                        // }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log('AJAX Error:', textStatus, errorThrown);
                    }
                });
            }
        });
    }
</script>