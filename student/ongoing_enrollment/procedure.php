<?php 

    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/Enrollment.php');
    include_once('../../includes/classes/SchoolYear.php');

    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

            // header("Location: procedure.php?information=show");
            // exit();

    $school_year_exec = new SchoolYear($con, $school_year_id);

    $enrollment_status = $school_year_exec->GetSYEnrollmentStatus();
    $startEnrollment = $school_year_exec->GetStartEnrollment();

    if($enrollment_status == 0 || $startEnrollment == null){
        # STart of Enrollment is not yet set now.
        echo "
            <div class='container'>
                <div class='alert alert-warning mt-4'>
                    <strong>Daehan College of Business and Technology Online Enrollment is current closed</strong> <br>Please check back later for enrollment availability.
                </div>
            </div>
            ";
        exit();
    }

    if(isset($_SESSION['username'])
        && isset($_SESSION['status']) 
        && $_SESSION['status'] != 'pending'
        && $_SESSION['status'] == 'enrolled'
        ){

            $enrollment = new Enrollment($con);
            
            $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();

            if (!isset($_SESSION['enrollment_form_id'])) {
                $enrollment_form_id = $enrollment->GenerateEnrollmentFormId();
                $_SESSION['enrollment_form_id'] = $enrollment_form_id;
                
            } else {
                $enrollment_form_id = $_SESSION['enrollment_form_id'];
            }

            $student = new Student($con, $_SESSION['username']);

            $student_id = $student->GetStudentId();

            $student_course_level = $student->GetStudentLevel($student_id);
            $student_fullname = $student->GetFullName();
            $student_firstname = $student->GetFirstName();
            $student_lastname = $student->GetLastName();
            $student_middle_name = $student->GetMiddleName();
            $date_creation = $student->GetCreation();
            $student_gender = $student->GetStudentSex();
            $student_contact = $student->GetContactNumber();
            $student_address = $student->GetStudentAddress();
            $admission_status = $student->GetAdmissionStatus();
            $student_civil_status = $student->GetCivilStatus();
            $student_nationality = $student->GetNationality();
            $student_birthday = $student->GetStudentBirthdays();
            $student_birthplace = $student->GetStudentBirthPlace();
            $student_religion = $student->GetReligion();
            $student_email = $student->GetEmail();
            $student_course_id = $student->GetStudentCurrentCourseId();

            $student_level = $student->GetStudentLevel($student_id);

            $student_lrn = $student->GetStudentLRN();
            $student_status = $student->GetStudentStatus();

            $type_status = $student->GetIsTertiary();

            $type = $type_status == 1 ? "Tertiary" : ($type_status === 0 ? "SHS" : "");

            $student_suffix = $student->GetSuffix();

            $student_unique_id = $student->GetStudentUniqueId();

            $section = new Section($con, $student_course_id);


            $student_program_section = $section->GetSectionName();
            $student_program_id = $section->GetSectionProgramId($student_course_id);
            $student_program_acronym = $section->GetAcronymByProgramId($student_program_id);

            
            if(isset($_GET['information']) && $_GET['information'] == "show"){

                ?>

                    <div class="content">

                        <main>
                            <div class="floating noBorder">
                                <header>
                                    <div class="title">
                                    <h2 style="color: var(--titleTheme)">Existing Student Form</h2>
                                    <p class="text-right mt-0">Generated Form ID: <?php echo $enrollment_form_id;?></p>
                                    </div>
                                </header>
                                <div class="progress">
                                    <span class="dot active"><p>Update Information</p></span>
                                    <span class="line inactive"></span>
                                    <span class="dot inactive"> <p>Enrollment Details</p></span>
                                    <span class="line inactive"></span>
                                    <span class="dot inactive"> <p>Validate Details</p></span>
                                    <span class="line inactive"></span>
                                    <span class="dot inactive"> <p>Finished</p></span>
                                </div>

                                <hr>
                                <form method="POST">
                                    <main>
                                        <header>
                                            <div class="title">
                                                <h3>Update Student Information</h3>
                                                <div class="row">
                                                    <span style="margin-left: 500px;">
                                                        <small>LRN</small>
                                                        <input  class="form-control" readonly style="width: 150px;" type="text" name="lrn" 
                                                            value="<?php echo ($student_lrn != "") ? $student_lrn : ''; ?>"id="lrn">
                                                    </span>
                                                </div>
                                            </div>
                                        </header>
                                        <div class="row">

                                            <span>
                                            <label for="name">Name</label>
                                            <div>
                                                <input class="form-control" type="text" required name="lastname" id="lastName" required value="<?php echo ($student_lastname != "") ? $student_lastname : ''; ?>" placeholder="Last name">
                                                <small>Last name</small>
                                            </div>

                                            <div>
                                                <input  class="form-control" type="text" required name="firstname" id="firstName" value="<?php echo ($student_firstname != "") ? $student_firstname : ''; ?>" placeholder="First name">

                                                <small>First name</small>
                                            </div>
                                            <div>
                                                <input  class="form-control" type="text" name="middle_name" id="middleName" value="<?php echo ($student_middle_name != "") ? $student_middle_name : ''; ?>" placeholder="Middle name">
                                                <small>Middle name</small>
                                            </div>
                                            <div>
                                                <input  class="form-control" type="text" name="suffix" id="suffixName" value="<?php echo ($student_suffix != "") ? $student_suffix : ''; ?>" placeholder="Suffix name">

                                                <small>Suffix name</small>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="status">Status</label>
                                            <div>
                                                <select  class="form-control" id="status" name="civil_status" class="form-control" required>
                                                    <option value="Single"<?php echo ($student_civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                                    <option value="Married"<?php echo ($student_civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                                    <option value="Divorced"<?php echo ($student_civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                                    <option value="Widowed"<?php echo ($student_civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                                </select>
                                            </div>
                                            </span>
                                            <span>
                                            <label for="citizenship">Citizenship</label>
                                            <div>
                                                <input  class="form-control" style="width: 220px;" type="text" name="nationality" 
                                                    required value="<?php echo ($student_nationality != "") ? $student_nationality : ''; ?>"id="nationality">
                                            </div>
                                            </span>
                                            <span>
                                            <label for="gender">Gender</label>
                                            <div>
                                                <select  class="form-control" required name="sex" id="sex">
                                                    <option value="Male"<?php echo ($student_gender == "Male") ? " selected" : ""; ?>>Male</option>
                                                    <option value="Female"<?php echo ($student_gender == "Female") ? " selected" : ""; ?>>Female</option>
                                                </select>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="birthdate">Birthdate</label>
                                            <div>
                                            <input type="date" id="birthday" name="birthday" class="form-control" required value="<?php echo ($student_birthday != "") ? $student_birthday : "2023-06-17"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="religion">Religion</label>
                                            <div>
                                                    <input type="text" id="religion" name="religion" class="form-control" required value="<?php echo ($student_religion != "") ? $student_religion : "None"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="birthplace">Birthplace</label>
                                            <div>
                                                    <input type="text" id="birthplace" name="birthplace" class="form-control" required value="<?php echo ($student_birthplace != "") ? $student_birthplace : "Taguigarao"; ?>">

                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="address">Address</label>
                                            <div>
                                                    <input  style="text-align: start;" type="text" id="address" name="address" class="form-control" required value="<?php echo ($student_address != "") ? $student_address : "None"; ?>">

                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="phone">Phone no.</label>
                                            <div>
                                                <input type="tel" id="contact_number" name="contact_number" class="form-control" required value="<?php echo ($student_contact != "") ? $student_contact : "09151515123"; ?>">
                                            </div>
                                            </span>
                                            <span>
                                            <label for="email">Email</label>
                                            <div>
                                                <input  class="form-control" readonly type="email" id="email" name="email" class="form-control" required value="<?php echo ($student_email != "") ? $student_email : ''; ?>">
                                            </div>
                                            </span>
                                        </div>
                                    </main>

                                    <div class="action">

                                        <button type="button" name="" class="mt-2 default success large"
                                                onclick="window.location.href = 'procedure.php?enrollment_details=show'"
                                                >Proceed
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </main>

                    </div>
                <?php
            }

            if(isset($_GET['enrollment_details']) && $_GET['enrollment_details'] == "show"){

                ?>

                    <div class="content">

                        <nav>
                            <a href="procedure.php?information=show"
                                ><i class="bi bi-arrow-return-left fa-10x"></i>
                                <h3>Back</h3>
                            </a>
                        </nav>

                        <main>
                            <div class="floating noBorder">
                                <header>
                                    <div class="title">
                                    <h2 style="color: var(--titleTheme)">Existing Student Form</h2>
                                    <p class="text-right mt-0">Generated Form ID: <?php echo $enrollment_form_id;?></p>
                                    </div>
                                </header>
                                <div class="progress">
                                    <span class="dot active"><p>Update Information</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Enrollment Details</p></span>
                                    <span class="line inactive"></span>
                                    <span class="dot inactive"> <p>Validate Details</p></span>
                                    <span class="line inactive"></span>
                                    <span class="dot inactive"> <p>Finished</p></span>
                                </div>
                                <hr>

                                <form method="POST">
                                    <main>
                                        
                                        <header>
                                            <div class="title">
                                            <h3>Grade Level</h3>
                                            </div>
                                        </header>
                                        
                                        <div class="row">
                                            <span>
                                            <div class="form-element">
                                                <label for="college">College</label>
                                                <div>
                                            <input required  type="radio" name="student_type"
                                                            value="Tertiary" <?php echo ($type == "Tertiary") ? ' checked' : ''; ?>>
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="shs">Senior High</label>
                                                <div>
                                                <input required  type="radio" name="student_type"
                                                            value="SHS" <?php echo ($type == "SHS") ? ' checked' : ''; ?>>
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                        
                                        <header>
                                            <div class="title">
                                            <h3><?php echo $type == "Tertiary " ? "Course" : ($type == "SHS" ? "Strand" : "");?></h3>
                                            </div>
                                        </header>

                                        <div class="row">
                                            <span>
                                                <div class="form-element courseStrand">
                                                    <div>
                                                        <?php echo $student->CreateRegisterStrand($student_program_id, true);?>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>

                                        <header>
                                            <div class="title" id="transferee-details">
                                            <h5 style="color: var(--titleTheme)">Year Level</h5>
                                            </div>
                                        </header>

                                        <div class="row" id="shs-checkbox">
                                            <span>
                                            <div class="form-element">
                                                <label for="11">11</label>
                                                <div>
                                                    <input type="radio" name="yearLevel" id="11" value="11" <?php echo $student_level == 11 ? 'checked' : ''; ?> />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="12">12</label>
                                                <div>
                                                    <input type="radio" name="yearLevel" id="11" value="12" <?php echo $student_level == 12 ? 'checked' : ''; ?> />

                                                </div>
                                            </div>
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span>
                                            <div class="form-element">
                                                <label for="school year">School Year</label>
                                                <div>
                                            <input required class="form-control" type="text" name="student_type"
                                                value="<?php echo $current_term;?>">
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="semester">Semester</label>
                                                <div>
                                                <input class="form-control" required  type="text" name="student_type"
                                                    value="<?php echo $current_semester;?>" <?php echo ($type == "SHS") ? ' checked' : ''; ?>>
                                                </div>
                                            </div>
                                            </span>
                                        </div>

                                    </main>

                                        <div class="action">
                                            <button type="button" name="" class="mt-2 default large"

                                            onclick="window.location.href = 'procedure.php?information=show'"
                                            >
                                            Return
                                            <button type="button" name="" class="mt-2 default success large"
                                            onclick="window.location.href = 'procedure.php?validate_details=show'"
                                            >
                                            Proceed
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </main>

                    </div>
                <?php
            }

            if(isset($_GET['validate_details']) && $_GET['validate_details'] == "show"){
                ?>

                    <div class="content">

                        <main>
                            <div class="floating noBorder">

                                <header>
                                    <div class="title">
                                    <h2 style="color: var(--titleTheme)">Existing Student Form</h2>
                                    <p class="text-right mt-0">Generated Form ID: <?php echo $enrollment_form_id;?></p>
                                    </div>
                                </header>

                                <div class="progress">
                                    <span class="dot active"><p>Update Information</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Enrollment Details</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Validate Details</p></span>
                                    <span class="line inactive"></span>
                                    <span class="dot inactive"> <p>Finished</p></span>
                                </div>
                                <hr>

                                <form method="POST">
                                    <main>

                                        <header>
                                            <div class="title">
                                            <h3>Grade Level</h3>
                                            </div>
                                        </header>
                                        
                                        <div class="row">
                                            <span>
                                            <div class="form-element">
                                                <label for="college">College</label>
                                                <div>
                                            <input required  type="radio" name="student_type"
                                                            value="Tertiary" <?php echo ($type == "Tertiary") ? ' checked' : ''; ?>>
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="shs">Senior High</label>
                                                <div>
                                                <input required  type="radio" name="student_type"
                                                            value="SHS" <?php echo ($type == "SHS") ? ' checked' : ''; ?>>
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                        
                                        <header>
                                            <div class="title">
                                                <h3><?php echo $type == "Tertiary " ? "Course" : ($type == "SHS" ? "Strand" : "");?></h3>
                                            </div>
                                        </header>

                                        <div class="row">
                                            <span>
                                                <div class="form-element courseStrand">
                                                    <div>
                                                        <?php echo $student->CreateRegisterStrand($student_program_id, true);?>
                                                    </div>
                                                </div>
                                            </span>
                                        </div>

                                        <header>
                                            <div class="title" id="transferee-details">
                                            <h5 style="color: var(--titleTheme)">Year Level</h5>
                                            </div>
                                        </header>

                                        <div class="row" id="shs-checkbox">
                                            <span>
                                            <div class="form-element">
                                                <label for="11">11</label>
                                                <div>
                                                    <input type="radio" name="yearLevel" id="11" value="11" <?php echo $student_level == 11 ? 'checked' : ''; ?> />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="12">12</label>
                                                <div>
                                                    <input type="radio" name="yearLevel" id="11" value="12" <?php echo $student_level == 12 ? 'checked' : ''; ?> />

                                                </div>
                                            </div>
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span>
                                            <div class="form-element">
                                                <label for="school year">School Year</label>
                                                <div>
                                            <input required class="form-control" type="text" name="student_type"
                                                value="<?php echo $current_term;?>">
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="semester">Semester</label>
                                                <div>
                                                <input class="form-control" required  type="text" name="student_type"
                                                    value="<?php echo $current_semester;?>" <?php echo ($type == "SHS") ? ' checked' : ''; ?>>
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                        <hr>


                                        <!--  -->

                                        <header>
                                            <div class="title">
                                                <h3>Update Student Information</h3>
                                                <div class="row">
                                                    <span style="margin-left: 500px;">
                                                        <small>LRN</small>
                                                        <input  class="form-control" readonly style="width: 150px;" type="text" name="lrn" 
                                                            value="<?php echo ($student_lrn != "") ? $student_lrn : ''; ?>"id="lrn">
                                                    </span>
                                                </div>
                                            </div>
                                        </header>

                                        <div class="row">

                                            <span>
                                            <label for="name">Name</label>
                                            <div>
                                                <input class="form-control" type="text" required name="lastname" id="lastName" required value="<?php echo ($student_lastname != "") ? $student_lastname : ''; ?>" placeholder="Last name">
                                                <small>Last name</small>
                                            </div>

                                            <div>
                                                <input  class="form-control" type="text" required name="firstname" id="firstName" value="<?php echo ($student_firstname != "") ? $student_firstname : ''; ?>" placeholder="First name">

                                                <small>First name</small>
                                            </div>
                                            <div>
                                                <input  class="form-control" type="text" name="middle_name" id="middleName" value="<?php echo ($student_middle_name != "") ? $student_middle_name : ''; ?>" placeholder="Middle name">
                                                <small>Middle name</small>
                                            </div>
                                            <div>
                                                <input  class="form-control" type="text" name="suffix" id="suffixName" value="<?php echo ($student_suffix != "") ? $student_suffix : ''; ?>" placeholder="Suffix name">

                                                <small>Suffix name</small>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="status">Status</label>
                                            <div>
                                                <select  class="form-control" id="status" name="civil_status" class="form-control" required>
                                                    <option value="Single"<?php echo ($student_civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                                    <option value="Married"<?php echo ($student_civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                                    <option value="Divorced"<?php echo ($student_civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                                    <option value="Widowed"<?php echo ($student_civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                                </select>
                                            </div>
                                            </span>
                                            <span>
                                            <label for="citizenship">Citizenship</label>
                                            <div>
                                                <input  class="form-control" style="width: 220px;" type="text" name="nationality" 
                                                    required value="<?php echo ($student_nationality != "") ? $student_nationality : ''; ?>"id="nationality">
                                            </div>
                                            </span>
                                            <span>
                                            <label for="gender">Gender</label>
                                            <div>
                                                <select  class="form-control" required name="sex" id="sex">
                                                    <option value="Male"<?php echo ($student_gender == "Male") ? " selected" : ""; ?>>Male</option>
                                                    <option value="Female"<?php echo ($student_gender == "Female") ? " selected" : ""; ?>>Female</option>
                                                </select>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="birthdate">Birthdate</label>
                                            <div>
                                            <input type="date" id="birthday" name="birthday" class="form-control" required value="<?php echo ($student_birthday != "") ? $student_birthday : "2023-06-17"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="religion">Religion</label>
                                            <div>
                                                    <input type="text" id="religion" name="religion" class="form-control" required value="<?php echo ($student_religion != "") ? $student_religion : "None"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="birthplace">Birthplace</label>
                                            <div>
                                                    <input type="text" id="birthplace" name="birthplace" class="form-control" required value="<?php echo ($student_birthplace != "") ? $student_birthplace : "Taguigarao"; ?>">

                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="address">Address</label>
                                            <div>
                                                    <input  style="text-align: start;" type="text" id="address" name="address" class="form-control" required value="<?php echo ($student_address != "") ? $student_address : "None"; ?>">

                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="phone">Phone no.</label>
                                            <div>
                                                <input type="tel" id="contact_number" name="contact_number" class="form-control" required value="<?php echo ($student_contact != "") ? $student_contact : "09151515123"; ?>">
                                            </div>
                                            </span>
                                            <span>
                                            <label for="email">Email</label>
                                            <div>
                                                <input  class="form-control" readonly type="email" id="email" name="email" class="form-control" required value="<?php echo ($student_email != "") ? $student_email : ''; ?>">
                                            </div>
                                            </span>
                                        </div>
                                    </main>

                                    <div class="action">


                                        <button type="button" name="" class="mt-2 default large"
                                                onclick="window.location.href = 'procedure.php?enrollment_details=show'"
                                                >Return
                                        </button>
                                        <button type="button" name="" class="mt-2 default success large"
                                                onclick="window.location.href = 'procedure.php?subject_summary=show'"
                                                >Proceed
                                        </button>
                                    </div>

                                </form>


                            </div>
                        </main>

                    </div>
                <?php
            }

            if(isset($_GET['subject_summary']) && $_GET['subject_summary'] == "show"){


                if(isset($_POST['apply_next_semester_os_' . $student_id])){

                    // echo $student_status;
                    // Note. registrar in evaluation can modify the selected course id.
                    # System had choose only the previous section of student in their 1st semester (As DEFAULT).
                    
                    // I HIT THIS LAST TOUCHED.
                    
                    $enrollment_request_success = $enrollment->ApplyEnrollmentOS($student_id, $student_course_id, $school_year_id,
                        $enrollment_form_id, $student_status, $type);
                    
                    if($enrollment_request_success == true){

                        Alert::success("Success applied for S.Y $current_term $current_semester Semester",
                            "application_summary.php?e_id=$enrollment_form_id&id=$student_id");
                        exit();
                        
                    }
                }
                ?>

                    <div class="content">

                        <main>
                            <div class="floating noBorder">

                                <header>
                                    <div class="title row">
                                        <h2 style="color: var(--titleTheme)">Existing Student Form</h2>
                                        <p class="text-right mt-0">Generated Form ID: <?php echo $enrollment_form_id;?></p>
                                    </div>
                                </header>

                                <div class="progress">
                                    <span class="dot active"><p>Update Information</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Enrollment Details</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Validate Details</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Finished</p></span>
                                </div>
                                <hr>

                                <form method="POST">
                                    <main>
                                        <div class="floating">

                                            <!-- REGULAR -> Populate Subjects -->
                                            <!-- IRREGULAR -> Should be evaluated by registrar. -->
                                            <?php 

                                                if($student_status == "Regular"){
                                                    
                                                    ?>
                                                        <header>
                                                            <div class="title">
                                                                <h3>To be taken subjects for this <?php echo $current_semester;?> Semester.</h3>
                                                            </div>
                                                        </header>

                                                        <form method="post">

                                                            <main>
                                                                <table class="a">
                                                                    <thead>
                                                                        <tr class="text-center"> 
                                                                            <th rowspan="2">Code</th>
                                                                            <th rowspan="2">Subject Title</th>
                                                                            <th rowspan="2">Unit</th>
                                                                            <th rowspan="2">Type</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php

                                                                            $active = "yes";

                                                                            # Only Available now.
                                                                            $sql = $con->prepare("SELECT 
                                                                            
                                                                                DISTINCT t2.subject_title, t2.subject_type, t2.unit, t2.subject_code

                                                                                FROM course AS t1
                                                                                INNER JOIN subject_program AS t2 ON t2.program_id = t1.program_id
                                                                                WHERE t2.program_id = :program_id
                                                                                AND t2.course_level = :course_level
                                                                                AND t2.semester = :semester
                                                                            ");

                                                                            $sql->bindParam(":program_id", $student_program_id);
                                                                            $sql->bindParam(":course_level", $student_level);
                                                                            $sql->bindParam(":semester", $current_semester);
                                                                            $sql->execute();
                                                                        
                                                                            if($sql->rowCount() > 0){

                                                                                while($get_course = $sql->fetch(PDO::FETCH_ASSOC)){

                                                                                    $subject_title = $get_course['subject_title'];
                                                                                    $subject_type = $get_course['subject_type'];
                                                                                    $unit = $get_course['unit'];
                                                                                    $subject_code = $get_course['subject_code'];
                                                                                    
                                                                                    echo "
                                                                                        <tr class='text-center'>
                                                                                            <td>$subject_code</td>
                                                                                            <td>$subject_title</td>
                                                                                            <td>$unit</td>
                                                                                            <td>$subject_type</td>
                                                                                        </tr>
                                                                                    ";
                                                                                    }
                                                                            }else{
                                                                                echo "
                                                                                    <div class='col-md-12'>
                                                                                        <h4 class='text-center text-muted'>No currently available section for $program_acronym</h4>
                                                                                    </div>
                                                                                ";
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </main>

                                                            <div style="margin-top: 20px;" class="action">
                                                                <button
                                                                type="button"
                                                                    class="default large"
                                                                    onclick="window.location.href = 'procedure.php?validate_details=show'">
                                                                    Return
                                                                </button>
                                                                <button
                                                                    class="default large success"
                                                                    name="apply_next_semester_os_<?php echo $student_id;?>"
                                                                    type="submit">
                                                                    Apply for Next Semester
                                                                </button>
                                                            </div>
                                                        </form>

                                                    <?php
                                                }
                                                
                                                else if($student_status == "Irregular"){
                                                    ?>
                                                        <p>Note. Enrollee personel should evaluate you to know your required subjects for this <?php echo $current_semester;?> Semester</p>

                                                        <form method="post">

                                                            <div style="margin-top: 20px;" class="action">
                                                                <button
                                                                type="button"
                                                                    class="default large"
                                                                    onclick="window.location.href = 'procedure.php?validate_details=show'">
                                                                    Return
                                                                </button>
                                                                <button
                                                                    class="default large success"
                                                                    name="apply_next_semester_os_<?php echo $student_id;?>"
                                                                    type="submit">
                                                                    Apply for Next Semester
                                                                </button>
                                                            </div>
                                                        </form>
                                                    <?php
                                                }
                                            
                                            ?>
                                            


                                        </div>
                                    </main>

                                </form>


                            </div>
                        </main>

                    </div>
                <?php
            }
    }

?>