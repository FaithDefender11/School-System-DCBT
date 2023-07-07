<?php 

    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/Section.php');

    if(isset($_GET['id'])){

        $pending_enrollees_id = $_GET['id'];

        unset($_SESSION['pending_enrollees_id']);
        unset($_SESSION['process_enrollment']);

        $enrollment = new Enrollment($con);
        $section = new Section($con);
        
        $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

        if (!isset($_SESSION['enrollment_form_id'])) {
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
            $_SESSION['enrollment_form_id'] = $enrollment_form_id;

        } else {
            $enrollment_form_id = $_SESSION['enrollment_form_id'];
        }

        $sql = $con->prepare("SELECT * FROM pending_enrollees
                WHERE pending_enrollees_id=:pending_enrollees_id
            ");

        $sql->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        $row = null;

        $course_id = 0;

        if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $program_id = $row['program_id'];

            $firstname = $row['firstname'];

            $middle_name = $row['middle_name'];
            $lastname = $row['lastname'];
            $birthday = $row['birthday'];
            $address = $row['address'];
            $sex = $row['sex'];
            $contact_number = $row['contact_number'];
            $date_creation = $row['date_creation'];
            $student_status = $row['student_status'];
            $email = $row['email'];
            $pending_enrollees_id = $row['pending_enrollees_id'];
            $password = $row['password'];
            $civil_status = $row['civil_status'];
            $nationality = $row['nationality'];
            $age = $row['age'];
            $guardian_name = $row['guardian_name'];
            $guardian_contact_number = $row['guardian_contact_number'];
            $lrn = $row['lrn'];
            $birthplace = $row['birthplace'];
            $religion = $row['religion'];
            $email = $row['email'];
            $type = $row['type'];

            $program = $con->prepare("SELECT acronym FROM program
                WHERE program_id=:program_id
                LIMIT 1
            ");
            $program->bindValue(":program_id", $program_id);
            $program->execute();

            $program_acronym = $program->fetchColumn();

            $student_fullname = $firstname . " " . $lastname;

            $section = new Section($con, null);

            // $program_id = $section->GetProgramIdBySectionId($student_course_id);
            $strand_name = $section->GetAcronymByProgramId($program_id);
            $track_name = $section->GetTrackByProgramId($program_id);
    
            if(isset($_GET['id']) && isset($_GET['step1'])){
                ?>
                    <body>
                        <div class="content">
                        <nav>
                            <a href="SHS-find-form-evaluation.html"
                            ><i class="bi bi-arrow-return-left fa-1x"></i>
                            <h3>Back</h3>
                            </a>
                        </nav>
                        <div class="content-header">
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
                            <div class="cards">
                            <div class="card">
                                <span>Form ID</span>
                                <span>*insert*</span>
                            </div>
                            <div class="card">
                                <span>Admission type</span>
                                <span>*insert*</span>
                            </div>
                            <div class="card">
                                <span>Student no.</span>
                                <span>*insert*</span>
                            </div>
                            <div class="card">
                                <span>Status</span>
                                <span>*insert*</span>
                            </div>
                            <div class="card">
                                <span>Submitted on</span>
                                <span>*insert*</span>
                            </div>
                            </div>
                        </div>
                        <main>
                            <div class="progress">
                            <span class="dot active"><p>Check form details</p></span>
                            <span class="line active"></span>
                            <span class="dot active"><p>Find section</p></span>
                            <span class="line active"></span>
                            <span class="dot active"><p>Subject confirmation</p></span>
                            </div>
                            <div class="floating">
                            <header>
                                <div class="title">
                                <h3>*insert* subjects</h3>
                                </div>
                            </header>
                            <main>
                                <table class="a">
                                <thead>
                                    <tr>
                                    <th>Subject ID</th>
                                    <th>Subject code</th>
                                    <th>Prerequisite</th>
                                    <th>Type</th>
                                    <th>Total units</th>
                                    <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td><input type="checkbox" name="check" id="check" /></td>
                                    </tr>
                                </tbody>
                                </table>
                            </main>
                            </div>
                            <div class="action">
                            <button
                                class="default large"
                                onclick="window.location.href = 'NEW-TRANSFEREE-enrollment-2.html';"
                            >
                                Return
                            </button>
                            <button class="clean large">Confirm</button>
                            </div>
                        </main>
                        </div>
                    </body>
                <?php
            }
        }
    }
?>



 


