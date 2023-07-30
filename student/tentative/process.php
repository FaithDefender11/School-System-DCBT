<?php 

    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/Alert.php');
    include_once('../../includes/classes/Department.php');

    echo Helper::RemoveSidebar();
 
    ?>
         <style>
            .read_only{
                pointer-events: none;
            }
         </style>
    <?php

    // echo "Student Tentative Page";
    // echo "<br>";
    // echo $_SESSION['studentLoggedIn'];
    // echo "<br>";
    // echo $_SESSION['status'];

    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

    // echo $_SESSION['username'];
    // echo $_SESSION['status'];

    if(
        isset($_SESSION['username'])
        && isset($_SESSION['enrollee_id'])
        && isset($_SESSION['status']) 
        && $_SESSION['status'] == 'pending'
        && $_SESSION['status'] != 'enrolled'){


        $username = $_SESSION['username'];
        $enrollee_id = $_SESSION['enrollee_id'];

        $pending_enrollees_id  = $enrollee_id;

        // echo $enrollee_id;
        $pending = new Pending($con, $pending_enrollees_id);

        $sql = $con->prepare("SELECT * FROM pending_enrollees
            WHERE pending_enrollees_id=:pending_enrollees_id
            AND is_finished = 0
            AND activated = 1
            AND student_status != 'APPROVED'
            ");
        
        $sql->bindValue(":pending_enrollees_id", $pending_enrollees_id);
        $sql->execute();

        if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            $pending_enrollees_id = $row['pending_enrollees_id'];

            # STEP 1

            $check = $pending->CheckInitialStatus($pending_enrollees_id);

            if($check == false){
                // echo "ERROR 401.";
                // exit();
            }
            
            $is_finished = $row['is_finished'];


            $pending = new Pending($con, $pending_enrollees_id);

            $department = new Department($con);

            $admission_status = $pending->GetPendingAdmissionStatus();
            $pending_type = $pending->GetPendingType();
            $course_level = $pending->GetCourseLevel();
            $program_id = $pending->GetPendingProgramId();

            // echo $pending_type;

            if(isset($_GET['new_student']) && $_GET['new_student'] == "true"){

                if(isset($_GET['step']) && $_GET['step'] == "preferred_course"){

                   include_once('./preferred_course.php');
                }

                if(isset($_GET['step']) && $_GET['step'] == "enrollee_information"){
                  
                    include_once('./enrollee_information.php');
                }

                if(isset($_GET['step']) && $_GET['step'] == "enrollee_parent_information"){

                    include_once('./enrollee_parent_information.php');
                }

                if(isset($_GET['step']) && $_GET['step'] == "enrollee_summary_details"){
                    include_once('./enrollee_summary_details.php');
                }

                if(isset($_GET['step']) && $_GET['step'] == 3){

                    $get_parent = $con->prepare("SELECT * FROM parent   
                        WHERE pending_enrollees_id=:pending_enrollees_id");
                
                    $get_parent->bindValue(":pending_enrollees_id", $pending_enrollees_id);
                    $get_parent->execute();

                    $parent_id = null;
                    $parent_firstname = "";
                    $parent_lastname = "";
                    $parent_middle_name = "";
                    $parent_contact_number = "";
                    $parent_email = "";
                    $parent_occupation = "";
                    $parent_suffix = "";
                    $relationship = "";

                    $hasParentData = false;


                    $father_firstname = "";
                    $father_lastname = "";
                    $father_middle = "";
                    $father_suffix = "";
                    $father_contact_number = "";
                    $father_occupation = "";
                    $father_email = "";

                    $mother_firstname = "";
                    $mother_lastname = "";
                    $mother_middle = "";
                    $mother_suffix = "";
                    $mother_contact_number = "";
                    $mother_occupation = "";
                    $mother_email = "";



                    if($get_parent->rowCount() > 0){

                        $rowParent = $get_parent->fetch(PDO::FETCH_ASSOC);

                        $parent_id = $rowParent['parent_id'];
                        $parent_firstname = $rowParent['firstname'];
                        $parent_lastname = $rowParent['lastname'];
                        $parent_middle_name = $rowParent['middle_name'];
                        $parent_contact_number = $rowParent['contact_number'];
                        $parent_occupation = $rowParent['occupation'];
                        $parent_suffix = $rowParent['suffix'];
                        $relationship = $rowParent['relationship'];
                        // echo $parent_id;
                        $hasParentData = true;


                        $father_firstname = empty($rowParent['father_firstname']) ? '' : $rowParent['father_firstname'];
                        $father_lastname = empty($rowParent['father_lastname']) ? '' : $rowParent['father_lastname'];
                        $father_middle = empty($rowParent['father_middle']) ? '' : $rowParent['father_middle'];
                        $father_suffix = empty($rowParent['father_suffix']) ? '' : $rowParent['father_suffix'];
                        $father_contact_number = empty($rowParent['father_contact_number']) ? '' : $rowParent['father_contact_number'];
                        $father_occupation = empty($rowParent['father_occupation']) ? '' : $rowParent['father_occupation'];
                        $father_email = empty($rowParent['father_email']) ? '' : $rowParent['father_email'];


                        $mother_firstname = empty($rowParent['mother_firstname']) ? '' : $rowParent['mother_firstname'];
                        $mother_lastname = empty($rowParent['mother_lastname']) ? '' : $rowParent['mother_lastname'];
                        $mother_middle = empty($rowParent['mother_middle']) ? '' : $rowParent['mother_middle'];
                        $mother_suffix = empty($rowParent['mother_suffix']) ? '' : $rowParent['mother_suffix'];
                        $mother_contact_number = empty($rowParent['mother_contact_number']) ? '' : $rowParent['mother_contact_number'];
                        $mother_occupation = empty($rowParent['mother_occupation']) ? '' : $rowParent['mother_occupation'];
                        $mother_email = empty($rowParent['mother_email']) ? '' : $rowParent['mother_email'];
                        
                    }

                    if(isset($_POST['new_step3_btn'])){

                        $firstname = $_POST['firstname'];
                        $middle_name = $_POST['middle_name'];
                        $lastName = $_POST['lastname'];
                        $civil_status = $_POST['civil_status'];
                        $nationality = $_POST['nationality'];
                        $sex = $_POST['sex'];
                        $birthday = $_POST['birthday'];
                        $birthplace = $_POST['birthplace'];
                        $religion = $_POST['religion'];
                        $address = $_POST['address'];
                        $contact_number = $_POST['contact_number'];
                        $email = $_POST['email'];
                        $lrn = $_POST['lrn'];

                        # Check if All Necessary inputs were met.

                        $wasCompleted = $pending->CheckAllStepsComplete($pending_enrollees_id);

                        $wasSuccessValidateEnrollee = $pending->UpdatePendingNewStep3($pending_enrollees_id, $firstname, $middle_name,
                            $lastName, $civil_status, $nationality, $sex, $birthday,
                            $birthplace, $religion, $address, $contact_number, $email, $lrn);
                        
                        if($wasSuccessValidateEnrollee){

                            $validateSuccess = $pending->ValidateDetailsUpdate($pending_enrollees_id, 
                                $parent_firstname, $parent_middle_name,
                                $parent_lastname, $parent_contact_number, $parent_email,
                                $parent_occupation, $parent_suffix, $relationship,

                                $father_firstname, $father_lastname, $father_middle, $father_contact_number, $father_email,
                                $father_occupation, $father_suffix,
                            
                                $mother_firstname, $mother_lastname, $mother_middle, $mother_contact_number, $mother_email,
                                $mother_occupation, $mother_suffix);

                                if($validateSuccess){

                                    Alert::success("Validation Completed.",
                                        "process.php?new_student=true&step=4");

                                    exit();
                                }
                        }
                    }

                    $SHS =  4;

                    $student_type = "Senior High School";
                    // echo $program_id . " qweqwe  qweqwe qweqweqwe";

                    if($section->GetDepartmentIdByProgramId($program_id) != $SHS){
                        $student_type = "Tertiary";
                    }

                    $year_level = 11;
                    // echo $program_id . " qweqwe  qweqwe qweqweqwe";

                    if($section->GetDepartmentIdByProgramId($program_id) != $SHS){
                        $year_level = 1;
                    }

                    $strandName = $section->GetAcronymByProgramId($program_id);


                    ?>

                        <div class="content">
                            <nav>
                                <a href="Online-enrollment-page.html"
                                ><i class="bi bi-arrow-return-left fa-10x"></i>
                                <h3>Back</h3>
                                </a>
                            </nav>
                            <main>
                                <div class="floating noBorder">
                                <header>
                                    <div class="title">
                                    <h2 style="color: var(--titleTheme)">New Student Form</h2>
                                    <small>SY <?php echo $current_term;?></small>
                                    </div>
                                </header>
                                <div class="progress">
                                    <span class="dot active"><p>Preferred Course/Strand</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Personal Information</p></span>
                                    <span class="line active"></span>
                                    <span class="dot active"> <p>Validate Details</p></span>
                                    <span class="line inactive"></span>
                                    <span class="dot inactive"> <p>Finished</p></span>
                                </div>

                                <form method="POST">

                                    <main>
                                        <header>
                                            <div class="title">
                                                <h3>Student Information</h3>
                                                <div class="row">
                                                    <span style="margin-left: 500px;">
                                                        <small>LRN</small>
                                                        <input required style="width: 150px;" type="text" name="lrn" 
                                                        required value="<?php echo ($lrn != "") ? $lrn : ''; ?>"id="lrn">
                                                    </span>
                                                </div>
                                            </div>
                                        </header>
                                        <div class="row">
                                            <span>
                                            <label for="name">Name</label>
                                            <div>
                                                <input type="text" required name="lastname" id="lastName" required value="<?php echo ($lastname != "") ? $lastname : ''; ?>" placeholder="Last name">
                                                <small>Last name</small>
                                            </div>
                                            <div>
                                                <input type="text" required name="firstname" id="firstName" value="<?php echo ($firstname != "") ? $firstname : ''; ?>" placeholder="First name">

                                                <small>First name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="middle_name" id="middleName" value="<?php echo ($middle_name != "") ? $middle_name : ''; ?>" placeholder="Middle name">
                                                <small>Middle name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="suffix" id="suffixName" value="<?php echo ($suffix != "") ? $suffix : ''; ?>" placeholder="Suffix name">

                                                <small>Suffix name</small>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="status">Status</label>
                                            <div>
                                                <select id="status" name="civil_status" class="form-control" required>
                                                    <option value="Single"<?php echo ($civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                                    <option value="Married"<?php echo ($civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                                    <option value="Divorced"<?php echo ($civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                                    <option value="Widowed"<?php echo ($civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                                </select>
                                            </div>
                                            </span>
                                            <span>
                                            <label for="citizenship">Citizenship</label>
                                            <div>
                                                <input style="width: 220px;" type="text" name="nationality" 
                                                    required value="<?php echo ($nationality != "") ? $nationality : ''; ?>"id="nationality">
                                            </div>
                                            </span>
                                            <span>
                                            <label for="gender">Gender</label>
                                            <div>
                                                <select required name="sex" id="sex">
                                                    <option value="Male"<?php echo ($sex == "Male") ? " selected" : ""; ?>>Male</option>
                                                    <option value="Female"<?php echo ($sex == "Female") ? " selected" : ""; ?>>Female</option>
                                                </select>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="birthdate">Birthdate</label>
                                            <div>
                                            <input type="date" id="birthday" name="birthday" class="form-control" required value="<?php echo ($birthday != "") ? $birthday : "2023-06-17"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="religion">Religion</label>
                                            <div>
                                                    <input type="text" id="religion" name="religion" class="form-control" required value="<?php echo ($religion != "") ? $religion : "None"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="birthplace">Birthplace</label>
                                            <div>
                                                    <input type="text" id="birthplace" name="birthplace" class="form-control" required value="<?php echo ($birthplace != "") ? $birthplace : "Taguigarao"; ?>">

                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="address">Address</label>
                                            <div>
                                                    <input  style="text-align: start;" type="text" id="address" name="address" class="form-control" required value="<?php echo ($address != "") ? $address : "None"; ?>">

                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="phone">Phone no.</label>
                                            <div>
                                                    <input type="tel" id="contact_number" name="contact_number" class="form-control" required value="<?php echo ($contact_number != "") ? $contact_number : "09151515123"; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="email">Email</label>
                                            <div>
                                                <input readonly type="email" id="email" name="email" class="form-control" required value="<?php echo ($email != "") ? $email : ''; ?>">
                                            </div>
                                            </span>
                                        </div>

                                        <!-- FATHER DD -->

                                        <header>
                                            <div class="title">
                                            <h3>Father's Information</h3>
                                            </div>
                                        </header>

                                        <div class="row">
                                            <span>
                                                <label for="name">Name</label>
                                                <div>
                                                    <input type="text" name="father_lastname" class="form-control" required value="<?php echo htmlspecialchars($father_lastname); ?>">
                                                    <small>Last name</small>
                                                </div>
                                                <div>
                                                    <input type="text" name="father_firstname" class="form-control" required value="<?php echo htmlspecialchars($father_firstname); ?>">
                                                    <small>First name</small>
                                                </div>
                                                <div>
                                                    <input type="text" name="father_middle" class="form-control" required value="<?php echo htmlspecialchars($father_middle); ?>">
                                                    <small>Middle name</small>
                                                </div>
                                                <div>
                                                    <input type="text" name="father_suffix" class="form-control" value="<?php echo htmlspecialchars($father_suffix); ?>">
                                                    <small>Suffix name</small>
                                                </div>
                                            </span>
                                        </div>

                                        <div class="row">
                                            <span>
                                            <label for="phone">Phone no.</label>
                                            <div>
                                                <input type="tel" id="father_contact_number" name="father_contact_number" class="form-control" required value="<?php echo ($father_contact_number != "") ? $father_contact_number : ''; ?>">
                                            </div>
                                            </span>
                                            <span>
                                            <label for="email">Email</label>
                                            <div>
                                              <input type="text" id="father_email" name="father_email" class="form-control" required value="<?php echo ($father_email != "") ? $father_email : ''; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="occupation">Occupation</label>
                                            <div>
                                                <input type="text" id="father_occupation" name="father_occupation" class="form-control" value="<?php echo ($father_occupation != "") ? $father_occupation : ''; ?>">
                                            </div>
                                            </span>
                                        </div>
                                    
                                        <!-- MOTHER DD -->

                                        <header>
                                            <div class="title">
                                            <h3>Mother's Information</h3>
                                            </div>
                                        </header>

                                        <div class="row">
                                            <span>
                                            <label for="name">Name</label>
                                            <div>
                                                <input type="text" name="mother_lastname" class="form-control" required value="<?php echo $mother_lastname; ?>">

                                                <small>Last name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="mother_firstname" class="form-control" required value="<?php echo $mother_firstname; ?>">
                                                <small>First name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="mother_middle" class="form-control" required value="<?php echo $mother_middle; ?>">
                                                
                                                <small>Middle name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="mother_suffix" class="form-control" value="<?php echo $mother_suffix; ?>">
                                                
                                                <small>Suffix name</small>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="phone">Phone no.</label>
                                            <div>
                                                <input type="tel" id="mother_contact_number" name="mother_contact_number" class="form-control" required value="<?php echo ($mother_contact_number != "") ? $mother_contact_number : '0915151515123'; ?>">
                                                
                                            </div>
                                            </span>
                                            <span>
                                            <label for="email">Email</label>
                                            <div>
                                              <input type="text" id="mother_email" name="mother_email" class="form-control" required value="<?php echo ($mother_email != "") ? $mother_email : ''; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="occupation">Occupation</label>
                                            <div>
                                                <input type="text" id="mother_occupation" name="mother_occupation" class="form-control" value="<?php echo ($mother_occupation != "") ? $mother_occupation : ''; ?>">
                                            </div>
                                            </span>
                                        </div>


                                        <header>
                                            <div class="title">
                                            <h3>Guardian's Information</h3>
                                            </div>
                                        </header>


                                        <div class="row">
                                            <span>
                                            <label for="name">Name</label>
                                            <div>
                                                    <input type="text" name="parent_lastname" class="form-control" required value="<?php echo $parent_lastname; ?>">
                                                    <small>Last name</small>
                                            </div>
                                            <div>
                                                <input type="text" name="parent_firstname" class="form-control" required value="<?php echo $parent_firstname; ?>">

                                                <small>First name</small>
                                            </div>
                                            <div>
                                                                            <input type="text" name="parent_middle_name" class="form-control" required value="<?php echo $parent_middle_name; ?>">

                                                <small>Middle name</small>
                                            </div>
                                            <div>
                                                                            <input type="text" name="parent_suffix" class="form-control" value="<?php echo $parent_suffix; ?>">

                                                <small>Suffix name</small>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row">
                                            <span>
                                            <label for="phone">Phone no.</label>
                                            <div>
                                                                            <input type="tel" id="parent_contact_number" name="parent_contact_number" class="form-control" required value="<?php echo ($parent_contact_number != "") ? $parent_contact_number : '0915151515123'; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="email">Email</label>
                                            <div>
                                                                            <input type="text" id="parent_email" name="parent_email" class="form-control" required value="<?php echo ($parent_email != "") ? $parent_email : 'parent@gmail.com'; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="occupation">Occupation</label>
                                            <div>
                                                                            <input type="text" id="parent_occupation" name="parent_occupation" class="form-control" value="<?php echo ($parent_occupation != "") ? $parent_occupation : ''; ?>">

                                            </div>
                                            </span>
                                            <span>
                                            <label for="relationship">Relationship</label>
                                            <div>
                                                <input
                                                type="text"
                                                name="relationship"
                                                id="relationship"
                                                value="<?php echo $relationship;?>"
                                                />
                                            </div>
                                            </span>
                                        </div>
                                    </main>
                                    <!-- <div class="action">
                                        <button type="button"
                                        class="default large"
                                        onclick="window.location.href = 'process.php?new_student=true&step=2';"
                                        >
                                        Return
                                        </button>
                                        <button
                                        class="default large success"
                                        name="new_step3_btn" 
                                        type="submit"
                                        >
                                        Confirm
                                        </button>
                                    </div> -->

                                </form>


                                </div>
                            </main>
                        </div>
                    <?php
                }

            }

        }
        // else{
        //     echo "Route doesnt exists.";
        //     return;
        // }

        $isFinished = $pending->GetPendingIsFinished();

        if($isFinished != null && $isFinished == 1){

            if(isset($_GET['step']) && $_GET['step'] == 4){

                ?>
                    <div class="content">
                        <nav>
                            <a href="Online-enrollment-page.html"
                            ><i class="bi bi-arrow-return-left fa-10x"></i>
                            <h3>Back</h3>
                            </a>
                        </nav>
                        <main>
                            <div class="floating noBorder">
                            <header>
                                <div class="title">
                                <h2 style="color: var(--titleTheme)">New Student Form</h2>
                                <small>SY <?php echo $current_term;?></small>
                                </div>
                            </header>
                            <div class="progress">
                                <span class="dot active"><p>Preferred Course/Strand</p></span>
                                <span class="line active"></span>
                                <span class="dot active"> <p>Personal Information</p></span>
                                <span class="line active"></span>
                                <span class="dot active"> <p>Validate Details</p></span>
                                <span class="line active"></span>
                                <span class="dot active"> <p>Finished</p></span>
                            </div>
                                <div class="floating noBorder">
                                <header>
                                <div class="title">
                                    <h2>You've successfully completed your form!</h2>
                                </div>
                                </header>
                                <header>
                                <div class="title">
                                    <h3 style="color: black">What's next?</h3>
                                    <p>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                                    Laudantium, molestias culpa dicta earum cupiditate non a ipsam
                                    repellat nulla, quisquam cum cumque, iste omnis ab error. Debitis
                                    rem asperiores cumque?
                                    </p>
                                    <ul>
                                    <li>Please kindly walk in to registrar for completion your requirements</li>
                                    </ul>
                                </div>
                                </header>
                            </div>
                        </main>

                        <div style="margin-top: 10px; text-align:right;" class="col-md-11">
                            <a href="profile.php?fill_up_state=finished">
                                <button class="default large">Return to Home</button>
                            </a>
                        </div>
                    </div> 
                        
                <?php
            }

        }

    } 
?>