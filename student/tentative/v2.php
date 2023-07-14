<?php 

    include_once('../../includes/student_header.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/Section.php');
    include_once('../../includes/classes/SchoolYear.php');
    
    ?>
        <head>
            <style>
                .error {
                    border: 1px solid red;
                }
                .progress-bar {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .steps {
                    display: flex;
                }
                .step.active {
                    background-color: dodgerblue;
                    color: white;
                }
                .step {
                    flex: 1;
                    padding: 5px;
                    text-align: center;
                    background-color: lightgray;
                    font-style: normal;
                    font-weight: 500;
                    /* font-size: 18px; */
                }
                .step-content {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    padding: 1px 150px;
                    gap: 10px;
                    width: 100%;
                    height: auto;
                }
                .step1-top {
                        display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-start;
                    padding: 1px 30px;
                    width: 100%;
                    height: auto;
                }
                .steps{
                    height: 35px;
                    display: flex;
                    justify-content: center;
                    display: flex;
                    align-items: center;
                }
                .step{
                    /* font-size: 16px; */
                }

                .info-box {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    padding: 0px;
                    width: 100%;
                    height: auto;
                }

                .info-1,
                .info-2,
                .info-3,
                .info-4,
                .info-5,
                .info-6,
                .info-7 {
                    display: flex;
                    flex-direction: row;
                    align-items: flex-start;
                    padding: 10px;
                    gap: 10px;
                    width: 100%;
                    height: auto;
                }

                .info-1 input,
                .info-2 input,
                .info-3 input,
                .info-4 input,
                .info-5 input,
                .info-6 input,
                .info-7 input {
                    width: 100%;
                    text-align: center;
                    border: 1px solid #D9D9D9;
                    border-radius: 5px;
                }

                .enrollment-details {
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: flex-start;
                    padding: 0px;
                }
            </style>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        </head>
    <?php
    

    $school_year = new SchoolYear($con);
    $section = new Section($con, null);

    $school_year_obj = $school_year->GetActiveSchoolYearAndSemester();

    $school_year_id = $school_year_obj['school_year_id'];
    $current_semester = $school_year_obj['period'];
    $current_term = $school_year_obj['term'];

    if(isset($_SESSION['username'])
        && isset($_SESSION['status']) 
        && $_SESSION['status'] == 'pending'
        && $_SESSION['status'] != 'enrolled'
        ){

        $username = $_SESSION['username'];

        // echo $username;
        $pending = new Pending($con);

        $sql = $con->prepare("SELECT * FROM pending_enrollees
            WHERE firstname=:firstname");
        
        $sql->bindValue(":firstname", $username);
        $sql->execute();


        if($sql->rowCount() > 0){

            $row = $sql->fetch(PDO::FETCH_ASSOC);


            # STEP 1
            $pending_enrollees_id = empty($row['pending_enrollees_id']) ? null : $row['pending_enrollees_id'];

            $check = $pending->CheckInitialStatus($pending_enrollees_id);
            if($check == false){
                echo "ERROR 401.";
                exit();
            }

            $program_id = empty($row['program_id']) ? 0 : $row['program_id'];
            $type = empty($row['type']) ? '' : $row['type'];
            $student_status = empty($row['student_status']) ? '' : $row['student_status'];

            // STEP 2
            $lrn = empty($row['lrn']) ? '' : $row['lrn'];
            $firstname = empty($row['firstname']) ? '' : $row['firstname'];
            $middle_name = empty($row['middle_name']) ? '' : $row['middle_name'];
            $lastname = empty($row['lastname']) ? '' : $row['lastname'];
            $civil_status = empty($row['civil_status']) ? '' : $row['civil_status'];
            $nationality = empty($row['nationality']) ? '' : $row['nationality'];
            $sex = empty($row['sex']) ? '' : $row['sex'];
            $birthday = empty($row['birthday']) ? '' : $row['birthday'];
            $religion = empty($row['religion']) ? '' : $row['religion'];
            $address = empty($row['address']) ? '' : $row['address'];
            $contact_number = empty($row['contact_number']) ? '' : $row['contact_number'];
            $email = empty($row['email']) ? '' : $row['email'];
            $birthplace = empty($row['birthplace']) ? '' : $row['birthplace'];
            $suffix = empty($row['suffix']) ? '' : $row['suffix'];
            $admission_status = empty($row['admission_status']) ? '' : $row['admission_status'];
            
            $is_finished = $row['is_finished'];

                
            if(isset($_GET['new_student']) && $_GET['new_student'] == "true"){


                if(isset($_GET['step']) && $_GET['step'] == 1){

                    // Checking Initial Status
                    

                    if(isset($_POST['new_step1_btn'])){

                        $admission_type = $_POST['admission_type'];
                        $student_type = $_POST['student_type'] ?? "";
                        $program_id = $_POST['STRAND'];

                        $wasSuccess = $pending->UpdatePendingNewStep1($admission_type,
                                $student_type, $program_id, $pending_enrollees_id);

                        if($wasSuccess){

                            $step1Completed = $pending->CheckFormStep1Complete(
                                $pending_enrollees_id);

                            if($step1Completed == true){

                                Alert::success("STEP 1 Completed.", "process.php?new_student=true&step=2");
                                // header("Location: process.php?new_student=true&step=2");
                                exit();
                            }else{
                                Alert::error("All inputs are required.", "process.php?new_student=true&step=1");
                                exit();
                            }
                        }
                    }

                    ?>
                         
                        <div class="row col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header">

                                        <div class="step1-top">
                                            <h3 style="color: #EA4040;" class="mb-3">New Student Form</h3>
                                            <span class="">S.Y <?php echo $current_term;?></span>
                                        </div>
                                    </div>

                                        <div class="mt-2 progress-bar">
                                            <div class="steps">
                                            <div class="step active">Preferred Course/Strand</div>
                                            <div class="step">Personal Information</div>
                                            <div class="step">Validate Details</div>
                                            <div class="step">Finished</div>
                                            </div>
                                        </div>

                                            <form method="POST">
                                                <div class="row">
                                                    <span>Admission Type</span>
                                                    <div class="col-md-6">
                                                        <label for="">New Student</label>
                                                        <input required type="radio" name="admission_type"
                                                            value="Regular"<?php echo ($admission_status == "Standard") ? ' checked' : ''; ?>>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="">Transferee</label>
                                                        <input type="radio" name="admission_type"
                                                            value="Transferee"<?php echo ($admission_status == "Transferee") ? ' checked' : ''; ?>>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <span>Grade Level</span>
                                                    <div class="col-md-6">
                                                        <label for="">College</label>
                                                        <input required  type="radio" name="student_type"
                                                            value="Tertiary" <?php echo ($type == "Tertiary") ? ' checked' : ''; ?>>
                                                        
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="">Senior High</label>
                                                        <input required  type="radio" name="student_type"
                                                            value="SHS" <?php echo ($type == "SHS") ? ' checked' : ''; ?>>
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <span>Strand</span>
                                                    <?php echo $pending->CreateRegisterStrand($program_id);?>
                                                </div>
                                               
                                                <button type="submit" name="new_step1_btn" class="mt-2 btn btn-primary">Proceed</button>
                                            </form>
                                </div>
                            </div>
                        </div>

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
                                        <small>SY *current sy*</small>
                                        </div>
                                    </header>
                                    <div class="progress">
                                        <span class="dot active"><p>Preferred Course/Strand</p></span>
                                        <span class="line inactive"></span>
                                        <span class="dot inactive"> <p>Personal Information</p></span>
                                        <span class="line inactive"></span>
                                        <span class="dot inactive"> <p>Validate Details</p></span>
                                        <span class="line inactive"></span>
                                        <span class="dot inactive"> <p>Finished</p></span>
                                    </div>

                                    <main>
                                        <form action="">
                                        <header>
                                            <div class="title">
                                            <h3>Admission Type</h3>
                                            </div>
                                        </header>
                                        <div class="row">
                                            <span>
                                            <div class="form-element">
                                                <label for="newStudent">New Student</label>
                                                <div>
                                                <input
                                                    type="radio"
                                                    name="admissionType"
                                                    id="newStudent"
                                                    value="New Student"
                                                />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="transferee">Transferee</label>
                                                <div>
                                                <input
                                                    type="radio"
                                                    name="admissionType"
                                                    id="transferee"
                                                    value="Transferee"
                                                    onclick="showTransferee()"
                                                />
                                                </div>
                                            </div>
                                            </span>
                                        </div>
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
                                                <input
                                                    type="radio"
                                                    name="gradeLevel"
                                                    id="college"
                                                    value="College"
                                                    onclick="showTransferee()"
                                                />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="shs">Senior High</label>
                                                <div>
                                                <input
                                                    type="radio"
                                                    name="gradeLevel"
                                                    id="shs"
                                                    value="Senior High"
                                                    onclick="showTransferee()"
                                                />
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                        <header>
                                            <div
                                            class="title"
                                            id="transferee-details"
                                            style="display: none"
                                            >
                                            <h3>Transferee Details</h3>
                                            <h4 style="color: var(--titleTheme)">Year Level</h4>
                                            </div>
                                        </header>
                                        <div class="row" id="shs-checkbox" style="display: none">
                                            <span>
                                            <div class="form-element">
                                                <label for="11">11</label>
                                                <div>
                                                <input type="radio" name="yearLevel" id="11" value="11" />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="12">12</label>
                                                <div>
                                                <input type="radio" name="yearLevel" id="12" value="12" />
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                        <div class="row" id="college-checkbox" style="display: none">
                                            <span>
                                            <div class="form-element">
                                                <label for="1">1</label>
                                                <div>
                                                <input type="radio" name="yearLevel" id="1" value="1" />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="2">2</label>
                                                <div>
                                                <input type="radio" name="yearLevel" id="2" value="2" />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="3">3</label>
                                                <div>
                                                <input type="radio" name="yearLevel" id="3" value="3" />
                                                </div>
                                            </div>
                                            <div class="form-element">
                                                <label for="4">4</label>
                                                <div>
                                                <input type="radio" name="yearLevel" id="4" value="4" />
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                        <header>
                                            <div class="title">
                                            <h3>Course/Strand</h3>
                                            </div>
                                        </header>
                                        <div class="row">
                                            <span>
                                            <div class="form-element courseStrand">
                                                <div>
                                                <select name="course-strand" id="course-strand">
                                                    <option value="ABM">
                                                    Accountancy, Business and Management (ABM)
                                                    </option>
                                                    <option value="HUMMS">
                                                    Humanities and Social Science (HUMMS)
                                                    </option>
                                                    <option value="GAS">
                                                    General Academic Strand (GAS)
                                                    </option>
                                                    <option value="ICT">
                                                    Information and Communication Technology (ICT)
                                                    </option>
                                                    <option value="IA">
                                                    Industrial Arts - Consumer Electronics/Electrical
                                                    Installation Maintenance (IA)
                                                    </option>
                                                    <option value="BCM">
                                                    Bachelor of Christian Ministries (BCM)
                                                    </option>
                                                    <option value="ABE">
                                                    Bachelor of Arts in English (ABE)
                                                    </option>
                                                    <option value="BSETREP">
                                                    Bachelor of Science in Entrepreneurship (BSENTREP)
                                                    </option>
                                                    <option value="BTTE">
                                                    Bachelor of Science in Teachers Education (BTTE)
                                                    </option>
                                                    <option value="BPE">
                                                    Bachelor of Physical Education (BPE)
                                                    </option>
                                                </select>
                                                </div>
                                            </div>
                                            </span>
                                        </div>
                                        </form>
                                    </main>

                                    <div class="action">
                                        <button
                                        class="default large"
                                        onclick="window.location.href = 'New-student-form-2.html';"
                                        >
                                        Proceed
                                        </button>
                                    </div>

                                </div>
                            </main>

                        </div>

                    <?php
                }


                if(isset($_GET['step']) && $_GET['step'] == 2){

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

                    $hasParentData = false;

                    if($get_parent->rowCount() > 0){

                        $rowParnet = $get_parent->fetch(PDO::FETCH_ASSOC);

                        $parent_id = $rowParnet['parent_id'];
                        $parent_firstname = $rowParnet['firstname'];
                        $parent_lastname = $rowParnet['lastname'];
                        $parent_middle_name = $rowParnet['middle_name'];
                        $parent_contact_number = $rowParnet['contact_number'];
                        $parent_occupation = $rowParnet['occupation'];
                        $parent_suffix = $rowParnet['suffix'];
                        // echo $parent_id;
                        $hasParentData = true;
                    }

                    if(isset($_POST['new_step2_btn'])){

                        // $firstname = $_POST['firstname'];
                        // $middle_name = $_POST['middle_name'];
                        // $lastName = $_POST['lastname'];
                        // $civil_status = $_POST['civil_status'];
                        // $nationality = $_POST['nationality'];
                        // $sex = $_POST['sex'];
                        // $birthday = $_POST['birthday'];
                        // $birthplace = $_POST['birthplace'];
                        // $religion = $_POST['religion'];
                        // $address = $_POST['address'];
                        // $contact_number = $_POST['contact_number'];
                        // $email = $_POST['email'];
                        // $lrn = $_POST['lrn'];
                        // $suffix = $_POST['suffix'];

                        $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : 'None';
                        $middle_name = isset($_POST['middle_name']) ? $_POST['middle_name'] : 'None';
                        $lastName = isset($_POST['lastname']) ? $_POST['lastname'] : 'None';
                        $civil_status = isset($_POST['civil_status']) ? $_POST['civil_status'] : 'None';
                        $nationality = isset($_POST['nationality']) ? $_POST['nationality'] : 'None';
                        $sex = isset($_POST['sex']) ? $_POST['sex'] : 'None';
                        $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : 'None';
                        $birthplace = isset($_POST['birthplace']) ? $_POST['birthplace'] : 'None';
                        $religion = isset($_POST['religion']) ? $_POST['religion'] : 'None';
                        $address = isset($_POST['address']) ? $_POST['address'] : 'None';
                        $contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : 'None';
                        $email = isset($_POST['email']) ? $_POST['email'] : '';
                        $lrn = isset($_POST['lrn']) ? $_POST['lrn'] : '';
                        $suffix = isset($_POST['suffix']) ? $_POST['suffix'] : 'None';


                        $age = $pending->CalculateAge($birthday);

                        // echo "firstname: " . $firstname . "<br>";
                        // echo "middle_name: " . $middle_name . "<br>";
                        // echo "lastName: " . $lastName . "<br>";
                        // echo "civil_status: " . $civil_status . "<br>";
                        // echo "nationality: " . $nationality . "<br>";
                        // echo "sex: " . $sex . "<br>";
                        // echo "birthday: " . $birthday . "<br>";
                        // echo "birthplace: " . $birthplace . "<br>";
                        // echo "religion: " . $religion . "<br>";
                        // echo "address: " . $address . "<br>";
                        // echo "contact_number: " . $contact_number . "<br>";
                        // echo "email: " . $email . "<br>";
                        // echo "lrn: " . $lrn . "<br>";
                        // echo "suffix: " . $suffix . "<br>";
                        // echo "age: " . $age . "<br>";

                        // # If there`s a present data
                        // # it just need to update not to create another.


                        $parent_firstname = isset($_POST['parent_firstname']) ? $_POST['parent_firstname'] : '';
                        $parent_middle_name = isset($_POST['parent_middle_name']) ? $_POST['parent_middle_name'] : '';
                        $parent_lastname = isset($_POST['parent_lastname']) ? $_POST['parent_lastname'] : '';
                        $parent_contact_number = isset($_POST['parent_contact_number']) ? $_POST['parent_contact_number'] : '';
                        $parent_email = isset($_POST['parent_email']) ? $_POST['parent_email'] : '';
                        $parent_occupation = isset($_POST['parent_occupation']) ? $_POST['parent_occupation'] : '';
                        $parent_suffix = isset($_POST['parent_suffix']) ? $_POST['parent_suffix'] : '';


                        // echo "parent_firstname: " . $parent_firstname . "<br>";
                        // echo "parent_middle_name: " . $parent_middle_name . "<br>";
                        // echo "parent_lastname: " . $parent_lastname . "<br>";
                        // echo "parent_email: " . $parent_email . "<br>";
                        // echo "parent_occupation: " . $parent_occupation . "<br>";
                        // echo "parent_suffix: " . $parent_suffix . "<br>";

                        // $guardian_form_input = $pending->CreateParentData($pending_enrollees_id, 
                        //     $parent_firstname, $parent_middle_name,
                        //     $parent_lastname, $parent_contact_number, $parent_email, $parent_occupation, $parent_suffix);
                        
                        // if($guardian_form_input == true){
                        //     AdminUser::error("Parent has already been created. 
                        //         Form should be in update state.", "");
                        // }else{
                        //     echo "something went wrong";
                        // }

                        // # Student information.
                        // $wasSuccess = $pending->UpdatePendingNewStep2($pending_enrollees_id, $firstname, $middle_name,
                        //         $lastName, $civil_status, $nationality, $sex, $birthday,
                        //         $birthplace, $religion, $address, $contact_number, $email, $age, $lrn, $suffix);
                        
                        // // # Parent/Guardian's Information.
                        // $guardian_form_input = $pending->CreateParentData($pending_enrollees_id, 
                        //     $parent_firstname, $parent_middle_name,
                        //     $parent_lastname, $parent_contact_number, $parent_email, $parent_occupation, $parent_suffix);
                            
                        // if($wasSuccess){
                        //     Alert::success("STEP 2 Completed", "");
                            
                        // }

                        ###
                        // if($hasParentData == false){

                        //     $guardian_form_input = $pending->CreateParentData($pending_enrollees_id, 
                        //     $parent_firstname, $parent_middle_name,
                        //     $parent_lastname, $parent_contact_number, $parent_email, $parent_occupation, $parent_suffix);
                            
                        //     // $guardian_form_input = $pending->CreateParentData($pending_enrollees_id, 
                        //     //     $parent_firstname, $parent_middle_name,
                        //     //     $parent_lastname, $parent_contact_number);
                            
                        //     if($guardian_form_input == false){
                        //         Alert::error("Parent has already been created. Form should be in update state.", "");
                        //     }

                        //     if($wasSuccess && $guardian_form_input){

                        //         Alert::success("STEP 2 Completed",
                        //             "process.php?new_student=true&step=3");
                        //         exit();
                        //     }
                        //     else{
                        //         Alert::error("All fields must be filled-up", "");
                        //     }
                        // }

                        // else if($wasSuccess == true && $hasParentData == true){

                        //     $parentUpdateSuccess = $pending->UpdateParentData($parent_id,
                        //         $parent_firstname, $parent_middle_name,
                        //         $parent_lastname, $parent_contact_number);

                        //     $wasCompleted = $pending->CheckAllStepsComplete($pending_enrollees_id);

                        //     if($wasCompleted == true){
                        //         Alert::success("STEP 2 Modification Success",
                        //             "process.php?new_student=true&step=3");
                        //         exit();

                        //     }else{
                        //         Alert::error("All fields must be filled-up", "");
                        //     }
                        // }
                        ###

                        $wasSuccess = $pending->UpdatePendingNewStep2($pending_enrollees_id, $firstname, $middle_name,
                                $lastName, $civil_status, $nationality, $sex, $birthday,
                                $birthplace, $religion, $address, $contact_number, $email, $age, $lrn, $suffix);
                        
                        // # Parent/Guardian's Information.
                        $guardian_form_input = $pending->CreateParentDatav2($pending_enrollees_id, 
                            $parent_firstname, $parent_middle_name,
                            $parent_lastname, $parent_contact_number, $parent_email, $parent_occupation, $parent_suffix);

                        if($wasSuccess == true && $guardian_form_input == true){

                            $checkStep2StudentInfo = $pending->
                                CheckIfSuppliedPendingStep2StudentInfo($pending_enrollees_id);
                            $checkStep2Parent = $pending->CheckIfSuppliedPendingStep2ParentData($pending_enrollees_id);
                            
                            if($checkStep2StudentInfo == true && $checkStep2Parent){
                                // echo "check 2 student info all checked";

                                header("Location: process.php?new_student=true&step=3");
                            }

                        }

                        // if($guardian_form_input == true){

                        //     $checkStep2Parent = $pending->CheckIfSuppliedPendingStep2ParentData($pending_enrollees_id);

                        //     if($checkStep2Parent == true){
                        //         echo "check 2 parent checked";
                        //     }else{
                        //         echo "<br>";
                        //         echo "not step 2 parent checked";
                        //     }

                        // }else{
                        //     echo "not parent exec";
                        // }
                    }

                    ?>
                        <div class="row col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header">

                                        <div class="step1-top">
                                            <h3 style="color: #EA4040;" class="mb-3">New Student Form</h3>
                                            <span class="">S.Y <?php echo $current_term;?></span>
                                        </div>
                                    </div>

                                    <div class="mt-2 progress-bar">
                                        <div class="steps">
                                            <div class="step active">Preferred Course/Strand</div>
                                            <div class="step active">Personal Information</div>
                                            <div class="step">Validate Details</div>
                                            <div class="step">Finished</div>
                                        </div>
                                    </div>

                                    <form method="POST">
                                        <div class="student-info">
                                            <h6 style="color: #EA4040;">Student information</h6>

                                            <div class="info-1">
                                                <label for="name"> Name </label>
                                                <input type="text" required name="lastname" id="lastName" required value="<?php echo ($lastname != "") ? $lastname : ''; ?>" placeholder="Last name">
                                                <input type="text" required name="firstname" id="firstName" value="<?php echo ($firstname != "") ? $firstname : ''; ?>" placeholder="First name">
                                                <input type="text" name="middle_name" id="middleName" value="<?php echo ($middle_name != "") ? $middle_name : ''; ?>" placeholder="Middle name">
                                                <input type="text" name="suffix" id="suffixName" value="<?php echo ($suffix != "") ? $suffix : ''; ?>" placeholder="Suffix name">
                                            </div>
                                            <div class="info-2">
                                                <label for="status"> Status </label>
                                                <div class="selection-box-1">
                                                    <select id="status" name="civil_status" class="form-control" required>
                                                        <option value="Single"<?php echo ($civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                                        <option value="Married"<?php echo ($civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                                        <option value="Divorced"<?php echo ($civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                                        <option value="Widowed"<?php echo ($civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                                    </select>
                                                </div>
                                                <label for="citizenship">Citizenship</label>
                                                <input style="width: 220px;" type="text" name="nationality" 
                                                    required value="<?php echo ($nationality != "") ? $nationality : ''; ?>"id="nationality">

                                                <label for="gender"> Gender </label>
                                                <div class="selection-box-1">
                                                    <select required name="sex" id="sex">
                                                        <option value="Male"<?php echo ($sex == "Male") ? " selected" : ""; ?>>Male</option>
                                                        <option value="Female"<?php echo ($sex == "Female") ? " selected" : ""; ?>>Female</option>
                                                    </select>
                                                </div>

                                                <label for="lrn">LRN </label>
                                                <input required style="width: 100px;" type="text" name="lrn" 
                                                    required value="<?php echo ($lrn != "") ? $lrn : ''; ?>"id="lrn">

                                            </div>

                                            <div class="info-3">
                                                <label for="birthdate"> Birthdate </label>
                                                <input type="date" id="birthday" name="birthday" class="form-control" required value="<?php echo ($birthday != "") ? $birthday : "2023-06-17"; ?>">

                                                <label for="birthplade"> Birthplace </label>
                                                <input type="text" id="birthplace" name="birthplace" class="form-control" required value="<?php echo ($birthplace != "") ? $birthplace : "Taguigarao"; ?>">

                                                <label for="religion"> Religion </label>
                                                <input type="text" id="religion" name="religion" class="form-control" required value="<?php echo ($religion != "") ? $religion : "None"; ?>">

                                            </div>

                                            <div class="info-4">
                                                <label for="address"> Address </label>
                                                <input  style="text-align: start;" type="text" id="address" name="address" class="form-control" required value="<?php echo ($address != "") ? $address : "None"; ?>">
                                            </div>

                                            <div class="info-5">
                                                <label for="phoneNo"> Phone no. </label>
                                                <input type="tel" id="contact_number" name="contact_number" class="form-control" required value="<?php echo ($contact_number != "") ? $contact_number : "09151515123"; ?>">
                                                <label for="email"> Email </label>
                                                <input readonly type="email" id="email" name="email" class="form-control" required value="<?php echo ($email != "") ? $email : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="ParentGuardian-info">
                                            <h6 style="color: #EA4040;">Parent/Guardian's Information</h6>
                                            <div class="info-1">

                                                <label for="name"> Name </label>
                                                <input type="text" name="parent_lastname" class="form-control" required value="<?php echo $parent_lastname; ?>">
                                                <input type="text" name="parent_firstname" class="form-control" required value="<?php echo $parent_firstname; ?>">
                                                <input type="text" name="parent_middle_name" class="form-control" required value="<?php echo $parent_middle_name; ?>">
                                                <input type="text" name="parent_suffix" class="form-control" value="<?php echo $parent_suffix; ?>">

                                            </div>
                                            
                                            <div class="info-2">
                                                <label for="phoneNo"> Phone no. </label>
                                                <input type="tel" id="parent_contact_number" name="parent_contact_number" class="form-control" required value="<?php echo ($parent_contact_number != "") ? $parent_contact_number : '0915151515123'; ?>">
                                                <label for="email"> Email </label>
                                                <input type="text" id="parent_email" name="parent_email" class="form-control" required value="<?php echo ($parent_email != "") ? $parent_email : 'parent@gmail.com'; ?>">
                                                <label for="occupation"> Occupation </label>
                                                <input type="text" id="parent_occupation" name="parent_occupation" class="form-control" value="<?php echo ($parent_occupation != "") ? $parent_occupation : ''; ?>">
                                            </div>
                                        </div>

                                        <div style="text-align: right" class="text-right col-md-12">
                                            <a href="process.php?new_student=true&step=1">
                                                <button type="button" class="btn btn-outline-info">Return</button>
                                            </a>
                                            <button name="new_step2_btn" 
                                                type="submit" class="text-right btn btn-primary">Proceed
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>



                    <?php
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

                    $hasParentData = false;

                    if($get_parent->rowCount() > 0){

                        $rowParent = $get_parent->fetch(PDO::FETCH_ASSOC);

                        $parent_id = $rowParent['parent_id'];
                        $parent_firstname = $rowParent['firstname'];
                        $parent_lastname = $rowParent['lastname'];
                        $parent_middle_name = $rowParent['middle_name'];
                        $parent_contact_number = $rowParent['contact_number'];
                        $parent_occupation = $rowParent['occupation'];
                        $parent_suffix = $rowParent['suffix'];

                        // echo $parent_id;
                        $hasParentData = true;
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

                        if($wasCompleted == true){
                            $wasSuccess = $pending->UpdatePendingNewStep3($pending_enrollees_id, $firstname, $middle_name,
                                $lastName, $civil_status, $nationality, $sex, $birthday,
                                $birthplace, $religion, $address, $contact_number, $email, $lrn);
                            
                            if($wasSuccess){

                                Alert::success("Validation Completed.",
                                    "process.php?new_student=true&step=4");

                                exit();
                            }
                        }else{
                            // echo "All fields must be filled-up";
                            Alert::error("All fields must be filled-up", "");
                            // exit();
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
                        <div class="row col-md-12">

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header">

                                        <div class="step1-top">
                                            <h3 style="color: #EA4040;" class="mb-3">New Student Form</h3>
                                            <span class="">S.Y <?php echo $current_term;?></span>
                                        </div>
                                    </div>


                                    <?php 

                                       if($is_finished != 1){
                                        ?>
                                            <div class="mt-2 progress-bar">
                                                <div class="steps">
                                                <div class="step active">Preferred Course/Strand</div>
                                                <div class="step active">Personal Information</div>
                                                <div class="step active">Validate Details</div>
                                                <div class="step ">Finished</div>
                                                </div>
                                            </div>
                                        <?php
                                       }
                                    ?>

                                    <div class="student-info">
                                        <h6 style="color: #EA4040;">Enrollment Details</h6>
                                       
                                        <div class="info-2">
                                            <label for="status">Grade Level</label>
                                                <input style="width: 145px;" type="text" name="nationality" 
                                                    value="<?php echo $student_type; ?>">

                                             <label for="status">Admission Type</label>
                                                 <input style="width: 145px;" type="text" name="nationality" 
                                                value="New">

                                            <label for="status">Strand</label>
                                                 <input style="width: 145px;" type="text" name="nationality" 
                                                value="<?php echo $strandName;?>"
                                                id="nationality">

                                        </div>

                                        <div class="info-2">
                                            <label for="status">School Year</label>
                                            <input value="<?php echo $current_term; ?>" style="width: 145px;" type="text" >
                                                
                                            <label for="status">Year Level</label>
                                            <input value="<?php echo $year_level; ?>" style="width: 145px;" type="text" >
                                                 
                                            <label for="status">Semester</label>
                                            <input value="<?php echo $current_semester; ?>" style="width: 145px;" type="text" >
                                                  
                                        </div>
                                    </div>
                                        
                                    <form method="POST">
                                        <div class="student-info">
                                            <h6 style="color: #EA4040;">Student information</h6>

                                            <div class="info-1">
                                                <label for="name"> Name </label>
                                                <input type="text" required name="lastname" id="lastName" required value="<?php echo ($lastname != "") ? $lastname : ''; ?>" placeholder="Last name">
                                                <input type="text" required name="firstname" id="firstName" value="<?php echo ($firstname != "") ? $firstname : ''; ?>" placeholder="First name">
                                                <input type="text" name="middle_name" id="middleName" value="<?php echo ($middle_name != "") ? $middle_name : ''; ?>" placeholder="Middle name">
                                                <input type="text" name="suffix" id="suffixName" value="<?php echo ($suffix != "") ? $suffix : ''; ?>" placeholder="Suffix name">
                                            </div>
                                            <div class="info-2">
                                                <label for="status"> Status </label>
                                                <div class="selection-box-1">
                                                    <select id="status" name="civil_status" class="form-control" required>
                                                        <option value="Single"<?php echo ($civil_status == "Single") ? " selected" : ""; ?>>Single</option>
                                                        <option value="Married"<?php echo ($civil_status == "Married") ? " selected" : ""; ?>>Married</option>
                                                        <option value="Divorced"<?php echo ($civil_status == "Divorced") ? " selected" : ""; ?>>Divorced</option>
                                                        <option value="Widowed"<?php echo ($civil_status == "Widowed") ? " selected" : ""; ?>>Widowed</option>
                                                    </select>
                                                </div>
                                                <label for="citizenship">Citizenship</label>
                                                <input style="width: 220px;" type="text" name="nationality" 
                                                    required value="<?php echo ($nationality != "") ? $nationality : ''; ?>"id="nationality">

                                                <label for="gender"> Gender </label>
                                                <div class="selection-box-1">
                                                    <select required name="sex" id="sex">
                                                        <option value="Male"<?php echo ($sex == "Male") ? " selected" : ""; ?>>Male</option>
                                                        <option value="Female"<?php echo ($sex == "Female") ? " selected" : ""; ?>>Female</option>
                                                    </select>
                                                </div>

                                                <label for="lrn">LRN </label>
                                                <input required style="width: 100px;" type="text" name="lrn" 
                                                    required value="<?php echo ($lrn != "") ? $lrn : ''; ?>"id="lrn">

                                            </div>

                                            <div class="info-3">
                                                <label for="birthdate"> Birthdate </label>
                                                <input type="date" id="birthday" name="birthday" class="form-control" required value="<?php echo ($birthday != "") ? $birthday : "2023-06-17"; ?>">

                                                <label for="birthplade"> Birthplace </label>
                                                <input type="text" id="birthplace" name="birthplace" class="form-control" required value="<?php echo ($birthplace != "") ? $birthplace : "Taguigarao"; ?>">

                                                <label for="religion"> Religion </label>
                                                <input type="text" id="religion" name="religion" class="form-control" required value="<?php echo ($religion != "") ? $religion : "None"; ?>">

                                            </div>

                                            <div class="info-4">
                                                <label for="address"> Address </label>
                                                <input style="text-align: start;" type="text" id="address" name="address" class="form-control" required value="<?php echo ($address != "") ? $address : "None"; ?>">
                                            </div>

                                            <div class="info-5">
                                                <label for="phoneNo"> Phone no. </label>
                                                <input type="tel" id="contact_number" name="contact_number" class="form-control" required value="<?php echo ($contact_number != "") ? $contact_number : "09151515123"; ?>">
                                                <label for="email"> Email </label>
                                                <input type="email" id="email" name="email" class="form-control" required value="<?php echo ($email != "") ? $email : ''; ?>">
                                            </div>
                                        </div>

                                        <div class="ParentGuardian-info">
                                            <h6 style="color: #EA4040;">Parent/Guardian's Information</h6>
                                            <div class="info-1">

                                                <label for="name"> Name </label>
                                                <input type="text" id="parent_lastname" name="parent_lastname" class="form-control" required value="<?php echo ($parent_lastname != "") ? $parent_lastname : 'Surname'; ?>">
                                                <input type="text" id="parent_firstname" name="parent_firstname" class="form-control" required value="<?php echo ($parent_firstname != "") ? $parent_firstname : ''; ?>">
                                                <input type="text" id="parent_middle_name" name="parent_middle_name" class="form-control" required value="<?php echo ($parent_middle_name != "") ? $parent_middle_name : 'Z'; ?>">
                                                <input type="text" id="parent_suffix" name="parent_suffix" class="form-control" value="<?php echo ($parent_suffix != "") ? $parent_suffix : ''; ?>">

                                            </div>
                                            
                                            <div class="info-2">
                                                <label for="phoneNo"> Phone no. </label>
                                                <input type="tel" id="parent_contact_number" name="parent_contact_number" class="form-control" required value="<?php echo ($parent_contact_number != "") ? $parent_contact_number : '0915151515123'; ?>">
                                                <label for="email"> Email </label>
                                                <input type="text" id="parent_email" name="parent_email" class="form-control" required value="<?php echo ($parent_email != "") ? $parent_email : 'parent@gmail.com'; ?>">
                                                <label for="occupation"> Occupation </label>
                                                <input type="text" id="parent_occupation" name="parent_occupation" class="form-control" value="<?php echo ($parent_occupation != "") ? $parent_occupation : ''; ?>">
                                            </div>
                                        </div>

                                        <?php 

                                            if($is_finished != 1){

                                                ?>
                                                    <div style="text-align: right" class="text-right col-md-12">
                                                        <a href="process.php?new_student=true&step=2">
                                                            <button type="button" class="btn btn-outline-primary">Return</button>
                                                        </a>
                                                        <button name="new_step3_btn" 
                                                            type="submit" class="text-right btn btn-success">Confirm
                                                        </button>
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php
                }


                if(isset($_GET['step']) && $_GET['step'] == 4){

                    ?>
                       <div class="row col-md-12">
                            <div class="card" style="padding-bottom: 20px;">

                                <div class="card-header">
                                    <h5 class="mb-3">New Student Form</h5>
                                    <span class="">S.Y <?php echo $current_term;?></span>
                                    
                                </div>
                                <h3 class="text-center ">You've successfully completed your form!</h3>
                               
                            </div>
                            
                             <div style="margin-top: 10px; text-align:right;" class="col-md-11">
                                    <a href="profile.php?fill_up_state=finished">
                                        <button class="btn btn-primary">Return to Home.</button>
                                    </a>
                                </div>
                        </div> 
                    <?php
                }

            }

        }


    } 
?>