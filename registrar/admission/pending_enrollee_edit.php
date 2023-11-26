<?php 
    include_once('../../includes/registrar_header.php');
    include_once('../../includes/classes/SchoolYear.php');
    include_once('../../includes/classes/StudentParent.php');
    include_once('../../includes/classes/Student.php');
    include_once('../../includes/classes/Pending.php');
    include_once('../../includes/classes/StudentSubject.php');
    include_once('../../includes/classes/PendingParent.php');
    include_once('../../includes/classes/EnrollmentAudit.php');

    if(isset($_GET['id'])){

        $pending_enrollees_id = $_GET['id'];

        $pending = new Pending($con, $pending_enrollees_id);
        $parent = new PendingParent($con, $pending_enrollees_id);

        $student_parent = new StudentParent($con, $pending_enrollees_id);

        $enrollmentAudit= new EnrollmentAudit($con);

        $parent_id = $parent->GetParentID();

        $firstname = $pending->GetPendingFirstName();
        $lastname = $pending->GetPendingLastName();
        $middle_name = $pending->GetPendingMiddleName();
        $suffix = $pending->GetPendingSuffix();
        $civil_status = $pending->GetPendingCivilStatus();
        $nationality = $pending->GetPendingNationality();
        $sex = $pending->GetPendingGender();
        $birthday = $pending->GetPendingBirthday();
        $birthplace = $pending->GetPendingBirthplace();
        $religion = $pending->GetPendingReligion();
        $address = $pending->GetPendingAddress();
        $email = $pending->GetPendingEmail();
        $contact_number = $pending->GetPendingContactNumber();


        $student_lrn = $pending->GetPendingLRN();

        $type_status = $pending->GetPendingType();

        // Guardian
        $parent_firstname = $parent->GetFirstName();
        $parent_lastname = $parent->GetLastName();
        $parent_middle_name = $parent->GetMiddleName();
        $parent_suffix = $parent->GetSuffix();
        $parent_contact_number = $parent->GetContactNumber();
        $parent_email = $parent->GetEmail();
        $parent_occupation = $parent->GetOccupation();
        $parent_relationship = $parent->GetGuardianRelationship();
        // 


        // Father
        $father_firstname = $parent->GetFatherFirstName();
        $father_lastname = $parent->GetFatherLastName();
        $father_middle = $parent->GetFatherMiddleName();
        $father_suffix = $parent->GetFatherSuffix();
        $father_contact_number = $parent->GetFatherContactNumber();
        $father_email = $parent->GetFatherEmail();
        $father_occupation = $parent->GetFatherOccupation();


        // Father
        $mother_firstname = $parent->GetMotherFirstName();
        $mother_lastname = $parent->GetMotherLastName();
        $mother_middle = $parent->GetMotherMiddleName();
        $mother_suffix = $parent->GetMotherSuffix();
        $mother_contact_number = $parent->GetMotherContactNumber();
        $mother_email = $parent->GetMotherEmail();
        $mother_occupation = $parent->GetMotherOccupation();


        $school_name = $parent->GetSchoolName();
        $school_address = $parent->GetSchoolAddress();
        $year_started = $parent->GetSchoolYearStarted();
        $year_ended = $parent->GetSchoolYearEnded();


        // var_dump($pending_enrollees_id);

        if(
            $_SERVER['REQUEST_METHOD'] === "POST" &&
            
            isset($_POST['update_pending_btn'])){


            // $stud = $con->prepare("SELECT 
            //     lastname, firstname, middle_name, suffix, civil_status, nationality,
            //     sex, birthday, birthplace, religion, address, contact_number, email

            //     FROM pending_enrollees WHERE pending_enrollees_id = :pending_enrollees_id"
            // );
            
            // $stud->bindParam(':pending_enrollees_id', $pending_enrollees_id);
            // $stud->execute();
            // $oldStudent = $stud->fetch(PDO::FETCH_ASSOC);

            // echo "old: ". $oldStudent['lastname'];

            // $changes = [];

            // if ($oldStudent['lastname'] !== $_POST['lastname']) {
            //     $changes['lastname'] = $oldStudent['lastname'];
            // }
            // if ($oldStudent['firstname'] !== $_POST['firstname']) {
            //     $changes['firstname'] = $oldStudent['firstname'];
            // }

            // if ($oldStudent['middle_name'] !== $_POST['middle_name']) {
            //     $changes['middle_name'] = $oldStudent['middle_name'];
            // }
            // if ($oldStudent['suffix'] !== $_POST['suffix']) {
            //     $changes['suffix'] = $oldStudent['suffix'];
            // }

            // if ($oldStudent['civil_status'] !== $_POST['civil_status']) {
            //     $changes['civil_status'] = $oldStudent['civil_status'];
            // }
            // if ($oldStudent['nationality'] !== $_POST['nationality']) {
            //     $changes['nationality'] = $oldStudent['nationality'];
            // }
            // if ($oldStudent['sex'] !== $_POST['sex']) {
            //     $changes['sex'] = $oldStudent['sex'];
            // }
            // if ($oldStudent['birthday'] !== $_POST['birthday']) {
            //     $changes['birthday'] = $oldStudent['birthday'];
            // }
            // if ($oldStudent['birthplace'] !== $_POST['birthplace']) {
            //     $changes['birthplace'] = $oldStudent['birthplace'];
            // }
            // if ($oldStudent['religion'] !== $_POST['religion']) {
            //     $changes['religion'] = $oldStudent['religion'];
            // }
            // if ($oldStudent['address'] !== $_POST['address']) {
            //     $changes['address'] = $oldStudent['address'];
            // }
            // if ($oldStudent['contact_number'] !== $_POST['contact_number']) {
            //     $changes['contact_number'] = $oldStudent['contact_number'];
            // }
            // if ($oldStudent['email'] !== $_POST['email']) {
            //     $changes['email'] = $oldStudent['email'];
            // }

            // $studentDetailsQuery = $con->prepare("SELECT 

            //     lastname, firstname, middle_name, suffix, occupation, relationship, contact_number,
            //     school_name, school_address, year_started, year_ended

            //     FROM parent WHERE pending_enrollees_id = :pending_enrollees_id"
            // );
            // $studentDetailsQuery->bindParam(':pending_enrollees_id', $pending_enrollees_id);
            // $studentDetailsQuery->execute();

            // $studentOther = $studentDetailsQuery->fetch(PDO::FETCH_ASSOC);

            // if ($studentOther['school_name'] !== $_POST['school_name']) {
            //     $changes['school_name'] = $studentOther['school_name'];
            // }
            // if ($studentOther['school_address'] !== $_POST['school_address']) {
            //     $changes['school_address'] = $studentOther['school_address'];
            // }
            // if ($studentOther['year_started'] !== $_POST['year_started']) {
            //     $changes['year_started'] = $studentOther['year_started'];
            // }
            // if ($studentOther['year_ended'] !== $_POST['year_ended']) {
            //     $changes['year_ended'] = $studentOther['year_ended'];
            // }


            // # Guardian

            // if ($studentOther['lastname'] !== $_POST['guardian_lastname']) {
            //     $changes['guardian_lastname'] = $studentOther['lastname'];
            // }
            // if ($studentOther['firstname'] !== $_POST['guardian_firstname']) {
            //     $changes['guardian_firstname'] = $studentOther['firstname'];
            // }

            // if ($studentOther['middle_name'] !== $_POST['guardian_middle_name']) {
            //     $changes['guardian_middle_name'] = $studentOther['middle_name'];
            // }
            // if ($studentOther['suffix'] !== $_POST['guardian_suffix']) {
            //     $changes['guardian_suffix'] = $studentOther['suffix'];
            // }

            // if ($studentOther['contact_number'] !== $_POST['guardian_contact']) {
            //     $changes['guardian_contact'] = $studentOther['contact_number'];
            // }
            // if ($studentOther['occupation'] !== $_POST['guardian_occupation']) {
            //     $changes['guardian_occupation'] = $studentOther['occupation'];
            // }
            // if ($studentOther['relationship'] !== $_POST['guardian_relationship']) {
            //     $changes['guardian_relationship'] = $studentOther['relationship'];
            // }

            // var_dump($changes);
            // return;

            // foreach ($changes as $field => $oldValue) {

            //     echo "<br>";
            //     // echo "oldValue: $oldValue";
            //     echo "field: $field";
            //     echo "<br>";

            //     $formatValue = "";

            //     if($field == "lastname"){
            //         $formatValue = "Last name";
            //     }
            //     if($field == "firstname"){
            //         $formatValue = "First name";
            //     }

            //     if($field == "middle_name"){
            //         $formatValue = "Middle name";
            //     }

            //     if($field == "suffix"){
            //         $formatValue = "Suffix";
            //     }

            //     if($field == "civil_status"){
            //         $formatValue = "Civil Status";
            //     }
                
            //     if($field == "nationality"){
            //         $formatValue = "Citizenship";
            //     }

            //     if($field == "sex"){
            //         $formatValue = "Gender";
            //     }


            //      if($field == "birthday"){
            //         $formatValue = "Birth day";
            //     }
                
            //     if($field == "birthplace"){
            //         $formatValue = "Birth place";
            //     }

            //     if($field == "religion"){
            //         $formatValue = "Religion";
            //     }

            //     if($field == "address"){
            //         $formatValue = "Address";
            //     }
            //     if($field == "contact_number"){
            //         $formatValue = "Phone";
            //     }

            //     if($field == "email"){
            //         $formatValue = "Email";
            //     }

            //     if($field == "school_name"){
            //         $formatValue = "School Name";
            //     }
                
            //     if($field == "school_address"){
            //         $formatValue = "School Address";
            //     }


            //     if($field == "guardian_lastname"){
            //         $formatValue = "Guardian lastname";
            //     }
                
            //     if($field == "guardian_firstname"){
            //         $formatValue = "Guardian firstname";
            //     }

            //     if($field == "guardian_middle_name"){
            //         $formatValue = "Guardian Middlename";
            //     }
                
            //     if($field == "guardian_suffix"){
            //         $formatValue = "Guardian suffix";
            //     }

            //     if($field == "guardian_contact"){
            //         $formatValue = "Guardian contact";
            //     }
                
            //     if($field == "guardian_occupation"){
            //         $formatValue = "Guardian occupation";
            //     }

            //     if($field == "guardian_relationship"){
            //         $formatValue = "Guardian relationship";
            //     }

            //     $newValue = $_POST[$field];

            //     // echo "newValue: $newValue";
            //     // echo "<br>";
            //     $description = "'Name' has been edited the $formatValue input, From '$oldValue' changed into '$newValue'";
                
            //     echo $description;
            //     echo "<br>";

            //     // $stmt = $con->prepare("INSERT INTO enrollment_audit 

            //     //     (enrollment_id, description, school_year_id, registrar_id) 
            //     //     VALUES (:enrollment_id, :description, :school_year_id, :registrar_id)
            //     // ");
            //     // $stmt->bindParam(':enrollment_id', $student_enrollment_id);
            //     // $stmt->bindParam(':description', $description);
            //     // $stmt->bindParam(':school_year_id', $current_school_year_id);
            //     // $stmt->bindParam(':registrar_id', $registrarUserId);
            //     // $stmt->execute();

            //     // $doesAuditInserted = $enrollmentAudit->EnrollmentAuditInsert(
            //     //     $student_enrollment_id,
            //     //     $description, $current_school_year_id, $registrarUserId
            //     // );
            // }

            // return;

            $student_lrn = isset($_POST['student_lrn'])  ? $_POST['student_lrn'] : NULL;
            $student_lrn = $student_lrn == "" ? NULL : $student_lrn;

            if ($student_lrn !== NULL) {
                // Validate the LRN format using a regular expression
                $lrn_pattern = '/^\d{12}$/';
                if (preg_match($lrn_pattern, $student_lrn) == false) {
                    // LRN is valid
                    // echo 'LRN is valid: ' . $student_lrn;
                    Alert::error("LRN is invalid format. It should consists of 12 digits ", "");
                    exit();
                }
            }

            // var_dump($student_lrn);
            // return;

            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $middle_name = $_POST['middle_name'];
            $suffix = $_POST['suffix'];
            $civil_status = $_POST['civil_status'];
            $nationality = $_POST['nationality'];
            $sex = $_POST['sex'];
            $birthday = $_POST['birthday'];
            $birthplace = $_POST['birthplace'];
            $religion = $_POST['religion'];
            $address = $_POST['address'];
            $contact_number = $_POST['contact_number'];
            $email = $_POST['email'];


            $guardian_firstname = $_POST['guardian_firstname'] ?? "";
            $guardian_lastname = $_POST['guardian_lastname'] ?? "";
            $guardian_middle_name = $_POST['guardian_middle_name'] ?? "";
            $guardian_suffix = $_POST['guardian_suffix'] ?? "";
            $guardian_contact = $_POST['guardian_contact'] ?? "";
            // $guardian_email = $_POST['guardian_email'] ?? "";
            $guardian_occupation = $_POST['guardian_occupation'] ?? "";
            $guardian_relationship = $_POST['guardian_relationship'] ?? "";




          

            // Father
            $father_firstname = $_POST['father_firstname'] ?? "";
            $father_lastname = $_POST['father_lastname'] ?? "";
            $father_middle = $_POST['father_middle'] ?? "";
            $father_suffix = $_POST['father_suffix'] ?? "";
            $father_contact_number = $_POST['father_contact_number'] ?? "";
            $father_email = $_POST['father_email'] ?? "";
            $father_occupation = $_POST['father_occupation'] ?? "";

          

            // Mother
            $mother_firstname = $_POST['mother_firstname'] ?? "";
            $mother_lastname = $_POST['mother_lastname'] ?? "";
            $mother_middle = $_POST['mother_middle'] ?? "";
            $mother_suffix = $_POST['mother_suffix'] ?? "";
            $mother_contact_number = $_POST['mother_contact_number'] ?? "";
            $mother_email = $_POST['mother_email'] ?? "";
            $mother_occupation = $_POST['mother_occupation'] ?? "";


            // echo "Father Information:<br>";
            // echo "First Name: " . $father_firstname . "<br>";
            // echo "Last Name: " . $father_lastname . "<br>";
            // echo "Middle Name: " . $father_middle . "<br>";
            // echo "Suffix: " . $father_suffix . "<br>";
            // echo "Contact Number: " . $father_contact_number . "<br>";
            // echo "Email: " . $father_email . "<br>";
            // echo "Occupation: " . $father_occupation . "<br>";

            // echo "<br>Mother Information:<br>";
            // echo "First Name: " . $mother_firstname . "<br>";
            // echo "Last Name: " . $mother_lastname . "<br>";
            // echo "Middle Name: " . $mother_middle . "<br>";
            // echo "Suffix: " . $mother_suffix . "<br>";
            // echo "Contact Number: " . $mother_contact_number . "<br>";
            // echo "Email: " . $mother_email . "<br>";
            // echo "Occupation: " . $mother_occupation . "<br>";

            // return;

            $editPendingtExec = $pending->UpdatePendingEnrolleeDetails(
                $pending_enrollees_id,
                $firstname,
                $lastname,
                $middle_name,
                $suffix,
                $civil_status,
                $nationality,
                $sex,
                $birthday,
                $birthplace,
                $religion,
                $address,
                $contact_number,
                $email,
                $student_lrn);

            $editParentExec = $parent->UpdatePendingParent(
                $pending_enrollees_id, $parent_id, $guardian_firstname, $guardian_lastname,
                $guardian_middle_name, $guardian_suffix, $guardian_contact,
                "", $guardian_occupation, $guardian_relationship,

                # Remove
                $father_firstname,
                $father_lastname,
                $father_middle,
                $father_suffix,
                $father_contact_number,
                $father_email,
                $father_occupation,
                $mother_firstname,
                $mother_lastname,
                $mother_middle,
                $mother_suffix,
                $mother_contact_number,
                $mother_email,
                $mother_occupation);

            if($editPendingtExec || $editParentExec){
                Alert::success("Successfully save Changes", "");
                exit();
            }
            
        }


        ?>
            <div class="content">
                <nav>
                    <a href="process_enrollment.php?enrollee_details=true&id=<?php echo $pending_enrollees_id;?>">
                        <i class="bi bi-arrow-return-left fa-1x"></i>
                        <h3>Back</h3>
                    </a>
                </nav>
                
                <main>

                    <div class="floating">

                        <header class="mt-4">
                            <div class="title">
                                <h4 style="font-weight: bold;">Enrollee Information</h4>
                            </div>
                            
                        </header>

                        <form method="POST">

                            <?php if($type_status == "SHS"):?>


                                <?php if(isset($_GET['update_lrn'])
                                    && $_GET['update_lrn'] == "true"
                                    ):?>
                                    <span>* Please fill up LRN to enable process student of form.</span>

                                <?php endif;?>

                                <div class="row">
                                    <span style="margin-left: 460px;">
                                        <label for="student_lrn">LRN *</label>
                                        <input class="form-control" placeholder="" style="width: 250px;" id="student_lrn" type="text" name="student_lrn" value="<?php echo $student_lrn;?>">
                                    </span>
                                </div>
                            <?php endif;?>

                            <main>
                                <div class="row">
                                <span>
                                    <label for="name">Name</label>
                                    <div>
                                    <input type="text" name="lastname" id="lastname" value="<?php echo $lastname;?>" class="form-control" />
                                    <small>Last name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="firstname" id="firstname" value="<?php echo $firstname;?>" class="form-control" />
                                    <small>First name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="middle_name" id="middle_name" value="<?php echo $middle_name;?>" class="form-control" />
                                    <small>Middle name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="suffix" id="suffix" value="<?php echo $suffix;?>" class="form-control" />
                                    <small>Suffix</small>
                                    </div>
                                </span>
                                </div>

                                <div class="row">
                                <span>
                                    <label for="status">Status</label>
                                    <div>
                                    <select name="civil_status" id="civil_status" class="form-control">
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
                                    <input type="text" name="nationality" id="nationality" value="<?php echo $nationality;?>" class="form-control" />
                                    </div>
                                </span>

                                <span>
                                    <label for="sex">Gender</label>
                                    <div>
                                    <select name="sex" id="sex" class="form-control">
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
                                    <input type="date" name="birthday" id="birthday" value="<?php echo $birthday;?>" class="form-control" />
                                    </div>
                                </span>
                                <span>
                                    <label for="birthplace">Birthplace</label>
                                    <div>
                                    <input type="text" name="birthplace" id="birthplace" value="<?php echo $birthplace;?>" class="form-control" />
                                    </div>
                                </span>
                                <span>
                                    <label for="religion">Religion</label>
                                    <div>
                                    <input type="text" name="religion" id="religion" value="<?php echo $religion;?>" class="form-control" />
                                    </div>
                                </span>
                                </div>

                                <div class="row">
                                <span>
                                    <label for="address">Address</label>
                                    <div>
                                    <input type="text" name="address" id="address" value="<?php echo $address;?>" class="form-control" />
                                    </div>
                                </span>
                                </div>

                                <div class="row">
                                <span>
                                    <label for="phoneNo">Phone no.</label>
                                    <div>
                                    <input type="text" name="contact_number" id="contact_number" value="<?php echo $contact_number;?>" class="form-control" />
                                    </div>
                                </span>
                                <span>
                                    <label for="email">Email</label>
                                    <div>
                                    <input type="email" name="email" id="email" value="<?php echo $email;?>" class="form-control" />
                                    </div>
                                </span>
                                </div>
                            </main>

                            <br>
                            <div id="previous_school">
                                <header>
                                    <div class="title">
                                        <h4 style="font-weight: bold;">Previous School Information</h4>
                                    </div>
                                </header>

                                <div class="row">
                                    <span>
                                        <label for="school_name">School Name</label>
                                        <div>
                                            <input required type="text" id="school_name" name="school_name" class="read_only form-control" 
                                            value="<?php echo $school_name; ?>">
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <label for="school_address">Address</label>
                                        <div>
                                            <input required type="text" id="school_address" name="school_address"
                                            class="read_only form-control" value="<?php echo $school_address; ?>">
                                        </div>
                                    </span>
                                </div>
                                <div class="row">
                                    <span>
                                        <label for="year_started">Admission Year</label>
                                        <div>
                                            <input required type="text" id="year_started" name="year_started"
                                            class="read_only form-control" value="<?php echo $year_started;?>">
                                        </div>
                                    </span>

                                    <span>
                                        <label for="year_ended">Graduation Year</label>
                                        <div>
                                            <input  required type="text" id="year_ended" name="year_ended" 
                                            class="read_only form-control" value="<?php echo $year_ended;?>">
                                        </div>
                                    </span>
                                </div>

                            </div>

                            <hr>
                            <header>
                                <div class="title mb-2 mt-2">
                                    <h4 style="font-weight: bold;">Guardian Information</h4>
                                </div>
                            </header>

                            <main>
                                <div class="row">
                                <span>
                                    <label for="name">Name</label>
                                    <div>
                                    <input type="text" name="guardian_firstname" id="guardian_firstname" value="<?php echo $parent_lastname;?>" class="form-control" />
                                    <small>Last name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="guardian_lastname" id="guardian_lastname" value="<?php echo $parent_firstname;?>" class="form-control" />
                                    <small>First name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="guardian_middle_name" id="guardian_middle_name" value="<?php echo $parent_middle_name;?>" class="form-control" />
                                    <small>Middle name</small>
                                    </div>
                                    <div>
                                    <input type="text" name="guardian_suffix" id="guardian_suffix" value="<?php echo $parent_suffix;?>" class="form-control" />
                                    <small>Suffix</small>
                                    </div>
                                </span>
                                </div>

                                <div class="row">
                                    <span>
                                        <label for="phoneNo">Phone no.</label>
                                        <div>
                                        <input type="text" name="guardian_contact" id="guardian_contact" value="<?php echo $parent_contact_number;?>" class="form-control" />
                                        </div>
                                    </span>
                                    
                                </div>

                                <div class="row">

                                    <span>
                                        <label for="relationship">Relationship</label>
                                        <div>
                                        <input type="text" name="guardian_relationship" id="guardian_relationship" value="<?php echo $parent_relationship;?>" class="form-control" />
                                        </div>
                                    </span>

                                    <span>
                                        <label for="occupation">Occupation</label>
                                        <div>
                                        <input type="text" name="guardian_occupation" id="guardian_occupation" value="<?php echo $parent_occupation;?>" class="form-control" />
                                        </div>
                                    </span>

                                </div>
                            </main>

                            <div class="action modal-footer">
                                <button name="update_pending_btn"
                                    type="submit" class="default large success">
                                    Update & Save
                                </button>
                            </div>
                        </form>
                    </div>
                </main>
            </div>
        <?php
    }

?>



